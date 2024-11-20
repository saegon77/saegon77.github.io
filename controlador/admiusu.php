<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <style>
        /* Estilos generales para el cuerpo de la página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Tipografía moderna y legible */
            background-color: #fff; /* Fondo blanco */
            color: #000; /* Texto en negro */
            line-height: 1.8; /* Espaciado entre líneas para mejor legibilidad */
            margin: 0;
            padding: 0;
        }

        /* Contenedor principal que centra y da formato a la página */
        .container {
            max-width: 1200px; /* Máximo ancho de la página */
            margin: 40px auto; /* Centra la página con márgenes superiores e inferiores */
            background-color: #fff; /* Fondo blanco para el contenido */
            padding: 40px; /* Espaciado interior */
            border-radius: 12px; /* Esquinas redondeadas */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        /* Estilo para el título principal (h1) */
        h1 {
            text-align: center; /* Centrar el texto */
            color: #000; /* Color del texto en negro */
            font-size: 2.5em; /* Tamaño del texto */
            margin-bottom: 40px; /* Espacio debajo del título */
        }

        /* Estilos para la tabla de usuarios */
        table {
            width: 100%; /* La tabla ocupa todo el ancho disponible */
            border-collapse: collapse; /* Colapsar bordes para un aspecto más limpio */
            margin-bottom: 30px; /* Espacio inferior */
        }

        /* Estilos para las celdas de la tabla (encabezados y datos) */
        th, td {
            padding: 15px; /* Espacio dentro de las celdas */
            border: 1px solid #000; /* Bordes negros */
            text-align: left; /* Alinear el texto a la izquierda */
        }

        /* Estilo específico para los encabezados de la tabla */
        th {
            background-color: #000; /* Fondo negro */
            color: #fff; /* Texto blanco */
            font-weight: bold; /* Texto en negrita */
        }

        /* Estilo para filas alternas de la tabla (fondo gris claro) */
        tr:nth-child(even) {
            background-color: #f0f0f0; /* Fondo gris claro para mayor legibilidad */
        }

        /* Estilos para los botones y los inputs de tipo submit */
        .btn, 
        input[type="submit"] {
            display: inline-block; /* Los botones son elementos en línea pero se comportan como bloques */
            padding: 10px 20px; /* Espaciado interior */
            background-color: #000; /* Fondo negro */
            color: #fff; /* Texto blanco */
            text-decoration: none; /* Eliminar subrayado en los enlaces */
            border-radius: 5px; /* Esquinas redondeadas */
            margin-right: 10px; /* Margen a la derecha */
            border: none; /* Sin bordes */
            cursor: pointer; /* El cursor cambia a puntero */
            transition: background-color 0.3s ease; /* Suave transición de color al hacer hover */
            font-size: 16px; /* Tamaño de fuente */
        }

        /* Efecto hover para los botones (cambia el color del fondo) */
        .btn:hover, 
        input[type="submit"]:hover {
            background-color: #555; /* Fondo gris oscuro al pasar el mouse */
        }

        /* Estilo especial para el botón "Insertar Nuevo Usuario" */
        .btn-insert {
            display: block; /* Botón en bloque, ocupa todo el ancho disponible */
            width: 250px; /* Ancho fijo */
            margin: 30px auto; /* Centrado horizontalmente */
            text-align: center; /* Centrar el texto dentro del botón */
            font-size: 18px; /* Tamaño de fuente */
        }

        /* Estilos para el formulario de búsqueda */
        .search-form {
            margin-bottom: 30px; /* Margen inferior */
            padding: 20px; /* Espaciado interior */
            background-color: #f0f0f0; /* Fondo gris claro */
            border-radius: 8px; /* Esquinas redondeadas */
            display: flex; /* Flexbox para alinear los elementos */
            justify-content: space-between; /* Espacio entre los elementos */
            align-items: center; /* Alinear verticalmente los elementos al centro */
            flex-wrap: wrap; /* Permitir que los elementos se ajusten en pantallas pequeñas */
        }

        /* Estilos para los campos de entrada de texto y números */
        .search-form input[type="text"],
        .search-form input[type="number"] {
            padding: 12px; /* Espaciado interior */
            margin: 5px; /* Margen exterior */
            border: 1px solid #ccc; /* Borde gris claro */
            border-radius: 5px; /* Esquinas redondeadas */
            flex-grow: 1; /* Los campos de entrada crecen para ocupar espacio disponible */
            font-size: 16px; /* Tamaño de fuente */
        }

        /* Estilo para el botón de búsqueda */
        .search-form input[type="submit"] {
            flex-grow: 0; /* No permite que el botón crezca */
            font-size: 16px; /* Tamaño de fuente */
        }

        /* Estilos responsivos para pantallas pequeñas */
        @media (max-width: 768px) {
            .container {
                padding: 20px; /* Reducir el padding en pantallas pequeñas */
            }

            .search-form {
                flex-direction: column; /* Poner los elementos en una columna en lugar de una fila */
            }

            .search-form input[type="text"],
            .search-form input[type="number"],
            .search-form input[type="submit"] {
                width: 100%; /* Hacer que los elementos ocupen el 100% del ancho */
                margin: 10px 0; /* Margen superior e inferior */
            }
        }

        /* Estilos para el botón de volver */
        .btn-volver {
            background-color: #000; /* Fondo negro */
            color: white; /* Texto blanco */
            padding: 10px 20px; /* Espaciado interior */
            text-decoration: none; /* Sin subrayado */
            border-radius: 5px; /* Esquinas redondeadas */
            display: inline-block; /* Comportamiento en línea tipo bloque */
            margin-top: 20px; /* Margen superior */
            transition: background-color 0.3s ease; /* Suave transición al hacer hover */
        }

        /* Efecto hover para el botón de volver */
        .btn-volver:hover {
            background-color: #555; /* Fondo gris oscuro al pasar el mouse */
        }

        /* Contenedor de botones, para alinear elementos */
        .button-container {
            display: flex; /* Flexbox para alinear los botones */
            justify-content: space-between; /* Espacio entre los botones */
            align-items: center; /* Alinear los botones verticalmente */
            margin-top: 30px; /* Margen superior */
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestión de Usuarios</h1> <!-- Título principal de la página -->

        <!-- Formulario de búsqueda de usuarios -->
        <form class="search-form" method="GET">
            <input type="number" name="usua_codigo" placeholder="Código de usuario"> <!-- Campo para buscar por código de usuario -->
            <input type="text" name="usua_nombre" placeholder="Nombre de usuario"> <!-- Campo para buscar por nombre de usuario -->
            <input type="number" name="id_rol" placeholder="ID de rol"> <!-- Campo para buscar por ID de rol -->
            <input type="submit" value="Buscar"> <!-- Botón para enviar el formulario -->
        </form>

        <!-- Enlace para insertar un nuevo usuario -->
        <a href="usuarios.php" class="btn btn-insert">Insertar Nuevo Usuario</a>

        <!-- Enlace para volver al panel de administración -->
        <a href="../vista/admin.html" class="btn-volver">Volver</a>

        <?php
        // Configuración de la conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dakara";

        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar los parámetros de búsqueda
        $usua_codigo = isset($_GET['usua_codigo']) && $_GET['usua_codigo'] !== '' ? intval($_GET['usua_codigo']) : null;
        $usua_nombre = isset($_GET['usua_nombre']) && $_GET['usua_nombre'] !== '' ? $_GET['usua_nombre'] : null;
        $id_rol = isset($_GET['id_rol']) && $_GET['id_rol'] !== '' ? intval($_GET['id_rol']) : null;

        // Llamar al procedimiento almacenado para buscar usuarios
        $sql = "CALL sp_consultar_usuarios(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $usua_codigo, $usua_nombre, $id_rol); // Enlazar parámetros
        $stmt->execute(); // Ejecutar la consulta
        $result = $stmt->get_result(); // Obtener resultados

        // Verificar si hay resultados y mostrarlos en una tabla
        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>ID Rol</th>
                        <th>Acciones</th>
                    </tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["usua_codigo"]."</td>
                        <td>".$row["usua_nombre"]."</td>
                        <td>".$row["id_rol"]."</td>
                        <td>
                            <a href='actuusu.php?id=".$row["usua_codigo"]."' class='btn'>Actualizar</a> <!-- Enlace para actualizar -->
                            <a href='borrarusu.php?id=".$row["usua_codigo"]."' class='btn' onclick='return confirm(\"¿Estás seguro de que quieres borrar este usuario?\");'>Borrar</a> <!-- Enlace para borrar -->
                        </td>
                      </tr>";
            }
            echo "</table>"; // Cierre de tabla
        } else {
            echo "<p>No se encontraron resultados.</p>"; // Mostrar mensaje si no hay resultados
        }

        $stmt->close(); // Cerrar la consulta
        $conn->close(); // Cerrar la conexión a la base de datos
        ?>
    </div>
</body>
</html>
