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
    <title>Proyecto Piccoling</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="usuario.php">Proyecto Piccoling</a>
            <span class="navbar-text">
                <?php echo "<a class='nav-link' href='logoutPicco.php'>Logout $us</a>"; ?>
            </span>
        </div>
        </div>
    </nav>
    <form method="post" action="procesarFacturaPicco.php">
        <table class="table">
            <thead>
                <tr>

                    <th scope="col">Nombre Platillo</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Cantidad disponible</th>
                    <th scope="col">Cantidad a pedir</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $servurl = "http://192.168.100.4:3002/menu";
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
                //echo '<form method="post" action="procesar.php">';
                for ($i = 0; $i < $long; $i++) {
                    $dec = $resp[$i];
                    $id_menu = $dec->id_menu;
                    $nombre = $dec->nombre;
                    $precio = $dec->precio;
                    $cantidad = $dec->cantidad;
                    ?>

                    <tr>

                        <td><?php echo $nombre; ?></td>
                        <td><?php echo $precio; ?></td>
                        <td><?php echo $cantidad; ?></td>
                        <td><?php echo '<input type="number" name="cantidad[' . $id_menu . ']" value="0" min="0">'; ?></td>
                    </tr>
                <?php } ?>


            </tbody>
        </table>
        <input type="hidden" name="usuario" value=<?php echo $us; ?>>
        <input type="submit" value="Agregar a la orden">
    </form>
</body>

</html>