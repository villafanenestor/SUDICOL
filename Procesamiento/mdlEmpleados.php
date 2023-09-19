<?php

require_once("Conexion.php");

$option = $_POST["option"];
if($option=="guardarEmpleado") guardarEmpleado();
elseif($option=="listarEmpleados") listarUsuarios();
elseif($option=="buscarEmpleado") buscarEmpleado();
elseif($option=="actualizarEmpleado") actualizarEmpleado();


function guardarEmpleado()
{
  global $mysqli;
  $nombre = $mysqli->real_escape_string(trim($_POST["nombre"]));
  $usuario = $mysqli->real_escape_string(trim($_POST["usuario"]));
  $password = $mysqli->real_escape_string(trim($_POST["password"]));
  $email = $mysqli->real_escape_string(trim($_POST["email"]));
  $tipo = $mysqli->real_escape_string(trim($_POST["tipo"]));
  $estado = $mysqli->real_escape_string(trim($_POST["estado"]));
  $consultarUsuario = "SELECT * FROM usuarios WHERE usuario='$usuario'";
  $consultaUsuario = $mysqli->query($consultarUsuario);
  if($consultaUsuario->num_rows > 0){
    echo "El usuario $usuario, ya existe.";
  }else{
    $sql = "INSERT INTO usuarios (nombre, usuario, pass, correo, tipo, estado) VALUES ('$nombre', '$usuario', '$password', '$email', '$tipo', '$estado')";
    $resultCreacion = $mysqli->query($sql);
    if($resultCreacion)echo "OK";
    else echo "Hubo un error al crear el usuario $sql";
  }

}

function actualizarEmpleado()
{
  global $mysqli;
  $nombre = $mysqli->real_escape_string(trim($_POST["nombre"]));
  $usuario = $mysqli->real_escape_string(trim($_POST["usuario"]));
  $pass = $mysqli->real_escape_string(trim($_POST["password"]));
  $correo = $mysqli->real_escape_string(trim($_POST["email"]));
  $tipo = $mysqli->real_escape_string(trim($_POST["tipo"]));
  $estado = $mysqli->real_escape_string(trim($_POST["estado"]));
  $update = "UPDATE usuarios SET nombre='$nombre', pass='$pass', correo='$correo', tipo='$tipo', estado='$estado' WHERE usuario='$usuario';";
  if($mysqli->query($update))echo "OK";
  else echo "No se ha podido Actualizar, verifique los datos";
}



function listarUsuarios()
{
    global $mysqli;
    $consulta = "SELECT usuario, nombre, pass, tipo, correo, estado FROM usuarios;";


    $rowstable = "";
    $resultEmpleados = $mysqli->query($consulta);
    if($resultEmpleados->num_rows > 0) {
        while($row = $resultEmpleados->fetch_array()) {
          $usuario = $row["usuario"];
          $nombre = $row["nombre"];
          $password = $row["pass"];
          $tipo = $row["tipo"];
          $correo = $row["correo"];
          $estado = $row["estado"];
          $usuarioBusqueda = "'$usuario'";
          $btnEditar = '<button type="button" class="btn btn-success btn-circle" onclick="buscarEmpleado('.$usuarioBusqueda.');"><i class="fa fa-edit"></i></button>';
          $rowstable .= "<tr>
          <td>$usuario</td>
          <td>$nombre</td>
          <td>$password</td>
          <td>$tipo</td>
          <td>$correo</td>
          <td>$estado</td>
          <td>$btnEditar</td>
            </tr>";                
        }
    }

    

    $titleTable = "<tr> 
                <th>USUARIO</th>
                <th>NOMBRE</th>
                <th>CONTRASEÑA</th>
                <th>TIPO</th>
                <th>CORREO</th>
                <th>ESTADO</th>
                <th>OPCIONES</th>
                </tr>";

    $tabla='<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
              <thead>'.$titleTable.'</thead>
              <tfoot>'.$titleTable.'</tfoot>  
              <tbody>
              '.$rowstable.'
              </tbody>
            </table>
        ';

    echo $tabla;
}

function buscarEmpleado(){
    global $mysqli;
    $usuario = $mysqli->real_escape_string(trim($_POST["usuario"]));
    $consulta = "SELECT usuario, nombre, pass, tipo, correo, estado FROM usuarios WHERE usuario='$usuario';";
    $resultBusqueda = $mysqli->query($consulta);
    $dataUsuario = array();
    if($resultBusqueda->num_rows > 0) {
      $dataUsuario = $resultBusqueda->fetch_array();
    }

    header('Content-Type: application/json');
    echo json_encode($dataUsuario);
}

mysqli_close($mysqli);
