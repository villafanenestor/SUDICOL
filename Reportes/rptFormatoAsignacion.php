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


 
      $consulta="SELECT e.`Cedula`,e.`Nombre`,p.`nombre`, a.fechainicioEnt,a.usuario,a.fechafinalEnt,a.usuariocierre,a.cons,a.estado,a.cantidad,a.observaciones,TipoEntrega,MotivoReposicion,Descuento
FROM ((asignaciones a INNER JOIN `empleados` e ON e.`Cedula`=a.codempleado) INNER JOIN proyectos p ON p.cons=e.`codProyecto`) WHERE 
a.cons= '".mysqli_real_escape_string($mysqli,$_GET["cod"])."'  "; 

$nombre="";
$fecha="";
$impreso="";
//echo $consulta;
$datos=mysqli_query($mysqli,$consulta);	 
if($row=mysqli_fetch_row($datos)){ 
 
if ($row[13]!="" && $row[13]!="0") { 
	$row[12]=$row[12]." - Descuento: $".$row[13];
}

		echo '<table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
					<tr>
						<th rowspan="5" width="18%"  ><img src="../images/Lecta.jpg" width="90%" ></th> 
						<th  width="18%">Cedula</th> 
						<td >'.$row[0].'</th> 
						<th width="18%">Empleado</th> 
						<td   >'.$row[1].'</th> 	</tr>	

							<tr> 
						<th >Codigo Asignacion</th> 
						<td >'.$row[7].'</th> 
						<th >Proyecto</th> 
						<td >'.$row[2].'</th> 
							</tr>	

							<tr>  
						<th >Usuario Entrega</th> 
						<td >'.$row[4].'</th> 
						<th >Fecha Inicio</th> 
						<td >'.$row[3].'</th> 
						</tr> <tr> 
						<th >Usuario Cierre</th> 
						<td >'.$row[6].'</th> 
						<th >Fecha Final</th> 
						<td >'.$row[5].'</th>  
						</tr>
	<tr> 
						<th >Tipo Entrega</th> 
						<td >'.$row[11].'</th> 
						<th >Motivo Reposicion</th> 
						<td  >'.$row[12].' </th> 
</tr>
						<tr> 
						<th >Estado</th> 
						<td >'.$row[8].'</th> 
						<th >Observaciones</th> 
						<td  colspan="3" >'.$row[10].'</th> 
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
					<th style="background-color:#e1e1e1">CANTIDAD</th>
					<th style="background-color:#e1e1e1">USUARIO</th>
					<th style="background-color:#e1e1e1">OBSERVACIONES</th> 
					</tr>
				<tbody>';

            $consulta = " SELECT a.cons,a.`fecha`,al.`nombre`,e.`Nombre`,t.`nombre`,a.`cantidad`,a.`usuario`,a.`observaciones` FROM ((((`asignacionesdetalle` a INNER JOIN almacenesinventario l ON a.`codalmaceninventario`=l.cons) INNER JOIN elementos e ON e.cons=l.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=l.`codtalla`)INNER JOIN almacenes al ON al.cons=l.codalmacen) WHERE 
          a.codasignacion='".mysqli_real_escape_string($mysqli,$_GET["cod"])."' order by a.cons desc ";   
  
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
          </tr>';
            }
              
          }  
 echo '</table>';
     /**   echo '</table>

        <BR>
        <BR> 
        <BR>
        <BR>



        _________________________<BR>
        FIRMA EMPLEADO';*/
 

?>


