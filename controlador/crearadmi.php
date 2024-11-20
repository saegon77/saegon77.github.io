<?php
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

// Inicializar variables para mensajes
$mensaje = '';
$tipo_mensaje = '';

// Obtener roles para el select
$roles = array();
$query = "SELECT id_rol, descripcion FROM roles WHERE estado = 'activo'";
$result = $conn->query($query);
if ($result) {
    while($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Preparar la llamada al procedimiento almacenado
        $stmt = $conn->prepare("CALL sp_crear_usuario_administrador(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Hash de la contraseña
        $clave_hash = md5($_POST['usua_clave']); // Nota: MD5 no es seguro, considera usar password_hash()
        
        $stmt->bind_param("ssissssss", 
            $_POST['usua_nombre'],
            $clave_hash,
            $_POST['id_rol'],
            $_POST['identificacion'],
            $_POST['tipo_identificacion'],
            $_POST['nombre'],
            $_POST['apellido'],
            $_POST['celular'],
            $_POST['direccion']
        );
        
        // Ejecutar el procedimiento
        if ($stmt->execute()) {
            $mensaje = "Usuario y Administrador creados exitosamente";
            $tipo_mensaje = "success";
        } else {
            throw new Exception("Error al ejecutar el procedimiento");
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $mensaje = "Error al crear el usuario y administrador: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Administrador</title>

    <style> 
    /* Estilos generales en blanco y negro */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

h1 {
    color: #333;
    text-align: center;
    border-bottom: 2px solid #333;
    padding-bottom: 10px;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #333;
    font-weight: bold;
}

input[type="text"],
input[type="password"],
select {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

.btn {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn:hover {
    background-color: #555;
}

.alert {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
}

.alert-success {
    background-color: #dff0d8;
    border: 1px solid #d0e9c6;
    color: #3c763d;
}

.alert-error {
    background-color: #f2dede;
    border: 1px solid #ebccd1;
    color: #a94442;
}

        /* Estilos adicionales para la sección de usuario */
        .section-title {
            margin-top: 20px;
            padding: 10px;
            background-color: #f8f8f8;
            border-left: 3px solid #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Nuevo Administrador</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <!-- Sección de datos de usuario -->
            <h2 class="section-title">Datos de Usuario</h2>
            <div class="form-group">
                <label for="usua_nombre">Nombre de Usuario:</label>
                <input type="text" id="usua_nombre" name="usua_nombre" required>
            </div>

            <div class="form-group">
                <label for="usua_clave">Contraseña:</label>
                <input type="password" id="usua_clave" name="usua_clave" required>
            </div>

            <div class="form-group">
                <label for="id_rol">Rol:</label>
                <select id="id_rol" name="id_rol" required>
                    <option value="">Seleccione un rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?php echo $rol['id_rol']; ?>">
                            <?php echo htmlspecialchars($rol['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sección de datos de administrador -->
            <h2 class="section-title">Datos Personales</h2>
            <div class="form-group">
                <label for="identificacion">Identificación:</label>
                <input type="text" id="identificacion" name="identificacion" required>
            </div>

            <div class="form-group">
                <label for="tipo_identificacion">Tipo de Identificación:</label>
                <select id="tipo_identificacion" name="tipo_identificacion" required>
                    <option value="">Seleccione un tipo</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                    <option value="TI">Tarjeta de Identidad</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required>
            </div>

            <div class="form-group">
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" required>
            </div>

            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required>
            </div>

            <button type="submit" class="btn">Crear Usuario y Administrador</button>
        </form>
    </div>
</body>
</html>
   
