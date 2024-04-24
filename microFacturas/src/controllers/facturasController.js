const express = require('express');
const router = express.Router();
const axios = require('axios');
const facturasModel = require('../models/facturasModel');

router.get('/:id', async (req, res) => {
    const id_factura = req.params.id;
    var result;
    result = await facturasModel.traerfactura(id_factura);
    res.json(result[0]);
});

router.get('/', async (req, res) => {
    var result;
    result = await facturasModel.traerfacturas();
    res.json(result);
});

router.post('/', async (req, res) => {
    const usuario = req.body.usuario;
    const items = req.body.items;

    const totalCuenta = await calcularTotal(items);
    console.log(totalCuenta)

    // Si el total es 0 o negativo, retornamos un error
    if (totalCuenta <= 0) {
        return res.json({ error: 'Invalid order total' });
    }

    // Verificamos si hay suficientes unidades de los productos para realizar la orden
    const disponibilidad = await verificarDisponibilidad(items);

    // Si no hay suficientes unidades de los productos, retornamos un error
    if (!disponibilidad) {
        return res.json({ error: 'No hay disponibilidad de productos' });
    }

    // Creamos la orden
    const response = await axios.get(`http://usuarios:3001/usuarios/${usuario}`);
    const name = response.data.nombrecompleto;
    const email = response.data.correo;
    console.log("entro al usuario")
    orden = {
        "nombreCliente": name, 
        "emailCliente": email, 
        "totalCuenta": totalCuenta  // Aquí incluimos totalCuenta en el objeto orden
    }
    const ordenRes = await facturasModel.crearfactura(orden);

    // Disminuimos la cantidad de unidades de los productos
    await actualizarInventario(items);

    return res.send("factura creada");
});


// Función para calcular el total de la orden
async function calcularTotal(items) {
    let ordenTotal = 0;
    for (const producto of items) {
        const response = await
            axios.get(`http://inventario:3002/menu/${producto.id_menu}`);
            console.log(`Los precios son: ${response.data.precio} y ${producto.cantidad}`)
        ordenTotal += response.data.precio * producto.cantidad;
        console.log(ordenTotal)
    }
    return ordenTotal;
}

// Función para verificar si hay suficientes unidades de los productos para realizar la orden
async function verificarDisponibilidad(items) {
    let disponibilidad = true;
    for (const producto of items) {
        const response = await axios.get(`http://inventario:3002/menu/${producto.id_menu}`);
        if (!response || !response.data || response.data.cantidad < producto.cantidad) {
            disponibilidad = false;
            break;
        }
    }
    return disponibilidad;
}


// Función para disminuir la cantidad de unidades de los productos
async function actualizarInventario(items) {
    for (const producto of items) {
        const response = await
            axios.get(`http://inventario:3002/menu/${producto.id_menu}`);
        const inventarioActual = response.data.cantidad;
        const inv = inventarioActual - producto.cantidad;
        await axios.put(`http://inventario:3002/menu/${producto.id_menu}`, {
            cantidad: inv
        });
    }
}

module.exports = router;