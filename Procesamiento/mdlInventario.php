<?php 
require_once("ConexionStock.php");

  if( isset($_POST["buscaElementosAlmacen"]) ){
		buscaElementosAlmacen($mysqli);							
	}else if(isset($_POST["buscaTallaxElementosAlmacen"])){
    buscaTallaxElementosAlmacen($mysqli);
  }else if(isset($_POST["IniciarAsignacion"])){
    IniciarAsignacion($mysqli);
  }else if(isset($_POST["AsignacionhistoricoGeneral"])){
    AsignacionhistoricoGeneral($mysqli);
  }else if(isset($_POST["AsignacionElementos"])){
    AsignacionElementos($mysqli);
  }else if(isset($_POST["buscaCantidadXTallaxElementosAlmacen"])){
    buscaCantidadXTallaxElementosAlmacen($mysqli);
  }else if(isset($_POST["AsignacionhistoricoActual"])){
    AsignacionhistoricoActual($mysqli);
  }else if(isset($_POST["FinalizaEntrega"])){
    FinalizaEntrega($mysqli);
  }else if(isset($_POST["ReporteEntregas"])){
    ReporteEntregas($mysqli);
  } else if(isset($_POST["ReporteInventario"])){
    ReporteInventario($mysqli);
  } else if(isset($_POST["CrearRotacion"])){
    CrearRotacion($mysqli);
  }  else if(isset($_POST["listadoenvios"])){
    listadoenvios($mysqli);
  } else if(isset($_POST["GenerarReporteAU"])){
    GenerarReporteAU($mysqli);
  } else if(isset($_POST["gestionarRotacion"])){
    gestionarRotacion($mysqli);
  } else if(isset($_POST["guardaGestionarRotacion"])){
    guardaGestionarRotacion($mysqli);
  } else if(isset($_POST["GenerarReporteRE"])){
    GenerarReporteRE($mysqli);
  } else if(isset($_POST["buscaelementosEmpleado"])){
    buscaelementosEmpleado($mysqli);
  }  else if(isset($_POST["buscaTallaxelementosEmpleado"])){
    buscaTallaxelementosEmpleado($mysqli);
  } else if(isset($_POST["buscaEntregadasxTallaxelementosEmpleado"])){
    buscaEntregadasxTallaxelementosEmpleado($mysqli);
  }else if(isset($_POST["buscaAsignacionesEmpleado"])){
    buscaAsignacionesEmpleado($mysqli);
  } else if(isset($_POST["AsignacionhistoricoActualxdevolucion"])){
    AsignacionhistoricoActualxdevolucion($mysqli);
  } else if(isset($_POST["DevolverElemento"])){
    DevolverElemento($mysqli);
  }  else if(isset($_POST["DevolucionesActual"])){
    DevolucionesActual($mysqli);
  }  else if(isset($_POST["generarReporteDevoluciones"])){
    generarReporteDevoluciones($mysqli);
  }   else if(isset($_POST["IniciarAuditoria"])){
    IniciarAuditoria($mysqli);
  } else if(isset($_POST["revisarauditoriaactiva"])){
    revisarauditoriaactiva($mysqli);
  } else if(isset($_POST["AuditoriashistoricoActual"])){
    AuditoriashistoricoActual($mysqli);
  } else if(isset($_POST["AuditoriaElemento"])){
    AuditoriaElemento($mysqli);
  } else if(isset($_POST["FinalizarAuditoria"])){
    FinalizarAuditoria($mysqli);
  } else if(isset($_POST["BuscarReporteAuditorias"])){
    BuscarReporteAuditorias($mysqli);
  } else if(isset($_POST["HistorialInventario"])){
    HistorialInventario($mysqli);
  } 
 

function HistorialInventario($mysqli){  
 
 
          $consulta = "SELECT codalmacen,codelemento,codtalla FROM `almacenesinventario` WHERE 
          cons='".mysqli_real_escape_string($mysqli,$_POST["HistorialInventario"])."'";   
$codalmacen="";
$codelemento="";
$codtalla="";
$datos=mysqli_query($mysqli,$consulta);   
if($row=mysqli_fetch_row($datos)){ 
    $codalmacen=$row[0];
    $codelemento=$row[1];
    $codtalla=$row[2]; 
}
 

          $consulta = "SELECT fecha,origen, destino,cantidad, cantidadanterior,usuario FROM `almacenesinventariomovimientos` 
WHERE codalmacen='".mysqli_real_escape_string($mysqli,$codalmacen)."' 
AND codelemento ='".mysqli_real_escape_string($mysqli,$codelemento)."' 
AND codtalla ='".mysqli_real_escape_string($mysqli,$codtalla)."' 
ORDER BY cons DESC  ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoHistorial" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>  
          <th width="50">cons</th>
          <th  >FECHA</th> 
          <th  >ORIGEN</th> 
          <th  >DESTINO</th>   
          <th width="50">TRANSACCION</th> 
          <th width="50">CANTIDAD ANTERIOR</th> 
          <th  >USUARIO</th>  
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">cons</th>
          <th  >FECHA</th> 
          <th  >ORIGEN</th> 
          <th  >DESTINO</th>   
          <th width="50">TRANSACCION</th> 
          <th width="50">CANTIDAD ANTERIOR</th> 
          <th  >USUARIO</th>  
          </tr>
        </tfoot>  <tbody> ';

$cons=1;          
while($row=mysqli_fetch_row($datos)){ 
 
  
              $tabla.='<tr > 
            <td class="sobretd"   >'.$cons.'</td>
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td>   
          </tr>';
              $cons++;
            } 

          $tabla.='</tbody></table>';

          echo $tabla;
} 

function BuscarReporteAuditorias($mysqli){  
 
     $completa="";
     if ($_POST["Filtro"]!="TODAS") { 
     $completa=" and usuario='".mysqli_real_escape_string($mysqli,($_POST["Filtro"]))."' ";
     }

           $consulta = "
 SELECT cons,fechainicio,finafinal,Observaciones,estado,usuario FROM `inventarioauditoria` WHERE  
  fechainicio>='".mysqli_real_escape_string($mysqli,($_POST["Desde"]))."' and 
  finafinal<='".mysqli_real_escape_string($mysqli,($_POST["Hasta"]))."'  
  ".$completa."   ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleReporteAuditorias" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA INICIO</th> 
          <th>FECHA FINAL</th> 
          <th>OBSERVACIONES</th> 
          <th width="50">ESTADO</th>
          <th width="50">USUARIO</th> 
          <th width="10"></th>    
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA INICIO</th> 
          <th>FECHA FINAL</th> 
          <th>OBSERVACIONES</th> 
          <th width="50">ESTADO</th>
          <th width="50">USUARIO</th> 
          <th width="10"></th> 
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
        $CODD="'".$row[0]."'";
              $tabla.='<tr >  
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td> 
            <td class="sobretd"  >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td>  
            <td class="sobretd"   >
        <button type="button" class="btn btn-info btn-circle" style="background-color:#E64A19;padding:7px !important" onclick="impresionformatoAuditoria('.$CODD.');"><i class="fa fa-file-text-o"></i></button></td>  
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

  
function FinalizarAuditoria($mysqli){  

 
            
                $consulta=" UPDATE  inventarioauditoria SET  
                observaciones='".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
                estado='Cerrada', finafinal=curdate()
                where cons= '".mysqli_real_escape_string($mysqli,($_POST["Codigo"]))."' ";    

              if( $datos=mysqli_query($mysqli,$consulta) ){ 
                        echo 'OK';
                
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
       
 

}


function AuditoriaElemento($mysqli){  



   $ruta="gs://lumensarchivostemporales/Stock/Auditoria/";
   //$ruta="./";

  
                $swx=0;
                $nombre ="";
                foreach ($_FILES as $key) {
                  if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
                   $valorext=explode(".", $key['name']); 
                $nombre = time().".".$valorext[count($valorext)-1];
                $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
                move_uploaded_file($temporal, $ruta.$nombre); //Movemos el archivo temporal a la ruta especificada  
                  if(file_exists($ruta.$nombre)){
                    $swx=1; 
                     }  
                  } 
  
                } 


  if ($swx==1)
  { 


         
          $consulta=" SELECT  a.cons  FROM almacenesinventario a  
          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' and 
          a.codelemento='".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."'and 
          a.codtalla='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."'  ";     
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
            
                $consulta=" INSERT inventarioauditoriadetalle( 
             `codinventarioauditoriaauditoria`,
             `codalmaceninventario`,
             `fecha`,
             `revisados`,
             `encontrados`,
             `soporte`,
             `observaciones`,
             `resultado`) VALUE ( 
            '".mysqli_real_escape_string($mysqli,($_POST["Codigob"]))."',
            '".mysqli_real_escape_string($mysqli,($row[0]))."',CURDATE(),
            '".mysqli_real_escape_string($mysqli,($_POST["Disponibles"]))."',
            '".mysqli_real_escape_string($mysqli,($_POST["Encontrados"]))."',
            '".mysqli_real_escape_string($mysqli,($nombre))."',
            '".mysqli_real_escape_string($mysqli,strtoupper($_POST["ObservacionesAUD"]))."',
            '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Resultado"]))."' ) ";    

              if( $datos=mysqli_query($mysqli,$consulta) ){ 
                        echo 'OK';
                
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
          }else{
                echo 'No se encontró codigo inventario';
           }

   }else{
    echo 'No se pudo subir el archivo';
  }

}







function AuditoriashistoricoActual($mysqli){  
 
     

          $consulta = "SELECT id.cons,id.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,id.`revisados`,id.`encontrados`,id.`soporte`,id.`resultado`
FROM  ((((( `inventarioauditoria` i INNER JOIN `inventarioauditoriadetalle` id ON i.cons=id.`codinventarioauditoriaauditoria`)
INNER JOIN almacenesinventario l ON id.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)    where i.cons='".mysqli_real_escape_string($mysqli,($_POST["Codigo"]))."'
 ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoDevoluciones" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">REVISADOS</th>
          <th width="50">ENCONTRADOS</th>   
          <th width="50">SOPORTE</th>
          <th width="50">RESULTADO</th>  
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">REVISADOS</th>
          <th width="50">ENCONTRADOS</th>   
          <th width="50">SOPORTE</th>
          <th width="50">RESULTADO</th>  
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td> 
            <td class="sobretd"  >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td>  
            <td class="sobretd"   ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Auditoria/'.$row[7].'" target="_blank">Descargar</a></td> 
            <td class="sobretd"   >'.$row[8].'</td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

function revisarauditoriaactiva($mysqli){  

 
                                  $consulta="SELECT cons,fechainicio,finafinal,observaciones FROM
                                         inventarioauditoria  where usuario='".mysqli_real_escape_string($mysqli,($_POST["Usuario"]))."' and estado='Abierta'
                                          order by cons desc limit 1  ";     
                                        $datos=mysqli_query($mysqli,$consulta);         
                                        if($row=mysqli_fetch_row($datos)){    
                                                $arreglo[]=$row;
                                                echo json_encode($arreglo);    
                                        }else{
                                          echo"n";
                                        }
         
       
}
function IniciarAuditoria($mysqli){  


  
            
                $consulta=" INSERT inventarioauditoria(fechainicio,finafinal,usuario,observaciones) VALUE
                            ( CURDATE() ,CURDATE() , 
                            '".mysqli_real_escape_string($mysqli,($_POST["Usuario"]))."', 
                            '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."'  ) ";     
              if( $datos=mysqli_query($mysqli,$consulta) ){ 

                                  $consulta="SELECT cons,fechainicio,finafinal,observaciones FROM
                                         inventarioauditoria order by cons desc limit 1  ";     
                                        $datos=mysqli_query($mysqli,$consulta);         
                                        if($row=mysqli_fetch_row($datos)){    
                                                $arreglo[]=$row;
                                                echo json_encode($arreglo);    
                                        } 
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
       
}
function generarReporteDevoluciones($mysqli){  
 
       
        $completa="";
       if ($_POST["Proyecto"]!="TODOS") { 
        $completa.="  AND em.`codProyecto`='".mysqli_real_escape_string($mysqli,$_POST["Proyecto"])."' ";
       } 
       if ($_POST["Empleados"]!="TODOS") { 
        $completa.="  AND em.cedula='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."' ";
       }   

          $consulta = "SELECT d.cons,d.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,d.`cantidad` ,em.`Nombre`,d.`observaciones`,d.`estado`,d.`soporte`
FROM (((((((`asignacionesdetalle` a INNER JOIN devoluciones d ON d.`codasignacionesdetalle`=a.cons )
INNER JOIN almacenesinventario l ON d.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)  INNER JOIN  asignaciones ai ON ai.cons=a.`codasignacion`)
INNER JOIN empleados em ON em.cedula=ai.`codempleado`) 
WHERE d.`fecha`>='".mysqli_real_escape_string($mysqli,$_POST["Desde"])."' AND
d.`fecha`<='".mysqli_real_escape_string($mysqli,$_POST["Hasta"])."'
".$completa."
   order by d.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoDevoluciones" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>  
          <th>EMPLEADO</th>
          <th width="50">ESTADO</th> 
          <th width="50">SOPORTE</th> 
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>  
          <th>EMPLEADO</th>
          <th width="50">ESTADO</th> 
          <th width="50">SOPORTE</th> 
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td> 
            <td class="sobretd"  >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td>  
            <td class="sobretd"   >'.$row[8].'</td> 
            <td class="sobretd"   ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Devolucion/'.$row[9].'" target="_blank">Descargar</a></td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 



function DevolucionesActual($mysqli){  
 
       
          $consulta = "SELECT d.cons,d.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,d.`cantidad` ,d.`usuario`,d.`observaciones`,d.`estado`,d.`soporte`
FROM (((((`asignacionesdetalle` a INNER JOIN devoluciones d ON d.`codasignacionesdetalle`=a.cons )
INNER JOIN almacenesinventario l ON d.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)   
WHERE a.`codasignacion` ='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' order by d.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoDevoluciones" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          <th width="50">ESTADO</th> 
          <th width="50">SOPORTE</th> 
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          <th width="50">ESTADO</th> 
          <th width="50">SOPORTE</th> 
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td> 
            <td class="sobretd"  >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
            <td class="sobretd"   >'.$row[7].'</td> 
            <td class="sobretd"   >'.$row[8].'</td> 
            <td class="sobretd"   ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Devolucion/'.$row[9].'" target="_blank">Descargar</a></td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 


function DevolverElemento($mysqli){  



   $ruta="gs://lumensarchivostemporales/Stock/Devolucion/";
  // $ruta="./";

  
                $swx=0;
                $nombre ="";
                foreach ($_FILES as $key) {
                  if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
                   $valorext=explode(".", $key['name']); 
                $nombre = time().".".$valorext[count($valorext)-1];
                $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
                move_uploaded_file($temporal, $ruta.$nombre); //Movemos el archivo temporal a la ruta especificada  
                  if(file_exists($ruta.$nombre)){
                    $swx=1; 
                     }  
                  } 
  
                } 


  if ($swx==1)
  { 

$swr=0; 

                          $c1=" SELECT  cons  FROM almacenesinventario WHERE 
                          codelemento ='".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."' and 
                          codtalla ='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."' and 
                          codalmacen ='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."'    ";     
                          $d1=mysqli_query($mysqli,$c1);         
                          if(!$r1=mysqli_fetch_row($d1)){     
                                  $c1="INSERT almacenesinventario(codelemento,codtalla,codalmacen,cantidad) VALUES(
                                  '".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."',
                                  '".mysqli_real_escape_string($mysqli,$_POST["Talla"])."',
                                  '".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."','0')  ";     
                                  $d1=mysqli_query($mysqli,$c1);    
                          } 


                          $c1=" SELECT  cons,cantidad  FROM almacenesinventario WHERE 
                          codelemento ='".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."' and 
                          codtalla ='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."' and 
                          codalmacen ='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."'    ";     
                          $d1=mysqli_query($mysqli,$c1);         
                          if($r1=mysqli_fetch_row($d1)){   


                                    $c1="UPDATE  almacenesinventario SET
                                    cantidad=cantidad+".$_POST["Cantidad"]."
                                    WHERE  cons ='".mysqli_real_escape_string($mysqli,$r1[0])."'   ";     
                                    if ($d1=mysqli_query($mysqli,$c1) ) { 

                                          $c1=" INSERT  devoluciones( `codasignacionesdetalle`,`codalmacen`,  `estado`,`cantidad`,
                                           `soporte`,`observaciones`,`usuario`,`fecha`,codalmaceninventario)
                                          VALUES ( 
                                          '".mysqli_real_escape_string($mysqli,$_POST["codasignacionesdetalle"])."' ,
                                          '".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' ,
                                          '".mysqli_real_escape_string($mysqli,$_POST["Estado"])."' ,
                                          '".mysqli_real_escape_string($mysqli,$_POST["Cantidad"])."' ,
                                          '".mysqli_real_escape_string($mysqli,$nombre)."' , 
                                          '".mysqli_real_escape_string($mysqli,$_POST["Observaciones"])."' ,
                                          '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."' ,CURDATE(),
                                          '".mysqli_real_escape_string($mysqli,$r1[0])."' ); ";     
                                          if ($d1=mysqli_query($mysqli,$c1) ) { 

                                                $c1="UPDATE  asignacionesdetalle SET
                                                devueltos=devueltos+".$_POST["Cantidad"]."
                                                WHERE  cons ='".mysqli_real_escape_string($mysqli,$_POST["codasignacionesdetalle"])."'   ";     
                                                if ($d1=mysqli_query($mysqli,$c1)) { 


                                                     $c2=" SELECT  max(cons)  FROM devoluciones WHERE  
                                                    usuario ='".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."'    ";     
                                                    $d2=mysqli_query($mysqli,$c2);         
                                                    if($r2=mysqli_fetch_row($d2)){   

                                                       $consulta=" INSERT almacenesinventariomovimientos(codalmacen,codelemento,codtalla,cantidad,cantidadanterior,codorigen,origen,destino,fecha,usuario) 
                                                                  VALUES('".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."', 
                                                                  '".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."',
                                                                  '".mysqli_real_escape_string($mysqli,$_POST["Talla"])."', 
                                                                  '".mysqli_real_escape_string($mysqli,($r1[1]+$_POST["Cantidad"]))."' , 
                                                                  '".mysqli_real_escape_string($mysqli,$r1[1])."', 
                                                                  '".mysqli_real_escape_string($mysqli,$r2[0])."' ,
                                                                  'DEVOLUCION',
                                                                  'ALMACEN ENTRADA',curdate(),
                                                                   '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."'   ) ";   
                                                                if ($datos=mysqli_query($mysqli,$consulta) ) { 
                                                                                      echo "OK";   
                                                                }else{ 
                                                                      echo 'No se pudo guardar movimiento origen'; 
                                                                } 
                                                      }else{ 
                                                            echo 'No se encontro codigo devolucion'; 
                                                      } 


                                                }else{
                                                 echo 'no se aumento devueltos en detalle de asignacion'; 
                                                }  
 
                                          }else{
                                           echo 'no se guardo devolucion inventario'; 
                                          }  

                                    }else{
                                     echo 'no se actualizo inventario'; 
                                    }  
                          }else{
                          echo 'no se encontro almacen inventario destino'; 
                          }

 

   }else{
    echo 'No se pudo subir el archivo';
  }

}



function AsignacionhistoricoActualxdevolucion($mysqli){  
 
       
          $consulta = " SELECT a.cons,a.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,a.`cantidad`,a.`devueltos`,a.`usuario`,a.`observaciones` FROM ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)INNER JOIN almacenes al ON al.cons=l.codalmacen) WHERE 
          a.codasignacion='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' order by a.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoEntregas" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>
          <th width="50">DEVUELTOS</th> 
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA</th> 
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>
          <th width="50">DEVUELTOS</th> 
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
            <td class="sobretd"   >'.$row[7].'</td> 
            <td class="sobretd"   >'.$row[8].'</td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

function buscaAsignacionesEmpleado($mysqli){  
 
 
          $consulta = " SELECT a.fechainicioEnt,a.fechafinalEnt,a.cons,p.`nombre`,e.`Nombre`,a.estado,a.cantidad,a.soporte,TipoEntrega
FROM ((asignaciones a INNER JOIN `empleados` e ON e.`Cedula`=a.codempleado) INNER JOIN proyectos p ON p.cons=e.`codProyecto`) WHERE 
codempleado='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."' and a.estado='Cerrado'   order by cons desc";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="70">FECHA INICIO</th>
          <th width="70">FECHA FINAL</th>
          <th width="50">CODIGO</th>
          <th width="50">TIPO</th>
          <th>PROYECTO</th>
          <th>EMPLEADO</th>
          <th  width="50">ESTADO</th>
          <th width="50">CANTIDAD</th>
          <th width="50">SOPORTE</th> 
          <th width="20"></th> 
          </tr>
        </thead><tfoot>
          <tr>
          <th>FECHA INICIO</th>
          <th>FECHA FINAL</th>
          <th width="50">CODIGO</th>
          <th width="50">TIPO</th>
          <th>PROYECTO</th>
          <th>EMPLEADO</th>
          <th  width="50">ESTADO</th>
          <th width="50">CANTIDAD</th>
          <th width="50">SOPORTE</th> 
          <th width="20"></th> 
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  $enlace="";
  if ($row[5]=="Cerrado") {
   $enlace="<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/".$row[7]."' target='_blank'>Descargar</a>";
  }

        $CODD="'".$row[2]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td>
            <td class="sobretd"   >'.$row[8].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
            <td class="sobretd"   >'.$enlace.'</td>  
            <td class="sobretd"   >
         <button type="button" class="btn btn-primary btn-circle" style="padding:7px !important" onclick="DetallarElementosXdevolucion('.$CODD.');"><i class="fa fa-search"></i></button></td>  
        </td>  
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 
function buscaEntregadasxTallaxelementosEmpleado($mysqli){  

          $consulta="SELECT   IFNULL(a.cantidad,0),IFNULL(a.devueltos,0)  FROM
 ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) 
 INNER JOIN elementos e ON e.cons=l.`codelemento`) 
 INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN asignaciones ai   ON ai.cons=a.`codasignacion`) 
WHERE ai.`cons`='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' AND
e.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."'  AND
t.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."'  AND
a.`cons`='".mysqli_real_escape_string($mysqli,$_POST["codasignacion"])."'   ORDER BY t.`Nombre`  ";   


          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){    
                  $arreglo[]=$row;
                  echo json_encode($arreglo);    
          } 

}

function buscaTallaxelementosEmpleado($mysqli){  

           $consulta="SELECT DISTINCT t.cons,t.`Nombre`,a.fecha,a.cons  FROM
 ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) 
 INNER JOIN elementos e ON e.cons=l.`codelemento`) 
 INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN asignaciones ai   ON ai.cons=a.`codasignacion`) 
WHERE ai.`cons`='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' AND
e.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Elementos"])."'   ORDER BY t.`Nombre`  ";   


          $datos=mysqli_query($mysqli,$consulta);
          echo ' <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'-'.$row[3].'">'.$row[1].' -'.$row[2].' ('.$row[3].') </option>';    
          }
          echo ' </select>'; 

}


function buscaelementosEmpleado($mysqli){  

           $consulta="SELECT DISTINCT e.cons,e.`Nombre`  FROM
 ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) 
 INNER JOIN elementos e ON e.cons=l.`codelemento`) 
 INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN asignaciones ai   ON ai.cons=a.`codasignacion`) 
WHERE ai.`cons`='".mysqli_real_escape_string($mysqli,$_POST["codigoAsignacion"])."'  ORDER BY e.`Nombre`  ";   


          $datos=mysqli_query($mysqli,$consulta);
          echo ' <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 

}
 

function GenerarReporteRE($mysqli){  
 
        $completa="";
       if ($_POST["Almacen"]!="Todos") { 
        $completa="  AND a.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' ";
       } 
       if ($_POST["AlmacenDestino"]!="Todos") { 
        $completa.="  AND a2.`cons`='".mysqli_real_escape_string($mysqli,$_POST["AlmacenDestino"])."' ";
       }  
       if ($_POST["Estado"]!="Todos") { 
        $completa.="  AND r.estado='".mysqli_real_escape_string($mysqli,$_POST["Estado"])."' ";
       }  


            $consulta = "
SELECT a.nombre, e.nombre,t.nombre,r.`cantidad`,r.fecha,a2.nombre,r.estado,r.cons
FROM `rotacion` r INNER JOIN almacenes a ON a.cons=r.`codalmacenorigen` 
INNER JOIN almacenesinventario ai ON ai.cons=r.`codinventario`
INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN almacenes a2 ON a2.cons=r.`codalmacendestino` 
WHERE   r.fecha>='".mysqli_real_escape_string($mysqli,$_POST["Desde"])."' and r.fecha<='".mysqli_real_escape_string($mysqli,$_POST["Hasta"])."' 
 ".$completa."  order by r.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>  
          <th width="50">COD</th>
          <th width="50">FECHA</th> 
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          <th  > </th>  
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">COD</th>
          <th width="50">FECHA</th> 
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          <th  > </th>  
          </tr>
        </tfoot>  <tbody> ';

$cons=1;
          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
 
 
        $CODD="'".$row[7]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$row[7].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[5].'</td>  
            <td class="sobretd"   >'.$row[6].'</td>   
            <td class="sobretd"   >
        <button type="button" class="btn btn-primary btn-circle" style="padding:7px !important" onclick="gestionarRotacionRE('.$CODD.');"><i class="fa fa-search"></i></button></td>  
          </tr>';
              $cons++;
            }
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 



function guardaGestionarRotacion($mysqli){  



   $ruta="gs://lumensarchivostemporales/Stock/Rotacion/";
  // $ruta="./";

  
                $swx=0;
                $nombre ="";
                foreach ($_FILES as $key) {
                  if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
                   $valorext=explode(".", $key['name']); 
                $nombre = time().".".$valorext[count($valorext)-1];
                $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
                move_uploaded_file($temporal, $ruta.$nombre); //Movemos el archivo temporal a la ruta especificada  
                  if(file_exists($ruta.$nombre)){
                    $swx=1; 
                     }  
                  } 
  
                } 


  if ($swx==1)
  { 

$swr=0;
         
          $consulta=" SELECT  cantidad,estado,codinventario,codalmacendestino,codalmacenorigen  FROM rotacion WHERE cons ='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."'  ";     
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
            if ($row[1]=="Enviado") { 
 
                      $c1=" SELECT  cantidad,codelemento,codtalla  FROM almacenesinventario WHERE cons ='".mysqli_real_escape_string($mysqli,$row[2])."'  ";     
                      $d1=mysqli_query($mysqli,$c1);         
                      if($r1=mysqli_fetch_row($d1)){     
                            if ($row[0]<=$r1[0]) {

                                    $consulta=" UPDATE rotacion SET
                                     usuarioaprueba='".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."',
                                     soporteaprueba='".mysqli_real_escape_string($mysqli,$nombre)."',
                                     observacionesaprueba='".mysqli_real_escape_string($mysqli,$_POST["Observaciones"])."',
                                     fechaaprueba=curdate(),
                                     estado='".mysqli_real_escape_string($mysqli,$_POST["Estado"])."'
                                     WHERE
                                     cons='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' ";   
                                    if ($datos=mysqli_query($mysqli,$consulta) ) { 
                                    if ($_POST["Estado"]=="Rechazado") { 
                                       echo "OK";
                                     }else{ 
                                            $c2=" SELECT  a.cons,a.cantidad  FROM almacenesinventario a  
                                            WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$row[3])."' and 
                                            a.codelemento='".mysqli_real_escape_string($mysqli,$r1[1])."'and 
                                            a.codtalla='".mysqli_real_escape_string($mysqli,$r1[2])."'  ";   
                                            $d2=mysqli_query($mysqli,$c2);         
                                            if(!$r2=mysqli_fetch_row($d2)){   
                                                 $consulta=" INSERT almacenesinventario(codalmacen,codelemento,codtalla,cantidad) 
                                                VALUES('".mysqli_real_escape_string($mysqli,$row[3])."', 
                                                '".mysqli_real_escape_string($mysqli,$r1[1])."',
                                                '".mysqli_real_escape_string($mysqli,$r1[2])."', 
                                                '0'   )  ";   
                                                if( $datos=mysqli_query($mysqli,$consulta) ){  
                                                    $swr=1;
                                                }else{  
                                                  echo 'No se ha podido guardar inventario, verifique los datos';
                                                }
                                        }else{
                                          $swr=1;
                                        }  
                                      $swr=1;
                                    } 



                                    if ($swr==1) { 

                                                $c2=" SELECT  a.cons,a.cantidad  FROM almacenesinventario a  
                                                WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$row[3])."' and 
                                                a.codelemento='".mysqli_real_escape_string($mysqli,$r1[1])."'and 
                                                a.codtalla='".mysqli_real_escape_string($mysqli,$r1[2])."'  ";   
                                                $d2=mysqli_query($mysqli,$c2);         
                                                if($r2=mysqli_fetch_row($d2)){  

                                                    $consulta=" UPDATE almacenesinventario SET  
                                                    cantidad=cantidad+".$row[0]." WHERE 
                                                    cons='".mysqli_real_escape_string($mysqli,$r2[0])."' ";   
                                                    if ($datos=mysqli_query($mysqli,$consulta) ) {
                                                        

                                                            $consulta=" UPDATE almacenesinventario SET  
                                                            cantidad=cantidad-".$row[0]." WHERE 
                                                            cons='".mysqli_real_escape_string($mysqli,$row[2])."' ";   
                                                            if ($datos=mysqli_query($mysqli,$consulta) ) {


                                                              $consulta=" INSERT almacenesinventariomovimientos(codalmacen,codelemento,codtalla,cantidad,cantidadanterior,codorigen,origen,destino,fecha,usuario) 
                                                                VALUES('".mysqli_real_escape_string($mysqli,$row[4])."', 
                                                                '".mysqli_real_escape_string($mysqli,$r1[1])."',
                                                                '".mysqli_real_escape_string($mysqli,$r1[2])."', 
                                                                '".mysqli_real_escape_string($mysqli,($r1[0]-$row[0]))."' , 
                                                                '".mysqli_real_escape_string($mysqli,$r1[0])."', 
                                                                '".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' ,
                                                                'ROTACION',
                                                                'ALMACEN SALIDA',curdate(),
                                                                 '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."'   ) ";   
                                                              if ($datos=mysqli_query($mysqli,$consulta) ) {

                                                                        $consulta=" INSERT almacenesinventariomovimientos(codalmacen,codelemento,codtalla,cantidad,cantidadanterior,codorigen,origen,destino,fecha,usuario) 
                                                                        VALUES('".mysqli_real_escape_string($mysqli,$row[3])."', 
                                                                        '".mysqli_real_escape_string($mysqli,$r1[1])."',
                                                                        '".mysqli_real_escape_string($mysqli,$r1[2])."', 
                                                                        '".mysqli_real_escape_string($mysqli,($r2[1]+$row[0]))."' , 
                                                                        '".mysqli_real_escape_string($mysqli,$r2[1])."', 
                                                                        '".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' ,
                                                                        'ROTACION',
                                                                        'ALMACEN ENTRADA',curdate(),
                                                                         '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."'   ) ";   
                                                                      if ($datos=mysqli_query($mysqli,$consulta) ) {   
                                                                                    echo "OK";  
                                                                      }else{ 
                                                                            echo 'No se pudo guardar movimiento destino'; 
                                                                      }  

                                                              }else{ 
                                                                    echo 'No se pudo guardar movimiento origen'; 
                                                              } 

                                                            }else{
                                                              echo 'No se pudo decrementar origen'; 
                                                            } 



                                                    }else{
                                                      echo 'No se pudo incrementar destino'; 
                                                    } 
                                                }else{
                                                      echo 'No se encontro inventario destino';

                                                } 
                                    } 

                                    }else{ 
                                          echo 'No se pudo actualizar rotacion'; 
                                    }  

                            }else{
                                echo 'Cantidad No Disponible en origen';
                            }  
                      }  else{
                          echo 'Sin inventario Origen';
                      }  
            }else{
                echo 'Ya fue gestionado';
            }  
          
          }else{
                echo 'No se encontró codigo inventario';
           }

   }else{
    echo 'No se pudo subir el archivo';
  }

}

function gestionarRotacion($mysqli){

  $arreglo= array();
  $consulta="
SELECT r.fecha,r.usuario,a.nombre, e.nombre,t.nombre,r.`cantidad`,a2.nombre,r.soporte,r.observaciones,r.estado,r.fechaaprueba,r.usuarioaprueba,r.soporteaprueba,r.observacionesaprueba
FROM `rotacion` r INNER JOIN almacenes a ON a.cons=r.`codalmacenorigen` 
INNER JOIN almacenesinventario ai ON ai.cons=r.`codinventario`
INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN almacenes a2 ON a2.cons=r.`codalmacendestino` 
WHERE   r.cons ='".mysqli_real_escape_string($mysqli,$_POST["gestionarRotacion"])."'  order by r.cons desc  "; 

  $datos=mysqli_query($mysqli,$consulta);
  if(mysqli_num_rows($datos)>0){
  $row=mysqli_fetch_row($datos); 
        $arreglo[]=$row;
        echo json_encode($arreglo);    
  }else{  
    echo 'n'; 
  }

}
 
function GenerarReporteAU($mysqli){  
 
        $completa="";
       if ($_POST["Almacen"]!="Todos") { 
        $completa="  AND a.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' ";
       } 
       if ($_POST["AlmacenDestino"]!="Todos") { 
        $completa.="  AND a2.`cons`='".mysqli_real_escape_string($mysqli,$_POST["AlmacenDestino"])."' ";
       }  


            $consulta = "
SELECT a.nombre, e.nombre,t.nombre,r.`cantidad`,r.fecha,a2.nombre,r.estado,r.cons
FROM `rotacion` r INNER JOIN almacenes a ON a.cons=r.`codalmacenorigen` 
INNER JOIN almacenesinventario ai ON ai.cons=r.`codinventario`
INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN almacenes a2 ON a2.cons=r.`codalmacendestino` 
WHERE   r.estado ='Enviado' ".$completa."  order by r.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>  
          <th width="50">COD</th>
          <th width="50">FECHA</th> 
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          <th  > </th>  
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">COD</th>
          <th width="50">FECHA</th> 
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          <th  > </th>  
          </tr>
        </tfoot>  <tbody> ';

$cons=1;
          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
 
 
        $CODD="'".$row[7]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$row[7].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[5].'</td>  
            <td class="sobretd"   >'.$row[6].'</td>   
            <td class="sobretd"   >
        <button type="button" class="btn btn-primary btn-circle" style="padding:7px !important" onclick="gestionarRotacion('.$CODD.');"><i class="fa fa-search"></i></button></td>  
          </tr>';
              $cons++;
            }
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 


function listadoenvios($mysqli){  
 
 

          $consulta = "
SELECT a.nombre, e.nombre,t.nombre,r.`cantidad`,ai.`cantidad`,a2.nombre,r.estado ,r.cons
FROM `rotacion` r INNER JOIN almacenes a ON a.cons=r.`codalmacenorigen` 
INNER JOIN almacenesinventario ai ON ai.cons=r.`codinventario`
INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN almacenes a2 ON a2.cons=r.`codalmacendestino` 
WHERE  r.`usuario`='".mysqli_real_escape_string($mysqli,$_POST["listadoenvios"])."' AND r.`fecha`=CURDATE()  ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>  
          <th width="50">COD</th>
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th width="50">DISPONIBLES</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">COD</th>
          <th  >ALMACEN ORIGEN</th>  
          <th  >ELEMENTO</th> 
          <th  >TALLA</th>  
          <th width="50">CANTIDAD</th> 
          <th width="50">DISPONIBLES</th> 
          <th  >ALMACEN DESTINO</th>  
          <th width="50">ESTADO</th>  
          </tr>
        </tfoot>  <tbody> ';

$cons=1;
          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
 
 
        $CODD="'".$row[1]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$row[7].'</td>
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td>  
            <td class="sobretd"   >'.$row[6].'</td>   
          </tr>';
              $cons++;
            }
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 


function CrearRotacion($mysqli){  



   $ruta="gs://lumensarchivostemporales/Stock/Rotacion/";
 //  $ruta="./";

  
                $swx=0;
                $nombre ="";
                foreach ($_FILES as $key) {
                  if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
                   $valorext=explode(".", $key['name']); 
                $nombre = time().".".$valorext[count($valorext)-1];
                $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
                move_uploaded_file($temporal, $ruta.$nombre); //Movemos el archivo temporal a la ruta especificada  
                  if(file_exists($ruta.$nombre)){
                    $swx=1; 
                     }  
                  } 
  
                } 


  if ($swx==1)
  { 


         
          $consulta=" SELECT  a.cons  FROM almacenesinventario a  
          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' and 
          a.codelemento='".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."'and 
          a.codtalla='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."'  ";     
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
            
                $consulta=" INSERT rotacion(codalmacenorigen,codinventario,codalmacendestino,usuario,cantidad,observaciones,soporte,fecha) VALUE
            ( 
            '".mysqli_real_escape_string($mysqli,($_POST["Almacen"]))."',
            '".mysqli_real_escape_string($mysqli,($row[0]))."',
            '".mysqli_real_escape_string($mysqli,($_POST["AlmacenDestino"]))."',
            '".mysqli_real_escape_string($mysqli,($_POST["Usuario"]))."',
            '".mysqli_real_escape_string($mysqli,($_POST["Cantidad"]))."',
            '".mysqli_real_escape_string($mysqli,strtoupper($_POST["Observaciones"]))."',
            '".mysqli_real_escape_string($mysqli,( $nombre))."',CURDATE() ) ";    

              if( $datos=mysqli_query($mysqli,$consulta) ){ 
                        echo 'OK';
                
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
          }else{
                echo 'No se encontró codigo inventario';
           }

   }else{
    echo 'No se pudo subir el archivo';
  }

}


function ReporteInventario($mysqli){  
 
       $completa="";
       if ($_POST["Almacen"]!="Todos") { 
        $completa="  AND a.`cons`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."'";
       } 
       if ($_POST["TipoElemento"]!="Todos") { 
        $completa.=" AND e.tipoelemento='".mysqli_real_escape_string($mysqli,$_POST["TipoElemento"])."'";
       } 

          $consulta = "
SELECT a.nombre,ai.cons,e.tipoelemento, e.nombre,t.nombre,ai.`cantidad` 
FROM almacenesinventario ai INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN `almacenes` a ON a.cons=ai.`codalmacen` WHERE  1=1
". $completa."
           order by a.nombre,e.tipoelemento, e.nombre,t.nombre   ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr>  
          <th width="50">cons</th>
          <th  >ALMACEN</th>
          <th   width="50">CODIGO</th>
          <th  >TIPO ELEMENTO</th>
          <th  >ELEMENTO</th> 
          <th  >TALLA</th> 
          <th width="50">CANTIDAD</th> 
          <th width="20"></th> 
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">cons</th>
          <th  >ALMACEN</th>
          <th  >CODIGO </th>
          <th  >TIPO ELEMENTO</th>
          <th  >ELEMENTO</th> 
          <th  >TALLA</th> 
          <th width="50">CANTIDAD</th> 
          <th width="20"></th> 
          </tr>
        </tfoot>  <tbody> ';

$cons=1;
          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
 
 
        $CODD="'".$row[1]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$cons.'</td>
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td>  
            <td class="sobretd"   >
        <button type="button" class="btn btn-info btn-circle" style="background-color:#E64A19;padding:7px !important" onclick="impresionformato('.$CODD.');"><i class="fa fa-file-text-o"></i></button></td>  
          </tr>';
            }
              $cons++;
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

function ReporteEntregas($mysqli){  
 
       $completa="";
       if ($_POST["Empleados"]!="TODOS") { 
        $completa=" AND codempleado='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."'";
       }
       if ($_POST["Proyecto"]!="TODOS") { 
        $completa.=" AND e.`codProyecto`='".mysqli_real_escape_string($mysqli,$_POST["Proyecto"])."'";
       }
       if ($_POST["TipoEntrega"]!="TODOS") { 
        $completa.=" AND `TipoEntrega`='".mysqli_real_escape_string($mysqli,$_POST["TipoEntrega"])."'";
       }
       if ($_POST["MotivoReposicion"]!="TODOS") { 
        $completa.=" AND `MotivoReposicion`='".mysqli_real_escape_string($mysqli,$_POST["MotivoReposicion"])."'";
       } 

          $consulta = " SELECT a.fechainicioEnt,a.fechafinalEnt,a.cons,p.`nombre`,e.`Nombre`,a.estado,a.cantidad,a.soporte,TipoEntrega
FROM ((asignaciones a INNER JOIN `empleados` e ON e.`Cedula`=a.codempleado) INNER JOIN proyectos p ON p.cons=e.`codProyecto`) WHERE 
a.fechainicioEnt>='".mysqli_real_escape_string($mysqli,$_POST["Desde"])."' AND 
a.fechainicioEnt<='".mysqli_real_escape_string($mysqli,$_POST["Hasta"])."' 
". $completa."
           order by cons desc";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoReporte" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="70">FECHA INICIO</th>
          <th width="70">FECHA FINAL</th>
          <th width="50">CODIGO</th>
          <th width="50">TIPO</th>
          <th>PROYECTO</th>
          <th>EMPLEADO</th>
          <th  width="50">ESTADO</th>
          <th width="50">CANTIDAD</th>
          <th width="50">SOPORTE</th> 
          <th width="20"></th> 
          </tr>
        </thead><tfoot>
          <tr>
          <th>FECHA INICIO</th>
          <th>FECHA FINAL</th>
          <th width="50">CODIGO</th>
          <th width="50">TIPO</th>
          <th>PROYECTO</th>
          <th>EMPLEADO</th>
          <th  width="50">ESTADO</th>
          <th width="50">CANTIDAD</th>
          <th width="50">SOPORTE</th> 
          <th width="20"></th> 
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  $enlace="";
  if ($row[5]=="Cerrado") {
   $enlace="<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/".$row[7]."' target='_blank'>Descargar</a>";
  }

        $CODD="'".$row[2]."'";
              $tabla.='<tr > 
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td>
            <td class="sobretd"   >'.$row[8].'</td> 
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
            <td class="sobretd"   >'.$enlace.'</td>  
            <td class="sobretd"   >
        <button type="button" class="btn btn-info btn-circle" style="background-color:#E64A19;padding:7px !important" onclick="impresionformato('.$CODD.');"><i class="fa fa-file-text-o"></i></button></td>  
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 

function FinalizaEntrega($mysqli){  



   $ruta="gs://lumensarchivostemporales/Stock/Ordenes/";
 //  $ruta="./";

  
                $swx=0;
                $nombre ="";
                foreach ($_FILES as $key) {
                  if($key['error'] == UPLOAD_ERR_OK ){//Verificamos si se subio correctamente
                   $valorext=explode(".", $key['name']); 
                $nombre = time().".".$valorext[count($valorext)-1];
                $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
                move_uploaded_file($temporal, $ruta.$nombre); //Movemos el archivo temporal a la ruta especificada  
                  if(file_exists($ruta.$nombre)){
                    $swx=1; 
                     }  
                  } 
  
                } 


  if ($swx==1)
  { 


          $consulta=" SELECT estado FROM asignaciones WHERE 
          cons='".mysqli_real_escape_string($mysqli,$_POST["Codigob"])."'   ";   
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
            
            $consulta=" UPDATE asignaciones SET
                        fechafinalEnt=CURDATE(),
                        estado='Cerrado',
                        observaciones='".mysqli_real_escape_string($mysqli,strtoupper($_POST["ObservacionesResp"]))."',
                        usuariocierre='".mysqli_real_escape_string($mysqli,($_POST["Usuario"]))."',
                        soporte='".mysqli_real_escape_string($mysqli,($nombre))."'  
                        WHERE cons='".mysqli_real_escape_string($mysqli,$_POST["Codigob"])."' ";   
              if( $datos=mysqli_query($mysqli,$consulta) ){ 
                        echo 'OK';
                
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
          }else{
                echo 'No se encontró asignacion';
              }

   }else{
    echo 'No se pudo subir el archivo';
  }

}

function AsignacionhistoricoActual($mysqli){  
 
       
          $consulta = " SELECT a.cons,a.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,a.`cantidad`,a.`usuario`,a.`observaciones` FROM ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)INNER JOIN almacenes al ON al.cons=l.codalmacen) WHERE 
          a.codasignacion='".mysqli_real_escape_string($mysqli,$_POST["codigo"])."' order by a.cons desc ";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoEntregas" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th width="50">CODIGO</th>
          <th>FECHA</th>
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          </tr>
        </thead><tfoot>
          <tr>
          <th width="50">CODIGO</th>
          <th>FECHA</th>
          <th>ALMACEN</th>
          <th>ELEMENTO</th>
          <th>TALLA</th>  
          <th width="50">CANTIDAD</th>
          <th width="50">USUARIO</th> 
          <th>OBSERVACIONES</th>
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
              $tabla.='<tr >
            
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td>
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$row[5].'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
            <td class="sobretd"   >'.$row[7].'</td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 


function buscaCantidadXTallaxElementosAlmacen($mysqli){  

          $consulta=" SELECT  a.cantidad,a.cons  FROM almacenesinventario a  
          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' and 
          a.codelemento='".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."'and 
          a.codtalla='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."'  ";   
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
                    echo $row[0] ;  
               } else{
                  echo 'No se encontró inventario ';
              }                  
     
 

}
 

function AsignacionElementos($mysqli){  

          $consulta=" SELECT  a.cantidad,a.cons  FROM almacenesinventario a  
          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' and 
          a.codelemento='".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."'and 
          a.codtalla='".mysqli_real_escape_string($mysqli,$_POST["Talla"])."'  ";   
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
               if($row[0]>=$_POST["Cantidad"]){

                    $consulta=" UPDATE almacenesinventario SET  
                    cantidad=cantidad-".$_POST["Cantidad"]." WHERE 
                    cons= '".mysqli_real_escape_string($mysqli,$row[1])."' ";   
                    if ($datos=mysqli_query($mysqli,$consulta) ) {

                             $consulta=" UPDATE asignaciones SET  
                              cantidad=cantidad+".$_POST["Cantidad"]." WHERE 
                              cons='".mysqli_real_escape_string($mysqli,$_POST["Codigob"])."' ";   
                              if ($datos=mysqli_query($mysqli,$consulta) ) {

                                      $consulta=" INSERT asignacionesdetalle(codasignacion,codalmaceninventario,cantidad,usuario,fecha,observaciones) VALUES  
                                      ('".mysqli_real_escape_string($mysqli,$_POST["Codigob"])."',
                                      '".mysqli_real_escape_string($mysqli,$row[1])."',
                                      '".mysqli_real_escape_string($mysqli,$_POST["Cantidad"])."',
                                      '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."',CURDATE(),
                                      '".mysqli_real_escape_string($mysqli,$_POST["Observaciones"])."' ) ";   
                                      if ($datos=mysqli_query($mysqli,$consulta) ) {

                                              $consulta=" INSERT almacenesinventariomovimientos(codalmacen,codelemento,codtalla,cantidad,cantidadanterior,codorigen,origen,destino,fecha,usuario) 
                                                                    VALUES('".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."', 
                                                                    '".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."',
                                                                    '".mysqli_real_escape_string($mysqli,$_POST["Talla"])."', 
                                                                    '".mysqli_real_escape_string($mysqli,$_POST["Cantidad"])."' , 
                                                                    '".mysqli_real_escape_string($mysqli,$row[0])."', 
                                                                    '".mysqli_real_escape_string($mysqli,$_POST["Codigob"])."' ,
                                                                    'ASIGNACION',
                                                                    'EMPLEADO',curdate(),
                                                                     '".mysqli_real_escape_string($mysqli,$_POST["Usuario"])."'   ) ";   
                                              if ($datos=mysqli_query($mysqli,$consulta) ) {
                                                  echo 'OK';

                                              } else{
                                                  echo 'No se ha podido ingresar, No guardó movimiento'.$consulta;
                                              }   


                                      } else{
                                          echo 'No se ha podido ingresar, No guardó detalle';
                                      }   
                              } else{
                                  echo 'No se ha podido ingresar, No incrementó asignacion';
                              }   
                    } else{
                        echo 'No se ha podido ingresar, No descontó inventario';
                    }    
               } else{
                  echo 'No se ha podido ingresar, Sin Inventario ';
              }                  
          }
 

}
 

 

function AsignacionhistoricoGeneral($mysqli){  
 
       
          $consulta = " SELECT cons,fechainicioEnt,fechafinalEnt,estado,cantidad,soporte,observaciones FROM asignaciones WHERE 
          codempleado='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."' order by cons desc";   
 
  
$datos=mysqli_query($mysqli,$consulta); 
          $tabla='<table id="exampleListadoGeneral" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>FECHA INICIO</th>
          <th>FECHA FINAL</th>
          <th width="50">CODIGO</th>
          <th  width="50">ESTADO</th>
          <th width="50">CANTIDAD</th>
          <th width="50">SOPORTE</th>
          <th>OBSERVACIONES</th>
          </tr>
        </thead><tfoot>
          <tr>
          <th>FECHA INICIO</th>
          <th>FECHA FINAL</th>
          <th>CODIGO</th>
          <th>ESTADO</th>
          <th>CANTIDAD</th>
          <th>SOPORTE</th>
          <th>OBSERVACIONES</th>
          </tr>
        </tfoot>  <tbody> ';


          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  $enlace="";
  if ($row[3]=="Cerrado") {
   $enlace="<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/".$row[5]."' target='_blank'>Descargar</a>";
  }

              $tabla.='<tr > 
            <td class="sobretd"  >'.$row[1].'</td>
            <td class="sobretd"   >'.$row[2].'</td>
            <td class="sobretd"   >'.$row[0].'</td>
            <td class="sobretd"   >'.$row[3].'</td>
            <td class="sobretd"   >'.$row[4].'</td> 
            <td class="sobretd"   >'.$enlace.'</td> 
            <td class="sobretd"   >'.$row[6].'</td> 
          </tr>';
            }
              
          }  

          $tabla.='</tbody></table>';

          echo $tabla;
} 




function IniciarAsignacion($mysqli){  

  $arreglo= array();
          $consulta=" SELECT cons,Descuento,TipoEntrega,MotivoReposicion,'SI' FROM asignaciones WHERE 
          codempleado='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."' AND estado='Abierto'  ";   
          $datos=mysqli_query($mysqli,$consulta);         
          if($row=mysqli_fetch_row($datos)){     
                      $arreglo[]=$row;
                      echo json_encode($arreglo);           
          }else{
            $consulta=" INSERT asignaciones(codempleado,fechainicioEnt,usuario,Descuento,TipoEntrega,MotivoReposicion) VALUE ( 
            '".mysqli_real_escape_string($mysqli, ($_POST["Empleados"]))."',curdate()
            , '".mysqli_real_escape_string($mysqli,($_POST["Usuario"]))."'
            , '".mysqli_real_escape_string($mysqli,($_POST["Descuento"]))."'
            , '".mysqli_real_escape_string($mysqli,($_POST["TipoEntrega"]))."'
            , '".mysqli_real_escape_string($mysqli,($_POST["MotivoReposicion"]))."'
          ) ";  

              if( $datos=mysqli_query($mysqli,$consulta) ){
                      $consulta=" SELECT cons,Descuento,TipoEntrega,MotivoReposicion,'NO' FROM asignaciones WHERE 
                      codempleado='".mysqli_real_escape_string($mysqli,$_POST["Empleados"])."' AND estado='Abierto'  ";   
                      $datos=mysqli_query($mysqli,$consulta);         
                      if($row=mysqli_fetch_row($datos)){     
                          
                      $arreglo[]=$row;
                      echo json_encode($arreglo);                         
                      }else{
                        echo 'No se encontró entrega Activa, verifique los datos';
                      }
              }else{
                  echo 'No se ha podido ingresar, verifique los datos';
              }  
          }

}


function buscaTallaxElementosAlmacen($mysqli){  

          $consulta="SELECT  distinct t.cons,t.`Nombre`  FROM ((`almacenesinventario` a INNER JOIN elementos e ON e.cons=a.codelemento ) 
          INNER JOIN tallasdetalle t ON t.cons=a.`codtalla`)

          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."' and 
          a.codelemento='".mysqli_real_escape_string($mysqli,$_POST["Elemento"])."'  ORDER BY t.`Nombre`  ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 

}
 

function buscaElementosAlmacen($mysqli){  

          $consulta="SELECT distinct e.cons,e.`Nombre` FROM `almacenesinventario` a INNER JOIN elementos e ON e.cons=a.codelemento
          WHERE a.`codalmacen`='".mysqli_real_escape_string($mysqli,$_POST["Almacen"])."'  ORDER BY e.`Nombre`  ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 

}
 
function cargarElementosListado($mysqli){  

          $consulta="SELECT DISTINCT e.cons,e.nombre FROM (`ordendecompraelementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons )
          WHERE o.`codordendecompra`='".mysqli_real_escape_string($mysqli,$_POST["cargarElementosListado"])."' ORDER BY e.nombre
          ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 

}


  

mysqli_close($mysqli);
?>