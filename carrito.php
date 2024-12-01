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

// Asegurarse de que el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Usuario actual
$usuario = $_SESSION['usuario'];

// Agregar producto al carrito
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['producto_id'])) {
    $producto_id = intval($_POST['producto_id']);
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    // Verificar si el producto ya está en el carrito
    $query = "SELECT id FROM carrito WHERE usuario = ? AND producto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $usuario, $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Actualizar cantidad si ya existe
        $query = "UPDATE carrito SET cantidad = cantidad + ? WHERE usuario = ? AND producto_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isi", $cantidad, $usuario, $producto_id);
    } else {
        // Insertar nuevo producto en el carrito
        $query = "INSERT INTO carrito (usuario, producto_id, cantidad) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sii", $usuario, $producto_id, $cantidad);
    }
    $stmt->execute();
    $stmt->close();
}

// Eliminar producto del carrito
if (isset($_GET['eliminar'])) {
    $producto_id = intval($_GET['eliminar']);
    $query = "DELETE FROM carrito WHERE usuario = ? AND producto_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $usuario, $producto_id);
    $stmt->execute();
    $stmt->close();
}

// Listar productos
$query = "SELECT * FROM productos";
$productos = $conn->query($query);

// Obtener carrito del usuario
$query = "SELECT c.id, p.nombre, p.precio, c.cantidad 
          FROM carrito c 
          JOIN productos p ON c.producto_id = p.id 
          WHERE c.usuario = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$carrito = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .productos, .carrito {
            margin: 20px 0;
        }

        .producto, .carrito-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .eliminar {
            background-color: #dc3545;
        }

        .eliminar:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Carrito de Compras</h1>

        <section class="productos">
            <h2>Productos Disponibles</h2>
            <?php while ($producto = $productos->fetch_assoc()) { ?>
                <div class="producto">
                    <div>
                        <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <p><strong>Precio:</strong> $<?php echo number_format($producto['precio'], 2); ?></p>
                    </div>
                    <form method="post">
                        <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                        <input type="number" name="cantidad" value="1" min="1">
                        <button type="submit">Agregar al Carrito</button>
                    </form>
                </div>
            <?php } ?>
        </section>

        <section class="carrito">
            <h2>Tu Carrito</h2>
            <?php if ($carrito->num_rows > 0) { ?>
                <?php while ($item = $carrito->fetch_assoc()) { ?>
                    <div class="carrito-item">
                        <div>
                            <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
                            <p><strong>Precio:</strong> $<?php echo number_format($item['precio'], 2); ?></p>
                            <p><strong>Cantidad:</strong> <?php echo $item['cantidad']; ?></p>
                            <p><strong>Total:</strong> $<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></p>
                        </div>
                        <a href="?eliminar=<?php echo $item['id']; ?>" class="eliminar">Eliminar</a>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Tu carrito está vacío.</p>
            <?php } ?>
        </section>
    </div>
</body>
</html>
