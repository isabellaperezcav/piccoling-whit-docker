const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    port: '3306',
    user: 'root',
    password: 'piccoling',
    database: 'piccoling'
});

// Funciones CRUD para menú directamente accesibles por los usuarios
async function traermenu() {
    const result = await connection.query('SELECT * FROM menu');
    return result[0];
}

async function traerMenu(id_menu) {
    const result = await connection.query('SELECT * FROM menu WHERE id_menu = ?', [id_menu]);
    return result[0];
}

async function crearMenu(nombre, precio, cantidad) {
    const result = await connection.query('INSERT INTO menu VALUES(null,?,?,?)', [nombre, precio, cantidad]);
    return result;
}

async function actualizarMenu(idMenu, nombre, precio, cantidad) {
    const result = await connection.query('UPDATE menu SET nombre = ?, precio = ?, cantidad = ? WHERE id_menu = ?', [nombre, precio, cantidad, idMenu]);
    return result;
}

async function eliminarMenu(idMenu) {
    const result = await connection.query('DELETE FROM menu WHERE id_menu = ?', [idMenu]);
    return result;
}

async function eliminarPreparacionesPorMenu(idMenu) {
    const result = await connection.query('DELETE FROM preparacion WHERE menu_id = ?', [idMenu]);
    return result[0];
}



// Funciones CRUD para inventario directamente accesibles por los usuarios
async function traerinventario() {
    const result = await connection.query('SELECT * FROM inventario');
    return result[0];
}

async function traerInventario(id_inventario) {
    const result = await connection.query('SELECT * FROM inventario WHERE id_inventario = ? ', [id_inventario]);
    return result[0];
}

async function crearInventario(ingrediente, cantidad_disponible) {
    const result = await connection.query('INSERT INTO inventario VALUES(null,?,?)', [ingrediente, cantidad_disponible]);
    return result;
}

async function actualizarInventario(idInventario, ingrediente, cantidadDisponible) {
    const result = await connection.query('UPDATE inventario SET ingrediente = ?, cantidad_disponible = ? WHERE id_inventario = ?', [ingrediente, cantidadDisponible, idInventario]);
    return result[0];
}

async function eliminarInventario(idInventario) {
    const result = await connection.query('DELETE FROM inventario WHERE id_inventario = ?', [idInventario]);
    return result[0];
}

async function eliminarPreparacionesPorInventario(idInventario) {
    const result = await connection.query('DELETE FROM preparacion WHERE inventario_id = ?', [idInventario]);
    return result[0];
}



// Funciones CRUD para preparacion directamente accesibles por los usuarios
async function traerpreparacion() {
    const result = await connection.query('SELECT * FROM preparacion');
    return result[0];
}

async function traerPreparacion(preparacion_id) {
    const result = await connection.query('SELECT * FROM preparacion WHERE preparacion_id = ?', [preparacion_id]);
    return result[0];
}

async function agregarPreparacion(menuId, inventarioId) {
    const result = await connection.query('INSERT INTO preparacion (menu_id, inventario_id) VALUES (?, ?)', [menuId, inventarioId]);
    return result;
}

async function actualizarPreparacion(preparacionId, menuId, inventarioId) {
    const result = await connection.query('UPDATE preparacion SET menu_id = ?, inventario_id = ? WHERE preparacion_id = ?', [menuId, inventarioId, preparacionId]);
    return result;
}

async function eliminarPreparacion(preparacionId) {
    const result = await connection.query('DELETE FROM preparacion WHERE preparacion_id = ?', [preparacionId]);
    return result;
}


// Funciones internas para manipulación de inventario y menú
async function traerPreparacionPorMenu(id_menu) {
    const result = await connection.query('SELECT * FROM preparacion WHERE menu_id = ?', [id_menu]);
    return result[0];
} 

async function actualizarMenuInventario(idMenu, cantidad) {
    const result = await connection.query('UPDATE menu SET cantidad = ? WHERE id_menu = ?', [cantidad, idMenu]);
    return result;
}

async function traerPreparacionPorInventario(id_inventario) {
    const result = await connection.query('SELECT * FROM preparacion WHERE inventario_id = ?', [id_inventario]);
    return result[0];
}

module.exports = {
    traermenu, traerMenu, actualizarMenu, crearMenu, eliminarMenu, eliminarPreparacionesPorMenu,
    traerinventario, traerInventario, actualizarInventario, crearInventario, eliminarInventario, eliminarPreparacionesPorInventario,
    traerpreparacion, traerPreparacion, agregarPreparacion, eliminarPreparacion, actualizarPreparacion,
    traerPreparacionPorMenu,
    actualizarMenuInventario, traerPreparacionPorInventario
};