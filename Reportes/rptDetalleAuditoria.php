<?php

    require_once("../Procesamiento/ConexionStock.php");
	$filename = "DetalleAuditorias".$_GET["Desde"]."A".$_GET["Hasta"].".xls"; 
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
					<th style="background-color:#e1e1e1">CODIGO AUDITORIA</th> 
					<th style="background-color:#e1e1e1">ESTADO</th>
					<th style="background-color:#e1e1e1">USUARIO</th>
					<th style="background-color:#e1e1e1">FECHA INICIO</th>
					<th style="background-color:#e1e1e1">FECHA FINAL</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES</th>
					<th style="background-color:#e1e1e1">CODIGO ELEMENTO AUDITADO</th> 
					<th style="background-color:#e1e1e1">FECHA</th>  
					<th style="background-color:#e1e1e1">ALMACEN</th>
					<th style="background-color:#e1e1e1">ELEMENTO</th>
					<th style="background-color:#e1e1e1">TALLA</th>  
					<th style="background-color:#e1e1e1">REVISADOS</th>
					<th style="background-color:#e1e1e1">ENCONTRADOS</th> 
					<th style="background-color:#e1e1e1">RESULTADO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES ELEMENTO AUDITADO</th> 
					<th style="background-color:#e1e1e1">SOPORTE</th> 
		</tr>
				<tbody>';    
 
	    $completa="";
 

      $completa="";
       if ($_GET["Filtro"]!="TODAS") { 
        $completa="  AND i.usuario='".mysqli_real_escape_string($mysqli,$_GET["Filtro"])."' ";
       }   


       $consulta = "SELECT  i.cons,i.estado,i.`usuario`,i.`fechainicio`,i.`finafinal`,i.`observaciones`,
 id.cons,id.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,id.`revisados`,id.`encontrados`, id.`resultado`,id.Observaciones,id.soporte
FROM  ((((( `inventarioauditoria` i INNER JOIN `inventarioauditoriadetalle` id ON i.cons=id.`codinventarioauditoriaauditoria`)
INNER JOIN almacenesinventario l ON id.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)  
WHERE  i.`fechainicio`>='".mysqli_real_escape_string($mysqli,$_GET["Desde"])."' and i.`finafinal`<='".mysqli_real_escape_string($mysqli,$_GET["Hasta"])."' 
 ".$completa."  order by i.cons desc ";   
 

 
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
					<td  >'.$row[10].'</td>
					<td  >'.$row[11].'</td>
					<td  >'.$row[12].'</td>
					<td  >'.$row[13].'</td>
					<td  >'.$row[14].'</td> 
					<td  ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Auditoria/'.$row[15].'" target="_blank">Descargar</a></td>   
				</tr>';
$cont++;
			}
	    echo '</tbody></table>';



?>


