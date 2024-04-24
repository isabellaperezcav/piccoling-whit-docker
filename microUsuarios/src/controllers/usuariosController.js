const { Router } = require('express');
const router = Router();
const usuariosModel = require('../models/usuariosModel');

router.get('/usuarios', async (req, res) => {
  try {
    const result = await usuariosModel.traerUsuarios();
    if (result.length > 0) {
      res.json(result);
    } else {
      res.status(404).send("No se encontraron usuarios.");
    }
  } catch (error) {
    console.error("Error al obtener usuarios:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

router.get('/usuarios/:usuario', async (req, res) => {
  const usuario = req.params.usuario;
  try {
    const result = await usuariosModel.traerUsuario(usuario);
    res.json(result[0]);
  } catch (error) {
    console.error("Error al obtener usuario:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

router.get('/usuarios/:usuario/:clave', async (req, res) => {
  const usuario = req.params.usuario;
  const clave = req.params.clave;
  try {
    const result = await usuariosModel.validarUsuario(usuario, clave);
    res.json(result);
  } catch (error) {
    console.error("Error al validar usuario:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

router.post('/usuarios', async (req, res) => {
  const { nombrecompleto, correo, usuario, clave } = req.body;
  try {
    const result = await usuariosModel.crearUsuario(nombrecompleto, correo, usuario, clave);
    res.send("Usuario creado");
  } catch (error) {
    console.error("Error al crear usuario:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

router.delete('/usuarios/:usuario', async (req, res) => {
  const usuario = req.params.usuario;
  try {
    const result = await usuariosModel.eliminarUsuario(usuario);
    res.send("Usuario eliminado");
  } catch (error) {
    console.error("Error al eliminar usuario:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

router.put('/usuarios/:usuario', async (req, res) => {
  const usuario = req.params.usuario;
  const { nombrecompleto, correo, clave } = req.body;
  try {
    const result = await usuariosModel.actualizarUsuario(nombrecompleto, correo, usuario, clave);
    res.send("Usuario actualizado");
  } catch (error) {
    console.error("Error al actualizar usuario:", error);
    res.status(500).send("Error interno del servidor.");
  }
});

module.exports = router;
