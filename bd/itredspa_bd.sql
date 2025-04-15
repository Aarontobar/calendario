CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    asignatura VARCHAR(100) NOT NULL,
    profesor VARCHAR(100) NOT NULL
);

CREATE TABLE asignaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    color VARCHAR(20) NOT NULL -- ejemplo: '#3A87AD'
);

ALTER TABLE reservas 
ADD COLUMN asignatura_id INT NOT NULL,
DROP COLUMN asignatura,
ADD CONSTRAINT fk_asignatura FOREIGN KEY (asignatura_id) REFERENCES asignaturas(id);

INSERT INTO asignaturas (nombre, color) VALUES
('Rutina de Inicio y formación de hábitos Higiénicos', '#0074D9'),
('Hora del Cuento', '#FF4136'),
('Lenguaje Verbal', '#2ECC40'),
('Taller de Deportes', '#FF851B'),
('Pensamiento Matemático', '#B10DC9'),
('Exploración del Entorno Natural', '#39CCCC'),
('Taller de Inglés', '#FFDC00'),
('Juego Estructurado', '#85144b'),
('Rutina de Hábitos Higiénicos', '#7FDBFF'),
('Juego de Rincones', '#3D9970'),
('Comprensión del Entorno Social', '#F012BE'),
('Taller Desarrollo Sostenible', '#111111'),
('Plan Socioemocional', '#AAAAAA'),
('Lenguaje y Comunicación', '#0074D9'),
('Interculturalidad', '#FF4136'),
('Matemática', '#2ECC40'),
('Ciencias Naturales', '#FF851B'),
('Inglés', '#B10DC9'),
('Inglés (Plataforma Oxford)', '#39CCCC'),
('Historia, Geografía y Cs Sociales', '#FFDC00'),
('Educación Física y Salud', '#85144b'),
('Tecnología', '#7FDBFF'),
('Artes Visuales', '#3D9970'),
('Música', '#F012BE'),
('Orientación', '#111111'),
('Filosofía', '#AAAAAA'),
('Diseño y Arquitectura', '#0074D9'),
('Ciencias del Ejercicio Físico', '#FF4136'),
('Estética', '#2ECC40'),
('Geografía y Territorios', '#FF851B'),
('Ciencias para la Ciudadanía', '#B10DC9'),
('Ciencias de la Salud', '#39CCCC'),
('Estadística, Probabilidad', '#FFDC00'),
('Educación Ciudadana', '#85144b'),
('Taller de Desafíos Ambientales', '#7FDBFF'),
('Lengua y Literatura', '#3D9970');