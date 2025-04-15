var calendar; // declarar afuera

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendario');

    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: ''
        },
        allDaySlot: false,
        slotDuration: "00:15:00",           // más granularidad para evitar cortes extraños
        slotLabelInterval: "00:45:00",      // mostramos solo bloques de 45 min
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

            document.getElementById("modalReserva").style.display = "block";
            document.getElementById("fecha").value = info.startStr.split("T")[0];
            document.getElementById("hora_inicio").value = info.startStr.split("T")[1].substring(0, 5);
            document.getElementById("hora_fin").value = info.endStr.split("T")[1].substring(0, 5);

            calendar.setOption('selectable', false);
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

    // Reemplazar los labels por bloques escolares personalizados
    setTimeout(() => {
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
                el.innerHTML = '&nbsp;'; // espacio no rompible para no colapsar
            }
        });
    }, 0);
});
// Cerrar modal y restaurar la capacidad de seleccionar bloques
function cerrarModal() {
    document.getElementById("modalReserva").style.display = "none";

    // Restaurar la selección en el calendario
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
    e.preventDefault(); // Evita recargar

    const datos = new FormData(this);

    fetch("php/guardar_reserva.php", {
        method: "POST",
        body: datos
    })
    .then(res => res.text())
    .then(respuesta => {
        alert(respuesta);
        document.getElementById("modalReserva").style.display = "none";
        calendar.refetchEvents(); // Recarga los eventos del calendario
        
    });
});