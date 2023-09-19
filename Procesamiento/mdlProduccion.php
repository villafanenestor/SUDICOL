<?php
require_once("Conexion.php");

$option = $_POST['option'] ? $_POST['option'] : '';


if($option == 'listarProduccion') listarProduccion();
elseif($option == 'actualizarRealizadosProceso') actualizarRealizadosProceso();



function listarProduccion(){
    global $mysqli;
    $cliente = $mysqli->real_escape_string($_POST['cliente']);
    $procesos = count($_POST["procesos"]) >=1 ? implode(',', array_map(function($item) { return "'$item'"; }, $_POST["procesos"])) : "";
    $usuario = $mysqli->real_escape_string($_POST['usuario']);
    $condicionesEstados = array(
        "Estado"=> "",
        "Pendientes" => " and p.realizado = 0 ",
        "En Proceso" => " (and p.realizado > 0 and p.realizado < p.cantidad) ",
        "Finalizados" => " and p.cantidad = p.realizado ",
      );
    
    $where = "";
    $where .= $cliente != "Todos" ? " and o.codcliente='$cliente' " : "";
    $where .= $procesos != "" ? " and p.proceso in ($procesos) " : "";

    $consulta = " 
    SELECT c.nombre nombreCliente,o.ordencompra,o.fecharecpecion,o.fechaentrega,el.nombre nombreProducto,
    t.nombre nombreTalla,p.proceso,p.cantidad as totalARealizar,p.realizado as cantidadRealizados,pr.nombre nombreProveedor,u.nombre nombreUsuario,p.cons, o.cons as codOrdenCompra, p.codordendecompraelementos
    FROM ordendecompra o INNER JOIN ordendecompraelementos e ON o.cons=e.codordendecompra 
    INNER JOIN ordendecompraprocesos p ON p.codordendecompraelementos=e.cons 
    INNER JOIN elementos el ON el.cons=e.codelemento 
    INNER JOIN tallasdetalle t ON t.cons=e.codtalla
    INNER JOIN clientes c ON c.cons=o.codcliente
    LEFT JOIN proveedores pr ON pr.cons=p.codproveedor
    LEFT JOIN usuarios u ON u.usuario=p.codproveedor
    WHERE (NOT o.estado='Terminada' AND NOT o.estado='Cancelada')
    and p.codproveedor='$usuario'
    $where  
    HAVING cantidadRealizados < totalARealizar
    ORDER BY o.fecharecpecion asc";


    // echo $consulta;


    
    $rowsTable = "";

    $resultado = $mysqli->query($consulta);
    if($resultado->num_rows > 0){
        $i = 1;
        while($row = $resultado->fetch_array()){
            $consProceso = $row['cons'];
            $nombreCliente = $row['nombreCliente'];
            $ordenCompra = $row['ordencompra'];
            $fechaRecepcion = $row['fecharecpecion'];
            $fechaEntrega = $row['fechaentrega'];
            $nombreProducto = $row['nombreProducto'];
            $nombreTalla = $row['nombreTalla'];
            $proceso = $row['proceso'];
            $cantidad = $row['totalARealizar'];
            $cantidadRealizados = $row['cantidadRealizados'];
            $nombreProveedor = $row['nombreProveedor'];
            $pendietes = $cantidad-$cantidadRealizados;
            $porcentajeRealizados = round(($cantidadRealizados / $cantidad * 100), 0);
            $codOrdenCompra = $row['codOrdenCompra'];
            $codordendecompraelementos = $row['codordendecompraelementos'];
            $btn = '  <button type="button" id="btnActualizarRealizados'.$consProceso.'" class="btn btn-success btn-circle" style="background-color:#388E3C;padding:7px !important" onclick="actualizarRealizados('.$consProceso.', '.$codordendecompraelementos.', '.$codOrdenCompra.' );"><i class="fa fa-check"></i></button> ';
            
            $rowsTable .= "
            <tr>
                <td class='text-center'>$i</td>
                <td class='text-center'>$nombreCliente</td>
                <td class='text-center'>$ordenCompra</td>
                <td class='text-center'>$fechaRecepcion</td>
                <td class='text-center'>$fechaEntrega</td>
                <td class='text-center'>$nombreProducto</td>
                <td class='text-center'>$nombreTalla</td>
                <td class='text-center'>$proceso</td>
                <td class='text-center'>$cantidad</td>
                <td class='text-center'>$cantidadRealizados</td>
                <td class='text-center'>$porcentajeRealizados%</td>
                <td class='text-center'>$pendietes</td>
                <td class='text-center'><input type='number' id='realizados$consProceso' class='form-control' min='$cantidadRealizados' max='$cantidad'  ></td>
                <td class='text-center'><input type='text' id='observacion$consProceso' class='form-control' ></td>
                <td class='text-center'>$btn</td>
                <input type='number' id='pendientesProceso$consProceso' value='$pendietes' hidden>
            </tr>
                ";
        }
    }



    $titleTable = "
    <tr>
        <th class='text-center' >#</th>
        <th class='text-center' >CLIENTE</th>
        <th class='text-center' >ORDEN</th>
        <th class='text-center' >FECHA RECEPCION</th>
        <th class='text-center' >FECHA ENTREGA</th>
        <th class='text-center' >PRODUCTO</th>
        <th class='text-center' >TALLA</th>
        <th class='text-center' >PROCESO </th>
        <th class='text-center' >CANTIDAD</th>
        <th class='text-center' >REALIZADOS</th>
        <th class='text-center' >PORCENTAJE REALIZADOS</th>
        <th class='text-center' >PENDIENTES</th>
        <th class='text-center' >REGISTRAR REALIZADOS</th>
        <th class='text-center' >COMENTARIO AL PROCESO</th>
        <th class='text-center'  >OPCIONES</th>
    </tr> ";
    $tabla = '
    <table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>'.$titleTable.'</thead>
        <tbody>'.$rowsTable.'</tbody>
        <tfoot>'.$titleTable.'</tfoot>  
    </table> 
    ';


    echo $tabla;
    
}

function actualizarRealizadosProceso(){
    global $mysqli;
    $response = array();
    $consProceso = $mysqli->real_escape_string($_POST['consProceso']);
    $cantidadRealizados = $mysqli->real_escape_string($_POST['cantidadRealizadosActualizado']);
    $observacion = $mysqli->real_escape_string($_POST['observacion']);
    $pendientes = $mysqli->real_escape_string($_POST['pendientes']);
    $finalizarProceso = $mysqli->real_escape_string($_POST['finalizarProceso']);
    $procesoSinIniciar = $mysqli->real_escape_string($_POST['procesoSinIniciar']);
    $set = $procesoSinIniciar == "true" ? ", fechainicial=CURDATE() " : "";
    $set.= $finalizarProceso == "true" ? ", fechafinal=CURDATE() " : "";
    $codordendecompraelementos = $mysqli->real_escape_string($_POST['codordendecompraelementos']);
    $codOrdenCompra = $mysqli->real_escape_string($_POST['codOrdenCompra']);


    $updateOrden = "";
    $update = "update ordendecompraprocesos set realizado= (realizado+$cantidadRealizados), fechaterminado=CURDATE(), observacion= CONCAT(observacion, ', $observacion') $set WHERE cons='$consProceso' ";
    if($mysqli->query($update)){
        $selectRealizados = "select sum(p.realizado) != sum(e.cantidad) hayPendientes from ordendecompra o
        inner join ordendecompraelementos e on e.codordendecompra =o.cons
        inner join ordendecompraprocesos p on p.codordendecompraelementos= e.cons where o.cons='$codOrdenCompra';";
        $resultadoPendientes = $mysqli->query($selectRealizados);
        if($resultadoPendientes->num_rows > 0){
            $row = $resultadoPendientes->fetch_array();
            $estadoOrden = $row["hayPendientes"] == 1 ? "En proceso" : "Terminada";
            $updateOrden = "update ordendecompra set estado='$estadoOrden', fechaestado=CURDATE() where cons='$codOrdenCompra'";
            $mysqli->query($updateOrden);
            
        }
        $response["status"] = "ok";
        $response["message"] = "Se han actualizado los datos correctamente";
        $response["consulta"] = $update;
        $response["consulta2"] = $selectRealizados;
        $response["consulta3"] = $updateOrden;
    }else{
        $response["status"] = "error";
        $response["message"] = $mysqli->error;
        $response["consulta"] = $update;
    }

    header('Content-Type: application/json');
    echo json_encode($response);

}

?>