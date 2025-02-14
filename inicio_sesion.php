<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador de Contenido</title>
    <link rel="stylesheet" href="estilos/estilo1.css">
</head>

<body>
    <h1 class="titulo-index">GUIA DIGITAL</h1>
    <main>
        <div class="login-container">

            <form class="login" action="inicio_sesion.php" method="POST">
                <h2 class="inicio-sesion">Iniciar Sesión</h2>
                <input type="text" name="username" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
                <h6><a href="registro.php">registro</a>

            </form>
    </main>


    </div>

    <?php
    session_start();
    $conexion = mysqli_connect("localhost", "root", "", "login")
        or die("Conexion fallida!");

    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']) ?? '';
        $password = trim($_POST['password']) ?? '';

        $registros = mysqli_query(
            $conexion,
            "SELECT id, username, password FROM usuario WHERE username = '$username'"
        )
            or die("Problemas en el select: " . mysqli_error($conexion));

        if ($reg = mysqli_fetch_array($registros)) {
            if ($reg['password'] === $password) {
                $_SESSION['id'] = $reg['id'];

                // GENERAR UN TOKEN ÚNICO
                $token = bin2hex(random_bytes(32));
                mysqli_query(
                    $conexion,
                    "UPDATE usuario SET token = '$token' WHERE id = " . $reg['id']
                )
                    or die("Problemas en el update: " . mysqli_error($conexion));

                setcookie("user_token", $token, time() + (86400 * 30), "/", "", true, true);

                header("Location: todo.php");
                exit();
            } else {

                echo "<h1>Contraseña incorrecta</h1>";
                echo "<a href='index.html'>Volver al login</a>";
            }
        } else {
            echo "<h1>Usuario no encontrado</h1>";
            echo "<a href='index.html'>Volver al login</a>";
        }
    } else {
        echo "<h1>Por favor, complete todos los campos</h1>";
        echo "<a href='index.html'>Volver al login</a>";
    }

    mysqli_close($conexion);
    ?>
</body>

</html>