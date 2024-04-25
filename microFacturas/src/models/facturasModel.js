const mysql = require('mysql2/promise');

const connection = mysql.createPool({
    host: 'db',
    port: '3306',
    user: 'root',
    password: 'piccoling',
    database: 'piccoling'
});

async function crearfactura(factura) {
    const nombreCliente = factura.nombreCliente;
    const emailCliente = factura.emailCliente;
    const totalCuenta = factura.totalCuenta;

    const result = await connection.query('INSERT INTO facturas (nombreCliente, emailCliente, totalCuenta) VALUES (?, ?, ?)', [nombreCliente, emailCliente, totalCuenta]);
    return result;
}


async function traerfactura(id_factura) {
    const result = await connection.query('SELECT * FROM facturas WHERE id = ? ', id_factura);
    return result[0];
}

async function traerfacturas() {
    const result = await connection.query('SELECT * FROM facturas');
    return result[0];
}

module.exports = {
    crearfactura,
    traerfacturas,
    traerfactura
};