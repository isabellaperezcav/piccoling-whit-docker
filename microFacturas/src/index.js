const express = require('express');
const morgan = require('morgan');
const facturasController = require('./controllers/facturasController');

const app = express();
app.use(morgan('dev'));
app.use(express.json());

// Punto de montaje para las rutas del controlador de facturas
app.use('/facturas', facturasController);

const PORT = 3003;
app.listen(PORT, () => {
    console.log(`Microservicio de facturas escuchando en el puerto ${PORT}`);
});