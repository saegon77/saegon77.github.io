<?php
// Conexión a la base de datos 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clie_identificacion = $_POST['clie_identificacion'];
    $clie_tipoIdentificacion = $_POST['clie_tipoIdentificacion'];
    $clie_nombre = $_POST['clie_nombre'];
    $clie_apellido = $_POST['clie_apellido'];
    $clie_celular = $_POST['clie_celular'];
    $clie_direccion = $_POST['clie_direccion'];
    $usua_codigo = $_POST['usua_codigo'];

    $sql = "INSERT INTO cliente (clie_identificacion, clie_tipoIdentificacion, clie_nombre, clie_apellido, clie_celular, clie_direccion, usua_codigo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $clie_identificacion, $clie_tipoIdentificacion, $clie_nombre, $clie_apellido, $clie_celular, $clie_direccion, $usua_codigo);

    if ($stmt->execute()) {
        // Redirigir a pagina del cliente después de un registro exitoso
        header("Location:../vista/acceso.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <style>
/* Estilos generales del cuerpo de la página */
body {
    font-family: 'Arial', sans-serif;
    background-color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    overflow: hidden;
}

/* Contenedor de las formas de fondo */
.background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
}

/* Estilos comunes para todas las formas */
.shape {
    position: absolute;
    opacity: 0.1;
    transition: all 3s ease;
}

/* Estilos específicos para cada tipo de forma */
.circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background-color: #fff;
}

.square {
    width: 80px;
    height: 80px;
    background-color: #fff;
}

.triangle {
    width: 0;
    height: 0;
    border-left: 50px solid transparent;
    border-right: 50px solid transparent;
    border-bottom: 86.6px solid #fff;
}

/* Contenedor del formulario */
.box {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
    width: 300px;
    transition: all 0.3s ease;
}

/* Efecto hover para el contenedor del formulario */
.box:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

/* Estilos para el título del formulario */
h1 {
    color: #fff;
    text-align: center;
    margin-bottom: 30px;
    font-size: 24px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

/* Estructura del formulario */
form {
    display: flex;
    flex-direction: column;
}

/* Estilos para las etiquetas de los campos */
label {
    margin-bottom: 5px;
    color: #fff;
    font-weight: bold;
}

/* Estilos para los campos de entrada */
input[type="text"],
input[type="password"] {
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 5px;
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    transition: all 0.3s ease;
}

/* Efecto focus para los campos de entrada */
input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #fff;
    outline: none;
    background-color: rgba(255, 255, 255, 0.2);
}

/* Estilos para el botón de envío */
button {
    padding: 10px;
    background-color: #fff;
    color: #000;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Efecto hover para el botón de envío */
button:hover {
    background-color: rgba(255, 255, 255, 0.8);
}

/* Estilos para el texto de placeholder en los campos de entrada */
input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}
    </style>
</head>
<body>
    <div class="background">
        <!-- Formas de fondo -->
        <div class="shape circle" style="top: 10%; left: 10%;"></div>
        <div class="shape square" style="top: 50%; right: 10%;"></div>
        <div class="shape triangle" style="bottom: 10%; left: 50%;"></div>
    </div>

    <div class="box">
        <h1>Registro de Cliente</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="usua_codigo" value="<?php echo $_GET['usua_codigo']; ?>">

            <label for="clie_identificacion">Identificación:</label>
            <input type="text" id="clie_identificacion" name="clie_identificacion" required>

            <label for="clie_tipoIdentificacion">Tipo de Identificación:</label>
            <input type="text" id="clie_tipoIdentificacion" name="clie_tipoIdentificacion" required>

            <label for="clie_nombre">Nombre:</label>
            <input type="text" id="clie_nombre" name="clie_nombre" required>

            <label for="clie_apellido">Apellido:</label>
            <input type="text" id="clie_apellido" name="clie_apellido" required>

            <label for="clie_celular">Celular:</label>
            <input type="text" id="clie_celular" name="clie_celular" required>

            <label for="clie_direccion">Dirección:</label>
            <input type="text" id="clie_direccion" name="clie_direccion" required>

            <button type="submit">Registrar Cliente</button>
        </form>
    </div>
</body>
</html>
