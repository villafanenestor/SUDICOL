<?php 
require_once("Conexion.php");

  if( isset($_POST["Ingresar"]) ){
		Ingresar($mysqli);							
	}else if(isset($_POST["Listar"])){
    Listar($mysqli);
  }else if(isset($_POST["Buscar_Datos"])){
    buscar($mysqli);
  }else if(isset($_POST["Actualizar"])){
    Actualizar($mysqli);
  }
			

function Ingresar($mysqli){
 

 $consulta=" INSERT proveedores(
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


    $consulta=" UPDATE proveedores SET 
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
             `fechaingreso` FROM proveedores WHERE 
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
 
       
          $consulta = " SELECT cons,nombre,nit,contacto,estado FROM proveedores   ORDER BY nombre ";    
          $datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>ID</th>
          <th>PROVEEDORES</th>
          <th>NIT</th>
          <th>CONTACTO</th> 
          <th>ESTADO</th>
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr> 
          <th>ID</th>
          <th>PROVEEDORES</th>
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

mysqli_close($mysqli);
?>