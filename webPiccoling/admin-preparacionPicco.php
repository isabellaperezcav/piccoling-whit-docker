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
                <th scope="col">ID</th>
                <th scope="col">Menu ID</th>
                <th scope="col">Inventario ID</th>
                <th scope="col">Eliminar preparacion</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servurl = "http://inventario:3002/preparacion";
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
                $id = $dec->preparacionid;
                $id_menu = $dec->menu_id;
                $id_inventario = $dec->inventario_id;

                ?>

                <tr>
                    <td><?php echo $id; ?></td>
                    <td><?php echo $id_menu; ?></td>
                    <td><?php echo $id_inventario; ?></td>
                    <td><button class='btn btn-danger' onclick='eliminarPreparacion(<?php echo $id; ?>)'>Eliminar</button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        CREAR RELACION
    </button>
    <div class="modal" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">CREAR PREPARACION</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="crearPreparacionPicco.php" method="post">
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">Menú</label>
                            <select name="menu_id" class="form-control" id="menu_id">
                                <?php
                                // Obtener opciones para el menú desde la API
                                $menuUrl = "http://inventario:3002/menu";
                                $menuJson = file_get_contents($menuUrl);
                                $menuData = json_decode($menuJson);

                                // Iterar sobre los elementos del menú para crear las opciones
                                foreach ($menuData as $menuItem) {
                                    echo "<option value='" . $menuItem->id_menu . "'>" . $menuItem->nombre . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="inventario_id" class="form-label">Inventario</label>
                            <select name="inventario_id" class="form-control" id="inventario_id">
                                <?php
                                // Obtener opciones para el inventario desde la API
                                $inventarioUrl = "http://inventario:3002/inventario";
                                $inventarioJson = file_get_contents($inventarioUrl);
                                $inventarioData = json_decode($inventarioJson);

                                // Iterar sobre los elementos del inventario para crear las opciones
                                foreach ($inventarioData as $inventarioItem) {
                                    echo "<option value='" . $inventarioItem->id_inventario . "'>" . $inventarioItem->ingrediente . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Crear Preparación</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>

        <script>
            function eliminarPreparacion(preparacionid) {
                if (confirm("¿Estás seguro de eliminar esta preparación?")) {
                    // Aquí puedes hacer una solicitud AJAX para eliminar la preparación
                    // Por simplicidad, redirigimos a un archivo PHP que manejará la eliminación
                    window.location.href = "eliminarPreparacionPicco.php?id=" + preparacionid;
                }
            }
        </script>

</body>

</html>