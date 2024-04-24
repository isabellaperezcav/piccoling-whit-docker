<?php
session_start();
$us = $_SESSION["usuario"];
if ($us == "") {
    header("Location: index.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
    <title>Admin Piccoling</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="adminPicco.php">Proyecto Piccoling</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="adminPicco.php">Usuarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-menuPicco.php">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-inventarioPicco.php">Inventarios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-facturasPicco.php">Facturas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin-preparacionPicco.php">Relacion Menu-Inventario</a>
                    </li>
                </ul>
                <span class="navbar-text">
                    <?php echo "<a class='nav-link' href='logoutPicco.php'>Logout $us</a>"; ?>
                </span>
            </div>
        </div>
    </nav>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID_INVENTARIO</th>
                <th scope="col">Ingrediente</th>
                <th scope="col">Cantidad_Disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servurl = "http://192.168.100.4:3002/inventario";
            $curl = curl_init($servurl);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);

            if ($response === false) {
                curl_close($curl);
                die("Error en la conexion");
            }

            curl_close($curl);
            $resp = json_decode($response);
            $long = count($resp);
            for ($i = 0; $i < $long; $i++) {
                $dec = $resp[$i];
                $id_inventario = $dec->id_inventario;
                $ingrediente = $dec->ingrediente;
                $cantidad_disponible = $dec->cantidad_disponible;

                ?>

                <tr>
                    <td>
                        <?php echo $id_inventario; ?>
                    </td>
                    <td>
                        <?php echo $ingrediente; ?>
                    </td>
                    <td>
                        <?php echo $cantidad_disponible; ?>
                    </td>
                    <td><button class='btn btn-danger'
                            onclick='eliminarInventario(<?php echo $id_inventario; ?>)'>Eliminar</button>
                    <td><button class='btn btn-primary'
                            onclick='actualizarCantidad(<?php echo $id_inventario; ?>, <?php echo $cantidad_disponible; ?>)'>Actualizar</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>


    <div class="modal" id="modalActualizar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar Cantidad Disponible</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formActualizarCantidad" action="actualizarInventarioPicco.php" method="post">
                        <input type="hidden" id="id_inventario_actualizar" name="id_inventario" value="">
                        <div class="mb-3">
                            <label for="ingrediente_actualizar" class="form-label">Nombre ingrediente a
                                actualizar</label>
                            <input type="text" name="ingrediente_actualizar" class="form-control"
                                id="ingrediente_actualizar" aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad_disponible_actualizar" class="form-label">Nueva Cantidad
                                Disponible</label>
                            <input type="text" name="cantidad_disponible" class="form-control"
                                id="cantidad_disponible_actualizar" aria-describedby="emailHelp">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary"
                        onclick="submitActualizarCantidad()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>



    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        CREAR INGREDIENTE
    </button>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREAR INGREDIENTE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crearInventarioPicco.php" method="post">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Ingrediente</label>
                            <input type="text" name="ingrediente" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Cantidad Disponible</label>
                            <input type="text" name="cantidad_disponible" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Crear Ingrediente</button>
                        </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function eliminarInventario(id_inventario) {
                if (confirm("¿Estás seguro de eliminar este ingrediente? Ten en cuenta que al eliminarlo se eliminaran las relaciones que tengas entre este y el menu")) {
                    window.location.href = "eliminarInventarioPicco.php?id=" + id_inventario;
                }
            }

            function actualizarCantidad(id_inventario, cantidad_disponible) {
                document.getElementById('id_inventario_actualizar').value = id_inventario;
                document.getElementById('cantidad_disponible_actualizar').value = cantidad_disponible;
                var modal = new bootstrap.Modal(document.getElementById('modalActualizar'));
                modal.show();
            }

            function submitActualizarCantidad() {
                document.getElementById('formActualizarCantidad').submit();
            }
        </script>
</body>
</body>

</html>