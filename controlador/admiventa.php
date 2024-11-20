<?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dakara";


        // Crear conexión a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variables de búsqueda
$vent_codigo = $vent_fecha = $vent_total = $vent_cantidadtotal = $clie_codigo = $admi_codigo = null;

// Procesar la búsqueda si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vent_codigo = !empty($_POST['vent_codigo']) ? $_POST['vent_codigo'] : null;
    $vent_fecha = !empty($_POST['vent_fecha']) ? $_POST['vent_fecha'] : null;
    $vent_total = !empty($_POST['vent_total']) ? $_POST['vent_total'] : null;
    $vent_cantidadtotal = !empty($_POST['vent_cantidadtotal']) ? $_POST['vent_cantidadtotal'] : null;
    $clie_codigo = !empty($_POST['clie_codigo']) ? $_POST['clie_codigo'] : null;
    $admi_codigo = !empty($_POST['admi_codigo']) ? $_POST['admi_codigo'] : null;
}

// Llamar al procedimiento almacenado de consulta
$stmt = $conn->prepare("CALL sp_consultar_ventas(?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isiiii", $vent_codigo, $vent_fecha, $vent_total, $vent_cantidadtotal, $clie_codigo, $admi_codigo);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <style>
        /* Estilos en blanco y negro */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #000;
            text-align: center;
        }
        form, .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        label {
            display: inline-block;
            margin-right: 10px;
            color: #000;
        }
        input[type="text"],
        input[type="date"],
        input[type="number"] {
            padding: 5px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"],
        .button {
            padding: 10px 15px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        input[type="submit"]:hover,
        .button:hover {
            background-color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Gestión de Ventas</h1>

    <!-- Formulario de búsqueda -->
    <form method="post">

        <label for="vent_codigo">Código:</label>
        <input type="number" id="vent_codigo" name="vent_codigo">

        <label for="vent_fecha">Fecha:</label>
        <input type="date" id="vent_fecha" name="vent_fecha">

        <label for="vent_total">Total:</label>
        <input type="number" id="vent_total" name="vent_total">

        <label for="vent_cantidadtotal">Cantidad Total:</label>
        <input type="number" id="vent_cantidadtotal" name="vent_cantidadtotal">

        <label for="clie_codigo">Código Cliente:</label>
        <input type="number" id="clie_codigo" name="clie_codigo">

        <label for="admi_codigo">Código Admin:</label>
        <input type="number" id="admi_codigo" name="admi_codigo">

        <input type="submit" value="Buscar">
        <a href="../vista/admin.html" class="button" >Volver</a>
    </form>

    <!-- Tabla de resultados -->
    <a href="./crearventa.php" class="button">Crear Venta</a>
        <a href="./actuventa.php" class="button">Actualizar Venta</a>
        <a href="./borrarventa.php" class="button">Eliminar Venta</a>
    <div class="table-container">
        <table>
            
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Cantidad Total</th>
                    <th>Cliente</th>
                    <th>Administrador</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['vent_codigo']); ?></td>
                    <td><?php echo htmlspecialchars($row['vent_fecha']); ?></td>
                    <td><?php echo htmlspecialchars($row['vent_total']); ?></td>
                    <td><?php echo htmlspecialchars($row['vent_cantidadtotal']); ?></td>
                    <td><?php echo htmlspecialchars($row['clie_nombre'] . ' ' . $row['clie_apellido']); ?></td>
                    <td><?php echo $row['admi_nombre'] ? htmlspecialchars($row['admi_nombre'] . ' ' . $row['admi_apellido']) : 'N/A'; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>