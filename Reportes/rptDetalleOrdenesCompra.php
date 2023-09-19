<?php

    require_once("../Procesamiento/Conexion.php");

	
	$cliente = $mysqli->real_escape_string($_GET["cliente"]);
	$fechaInicio = $mysqli->real_escape_string($_GET["fechaInicio"]);
	$fechaFinal = $mysqli->real_escape_string($_GET["fechaFinal"]);

	$filename = "DetalleOrdenesCompras-$cliente-$fechaInicio-$fechaFinal.xls"; 
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");

	$where = "o.fecharecpecion between '$fechaInicio' and '$fechaFinal' ";
	$where .= $cliente!="Todos" ? " and o.codcliente = '$cliente' " : "";

	$consulta = "
	select o.cons, u.nombre AS nombreUsuario , o.fecharealizado , 
	   o.formadepago, o.fechaentrega , o.fechadocierre , 
	   o.cotizaciones AS solicitud, o.estado , o.fechaestado, 
	   c.nombre AS cliente, d.nombre AS departamento, o.observaciones, o.valor , o.ordencompra , o.fecharecpecion , o.costo , o.plazo, o.subcliente 
	  from (((ordendecompra o 
	  	inner join clientes c on o.codcliente=c.cons)
	  	inner join departamentos d  on o.departamento= d.cons)
	  	left join usuarios u on o.usuario=u.usuario) 
		  where $where order by o.cons DESC;";


	// echo $consulta;

	$rowsTable = "";


	$resultado = $mysqli->query($consulta);
	if($resultado->num_rows>0){
		$contador = 1;
		while($row = $resultado->fetch_array()){
			$cons = $row["cons"];
			$nombreUsuario = $row["nombreUsuario"]; 
			$fecharealizado = $row["fecharealizado"]; 
			$formadepago = $row["formadepago"];
			$fechaentrega = $row["fechaentrega"]; 
			$fechadocierre = $row["fechadocierre"]; 
			$solicitud = $row["solicitud"] == "" ? "Sin Solicitud" : '<a href="https://sudicol.com.co/stock/Archivos/Soportes/'.$row["solicitud"].'" target="_blank">Ver Solicitud</a>';
			$estado = $row["estado"]; 
			$fechaestado = $row["fechaestado"];
			$nombreCliente = $row["cliente"];
			$subCliente = $row["subcliente"];
			$nombreDepartamento = $row["departamento"];
			$observaciones = $row["observaciones"];
			$valor = $row["valor"]; 
			$ordencompra = $row["ordencompra"]; 
			$fecharecpecion = $row["fecharecpecion"]; 
			$costo = $row["costo"]; 
			$plazo = $row["plazo"]!="" ? $row["plazo"]." Dias" : "";
			$rentabilidad = ($valor-$costo);

			$rowsTable .= "
			<tr>
				<td>$contador</td>
				<td>$cons</td>
				<td>$nombreUsuario</td>
				<td>$nombreDepartamento</td>
				<td>$nombreCliente</td>
				<td>$subCliente</td>
				<td>$ordencompra</td>
				<td>$fecharecpecion</td>
				<td>$formadepago</td>
				<td>$valor</td>
				<td>$costo</td>
				<td>$rentabilidad</td>
				<td>$plazo</td>
				<td>$estado</td>
				<td>$fechaestado</td>
				<td>$fecharealizado</td>
				<td>$fechaentrega</td>
				<td>$fechadocierre</td>
				<td>$observaciones</td>
				<td>$solicitud</td>
			</tr>
			";
			$contador++;
		}
	}

    $tabla='
    <!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
	<body>
    
    <table border="1" width="100%" style="font-size: 12px !important;border-collapse: collapse;">
        <tr> 
					<th style="background-color:#e1e1e1">#</th>  
					<th style="background-color:#e1e1e1">CONSECUTIVO</th>  
					<th style="background-color:#e1e1e1">USUARIO</th>  
					<th style="background-color:#e1e1e1">DEPARTAMENTO</th>  
					<th style="background-color:#e1e1e1">CLIENTE</th>  
					<th style="background-color:#e1e1e1">SUBCLIENTE</th>  
					<th style="background-color:#e1e1e1">ORDENCOMPRA </th>  
					<th style="background-color:#e1e1e1">FECHARECPECION </th>  
					<th style="background-color:#e1e1e1">FORMADEPAGO</th>  
					<th style="background-color:#e1e1e1">VALOR </th>  
					<th style="background-color:#e1e1e1">COSTO </th>  
					<th style="background-color:#e1e1e1">RENTABILIDAD</th>  
					<th style="background-color:#e1e1e1">PLAZO</th>  
					<th style="background-color:#e1e1e1">ESTADO </th>  
					<th style="background-color:#e1e1e1">FECHAESTADO</th>  
					<th style="background-color:#e1e1e1">FECHAREALIZADO </th>  
					<th style="background-color:#e1e1e1">FECHAENTREGA </th>  
					<th style="background-color:#e1e1e1">FECHADOCIERRE </th>  
					<th style="background-color:#e1e1e1">OBSERVACIONES</th>  
					<th style="background-color:#e1e1e1">SOLICITUD</th>  
		</tr>
	<tbody>
		'.$rowsTable.'
	</tbody>
	</table>
	</body></html>';


	echo $tabla;
