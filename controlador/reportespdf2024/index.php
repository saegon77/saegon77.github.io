<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Generar Reportes</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
//Incluimos el fichero de conexion
include_once("dbconect.php");
?>
</head>
<body>
<div class="container" style="padding-top:50px">
<h2>Generar Reportes</h2>
<form class="form-inline" method="post" action="crear_pdf.php">
<button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf" aria-hidden="true"></i>
Exportar PDF</button>
</form>
</fieldset>
<hr>
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">codigo</th>
      <th scope="col">Nombre</th>
      <th scope="col">clave</th>
      <th scope="col">id_rol</th>
    </tr>
  </thead>
  <tbody>

<?php
$db = new dbConexion();
$connString =  $db->getConexion();
 
$result = mysqli_query($connString, "SELECT usua_codigo, usua_nombre, usua_clave, id_rol FROM usuario") or die("database error:". mysqli_error($connString));
 foreach($result as $row)
    {
   echo '<tr>
      <th scope="row">'.$row['usua_codigo'].'</th>
      <td>'.$row['usua_nombre'].'</td>
      <td>'.$row['usua_clave'].'</td>
      <td>'.$row['id_rol'].'</td>
    </tr>';
    }
?>
  </tbody>
</table>
</div>
</body>
</html>
