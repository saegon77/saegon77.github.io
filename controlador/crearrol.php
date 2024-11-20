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
    // Obtener datos del formulario
    $id_rol = $_POST['id_rol'];
    $descripcion = $_POST['descripcion'];
    $estado = $_POST['estado'];

    // Preparar y ejecutar el procedimiento almacenado
    $stmt = $conn->prepare("CALL CrearRol(?, ?, ?)");
    $stmt->bind_param("iss", $id_rol, $descripcion, $estado);

    // Ejecutar y mostrar resultado
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Nuevo rol creado con éxito</p>";
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
    <title>Crear Nuevo Rol</title>
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
        input[type="number"], input[type="text"] {
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
    <h2>Crear Nuevo Rol</h2>
    <!-- Formulario para crear un nuevo rol -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        ID Rol: <input type="number" name="id_rol" required><br>
        Descripción: <input type="text" name="descripcion" maxlength="20" required><br>
        Estado: <input type="text" name="estado" maxlength="15" required><br>
        <input type="submit" value="Crear Rol">
    </form>
    
    <!-- Botón Volver -->
    <a href="../controlador/admirol.php" class="btn">Volver</a>
</body>
</html>