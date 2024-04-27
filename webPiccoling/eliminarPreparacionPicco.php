<?php
// Verificar si se ha proporcionado un ID válido para eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $preparacionId = $_GET['id'];

    // URL de la solicitud DELETE para eliminar la preparación
    $url = 'http://inventario:3002/preparacion/' . $preparacionid;

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL para una solicitud DELETE
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecutar la solicitud DELETE
    $response = curl_exec($ch);

    // Cerrar la conexión cURL
    curl_close($ch);

    // Redirigir de nuevo a la página de administración
    header("Location:admin-preparacionPicco.php");
} else {
    // Si no se proporcionó un ID válido, redirigir a la página de inicio
    header("Location:index.html");
}
?>