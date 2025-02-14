<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="estilos/estilo1.css">
    <title>Registro</title>
</head>

<body>
    <h1>Registrar usuario</h1>
    <?php

    $mysql = new mysqli("localhost", "root", "", "login");
    if ($mysql->connect_error) {
        die("Problemas con la conexión a la base de datos: " . $mysql->connect_error);
    }

    echo '
    <form class="login-container" method="post" name="formulario" target="_self">
        
        

        <p>Nombre:&nbsp;<input name="nombre" type="text" required /></p>
        <p>Apellido:&nbsp;<input name="apellido" type="text" required /></p>
        <p>Correo:&nbsp;<input name="correo" type="text" required /></p>
        <p>Contraseña:&nbsp;<input name="contraseña" type="text" required /></p>
        <p>Usuario:&nbsp;<input name="usuario" type="text" required /></p>
        
        <p><input name="enviar" type="submit" value="Enviar" /></p>
    </form>';

    if (isset($_POST['enviar'])) {

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $contraseña = trim($_POST['contraseña'] ?? '');
        $username = trim($_POST['usuario'] ?? '');
        

        $consulta_usuario = "SELECT username FROM usuario WHERE username = '$username'";
        $resultado = $mysql->query($consulta_usuario);

        if ($resultado->num_rows > 0) {
                echo'<p> Usuario existente</p>';
            
        }else{
            $sql = "INSERT INTO usuario (nombres, apellidos, correo, password, username) VALUES ('" . $nombre . "', '" . $apellido . "',  '$correo','" . $contraseña . "', '" . $username . "')";
            $stmt = $mysql->prepare($sql);
            if ($stmt->execute()) {
                header("Location: inicio_sesion.php");
            } else {
                echo "<p>Error al insertar: " . $stmt->error . "</p>";
            }
        }
    
    }

    $mysql->close();
    ?>
    <a href="inicio_sesion.php">Volver</a>
</body>

</html>