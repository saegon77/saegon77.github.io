<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dakara";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Función para obtener todos los roles
function obtenerTodosLosRoles($conn) {
    $sql = "SELECT * FROM roles";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Función para buscar un rol por ID
function buscarRolPorId($conn, $id) {
    $stmt = $conn->prepare("CALL ConsultarRol(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Procesar la búsqueda si se envió el formulario
$rolBuscado = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buscar'])) {
    $id_buscar = $_POST['id_buscar'];
    $rolBuscado = buscarRolPorId($conn, $id_buscar);
}

// Obtener todos los roles para mostrar en la tabla
$roles = obtenerTodosLosRoles($conn);

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <style>
        /* Estilos en blanco y negro */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        h1, h2 {
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        input[type="number"], input[type="submit"] {
            padding: 8px;
            margin: 5px 0;
        }
        input[type="submit"], .btn {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            padding: 10px 15px;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        input[type="submit"]:hover, .btn:hover {
            background-color: #000;
        }
    </style>
</head>
<body>
    <h1>Gestión de Roles</h1>

    <!-- Formulario de búsqueda -->
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        <h2>Buscar Rol</h2>
        <input type="number" name="id_buscar" placeholder="Ingrese ID del rol" required>
        <input type="submit" name="buscar" value="Buscar">
    </form>

    <!-- Mostrar resultado de la búsqueda -->
    <?php if ($rolBuscado): ?>
        <h2>Resultado de la búsqueda</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Estado</th>
            </tr>
            <tr>
                <td><?php echo $rolBuscado['id_rol']; ?></td>
                <td><?php echo $rolBuscado['descripcion']; ?></td>
                <td><?php echo $rolBuscado['estado']; ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <!-- Tabla de todos los roles -->
    <h2>Todos los Roles</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Descripción</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($roles as $rol): ?>
            <tr>
                <td><?php echo $rol['id_rol']; ?></td>
                <td><?php echo $rol['descripcion']; ?></td>
                <td><?php echo $rol['estado']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Botones de navegación -->
    <div>
        <a href="../controlador/crearrol.php" class="btn">Crear Nuevo Rol</a>
        <a href="../controlador/acturol.php" class="btn">Actualizar Rol</a>
        <a href="../controlador/borrarrol.php" class="btn">Eliminar Rol</a>
        <a href="../vista/admin.html" class="btn">Volver</a>
    </div>
</body>
</html>