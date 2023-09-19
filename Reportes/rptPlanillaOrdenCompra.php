<?php
require_once("../Procesamiento/Conexion.php");


$consOrdenCompra = $mysqli->real_escape_string($_GET["consOrdenCompra"]);

$consultaOrdenCompra = "SELECT o2.ordencompra as ordenCompra,o2.formadepago , o2.fechaentrega, o2.fecharecpecion, c.nombre as cliente, o2.subcliente, 
d.nombre as departamentoEntrega, o2.valor, o2.costo, o2.plazo, o2.observaciones  
FROM ((u682444666_stock.ordendecompra o2 
inner join u682444666_stock.clientes c on o2.codcliente = c.cons ) 
INNER JOIN u682444666_stock.departamentos d ON o2.departamento = d.cons)
WHERE o2.cons = '$consOrdenCompra';";

// echo $consultaOrdenCompra;

$resultOrdenCompra = $mysqli->query($consultaOrdenCompra);


$ordenCompra = "";
$formaPago = "";
$fechaRecepcion = "";
$fechaEntrega = "";
$cliente = "";
$subCliente = "";
$departamentoEntrega = "";
$valorTotal = "";
$costo = "";
$plazoPago = "";
$observaciones = "";
$rowsTable = "<tr><td colspan='6'><p><strong>LA ORDEN NO TIENE PRODUCTOS</strong></p></p></td></tr>";
$costoTotal = 0;
$valorTotal = 0;
$utilidadTotal = 0;

if($resultOrdenCompra->num_rows > 0){
    while($row = $resultOrdenCompra->fetch_array()){
        $ordenCompra = $row["ordenCompra"];
        $formaPago = $row["formadepago"];
        $fechaEntrega = $row["fechaentrega"];
        $fechaRecepcion = $row["fecharecpecion"];
        $cliente = $row["cliente"];
        $subCliente = $row["subcliente"];
        $departamentoEntrega = $row["departamentoEntrega"];
        $valorTotal = $row["valor"];
        $costo = $row["costo"];
        $plazoPago = $row["plazo"];
        $observaciones = $row["observaciones"];
    }
    $consultaProductosOrden = "select e.nombre as producto ,t.nombre as tallaProducto,o.cantidad as cantidadProducto, o.costo as costoProducto, o.valor valorProducto
    FROM ((u682444666_stock.ordendecompraelementos o 
    INNER JOIN u682444666_stock.elementos e ON o.codelemento=e.cons ) 
    INNER JOIN u682444666_stock.tallasdetalle t ON t.cons=o.codtalla)
    WHERE o.codordendecompra ='$consOrdenCompra' 
    ORDER BY e.cons;";

    $resultProductos = $mysqli->query($consultaProductosOrden);
    if($resultProductos->num_rows > 0){
        $rowsTable = "";
        while($row = $resultProductos->fetch_array()){
            $producto = $row["producto"];
            $tallaProducto = $row["tallaProducto"];
            $cantidadProducto = $row["cantidadProducto"];
            $costoProducto = number_format($row["costoProducto"],0 );
            $valorProducto = number_format($row["valorProducto"],0);
            $utilidad = number_format(($row["valorProducto"] - $row["costoProducto"]), 0);
            $costoSubtotal = number_format(($row["costoProducto"] * $cantidadProducto), 0);
            $valorSubtotal = number_format(($row["valorProducto"] * $cantidadProducto), 0);
            $utilidadSubtotal = number_format( (($row["valorProducto"] * $cantidadProducto) - ($row["costoProducto"] * $cantidadProducto)) ,0);

            $costoTotal += ($row["costoProducto"] * $cantidadProducto);
            $valorTotal += ($row["valorProducto"] * $cantidadProducto);
            $utilidadTotal += ($row["valorProducto"] * $cantidadProducto) - ($row["costoProducto"] * $cantidadProducto);

            $rowsTable .= " <tr>
                            <td>$producto</td>
                            <td>$tallaProducto</td>
                            <td>$cantidadProducto</td>
                            <td>$$costoProducto</td>
                            <td>$$valorProducto</td>
                            <td>$$utilidad</td>
                            <td>$$costoSubtotal</td>
                            <td>$$valorSubtotal</td>
                            <td>$$utilidadSubtotal</td>
                            </tr>";
        }
    }

}


$planilla = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ORDEN DE COMPRA '.$ordenCompra.'</title>
    
    <style>
       body{
           font-size: 9px;
       }
        table, td, th, tr{
            border-style: solid;
        }
        td{
            text-align: center;
        }
        
        .izquierda{
            text-align: left !important
        }
        .negrita{
         font-weight: bold;
        
       }
   
        
        
    </style>
</head>
<!-- <body onLoad="window.print()" > -->
<body onLoad="window.print()" >

<!-- ? encabezado de formato -->
<table class="tabla" cellspacing="0" cellpadding="2" border="1" align="center" style="width:100% !important">

    <tr>
        <td width="30%" rowspan="3">
         <img src="../images/logosudicol.png" width="200">
        </td>
        <td width="50%" rowspan="3">
           ORDEN DE COMPRA NO. '.$ordenCompra.'
       </td>
        <td width="30%" rowspan="3">
           <img src="../images/logosudicol.png" width="200">
       </td>
    </tr>


</table>

<!-- ? DATOS DEL CLIENTE SOLICITANTE-->
<table class="tabla" cellspacing="0" cellpadding="2" border="1" align="center" style="width:100% !important">
    
   <tr>
       <td class="izquierda" width="16%">Forma de pago:</td>
       <td class="izquierda" width="16%">'.$formaPago.'</td>

       <td class="izquierda" width="16%">Fecha Recepción:</td>
       <td class="izquierda" width="16%">'.$fechaRecepcion.'</td>

       <td class="izquierda" width="16%">Fecha Entrega:</td>
       <td class="izquierda" width="16%">'.$fechaEntrega.'</td>
   </tr>
   <tr>
       <td class="izquierda" width="16%">Cliente:</td>
       <td class="izquierda" width="16%">'.$cliente.'</td>

       <td class="izquierda" width="16%">SubCliente:</td>
       <td class="izquierda" width="16%">'.$subCliente.'</td>

       <td class="izquierda" width="16%">Depto. Entrega:</td>
       <td class="izquierda" width="16%">'.$departamentoEntrega.'</td>
   </tr>
   <tr>
       <td class="izquierda" width="16%">Valor Total:</td>
       <td class="izquierda" width="16%">$'.number_format($valorTotal).'</td>

       <td class="izquierda" width="16%">Costos:</td>
       <td class="izquierda" width="16%">$'.number_format($costo).'</td>

       <td class="izquierda" width="16%">Plazo de pago:</td>
       <td class="izquierda" width="16%">'.$plazoPago.' Dias</td>
   </tr>
   <tr>
       <td class="izquierda" width="16%" >Observaciones</td>
       <td class="izquierda" width="84%" colspan="5">'.$observaciones.'</td>
   </tr>
   

</table>



<!-- ? TABLA CERTIFICACIONES OBLIGATORIAS ( 100 % )-->
<table class="tabla" cellspacing="0" cellpadding="2" border="1" align="center" style="width:100% !important">
   <tr>
       <th style="text-align:center;background-color:#f2f2f2" >PRODUCTO</th>
       <th style="text-align:center;background-color:#f2f2f2" >TALLA</th>
       <th style="text-align:center;background-color:#f2f2f2" >CANTIDAD</th>
       <th style="text-align:center;background-color:#f2f2f2" >COSTO UNIDAD</th>
       <th style="text-align:center;background-color:#f2f2f2" >VALOR UNIDAD</th>
       <th style="text-align:center;background-color:#f2f2f2" >UTILIDAD UNIDAD</th>
       <th style="text-align:center;background-color:#f2f2f2" >COSTO SUBTOTAL</th>
       <th style="text-align:center;background-color:#f2f2f2" >VALOR SUBTOTAL</th>
       <th style="text-align:center;background-color:#f2f2f2" >UTILIDAD SUBTOTAL</th>
   </tr>
    <!-- valores -->
    '.$rowsTable.'
    <!-- valores -->
    <tr>
       <td colspan="6">TOTAL</td>
       <td>$'.number_format($costoTotal,0).'</td>
       <td>$'.number_format($valorTotal,0).'</td>
       <td>$'.number_format($utilidadTotal,0).'</td>
    </tr>
</table>
</body>
</html>';

echo $planilla;
?>