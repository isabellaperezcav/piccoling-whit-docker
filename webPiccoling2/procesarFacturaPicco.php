<?php
$usuario = $_POST['usuario'];

$items = array();

// Recorrer los valores de cantidad enviados por el formulario
foreach ($_POST['cantidad'] as $id_menu => $cantidad) {
    if ($cantidad>0){
    $item['id_menu'] = $id_menu;
    $item["cantidad"] = $cantidad;
    array_push($items, $item);
}

}

$facturas['usuario']=$usuario;
$facturas['items']=$items;

$json = json_encode($facturas);
//echo $json;


$url = 'http://facturas:3003/facturas';

// Inicializar cURL
$ch = curl_init();

// Configurar opciones de cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
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

header("Location:usuarioPicco.php");

?>