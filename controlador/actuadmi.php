<?php
// Definición de las credenciales para la conexión con la base de datos
$servername = "localhost"; // Nombre del servidor
$username = "root"; // Nombre de usuario de la base de datos
$password = ""; // Contraseña del usuario de la base de datos
$dbname = "dakara"; // Nombre de la base de datos

// Creación de la conexión usando mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificación de si hubo un error en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error); // Muestra un mensaje de error y termina la ejecución
}

// Inicialización de variables para mensajes y datos de administrador
$mensaje = ''; // Mensaje para mostrar resultados de las acciones
$tipo_mensaje = ''; // Tipo de mensaje (éxito o error)
$administrador = null; // Inicializa la variable para almacenar datos del administrador

// Verifica si se ha recibido un parámetro 'id' a través de GET
if (isset($_GET['id'])) {
    try {
        // Preparación de la consulta para obtener un administrador por su código
        $stmt = $conn->prepare("SELECT * FROM administrador WHERE admi_codigo = ?"); // Consulta SQL
        $stmt->bind_param("i", $_GET['id']); // Asigna el parámetro recibido
        $stmt->execute(); // Ejecuta la consulta
        $result = $stmt->get_result(); // Obtiene el resultado de la consulta
        $administrador = $result->fetch_assoc(); // Guarda los datos en la variable
        $stmt->close(); // Cierra la consulta preparada
    } catch (Exception $e) {
        // Captura cualquier error y muestra un mensaje
        $mensaje = "Error al obtener datos: " . $e->getMessage();
        $tipo_mensaje = "error"; // Define el tipo de mensaje como error
    }
}

// Verifica si la solicitud es de tipo POST (es decir, se ha enviado un formulario)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Preparación del procedimiento almacenado para actualizar un administrador
        $stmt = $conn->prepare("CALL sp_actualizar_administrador(?, ?, ?, ?, ?, ?, ?)"); // Llama al procedimiento
        $stmt->bind_param("issssss", 
            $_POST['admi_codigo'], // Código del administrador
            $_POST['identificacion'], // Identificación
            $_POST['tipo_identificacion'], // Tipo de identificación
            $_POST['nombre'], // Nombre del administrador
            $_POST['apellido'], // Apellido del administrador
            $_POST['celular'], // Celular del administrador
            $_POST['direccion'] // Dirección del administrador
        );

        // Verifica si la ejecución fue exitosa
        if ($stmt->execute()) {
            $mensaje = "Administrador actualizado exitosamente"; // Mensaje de éxito
            $tipo_mensaje = "success"; // Define el tipo de mensaje como éxito

            // Actualiza los datos mostrados después de la actualización
            $stmt = $conn->prepare("SELECT * FROM administrador WHERE admi_codigo = ?"); // Nueva consulta para obtener datos
            $stmt->bind_param("i", $_POST['admi_codigo']); // Asigna el código del administrador
            $stmt->execute(); // Ejecuta la consulta
            $result = $stmt->get_result(); // Obtiene el resultado
            $administrador = $result->fetch_assoc(); // Almacena los datos actualizados
        } else {
            throw new Exception("Error al ejecutar el procedimiento"); // Lanza una excepción si hubo un error
        }

        $stmt->close(); // Cierra la consulta preparada
    } catch (Exception $e) {
        // Captura cualquier error durante la actualización
        $mensaje = "Error al actualizar: " . $e->getMessage(); // Mensaje de error
        $tipo_mensaje = "error"; // Define el tipo de mensaje como error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración de la codificación y título de la página -->
    <meta charset="UTF-8">
    <title>Actualizar Administrador</title>
    <style>
    /* Estilo del cuerpo */
    body {
        font-family: Arial, sans-serif; /* Fuente para el cuerpo */
        background-color: #f0f0f0; /* Color de fondo de la página */
        margin: 0; /* Sin márgenes en la página */
        padding: 20px; /* Espaciado interior de la página */
    }

    /* Estilo del contenedor */
    .container {
        max-width: 800px; /* Ancho máximo del contenedor */
        margin: 0 auto; /* Centrar el contenedor */
        background-color: white; /* Color de fondo del contenedor */
        padding: 20px; /* Espacio interior del contenedor */
        box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra alrededor del contenedor */
    }

    /* Estilo del encabezado */
    h1 {
        color: #333; /* Color del texto del encabezado */
        text-align: center; /* Centrar texto */
        border-bottom: 2px solid #333; /* Línea inferior */
        padding-bottom: 10px; /* Espaciado inferior */
    }

    /* Estilo de los grupos de formulario */
    .form-group {
        margin-bottom: 15px; /* Espaciado inferior para grupos de formulario */
    }

    /* Estilo de las etiquetas */
    label {
        display: block; /* Mostrar etiquetas como bloque */
        margin-bottom: 5px; /* Espaciado inferior para etiquetas */
        color: #333; /* Color del texto de las etiquetas */
        font-weight: bold; /* Texto en negrita */
    }

    /* Estilo de los campos de entrada y selección */
    input[type="text"],
    select {
        width: 100%; /* Ancho completo para los campos */
        padding: 8px; /* Espaciado interior */
        border: 1px solid #ddd; /* Borde de los campos */
        border-radius: 4px; /* Bordes redondeados */
        box-sizing: border-box; /* Incluye el padding y el borde en el ancho total */
    }

    /* Estilo del botón */
    .btn {
        background-color: #333; /* Color de fondo del botón */
        color: white; /* Color del texto del botón */
        padding: 10px 20px; /* Espaciado interior del botón */
        border: none; /* Sin borde */
        border-radius: 4px; /* Bordes redondeados */
        cursor: pointer; /* Cambia el cursor al pasar sobre el botón */
    }

    /* Efecto hover del botón */
    .btn:hover {
        background-color: #555; /* Color de fondo al pasar el cursor */
    }

    /* Estilo de las alertas */
    .alert {
        padding: 10px; /* Espaciado interior */
        margin-bottom: 15px; /* Espaciado inferior */
        border-radius: 4px; /* Bordes redondeados */
    }

    /* Estilo de las alertas de éxito */
    .alert-success {
        background-color: #dff0d8; /* Color de fondo de éxito */
        border: 1px solid #d0e9c6; /* Borde verde */
        color: #3c763d; /* Texto verde */
    }

    /* Estilo de las alertas de error */
    .alert-error {
        background-color: #f2dede; /* Color de fondo de error */
        border: 1px solid #ebccd1; /* Borde rojo */
        color: #a94442; /* Texto rojo */
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Actualizar Administrador</h1>
        
        <!-- Mostrar mensaje si existe alguno -->
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?> <!-- Muestra el mensaje -->
            </div>
        <?php endif; ?>

        <!-- Mostrar formulario si el administrador fue encontrado -->
        <?php if ($administrador): ?>
            <form method="POST">
                <input type="hidden" name="admi_codigo" value="<?php echo $administrador['admi_codigo']; ?>"> <!-- Campo oculto para el código del administrador -->
                
                <div class="form-group">
                    <label for="identificacion">Identificación:</label>
                    <input type="text" id="identificacion" name="identificacion" 
                           value="<?php echo htmlspecialchars($administrador['admi_identificacion']); ?>" required> <!-- Campo para identificación -->
                </div>

                <div class="form-group">
                    <label for="tipo_identificacion">Tipo de Identificación:</label>
                    <select id="tipo_identificacion" name="tipo_identificacion" required> <!-- Campo de selección -->
                        <option value="CC" <?php echo ($administrador['admi_tipoidentificacion'] == 'CC') ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                        <option value="CE" <?php echo ($administrador['admi_tipoidentificacion'] == 'CE') ? 'selected' : ''; ?>>Cédula de Extranjería</option>
                        <option value="TI" <?php echo ($administrador['admi_tipoidentificacion'] == 'TI') ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" 
                           value="<?php echo htmlspecialchars($administrador['admi_nombre']); ?>" required> <!-- Campo para nombre -->
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" 
                           value="<?php echo htmlspecialchars($administrador['admi_apellido']); ?>" required> <!-- Campo para apellido -->
                </div>

                <div class="form-group">
                    <label for="celular">Celular:</label>
                    <input type="text" id="celular" name="celular" 
                           value="<?php echo htmlspecialchars($administrador['admi_celular']); ?>" required> <!-- Campo para celular -->
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" 
                           value="<?php echo htmlspecialchars($administrador['admi_direccion']); ?>" required> <!-- Campo para dirección -->
                </div>

                <button type="submit" class="btn">Actualizar Administrador</button> <!-- Botón para enviar el formulario -->
            </form>
        <?php else: ?>
            <p>No se encontró el administrador especificado.</p> <!-- Mensaje si no se encontró el administrador -->
        <?php endif; ?>
    </div>
</body>
</html>
