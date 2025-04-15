<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva sala</title>
    <link rel="icon" type="image/png" href="img/logo.png">

    <!-- FullCalendar CDN -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

    <!-- Encabezado con logo -->
    <header>
        <img src="img/logo.png" alt="Logo Colegio" class="logo">
        <h1>Reserva sala</h1>
    </header>

    <!-- Calendario -->
    <main>
        <div id="calendario"></div>
    </main>

    <!-- Modal de reserva (inicialmente oculto) -->
    <div id="modalReserva" class="modal" style="display: none;">
        <div class="modal-contenido">
            <h3>Reservar bloque</h3>
            <form id="formReserva">
                <label>Asignatura:</label>
                <select name="asignatura_id" id="asignaturaSelect" required></select>
                <label>Profesor:</label>
                <input type="text" name="profesor" required>
                <input type="hidden" name="fecha" id="fecha">
                <input type="hidden" name="hora_inicio" id="hora_inicio">
                <input type="hidden" name="hora_fin" id="hora_fin">
                <button type="submit">Guardar</button>
                <button type="button" onclick="cerrarModal()">Cancelar</button>
            </form>
        </div>
    </div>

    <!-- Script principal -->
    <script src="js/main3.js"></script>
</body>
</html>