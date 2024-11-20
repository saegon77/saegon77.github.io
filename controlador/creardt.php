<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subtotal = $_POST['subtotal'];
    $cantidad = $_POST['cantidad'];
    $venta_id = $_POST['venta_id'];
    $producto_id = $_POST['producto_id'];
    
    $sql = "CALL sp_crear_detalle_venta(?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $subtotal, $cantidad, $venta_id, $producto_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Detalle de venta creado exitosamente');</script>";
    } else {
        echo "<script>alert('Error al crear el detalle de venta');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Detalle de Venta</title>
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
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #555;
        }
        .btn-volver {
            background-color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Crear Detalle de Venta</h2>
        <form method="POST">
            <div class="form-group">
                <label for="subtotal">Subtotal:</label>
                <input type="number" id="subtotal" name="subtotal" required>
            </div>
            <div class="form-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" required>
            </div>
            <div class="form-group">
                <label for="venta_id">Venta:</label>
                <select id="venta_id" name="venta_id" required>
                    <?php
                    $sql = "SELECT vent_codigo FROM venta";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['vent_codigo']."'>Venta #".$row['vent_codigo']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="producto_id">Producto:</label>
                <select id="producto_id" name="producto_id" required>
                    <?php
                    $sql = "SELECT prod_codigo, prod_nombre FROM producto";
                    $result = $conn->query($sql);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='".$row['prod_codigo']."'>".$row['prod_nombre']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Crear Detalle</button>
                <a href="index.php" class="btn btn-volver">Volver</a>
            </div>
        </form>
    </div>
</body>
</html>