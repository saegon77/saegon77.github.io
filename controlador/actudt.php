<?php
$servername = "localhost"; // Servidor de la base de datos
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña del usuario
$dbname = "dakara"; // Nombre de la base de datos

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Termina el script y muestra un mensaje de error si la conexión falla
}

// Leer detalles del detalle de venta si se pasa un id en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "CALL sp_leer_detalle_venta(?)"; // Llamada al procedimiento almacenado para leer detalles
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Vincular parámetro
    $stmt->execute(); // Ejecutar la consulta
    $result = $stmt->get_result(); // Obtener el resultado
    $detalle = $result->fetch_assoc(); // Recuperar los datos en un array asociativo
    $stmt->close(); // Cerrar la consulta
}

// Manejar la actualización del detalle de venta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // ID del detalle a actualizar
    $subtotal = $_POST['subtotal']; // Subtotal del producto
    $cantidad = $_POST['cantidad']; // Cantidad del producto
    $producto_id = $_POST['producto_id']; // ID del producto
    
    $sql = "CALL sp_actualizar_detalle_venta(?, ?, ?, ?)"; // Llamada al procedimiento almacenado para actualizar el detalle
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $id, $subtotal, $cantidad, $producto_id); // Vincular parámetros
    
    // Ejecutar la consulta y mostrar un mensaje de éxito o error
    if ($stmt->execute()) {
        echo "<script>alert('Detalle de venta actualizado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al actualizar el detalle de venta');</script>";
    }
    $stmt->close(); // Cerrar la consulta
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Actualizar Detalle de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif; /* Estilo de fuente */
            margin: 0; /* Sin margen */
            padding: 20px; /* Espaciado interno */
            background-color: #f0f0f0; /* Color de fondo */
        }
        .container {
            max-width: 600px; /* Ancho máximo del contenedor */
            margin: 0 auto; /* Centrado horizontal */
            background-color: white; /* Color de fondo del contenedor */
            padding: 20px; /* Espaciado interno del contenedor */
            border-radius: 5px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra alrededor del contenedor */
        }
        h2 {
            color: #333; /* Color del texto del encabezado */
            text-align: center; /* Centrado del texto */
        }
        .form-group {
            margin-bottom: 15px; /* Espaciado inferior entre grupos de formularios */
        }
        label {
            display: block; /* Mostrar etiquetas como bloque */
            margin-bottom: 5px; /* Espaciado inferior para etiquetas */
            color: #333; /* Color del texto de las etiquetas */
        }
        input[type="number"],
        select {
            width: 100%; /* Ancho completo para los campos */
            padding: 8px; /* Espaciado interior */
            border: 1px solid #ddd; /* Borde de los campos */
            border-radius: 4px; /* Bordes redondeados */
            box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
        }
        .btn {
            background-color: #333; /* Color de fondo del botón */
            color: white; /* Color del texto del botón */
            padding: 10px 20px; /* Espaciado interno del botón */
            border: none; /* Sin borde */
            border-radius: 4px; /* Bordes redondeados */
            cursor: pointer; /* Cambiar cursor al pasar por encima */
        }
        .btn:hover {
            background-color: #555; /* Color de fondo al pasar el cursor */
        }
        .btn-volver {
            background-color: #666; /* Color de fondo para el botón volver */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Actualizar Detalle de Venta</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $detalle['deve_codigo']; ?>"> <!-- Campo oculto para el ID -->
            <div class="form-group">
                <label for="subtotal">Subtotal:</label>
                <input type="number" id="subtotal" name="subtotal" value="<?php echo $detalle['deve_subtotal']; ?>" required> <!-- Campo para subtotal -->
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="<?php echo $detalle['deve_cantidad_por_producto']; ?>" required> <!-- Campo para cantidad -->
            </div>
            <div class="form-group">
                <label for="producto_id">Producto:</label>
                <select id="producto_id" name="producto_id" required> <!-- Selección de productos -->
                    <?php
                    $sql = "SELECT prod_codigo, prod_nombre FROM producto"; // Consulta para obtener productos
                    $result = $conn->query($sql); // Ejecutar consulta
                    while($row = $result->fetch_assoc()) { // Iterar sobre los resultados
                        $selected = ($row['prod_codigo'] == $detalle['prod_codigo']) ? 'selected' : ''; // Verificar si el producto es el seleccionado
                        echo "<option value='".$row['prod_codigo']."' ".$selected.">".$row['prod_nombre']."</option>"; // Mostrar opciones de productos
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Actualizar Detalle</button> <!-- Botón para enviar el formulario -->
                <a href="index.php" class="btn btn-volver">Volver</a> <!-- Enlace para volver -->
            </div>
        </form>
    </div>
</body>
</html>
