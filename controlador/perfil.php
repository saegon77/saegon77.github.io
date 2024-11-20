<?php
// Iniciar sesión para manejar la autenticación del usuario
session_start();

/**
 * Clase Database
 * Maneja todas las operaciones relacionadas con la base de datos
 */
class Database {
    // Propiedades de conexión a la base de datos
    private $host = "localhost";
    private $usuario = "root";
    private $contrasena = "";
    private $nombreBD = "dakara";
    private $conexion;

    /**
     * Constructor: Establece la conexión con la base de datos
     */
    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombreBD);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    /**
     * Obtiene los datos completos del cliente basado en su nombre de usuario
     * @param string $usua_nombre Nombre del usuario
     * @return array Datos del cliente
     */
    public function obtenerDatosCliente($usua_nombre) {
        $sql = "SELECT c.*, u.usua_nombre, r.descripcion as rol_descripcion 
                FROM cliente c
                INNER JOIN usuario u ON c.usua_codigo = u.usua_codigo
                INNER JOIN roles r ON u.id_rol = r.id_rol
                WHERE u.usua_nombre = ?";
                
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $usua_nombre);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $datos = $resultado->fetch_assoc();
        $stmt->close();
        return $datos;
    }

    /**
     * Cierra la conexión con la base de datos
     */
    public function cerrarConexion() {
        $this->conexion->close();
    }
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usua_nombre'])) {
    header("Location: ../vista/login.html");
    exit();
}

// Crear instancia de la base de datos y obtener datos del cliente
$db = new Database();
$datosCliente = $db->obtenerDatosCliente($_SESSION['usua_nombre']);

// Verificar si se obtuvieron los datos del cliente
if (!$datosCliente) {
    header("Location: ../vista/error_de_sesion.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Cliente</title>
    <style>
        /* Reset básico y estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Estilos del body y fondo */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a1a1a 0%, #0a0a0a 100%);
            color: #ffffff;
            line-height: 1.6;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Elementos decorativos del fondo */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        /* Animaciones para las formas del fondo */
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .circle {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ffffff, #4a90e2);
            top: 10%;
            left: 10%;
        }

        .square {
            width: 120px;
            height: 120px;
            background: linear-gradient(45deg, #ffffff, #50c878);
            top: 50%;
            right: 10%;
            transform: rotate(45deg);
        }

        .triangle {
            width: 0;
            height: 0;
            border-left: 75px solid transparent;
            border-right: 75px solid transparent;
            border-bottom: 130px solid rgba(255,255,255,0.1);
            bottom: 10%;
            left: 50%;
        }

        /* Contenedor principal del dashboard */
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Estilos para los títulos */
        h1 {
            font-size: 2.5em;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        h2 {
            font-size: 1.8em;
            margin-bottom: 20px;
            color: #4a90e2;
        }

        /* Contenedor de la información del cliente */
        .client-info {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            padding: 30px;
            margin-top: 20px;
        }

        /* Grid para la información del cliente */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        /* Elementos individuales de información */
        .info-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .info-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
        }

        .info-item strong {
            display: block;
            color: #4a90e2;
            font-size: 1.1em;
            margin-bottom: 8px;
        }

        .info-item p {
            color: #ffffff;
            font-size: 1.2em;
        }

        /* Botón de cerrar sesión */
        .logout-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(204, 0, 0, 0.3);
        }

        .actu-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 24px;
            background: linear-gradient(45deg, #449bff, #0081cc);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .actu-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 82, 204, 0.3);
        }

        /* Media queries para responsividad */
        @media (max-width: 768px) {
            .dashboard-container {
                margin: 20px;
                padding: 20px;
            }

            h1 {
                font-size: 2em;
            }

            h2 {
                font-size: 1.5em;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Elementos decorativos del fondo -->
    <div class="background">
        <div class="shape circle"></div>
        <div class="shape square"></div>
        <div class="shape triangle"></div>
    </div>

    <!-- Contenedor principal del dashboard -->
    <div class="dashboard-container">
        <h1>Dashboard del Cliente</h1>
        <div class="client-info">
            <!-- Encabezado con el nombre del cliente -->
            <h2>Bienvenido, <?php echo htmlspecialchars($datosCliente['clie_nombre'] . ' ' . $datosCliente['clie_apellido']); ?></h2>
            
            <!-- Grid con la información del cliente -->
            <div class="info-grid">
                <!-- Identificación -->
                <div class="info-item">
                    <strong>Identificación</strong>
                    <p><?php echo htmlspecialchars($datosCliente['clie_identificacion']); ?></p>
                </div>
                <!-- Tipo de Identificación -->
                <div class="info-item">
                    <strong>Tipo de Identificación</strong>
                    <p><?php echo htmlspecialchars($datosCliente['clie_tipoIdentificacion']); ?></p>
                </div>
                <!-- Celular -->
                <div class="info-item">
                    <strong>Celular</strong>
                    <p><?php echo htmlspecialchars($datosCliente['clie_celular']); ?></p>
                </div>
                <!-- Dirección -->
                <div class="info-item">
                    <strong>Dirección</strong>
                    <p><?php echo htmlspecialchars($datosCliente['clie_direccion']); ?></p>
                </div>
                <!-- Usuario -->
                <div class="info-item">
                    <strong>Usuario</strong>
                    <p><?php echo htmlspecialchars($datosCliente['usua_nombre']); ?></p>
                </div>
                <!-- Rol -->
                <div class="info-item">
                    <strong>Rol</strong>
                    <p><?php echo htmlspecialchars($datosCliente['rol_descripcion']); ?></p>
                </div>
            </div>
            
            <!-- Botón de cerrar sesión -->
            <a href="../index.html" class="logout-btn">Cerrar Sesión</a>
            <a href="./actuperfil.php" class="actu-btn">Actualizar</a>
        </div>
    </div>
</body>
</html>

<?php
// Cerrar la conexión con la base de datos
$db->cerrarConexion();
?>