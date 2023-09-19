<?php

    require_once("../Procesamiento/ConexionStock.php");
	$filename = "DetalleRotacion".$_GET["Desde"]."A".$_GET["Hasta"].".xls"; 
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");

    echo '
    
    <!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
		<body>
    
    <table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
        <tr> 
					<th style="background-color:#e1e1e1">CONS</th>
					<th style="background-color:#e1e1e1">CODIGO ROTACION</th> 
					<th style="background-color:#e1e1e1">FECHA ENVIO</th>
					<th style="background-color:#e1e1e1">USUARIO ENVIO</th>
					<th style="background-color:#e1e1e1">ALMACEN ORIGEN</th>
					<th style="background-color:#e1e1e1">ELEMENTO</th>
					<th style="background-color:#e1e1e1">TALLA</th>
					<th style="background-color:#e1e1e1">CANTIDAD</th>
					<th style="background-color:#e1e1e1">ALMACEN DESTINO</th>
					<th style="background-color:#e1e1e1">SOPORTE ENVIO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES ENVIO</th>
					<th style="background-color:#e1e1e1">ESTADO</th>
					<th style="background-color:#e1e1e1">FECHA RECIBIDO</th>
					<th style="background-color:#e1e1e1">USUARIO RECIBIDO</th>
					<th style="background-color:#e1e1e1">SOPORTE RECIBIDO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES RECIBIDO</th>
		</tr>
				<tbody>';    
 
	    $completa="";
 

      $completa="";
       if ($_GET["Almacen"]!="Todos") { 
        $completa.="  AND a.`cons`='".mysqli_real_escape_string($mysqli,$_GET["Almacen"])."' ";
       } 
       if ($_GET["AlmacenDestino"]!="Todos") { 
        $completa.="  AND a2.`cons`='".mysqli_real_escape_string($mysqli,$_GET["AlmacenDestino"])."' ";
       }  
       if ($_GET["Estado"]!="Todos") { 
        $completa.="  AND r.estado='".mysqli_real_escape_string($mysqli,$_GET["Estado"])."' ";
       }  

     $consulta = "
SELECT r.cons,r.fecha,r.usuario,a.nombre, e.nombre,t.nombre,r.`cantidad`,a2.nombre,r.soporte,r.observaciones,r.estado,r.fechaaprueba,r.usuarioaprueba,r.soporteaprueba,r.observacionesaprueba
FROM `rotacion` r INNER JOIN almacenes a ON a.cons=r.`codalmacenorigen` 
INNER JOIN almacenesinventario ai ON ai.cons=r.`codinventario`
INNER JOIN elementos e ON e.cons=ai.`codelemento` 
INNER JOIN tallasdetalle t ON t.cons=ai.`codtalla` 
INNER JOIN almacenes a2 ON a2.cons=r.`codalmacendestino` 
WHERE   r.fecha>='".mysqli_real_escape_string($mysqli,$_GET["Desde"])."' and r.fecha<='".mysqli_real_escape_string($mysqli,$_GET["Hasta"])."' 
 ".$completa."  order by r.cons desc ";   
 

 
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
					<td  ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Rotacion/'.$row[8].'" target="_blank">Descargar</a></td>  
					<td  >'.$row[9].'</td>  
					<td  >'.$row[10].'</td>
					<td  >'.$row[11].'</td>
					<td  >'.$row[12].'</td>
					<td  ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Rotacion/'.$row[13].'" target="_blank">Descargar</a></td>   
					<td  >'.$row[14].'</td> 
				</tr>';
$cont++;
			}
	    echo '</tbody></table>';



?>


