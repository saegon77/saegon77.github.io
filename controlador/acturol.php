<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Nombre del servidor de la base de datos
$username = "root"; // Nombre de usuario de la base de datos
$password = ""; // Contraseña de la base de datos
$dbname = "dakara"; // Nombre de la base de datos

// Crear conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    // Si hay un error en la conexión, muestra un mensaje y detiene la ejecución
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario enviados mediante el método POST
    $id_rol = $_POST['id_rol']; // ID del rol a actualizar
    $descripcion = $_POST['descripcion']; // Nueva descripción del rol
    $estado = $_POST['estado']; // Nuevo estado del rol

    // Preparar la consulta para ejecutar el procedimiento almacenado
    $stmt = $conn->prepare("CALL ActualizarRol(?, ?, ?)"); // Preparar la consulta
    $stmt->bind_param("iss", $id_rol, $descripcion, $estado); // Vincular los parámetros a la consulta

    // Ejecutar la consulta y mostrar el resultado
    if ($stmt->execute()) { // Si la ejecución es exitosa
        // Mostrar un mensaje de éxito
        echo "<p style='color: green;'>Rol actualizado con éxito</p>";
    } else {
        // Mostrar un mensaje de error si la ejecución falla
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    // Cerrar la declaración para liberar recursos
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Actualizar Rol</title> <!-- Título de la página -->
    <style>
        /* Estilos en blanco y negro para la presentación */
        body {
            font-family: Arial, sans-serif; /* Tipo de fuente */
            background-color: #f0f0f0; /* Color de fondo de la página */
            color: #333; /* Color del texto */
            line-height: 1.6; /* Altura de línea */
            padding: 20px; /* Espaciado interno de la página */
        }
        h2 {
            color: #000; /* Color del encabezado */
            border-bottom: 2px solid #000; /* Línea inferior del encabezado */
            padding-bottom: 10px; /* Espacio inferior del encabezado */
        }
        form {
            background-color: #fff; /* Color de fondo del formulario */
            padding: 20px; /* Espaciado interno del formulario */
            border: 1px solid #ddd; /* Borde del formulario */
            max-width: 400px; /* Ancho máximo del formulario */
            margin: 0 auto; /* Centrando el formulario en la página */
        }
        input[type="number"], input[type="text"] {
            width: 100%; /* Ancho completo para los campos de entrada */
            padding: 8px; /* Espaciado interno para los campos de entrada */
            margin: 10px 0; /* Espaciado vertical entre campos */
            border: 1px solid #999; /* Borde de los campos de entrada */
        }
        input[type="submit"] {
            background-color: #333; /* Color de fondo del botón de envío */
            color: #fff; /* Color del texto del botón */
            padding: 10px 15px; /* Espaciado interno del botón */
            border: none; /* Sin borde para el botón */
            cursor: pointer; /* Cambiar cursor al pasar por encima */
        }
        input[type="submit"]:hover {
            background-color: #000; /* Color de fondo al pasar el cursor por encima */
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
    <h2>Actualizar Rol</h2> <!-- Encabezado de la sección de actualización -->
    <!-- Formulario para actualizar un rol existente -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>"> <!-- Enviar el formulario a la misma página -->
        ID Rol: <input type="number" name="id_rol" required><br> <!-- Campo para ID del rol, requerido -->
        Nueva Descripción: <input type="text" name="descripcion" maxlength="20" required><br> <!-- Campo para nueva descripción, requerido con límite de caracteres -->
        Nuevo Estado: <input type="text" name="estado" maxlength="15" required><br> <!-- Campo para nuevo estado, requerido con límite de caracteres -->
        <input type="submit" value="Actualizar Rol"> <!-- Botón para enviar el formulario -->
    </form>
    <a href="../controlador/admirol.php" class="btn">Volver</a>
</body>
</html>
