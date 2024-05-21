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



INSERT INTO usuarios (nombrecompleto, correo, usuario, clave) VALUES
('Eamon Hause', 'ehause0@digg.com', 'ehause0', '26-7788533'),
('Siobhan Tillman', 'stillman1@imgur.com', 'stillman1', '90-2167294'),
('Jamison Cast', 'jcast2@cdbaby.com', 'jcast2', '95-9844903'),
('Rosabelle Dingle', 'rdingle3@tinypic.com', 'rdingle3', '99-8495063'),
('Hildy Bellsham', 'hbellsham4@photobucket.com', 'hbellsham4', '10-4893610'),
('Cherin Perrie', 'cperrie5@reddit.com', 'cperrie5', '00-5792001'),
('Bondon Greyes', 'bgreyes6@hud.gov', 'bgreyes6', '88-4179432'),
('Fredric Sterke', 'fsterke7@hud.gov', 'fsterke7', '59-9103131'),
('Vivyanne Ridder', 'vridder8@comsenz.com', 'vridder8', '10-9199601'),
('Corly Drieu', 'cdrieu9@oracle.com', 'cdrieu9', '61-4450157'),
('Leontine Pietruszka', 'lpietruszkaa@histats.com', 'lpietruszkaa', '66-4563264');


INSERT INTO inventario (ingrediente, cantidad_disponible) VALUES
('Loquat', 83),
('Banana - Green', 49),
('Broccoli - Fresh', 42),
('Beer - Moosehead', 45),
('Soup Bowl Clear 8oz92008', 41),
('Coffee - Irish Cream', 23),
('Trueblue - Blueberry', 62),
('Dc - Sakura Fu', 63),
('Pants Custom Dry Clean', 38),
('Soup - Campbells, Cream Of', 48),
('Ham - Cooked', 93),
('Chicken - Soup Base', 48),
('Coffee Caramel Biscotti', 94),
('Lobster - Cooked', 21),
('Tea - Herbal - 6 Asst', 48),
('Salmon - Smoked, Sliced', 19),
('Wine - White, French Cross', 42),
('Cake - Cheese Cake 9 Inch', 94),
('Lamb - Shoulder, Boneless', 98),
('Gatorade - Cool Blue Raspberry', 71),
('Celery', 37),
('Carbonated Water - Peach', 53),
('Cardamon Seed / Pod', 9),
('Bay Leaf Ground', 52),
('Lid - 3oz Med Rec', 47),
('Lid Tray - 16in Dome', 89),
('Table Cloth 144x90 White', 84),
('Lidsoupcont Rp12dn', 47),
('Club Soda - Schweppes, 355 Ml', 37),
('Fish - Bones', 69),
('General Purpose Trigger', 73),
('Spice - Pepper Portions', 34),
('Beef - Top Sirloin', 75),
('Prunes - Pitted', 57),
('Yogurt - Peach, 175 Gr', 31),
('Appetizer - Mini Egg Roll, Shrimp', 85),
('Crab - Dungeness, Whole, live', 67),
('Bread - Corn Muffaleta Onion', 54),
('Coffee Caramel Biscotti', 33),
('Pepper - Chili Powder', 28),
('Beef - Outside, Round', 14),
('Cod - Black Whole Fillet', 55),
('Clam Nectar', 84),
('Beer - Camerons Cream Ale', 52),
('Chip - Potato Dill Pickle', 9),
('Cheese - Stilton', 3),
('Wine - Zinfandel California 2002', 18),
('Carrots - Jumbo', 54),
('Beans - Long, Chinese', 58),
('Skewers - Bamboo', 87),
('Gooseberry', 42),
('Ecolab - Ster Bac', 7),
('Cheese - St. Paulin', 19),
('Nut - Chestnuts, Whole', 27),
('Beef - Rouladin, Sliced', 15),
('Mushroom - Morel Frozen', 25),
('Oranges - Navel, 72', 10),
('Nantucket Apple Juice', 70),
('Tomato - Plum With Basil', 80),
('Tea - Earl Grey', 83),
('Bread - Onion Focaccia', 82),
('Squash - Butternut', 13),
('Wine - Cotes Du Rhone', 30),
('The Pop Shoppe - Black Cherry', 77),
('Wine - Placido Pinot Grigo', 84),
('Broom - Angled', 38),
('Cheese - Taleggio D.o.p.', 14),
('Wine - Bouchard La Vignee Pinot', 24),
('Table Cloth 53x69 White', 18),
('Tomatoes - Vine Ripe, Yellow', 20),
('Apple - Delicious, Red', 22),
('Cheese - St. Paulin', 43),
('Cocoa Powder - Natural', 81),
('Bandage - Finger Cots', 3),
('Pur Value', 62),
('Pasta - Rotini, Dry', 20),
('Truffle Cups - Red', 34),
('Pepsi, 355 Ml', 62),
('Coffee - Frthy Coffee Crisp', 53),
('Grapes - Red', 58);


INSERT INTO menu (nombre, precio, cantidad) VALUES
('Pie Filling - Pumpkin', 64682.81, 66),
('Soup - Campbells, Butternut', 12044.49, 87),
('Gin - Gilbeys London, Dry', 75944.75, 43),
('Rice - Jasmine Sented', 64268.24, 71),
('Stock - Fish', 30614.09, 45),
('Rosemary - Primerba, Paste', 11786.30, 16),
('V8 Pet', 69126.03, 44),
('Flour - Bran, Red', 14936.75, 14),
('Cheese - Brie, Cups 125g', 17231.43, 37),
('Veal - Ground', 89523.34, 34),
('Horseradish Root', 93977.58, 69),
('Bread Bowl Plain', 23539.98, 96),
('Mince Meat - Filling', 35430.01, 98),
('Ginger - Pickled', 4190.03, 41),
('Liqueur Banana, Ramazzotti', 30129.43, 98),
('Melon - Watermelon, Seedless', 25539.69, 32),
('Chives - Fresh', 85648.01, 15),
('Wine - Vovray Sec Domaine Huet', 99295.93, 49),
('Bacardi Breezer - Strawberry', 19017.03, 53),
('Cake Sheet Combo Party Pack', 87801.41, 65),
('Onions - Spanish', 37671.52, 32),
('Cabbage - Green', 93562.51, 34),
('Mushrooms - Honey', 25190.47, 80),
('Sorrel - Fresh', 26133.38, 64),
('Venison - Ground', 53194.75, 15),
('Butter Balls Salted', 51393.08, 77),
('Cake - Mini Cheesecake', 15906.46, 9),
('Flour Pastry Super Fine', 70036.47, 90),
('Sole - Dover, Whole, Fresh', 88834.52, 54),
('Fork - Plastic', 22191.81, 55),
('Bread Base - Gold Formel', 25599.72, 48),
('Blouse / Shirt / Sweater', 9076.39, 29),
('Mustard - Dry, Powder', 13097.14, 97),
('Shrimp - Black Tiger 13/15', 91452.02, 22),
('Chips - Potato Jalapeno', 3544.74, 94),
('Bag Stand', 77370.06, 93),
('Bagel - Plain', 2131.68, 91),
('Tuna - Fresh', 28259.24, 11),
('Bread - English Muffin', 11501.23, 14),
('Veal - Slab Bacon', 60679.42, 29),
('Irish Cream - Butterscotch', 43799.67, 61),
('Bread - Rolls, Corn', 25609.79, 75),
('Beef - Tenderlion, Center Cut', 88066.31, 26),
('Daves Island Stinger', 57167.20, 59),
('Broom Handle', 23497.42, 36),
('Pimento - Canned', 74380.67, 71),
('Pear - Prickly', 57634.77, 8),
('Cabbage - Nappa', 4814.28, 63),
('Pineapple - Regular', 58144.17, 4),
('Garlic Powder', 91708.85, 62),
('Wine La Vielle Ferme Cote Du', 71033.07, 82),
('Crackers - Trio', 80705.34, 82),
('Chocolate - Compound Coating', 31484.88, 5),
('Pasta - Gnocchi, Potato', 9302.90, 69),
('Shichimi Togarashi Peppeers', 367.91, 59),
('Wine - Sherry Dry Sack, William', 88983.55, 10),
('Melon - Watermelon Yellow', 38947.45, 2),
('Cheese - Montery Jack', 37895.49, 47),
('Doilies - 12, Paper', 13682.60, 8),
('Tomato - Tricolor Cherry', 60782.25, 32),
('Chips - Doritos', 5126.36, 67),
('Bread - Pita, Mini', 94630.65, 11),
('Container - Foam Dixie 12 Oz', 46056.27, 44),
('Bagel - Everything Presliced', 25693.81, 73);


INSERT INTO preparacion (menu_id, inventario_id) VALUES
(37, 48),
(60, 78),
(54, 6),
(11, 58),
(8, 70),
(57, 37),
(61, 62),
(58, 8),
(13, 42),
(35, 27),
(50, 64),
(17, 73),
(7, 50),
(9, 3),
(24, 78),
(28, 7),
(28, 4),
(8, 12),
(62, 53),
(4, 16),
(54, 80),
(14, 63),
(53, 43),
(9, 56),
(39, 80),
(28, 6),
(27, 22),
(65, 49),
(41, 44),
(25, 1),
(56, 20),
(26, 36),
(23, 27),
(20, 34),
(59, 37),
(55, 76),
(28, 31),
(34, 77),
(51, 48),
(26, 27),
(56, 35),
(48, 75),
(12, 39),
(31, 35),
(11, 71),
(36, 5),
(23, 38),
(5, 27);