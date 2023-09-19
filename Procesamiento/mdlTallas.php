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
  }else if(isset($_POST["buscatallas"])){
    buscatallas($mysqli);
  }else if(isset($_POST["IngresarDetalle"])){
    IngresarDetalle($mysqli);
  }else if(isset($_POST["ListarDetalle"])){
    ListarDetalle($mysqli);
  }else if(isset($_POST["Buscar_DatosDetalle"])){
    Buscar_DatosDetalle($mysqli);
  }else if(isset($_POST["ActualizarDetalle"])){
    ActualizarDetalle($mysqli);
  }else if(isset($_POST["buscatallasxProducto"])){
    buscatallasxProducto($mysqli);
  }
			
function buscatallasxProducto($mysqli){
        $consulta="SELECT d.cons,d.nombre FROM (elementos e INNER JOIN  tallasdetalle d ON d.`codtalla`=e.`codTipoTalla`) WHERE
        e.cons= '".mysqli_real_escape_string($mysqli,strtoupper($_POST["buscatallasxProducto"]))."'";   
                      $datos=mysqli_query($mysqli,$consulta);
                      echo ' 
                         <option  selected="selected" value="">Seleccionar...</option>';           
                      while($row=mysqli_fetch_row($datos)){                               
                          echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
                      } 
}


function ActualizarDetalle($mysqli){


    $consulta=" UPDATE tallasdetalle SET 
    nombre='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."',
    codtalla='".mysqli_real_escape_string($mysqli,strtoupper($_POST["TipoTalla"]))."'
    WHERE cons='".mysqli_real_escape_string($mysqli,$_POST["codigoc"])."' "; 
    if( $datos=mysqli_query($mysqli,$consulta)){
      echo 'OK';
    }else{
      echo 'No se ha podido Actualizar, verifique los datos';
    }
     
}
  



function Buscar_DatosDetalle($mysqli){

  $arreglo= array();
  $consulta=" SELECT cons,nombre,codtalla FROM tallasdetalle WHERE 
  cons='".mysqli_real_escape_string($mysqli,$_POST["Buscar_DatosDetalle"])."' "; 

  $datos=mysqli_query($mysqli,$consulta);
  if(mysqli_num_rows($datos)>0){
  $row=mysqli_fetch_row($datos); 
        $arreglo[]=$row;
        echo json_encode($arreglo);    
  }else{  
    echo 'n'; 
  }

}
function IngresarDetalle($mysqli){

$consulta2=" SELECT  nombre FROM tallasdetalle WHERE 
nombre='".mysqli_real_escape_string($mysqli,$_POST["Nombre"])."' and 
codtalla='".mysqli_real_escape_string($mysqli,$_POST["TipoTalla"])."' ";           
 $datos2=mysqli_query($mysqli,$consulta2);
  if(mysqli_num_rows($datos2)>0){
    echo 'Ya fue ingresado';    
  }else{ 

 $consulta=" INSERT tallasdetalle(nombre,codtalla) VALUE ('".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."','".mysqli_real_escape_string($mysqli,strtoupper($_POST["TipoTalla"]))."') ";  

      if( $datos=mysqli_query($mysqli,$consulta) ){
        echo 'OK';
      }else{
        echo 'No se ha podido ingresar, verifique los datos';
      }
 }

}
function buscatallas($mysqli){
        $consulta="SELECT cons, nombre FROM tallas  ORDER BY nombre ";   
                      $datos=mysqli_query($mysqli,$consulta);
                      echo ' 
                         <option  selected="selected" value="">Seleccionar...</option>';           
                      while($row=mysqli_fetch_row($datos)){                               
                          echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
                      } 
}

function Ingresar($mysqli){

$consulta2=" SELECT  nombre FROM tallas WHERE 
nombre='".mysqli_real_escape_string($mysqli,$_POST["Nombre"])."' ";           
 $datos2=mysqli_query($mysqli,$consulta2);
  if(mysqli_num_rows($datos2)>0){
    echo 'Ya fue ingresado';    
  }else{ 

 $consulta=" INSERT tallas(nombre) VALUE ('".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."') ";  

      if( $datos=mysqli_query($mysqli,$consulta) ){
        echo 'OK';
      }else{
        echo 'No se ha podido ingresar, verifique los datos';
      }
 }

}

function Actualizar($mysqli){


    $consulta=" UPDATE tallas SET 
    nombre='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Nombre"]))."'
    WHERE cons='".mysqli_real_escape_string($mysqli,$_POST["codigoc"])."' "; 
    if( $datos=mysqli_query($mysqli,$consulta)){
      echo 'OK';
    }else{
      echo 'No se ha podido Actualizar, verifique los datos';
    }
     
}
  

function buscar($mysqli){

  $arreglo= array();
  $consulta=" SELECT cons,nombre FROM tallas WHERE 
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
 
       
          $consulta = " SELECT cons, nombre FROM tallas   ORDER BY nombre ";   
 
  
$datos=mysqli_query($mysqli,$consulta);
// <th >Empresa</th>
          $tabla='<h3 class="title1">TIPO TALLAS</h3><table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>
          
          <th>ID</th>
          <th>TIPO TALLA</th>
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
         
          <th>ID</th>
          <th>TIPO TALLA</th>
          <th></th></tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd" >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"  ><button type="button" class="btn btn-success btn-circle" onclick="Buscar_Datos('.$row[0].');"><i class="fa fa-edit"></i></button></td>
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
}
function ListarDetalle($mysqli){  
 
       
          $consulta = " SELECT d.cons, t.nombre, d.nombre FROM tallas t inner join tallasdetalle d on t.cons=d.codtalla   ORDER BY t.nombre, d.nombre ";   
 
  
$datos=mysqli_query($mysqli,$consulta);
 
          $tabla='<h3 class="title1">DETALLE TALLAS</h3><table id="exampleDetalle" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>
          
          <th>ID</th>
          <th>TIPO TALLA</th>
          <th>DETALLE TALLA</th>
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
         
          <th>ID</th>
          <th>TIPO TALLA</th>
          <th>DETALLE TALLA</th>
          <th></th></tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd" >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"  >'.$row[2].'</td>
            <td class="sobretd"  ><button type="button" class="btn btn-success btn-circle" onclick="Buscar_DatosDetalle('.$row[0].');"><i class="fa fa-edit"></i></button></td>
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

mysqli_close($mysqli);
?>