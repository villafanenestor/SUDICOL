<?php

require_once("../Procesamiento/Conexion.php");

$option = $_GET["option"];

if($option == "detalleAsignacion") rptDetalleAsignacion();


function rptDetalleAsignacion(){
	global $mysqli;
	$estadoConsulta = $_GET["estado"];
	$proyecto = $mysqli->real_escape_string($_GET["Proyecto"]); 
	$procesos = $_GET["procesos"] !="" ? explode(',',$_GET["procesos"]) : "";
	$procesos = count($procesos) >=1 ? implode(',', array_map(function($item) { return "'$item'"; }, $procesos)) : "";
	$desde = $mysqli->real_escape_string($_GET["Desde"]);
	$hasta = $mysqli->real_escape_string($_GET["Hasta"]);
	$condicionesEstados = array(
	  "Estado"=> "",
	  "Pendientes" => " and p.realizado = 0 ",
	  "En Proceso" => " (and p.realizado > 0 and p.realizado < p.cantidad) ",
	  "Finalizados" => " and p.cantidad = p.realizado ",
	  "Sin Asignar" => " AND pr.nombre is null and u.nombre is null ",
	  "sinAsignar" => " AND (pr.nombre is null or pr.nombre='') and (u.nombre is null or u.nombre='')  ",
	);
	// echo $estadoConsulta;
	$completa = $proyecto=="Todos" ? " " : " and c.cons='$proyecto'   ";
	$completa .= $estadoConsulta !="Todos" ? " $condicionesEstados[$estadoConsulta] " : " ";
	$completa .= $procesos != "" ? " and p.proceso in ($procesos)" : " ";

	 $consulta = " 
	  SELECT c.nombre as nombreCliente,o.ordencompra,o.fecharecpecion,o.fechaentrega,el.nombre as nombreProducto,
	  t.nombre as nombreTalla,p.proceso,p.cantidad,p.realizado,pr.nombre as nombreProveedor,u.nombre as nombreUsuario, p.cons, p.observacion, p.fechainicial, p.fechafinal, p.fechaestimada
	  FROM u682444666_stock.ordendecompra o 
	  INNER JOIN u682444666_stock.ordendecompraelementos e ON o.cons=e.codordendecompra 
	  INNER JOIN u682444666_stock.ordendecompraprocesos p ON p.codordendecompraelementos=e.cons 
	  INNER JOIN u682444666_stock.elementos el ON el.cons=e.codelemento 
	  INNER JOIN u682444666_stock.tallasdetalle t ON t.cons=e.codtalla
	  INNER JOIN u682444666_stock.clientes c ON c.cons=o.codcliente
	  LEFT JOIN u682444666_stock.proveedores pr ON pr.cons=p.codproveedor
	  LEFT JOIN u682444666_stock.usuarios u ON u.usuario=p.codproveedor
	  WHERE (NOT o.estado='Terminada' AND NOT o.estado='Cancelada') 
			  and  fecharecpecion>='$desde'  
			  AND fecharecpecion<='$hasta' 
			  " . $completa . "  ORDER BY o.fecharecpecion asc";
	// echo $consulta;

	$procesos;

	$rowsTable = "";

	$resultado = $mysqli->query($consulta);
	if($resultado->num_rows > 0){
		$contador = 1;
		while($row = $resultado->fetch_array()){

			$nombreCliente = $row["nombreCliente"];
			$ordenCompra = $row["ordencompra"];
			$fechaRecepcion = $row["fecharecpecion"];
			$fechaEntrega = $row["fechaentrega"];
			$nombreProducto = $row["nombreProducto"];
			$nombreTalla = $row["nombreTalla"];
			$proceso = $row["proceso"];
			$cantidad = $row["cantidad"];
			$realizado = $row["realizado"];
			$nombreProveedor = $row["nombreProveedor"];
			$nombreUsuario = $row["nombreUsuario"];
			$cons = $row["cons"];
			$observacion = $row["observacion"];

			$porcentajeRealizados = round(($realizado / $cantidad * 100), 0);
			$fechainicial = $row["fechainicial"]; 
			$fechafinal = $row["fechafinal"];
			$fechaestimada = $row["fechaestimada"];


			// $tipoAsignacion = $nombreProveedor=="" ? "Externo" : "Sudicol";
			// $nombreAsignado = $nombreProveedor=="" ? $nombreProveedor : $nombreUsuario;
			$asignado = "NO Asignado";
			$asignadoA = "";

			if($nombreProveedor!=""){
				$asignado = "Externo";
				$asignadoA = $nombreProveedor;
			}
			if($nombreUsuario!=""){
				$asignado = "Sudicol";
				$asignadoA = $nombreUsuario;
			}

			$pendientes = ($cantidad-$realizado);

			$rowsTable .= "
			<tr>
				<td>$contador</td>
				<td>$nombreCliente</td>
				<td>$ordenCompra</td>
				<td>$fechaRecepcion</td>
				<td>$fechaEntrega</td>
				<td>$nombreProducto</td>
				<td>$nombreTalla</td>
				<td>$proceso</td>
				<td>$cantidad</td>
				<td>$realizado</td>
				<td>$pendientes</td>

				<td>$porcentajeRealizados%</td>
				<td>$fechainicial</td>
				<td>$fechafinal</td>
				<td>$fechaestimada</td>

				<td>$asignado</td>
				<td>$asignadoA</td>
				<td>$observacion</td>

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
			<th style="background-color:#e1e1e1">CLIENTE</th>
			<th style="background-color:#e1e1e1">ORDEN</th>
			<th style="background-color:#e1e1e1">FECHA RECEPCION</th>
			<th style="background-color:#e1e1e1">FECHA ENTREGA</th>
			<th style="background-color:#e1e1e1">PRODUCTO</th>
			<th style="background-color:#e1e1e1">TALLA</th>
			<th style="background-color:#e1e1e1">PROCESO</th>
			<th style="background-color:#e1e1e1">CANTIDAD</th>
			<th style="background-color:#e1e1e1">REALIZADOS</th>
			<th style="background-color:#e1e1e1">PENDIENTES</th>
			<th style="background-color:#e1e1e1">%</th>
			<th style="background-color:#e1e1e1">FECHA INICIADO</th>
			<th style="background-color:#e1e1e1">FECHA TERMINADO</th>
			<th style="background-color:#e1e1e1">FECHA DE ENTREA ESTIMADA</th>
			<th style="background-color:#e1e1e1">ASIGNACION</th>
			<th style="background-color:#e1e1e1">ASIGNADO A</th> 
			<th style="background-color:#e1e1e1">COMENTARIO PROCESO</th> 
		</tr>
	<tbody>
		'.$rowsTable.'
	</tbody>
	</table>
	</body></html>';

	$filename = "detalleAsignacion-$desde-$hasta.xls"; 
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
	echo $tabla;
}





?>