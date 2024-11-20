<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta etiquetas para la codificación y la adaptabilidad a dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Cliente</title>
    <style>
        /* Estilo del cuerpo de la página */
        body {
            font-family: Arial, sans-serif; /* Fuente utilizada en la página */
            background-color: #f0f0f0; /* Color de fondo de la página */
            color: #333; /* Color del texto */
            line-height: 1.6; /* Altura de línea */
            padding: 20px; /* Espaciado interno */
        }
        /* Estilo del contenedor */
        .container {
            max-width: 600px; /* Ancho máximo del contenedor */
            margin: 0 auto; /* Centrado horizontal */
            background-color: #fff; /* Color de fondo del contenedor */
            padding: 20px; /* Espaciado interno del contenedor */
            border-radius: 5px; /* Bordes redondeados */
            box-shadow: 0 0 10px rgba(0,0,0,0.1); /* Sombra alrededor del contenedor */
        }
        /* Estilo del encabezado */
        h1 {
            color: #000; /* Color del texto del encabezado */
            text-align: center; /* Centrado del texto */
        }
        /* Estilo del formulario */
        form {
            margin-top: 20px; /* Espaciado superior para el formulario */
        }
        /* Estilo de las etiquetas */
        label {
            display: block; /* Mostrar etiquetas como bloque */
            margin-bottom: 5px; /* Espaciado inferior para etiquetas */
        }
        /* Estilo de los campos de entrada */
        input[type="text"],
        input[type="number"] {
            width: 100%; /* Ancho completo para los campos */
            padding: 8px; /* Espaciado interior */
            margin-bottom: 10px; /* Espaciado inferior para campos */
            border: 1px solid #ddd; /* Borde de los campos */
            border-radius: 4px; /* Bordes redondeados */
        }
        /* Estilo de los botones */
        input[type="submit"],
        .btn-volver {
            background-color: #333; /* Color de fondo de los botones */
            color: #fff; /* Color del texto de los botones */
            border: none; /* Sin borde */
            padding: 10px 15px; /* Espaciado interno del botón */
            cursor: pointer; /* Cambiar el cursor al pasar sobre el botón */
            border-radius: 4px; /* Bordes redondeados */
        }
        /* Efecto hover en los botones */
        input[type="submit"]:hover,
        .btn-volver:hover {
            background-color: #555; /* Color de fondo al pasar el cursor */
        }
        /* Estilo de los mensajes de éxito y error */
        .mensaje {
            margin-top: 20px; /* Espaciado superior para mensajes */
            padding: 10px; /* Espaciado interno de los mensajes */
            border-radius: 4px; /* Bordes redondeados */
        }
        .exito {
            background-color: #dff0d8; /* Color de fondo de éxito */
            border: 1px solid #d6e9c6; /* Borde verde */
            color: #3c763d; /* Texto verde */
        }
        .error {
            background-color: #f2dede; /* Color de fondo de error */
            border: 1px solid #ebccd1; /* Borde rojo */
            color: #a94442; /* Texto rojo */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Actualizar Cliente</h1>
        
        <?php
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Configuración de la conexión a la base de datos
            $servername = "localhost"; // Servidor de la base de datos
            $username = "root"; // Usuario de la base de datos
            $password = ""; // Contraseña del usuario
            $dbname = "dakara"; // Nombre de la base de datos
            
            // Crear conexión
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexión
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error); // Mostrar mensaje de error y finalizar
            }

            // Obtener datos del formulario
            $codigo = $_POST['codigo']; // Código del cliente
            $identificacion = $_POST['identificacion']; // Identificación del cliente
            $tipoIdentificacion = $_POST['tipoIdentificacion']; // Tipo de identificación
            $nombre = $_POST['nombre']; // Nombre del cliente
            $apellido = $_POST['apellido']; // Apellido del cliente
            $celular = $_POST['celular']; // Celular del cliente
            $direccion = $_POST['direccion']; // Dirección del cliente

            // Llamar al procedimiento almacenado para actualizar cliente
            $sql = "CALL sp_actualizar_cliente(?, ?, ?, ?, ?, ?, ?)"; // Consulta para llamar al procedimiento
            $stmt = $conn->prepare($sql); // Prepara la consulta
            $stmt->bind_param("issssss", $codigo, $identificacion, $tipoIdentificacion, $nombre, $apellido, $celular, $direccion); // Asigna los parámetros

            // Ejecutar la consulta y mostrar mensaje según el resultado
            if ($stmt->execute()) {
                echo "<div class='mensaje exito'>Cliente actualizado exitosamente.</div>"; // Mensaje de éxito
            } else {
                echo "<div class='mensaje error'>Error al actualizar cliente: " . $stmt->error . "</div>"; // Mensaje de error
            }

            // Cerrar la consulta y la conexión
            $stmt->close(); // Cierra la consulta preparada
            $conn->close(); // Cierra la conexión a la base de datos
        }
        ?>

        <!-- Formulario para actualizar cliente -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="codigo">Código del Cliente:</label>
            <input type="number" id="codigo" name="codigo" required> <!-- Campo para código del cliente -->

            <label for="identificacion">Identificación:</label>
            <input type="text" id="identificacion" name="identificacion" required> <!-- Campo para identificación -->

            <label for="tipoIdentificacion">Tipo de Identificación:</label>
            <input type="text" id="tipoIdentificacion" name="tipoIdentificacion" required> <!-- Campo para tipo de identificación -->

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required> <!-- Campo para nombre -->

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required> <!-- Campo para apellido -->

            <label for="celular">Celular:</label>
            <input type="text" id="celular" name="celular" required> <!-- Campo para celular -->

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required> <!-- Campo para dirección -->

            <input type="submit" value="Actualizar Cliente"> <!-- Botón para enviar el formulario -->
        </form>

        <br>
        <!-- Enlace para volver a la página principal -->
        <a href="admiclie.php" class="btn-volver">Volver</a>
    </div>
</body>
</html>

```