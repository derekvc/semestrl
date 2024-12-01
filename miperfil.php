<?php
session_start();

// Verificar si hay una sesión activa
if (!isset($_SESSION['usuario'])) {
    // Redirigir al inicio de sesión si no está autenticado
    header("Location: login.php");
    exit;
}

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

// Recuperar datos del usuario
$usuario = $_SESSION['usuario'];
$query = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc(); // Datos del usuario
} else {
    // Redirigir al inicio de sesión si no encuentra el usuario
    header("Location: login.php");
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="estilop.css">
</head>
<body>
    <header>
        <h1>Real Cap</h1>
        <nav>
            <a href="Gorra.php">Gorras</a>
            <a href="Accesorios.php">Accesorios</a>
            <a href="proyecto.html">Quienes somos</a>
            <a href="miperfil.php">Mi perfil</a>
            <a href="sem.php">Cerrar sesión</a>
        </nav>
        <a href="#" class="basket">Basket (3)</a>
    </header>

    <main class="profile-container">
        <section class="user-details">
            <h2><?php echo htmlspecialchars($user['usuario']); ?></h2>
            <p>Bienvenido a tu perfil, <?php echo htmlspecialchars($user['usuario']); ?>.</p>
            

            <div class="details">
                <h3>Detalles de usuario</h3>
                <p><strong>Dirección de correo:</strong> <?php echo htmlspecialchars($user['correo']); ?></p>
                <p><strong>Lugar de residencia:</strong> <?php echo htmlspecialchars($user['lugarResidencia'] ?? 'No especificado'); ?></p>
            </div>
        </section>
    </main>
</body>
</html>
