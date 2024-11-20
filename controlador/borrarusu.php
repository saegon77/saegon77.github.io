<?php
// Iniciar sesión y verificar permisos si es necesario
session_start();
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

$mensaje = '';

// Verificar si se ha enviado un ID de usuario para eliminar
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $usua_codigo = intval($_GET['id']);

    // Llamar al procedimiento almacenado
    $sql = "CALL sp_eliminar_usuario(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usua_codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $mensaje = $row['mensaje'];
    } else {
        $mensaje = "Error al eliminar el usuario.";
    }

    $stmt->close();
} else {
    $mensaje = "ID de usuario no válido.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
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
            color: #333;
            text-align: center;
        }
        .message {
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            background-color: #333;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Eliminar Usuario</h1>
        <div class="message">
            <?php echo $mensaje; ?>
        </div>
        <a href="admiusu.php" class="btn">Volver a Gestión de Usuarios</a>
    </div>
</body>
</html>