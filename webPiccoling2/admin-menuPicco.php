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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Piccoling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link active" href="admin-inventarioPicco.php">Inventario</a>
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
                <th scope="col">ID_MENU</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio</th>
                <th scope="col">Cantidad disponible</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servurl = "http://inventario:3002/menu";
            $curl = curl_init($servurl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            if ($response === false) {
                curl_close($curl);
                die("Error en la conexión");
            }
            curl_close($curl);
            $resp = json_decode($response);
            $long = count($resp);
            for ($i = 0; $i < $long; $i++) {
                $dec = $resp[$i];
                $id_menu = $dec->id_menu;
                $nombre = $dec->nombre;
                $precio = $dec->precio;
                $cantidad = $dec->cantidad;
                ?>
                <tr>
                    <td>
                        <?php echo $id_menu; ?>
                    </td>
                    <td>
                        <?php echo $nombre; ?>
                    </td>
                    <td>
                        <?php echo $precio; ?>
                    </td>
                    <td>
                        <?php echo $cantidad; ?>
                    </td>
                    <td><button class='btn btn-danger' onclick='eliminarMenu(<?php echo $id_menu; ?>)'>Eliminar</button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        CREAR Menu(platillo)
    </button>


    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREAR Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crearMenuPicco.php" method="post">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Precio</label>
                            <input type="text" name="precio" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Cantidad</label>
                            <input type="text" name="cantidad" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Crear Menu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function eliminarMenu(id_menu) {
            if (confirm("¿Estás seguro de eliminar este menú?")) {
                window.location.href = "eliminarMenuPicco.php?id=" + id_menu;
            }
        }


    </script>
</body>

</html>