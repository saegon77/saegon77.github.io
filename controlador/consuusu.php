<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuarios</title>
    <style>
        /* Estilo general de la página */
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            background-color: #f0f0f0; 
            color: #333; 
        }
        
        /* Estilo del formulario */
        form { 
            margin-bottom: 20px; 
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }
        
        /* Estilo de las etiquetas del formulario */
        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }
        
        /* Estilo de los campos de entrada */
        input[type="text"], input[type="number"] {
            width: 200px;
            padding: 5px;
            margin-bottom: 10px;
        }
        
        /* Estilo del botón de envío */
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        
        /* Estilo de la tabla de resultados */
        table { 
            border-collapse: collapse; 
            width: 100%;
            background-color: #fff;
        }
        
        /* Estilo de las celdas de la tabla */
        table, th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left;
        }
        
        /* Estilo del encabezado de la tabla */
        th {
            background-color: #333;
            color: #fff;
        }
        
        /* Estilo alternado de las filas de la tabla */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Consulta de Usuarios</h1>
    
    <!-- Formulario para ingresar los parámetros de búsqueda -->
    <form action="" method="POST">
        <label for="usua_codigo">Código de Usuario:</label>
        <input type="number" id="usua_codigo" name="usua_codigo"><br>
        
        <label for="usua_nombre">Nombre de Usuario:</label>
        <input type="text" id="usua_nombre" name="usua_nombre"><br>
        
        <label for="id_rol">ID de Rol:</label>
        <input type="number" id="id_rol" name="id_rol"><br>
        
        <input type="submit" value="Consultar">
    </form>

    <?php
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Datos de conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dakara";


        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar si la conexión fue exitosa
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar los parámetros para la consulta
        $usua_codigo = !empty($_POST['usua_codigo']) ? $_POST['usua_codigo'] : null;
        $usua_nombre = !empty($_POST['usua_nombre']) ? $_POST['usua_nombre'] : null;
        $id_rol = !empty($_POST['id_rol']) ? $_POST['id_rol'] : null;

        // Llamar al procedimiento almacenado
        $sql = "CALL sp_consultar_usuarios(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $usua_codigo, $usua_nombre, $id_rol);
        $stmt->execute();
        $result = $stmt->get_result();

        // Verificar si hay resultados y mostrarlos
        if ($result->num_rows > 0) {
            echo "<h2>Resultados:</h2>";
            echo "<table>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>ID Rol</th>
                    </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["usua_codigo"]."</td>
                        <td>".$row["usua_nombre"]."</td>
                        <td>".$row["id_rol"]."</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "0 resultados";
        }

        // Cerrar la declaración y la conexión
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>