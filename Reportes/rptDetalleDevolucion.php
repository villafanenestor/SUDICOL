<?php

    require_once("../Procesamiento/ConexionStock.php");
	$filename = "DetalleDevolucion".$_GET["Desde"]."A".$_GET["Hasta"].".xls"; 
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");

    echo ' <!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
		<body>
    
    <table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
        <tr> 
					<th style="background-color:#e1e1e1">CONS</th>
					<th style="background-color:#e1e1e1">CODIGO DEVOLUCION</th> 
					<th style="background-color:#e1e1e1">EMPLEADO</th>
					<th style="background-color:#e1e1e1">FECHA DEVOLUCION</th>
					<th style="background-color:#e1e1e1">USUARIO DEVOLUCION</th>
					<th style="background-color:#e1e1e1">ALMACEN ORIGEN</th>
					<th style="background-color:#e1e1e1">ELEMENTO</th>
					<th style="background-color:#e1e1e1">TALLA</th>
					<th style="background-color:#e1e1e1">CANTIDAD DEVUELTAS</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES </th> 
					<th style="background-color:#e1e1e1">ESTADO ELEMENTOS</th> 
					<th style="background-color:#e1e1e1">SOPORTE  </th> 
					<th style="background-color:#e1e1e1">CODIGO ASIGNACION</th> 
					<th style="background-color:#e1e1e1">FECHA ASIGNACION ELEMENTO</th> 
					<th style="background-color:#e1e1e1">CANTIDAD ASIGNADAS</th>
					<th style="background-color:#e1e1e1">CANTIDAD DEVUELTAS TOTAL</th>
		</tr>
				<tbody>';    
 
	    $completa="";
 

      $completa="";
       if ($_GET["Proyecto"]!="TODOS") { 
        $completa="  AND em.`codProyecto`='".mysqli_real_escape_string($mysqli,$_GET["Proyecto"])."' ";
       } 
       if ($_GET["Empleados"]!="TODOS") { 
        $completa="  AND em.cedula='".mysqli_real_escape_string($mysqli,$_GET["Empleados"])."' ";
       }  
 



     $consulta = "
SELECT d.cons,em.nombre,d.`fecha`,d.`usuario`,al.`nombre`,e.`Nombre`,t.`nombre`,d.`cantidad` ,d.`observaciones`,d.`estado`,d.`soporte`,a.`codasignacion`,a.`fecha`,a.`cantidad`,a.`devueltos`
FROM (((((((`asignacionesdetalle` a INNER JOIN devoluciones d ON d.`codasignacionesdetalle`=a.cons )
INNER JOIN almacenesinventario l ON d.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)  INNER JOIN  asignaciones ai ON ai.cons=a.`codasignacion`)
INNER JOIN empleados em ON em.cedula=ai.`codempleado`)
WHERE   d.fecha>='".mysqli_real_escape_string($mysqli,$_GET["Desde"])."' and d.fecha<='".mysqli_real_escape_string($mysqli,$_GET["Hasta"])."' 
 ".$completa."  order by d.cons desc ";   
 

 
$datos=mysqli_query($mysqli,$consulta);	

				$cont=1;         
while($row=mysqli_fetch_row($datos)){ 


					echo '<tr >
					<td  >'.$cont.'</td>
					<td  >'.$row[0].'</td>
					<td  >'.$row[1].'</td>
					<td  >'.$row[2].'</td> 
					<td  >'.$row[3].'</td> 
					<td  >'.$row[4].'</td>
					<td  >'.$row[5].'</td>
					<td  >'.$row[6].'</td>
					<td  >'.$row[7].'</td> 
					<td  >'.$row[8].'</td> 
					<td  >'.$row[9].'</td>  
					<td  ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Devolucion/'.$row[10].'" target="_blank">Descargar</a></td> 
					<td  >'.$row[11].'</td>
					<td  >'.$row[12].'</td> 
					<td  >'.$row[13].'</td>  
					<td  >'.$row[14].'</td> 
				</tr>';
$cont++;
			}
	    echo '</tbody></table>';



?>


