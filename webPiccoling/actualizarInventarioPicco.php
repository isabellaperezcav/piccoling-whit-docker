<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_inventario']) && isset($_POST['ingrediente_actualizar']) && isset($_POST['cantidad_disponible'])) {
    $id_ingrediente = $_POST['id_inventario'];
    $ingrediente = $_POST['ingrediente_actualizar'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    // Validar y sanitizar los datos aquí si es necesario...

    // URL de la solicitud PUT para actualizar el inventario
    $url = 'http://192.168.100.4:3002/inventario/' . $id_ingrediente;

    // Datos que se enviarán en la solicitud PUT
    $data = array(
        'ingrediente' => $ingrediente,
        'cantidad_disponible' => $cantidad_disponible,
    );
    $json_data = json_encode($data);

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL para la solicitud PUT
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud PUT
    $response = curl_exec($ch);

    // Manejar la respuesta
    if ($response === false) {
        echo "Error en la conexión";
    } else {
        // Verificar la respuesta para saber si la actualización fue exitosa
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode == 200) {
            header("Location:admin-inventarioPicco.php");
        } else {
            echo "Error al actualizar la cantidad disponible: " . $response;
        }
    }

    // Cerrar la conexión cURL
    curl_close($ch);
} else {
    echo "Error: Se esperaba una solicitud POST con los datos necesarios";
}
?>
