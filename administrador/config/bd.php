<?php
$host = "localhost";
$bd="sitio";
$usuario="root";
$contrasenia="";

try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd",$usuario,$contrasenia);
    if($conexion){
        echo "Conexión exitosa ";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
?>