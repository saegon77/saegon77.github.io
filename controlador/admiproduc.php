<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

$conn = null;
$productos = [];
$error_mensaje = '';

try {
    // Conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Procesar la búsqueda si se envía el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
        $busqueda = $_POST['busqueda'];
        
        // Consulta directa en lugar del procedimiento almacenado
        $sql = "SELECT * FROM producto WHERE prod_nombre LIKE ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
        
        $busqueda = "%$busqueda%";
        $stmt->bind_param("s", $busqueda);
        
    } else {
        // Si no hay búsqueda, mostrar todos los productos
        $sql = "SELECT * FROM producto";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    
    $stmt->close();

} catch (Exception $e) {
    $error_mensaje = "Error: " . $e->getMessage();
} finally {
    // Cerrar la conexión si está establecida
    if ($conn) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <style>
        /* Estilos en blanco y negro */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #000;
            text-align: center;
        }
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input[type="text"] {
            width: 70%;
            padding: 8px;
            margin-right: 10px;
        }
        .search-form input[type="submit"] {
            padding: 8px 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 5px;
        }
        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Productos</h1>
        
        <?php if ($error_mensaje): ?>
            <div style="color: red; margin-bottom: 20px;">
                <?php echo htmlspecialchars($error_mensaje); ?>
            </div>
        <?php endif; ?>
        
        <!-- Formulario de búsqueda -->
        <form class="search-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="text" name="busqueda" placeholder="Buscar productos...">
            <input type="submit" name="buscar" value="Buscar">
            <a href="../vista/admin.html" class="btn">Volver</a>
        </form>
        
        <div class="actions">
            <a href="./crearproduc.php" class="btn">Crear Producto</a>
            <a href="./actuproduc.php" class="btn">Actualizar Producto</a>
            <a href="./borrarproduc.php" class="btn">Eliminar Producto</a>

        </div>
        <br>
        <!-- Tabla de productos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio de Venta</th>
                    <th>Cantidad en Stock</th>
                    <th>Unidad de Medida</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">No se encontraron productos</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($producto['prod_codigo']); ?></td>
                        <td><?php echo htmlspecialchars($producto['prod_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($producto['prod_precioventa']); ?></td>
                        <td><?php echo htmlspecialchars($producto['prod_cantidadstock']); ?></td>
                        <td><?php echo htmlspecialchars($producto['prod_unidadmedia']); ?></td>
                        <td><?php echo htmlspecialchars($producto['prod_descripcion']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>
</html>
  
