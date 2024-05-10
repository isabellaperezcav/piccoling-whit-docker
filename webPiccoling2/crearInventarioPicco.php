<?php
    $ingrediente=$_POST["ingrediente"];
    $cantidad_disponible=$_POST["cantidad_disponible"];

    // URL de la solicitud POST
    $url = 'http://inventario:3002/inventario';

    // Datos que se enviarán en la solicitud POST
    $data = array(
        'ingrediente' => $ingrediente,
        'cantidad_disponible' => $cantidad_disponible,
    );
    $json_data = json_encode($data);

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud POST
    $response = curl_exec($ch);

    // Manejar la respuesta
    if ($response===false){
        header("Location:index.html");
    }
    // Cerrar la conexión cURL
    curl_close($ch);
    header("Location:admin-inventarioPicco.php");

?>