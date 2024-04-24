const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    port: '3306',
    user: 'root',
    password: 'root',
    database: 'piccoling'
});

async function traerUsuarios() {
  const result = await connection.query('SELECT * FROM usuarios');
  return result[0];
}

async function traerUsuario(usuario) {
  const result = await connection.query('SELECT * FROM usuarios WHERE usuario = ?', usuario);
  return result[0];
}

async function validarUsuario(usuario, password) {
  const result = await connection.query('SELECT * FROM usuarios WHERE usuario = ? AND clave = ?', [usuario, password]);
  return result[0];
}

async function crearUsuario(nombre, correo, usuario, clave) {
  const result = await connection.query('INSERT INTO usuarios (nombrecompleto, correo, usuario, clave) VALUES (?, ?, ?, ?)', [nombre, correo, usuario, clave]);
  return result;
}

async function eliminarUsuario(usuario) {
  const result = await connection.query('DELETE FROM usuarios WHERE usuario = ?', usuario);
  return result;
}

async function actualizarUsuario(nombre, correo, usuario, clave) {
  const result = await connection.query('UPDATE usuarios SET nombrecompleto = ?, correo = ?, clave = ? WHERE usuario = ?', [nombre, correo, clave, usuario]);
  return result;
}

module.exports = {
  traerUsuarios,
  traerUsuario,
  validarUsuario,
  crearUsuario,
  eliminarUsuario,
  actualizarUsuario
};