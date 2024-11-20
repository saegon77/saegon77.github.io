<?php
        // Configuración de la conexión a la base de datos
        $servername = "localhost"; // Nombre del servidor
        $username = "root"; // Usuario de la base de datos
        $password = ""; // Contraseña de la base de datos
        $dbname = "dakara"; // Nombre de la base de datos

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error); // Muestra un mensaje si falla la conexión
        }

// Inicializar variables
$mensaje = '';
$tipo_mensaje = '';
$administradores = array();
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Si no hay búsqueda específica, mostrar todos los administradores
if (empty($busqueda)) {
    try {
        // Consulta para obtener todos los administradores con información de usuario y rol
        $sql = "SELECT a.*, u.usua_nombre, r.descripcion as rol_descripcion 
                FROM administrador a 
                INNER JOIN usuario u ON a.usua_codigo = u.usua_codigo 
                INNER JOIN roles r ON u.id_rol = r.id_rol";
        $result = $conn->query($sql);
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $administradores[] = $row;
            }
        }
    } catch (Exception $e) {
        $mensaje = "Error al cargar los datos: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
} else {
    // Si hay término de búsqueda, usar el procedimiento almacenado
    try {
        $stmt = $conn->prepare("CALL sp_buscar_administrador(?)");
        $stmt->bind_param("s", $busqueda);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $administradores[] = $row;
        }
        
        $stmt->close();
        $conn->next_result();
    } catch (Exception $e) {
        $mensaje = "Error en la búsqueda: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
}

// Para debug - Mostrar la cantidad de registros encontrados
$cantidad_registros = count($administradores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Administradores</title>

    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Estilos para el encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .title {
            color: #333;
            margin: 0;
        }

        /* Estilos para los botones */
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #333;
            color: white;
        }

        .btn-primary:hover {
            background-color: #555;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-edit {
            background-color: #666;
            color: white;
        }

        .btn-edit:hover {
            background-color: #888;
        }

        /* Estilos para el formulario de búsqueda */
        .search-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex-grow: 1;
        }

        /* Estilos para la tabla */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f8f8f8;
            color: #333;
        }

        .table tr:hover {
            background-color: #f5f5f5;
        }

        /* Estilos para las acciones */
        .actions {
            display: flex;
            gap: 5px;
        }

        /* Estilos para mensajes */
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="../vista/admin.html" class="btn btn-primary">← Volver</a>
            <h1 class="title">Gestión de Administradores</h1>
            <a href="crearadmi.php" class="btn btn-primary">+ Nuevo Administrador</a>
        </div>

        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="search-form">
            <input type="text" 
                   name="busqueda" 
                   class="search-input" 
                   placeholder="Buscar por cualquier campo..."
                   value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" name="buscar" class="btn btn-primary">Buscar</button>
            <?php if ($busqueda): ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-edit">Mostrar Todos</a>
            <?php endif; ?>
        </form>

        <!-- Debug info -->
        <p>Registros encontrados: <?php echo $cantidad_registros; ?></p>

        <!-- Tabla de resultados -->
        <table class="table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Identificación</th>
                    <th>Tipo ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($administradores)): ?>
                    <tr>
                        <td colspan="10" style="text-align: center;">No se encontraron registros</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($administradores as $admin): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['admi_codigo']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_identificacion']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_tipoidentificacion']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_apellido']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_celular']); ?></td>
                            <td><?php echo htmlspecialchars($admin['admi_direccion']); ?></td>
                            <td><?php echo htmlspecialchars($admin['usua_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($admin['rol_descripcion']); ?></td>
                            <td class="actions">
                                <a href="actuadmi.php?id=<?php echo $admin['admi_codigo']; ?>" 
                                   class="btn btn-edit">Editar</a>
                                <a href="borraradmi.php?id=<?php echo $admin['admi_codigo']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirm('¿Está seguro de que desea eliminar este administrador?')">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script para limpiar la URL después de una búsqueda vacía -->
    <script>
        // Si el campo de búsqueda está vacío y hay un parámetro de búsqueda en la URL,
        // redirigimos a la página sin parámetros
        document.querySelector('.search-form').addEventListener('submit', function(e) {
            if (document.querySelector('.search-input').value.trim() === '') {
                e.preventDefault();
                window.location.href = window.location.pathname;
            }
        });
    </script>
</body>
</html>
    

