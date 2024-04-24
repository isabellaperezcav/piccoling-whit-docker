const express = require('express');
const inventarioController = require('./controllers/inventarioController');
const morgan = require('morgan');
const app = express();
app.use(morgan('dev'));
app.use(express.json());

app.use(inventarioController);

app.listen(3002, () => {
    console.log('Microservicio Inventario ejecutandose en el puerto 3002');
});