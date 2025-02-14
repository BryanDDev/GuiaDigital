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
        header("Location: inicio_sesion.php");
        exit();
    }
} else {
    header("Location: inicio_sesion.php"); 
    exit();
}
$mysql = new mysqli("localhost", "root", "", "contenido_digital");

$categoria = $_GET['categoria'] ?? null;
$seleccion = $_GET['Ordenar'] ?? null;


if ($categoria) {
    

    if ($seleccion == "valoracion") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo WHERE categoria = '$categoria' ORDER BY valoracion DESC";
    } elseif ($seleccion == "ascendente") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo WHERE categoria = '$categoria' ORDER BY nombre ASC";
    } elseif ($seleccion == "descendente") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo WHERE categoria = '$categoria' ORDER BY nombre DESC";
    } else {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo WHERE categoria = '$categoria'";
    }
} else {
    if ($seleccion == "valoracion") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo ORDER BY valoracion DESC";
    } elseif ($seleccion == "ascendente") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo ORDER BY nombre ASC";
    } elseif ($seleccion == "descendente") {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo ORDER BY nombre DESC";
    } else {
        $sql = "SELECT nombre, creador, valoracion, imagen FROM todo";
    }
}

$registros = $mysql->query($sql) or die($mysql->error);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia Digital</title>
    <link rel="stylesheet" href="estilos/estilo1.css">
</head>
<body>
    <h1 class="titulo">Todo</h1>
    <nav class="navbar">
        <ul class="barra-nav">
            <li><a href="todo.php">Todo</a></li>
            <li><a href="todo.php?categoria=musica">Música</a></li>
            <li><a href="todo.php?categoria=peliculas">Películas</a></li>
            <li><a href="todo.php?categoria=videojuegos">Videojuegos</a></li>
            <li><a href="login_administrador.php"><img src="imagenes/admin.png" alt="login"></a></li>
        </ul>
    </nav>

    <main>
        <form method="get" name="formulario" target="_self">
            <input type="hidden" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>">
            <select class="opciones-ordenar" name="Ordenar">
                <option value="valoracion" <?php if ($seleccion == "valoracion") echo "selected"; ?>>Valoración</option>
                <option value="ascendente" <?php if ($seleccion == "ascendente") echo "selected"; ?>>Ascendente</option>
                <option value="descendente" <?php if ($seleccion == "descendente") echo "selected"; ?>>Descendente</option>
            </select>
            <input type="submit" value="Ordenar">
        </form>

        <div class="contenedor">
            <?php while ($reg = $registros->fetch_array()) : ?>
                <div class="card">
                    <img src="<?php echo htmlspecialchars($reg['imagen']); ?>" alt="<?php echo htmlspecialchars($reg['nombre']); ?>" width="100">
                    <h3><?php echo htmlspecialchars($reg['nombre']); ?></h3>
                    <p><strong>Creador:</strong> <?php echo htmlspecialchars($reg['creador']); ?></p>
                    <p><strong>Valoración:</strong> <?php echo htmlspecialchars($reg['valoracion']); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
       
      <a href="ciere_sesion.php">Cerrar sesión</a>
          
    </main>

    <?php $mysql->close(); ?>
</body>
</html>
