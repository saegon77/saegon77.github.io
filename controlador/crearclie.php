<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario y Cliente</title>
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
        input[type="text"],
        input[type="password"],
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
        <h1>Crear Cliente</h1>
        
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

            // Obtener datos del formulario
            $usua_nombre = $_POST['usua_nombre'];
            $usua_clave = $_POST['usua_clave'];
            $id_rol = $_POST['id_rol'];
            $identificacion = $_POST['identificacion'];
            $tipoIdentificacion = $_POST['tipoIdentificacion'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $celular = $_POST['celular'];
            $direccion = $_POST['direccion'];

            // Llamar al procedimiento almacenado
            $sql = "CALL sp_crear_usuario_cliente(?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssissssss", $usua_nombre, $usua_clave, $id_rol, $identificacion, $tipoIdentificacion, $nombre, $apellido, $celular, $direccion);

            if ($stmt->execute()) {
                echo "<div class='mensaje exito'>Usuario y cliente creados exitosamente.</div>";
            } else {
                echo "<div class='mensaje error'>Error al crear usuario y cliente: " . $stmt->error . "</div>";
            }

            $stmt->close();
            $conn->close();
        }
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="usua_nombre">Nombre de Usuario:</label>
            <input type="text" id="usua_nombre" name="usua_nombre" required>

            <label for="usua_clave">Contraseña:</label>
            <input type="password" id="usua_clave" name="usua_clave" required>

            <label for="id_rol">ID del Rol:</label>
            <input type="number" id="id_rol" name="id_rol" required>

            <label for="identificacion">Identificación:</label>
            <input type="text" id="identificacion" name="identificacion" required>

            <label for="tipoIdentificacion">Tipo de Identificación:</label>
            <input type="text" id="tipoIdentificacion" name="tipoIdentificacion" required>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <input type="submit" value="Crear Usuario y Cliente">
        </form>

        <br>
        <a href="admiclie.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>