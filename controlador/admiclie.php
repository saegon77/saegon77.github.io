<!DOCTYPE html>
<html lang="es"> <!-- Declaración del idioma para mejorar la accesibilidad -->
<head>
    <meta charset="UTF-8"> <!-- Especifica la codificación de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Optimiza la visualización en dispositivos móviles -->
    <title>Gestión de Clientes</title> <!-- Título de la página -->
    <style>
        /* Estilos en blanco y negro */
        body {
            font-family: Arial, sans-serif; /* Estilo de fuente */
            background-color: #f0f0f0; /* Color de fondo claro */
            color: #333; /* Color del texto */
            line-height: 1.6; /* Altura de línea para mejorar la legibilidad */
            padding: 20px;
        }
        .container {
            max-width: 1000px; /* Ancho máximo del contenedor */
            margin: 0 auto; /* Centra el contenedor */
            background-color: #fff; /* Fondo blanco */
            padding: 20px;
            border-radius: 5px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra ligera */
        }
        h1 {
            color: #000; /* Color del encabezado */
            text-align: center; /* Centrado del texto */
        }
        table {
            width: 100%; /* Tabla ocupa todo el ancho del contenedor */
            border-collapse: collapse; /* Quita espacios entre celdas */
            margin-top: 20px;
        }
        th, td {
            padding: 10px; /* Espaciado interno en celdas */
            border: 1px solid #ddd; /* Borde alrededor de cada celda */
            text-align: left; /* Alineación del texto a la izquierda */
        }
        th {
            background-color: #f2f2f2; /* Fondo más claro para encabezados */
            font-weight: bold;
        }
        .btn {
            display: inline-block; /* Botón en línea */
            background-color: #333; /* Fondo oscuro del botón */
            color: #fff; /* Texto blanco */
            padding: 5px 10px; /* Espaciado interno */
            text-decoration: none; /* Sin subrayado */
            border-radius: 3px; /* Bordes redondeados */
            margin-right: 5px;
        }
        .btn:hover {
            background-color: #555; /* Cambio de color al pasar el cursor */
        }
        .search-form {
            margin-bottom: 20px;
            display: flex; /* Flexbox para organizar elementos */
            align-items: center; /* Alinea verticalmente los elementos */
        }
        .search-form input[type="text"] {
            width: 200px; /* Ancho del campo de búsqueda */
            padding: 5px;
        }
        .search-form select, .search-form input[type="submit"], .search-form .btn {
            padding: 5px;
            margin-left: 10px; /* Espaciado entre elementos */
        }
    </style>
</head>
<body>
    <div class="container"> <!-- Contenedor principal -->
        <h1>Gestión de Clientes</h1> <!-- Encabezado -->
 
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

        // Función para limpiar datos de entrada y evitar ataques XSS
        function limpiar_entrada($dato) {
            $dato = trim($dato); // Elimina espacios al inicio y al final
            $dato = stripslashes($dato); // Quita barras invertidas
            $dato = htmlspecialchars($dato); // Escapa caracteres especiales
            return $dato;
        }

        // Procesar búsqueda
        $campo = "clie_codigo"; // Campo por defecto para la búsqueda
        $valor = ""; // Valor vacío por defecto
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['campo']) && isset($_GET['valor'])) {
            $campo = limpiar_entrada($_GET['campo']); // Campo seleccionado por el usuario
            $valor = limpiar_entrada($_GET['valor']); // Valor ingresado por el usuario
        }

        // Formulario de búsqueda
        echo "<form class='search-form' method='get'>";
        echo "<select name='campo'>"; // Desplegable para elegir campo de búsqueda
        echo "<option value='clie_codigo'" . ($campo == 'clie_codigo' ? " selected" : "") . ">Código</option>";
        echo "<option value='clie_identificacion'" . ($campo == 'clie_identificacion' ? " selected" : "") . ">Identificación</option>";
        echo "<option value='clie_nombre'" . ($campo == 'clie_nombre' ? " selected" : "") . ">Nombre</option>";
        echo "<option value='clie_apellido'" . ($campo == 'clie_apellido' ? " selected" : "") . ">Apellido</option>";
        echo "</select>";
        echo "<input type='text' name='valor' value='" . htmlspecialchars($valor) . "' placeholder='Buscar...'>";
        echo "<input type='submit' value='Buscar'>";
        echo "<a href='../vista/admin.html' class='btn'>Volver</a>";  
        echo "</form>";

        ?>
        <a href="crearclie.php" class="btn">Crear Nuevo Cliente</a> <!-- Enlace para crear un cliente nuevo -->
        <?php

        // Llamar al procedimiento almacenado para obtener clientes
        $sql = "CALL sp_consultar_cliente(?, ?)"; // Procedimiento almacenado
        $stmt = $conn->prepare($sql); // Preparar la consulta
        $stmt->bind_param("ss", $campo, $valor); // Asignar parámetros
        

        try {
            $stmt->execute(); // Ejecutar la consulta
            $resultado = $stmt->get_result(); // Obtener el resultado

            // Mostrar resultados en una tabla si hay datos
            if ($resultado->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Código</th><th>Identificación</th><th>Tipo ID</th><th>Nombre</th><th>Apellido</th><th>Celular</th><th>Dirección</th><th>Acciones</th></tr>";
                while($fila = $resultado->fetch_assoc()) { // Iterar sobre los resultados
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($fila["clie_codigo"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_identificacion"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_tipoIdentificacion"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_nombre"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_apellido"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_celular"])."</td>";
                    echo "<td>".htmlspecialchars($fila["clie_direccion"])."</td>";
                    echo "<td>";
                    echo "<a href='actuclie.php?id=".htmlspecialchars($fila["clie_codigo"])."' class='btn'>Actualizar</a>";
                    echo "<a href='borrarclie.php?id=".htmlspecialchars($fila["clie_codigo"])."' class='btn'>Eliminar</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No se encontraron resultados."; // Mensaje si no hay resultados
            }
        } catch (mysqli_sql_exception $e) {
            echo "Error en la consulta: " . htmlspecialchars($e->getMessage()); // Manejar errores de consulta
        }

        $stmt->close(); // Cerrar la consulta preparada
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>

        <br>
        <a href="crearclie.php" class="btn">Crear Nuevo Cliente</a> <!-- Enlace para crear un cliente nuevo -->
    </div>
</body>
</html>
