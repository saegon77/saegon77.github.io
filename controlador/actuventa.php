<?php
// Configuración de la conexión a la base de datos
$servername = "localhost"; // Nombre del servidor de la base de datos
$username = "root"; // Usuario de la base de datos
$password = ""; // Contraseña del usuario de la base de datos
$dbname = "dakara"; // Nombre de la base de datos

// Conexión a la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error); // Mostrar error si no se puede conectar
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $codigo = $_POST['codigo']; // Código de venta
    $fecha = $_POST['fecha']; // Nueva fecha de la venta
    $total = $_POST['total']; // Nuevo total de la venta
    $cantidad = $_POST['cantidad']; // Nueva cantidad total
    $cliente = $_POST['cliente']; // Nuevo código del cliente
    $admin = $_POST['admin']; // Nuevo código del administrador

    // Llamar al procedimiento almacenado
    $stmt = $conn->prepare("CALL sp_actualizar_venta(?, ?, ?, ?, ?, ?)"); // Preparar la llamada al procedimiento
    $stmt->bind_param("isiiii", $codigo, $fecha, $total, $cantidad, $cliente, $admin); // Vincular los parámetros
    
    // Ejecutar el procedimiento y mostrar resultado
    if ($stmt->execute()) {
        echo "Venta actualizada con éxito."; // Mensaje de éxito
    } else {
        echo "Error al actualizar la venta: " . $stmt->error; // Mensaje de error
    }

    $stmt->close(); // Cerrar la declaración
}

$conn->close(); // Cerrar la conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Venta</title>
    <style>
        /* Estilos para la página */
        body {
            font-family: Arial, sans-serif; /* Fuente utilizada en el cuerpo */
            line-height: 1.6; /* Altura de línea */
            color: #333; /* Color del texto */
            max-width: 600px; /* Ancho máximo del contenedor */
            margin: 0 auto; /* Centrando el contenedor en la página */
            padding: 20px; /* Espacio interior de la página */
            background-color: #f4f4f4; /* Color de fondo de la página */
        }

        /* Estilos para el encabezado */
        h1 {
            color: #000; /* Color del encabezado */
            text-align: center; /* Centrando el texto del encabezado */
        }

        /* Estilos para el formulario */
        form {
            background-color: #fff; /* Color de fondo del formulario */
            padding: 20px; /* Espacio interior del formulario */
            border-radius: 5px; /* Bordes redondeados del formulario */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra alrededor del formulario */
        }

        /* Estilos para las etiquetas */
        label {
            display: block; /* Mostrar etiquetas como bloque */
            margin-bottom: 5px; /* Espacio inferior para las etiquetas */
            color: #000; /* Color del texto de las etiquetas */
        }

        /* Estilos para los campos de entrada */
        input[type="date"],
        input[type="number"] {
            width: 100%; /* Ancho completo para los campos de entrada */
            padding: 8px; /* Espacio interior de los campos de entrada */
            margin-bottom: 10px; /* Espacio inferior para los campos de entrada */
            border: 1px solid #ddd; /* Borde de los campos de entrada */
            border-radius: 4px; /* Bordes redondeados de los campos de entrada */
        }

        /* Estilos para el botón de envío */
        input[type="submit"] {
            display: block; /* Mostrar botón como bloque */
            width: 100%; /* Ancho completo para el botón */
            padding: 10px; /* Espacio interior del botón */
            background-color: #333; /* Color de fondo del botón */
            color: #fff; /* Color del texto del botón */
            border: none; /* Sin borde para el botón */
            border-radius: 4px; /* Bordes redondeados del botón */
            cursor: pointer; /* Cambiar cursor al pasar sobre el botón */
        }

        /* Estilos para el botón en hover */
        input[type="submit"]:hover {
            background-color: #555; /* Color de fondo al pasar el cursor */
        }
    </style>
</head>
<body>
    <h1>Actualizar Venta</h1>
    <!-- Formulario para actualizar la venta -->
    <form method="post">
        <label for="codigo">Código de Venta:</label>
        <input type="number" id="codigo" name="codigo" required><br> <!-- Campo requerido para el código de la venta -->

        <label for="fecha">Nueva Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br> <!-- Campo requerido para la nueva fecha -->

        <label for="total">Nuevo Total:</label>
        <input type="number" id="total" name="total" required><br> <!-- Campo requerido para el nuevo total -->

        <label for="cantidad">Nueva Cantidad Total:</label>
        <input type="number" id="cantidad" name="cantidad" required><br> <!-- Campo requerido para la nueva cantidad total -->

        <label for="cliente">Nuevo Código del Cliente:</label>
        <input type="number" id="cliente" name="cliente" required><br> <!-- Campo requerido para el nuevo código del cliente -->

        <label for="admin">Nuevo Código del Administrador:</label>
        <input type="number" id="admin" name="admin"><br> <!-- Campo opcional para el nuevo código del administrador -->

        <input type="submit" value="Actualizar Venta"> <!-- Botón para enviar el formulario -->
    </form>
</body>
</html>
