<?php
// Configuración de conexión
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

// Inicializar variables para mensajes
$mensaje_exitoso = "";
$mensaje_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $lugarResidencia = $_POST['lugarResidencia'];
    $contrasena = $_POST['contrasena'];
    $confirmar_contrasena = $_POST['confirmar_contrasena'];

    // Verificar que las contraseñas coinciden
    if ($contrasena !== $confirmar_contrasena) {
        $mensaje_error = "Error: Las contraseñas no coinciden.";
    } else {
        // Encriptar la contraseña
        $contrasena_hashed = password_hash($contrasena, PASSWORD_BCRYPT);

        // Verificar si el correo ya existe
        $checkQuery = "SELECT * FROM usuarios WHERE correo = ?";
        $stmtCheck = $conn->prepare($checkQuery);
        $stmtCheck->bind_param("s", $correo);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            $mensaje_error = "Error: Este correo ya está registrado.";
        } else {
            // Insertar datos en la base de datos
            $sql = "INSERT INTO usuarios (correo, usuario, lugarResidencia, contrasena) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $correo, $usuario, $lugarResidencia, $contrasena_hashed);

            if ($stmt->execute()) {
                $mensaje_exitoso = "Registro exitoso. Bienvenido, $usuario.";
            } else {
                $mensaje_error = "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
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

        .error{
            color: red;
            font-size: 20px;
            margin-top: 20px;
            text-align: center;
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


        .success {
            color: green;
            font-size: 14px;
            margin-top: 10px; /* Espaciado con el botón */
            text-align: center; /* Centrar el mensaje */
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registro</h1>
        <form method="post" action="">
            <label for="correo">Correo electrónico:</label>
            <input type="email" name="correo" id="correo" placeholder="ejemplo@correo.com" required>
            <label for="usuario">Nombre de usuario:</label>
            <input type="text" name="usuario" id="usuario" placeholder="Ingrese su nombre" required>
            <label for="lugarResidencia">Ubicación:</label>
            <input type="text" name="lugarResidencia" id="ubicacion" placeholder="Ciudad, distrito, calle" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese una contraseña" required>
            <label for="confirmar_contrasena">Confirmar contraseña:</label>
            <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" placeholder="Ingrese su contraseña" required>
            <button type="submit">Registrarse</button>
            <button class="secondary-button" onclick="window.location.href='sem.php'">Iniciar Sesión</button>
        </form>
        <!-- Muestra el mensaje de error o éxito aquí -->
        <?php if (!empty($mensaje_error)) echo "<p class='error'>$mensaje_error</p>"; ?>
        <?php if (!empty($mensaje_exitoso)) echo "<p class='success'>$mensaje_exitoso</p>"; ?>

    </div>
</body>
</html>
