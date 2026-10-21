-- 1. Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS practica_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Seleccionar la base de datos a usar
USE practica_db;

-- 3. Crear la tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Insertar 4 registros de prueba
INSERT INTO usuarios (nombre, apellido, correo, clave) VALUES
('Juan', 'Pérez', 'juan.perez@example.com', '123456'),
('María', 'Gómez', 'maria.gomez@example.com', '123456'),
('Carlos', 'Mendoza', 'carlos.mendoza@example.com', '123456'),
('Ana', 'Rodríguez', 'ana.rodriguez@example.com', '123456');