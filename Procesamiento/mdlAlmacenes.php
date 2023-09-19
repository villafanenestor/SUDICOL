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
 

 $consulta=" INSERT almacenes(nombre,observaciones,estado,encargado) VALUE (
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."',
 '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
 '".mysqli_real_escape_string($mysqli,($_POST["Estado"]))."',
 '".mysqli_real_escape_string($mysqli,($_POST["Encargado"]))."') ";  

      if( $datos=mysqli_query($mysqli,$consulta) ){
        echo 'OK';
      }else{
        echo 'No se ha podido ingresar, verifique los datos';
      }
 

}

function Actualizar($mysqli){


    $consulta=" UPDATE almacenes SET 
    nombre='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."',
    observaciones='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
    estado='".mysqli_real_escape_string($mysqli,($_POST["Estado"]))."',
    encargado='".mysqli_real_escape_string($mysqli,($_POST["Encargado"]))."'
    WHERE cons='".mysqli_real_escape_string($mysqli,$_POST["codigoc"])."' "; 
    if( $datos=mysqli_query($mysqli,$consulta)){
      echo 'OK';
    }else{
      echo 'No se ha podido Actualizar, verifique los datos';
    }
     
}
  

function buscar($mysqli){

  $arreglo= array();
  $consulta=" SELECT cons,nombre,estado,observaciones,encargado FROM almacenes WHERE 
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
 
       
          $consulta = " SELECT cons,nombre,observaciones,estado,encargado  FROM almacenes   ORDER BY nombre ";    
          $datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>ID</th>
          <th>ALMACEN</th>
          <th>OBSERVACIONES</th>
          <th>ESTADO</th>
          <th>ENCARGADO</th>
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr> 
          <th>ID</th>
          <th>ALMACEN</th>
          <th>OBSERVACIONES</th>
          <th>ESTADO</th>
          <th>ENCARGADO</th>
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