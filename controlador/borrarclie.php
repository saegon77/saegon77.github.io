<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cliente</title>
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
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"],
        .btn-volver {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover,
        .btn-volver:hover {
            background-color: #555;
        }
        .mensaje {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }
        .exito {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar Cliente</h1>
        
        <?php
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Configuración de la conexión a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "dakara";
            // Crear conexión
            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener datos del formulario
            $codigo = $_POST['codigo'];

            // Llamar al procedimiento almacenado
            $sql = "CALL sp_eliminar_cliente(?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $codigo);

            if ($stmt->execute()) {
                echo "<div class='mensaje exito'>Cliente eliminado exitosamente.</div>";
            } else {
                echo "<div class='mensaje error'>Error al eliminar cliente: " . $stmt->error . "</div>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="codigo">Código del Cliente a Eliminar:</label>
            <input type="number" id="codigo" name="codigo" required>

            <input type="submit" value="Eliminar Cliente">
        </form>

        <br>
        <a href="admiclie.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>