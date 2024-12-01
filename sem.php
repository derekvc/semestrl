<?php
session_start();

// Configuración de conexión a la base de datos
$host = "localhost";
$dbname = "registro_db";
$username = "root";
$password = "";

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta para verificar usuario y contraseña
    $query = "SELECT contrasena FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['contrasena'];

        // Verificar la contraseña
        if (password_verify($contrasena, $hashed_password)) {
            $_SESSION['usuario'] = $usuario;
            header("Location: proyecto.html"); // Redirigir a página de bienvenida
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background-color: #d3c9a3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #d3c9a3;
            text-align: center;
            padding: 30px;
            border-radius: 15px;
            width: 350px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 36px;
            color: #064214;
            font-weight: bold;
        }

        h1 span {
            font-size: 36px;
            color: white;
            text-shadow: 2px 2px #000000;
        }

        label {
            font-size: 18px;
            color: black;
            font-weight: bold;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background-color: #064214;
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .secondary-button {
            background-color: #f4f4f4;
            color: black;
            border: 2px solid #ccc;
            margin-top: 10px;
            padding: 10px;
            cursor: pointer;
        }

        .secondary-button:hover {
            background-color: #e0e0e0;
        }

        .error {
            color: red;
            font-size: 20px;
            margin-top: 20px; /* Espaciado entre el error y los botones */
            text-align: center; /* Centrar el mensaje */
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>REAL <span>CAP</span></h1>
        <form method="post" action="">
            <label for="usuario">Usuarios:</label>
            <input type="text" name="usuario" id="usuario" placeholder="Ingrese su nombre de usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese su contraseña" required>
            <button type="submit">Iniciar Sesión</button>
            <button class="secondary-button" onclick="window.location.href='register.php'">Registrarse</button>
        </form>
    <!-- Aquí se coloca el mensaje de error abajo -->
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </div>

</body>
</html>