<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del rol a eliminar
    $id_rol = $_POST['id_rol'];

    // Preparar y ejecutar el procedimiento almacenado
    $stmt = $conn->prepare("CALL EliminarRol(?)");
    $stmt->bind_param("i", $id_rol);

    // Ejecutar y mostrar resultado
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Rol eliminado con éxito</p>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    // Cerrar la declaración
    $stmt->close();
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Rol</title>
    <style>
        /* Estilos en blanco y negro */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        h2 {
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            max-width: 400px;
            margin: 0 auto;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #999;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #000;
        }
        .btn {
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn:hover {
            background-color: #000;
        }
    </style>
</head>
<body>
    <h2>Eliminar Rol</h2>
    <!-- Formulario para eliminar un rol -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        ID Rol a eliminar: <input type="number" name="id_rol" required><br>
        <input type="submit" value="Eliminar Rol">         

    </form>
    <a href="../controlador/admirol.php" class="btn">Volver</a>
</body>
</html>