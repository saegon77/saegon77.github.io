<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dakara";

        $conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];
    $cantidad = $_POST['cantidad'];
    $cliente = $_POST['cliente'];
    $admin = $_POST['admin'];

    // Llamar al procedimiento almacenado
    $stmt = $conn->prepare("CALL sp_crear_venta(?, ?, ?, ?, ?)");
    $stmt->bind_param("siiii", $fecha, $total, $cantidad, $cliente, $admin);
    
    if ($stmt->execute()) {
        echo "Venta creada con éxito.";
    } else {
        echo "Error al crear la venta: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Venta</title>

    <style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f4f4f4;
}

h1 {
    color: #000;
    text-align: center;
}

form {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    color: #000;
}

input[type="date"],
input[type="number"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

input[type="submit"] {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #333;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #555;
}
    </style>
</head>
<body>
    <h1>Crear Nueva Venta</h1>
    <form method="post">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br>

        <label for="total">Total:</label>
        <input type="number" id="total" name="total" required><br>

        <label for="cantidad">Cantidad Total:</label>
        <input type="number" id="cantidad" name="cantidad" required><br>

        <label for="cliente">Código del Cliente:</label>
        <input type="number" id="cliente" name="cliente" required><br>

        <label for="admin">Código del Administrador:</label>
        <input type="number" id="admin" name="admin"><br>

        <input type="submit" value="Crear Venta">
    </form>
</body>
</html>