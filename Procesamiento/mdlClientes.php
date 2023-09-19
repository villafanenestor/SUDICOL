<?php 
require_once("Conexion.php");

$option = isset($_POST['option']) ? $_POST['option'] : '';

if($option == 'guardarCliente') guardarCliente();
else if($option == 'listarClientes') listarClientes();
else if($option == 'buscarCliente') buscarCliente();
else if($option == 'actualizarCliente') actualizarCliente();
else echo "opcion invalida";


  // if( isset($_POST["Ingresar"]) ){
	// 	Ingresar($mysqli);							
	// }else if(isset($_POST["Listar"])){
  //   Listar($mysqli);
  // }else if(isset($_POST["Buscar_Datos"])){
  //   buscar($mysqli);
  // }else if(isset($_POST["Actualizar"])){
  //   Actualizar($mysqli);
  // }
			

function Ingresar($mysqli){
 

 $consulta=" INSERT clientes(
             `nombre`,
             `direccion`,
             `correo`,
             `telefono`,
             `contacto`,
             `nit`,
             `bancarios`,
             `observaciones`,
             `estado`,
             `fechaingreso`) VALUE (
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Direccion"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Correo"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Telefono"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Contacto"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["NIT"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Bancarios"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
 '".mysqli_real_escape_string($mysqli, ($_POST["Estado"]))."',curdate() ) ";  

      if( $datos=mysqli_query($mysqli,$consulta) ){
        echo 'OK';
      }else{
        echo 'No se ha podido ingresar, verifique los datos';
      }
 

}
 

function Actualizar($mysqli){


    $consulta=" UPDATE clientes SET 
    nombre='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."',
    direccion='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Direccion"]))."',
    correo='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Correo"]))."',
    telefono='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Telefono"]))."',
    contacto='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Contacto"]))."',
    nit='".mysqli_real_escape_string($mysqli,strtoupper($_POST["NIT"]))."',
    bancarios='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Bancarios"]))."', 
    observaciones='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
    estado='".mysqli_real_escape_string($mysqli,($_POST["Estado"]))."'
    WHERE cons='".mysqli_real_escape_string($mysqli,$_POST["codigoc"])."' "; 
    if( $datos=mysqli_query($mysqli,$consulta)){
      echo 'OK';
    }else{
      echo 'No se ha podido Actualizar, verifique los datos';
    }
     
}
  

function buscar($mysqli){

  $arreglo= array();
  $consulta=" SELECT cons, `nombre`,
             `direccion`,
             `telefono`,
             `correo`,
             `contacto`,
             `nit`,
             `bancarios`,
             `estado`,
             `observaciones`,
             `fechaingreso` FROM clientes WHERE 
  cons='".mysqli_real_escape_string($mysqli,$_POST["Buscar_Datos"])."' "; 

  $datos=mysqli_query($mysqli,$consulta);
  if(mysqli_num_rows($datos)>0){
  $row=mysqli_fetch_row($datos); 
        $arreglo[]=$row;
        echo json_encode($arreglo);    
  }else{  
    echo 'n'; 
  }

}

function Listar($mysqli){  
 
       
          $consulta = " SELECT cons,nombre,nit,contacto,estado FROM clientes   ORDER BY nombre ";    
          $datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>ID</th>
          <th>CLIENTE</th>
          <th>NIT</th>
          <th>CONTACTO</th> 
          <th>ESTADO</th>
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr> 
          <th>ID</th>
          <th>CLIENTE</th>
          <th>NIT</th>
          <th>CONTACTO</th> 
          <th>ESTADO</th>
          <th></th></tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
  while($row=mysqli_fetch_row($datos)){ 
    
                $tabla.='<tr >
              
              <td class="sobretd"  >'.$row[0].'</td>
              <td class="sobretd" >'.$row[1].'</td>
              <td class="sobretd"   >'.$row[2].'</td>
              <td class="sobretd"  >'.$row[3].'</td>
              <td class="sobretd"  >'.$row[4].'</td>
              <td class="sobretd"  ><button type="button" class="btn btn-success btn-circle" onclick="Buscar_Datos('.$row[0].');"><i class="fa fa-edit"></i></button></td>
            </tr>';
              }
                
            }  

            $tabla.='</tbody></table>';

            echo $tabla;
}//<td class="sobretd" id="'.$row[0].'" >'.$row[2].'</td>


function guardarCliente(){
  global $mysqli;
  $ruta = "../Archivos/Soportes/";

  $archivosGuardados = 0;
  $archivos = array();
  $errores = "";

  foreach ($_FILES as $key => $file) {
    $error = $file['error'];
      if ($error !== UPLOAD_ERR_OK) {
          // Hubo un error al subir el archivo
          $errores = "Hubo un error al subir el archivo: $key </br>";
          break;
      }else{
        $extension = explode(".", $file['name']);
        $nombre = time()."-$key.".$extension[count($extension) - 1];
        $nobmreTemporal = $file['tmp_name']; //Obtenemos el nombre del archivo temporal
        move_uploaded_file($nobmreTemporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
        if (file_exists($ruta . $nombre)) {
          $archivosGuardados++;
          $archivos[$key] = $nombre;
        }else{
          $errores = "No se pudo moverl el archivo $nombre";
        }
      }
  }

  if($archivosGuardados>=0){
    $razonSocial = $mysqli->real_escape_string(trim($_POST["razonSocial"]));//Razosocial,
    $nit = $mysqli->real_escape_string(trim($_POST["nit"]));//NIT,
    $direccion = $mysqli->real_escape_string(trim($_POST["direccion"]));//Direccion,
    $ciudad = $mysqli->real_escape_string(trim($_POST["ciudad"]));//Ciudad,
    $telefono = $mysqli->real_escape_string(trim($_POST["telefono"]));//Telefono,
    $direccionCorrespondencia = $mysqli->real_escape_string(trim($_POST["direccionCorrespondencia"]));//Direccioncorrespondencia,
    $actividadEconomica = $mysqli->real_escape_string(trim($_POST["actividadEconomica"]));//Actividadeconomica,
    $representanteLegal = $mysqli->real_escape_string(trim($_POST["representanteLegal"]));//Representantelegal,
    $cargo = $mysqli->real_escape_string(trim($_POST["cargo"]));//Cargo,
    $escrConsNo = $mysqli->real_escape_string(trim($_POST["escrConsNo"]));//EscrituraNo,
    $escrConstFecha = $mysqli->real_escape_string(trim($_POST["escrConstFecha"]));//EscrituraFecha,
    $escrConsNotaria = $mysqli->real_escape_string(trim($_POST["escrConsNotaria"]));//EscrituraNotaria,
    $escrConsCiudad = $mysqli->real_escape_string(trim($_POST["escrConsCiudad"]));//EscrituraCiudad,
    $registroCamaraComercio = $mysqli->real_escape_string(trim($_POST["registroCamaraComercio"]));//RegistroCamaradeComercio,
    $fechaRegistroCamaraComercio = $mysqli->real_escape_string(trim($_POST["fechaRegistroCamaraComercio"]));//CamaraFecha,
    $nombreAreaCompra = $mysqli->real_escape_string(trim($_POST["nombreAreaCompra"]));//ComprasNombre,
    $cargoAreaCompra = $mysqli->real_escape_string(trim($_POST["cargoAreaCompra"]));//ComprasCargo,
    $telefonoAreaCompra = $mysqli->real_escape_string(trim($_POST["telefonoAreaCompra"]));//ComprasTelefono,
    $correoAreaCompra = $mysqli->real_escape_string(trim($_POST["correoAreaCompra"]));//ComprasCorreo,
    $nombreAreaFTP = $mysqli->real_escape_string(trim($_POST["nombreAreaFTP"]));//FinancieraNombre,
    $cargoAreaFTP = $mysqli->real_escape_string(trim($_POST["cargoAreaFTP"]));//FinancieraCargo,
    $telefonoAreaFTP = $mysqli->real_escape_string(trim($_POST["telefonoAreaFTP"]));//FinancieraTelefono,
    $correoAreaFTP = $mysqli->real_escape_string(trim($_POST["correoAreaFTP"]));//FinancieraCorreo,
    $ciudadAreaFTP = $mysqli->real_escape_string(trim($_POST["ciudadAreaFTP"]));//FinancieraCiudad,
    $infoTributariaTipo = $mysqli->real_escape_string(trim($_POST["infoTributariaTipo"]));//TipoContribuyente,
    $infoTributariaCiudad = $mysqli->real_escape_string(trim($_POST["infoTributariaCiudad"]));//CiudadICA,
    $infoTributariaFechaMaxRecepFact = $mysqli->real_escape_string(trim($_POST["infoTributariaFechaMaxRecepFact"]));//Fechafacturas,
    $infoTributariaCorreo = $mysqli->real_escape_string(trim($_POST["infoTributariaCorreo"]));//Correofacturacion,
    $infoAccionariaNombre = $mysqli->real_escape_string(trim($_POST["infoAccionariaNombre"]));//AccionariaNombre,
    $infoAccionariaCCoNIT = $mysqli->real_escape_string(trim($_POST["infoAccionariaCCoNIT"]));//AccionariaCC,
    $infoAccionariaDireccion = $mysqli->real_escape_string(trim($_POST["infoAccionariaDireccion"]));//AccionariaDireccion,
    $infoAccionariaTelefono = $mysqli->real_escape_string(trim($_POST["infoAccionariaTelefono"]));//AccionariaTelefono,
    $infoAccionariaCiudad = $mysqli->real_escape_string(trim($_POST["infoAccionariaCiudad"]));//AccionariaCiudad,
    $infoAccionariaPorcenAcc = $mysqli->real_escape_string(trim($_POST["infoAccionariaPorcenAcc"]));//Accionariaacc,
    $bancosNombre = $mysqli->real_escape_string(trim($_POST["bancosNombre"]));//Banco,
    $bancosCuenta = $mysqli->real_escape_string(trim($_POST["bancosCuenta"]));//BancoCuenta,
    $bancosSucursal = $mysqli->real_escape_string(trim($_POST["bancosSucursal"]));//BancoSucursal,
    $bancosTelefono = $mysqli->real_escape_string(trim($_POST["bancosTelefono"]));//BancoTelefono,
    $bancosCiudad = $mysqli->real_escape_string(trim($_POST["bancosCiudad"]));//BancoCiudad,
    $proveedoresRazonSocial = $mysqli->real_escape_string(trim($_POST["proveedoresRazonSocial"]));//ProveedoresRazonsocial,
    $proveedoresDireccion = $mysqli->real_escape_string(trim($_POST["proveedoresDireccion"]));//ProveedoresDireccion,
    $proveedoresTelefono = $mysqli->real_escape_string(trim($_POST["proveedoresTelefono"]));//ProveedoresTelefonos,
    $proveedoresCiudad = $mysqli->real_escape_string(trim($_POST["proveedoresCiudad"]));//ProveedoresCiudad,
    $clientesRazonSocial = $mysqli->real_escape_string(trim($_POST["clientesRazonSocial"]));//ClientesRazonsocial,
    $clientesDireccion = $mysqli->real_escape_string(trim($_POST["clientesDireccion"]));//ClientesDireccion,
    $clientesTelefono = $mysqli->real_escape_string(trim($_POST["clientesTelefono"]));//ClientesTelefonos,
    $clientesCiudad = $mysqli->real_escape_string(trim($_POST["clientesCiudad"]));//ClientesCiudad,
    $cupoCreditoSolicitado = $mysqli->real_escape_string(trim($_POST["cupoCreditoSolicitado"]));//Cupocredito,
    $cupoCreditoPlazo = $mysqli->real_escape_string(trim($_POST["cupoCreditoPlazo"]));//PlazoPago,
    $cupoCreditoObservaciones = $mysqli->real_escape_string(trim($_POST["cupoCreditoObservaciones"]));//CupocreditoObservaciones,
    $archivoCertificadoExisRepreLegal = $archivos["archivoCertificadoExisRepreLegal"];//soporte1,
    $archivoCedulaRepresentante = $archivos["archivoCedulaRepresentante"];//soporte2,
    $archivoEstadoFinanciero = $archivos["archivoEstadoFinanciero"];//soporte3,
    $archivoRut = $archivos["archivoRut"];//soporte4,
    $archivoDeclaracionRenta = $archivos["archivoDeclaracionRenta"];//soporte5,
    $insert = "INSERT INTO u682444666_stock.clientes
      (nombre, NIT, Direccion, Ciudad, Telefono, Direccioncorrespondencia, Actividadeconomica, Representantelegal, Cargo, EscrituraNo, EscrituraFecha, EscrituraNotaria, EscrituraCiudad, RegistroCamaradeComercio, CamaraFecha, ComprasNombre, ComprasCargo, ComprasTelefono, ComprasCorreo, FinancieraNombre, 
      FinancieraCargo, FinancieraTelefono, FinancieraCorreo, FinancieraCiudad, TipoContribuyente, 
      CiudadICA, Fechafacturas, Correofacturacion, AccionariaNombre, AccionariaCC, AccionariaDireccion, 
      AccionariaTelefono, AccionariaCiudad, Accionariaacc, Banco, BancoCuenta, BancoSucursal, BancoTelefono, 
      BancoCiudad, ProveedoresRazonsocial, ProveedoresDireccion, ProveedoresTelefonos, ProveedoresCiudad, 
      ClientesRazonsocial, ClientesDireccion, ClientesTelefonos, ClientesCiudad, Cupocredito, PlazoPago, 
      CupocreditoObservaciones, soporte1, soporte2, soporte3, soporte4, soporte5,fechaguardado)
      VALUES('$razonSocial','$nit','$direccion','$ciudad','$telefono','$direccionCorrespondencia','$actividadEconomica','$representanteLegal','$cargo','$escrConsNo',
      '$escrConstFecha','$escrConsNotaria','$escrConsCiudad','$registroCamaraComercio','$fechaRegistroCamaraComercio','$nombreAreaCompra','$cargoAreaCompra',
      '$telefonoAreaCompra','$correoAreaCompra','$nombreAreaFTP','$cargoAreaFTP','$telefonoAreaFTP','$correoAreaFTP','$ciudadAreaFTP','$infoTributariaTipo',
      '$infoTributariaCiudad','$infoTributariaFechaMaxRecepFact','$infoTributariaCorreo','$infoAccionariaNombre','$infoAccionariaCCoNIT','$infoAccionariaDireccion',
      '$infoAccionariaTelefono','$infoAccionariaCiudad','$infoAccionariaPorcenAcc','$bancosNombre','$bancosCuenta','$bancosSucursal','$bancosTelefono','$bancosCiudad',
      '$proveedoresRazonSocial','$proveedoresDireccion','$proveedoresTelefono','$proveedoresCiudad','$clientesRazonSocial','$clientesDireccion','$clientesTelefono',
      '$clientesCiudad','$cupoCreditoSolicitado','$cupoCreditoPlazo','$cupoCreditoObservaciones','$archivoCertificadoExisRepreLegal','$archivoCedulaRepresentante',
      '$archivoEstadoFinanciero','$archivoRut','$archivoDeclaracionRenta', CURDATE())";
    if($mysqli->query($insert)){
      echo "OK";
    }else{
      echo "Error: $insert";
    }
  }else{
    echo $errores;
  }

}



function listarClientes(){
  global $mysqli;
  
  $select = "select c.cons, c.nombre as razonSocial, NIT, Direccion, c2.nombre as nombreCiudad, c.Telefono,
  c.Direccion, c.Ciudad, c.Direccioncorrespondencia, c.Actividadeconomica, 
  c.Representantelegal, c.Cargo, c.EscrituraNo, c.EscrituraFecha, c.EscrituraNotaria, c.EscrituraCiudad, 
  c.RegistroCamaradeComercio, c.CamaraFecha, c.ComprasNombre, c.ComprasCargo, c.ComprasTelefono, 
  c.ComprasCorreo, c.FinancieraNombre, c.FinancieraCargo, c.FinancieraTelefono, c.FinancieraCorreo, 
  c.FinancieraCiudad, c.TipoContribuyente, c.CiudadICA, c.Fechafacturas, c.Correofacturacion, 
  c.AccionariaNombre, c.AccionariaCC, c.AccionariaDireccion, c.AccionariaTelefono, c.AccionariaCiudad, 
  c.Accionariaacc, c.Banco, c.BancoCuenta, c.BancoSucursal, c.BancoTelefono, c.BancoCiudad, 
  c.ProveedoresRazonsocial, c.ProveedoresDireccion, c.ProveedoresTelefonos, 
  c.ProveedoresCiudad, c.ClientesRazonsocial, c.ClientesDireccion, 
  c.ClientesTelefonos, c.ClientesCiudad, c.Cupocredito, c.PlazoPago, 
  c.CupocreditoObservaciones, c.soporte1, c.soporte2, c.soporte3, c.soporte4, c.soporte5
  from clientes c left join ciudades c2 on c.Ciudad= c2.cons ORDER BY c2.nombre;";
  $rowsTable = "";

  $contador = 1;

  // echo $select;

  $result = $mysqli->query($select);
  if($result->num_rows>0){
    while($cliente = $result->fetch_assoc()){
      $cons = $cliente["cons"];
      $razonSocial = $cliente["razonSocial"];
      $NIT = $cliente["NIT"];
      $direccion = $cliente["Direccion"];
      $nombreCiudad = $cliente["nombreCiudad"];
      $telefono = $cliente["Telefono"];
      $btn = '<button type="button" class="btn btn-success btn-circle" onclick="Buscar_Datos('.$cons.');"><i class="fa fa-edit"></i></button></td>';
      $camposFaltantes= 0;
      $camposFaltantes += $cliente["Direccion"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Ciudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Direccioncorrespondencia"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Actividadeconomica"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Representantelegal"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Cargo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["EscrituraNo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["EscrituraFecha"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["EscrituraNotaria"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["EscrituraCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["RegistroCamaradeComercio"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["CamaraFecha"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ComprasNombre"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ComprasCargo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ComprasTelefono"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ComprasCorreo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["FinancieraNombre"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["FinancieraCargo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["FinancieraTelefono"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["FinancieraCorreo"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["FinancieraCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["TipoContribuyente"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["CiudadICA"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Fechafacturas"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Correofacturacion"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["AccionariaNombre"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["AccionariaCC"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["AccionariaDireccion"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["AccionariaTelefono"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["AccionariaCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Accionariaacc"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Banco"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["BancoCuenta"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["BancoSucursal"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["BancoTelefono"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["BancoCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ProveedoresRazonsocial"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ProveedoresDireccion"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ProveedoresTelefonos"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ProveedoresCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ClientesRazonsocial"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ClientesDireccion"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ClientesTelefonos"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["ClientesCiudad"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["Cupocredito"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["PlazoPago"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["CupocreditoObservaciones"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["soporte1"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["soporte2"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["soporte3"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["soporte4"] =="" ? 1 : 0;
      $camposFaltantes += $cliente["soporte5"] =="" ? 1 : 0;

      $class = $camposFaltantes > 0 ? "danger" : "success";
      $camposCompletos = $camposFaltantes > 0 ? "INCOMPLETO" : "COMPLETO";      
      
      $rowsTable .= "<tr>
        <td class='text-center'>$contador</td>
        <td class='text-center'>$razonSocial</td>
        <td class='text-center'>$NIT</td>
        <td class='text-center'>$direccion</td>
        <td class='text-center'>$nombreCiudad</td>
        <td class='text-center'>$telefono</td>
        <td class='bg-$class text-center'>$camposCompletos</td>
        <td class='text-center'>$btn</td>
      </tr>";
      $contador++;
    }

  }


  $titlesTable  = "<tr>
    <th class='text-center'>#</th>
    <th class='text-center'>Razón Social</th>
    <th class='text-center'>NIT</th>
    <th class='text-center'>Dirección</th>
    <th class='text-center'>Ciudad</th>
    <th class='text-center'>Teléfono</th>
    <th class='text-center'>Estado</th>
    <th class='text-center'>Opciones</th>
    </tr>";

  $tabla='<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
  <thead>'.$titlesTable.'</thead>
  <tfoot>'.$titlesTable.'</tfoot>
  <tbody>'.$rowsTable.'</tbody></table> ';

  echo $tabla;
}



function buscarCliente(){
  global $mysqli;
  $cons = $mysqli->real_escape_string(trim($_POST["consecutivo"]));
  $cliente = array();
  $select = "select * from clientes where cons ='$cons'";
  $cliente["consulta"] = $select;

  if($result = $mysqli->query($select)){
    if($result->num_rows>0){
      $cliente = $result->fetch_array();
    }
  }
  header('Content-Type: application/json');
  echo json_encode($cliente);    
}

function actualizarCliente(){
  global $mysqli;
  $ruta = "../Archivos/Soportes/";

  $archivosGuardados = 0;
  $archivos = array();
  $errores = "";
  $cantidadSoporteActualizados = $_POST["cantidadSoporteActualizados"];


  if($cantidadSoporteActualizados>0){

    foreach ($_FILES as $key => $file) {
      $error = $file['error'];
        if ($error !== UPLOAD_ERR_OK) {
            // Hubo un error al subir el archivo
            $errores = "Hubo un error al subir el archivo: $key </br>";
            break;
        }else{
          $extension = explode(".", $file['name']);
          $nombre = time()."-$key.".$extension[count($extension) - 1];
          $nobmreTemporal = $file['tmp_name']; //Obtenemos el nombre del archivo temporal
          move_uploaded_file($nobmreTemporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
          if (file_exists($ruta . $nombre)) {
            $archivosGuardados++;
            $archivos[$key] = $nombre;
          }else{
            $errores = "No se pudo moverl el archivo $nombre";
          }
        }
    }
  }

  $consecutivoEmpresa = $mysqli->real_escape_string(trim($_POST["consecutivoEmpresa"]));
  $razonSocial = $mysqli->real_escape_string(trim($_POST["razonSocial"]));//Razosocial,
  $nit = $mysqli->real_escape_string(trim($_POST["nit"]));//NIT,
  $direccion = $mysqli->real_escape_string(trim($_POST["direccion"]));//Direccion,
  $ciudad = $mysqli->real_escape_string(trim($_POST["ciudad"]));//Ciudad,
  $telefono = $mysqli->real_escape_string(trim($_POST["telefono"]));//Telefono,
  $direccionCorrespondencia = $mysqli->real_escape_string(trim($_POST["direccionCorrespondencia"]));//Direccioncorrespondencia,
  $actividadEconomica = $mysqli->real_escape_string(trim($_POST["actividadEconomica"]));//Actividadeconomica,
  $representanteLegal = $mysqli->real_escape_string(trim($_POST["representanteLegal"]));//Representantelegal,
  $cargo = $mysqli->real_escape_string(trim($_POST["cargo"]));//Cargo,
  $escrConsNo = $mysqli->real_escape_string(trim($_POST["escrConsNo"]));//EscrituraNo,
  $escrConstFecha = $mysqli->real_escape_string(trim($_POST["escrConstFecha"]));//EscrituraFecha,
  $escrConsNotaria = $mysqli->real_escape_string(trim($_POST["escrConsNotaria"]));//EscrituraNotaria,
  $escrConsCiudad = $mysqli->real_escape_string(trim($_POST["escrConsCiudad"]));//EscrituraCiudad,
  $registroCamaraComercio = $mysqli->real_escape_string(trim($_POST["registroCamaraComercio"]));//RegistroCamaradeComercio,
  $fechaRegistroCamaraComercio = $mysqli->real_escape_string(trim($_POST["fechaRegistroCamaraComercio"]));//CamaraFecha,
  $nombreAreaCompra = $mysqli->real_escape_string(trim($_POST["nombreAreaCompra"]));//ComprasNombre,
  $cargoAreaCompra = $mysqli->real_escape_string(trim($_POST["cargoAreaCompra"]));//ComprasCargo,
  $telefonoAreaCompra = $mysqli->real_escape_string(trim($_POST["telefonoAreaCompra"]));//ComprasTelefono,
  $correoAreaCompra = $mysqli->real_escape_string(trim($_POST["correoAreaCompra"]));//ComprasCorreo,
  $nombreAreaFTP = $mysqli->real_escape_string(trim($_POST["nombreAreaFTP"]));//FinancieraNombre,
  $cargoAreaFTP = $mysqli->real_escape_string(trim($_POST["cargoAreaFTP"]));//FinancieraCargo,
  $telefonoAreaFTP = $mysqli->real_escape_string(trim($_POST["telefonoAreaFTP"]));//FinancieraTelefono,
  $correoAreaFTP = $mysqli->real_escape_string(trim($_POST["correoAreaFTP"]));//FinancieraCorreo,
  $ciudadAreaFTP = $mysqli->real_escape_string(trim($_POST["ciudadAreaFTP"]));//FinancieraCiudad,
  $infoTributariaTipo = $mysqli->real_escape_string(trim($_POST["infoTributariaTipo"]));//TipoContribuyente,
  $infoTributariaCiudad = $mysqli->real_escape_string(trim($_POST["infoTributariaCiudad"]));//CiudadICA,
  $infoTributariaFechaMaxRecepFact = $mysqli->real_escape_string(trim($_POST["infoTributariaFechaMaxRecepFact"]));//Fechafacturas,
  $infoTributariaCorreo = $mysqli->real_escape_string(trim($_POST["infoTributariaCorreo"]));//Correofacturacion,
  $infoAccionariaNombre = $mysqli->real_escape_string(trim($_POST["infoAccionariaNombre"]));//AccionariaNombre,
  $infoAccionariaCCoNIT = $mysqli->real_escape_string(trim($_POST["infoAccionariaCCoNIT"]));//AccionariaCC,
  $infoAccionariaDireccion = $mysqli->real_escape_string(trim($_POST["infoAccionariaDireccion"]));//AccionariaDireccion,
  $infoAccionariaTelefono = $mysqli->real_escape_string(trim($_POST["infoAccionariaTelefono"]));//AccionariaTelefono,
  $infoAccionariaCiudad = $mysqli->real_escape_string(trim($_POST["infoAccionariaCiudad"]));//AccionariaCiudad,
  $infoAccionariaPorcenAcc = $mysqli->real_escape_string(trim($_POST["infoAccionariaPorcenAcc"]));//Accionariaacc,
  $bancosNombre = $mysqli->real_escape_string(trim($_POST["bancosNombre"]));//Banco,
  $bancosCuenta = $mysqli->real_escape_string(trim($_POST["bancosCuenta"]));//BancoCuenta,
  $bancosSucursal = $mysqli->real_escape_string(trim($_POST["bancosSucursal"]));//BancoSucursal,
  $bancosTelefono = $mysqli->real_escape_string(trim($_POST["bancosTelefono"]));//BancoTelefono,
  $bancosCiudad = $mysqli->real_escape_string(trim($_POST["bancosCiudad"]));//BancoCiudad,
  $proveedoresRazonSocial = $mysqli->real_escape_string(trim($_POST["proveedoresRazonSocial"]));//ProveedoresRazonsocial,
  $proveedoresDireccion = $mysqli->real_escape_string(trim($_POST["proveedoresDireccion"]));//ProveedoresDireccion,
  $proveedoresTelefono = $mysqli->real_escape_string(trim($_POST["proveedoresTelefono"]));//ProveedoresTelefonos,
  $proveedoresCiudad = $mysqli->real_escape_string(trim($_POST["proveedoresCiudad"]));//ProveedoresCiudad,
  $clientesRazonSocial = $mysqli->real_escape_string(trim($_POST["clientesRazonSocial"]));//ClientesRazonsocial,
  $clientesDireccion = $mysqli->real_escape_string(trim($_POST["clientesDireccion"]));//ClientesDireccion,
  $clientesTelefono = $mysqli->real_escape_string(trim($_POST["clientesTelefono"]));//ClientesTelefonos,
  $clientesCiudad = $mysqli->real_escape_string(trim($_POST["clientesCiudad"]));//ClientesCiudad,
  $cupoCreditoSolicitado = $mysqli->real_escape_string(trim($_POST["cupoCreditoSolicitado"]));//Cupocredito,
  $cupoCreditoPlazo = $mysqli->real_escape_string(trim($_POST["cupoCreditoPlazo"]));//PlazoPago,
  $cupoCreditoObservaciones = $mysqli->real_escape_string(trim($_POST["cupoCreditoObservaciones"]));//CupocreditoObservaciones,
  $archivoCertificadoExisRepreLegal = $archivos["archivoCertificadoExisRepreLegal"];//soporte1,
  $archivoCedulaRepresentante = $archivos["archivoCedulaRepresentante"];//soporte2,
  $archivoEstadoFinanciero = $archivos["archivoEstadoFinanciero"];//soporte3,
  $archivoRut = $archivos["archivoRut"];//soporte4,
  $archivoDeclaracionRenta = $archivos["archivoDeclaracionRenta"];//soporte5,
  $setSoportes = "";
  $setSoportes .= $archivos["archivoCertificadoExisRepreLegal"]!="" ? " , soporte1='$archivoCertificadoExisRepreLegal' " : " ";
  $setSoportes .= $archivos["archivoCedulaRepresentante"]!="" ? " , soporte2='$archivoCedulaRepresentante' " : " ";
  $setSoportes .= $archivos["archivoEstadoFinanciero"]!="" ? " , soporte3='$archivoEstadoFinanciero' " : " ";
  $setSoportes .= $archivos["archivoRut"]!="" ? " , soporte4='$archivoRut' " : " ";
  $setSoportes .= $archivos["archivoDeclaracionRenta"]!="" ? " ,soporte5='$archivoDeclaracionRenta' " : " ";


  $update = "UPDATE u682444666_stock.clientes SET nombre='$razonSocial', NIT='$nit', Direccion='$direccion', Ciudad='$ciudad', Telefono='$telefono', 
    Direccioncorrespondencia='$direccionCorrespondencia', Actividadeconomica='$actividadEconomica', 
    Representantelegal='$representanteLegal', Cargo='$cargo', EscrituraNo='$escrConsNo', EscrituraFecha='$escrConstFecha', EscrituraNotaria='$escrConsNotaria', EscrituraCiudad='$escrConsCiudad', RegistroCamaradeComercio='$registroCamaraComercio', CamaraFecha='$fechaRegistroCamaraComercio', ComprasNombre='$nombreAreaCompra', ComprasCargo='$cargoAreaCompra', 
    ComprasTelefono='$telefonoAreaCompra', ComprasCorreo='$correoAreaCompra', FinancieraNombre='$nombreAreaFTP', FinancieraCargo='$cargoAreaFTP', FinancieraTelefono='$telefonoAreaFTP', 
    FinancieraCorreo='$correoAreaFTP', FinancieraCiudad='$ciudadAreaFTP', TipoContribuyente='$infoTributariaTipo',  CiudadICA='$infoTributariaCiudad', 
    Fechafacturas='$infoTributariaFechaMaxRecepFact', Correofacturacion='$infoTributariaCorreo', AccionariaNombre='$infoAccionariaNombre', AccionariaCC='$infoAccionariaCCoNIT', 
    AccionariaDireccion='$infoAccionariaDireccion', AccionariaTelefono='$infoAccionariaTelefono', AccionariaCiudad='$infoAccionariaCiudad', 
    Accionariaacc='$infoAccionariaPorcenAcc', Banco='$bancosNombre', BancoCuenta='$bancosCuenta', BancoSucursal='$bancosSucursal', BancoTelefono='$bancosTelefono', 
    BancoCiudad='$bancosCiudad', ProveedoresRazonsocial='$proveedoresRazonSocial', ProveedoresDireccion='$proveedoresDireccion', 
    ProveedoresTelefonos='$proveedoresTelefono', ProveedoresCiudad='$proveedoresCiudad', 
    ClientesRazonsocial='$clientesRazonSocial', ClientesDireccion='$clientesDireccion', 
    ClientesTelefonos='$clientesTelefono', ClientesCiudad='$clientesCiudad', Cupocredito='$cupoCreditoSolicitado', PlazoPago='$cupoCreditoPlazo', 
    CupocreditoObservaciones='$cupoCreditoObservaciones' $setSoportes where cons='$consecutivoEmpresa'; ";
  if($mysqli->query($update)){
    echo "OK";
  }else{
    echo "Error: $update";
    echo var_dump($archivos);
  }


}

mysqli_close($mysqli);
?>