<?php

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

$conn = null;

try {
    // Conexión a la base de datos 
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Procesar el formulario cuando se envía
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $precioVenta = $_POST['precioVenta'];
        $cantidadStock = $_POST['cantidadStock'];
        $unidadMedida = $_POST['unidadMedida'];
        $descripcion = $_POST['descripcion'];

        // Llamar al procedimiento almacenado
        $stmt = $conn->prepare("CALL crear_producto(?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta: " . $conn->error);
        }
        
        $stmt->bind_param("siiss", $nombre, $precioVenta, $cantidadStock, $unidadMedida, $descripcion);
        
        if ($stmt->execute()) {
            echo "<div style='color: green; background-color: #e6ffe6; padding: 10px; border: 1px solid green; margin: 10px 0;'>";
            echo "Producto creado con éxito.";
            echo "</div>";
        } else {
            throw new Exception("Error al crear el producto: " . $stmt->error);
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
    <title>Crear Producto</title>
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
        input[type="text"],
        input[type="number"],
        textarea {
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
        <h1>Crear Nuevo Producto</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            
            <label for="precioVenta">Precio de Venta:</label>
            <input type="number" id="precioVenta" name="precioVenta" required>
            
            <label for="cantidadStock">Cantidad en Stock:</label>
            <input type="number" id="cantidadStock" name="cantidadStock" required>
            
            <label for="unidadMedida">Unidad de Medida:</label>
            <input type="text" id="unidadMedida" name="unidadMedida" required>
            
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion" required></textarea>
            
            <input type="submit" value="Crear Producto">
            <a href="./admiproduc.php" class="btn-volver">Volver</a>
        </form>

    </div>
</body>
</html>
  
