```php
<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

// Conexión a la base de datos 
$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error); // Si hay un error, se detiene la ejecución mostrando el mensaje
}

$mensaje = ''; // Variable para almacenar mensajes de estado
$producto = null; // Variable para almacenar datos del producto

// Buscar producto por ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) { // Verificar si se ha enviado el formulario de búsqueda
    $id_buscar = $_POST['id_buscar']; // Obtener el ID ingresado
    $stmt = $conexion->prepare("CALL consultar_producto(?)"); // Preparar llamada al procedimiento almacenado
    $stmt->bind_param("i", $id_buscar); // Asignar el parámetro del ID

    if ($stmt->execute()) { // Ejecutar la consulta
        $resultado = $stmt->get_result(); // Obtener el resultado
        if ($resultado && $resultado->num_rows > 0) { // Verificar si se encontró el producto
            $producto = $resultado->fetch_assoc(); // Guardar datos del producto en la variable
        } else {
            $mensaje = "No se encontró ningún producto con ese ID."; // Mostrar mensaje si no se encontró
        }
    } else {
        $mensaje = "Error al ejecutar la consulta: " . $stmt->error; // Mostrar mensaje en caso de error
    }
    $stmt->close(); // Cerrar la consulta preparada
}

// Procesar el formulario para actualizar el producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) { // Verificar si se ha enviado el formulario de actualización
    $id = $_POST['id']; // Obtener el ID del producto
    $nombre = $_POST['nombre']; // Obtener el nombre del producto
    $precioVenta = $_POST['precioVenta']; // Obtener el precio de venta
    $cantidadStock = $_POST['cantidadStock']; // Obtener la cantidad en stock
    $unidadMedida = $_POST['unidadMedida']; // Obtener la unidad de medida
    $descripcion = $_POST['descripcion']; // Obtener la descripción

    $stmt = $conexion->prepare("CALL actualizar_producto(?, ?, ?, ?, ?, ?)"); // Preparar llamada al procedimiento almacenado para actualizar
    $stmt->bind_param("isidss", $id, $nombre, $precioVenta, $cantidadStock, $unidadMedida, $descripcion); // Asignar los parámetros

    if ($stmt->execute()) { // Ejecutar la consulta
        $mensaje = "Producto actualizado con éxito."; // Mostrar mensaje de éxito
        $stmt->close(); // Cerrar la consulta preparada

        $stmt = $conexion->prepare("CALL consultar_producto(?)"); // Preparar consulta para recargar el producto actualizado
        $stmt->bind_param("i", $id); // Asignar el ID como parámetro
        if ($stmt->execute()) { // Ejecutar la consulta
            $resultado = $stmt->get_result(); // Obtener el resultado
            if ($resultado && $resultado->num_rows > 0) { // Verificar si se encontró el producto
                $producto = $resultado->fetch_assoc(); // Guardar datos del producto en la variable
            } else {
                $mensaje .= " Pero no se pudo recargar la información actualizada."; // Mostrar mensaje si no se pudo recargar
            }
        } else {
            $mensaje .= " Pero no se pudo recargar la información actualizada."; // Mostrar mensaje en caso de error
        }
    } else {
        $mensaje = "Error al actualizar el producto: " . $stmt->error; // Mostrar mensaje de error
    }
    
    $stmt->close(); // Cerrar la consulta preparada
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Producto</title>
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
        h1, h2 {
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
        .mensaje {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Actualizar Producto</h1>
        
        <?php if ($mensaje): ?> <!-- Mostrar mensaje si existe -->
            <div class="mensaje"><?php echo $mensaje; ?></div>
        <?php endif; ?>

        <h2>Buscar Producto</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- Formulario para buscar producto -->
            <label for="id_buscar">ID del Producto:</label>
            <input type="number" id="id_buscar" name="id_buscar" required> <!-- Campo para ingresar ID -->
            <input type="submit" name="buscar" value="Buscar Producto"> <!-- Botón de búsqueda -->
        </form>

        <?php if ($producto): ?> <!-- Mostrar formulario de actualización si se encontró el producto -->
            <h2>Actualizar Producto</h2>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"> <!-- Formulario para actualizar producto -->
                <input type="hidden" name="id" value="<?php echo $producto['prod_codigo']; ?>"> <!-- Campo oculto con el ID -->

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['prod_nombre']); ?>" required>

                <label for="precioVenta">Precio de Venta:</label>
                <input type="number" id="precioVenta" name="precioVenta" value="<?php echo $producto['prod_precioventa']; ?>" step="0.01" required>

                <label for="cantidadStock">Cantidad en Stock:</label>
                <input type="number" id="cantidadStock" name="cantidadStock" value="<?php echo $producto['prod_cantidadstock']; ?>" required>

                <label for="unidadMedida">Unidad de Medida:</label>
                <input type="text" id="unidadMedida" name="unidadMedida" value="<?php echo htmlspecialchars($producto['prod_unidadmedia']); ?>" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($producto['prod_descripcion']); ?></textarea>

                <input type="submit" name="actualizar" value="Actualizar Producto"> <!-- Botón de actualización -->
            </form>
        <?php endif; ?>

        <a href="./admiproduc.php" class="btn-volver">Volver</a> <!-- Enlace para volver -->
    </div>
</body>
</html>
```