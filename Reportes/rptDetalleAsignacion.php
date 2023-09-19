<?php

    require_once("../Procesamiento/ConexionStock.php");
	$filename = "DetalleAsignacion".$_GET["Desde"]."A".$_GET["Hasta"].".xls"; 
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
					<th style="background-color:#e1e1e1">CODIGO ASIGNACION</th>
					<th style="background-color:#e1e1e1">TIPO ENTREGA</th>
					<th style="background-color:#e1e1e1">MOTIVO REPOSICION</th>
					<th style="background-color:#e1e1e1">DESCUENTO</th>
					<th style="background-color:#e1e1e1">CEDULA</th>
					<th style="background-color:#e1e1e1">EMPLEADO</th>
					<th style="background-color:#e1e1e1">PROYECTO</th>
					<th style="background-color:#e1e1e1">CARGO</th>
					<th style="background-color:#e1e1e1">USUARIO ENTREGA</th>
					<th style="background-color:#e1e1e1">FECHA ENTREGA</th>
					<th style="background-color:#e1e1e1">USUARIO CIERRE</th>
					<th style="background-color:#e1e1e1">FECHA CIERRE</th>
					<th style="background-color:#e1e1e1">ESTADO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES ASIGNACION</th>
					<th style="background-color:#e1e1e1">SOPORTE ASIGNACION</th> 
					<th style="background-color:#e1e1e1">CANTIDAD TOTAL</th> 
					<th style="background-color:#e1e1e1">CODIGO PRODUCTO</th>
					<th style="background-color:#e1e1e1">FECHA</th>
					<th style="background-color:#e1e1e1">ALMACEN</th>
					<th style="background-color:#e1e1e1">PRODUCTO</th>
					<th style="background-color:#e1e1e1">TALLA</th>
					<th style="background-color:#e1e1e1">CANTIDAD</th>
					<th style="background-color:#e1e1e1">USUARIO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES</th>  
		</tr>
				<tbody>';    

 
	    $completa="";
       if ($_GET["Empleados"]!="TODOS") { 
        $completa=" AND ai.codempleado='".mysqli_real_escape_string($mysqli,$_GET["Empleados"])."'";
       }
       if ($_GET["Proyecto"]!="TODOS") { 
        $completa.=" AND em.`codProyecto`='".mysqli_real_escape_string($mysqli,$_GET["Proyecto"])."'";
       }
       if ($_GET["TipoEntrega"]!="TODOS") { 
        $completa.=" AND em.`codProyecto`='".mysqli_real_escape_string($mysqli,$_GET["TipoEntrega"])."'";
       }
       if ($_GET["MotivoReposicion"]!="TODOS") { 
        $completa.=" AND em.`codProyecto`='".mysqli_real_escape_string($mysqli,$_GET["MotivoReposicion"])."'";
       }


    $consulta = " SELECT ai.cons,TipoEntrega,MotivoReposicion,Descuento,em.`Cedula`,em.`Nombre`,p.`nombre`,c.`nombre`,ai.`usuario`,ai.`fechainicioEnt`,ai.`usuariocierre`,ai.`fechafinalEnt`,ai.`estado`,ai.`observaciones`,ai.`soporte`,ai.`cantidad`,a.`cons`,a.`fecha`, al.`nombre`,e.`Nombre`,t.`nombre`,a.`cantidad`,a.`usuario`,a.`observaciones` FROM ((((((((  
`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons)
 INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)
 INNER JOIN almacenes al ON al.cons=l.codalmacen) INNER JOIN asignaciones ai ON ai.cons=a.`codasignacion`) 
 INNER JOIN empleados em ON em.cedula=ai.`codempleado`) INNER JOIN proyectos p ON p.cons=em.`codProyecto`) 
 INNER JOIN cargos c ON c.cons=em.`codCargo`) WHERE 
          ai.`fechainicioEnt`>='".mysqli_real_escape_string($mysqli,$_GET["Desde"])."' AND
          ai.`fechainicioEnt`<='".mysqli_real_escape_string($mysqli,$_GET["Hasta"])."'
". $completa."
           order by ai.cons desc ";   
 
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
					<td  ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/'.$row[14].'" target="_blank">Descargar</a></td>    
					<td  >'.$row[15].'</td>
					<td  >'.$row[16].'</td>
					<td  >'.$row[17].'</td>
					<td  >'.$row[18].'</td>  
					<td  >'.$row[19].'</td> 
					<td  >'.$row[20].'</td> 
					<td  >'.$row[21].'</td> 
					<td  >'.$row[22].'</td> 
					<td  >'.$row[23].'</td> 
				</tr>';
$cont++;
			}
	    echo '</tbody></table>';



?>


