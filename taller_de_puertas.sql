CREATE DATABASE IF NOT EXISTS taller_de_puertas;
USE taller_de_puertas;

CREATE TABLE Rol (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    FOREIGN KEY (rol_id) REFERENCES Rol(id)
);

CREATE TABLE Categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE TipoProducto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Producto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    precio DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    categoria_id INT NOT NULL,
    tipo_id INT NOT NULL,
    FOREIGN KEY (categoria_id) REFERENCES Categoria(id),
    FOREIGN KEY (tipo_id) REFERENCES TipoProducto(id)
);

CREATE TABLE Carrito (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id)
);

CREATE TABLE CarritoProducto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    carrito_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) GENERATED ALWAYS AS (cantidad * precio_unitario) STORED,
    FOREIGN KEY (carrito_id) REFERENCES Carrito(id),
    FOREIGN KEY (producto_id) REFERENCES Producto(id)
);

CREATE TABLE Factura (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    carrito_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES Usuario(id),
    FOREIGN KEY (carrito_id) REFERENCES Carrito(id)
);

-- Insertar registros iniciales
INSERT INTO Rol (nombre) VALUES ('Admin'), ('Usuario');
INSERT INTO Categoria (nombre) VALUES ('Puertas de Madera'), ('Puertas de Metal');
INSERT INTO TipoProducto (nombre) VALUES ('Manual'), ('Automático');

select * from categoria;
describe usuario;

-- Insertar Categorías de Puertas
INSERT INTO Categoria (nombre) VALUES ('Puertas de Madera'), ('Puertas de Metal');

-- Insertar Tipos de Producto (Manual o Automático)
INSERT INTO TipoProducto (nombre) VALUES ('Manual'), ('Automático');

-- Insertar Productos (Puertas)
INSERT INTO Producto (nombre, descripcion, precio, cantidad, categoria_id, tipo_id)
VALUES
('Puerta de Madera Manual', 'Puerta rústica de madera ideal para interiores', 250.00, 15, 1, 1),
('Puerta de Madera Automática', 'Puerta de madera con sistema automático de apertura', 550.00, 5, 1, 2),
('Puerta de Metal Manual', 'Puerta resistente de metal con cerradura manual', 300.00, 20, 2, 1),
('Puerta de Metal Automática', 'Puerta metálica de alta seguridad con apertura automática', 800.00, 8, 2, 2);