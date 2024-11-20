<<?php
session_start(); // Inicia una sesión PHP para mantener información del usuario

/**
 * Clase Database
 * Encapsula la funcionalidad de conexión a una base de datos MySQL
 */
class Database {
    private $host = "localhost"; // Dirección del servidor de la base de datos
    private $usuario = "root"; // Usuario de la base de datos
    private $contrasena = ""; // Contraseña del usuario de la base de datos
    private $nombreBD = "dakara"; // Nombre de la base de datos
    private $conexion; // Propiedad que almacenará la conexión a la base de datos

    /**
     * Constructor de la clase Database
     * Establece la conexión a la base de datos utilizando los valores de las propiedades
     */
    public function __construct() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->nombreBD);

        // Verifica si hubo un error de conexión
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
            // Si hay un error de conexión, muestra el mensaje de error y detiene la ejecución del script
        }
    }

    /**
     * Función verificarUsuario
     * Verifica las credenciales de un usuario en la base de datos
     * @param string $usua_nombre Nombre de usuario
     * @param string $usua_clave Contraseña del usuario
     * @return array|null Arreglo con los datos del usuario si se encuentra, null en caso contrario
     */
    public function verificarUsuario($usua_nombre, $usua_clave) {
        $stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE usua_nombre = ? AND usua_clave = ?");
        // Prepara una consulta SQL para seleccionar todos los datos de la tabla "usuario"
        // donde el nombre de usuario y la clave coinciden con los parámetros proporcionados

        $stmt->bind_param("ss", $usua_nombre, $usua_clave);
        // Vincula los parámetros a la consulta preparada

        $stmt->execute();
        $resultado = $stmt->get_result();
        $filas = $resultado->fetch_assoc();
        // Ejecuta la consulta, obtiene el resultado y lo convierte en un arreglo asociativo

        $stmt->close();
        return $filas;
        // Cierra la declaración preparada y devuelve el arreglo con los datos del usuario
    }

    /**
     * Función cerrarConexion
     * Cierra la conexión a la base de datos
     */
    public function cerrarConexion() {
        $this->conexion->close();
    }
}


// Recibir los datos del formulario
$usua_nombre = $_POST['usua_nombre'];
$usua_clave = $_POST['usua_clave'];


// Crear instancia de la base de datos
$db = new Database();


// Verificar usuario
$filas = $db->verificarUsuario($usua_nombre, $usua_clave);



// Verificar si se encontró el usuario
if ($filas) {
    // Iniciar la sesión del usuario
    $_SESSION['usua_nombre'] = $usua_nombre;

    // Redirigir según el rol del usuario
    if ($filas['id_rol'] == 1) {
        header("Location: ../vista/admin.html");
        exit();
    } elseif ($filas['id_rol'] == 2) {
        header("Location: ../vista/pagcliente.html");
        exit();
    }
} else {
    // Mostrar mensaje de error si no se encontró el usuario
    header("Location:../vista/paginas/error_de_sesion.html");
}

// Cerrar la conexión
$db->cerrarConexion();
?>
