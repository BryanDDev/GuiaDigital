<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "login");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}


$id_usuario = $_SESSION['id']; 
$query = "UPDATE usuario SET token = '' WHERE id = '$id_usuario'";
$registros = mysqli_query($conexion, $query);



if (isset($_COOKIE['token'])) {
    setcookie("token", "", time() - 3600, "/"); 
}


header("Location: index.php");
exit();
?>
