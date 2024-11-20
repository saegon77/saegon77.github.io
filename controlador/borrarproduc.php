<?php


// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

$conn = null;
$mensaje = '';

try {
    // Conexión a la base de datos
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Procesar la eliminación si se proporciona un ID
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
        $id = $_POST['id'];
        
        // Llamar al procedimiento almacenado
        $stmt = $conn->prepare("CALL eliminar_producto(?)");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
        
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $mensaje = "<div style='color: green; background-color: #e6ffe6; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
            $mensaje .= "Producto con ID $id eliminado con éxito.";
            $mensaje .= "</div>";
        } else {
            throw new Exception("Error al eliminar el producto: " . $stmt->error);
        }
        
        $stmt->close();
    }
} catch (Exception $e) {
    manejar_error($e->getMessage());
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
    <title>Eliminar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .container {
            max-width: 600px;
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
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"],
        .btn-volver {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover,
        .btn-volver:hover {
            background-color: #555;
        }
        .btn-volver {
            display: inline-block;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar Producto</h1>
        <?php 
        if ($mensaje) {
            echo $mensaje;
        }
        ?>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="id">ID del Producto a Eliminar:</label>
            <input type="number" id="id" name="id" required>
            <input type="submit" value="Eliminar Producto">
        </form>
        <a href="./admiproduc.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>