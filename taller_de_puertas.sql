CREATE DATABASE IF NOT EXISTS taller_de_puertas;
USE taller_de_puertas;

CREATE TABLE Rol (
    id_rol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL
);

CREATE TABLE Usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido1 VARCHAR(50) NOT NULL,
    apellido2 VARCHAR(50),
    contraseña VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    id_rol INT NOT NULL,
    FOREIGN KEY (id_rol) REFERENCES Rol(id_rol)
);

CREATE TABLE Estado_carrito (
    id_estado_carrito INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE Carrito (
    id_carrito INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,
    id_estado_carrito INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_estado_carrito) REFERENCES Estado_carrito(id_estado_carrito)
);

CREATE TABLE Categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE Tipo_producto (
    id_tipo_producto INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE Inventario (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    cantidad_inventario INT NOT NULL,
    id_categoria INT NOT NULL,
    id_tipo_producto INT NOT NULL,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id_categoria),
    FOREIGN KEY (id_tipo_producto) REFERENCES Tipo_producto(id_tipo_producto)
);

CREATE TABLE Carrito_producto (
    id_carrito_producto INT AUTO_INCREMENT PRIMARY KEY,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    id_carrito INT NOT NULL,
    id_producto INT NOT NULL,
    FOREIGN KEY (id_carrito) REFERENCES Carrito(id_carrito),
    FOREIGN KEY (id_producto) REFERENCES Inventario(id_producto)
);

CREATE TABLE Estado_factura (
    id_estado_factura INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE Metodo_de_pago (
    id_metodo_de_pago INT AUTO_INCREMENT PRIMARY KEY,
    descripcion VARCHAR(100) NOT NULL
);

CREATE TABLE Facturacion (
    id_factura INT AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    id_usuario INT NOT NULL,
    id_carrito INT NOT NULL,
    id_estado_factura INT NOT NULL,
    id_metodo_de_pago INT NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
    FOREIGN KEY (id_carrito) REFERENCES Carrito(id_carrito),
    FOREIGN KEY (id_estado_factura) REFERENCES Estado_factura(id_estado_factura),
    FOREIGN KEY (id_metodo_de_pago) REFERENCES Metodo_de_pago(id_metodo_de_pago)
);

INSERT INTO Rol (nombre) VALUES('Administrador'),('Usuario');

INSERT INTO Usuario (nombre, apellido1, apellido2, contraseña, email, id_rol) VALUES('Juan', 'Pérez', 'Gómez', '123456', 'juan.perez@example.com', 2),('Ana', 'Martínez', 'López', '123456', 'ana.martinez@example.com', 2),('Pedro', 'García', NULL, '123456', 'pedro.garcia@example.com', 1);

GRANT ALL PRIVILEGES ON taller_de_puertas.* TO 'root'@'localhost';
ALTER USER 'root'@'localhost' IDENTIFIED BY '1234';
FLUSH PRIVILEGES;

ALTER TABLE Usuario MODIFY apellido2 VARCHAR(50) NULL;
ALTER TABLE Usuario CHANGE contraseña password VARCHAR(255) NOT NULL;