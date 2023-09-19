<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato Aaasignacion</title>
</head>
<body onLoad="window.print()">

<?php

    require_once("../Procesamiento/ConexionStock.php"); 


 
      $consulta=" 
 SELECT cons,estado,fechainicio,finafinal,Observaciones,usuario FROM `inventarioauditoria` WHERE  cons= '".mysqli_real_escape_string($mysqli,$_GET["cod"])."'  "; 

 
$datos=mysqli_query($mysqli,$consulta);	 
if($row=mysqli_fetch_row($datos)){ 
  

		echo '<table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
					<tr>
						<th rowspan="5" width="18%"  ><img src="../images/Lecta.jpg" width="90%" ></th> 
						<th  width="18%">CODIGO</th> 
						<td >'.$row[0].'</th> 
						<th  width="18%">ESTADO</th> 
						<td >'.$row[1].'</th> 
							</tr>	

							<tr>  
						<th width="18%">FECHA INICIO</th> 
						<td   >'.$row[2].'</th>  
						<th width="18%">FECHA FINAL</th> 
						<td >'.$row[3].'</th>  
							</tr>	

							<tr>   
						<th >OBSERVACIONES</th> 
						<td  colspan="3" >'.$row[4].'</th> 
						</tr>

						</table>
<br><br>
						';
						} 
 

echo '<table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
					<tr>
					
					<th style="background-color:#e1e1e1">CODIGO</th>
					<th style="background-color:#e1e1e1">FECHA</th> 
					<th style="background-color:#e1e1e1">ALMACEN</th>
					<th style="background-color:#e1e1e1">ELEMENTO</th>
					<th style="background-color:#e1e1e1">TALLA</th>
					<th style="background-color:#e1e1e1">REVISADOS</th>
					<th style="background-color:#e1e1e1">ENCONTRADOS</th> 
					<th style="background-color:#e1e1e1">RESULTADO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES</th> 
					</tr>
				<tbody>';

   $consulta = "SELECT id.cons,id.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,id.`revisados`,id.`encontrados`, id.`resultado`,id.Observaciones
FROM  ((((( `inventarioauditoria` i INNER JOIN `inventarioauditoriadetalle` id ON i.cons=id.`codinventarioauditoriaauditoria`)
INNER JOIN almacenesinventario l ON id.`codalmaceninventario`=l.cons) 
INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
INNER JOIN almacenes al ON al.cons=l.codalmacen)    where i.cons='".mysqli_real_escape_string($mysqli,($_GET["cod"]))."'
 ";   
 
 
      
  
$datos=mysqli_query($mysqli,$consulta); 





          if(mysqli_num_rows($datos)>0){            
while($row=mysqli_fetch_row($datos)){ 
  
             echo '<tr > 
					<td    >'.$row[0].'</td>
					<td   >'.$row[1].'</td>
					<td    >'.$row[2].'</td>
					<td    >'.$row[3].'</td>
					<td    >'.$row[4].'</td> 
					<td  >'.$row[5].'</td> 
					<td   >'.$row[6].'</td> 
					<td     >'.$row[7].'</td> 
					<td     >'.$row[8].'</td>  
          </tr>';
            }
              
          }  
 echo '</table>'; 

?>


