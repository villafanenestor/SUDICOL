<?php
require_once("../Procesamiento/Conexion.php");


$consCotizacion = $mysqli->real_escape_string($_GET["consCotizacion"]);

$consultaOrdenCompra = "SELECT o2.cons as consCotizacion, c.nombre as cliente,
            o2.fecharecpecion as validez,
            plazo as fechaCotizacion, c.Telefono, 
            o2.fechaentrega as plazoEntrega, c.Direccion,o2.formadepago as formaPago, 
            u.nombre as nombreVendedor, o2.valor, o2.observaciones, c.NIT 
            FROM ((u682444666_stock.cotizaciones o2 
            left join u682444666_stock.usuarios u on o2.usuario = u.usuario)
            inner join u682444666_stock.clientes c on o2.codcliente = c.cons )
            WHERE o2.cons = '$consCotizacion';";

// echo $consultaOrdenCompra;

$resultOrdenCompra = $mysqli->query($consultaOrdenCompra);


// $consCotizacion = "";
$cliente = "";
$fechaCotizacion = "";
$nit = "";
$validez = "";
$telefono = "";
$plazoEntrega = "";
$direccion = "";
$formaPago = "";
$nombreVendedor = "";
$observaciones = "";
$cantidadProductos = 0;
$rowsTable = "<tr><td colspan='6'><p><strong>LA ORDEN NO TIENE PRODUCTOS</strong></p></p></td></tr>";
$totalTotal = 0;


if($resultOrdenCompra->num_rows > 0){
    while($row = $resultOrdenCompra->fetch_array()){
        $cliente = $row["cliente"];
        $fechaCotizacion = $row["fechaCotizacion"];
        $nit = $row["NIT"];
        $validez = $row["validez"];
        $telefono = $row["Telefono"];
        $plazoEntrega = $row["plazoEntrega"];
        $direccion = $row["Direccion"];
        $formaPago = $row["formaPago"];
        $nombreVendedor = $row["nombreVendedor"];
        $observaciones = $row["observaciones"];
    }
    $consultaProductosOrden = "SELECT o.codelemento codigoProducto, e.nombre as descripcion, t.nombre as tallaProducto,
    o.cantidad as cantidadProducto, o.valor valorProducto
    FROM ((u682444666_stock.cotizacioneselementos o 
    INNER JOIN u682444666_stock.elementos e ON o.codelemento=e.cons ) 
    INNER JOIN u682444666_stock.tallasdetalle t ON t.cons=o.codtalla)
    WHERE o.codordendecompra ='$consCotizacion' 
    ORDER BY e.cons;";
    $contador = 1;
    $resultProductos = $mysqli->query($consultaProductosOrden);
    if($resultProductos->num_rows > 0){
        $rowsTable = "";
        while($row = $resultProductos->fetch_array()){
            $codigoProducto = $row["codigoProducto"];
            $producto = $row["descripcion"];
            $tallaProducto = $row["tallaProducto"];
            $cantidadProducto = $row["cantidadProducto"];
            $valorProducto = number_format($row["valorProducto"],0);
            $descuento = 0;
            $iva = 0;
            $valorTotal = number_format($row["valorProducto"] * $cantidadProducto, 0);
            $totalTotal += ($row["valorProducto"] * $cantidadProducto);
            $cantidadProductos += $cantidadProducto;

            $rowsTable .= " <tr>
                            <td>$contador</td>
                            <td>$codigoProducto</td>
                            <td>$producto - $tallaProducto</td>
                            <td>$cantidadProducto</td>
                            <td>$$valorProducto</td>
                            <td>$descuento%</td>
                            <td>$iva%</td>
                            <td>$$valorTotal</td>

                            </tr>";
            $contador++;
        }
    }

}


$planilla = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COTIZACION '.$consCotizacion.'</title>
    
    <style>
       body{
           font-size: 9px;
       }
        table, td, th, tr{
            /* border-style: solid; */
        }
        td{
            text-align: center;
        }
        
        .izquierda{
            text-align: left !important
        }
        .text-bold{
         font-weight: bold;
        
       }
       .textoRojo{
        color: red;
       }
   
        
        
    </style>
</head>
<!-- <body onLoad="window.print()" > -->
<body onLoad="window.print()">

<!-- ? encabezado de formato -->
<table class="tabla" cellspacing="0" cellpadding="2" align="center" style="width:100% !important">

    <tr>
        <td width="30%" rowspan="3">
         <img src="../images/logosudicol.png" width="200">
        </td>
        <td width="50%" rowspan="3">
           SUMINISTROS Y DOTACIONES <br>
           INTEGRALES DE COLOMBIA S.A.S. <br>
           NIT: 900.654.772-9 - Regimen Común <br>
           Carrera 33 No. 72-83 Telefono: 301745 Cel: 301-6009979 <br>
           correo: diradministrativo@sudicol.com.co <br>
       </td>
        <td width="30%" rowspan="3">
            <h3>Cotizacion</h3>
            <strong class="textoRojo text-bold">N° '.$consCotizacion.'</strong>
       </td>
    </tr>


</table>

<!-- ? DATOS DEL CLIENTE SOLICITANTE-->
<table class="tabla" cellspacing="0" cellpadding="2" align="center" style="width:100% !important">
    
   <tr>

    <td class="izquierda text-bold" width="25%">Cliente:</td>
    <td class="izquierda" width="25%">'.$cliente.'</td>

    <td class="izquierda text-bold" width="25%">Fecha:</td>
    <td class="izquierda" width="25%">'.$fechaCotizacion.'</td>

   </tr>
   <tr>
    <td class="izquierda text-bold" width="25%">NIT:</td>
    <td class="izquierda" width="25%">'.$nit.'</td>

    <td class="izquierda text-bold" width="25%">Validez:</td>
    <td class="izquierda" width="25%">'.$validez.'</td>
   </tr>
   <tr>
    <td class="izquierda text-bold" width="25%">Telefono/Cel.:</td>
    <td class="izquierda" width="25%">'.$telefono.'</td>

    <td class="izquierda text-bold" width="25%">Plazo de Entrega:</td>
    <td class="izquierda" width="25%">'.$plazoEntrega.'</td>
   </tr>
   <tr>
    <td class="izquierda text-bold" width="25%">Direccion:</td>
    <td class="izquierda" width="25%">'.$direccion.'</td>
    
    <td class="izquierda text-bold" width="25%">Forma de pago:</td>
    <td class="izquierda" width="25%">'.$formaPago.'</td>
   </tr>

   <tr>
    <td class="izquierda text-bold" width="">Nombre Vendedor:</td>
    <td class="izquierda" width="">'.$nombreVendedor.'</td>

   </tr>
   

</table>


<!-- TABLA PRODUCTOS -->
<table class="tabla" cellspacing="0" cellpadding="2" border="1" align="center" style="width:100% !important">
   <tr>
       <th width="4%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>#</strong></th>
       <th width="5%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>CODIGO</strong></th>
       <th width="24%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>DESCRIPCIÓN</strong></th>
       <th width="4%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>CANT</strong></th>
       <th width="5%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>VALOR U.</strong></th>
       <th width="4%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>DTO</strong></th>
       <th width="3%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>IVA</strong></th>
       <th width="15%" style="text-align:center;background-color:#122562; color:#f2f2f2" ><strong>TOTAL</strong></th>
   </tr>
    <!-- valores -->
    '.$rowsTable.'
    <tr>
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
        <!-- <td></td> -->
    </tr>
    <!-- valores -->
    <tr>
       <td colspan="7">TOTAL</td>
       <td >$'.number_format($totalTotal,0).'</td>
    </tr>
</table>
<!-- TABLA PRODUCTOS -->
<br>
<br>
<br>
<br>

<!-- TABLA APRECIADO -->
<table class="tabla" cellspacing="0" cellpadding="2"  align="center" style="width:100% !important">
  <tr>
    <td class="izquierda text-bold" width="50%" colspan="2">'.$observaciones.'</td>
    <td class="izquierda text-bold" width="50%" colspan="1"><p></p></td>
  </tr>
  <tr>
    <td class="izquierda text-bold" width="15%">'.$cantidadProductos.' articulos cotizados</td>
    <td class="izquierda text-bold" width="35%"> FLETES Y/O GASTOS DE TRANSPORTE A OTRAS CIUDADES NO ESTAN INCLUIDAS EN EL PRECIO DE VENTA</td>
    <td class="izquierda text-bold" width="50%" colspan="1"><p></p></td>
  </tr>
</table>
<!-- TABLA APRECIADO -->
<table class="tabla" cellspacing="0" cellpadding="2"  align="center" style="width:100% !important">
    <tr>
        <td><strong>Esperamos Su Respuesta !!!!!</strong></td>
    </tr>
</table>
<hr style="width: 15rem;" align="center" >
<p class="text-bold" align="center">Correo: dcomercial@sudicol.com.co</p>
</body>
</html>';

echo $planilla;
?>