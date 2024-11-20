<?php
// Iniciar sesión para manejar la autenticación del usuario
session_start();

/**
 * Clase Database
 * Maneja todas las operaciones relacionadas con la base de datos
 */
class Database {
    private $host = "localhost"; // Dirección del servidor de la base de datos
    private $usuario = "root"; // Usuario de la base de datos
    private $contrasena = ""; // Contraseña de la base de datos
    private $nombreBD = "dakara"; // Nombre de la base de datos
    private $conexion; // Variable para almacenar la conexión

    // Constructor que establece la conexión a la base de datos
    public function __construct() {
        // Crear una nueva conexión a la base de datos usando MySQLi
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombreBD);
        // Verificar si hay errores de conexión
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error); // Manejar error de conexión
        }
    }

    /**
     * Obtiene los datos actuales del cliente
     * @param string $usua_nombre - Nombre del usuario
     * @return array - Datos del cliente
     */
    public function obtenerDatosCliente($usua_nombre) {
        // Consulta SQL para obtener datos del cliente y del usuario asociado
        $sql = "SELECT c.*, u.usua_nombre 
                FROM cliente c
                INNER JOIN usuario u ON c.usua_codigo = u.usua_codigo
                WHERE u.usua_nombre = ?";
                
        $stmt = $this->conexion->prepare($sql); // Preparar la consulta
        $stmt->bind_param("s", $usua_nombre); // Asignar el parámetro de entrada
        $stmt->execute(); // Ejecutar la consulta
        $resultado = $stmt->get_result(); // Obtener el resultado
        $datos = $resultado->fetch_assoc(); // Extraer los datos como un array asociativo
        $stmt->close(); // Cerrar la consulta
        return $datos; // Retornar los datos obtenidos
    }

    /**
     * Actualiza los datos del cliente usando el procedimiento almacenado
     * @param array $datos - Datos del cliente a actualizar
     * @return bool - Resultado de la operación
     */
    public function actualizarCliente($datos) {
        // Llamar al procedimiento almacenado para actualizar el perfil del cliente
        $sql = "CALL actu_perfil(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($sql); // Preparar la consulta
        // Asignar los parámetros correspondientes a la consulta
        $stmt->bind_param("issssss", 
            $datos['clie_codigo'],
            $datos['clie_identificacion'],
            $datos['clie_tipoIdentificacion'],
            $datos['clie_nombre'],
            $datos['clie_apellido'],
            $datos['clie_celular'],
            $datos['clie_direccion']
        );

        $resultado = $stmt->execute(); // Ejecutar la consulta
        $stmt->close(); // Cerrar la consulta
        return $resultado; // Retornar resultado de la operación
    }

    // Cierra la conexión a la base de datos
    public function cerrarConexion() {
        $this->conexion->close(); // Cerrar la conexión a la base de datos
    }
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usua_nombre'])) {
    header("Location: ../vista/login.html"); // Redirigir a la página de login si no está autenticado
    exit();
}

$db = new Database(); // Crear instancia de la clase Database
$mensaje = ''; // Inicializar mensaje para mostrar al usuario

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Intentar actualizar los datos del cliente
        $resultado = $db->actualizarCliente($_POST); // Actualizar datos del cliente
        if ($resultado) {
            $mensaje = '<div class="alert success">Datos actualizados correctamente</div>'; // Mensaje de éxito
        } else {
            $mensaje = '<div class="alert error">Error al actualizar los datos</div>'; // Mensaje de error
        }
    } catch (Exception $e) {
        // Capturar cualquier excepción y mostrar el mensaje de error
        $mensaje = '<div class="alert error">Error: ' . $e->getMessage() . '</div>';
    }
}

// Obtener datos actuales del cliente
$datosCliente = $db->obtenerDatosCliente($_SESSION['usua_nombre']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"> <!-- Definición de la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Adaptación a dispositivos móviles -->
    <title>Actualizar Datos - Cliente</title> <!-- Título de la página -->
    <style>
        /* Estilos base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box; /* Asegura que el padding y border no afecten el tamaño total */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Fuente de la página */
        }

        body {
            min-height: 100vh; /* Altura mínima del cuerpo */
            background: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%); /* Fondo de degradado */
            color: #ffffff; /* Color del texto */
            padding: 20px; /* Espaciado interno */
        }

        /* Contenedor principal */
        .update-container {
            max-width: 800px; /* Ancho máximo del contenedor */
            margin: 40px auto; /* Margen superior/inferior y automático en los lados */
            background: rgba(255, 255, 255, 0.1); /* Fondo semi-transparente */
            backdrop-filter: blur(10px); /* Efecto de desenfoque en el fondo */
            border-radius: 20px; /* Bordes redondeados */
            padding: 30px; /* Espaciado interno */
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); /* Sombra del contenedor */
        }

        h1 {
            text-align: center; /* Centrar el texto del encabezado */
            margin-bottom: 30px; /* Margen inferior */
            color: #4a90e2; /* Color del encabezado */
        }

        /* Estilos del formulario */
        .form-grid {
            display: grid; /* Usar diseño de cuadrícula */
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Crear columnas automáticas */
            gap: 20px; /* Espacio entre columnas */
        }

        .form-group {
            margin-bottom: 20px; /* Margen inferior de los grupos de formulario */
        }

        label {
            display: block; /* Mostrar etiquetas como bloques */
            margin-bottom: 8px; /* Margen inferior */
            color: #4a90e2; /* Color de las etiquetas */
            font-weight: bold; /* Hacer que el texto de las etiquetas sea negrita */
        }

        input[type="text"] {
            width: 100%; /* Ancho completo */
            padding: 12px; /* Espaciado interno */
            background: rgba(255, 255, 255, 0.1); /* Fondo semi-transparente */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Bordes claros */
            border-radius: 8px; /* Bordes redondeados */
            color: #ffffff; /* Color del texto */
            font-size: 16px; /* Tamaño de fuente */
            transition: all 0.3s ease; /* Transiciones suaves */
        }

        input[type="text"]:focus {
            outline: none; /* Sin contorno al enfocar */
            border-color: #4a90e2; /* Color del borde al enfocar */
            background: rgba(255, 255, 255, 0.15); /* Cambio de fondo al enfocar */
        }

        /* Estilos de los botones */
        .button-group {
            display: flex; /* Usar diseño flex */
            gap: 15px; /* Espacio entre botones */
            justify-content: center; /* Centrar botones */
            margin-top: 30px; /* Margen superior */
        }

        .btn {
            padding: 12px 24px; /* Espaciado interno del botón */
            border: none; /* Sin borde */
            border-radius: 25px; /* Bordes redondeados */
            cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
            font-weight: bold; /* Hacer que el texto del botón sea negrita */
            transition: all 0.3s ease; /* Transiciones suaves */
            text-decoration: none; /* Sin subrayado en enlaces */
            text-align: center; /* Centrar texto en botones */
        }

        .btn-primary {
            background: linear-gradient(45deg, #4a90e2, #0077cc); /* Fondo degradado del botón primario */
            color: white; /* Color del texto del botón */
        }

        .btn-primary:hover {
            opacity: 0.9; /* Cambiar opacidad al pasar el mouse */
        }

        .btn-secondary {
            background: transparent; /* Fondo transparente del botón secundario */
            color: #4a90e2; /* Color del texto del botón */
            border: 2px solid #4a90e2; /* Borde del botón */
        }

        .btn-secondary:hover {
            background: rgba(74, 144, 226, 0.2); /* Fondo al pasar el mouse */
        }

        /* Estilos para mensajes de error y éxito */
        .alert {
            padding: 15px; /* Espaciado interno del mensaje */
            border-radius: 5px; /* Bordes redondeados del mensaje */
            margin-bottom: 20px; /* Margen inferior */
            color: white; /* Color del texto */
        }

        .alert.success {
            background-color: #4caf50; /* Fondo verde para mensajes de éxito */
        }

        .alert.error {
            background-color: #f44336; /* Fondo rojo para mensajes de error */
        }
    </style>
</head>
<body>
    <div class="update-container"> <!-- Contenedor principal para actualizar datos -->
        <h1>Actualizar Datos Personales</h1> <!-- Título de la sección -->
        <?php echo $mensaje; ?> <!-- Mostrar mensaje de error o éxito -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"> <!-- Formulario para actualizar datos -->
            <input type="hidden" name="clie_codigo" value="<?php echo htmlspecialchars($datosCliente['clie_codigo']); ?>"> <!-- Campo oculto para el código del cliente -->
            <div class="form-grid"> <!-- Contenedor de cuadrícula para campos del formulario -->
                <div class="form-group">
                    <label for="clie_identificacion">Identificación:</label>
                    <input type="text" id="clie_identificacion" name="clie_identificacion" value="<?php echo htmlspecialchars($datosCliente['clie_identificacion']); ?>" required> <!-- Campo de identificación -->
                </div>
                <div class="form-group">
                    <label for="clie_tipoIdentificacion">Tipo de Identificación:</label>
                    <input type="text" id="clie_tipoIdentificacion" name="clie_tipoIdentificacion" value="<?php echo htmlspecialchars($datosCliente['clie_tipoIdentificacion']); ?>" required> <!-- Campo de tipo de identificación -->
                </div>
                <div class="form-group">
                    <label for="clie_nombre">Nombre:</label>
                    <input type="text" id="clie_nombre" name="clie_nombre" value="<?php echo htmlspecialchars($datosCliente['clie_nombre']); ?>" required> <!-- Campo de nombre -->
                </div>
                <div class="form-group">
                    <label for="clie_apellido">Apellido:</label>
                    <input type="text" id="clie_apellido" name="clie_apellido" value="<?php echo htmlspecialchars($datosCliente['clie_apellido']); ?>" required> <!-- Campo de apellido -->
                </div>
                <div class="form-group">
                    <label for="clie_celular">Celular:</label>
                    <input type="text" id="clie_celular" name="clie_celular" value="<?php echo htmlspecialchars($datosCliente['clie_celular']); ?>" required> <!-- Campo de celular -->
                </div>
                <div class="form-group">
                    <label for="clie_direccion">Dirección:</label>
                    <input type="text" id="clie_direccion" name="clie_direccion" value="<?php echo htmlspecialchars($datosCliente['clie_direccion']); ?>" required> <!-- Campo de dirección -->
                </div>
            </div>
            <div class="button-group"> <!-- Grupo de botones -->
                <button type="submit" class="btn btn-primary">Actualizar Datos</button> <!-- Botón para enviar el formulario -->
                <a href="perfil.php" class="btn btn-secondary">Volver al perfil</a> <!-- Botón para volver al perfil -->
            </div>
        </form>
    </div>
</body>
</html>

<?php
$db->cerrarConexion(); // Cerrar la conexión a la base de datos al final del script
?>
