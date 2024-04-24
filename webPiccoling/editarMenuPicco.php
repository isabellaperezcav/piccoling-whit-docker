<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario enviado con el método POST
    $menu_id_edit = $_POST["menu_id_edit"];
    $nombre_edit = $_POST["nombre_edit"];
    $precio_edit = $_POST["precio_edit"];
    $cantidad_edit = $_POST["cantidad_edit"];

    // URL de la solicitud PUT para editar el menú
    $url = 'http://192.168.100.4:3002/menu/'.$menu_id_edit;

    // Datos que se enviarán en la solicitud PUT
    $data = array(
        'nombre' => $nombre_edit,
        'precio' => $precio_edit,
        'cantidad' => $cantidad_edit,
    );
    $json_data = json_encode($data);

    // Inicializar cURL para una solicitud PUT
    $ch = curl_init();

    // Configurar opciones de cURL para la solicitud PUT
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data) // Agregar la longitud de los datos
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud PUT
    $response = curl_exec($ch);

    // Obtener el código de estado HTTP y la información de la solicitud
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    // Mostrar información de depuración
    echo "URL de la solicitud PUT: $url<br>";
    echo "Datos enviados en la solicitud PUT: $json_data<br>";
    echo "Código de estado HTTP: $http_code<br>";
    echo "Error de cURL: $error<br>";

    // Manejar la respuesta según el código de estado HTTP
    if ($http_code == 200) {
        // Redirigir de nuevo a la página de menú con el mensaje de éxito
        header("Location: admin-menuPicco.php?edit_success=true");
        exit; // Importante: detener la ejecución después de la redirección
    } else {
        // Manejo del error
        echo "Error al editar el menú";
        // También puedes redirigir a una página de error si es necesario
        // header("Location: error.php");
    }

    // Cerrar la conexión cURL
    curl_close($ch);
} else {
    // Si no se ha enviado el formulario, redirigir a la página de inicio
    header("Location: index.html");
    exit; // Importante: detener la ejecución después de la redirección
}
?>