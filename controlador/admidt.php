<?php
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

// Consulta para obtener todos los detalles de venta con información relacionada
$sql = "SELECT 
            dv.deve_codigo,
            dv.deve_subtotal,
            dv.deve_cantidad_por_producto,
            v.vent_codigo,
            v.vent_fecha,
            p.prod_codigo,
            p.prod_nombre,
            p.prod_precioventa
        FROM detalle_venta dv
        INNER JOIN venta v ON dv.vent_codigo = v.vent_codigo
        INNER JOIN producto p ON dv.prod_codigo = p.prod_codigo
        ORDER BY dv.deve_codigo DESC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Detalles de Venta</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }

        /* Contenedor principal */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Estilos para el encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        /* Estilos para títulos */
        h1 {
            color: #333;
            margin: 0;
        }

        /* Estilos para la tabla */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Estilos para botones */
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 2px;
            color: white;
        }

        .btn-crear {
            background-color: #333;
        }

        .btn-editar {
            background-color: #666;
        }

        .btn-eliminar {
            background-color: #999;
        }

        .btn-volver {
            background-color: #333;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Estilos para acciones en la tabla */
        .acciones {
            display: flex;
            gap: 5px;
        }

        /* Estilos para el formato de moneda */
        .moneda {
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Encabezado con título y botones principales -->
        <div class="header">
            <h1>Gestión de Detalles de Venta</h1>
            <div>
                <!-- Botón de volver -->
                <a href="../vista/admin.html" class="btn btn-volver">Volver</a>
                <!-- Botón para crear nuevo detalle de venta -->
                <a href="creardt.php" class="btn btn-crear">Crear Nuevo Detalle</a>
            </div>
        </div>

        <!-- Contenedor de la tabla -->
        <div class="table-container">
            <table>
                <!-- Encabezados de la tabla -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fecha Venta</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Cuerpo de la tabla -->
                <tbody>
                    <?php
                    // Verificar si hay resultados
                    if ($resultado->num_rows > 0) {
                        // Iterar sobre cada detalle de venta
                        while($row = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['deve_codigo'] . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($row['vent_fecha'])) . "</td>";
                            echo "<td>" . $row['prod_nombre'] . "</td>";
                            echo "<td>" . $row['deve_cantidad_por_producto'] . "</td>";
                            echo "<td class='moneda'>$" . number_format($row['prod_precioventa'], 2) . "</td>";
                            echo "<td class='moneda'>$" . number_format($row['deve_subtotal'], 2) . "</td>";
                            echo "<td class='acciones'>";
                            // Botones de acción para cada registro
                            echo "<a href='actudt.php?id=" . $row['deve_codigo'] . "' class='btn btn-editar'>Editar</a>";
                            echo "<a href='borrardt.php?id=" . $row['deve_codigo'] . "' class='btn btn-eliminar'>Eliminar</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Mensaje cuando no hay registros
                        echo "<tr><td colspan='7' style='text-align: center;'>No hay detalles de venta registrados</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script para confirmar eliminación (opcional) -->
    <script>
        // Función para confirmar antes de eliminar
        function confirmarEliminacion(id) {
            if (confirm('¿Está seguro que desea eliminar este detalle de venta?')) {
                window.location.href = 'eliminar_detalle_venta.php?id=' + id;
            }
        }
    </script>
</body>
</html>