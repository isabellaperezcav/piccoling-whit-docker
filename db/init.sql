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

INSERT INTO usuarios (nombrecompleto, correo, usuario, clave) VALUES ('Administrador', 'administrador@piccoling.com', 'admin', '1234'),
('Eamon Hause', 'ehause0@digg.com', 'ehause0', '26-7788533'),
('Siobhan Tillman', 'stillman1@imgur.com', 'stillman1', '90-2167294'),
('Corly Drieu', 'cdrieu9@oracle.com', 'cdrieu9', '61-4450157'),
('Leontine Pietruszka', 'lpietruszkaa@histats.com', 'lpietruszkaa', '66-4563264');


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


INSERT INTO inventario (ingrediente, cantidad_disponible) VALUES
('Loquat', 83),
('Banana - Green', 49),
('Broccoli - Fresh', 42),
('Beer - Moosehead', 45),
('Soup Bowl Clear 8oz92008', 41),
('Truffle Cups - Red', 34),
('Pepsi, 355 Ml', 62),
('Coffee - Frthy Coffee Crisp', 53),
('Grapes - Red', 58);


INSERT INTO menu (nombre, precio, cantidad) VALUES
('Pie Filling - Pumpkin', 64682.81, 66),
('Soup - Campbells, Butternut', 12044.49, 87),
('Gin - Gilbeys London, Dry', 75944.75, 43),
('Rice - Jasmine Sented', 64268.24, 71),
('Shrimp - Black Tiger 13/15', 91452.02, 22),
('Chips - Potato Jalapeno', 3544.74, 94),
('Bag Stand', 77370.06, 93),
('Bagel - Everything Presliced', 25693.81, 73);


INSERT INTO preparacion (menu_id, inventario_id) VALUES
(37, 48),
(60, 78),
(54, 6),
(11, 71),
(36, 5),
(23, 38),
(5, 27);


INSERT INTO facturas (nombreCliente, emailCliente, totalCuenta, fecha) VALUES
('Kaycee MacAllen', 'kmacallen0@microsoft.com', NULL, '2024-03-10T00:00:00.000Z'),
('Ines Kirkwood', 'ikirkwood1@comsenz.com', NULL, '2023-12-20T00:00:00.000Z'),
('Grover Phizackarley', 'gphizackarley2@biblegateway.com', 836200000.00, '2024-02-04T00:00:00.000Z'),
('Brina Devey', 'bdevey3@seesaa.net', 386900000.00, '2023-10-27T00:00:00.000Z'),
('Ariana Devenish', 'adevenish4@epa.gov', 4230000000.00, '2023-12-17T00:00:00.000Z'),
('Dorolisa Mahon', 'dmahon5@photobucket.com', 11180000000.00, '2023-11-14T00:00:00.000Z'),
('Vittoria Garbar', 'vgarbar1o@yelp.com', NULL, '2024-01-19T00:00:00.000Z');