<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
            padding: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 16px;
            margin-bottom: 15px;
        }

        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 3px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #555;
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
    <form action="" method="post">
        <label for="usua_nombre">Ingrese Nombre de Usuario:</label>
        <input type="text" id="usua_nombre" name="usua_nombre" required>

        <label for="usua_clave">Ingrese Clave:</label>
        <input type="password" id="usua_clave" name="usua_clave" required>

        <label for="id_rol">Seleccione Rol:</label>
        <select id="id_rol" name="id_rol" required>
            <option value="">Seleccione un rol</option>
            <option value="1">Rol 1</option>
            <option value="2">Rol 2</option>
        </select>

        <input type="submit" value="Registrar Usuario">
    </form>
    <a href="../controlador/admiusu.php" class="btn">Volver</a>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        // Preparar el procedimiento almacenado
        $stmt = $conn->prepare("CALL InsertarUsuario(?, ?, ?)");

        // Enlazar los parámetros de entrada
        $stmt->bind_param("ssi", $usua_nombre, $usua_clave, $id_rol);

        // Obtener los valores de los parámetros desde el formulario HTML
        $usua_nombre = $_POST['usua_nombre'];
        $usua_clave = $_POST['usua_clave'];
        $id_rol = $_POST['id_rol'];

        // Ejecutar el procedimiento almacenado
        if ($stmt->execute()) {
            echo "El usuario se ha registrado correctamente.";
        } else {
            echo "Ha ocurrido un error al registrar el usuario: " . $conn->error;
        }
        header("Location:admiusu.php");
        // Cerrar la conexión
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>