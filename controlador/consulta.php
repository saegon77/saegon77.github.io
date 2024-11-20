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

// Consulta SQL con INNER JOIN
$sql = "SELECT 
            u.usua_codigo,
            u.usua_nombre,
            a.admi_codigo,
            a.admi_identificacion,
            a.admi_tipoidentificacion,
            a.admi_nombre,
            a.admi_apellido,
            a.admi_celular,
            a.admi_direccion
        FROM 
            usuario u
        INNER JOIN 
            administrador a ON u.usua_codigo = a.usua_codigo";

$result = $conn->query($sql);

// Verificar si hay resultados y mostrarlos
if ($result->num_rows > 0) {
    echo "<table border='1'>
    <tr>
    <th>Código Usuario</th>
    <th>Nombre Usuario</th>
    <th>Código Admin</th>
    <th>Identificación</th>
    <th>Tipo ID</th>
    <th>Nombre</th>
    <th>Apellido</th>
    <th>Celular</th>
    <th>Dirección</th>
    </tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["usua_codigo"] . "</td>";
        echo "<td>" . $row["usua_nombre"] . "</td>";
        echo "<td>" . $row["admi_codigo"] . "</td>";
        echo "<td>" . $row["admi_identificacion"] . "</td>";
        echo "<td>" . $row["admi_tipoidentificacion"] . "</td>";
        echo "<td>" . $row["admi_nombre"] . "</td>";
        echo "<td>" . $row["admi_apellido"] . "</td>";
        echo "<td>" . $row["admi_celular"] . "</td>";
        echo "<td>" . $row["admi_direccion"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}

// Cerrar la conexión
$conn->close();
?>

<style>
/* Estilos generales para el cuerpo de la página */
body {
    font-family: Arial, sans-serif; /* Tipo de letra para todo el documento */
    background-color: #f0f0f0; /* Color de fondo gris claro */
    margin: 0; /* Elimina el margen predeterminado */
    padding: 20px; /* Añade un poco de espacio alrededor del contenido */
}
/* Estilos para la tabla */
table {
    width: 100%; /* La tabla ocupa todo el ancho disponible */
    border-collapse: collapse; /* Combina los bordes de las celdas */
    background-color: white; /* Fondo blanco para la tabla */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Sombra suave alrededor de la tabla */
}
/* Estilos para las celdas de encabezado y datos */
th, td {
    padding: 12px; /* Espacio interno en las celdas */
    text-align: left; /* Alinea el texto a la izquierda */
    border-bottom: 1px solid #ddd; /* Línea gris claro entre filas */
}
/* Estilos específicos para las celdas de encabezado */
th {
    background-color: #333; /* Fondo negro para los encabezados */
    color: white; /* Texto blanco en los encabezados */
    font-weight: bold; /* Texto en negrita */
    text-transform: uppercase; /* Convierte el texto a mayúsculas */
}
/* Estilo para filas pares */
tr:nth-child(even) {
    background-color: #f2f2f2; /* Fondo gris muy claro para filas pares */
}
/* Efecto al pasar el mouse sobre las filas */
tr:hover {
    background-color: #ddd; /* Cambia el color de fondo al pasar el mouse */
}
</style>