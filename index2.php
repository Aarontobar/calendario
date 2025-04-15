<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario Escolar</title>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/locales-all.global.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        #calendario {
            max-width: 1000px;
            margin: 0 auto;
        }

        #modalReserva {
            display: none;
            position: fixed;
            background: rgba(0,0,0,0.5);
            top: 0; left: 0;
            width: 100%; height: 100%;
            justify-content: center;
            align-items: center;
        }

        #modalReserva form {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <h1>Calendario Escolar</h1>
    <div id="calendario"></div>

    <!-- Modal -->
    <div id="modalReserva">
        <form id="formReserva">
            <label>Fecha: <input type="date" id="fecha" name="fecha" required></label><br>
            <label>Inicio: <input type="time" id="hora_inicio" name="hora_inicio" required></label><br>
            <label>Fin: <input type="time" id="hora_fin" name="hora_fin" required></label><br>
            <label>Asignatura:
                <select id="asignaturaSelect" name="asignatura_id" required></select>
            </label><br>
            <button type="submit">Reservar</button>
            <button type="button" onclick="cerrarModal()">Cancelar</button>
        </form>
    </div>

    <script>
        var calendar;

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendario');

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                allDaySlot: false,
                slotDuration: "00:15:00",
                slotLabelInterval: "00:45:00",
                slotMinTime: "08:00:00",
                slotMaxTime: "17:05:00",
                hiddenDays: [0, 6],
                selectable: true,
                selectOverlap: false,
                selectConstraint: {
                    startTime: '08:00',
                    endTime: '17:05'
                },
                select: function (info) {
                    if (
                        info.startStr.includes('09:30') ||
                        info.startStr.includes('11:15') ||
                        info.startStr.includes('13:00') ||
                        info.startStr.includes('15:25')
                    ) {
                        return; // No permitir selección en recreos
                    }

                    document.getElementById("modalReserva").style.display = "flex";
                    document.getElementById("fecha").value = info.startStr.split("T")[0];
                    document.getElementById("hora_inicio").value = info.startStr.split("T")[1].substring(0, 5);
                    document.getElementById("hora_fin").value = info.endStr.split("T")[1].substring(0, 5);

                    calendar.setOption('selectable', false);
                },
                viewDidMount: function () {
                    const bloques = [
                        { inicio: '08:00', fin: '08:45' },
                        { inicio: '08:45', fin: '09:30' },
                        { inicio: '09:45', fin: '10:30' },
                        { inicio: '10:30', fin: '11:15' },
                        { inicio: '11:30', fin: '12:15' },
                        { inicio: '12:15', fin: '13:00' },
                        { inicio: '13:55', fin: '14:40' },
                        { inicio: '14:40', fin: '15:25' },
                        { inicio: '15:35', fin: '16:20' },
                        { inicio: '16:20', fin: '17:05' }
                    ];

                    document.querySelectorAll('.fc-timegrid-slot-label').forEach((el, index) => {
                        if (bloques[index]) {
                            el.innerHTML = `${bloques[index].inicio} - ${bloques[index].fin}`;
                        } else {
                            el.innerHTML = '&nbsp;';
                        }
                    });
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    fetch('php/obtener_reservas.php')
                        .then(res => res.json())
                        .then(reservas => {
                            const recreos = [
                                {
                                    daysOfWeek: [1, 2, 3, 4, 5],
                                    startTime: '09:30:00',
                                    endTime: '09:45:00',
                                    display: 'background',
                                    color: '#f0f0f0'
                                },
                                {
                                    daysOfWeek: [1, 2, 3, 4, 5],
                                    startTime: '11:15:00',
                                    endTime: '11:30:00',
                                    display: 'background',
                                    color: '#f0f0f0'
                                },
                                {
                                    daysOfWeek: [1, 2, 3, 4, 5],
                                    startTime: '13:00:00',
                                    endTime: '13:55:00',
                                    display: 'background',
                                    color: '#f0f0f0'
                                },
                                {
                                    daysOfWeek: [1, 2, 3, 4, 5],
                                    startTime: '15:25:00',
                                    endTime: '15:35:00',
                                    display: 'background',
                                    color: '#f0f0f0'
                                }
                            ];

                            successCallback([...reservas, ...recreos]);
                        })
                        .catch(err => {
                            console.error("Error al cargar eventos:", err);
                            failureCallback(err);
                        });
                }
            });

            calendar.render();
        });

        function cerrarModal() {
            document.getElementById("modalReserva").style.display = "none";
            if (calendar) {
                calendar.setOption('selectable', true);
            }
        }

        fetch('php/obtener_asignaturas.php')
            .then(res => res.json())
            .then(asignaturas => {
                const select = document.getElementById("asignaturaSelect");
                asignaturas.forEach(asignatura => {
                    const option = document.createElement("option");
                    option.value = asignatura.id;
                    option.textContent = asignatura.nombre;
                    select.appendChild(option);
                });
            });

        document.getElementById("formReserva").addEventListener("submit", function (e) {
            e.preventDefault();

            const datos = new FormData(this);

            fetch("php/guardar_reserva.php", {
                method: "POST",
                body: datos
            })
            .then(res => res.text())
            .then(respuesta => {
                alert(respuesta);
                document.getElementById("modalReserva").style.display = "none";
                calendar.refetchEvents();
            });
        });
    </script>
</body>
</html>