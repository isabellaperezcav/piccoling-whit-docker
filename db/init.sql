create database piccoling;
use piccoling

CREATE TABLE facturas (
    id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nombreCliente VARCHAR(50),
    emailCliente VARCHAR(50),
    totalCuenta DECIMAL(10,2),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
  nombrecompleto VARCHAR(90),
  correo VARCHAR(80),
  usuario VARCHAR(15) PRIMARY KEY,
  clave VARCHAR(50)
);

INSERT INTO usuarios (nombrecompleto, correo, usuario, clave) VALUES ('Administrador', 'administrador@piccoling.com', 'admin', '1234');

CREATE TABLE inventario (
  id_inventario INT AUTO_INCREMENT,
  ingrediente VARCHAR(100),
  cantidad_disponible INT,
  PRIMARY KEY (id_inventario)
);

CREATE TABLE menu (
    id_menu INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100),
    precio DECIMAL(10,2),
    cantidad INT(11)
);

CREATE TABLE preparacion (
    preparacionid INT PRIMARY KEY AUTO_INCREMENT,
    menu_id INT,
    inventario_id INT,
    FOREIGN KEY (menu_id) REFERENCES menu(id_menu),
    FOREIGN KEY (inventario_id) REFERENCES inventario(id_inventario)
) ENGINE=InnoDB;

