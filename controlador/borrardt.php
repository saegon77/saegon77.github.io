<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dakara";

        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    if (isset($_POST['confirmar'])) {
        $sql = "CALL sp_eliminar_detalle_venta(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Detalle de venta eliminado exitosamente');
                    window.location.href = 'admidt.php';
                  </script>";
        } else {
            echo "<script>alert('Error al eliminar el detalle de venta');</script>";
        }
        $stmt->close();
    }
    
    // Obtener información del detalle de venta
    $sql = "CALL sp_leer_detalle_venta(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $detalle = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Detalle de Venta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        .info {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #555;
        }
        .btn-eliminar {
            background-color: #ff0000;
        }
        .btn-volver {
            background-color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Eliminar Detalle de Venta</h2>
        <div class="info">
            <p><strong>ID Detalle:</strong> <?php echo $detalle['deve_codigo']; ?></p>
            <p><strong>Subtotal:</strong> <?php echo $detalle['deve_subtotal']; ?></p>
            <p><strong>Cantidad:</strong> <?php echo $detalle['deve_cantidad_por_producto']; ?></p>
            <p><strong>Producto:</strong> <?php echo $detalle['prod_nombre']; ?></p>
        </div>
        <p>¿Está seguro que desea eliminar este detalle de venta?</p>
        <form method="POST">
            <input type="hidden" name="confirmar" value="1">
            <button type="submit" class="btn btn-eliminar">Eliminar</button>
            <a href="admidt.php" class="btn btn-volver">Volver</a>
        </form>
    </div>
</body>
</html>