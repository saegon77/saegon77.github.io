<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        /* Estilos para el contenedor principal */
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Estilos para el título */
        h1 {
            text-align: center;
            color: #333;
        }

        /* Estilos para el formulario */
        form {
            display: flex;
            flex-direction: column;
        }

        /* Estilos para las etiquetas */
        label {
            margin-top: 10px;
            font-weight: bold;
        }

        /* Estilos para los campos de entrada */
        input[type="number"],
        input[type="text"],
        input[type="password"] {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Estilos para el botón de envío */
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        /* Estilos para el mensaje de resultado */
        .mensaje {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
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
    <div class="container">
        <h1>Actualizar Usuario</h1>
        
        <!-- Formulario para actualizar usuario -->
        <form action="" method="POST">
            <label for="usua_codigo">Código de Usuario:</label>
            <input type="number" id="usua_codigo" name="usua_codigo" required>
            
            <label for="usua_nombre">Nuevo Nombre de Usuario:</label>
            <input type="text" id="usua_nombre" name="usua_nombre">
            
            <label for="usua_clave">Nueva Clave:</label>
            <input type="password" id="usua_clave" name="usua_clave">
            
            <input type="submit" value="Actualizar Usuario">
        </form>
        <a href="../controlador/admiusu.php" class="btn">Volver</a>
        <?php
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Configuración de la conexión a la base de datos
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "dakara";
            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            // Obtener y sanitizar los datos del formulario
            $usua_codigo = $conn->real_escape_string($_POST['usua_codigo']);
            $usua_nombre = !empty($_POST['usua_nombre']) ? $conn->real_escape_string($_POST['usua_nombre']) : null;
            $usua_clave = !empty($_POST['usua_clave']) ? $conn->real_escape_string($_POST['usua_clave']) : null;

            // Preparar la llamada al procedimiento almacenado
            $sql = "CALL sp_actualizar_usuario(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $usua_codigo, $usua_nombre, $usua_clave);

            // Ejecutar el procedimiento
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar el resultado
            if ($row = $result->fetch_assoc()) {
                echo "<div class='mensaje'>";
                echo "Filas actualizadas: " . $row['filas_actualizadas'];
                echo "</div>";
            } else {
                echo "<div class='mensaje'>Error al actualizar el usuario.</div>";
            
            }
            header("Location:admiusu.php");
            // Cerrar la conexión y liberar recursos
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>