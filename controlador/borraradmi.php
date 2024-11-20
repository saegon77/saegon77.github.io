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

$mensaje = '';
$tipo_mensaje = '';

if (isset($_GET['id'])) {
    try {
        // Llamar al procedimiento almacenado para eliminar
        $stmt = $conn->prepare("CALL sp_eliminar_administrador(?)");
        $stmt->bind_param("i", $_GET['id']);
        
        if ($stmt->execute()) {
            $mensaje = "Administrador eliminado exitosamente";
            $tipo_mensaje = "success";
        } else {
            throw new Exception("Error al ejecutar el procedimiento");
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $mensaje = "Error al eliminar: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Administrador</title>
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
        <h1>Eliminar Administrador</h1>
        
        <?php if ($mensaje): ?>
            <div class="alert alert-<?php echo $tipo_mensaje; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <p>
            <?php if ($tipo_mensaje == 'success'): ?>
                El administrador ha sido eliminado. 
                <a href="admix2.php" class="btn">Volver al listado</a>
            <?php else: ?>
                Ha ocurrido un error al intentar eliminar el administrador.
                <a href="admix2.php" class="btn">Volver al listado</a>
            <?php endif; ?>
        </p>
    </div>
</body>
</html>