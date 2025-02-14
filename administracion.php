<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="estilos/estilo1.css">
    <title>Administrador</title>
</head>

<body>
    <?php
    session_start();

    
    if (isset($_COOKIE['user_token'])) {
        $mysql = new mysqli("localhost", "root", "", "login");
        if ($mysql->connect_error) {
            die("Problemas con la conexión a la base de datos");
        }
    
        $token = $_COOKIE['user_token'];
    
        
    $resultado = $mysql->execute_query("SELECT id FROM usuario WHERE token = '$token'");

    
        if ($fila = $resultado->fetch_assoc()) {
            $_SESSION['id'] = $fila['id']; 
        } else {
            header("Location: index.php"); 
            exit();
        }
    } else {
        header("Location: index.php"); 
    }
    ?>
    <h1>Administrador</h1>
    <?php

    $mysql = new mysqli("localhost", "root", "", "contenido_digital");
    if ($mysql->connect_error) {
        die("Problemas con la conexión a la base de datos: " . $mysql->connect_error);
    }

    echo '
    <form class="login-container" method="post" name="formulario" target="_self">
        <p>Categoría:&nbsp;
            <select name="categoria" required>
                <option selected value="">Seleccionar</option>
                <option value="Musica">Música</option>
                <option value="Peliculas">Películas</option>
                <option value="Videojuegos">Videojuegos</option>
            </select>
        </p>

        <p>Nombre:&nbsp;<input name="nombre" type="text" required /></p>
        <p>Creador:&nbsp;<input name="creador" type="text" required /></p>
        
        <p>Valoración:
            <select name="valoracion">
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </p>

        <p>Link de Imagen:&nbsp;<input name="url" type="text" required /></p>
        <p><input name="enviar" type="submit" value="Enviar" /></p>
    </form>';

    if (isset($_POST['enviar'])) {

        $categoria = $_POST['categoria'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $creador = trim($_POST['creador'] ?? '');
        $valoracion = $_POST['valoracion'] ?? '';
        $url = ($_POST['url'] ?? '');


        $imagen = file_get_contents($url);
        $nombrefile = str_replace(' ', '', $nombre);
        file_put_contents('imagenes/' . $nombrefile . '.jpg', $imagen);
        $url = 'imagenes/' . $nombrefile . '.jpg';




        $sql = "INSERT INTO todo (nombre, creador, valoracion, imagen, categoria) VALUES ('" . $nombre . "', '" . $creador . "',  '$valoracion','" . $url . "', '".$categoria."') ";
        $stmt = $mysql->prepare($sql);

        if (!$stmt) {
            die("Error en la consulta: " . $mysql->error);
        }




        if ($stmt->execute()) {
            echo "<p>Registro agregado correctamente a la tabla <strong>$categoria</strong>.</p>";
            echo "<a href='todo.php'>Ir a todo</a>";
        } else {
            echo "<p>Error al insertar: " . $stmt->error . "</p>";
        }


        $stmt->close();
    }

    $mysql->close();
    ?>
    <a href="todo.php">Volver</a>
</body>

</html>