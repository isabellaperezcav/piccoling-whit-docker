const { Router } = require('express');
const router = Router();
const productosModel = require('../models/inventarioModel');

// Rutas para manipulación directa de menú
router.get('/menu', async (req, res) => {
    const result = await productosModel.traermenu();
    res.json(result);
});

router.get('/menu/:id_menu', async (req, res) => {
    const { id_menu } = req.params;
    const result = await productosModel.traerMenu(id_menu);
    res.json(result[0]);
});

router.post('/menu', async (req, res) => {
    const { nombre, precio, cantidad } = req.body;
    const result = await productosModel.crearMenu(nombre, precio, cantidad);
    res.send("Platillo creado exitosamente");
});

router.put('/menu/:id_menu', async (req, res) => {
    const { id_menu } = req.params;
    const { cantidad } = req.body;

    try {
        const menu = await productosModel.traerMenu(id_menu);
        const cantidad_anterior = menu[0].cantidad;
        const diferencia_cantidad = cantidad - cantidad_anterior;

        await productosModel.actualizarMenu(id_menu, menu[0].nombre, menu[0].precio, cantidad);

        const preparaciones = await productosModel.traerPreparacionPorMenu(id_menu);
        for (const preparacion of preparaciones) {
            const { inventario_id } = preparacion;
            const inventario = await productosModel.traerInventario(inventario_id);
            const nuevaCantidadInventario = inventario[0].cantidad_disponible + diferencia_cantidad;
            await productosModel.actualizarInventario(inventario_id, inventario[0].ingrediente, nuevaCantidadInventario);
        }

        res.send("Menú actualizado exitosamente");
    } catch (error) {
        console.error(error);
        res.status(500).send("Error al actualizar menú e inventario");
    }
});

router.delete('/menu/:id_menu', async (req, res) => {
    const { id_menu } = req.params;

    try {
        await productosModel.eliminarPreparacionesPorMenu(id_menu);
        await productosModel.eliminarMenu(id_menu);

        res.send("Menú eliminado exitosamente");
    } catch (error) {
        console.error(error);
        res.status(500).send("Error al eliminar menú");
    }
});


// Rutas para manipulación directa de inventario
router.get('/inventario', async (req, res) => {
    const result = await productosModel.traerinventario();
    res.json(result);
});

router.get('/inventario/:id_inventario', async (req, res) => {
    const { id_inventario } = req.params;
    const result = await productosModel.traerInventario(id_inventario);
    res.json(result[0]);
});

router.post('/inventario', async (req, res) => {
    const { ingrediente, cantidad_disponible } = req.body;
    const result = await productosModel.crearInventario(ingrediente, cantidad_disponible);
    res.send("Ingrediente creado exitosamente");
});


router.put('/inventario/:id_inventario', async (req, res) => {
    const { id_inventario } = req.params;
    const { ingrediente, cantidad_disponible } = req.body;

    try {
        const inventario = await productosModel.traerInventario(id_inventario);
        const cantidad_disponible_anterior = inventario[0].cantidad_disponible;
        const diferencia_cantidad = cantidad_disponible - cantidad_disponible_anterior;

        await productosModel.actualizarInventario(id_inventario, ingrediente, cantidad_disponible);

        const preparaciones = await productosModel.traerPreparacionPorInventario(id_inventario);
        for (const preparacion of preparaciones) {
            const { menu_id } = preparacion;
            const menu = await productosModel.traerMenu(menu_id);
            const nuevaCantidadMenu = menu[0].cantidad + diferencia_cantidad;
            await productosModel.actualizarMenu(menu_id, menu[0].nombre, menu[0].precio, nuevaCantidadMenu);
        }

        res.send("Inventario actualizado exitosamente");
    } catch (error) {
        console.error(error);
        res.status(500).send("Error al actualizar inventario y menú");
    }
});


router.delete('/inventario/:id_inventario', async (req, res) => {
    const { id_inventario } = req.params;

    try {
        await productosModel.eliminarPreparacionesPorInventario(id_inventario);
        await productosModel.eliminarInventario(id_inventario);

        res.send("Inventario eliminado exitosamente");
    } catch (error) {
        console.error(error);
        res.status(500).send("Error al eliminar inventario");
    }
});


// Rutas para manipulación directa de preparacion
router.get('/preparacion', async (req, res) => {
    const result = await productosModel.traerpreparacion();
    res.json(result);
});

router.get('/preparacion/:preparacionid', async (req, res) => {
    const { preparacionid } = req.params;
    const result = await productosModel.traerPreparacion(preparacionid);
    res.json(result[0]);
});

router.post('/preparacion', async (req, res) => {
    const { menu_id, inventario_id } = req.body;
    await productosModel.agregarPreparacion(menu_id, inventario_id);
    res.send("Preparación creada exitosamente");
});

router.put('/inventario/:id_inventario', async (req, res) => {
    const { id_inventario } = req.params;
    const { cantidad } = req.body;

    try {
        const inventario = await productosModel.traerInventario(id_inventario);
        const cantidad_disponible_anterior = inventario[0].cantidad_disponible;
        const diferencia_cantidad = cantidad - cantidad_disponible_anterior;

        await productosModel.actualizarInventario(id_inventario, inventario[0].ingrediente, cantidad);

        const preparaciones = await productosModel.traerPreparacionPorInventario(id_inventario);
        for (const preparacion of preparaciones) {
            const { menu_id } = preparacion;
            const menu = await productosModel.traerMenu(menu_id);
            const nuevaCantidadMenu = menu[0].cantidad + diferencia_cantidad;
            await productosModel.actualizarMenu(menu_id, menu[0].nombre, menu[0].precio, nuevaCantidadMenu);
        }

        res.send("Inventario actualizado exitosamente");
    } catch (error) {
        res.status(500).send("Error al actualizar inventario y menú");
    }
});

router.delete('/preparacion/:preparacionid', async (req, res) => {
    const { preparacionid } = req.params;
    await productosModel.eliminarPreparacion(preparacionid);
    res.send("Preparación eliminada exitosamente");
});

module.exports = router;