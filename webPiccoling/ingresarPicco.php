<?php
    $user=$_POST["usuario"];
    $clave=$_POST["clave"];

    $servurl="http://192.168.100.4:3001/usuarios/$user/$clave";
    $curl=curl_init($servurl);

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response=curl_exec($curl);
    curl_close($curl);

    if ($response===false){
        header("Location:index.html");
    }

    $resp = json_decode($response);

    if (count($resp) != 0){
        session_start();
        $_SESSION["usuario"]=$user;
        if ($user == "admin"){ 
            header("Location:adminPicco.php");
        } 
        else { 
           header("Location:usuarioPicco.php");
        } 
    }
    else {
    header("Location:index.html"); 
    }

?>