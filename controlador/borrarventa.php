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
    $codigo = $_POST['codigo'];

    // Llamar al procedimiento almacenado
    $stmt = $conn->prepare("CALL sp_eliminar_venta(?)");
    $stmt->bind_param("i", $codigo);
    
    if ($stmt->execute()) {
        echo "Venta eliminada con éxito.";
    } else {
        echo "Error al eliminar la venta: " . $stmt->error;
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
    <title>Eliminar Venta</title>
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
    <h1>Eliminar Venta</h1>
    <form method="post">
        <label for="codigo">Código de Venta a Eliminar:</label>
        <input type="number" id="codigo" name="codigo" required><br>

        <input type="submit" value="Eliminar Venta">
    </form>
</body>
</html>

