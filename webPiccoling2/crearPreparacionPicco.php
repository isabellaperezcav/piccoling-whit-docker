<?php
// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores de menu_id e inventario_id desde el formulario
    $menu_id = $_POST["menu_id"];
    $inventario_id = $_POST["inventario_id"];

    // URL de la solicitud POST para crear preparación
    $url = 'http://inventario:3002/preparacion';

    // Datos que se enviarán en la solicitud POST
    $data = array(
        'menu_id' => $menu_id,
        'inventario_id' => $inventario_id,
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
    if ($response === false) {
        header("Location:index.html");
    }

    // Cerrar la conexión cURL
    curl_close($ch);

    // Redirigir de nuevo a la página de administración
    header("Location:admin-preparacionPicco.php");
} else {
    // Si no se ha enviado el formulario de manera adecuada, redirigir a la página de inicio
    header("Location:index.html");
}
?>
