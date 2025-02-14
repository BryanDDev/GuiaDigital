<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Contenido</title>
    <link rel="stylesheet" href="estilos/estilo1.css">
    
</head>
<body>
<title>Login Básico</title>

</head>

<body>
    <?php
    if (isset($_COOKIE['user_token'])) {
        $mysql = new mysqli("localhost", "root", "", "login");
        if ($mysql->connect_error) {
            die("Problemas con la conexión a la base de datos");
        }
    
        $token = $_COOKIE['user_token'];
    
        // Verificar el token en la base de datos
        $stmt = $mysql->prepare("SELECT id FROM usuario WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $resultado = $stmt->get_result();
    
        if ($fila = $resultado->fetch_assoc()) {
            $_SESSION['id'] = $fila['id']; // Restaurar sesión si el token es válido
        } else {
            header("Location: inicio_sesion.php"); // Redirigir si el token es inválido
            exit();
        }
    } else {
        header("Location: inicio_sesion.php"); // Redirigir si no hay cookie o token
        exit();
    }
    ?>
    <div class="login-container">
        <h2> Login Administrador</h2>
        <form action="login_administrador.php" method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>

    </div>
<?php

$conexion = mysqli_connect("localhost", "root", "", "login");

// Verificar si la conexión es exitosa
if (!$conexion) {
    die("Conexión fallida: " );
}


$username = trim($_POST['username']);  
$password = trim($_POST['password']);  


$consulta = "SELECT username, password FROM usuario WHERE username = '$username'";
$resultado = mysqli_query($conexion, $consulta);


if ($resultado && mysqli_num_rows($resultado) > 0) {
   
    $fila = mysqli_fetch_assoc($resultado);
    $stored_username = $fila['username'];
    $stored_password = $fila['password'];

   
    if ($stored_password === $password) {
       
        header("Location: administracion.php");
        exit();
    } else {

     
        echo "<h1>Contraseña incorrecta</h1>";
        echo "<a href='inicio_sesion.html'>Volver al login</a>";
    }
} else {
    // Si no se encuentra el usuario
    echo "<h1>Usuario no encontrado</h1>";
    echo "<a href='inicio_sesion.html'>Volver al login</a>";
}

// Cerrar la conexión
mysqli_close($conexion);
?>


    
</body>
</html>