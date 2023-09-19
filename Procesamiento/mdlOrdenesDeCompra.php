<?php
require_once("Conexion.php");

$option = isset($_POST['option']) ? $_POST['option'] : '';
if (isset($_POST["Ingresar"])) {
  Ingresar($mysqli);
} else if (isset($_POST["Listar"])) {
  Listar($mysqli);
} else if (isset($_POST["Buscar_Datos"])) {
  buscar($mysqli);
} else if (isset($_POST["Actualizar"])) {
  Actualizar($mysqli);
} else if (isset($_POST["AnularOC"])) {
  AnularOC($mysqli);
} else if (isset($_POST["cargarElementosOrden"])) {
  cargarElementosOrden($mysqli);
} else if (isset($_POST["addProducto"])) {
  addProducto($mysqli);
} else if (isset($_POST["quitarElemento"])) {
  quitarElemento($mysqli);
} else if (isset($_POST["Autorizaciones"])) {
  Autorizaciones($mysqli);
} else if (isset($_POST["ResponderAutorizaciones"])) {
  ResponderAutorizaciones($mysqli);
} else if (isset($_POST["Ingresos"])) {
  Ingresos($mysqli);
} else if (isset($_POST["cargarElementosListado"])) {
  cargarElementosListado($mysqli);
} else if (isset($_POST["buscatallasListado"])) {
  buscatallasListado($mysqli);
} else if (isset($_POST["IngresarElementos"])) {
  IngresarElementos($mysqli);
} else if (isset($_POST["cargarIngresos"])) {
  cargarIngresos($mysqli);
} else if (isset($_POST["ReportesCompras"])) {
  ReportesCompras($mysqli);
} else if ($option == "cambiarEstado") cambiarEstado();
else if ($option == "cargarOrdenesPorArchivo") cargarOrdenesPorArchivo();
else if ($option == "addProductosExcel") addProductosExcel();
else if ($option == "consultarDatosOrdenCompra") consultarDatosOrdenCompra();
else if ($option == "actualizarOrdenCompra") actualizarOrdenCompra();

function ReportesCompras($mysqli)
{
  $colores = array(
    "Pendiente" => "#F57C00", //naranja
    "En proceso" => "#1976D2", //azul
    "Terminada" => "#388E3C", //verde
    "Cancelada" => "#757575", //gris oscuro
    "Atrasada" => "#D32F2F" //Rojo
  );
  $iconos = array(
    "En proceso" => "fa fa-tasks",
    "Terminada" => "fa fa-check",
    "Cancelada" => "fa fa-thumbs-down",
  );
  $estadosSiguiente = array(
    "Pendiente" => "En proceso",
    "En proceso" => "Terminada",
  );

  $cliente = $mysqli->real_escape_string($_POST["Proyecto"]);
  $fechaInicio = $mysqli->real_escape_string($_POST["Desde"]);
  $fechaFinal = $mysqli->real_escape_string($_POST["Hasta"]);
  $estadoConsulta = $mysqli->real_escape_string($_POST["estado"]);

  $where = "o.fecharecpecion between '$fechaInicio' and '$fechaFinal' ";
  $where .= $cliente != "Todos" ? " and o.codcliente = '$cliente' " : "";
  $where .= $estadoConsulta != "Todos" ? " and o.estado = '$estadoConsulta' " : "";

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
  $cont = 1;
  $fecha_actual = strtotime(date('Y-m-d'));

  $datos = mysqli_query($mysqli, $consulta);

  $rowsTable = "";
  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_array($datos)) {
      $cons = $row["cons"];
      $consecutivoOrdenCompra = "'$cons'";
			$nombreUsuario = $row["nombreUsuario"]; 
			$fecharealizado = $row["fecharealizado"]; 
			$formadepago = $row["formadepago"];
			$fechaEntrega = $row["fechaentrega"]; 
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

      // $estadoSiguiente = $estadosSiguiente[$estado];
      // $iconoBtn = $iconos[$estadoSiguiente];
      // $colorBtn = $colores[$estadoSiguiente];
      // $onClickCambiarEstado = "cambiarEstado('$cons','$estadoSiguiente');";
      // $onClickCancelar = "cambiarEstado('$cons','Cancelada');";
      // $btnCambiarEstado = ($estado != "Cancelada" && $estado != "Terminada")
      //   ? '<button type="button" class="btn btn-warning btn-circle" style="background-color: ' . $colorBtn . ';padding:7px !important" onclick="' . $onClickCambiarEstado . '"><i class="' . $iconoBtn . '"></i></button>'
      //   : "";
      // $btnCancelar = ($estado != "Terminada" && $estado != "Cancelada")
      //   ? '<button type="button" class="btn btn-warning btn-circle" style="background-color: ' . $colores["Cancelada"] . ';padding:7px !important" onclick="' . $onClickCancelar . '"><i class="fa fa-thumbs-down"></i></button>'
      //   : '';
      // $btns = "$btnCambiarEstado $btnCancelar";
      $btnImprimir = '  <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important" onclick="imprimirPlanilla('.$consecutivoOrdenCompra.');"><i class="fa fa-file-text"></i></button> ';

      $btns  = "$btnImprimir"; 

      $classEstado = ($fecha_actual > strtotime($fechaEntrega) && $estado != "Cancelada" && $estado != "Terminada")
        ? 'style="background-color: ' . $colores["Atrasada"] . ';color:white;text-align:center"'
        : 'style="background-color: ' . $colores[$estado] . ';color:white;text-align:center"';

      $estado = ($fecha_actual > strtotime($fechaEntrega) && $estado != "Cancelada" && $estado != "Terminada")
        ? 'Atrasada'
        : $row[7];
      $rowsTable.= "
      <tr>
				<td>$cont</td>
				<td>$nombreUsuario</td>
				<td>$nombreDepartamento</td>
				<td>$nombreCliente</td>
				<td>$subCliente</td>
				<td>$fecharealizado</td>
				<td>$fecharecpecion</td>
				<td>$fechaEntrega</td>
				<td>$ordencompra</td>
				<td>$formadepago</td>
				<td>$".number_format($valor)."</td>
				<td>$".number_format($costo)."</td>
				<td>$".number_format($rentabilidad)."</td>
				<td>$plazo</td>
				<td $classEstado >$estado</td>
				<td>$fechaestado</td>
				<td>$observaciones</td>
				<td>$solicitud</td>
				<td>$btns</td>
			</tr>";
      $cont++;
    }
  }

  $titleTable = '<tr>
  <th style="background-color:#e1e1e1">CONS</th>  
  <th style="background-color:#e1e1e1">USUARIO</th>  
  <th style="background-color:#e1e1e1">DEPARTAMENTO</th>  
  <th style="background-color:#e1e1e1">CLIENTE</th>  
  <th style="background-color:#e1e1e1">SUBCLIENTE</th>  
  <th style="background-color:#e1e1e1">FECHA REALIZADO </th>  
  <th style="background-color:#e1e1e1">FECHA RECEPCION </th>  
  <th style="background-color:#e1e1e1">FECHA ENTREGA </th>  
  <th style="background-color:#e1e1e1">ORDENCOMPRA </th>  
  <th style="background-color:#e1e1e1">FORMA  PAGO</th>  
  <th style="background-color:#e1e1e1">VALOR </th>  
  <th style="background-color:#e1e1e1">COSTO </th>  
  <th style="background-color:#e1e1e1">RENTABILIDAD</th>  
  <th style="background-color:#e1e1e1">PLAZO</th>  
  <th style="background-color:#e1e1e1">ESTADO </th>  
  <th style="background-color:#e1e1e1">FECHA ESTADO</th>   
  <th style="background-color:#e1e1e1">OBSERVACIONES</th>  
  <th style="background-color:#e1e1e1">SOLICITUD</th> 
  <th style="background-color:#e1e1e1">OPCIONES</th> 
  </tr>';

  $tabla = '
  <div class="overflow:scroll; height:200px; width:80%;" style="overflow-x: scroll; margin: 30px;" >
    <table id="example" class="display dataTable" cellspacing="0" width="80%" style="font-size: 12px; width: 80%;" role="grid" aria-describedby="example_info">
    <thead>
    ' . $titleTable . '
    </thead>
    <tfoot>
    ' . $titleTable . '
    </tfoot>  
    <tbody>
    '.$rowsTable.'

    </tbody>

    
    </table>  
  </div>';

  echo $tabla;
}



function cargarIngresos($mysqli)
{


  $consulta = "SELECT l.cons,c.cons,e.`Nombre`,t.`nombre`,a.nombre,l.fecha,l.usuario,l.cantidad,l.soportelog FROM (((((ordendecompralog l INNER JOIN ordendecompra o ON o.cons=l.codordencompra) INNER JOIN ordendecompraelementos c ON l.`codordencompraelementos`= c.cons) INNER JOIN elementos e ON e.cons=c.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=c.codtalla) INNER JOIN almacenes a ON a.cons=l.`codalmacen`)
WHERE o.cons='" . mysqli_real_escape_string($mysqli, $_POST["codigoOrdenIngreso"]) . "' ORDER BY l.cons DESC ";


  $datos = mysqli_query($mysqli, $consulta);
  $tabla = '<table id="exampleIngresos" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>CODIGO ENTREGA</th>
          <th>CODIGO PRODUCTO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>ALMACEN</th>
          <th>FECHA</th> 
          <th>USUARIO</th> 
          <th>CANTIDAD</th>  
          <th>SOLICITUD</th>  </tr></tr>
        </thead><tfoot>
          <tr>
          <th>CODIGO ENTREGA</th>
          <th>CODIGO PRODUCTO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>ALMACEN</th>
          <th>FECHA</th> 
          <th>USUARIO</th> 
          <th>CANTIDAD</th>
          <th>SOLICITUD</th>   </tr>
        </tfoot>  <tbody> ';


  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_row($datos)) {


      $tabla .= '<tr > 
            <td class="sobretd"  >' . $row[0] . '</td>
            <td class="sobretd"   >' . $row[1] . '</td>
            <td class="sobretd"   >' . $row[2] . '</td>
            <td class="sobretd"   >' . $row[3] . '</td> 
            <td class="sobretd"   >' . $row[4] . '</td>  
            <td class="sobretd"   >' . $row[5] . '</td>  
            <td class="sobretd"   >' . $row[6] . '</td>   
            <td class="sobretd"   >' . $row[7] . '</td>   
            <td class="sobretd"   ><a href="https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/' . $row[8] . '" target="_blank">Descargar</a></td>   
          </tr>';
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}

function IngresarElementos($mysqli)
{

  $ruta = "gs://lumensarchivostemporales/Stock/Ordenes/";
  //  $ruta="./";


  $swx = 0;
  $nombre = "";
  foreach ($_FILES as $key) {
    if ($key['error'] == UPLOAD_ERR_OK) { //Verificamos si se subio correctamente
      $valorext = explode(".", $key['name']);
      $nombre = time() . "." . $valorext[count($valorext) - 1];
      $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
      if (file_exists($ruta . $nombre)) {
        $swx = 1;
      }
    }
  }


  if ($swx == 1) {

    $consulta = "SELECT cantidad,recibidos,cons FROM `ordendecompraelementos`  
          WHERE codordendecompra='" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "'
          AND codelemento='" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "'
          AND codtalla='" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "'  ";


    $datos = mysqli_query($mysqli, $consulta);
    if ($row = mysqli_fetch_row($datos)) {
      $restan = $row[0] - $row[1];
      if ($restan >= $_POST["Cantidad"]) {

        $consulta = " UPDATE ordendecompraelementos SET 
                              recibidos=recibidos+" . $_POST["Cantidad"] . "
                              WHERE codordendecompra='" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "'
                              AND codelemento='" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "'
                              AND codtalla='" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "' ";
        if ($datos = mysqli_query($mysqli, $consulta)) {

          $consulta = " INSERT ordendecompralog(codordencompra,codordencompraelementos,cantidad,usuario,fecha,codalmacen,soportelog) 
                                            VALUES('" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "',
                                            '" . mysqli_real_escape_string($mysqli, $row[2]) . "',
                                            '" . mysqli_real_escape_string($mysqli, $_POST["Cantidad"]) . "',
                                            '" . mysqli_real_escape_string($mysqli, $_POST["Usuario"]) . "',
                                            curdate(),
                                            '" . mysqli_real_escape_string($mysqli, $_POST["Almacen"]) . "' ,
                                            '" . mysqli_real_escape_string($mysqli, $nombre) . "'  )  ";
          if ($datos = mysqli_query($mysqli, $consulta)) {

            $sw = 0;
            $cantold = 0;

            $consulta = "SELECT  cantidad FROM `almacenesinventario`  
                                                      WHERE codalmacen='" . mysqli_real_escape_string($mysqli, $_POST["Almacen"]) . "'
                                                      AND codelemento='" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "'
                                                      AND codtalla='" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "'  ";
            $datos = mysqli_query($mysqli, $consulta);
            if ($row = mysqli_fetch_row($datos)) {

              $cantold = $row[0];
              $consulta = " UPDATE almacenesinventario SET
                                                          cantidad=cantidad+" . $_POST["Cantidad"] . " 
                                                          WHERE codalmacen='" . mysqli_real_escape_string($mysqli, $_POST["Almacen"]) . "'
                                                          AND codelemento='" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "'
                                                          AND codtalla='" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "'  ";
              if ($datos = mysqli_query($mysqli, $consulta)) {
              } else {
                $sw = 1;
                echo 'No se ha podido actualizar inventario, verifique los datos';
              }
            } else {
              $consulta = " INSERT almacenesinventario(codalmacen,codelemento,codtalla,cantidad) 
                                                            VALUES('" . mysqli_real_escape_string($mysqli, $_POST["Almacen"]) . "', 
                                                            '" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "',
                                                            '" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "', 
                                                            '" . mysqli_real_escape_string($mysqli, $_POST["Cantidad"]) . "'   )  ";
              if ($datos = mysqli_query($mysqli, $consulta)) {
              } else {
                $sw = 1;
                echo 'No se ha podido guardar inventario, verifique los datos';
              }
            }

            if ($sw == 0) {
              $consulta = " INSERT almacenesinventariomovimientos(codalmacen,codelemento,codtalla,cantidad,cantidadanterior,codorigen,origen,destino,fecha,usuario) 
                                                                    VALUES('" . mysqli_real_escape_string($mysqli, $_POST["Almacen"]) . "', 
                                                                    '" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "',
                                                                    '" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "', 
                                                                    '" . mysqli_real_escape_string($mysqli, $_POST["Cantidad"]) . "' , 
                                                                    '" . mysqli_real_escape_string($mysqli, $cantold) . "', 
                                                                    '" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "' ,
                                                                    'ORDEN DE COMPRA',
                                                                    'ALMACEN COMPRA',curdate(),
                                                                     '" . mysqli_real_escape_string($mysqli, $_POST["Usuario"]) . "'   )  ";
              if ($datos = mysqli_query($mysqli, $consulta)) {
                echo 'OK';

                $consulta = "SELECT sum(cantidad),sum(recibidos) FROM `ordendecompraelementos`  
                                                                    WHERE codordendecompra='" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "'  ";
                $datos = mysqli_query($mysqli, $consulta);
                if ($row = mysqli_fetch_row($datos)) {
                  if ($row[0] == $row[1]) {
                    $consulta = " UPDATE ordendecompra SET
                                                                            estadoingreso='Cerrado' WHERE cons='" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "' ";
                    $datos = mysqli_query($mysqli, $consulta);
                  }
                }
              } else {
                echo 'No se ha podido guardar inventario de almacen, verifique los datos';
              }
            }
          } else {
            echo 'No se ha podido generar el log, verifique los datos';
          }
        } else {
          echo 'No se ha podido descontar, verifique los datos';
        }
      } else {
        echo ' Cantidad incorrecta, Pedidos: ' . $row[0] . " - Recibidos: " . $row[1] . " - Error: " . $_POST["Cantidad"];
      }
    }
  } else {
    echo 'No se pudo subir el archivo';
  }
}


function buscatallasListado($mysqli)
{

  $consulta = "SELECT t.cons,t.nombre FROM ((`ordendecompraelementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons ) INNER JOIN tallasdetalle t ON t.cons=o.`codtalla`)
WHERE o.`codordendecompra`='" . mysqli_real_escape_string($mysqli, $_POST["CodigoOrden"]) . "'
 AND e.`cons`='" . mysqli_real_escape_string($mysqli, $_POST["codigoElemento"]) . "' ORDER BY t.nombre  ";
  $datos = mysqli_query($mysqli, $consulta);
  echo ' <option  selected="selected" value="">Seleccionar...</option>';
  while ($row = mysqli_fetch_row($datos)) {
    echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
  }
  echo ' </select>';
}

function cargarElementosListado($mysqli)
{

  $consulta = "SELECT DISTINCT e.cons,e.nombre FROM (`ordendecompraelementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons )
          WHERE o.`codordendecompra`='" . mysqli_real_escape_string($mysqli, $_POST["cargarElementosListado"]) . "' ORDER BY e.nombre
          ";
  $datos = mysqli_query($mysqli, $consulta);
  echo ' <option  selected="selected" value="">Seleccionar...</option>';
  while ($row = mysqli_fetch_row($datos)) {
    echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
  }
  echo ' </select>';
}



function Ingresos($mysqli)
{


  if ($_POST["Codigob"] != "") {

    $consulta = " SELECT e.cons,fecharealizado,r.nombre,p.Nombre,fechaentrega,estadoingreso,fechaestado  FROM 
        ((ordendecompra e inner join proyectos p on p.cons=e.codProyecto)  inner join proveedores r on r.cons=e.codproveedor) where e.cons='" . mysqli_real_escape_string($mysqli, $_POST["Codigob"]) . "' and estado='Aprobado'  ORDER BY e.cons desc";
  } else {

    $completa = "";
    if ($_POST["Proyecto"] != "Todos") {
      $completa .= " and p.cons='" . mysqli_real_escape_string($mysqli, $_POST["Proyecto"]) . "'  ";
    }

    if ($_POST["Proveedor"] != "Todos") {
      $completa .= " and e.codproveedor='" . mysqli_real_escape_string($mysqli, $_POST["Proveedor"]) . "'  ";
    }


    $consulta = " SELECT e.cons,fecharealizado,r.nombre,p.Nombre,fechaentrega,estadoingreso,fechaestado  FROM 
        ((ordendecompra e inner join proyectos p on p.cons=e.codProyecto)  inner join proveedores r on r.cons=e.codproveedor)
        where fecharealizado>='" . mysqli_real_escape_string($mysqli, $_POST["Desde"]) . "'  
        AND fecharealizado<='" . mysqli_real_escape_string($mysqli, $_POST["Hasta"]) . "' " . $completa . " and e.estado='Aprobado' ORDER BY e.cons desc";
  }
  $cont = 1;
  $datos = mysqli_query($mysqli, $consulta);
  // <th >Empresa</th>
  $tabla = '<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>#</th>
          <th>CODIGO</th>
          <th>REALIZADO</th> 
          <th>PROVEEDOR</th>
          <th>PROYECTO</th>
          <th>FECHA ENTREGA</th> 
          <th>ESTADO</th>
          <th>FECHA APROBADO</th> 
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
          <th>#</th>
          <th>CODIGO</th>
          <th>REALIZADO</th>
          <th>PROVEEDOR</th>
          <th>PROYECTO</th>
          <th>FECHA ENTREGA</th> 
          <th>ESTADO</th> 
          <th>FECHA APROBADO</th> 
          <th></th></tr>
        </tfoot>  <tbody> ';


  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_row($datos)) {

      $clase = ' style="background-color: #5cb85c;color:white;text-align:center"';
      if ($row[5] == "Abierto") {
        $clase = 'style="background-color: #f0ad4e;color:white;text-align:center"';
      }

      $tabla .= '<tr > 
            <td class="sobretd"  >' . $cont . '</td>
            <td class="sobretd"  >' . $row[0] . '</td>
            <td class="sobretd"   >' . $row[1] . '</td>
            <td class="sobretd"   >' . $row[2] . '</td>
            <td class="sobretd"   >' . $row[3] . '</td>
            <td class="sobretd"   >' . $row[4] . '</td>
            <td  ' . $clase . ' >' . $row[5] . '</td>
            <td class="sobretd"   >' . $row[6] . '</td>
            <td class="sobretd"  >
            <button type="button" class="btn btn-primary btn-circle" style="padding:7px !important"  onclick="Buscar_Datos(' . $row[0] . ');"><i class="fa fa-search"></i></button> 
            </td>
          </tr>';

      $cont++;
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}

function ResponderAutorizaciones($mysqli)
{


  $ruta = "gs://lumensarchivostemporales/Stock/Ordenes/";
  //$ruta="./";


  $swx = 0;
  $nombre = "";
  foreach ($_FILES as $key) {
    if ($key['error'] == UPLOAD_ERR_OK) { //Verificamos si se subio correctamente
      $valorext = explode(".", $key['name']);
      $nombre = time() . "." . $valorext[count($valorext) - 1];
      $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
      if (file_exists($ruta . $nombre)) {
        $swx = 1;
      }
    }
  }


  if ($swx == 1) {
    $consulta = " UPDATE ordendecompra SET 
        soporteResp= '" . mysqli_real_escape_string($mysqli, ($nombre)) . "',
        observacionesResp= '" . mysqli_real_escape_string($mysqli, ($_POST["RespObservaciones"])) . "',
        estado= '" . mysqli_real_escape_string($mysqli, ($_POST["RespEstado"])) . "',
        codproveedor= '" . mysqli_real_escape_string($mysqli, ($_POST["RespProveedores"])) . "',
        fechaestado= curdate(),
        usuarioresp= '" . mysqli_real_escape_string($mysqli, ($_POST["Usuario"])) . "' 
        WHERE cons='" . mysqli_real_escape_string($mysqli, ($_POST["Codigo"])) . "'  ";


    if ($datos = mysqli_query($mysqli, $consulta)) {

      echo 'OK';
    } else {
      echo 'No se ha podido ingresar, verifique los datos';
    }
  } else {
    echo 'No se pudo subir el archivo';
  }
}


function Autorizaciones($mysqli)
{
  $colores = array(
    "Pendiente" => "#F57C00", //naranja
    "En proceso" => "#1976D2", //azul
    "Terminada" => "#388E3C", //verde
    "Cancelada" => "#757575", //gris oscuro
    "Atrasada" => "#D32F2F" //Rojo
  );
  $iconos = array(
    "En proceso" => "fa fa-tasks",
    "Terminada" => "fa fa-check",
    "Cancelada" => "fa fa-thumbs-down",
  );
  $estadosSiguiente = array(
    "Pendiente" => "En proceso",
    "En proceso" => "Terminada",
  );



  if ($_POST["Codigob"] != "") {

    $consulta = " SELECT e.cons,fecharecpecion,c.nombre,fechaentrega,ordencompra,valor,e.cotizaciones,e.estado,e.fechaestado   FROM 
        (ordendecompra e inner join clientes c on c.cons=e.codcliente)    where e.ordencompra='" . mysqli_real_escape_string($mysqli, $_POST["Codigob"]) . "'   ORDER BY e.cons desc";
  } else {

    $completa = "";
    if ($_POST["Proyecto"] != "Todos") {
      $completa = " and c.cons='" . mysqli_real_escape_string($mysqli, $_POST["Proyecto"]) . "'  ";
    }
    $consulta = " SELECT e.cons,fecharecpecion,c.nombre,fechaentrega,ordencompra,valor,e.cotizaciones,e.estado,e.fechaestado   FROM 
        (ordendecompra e inner join clientes c on c.cons=e.codcliente)  
        where fecharecpecion>='" . mysqli_real_escape_string($mysqli, $_POST["Desde"]) . "'  
        AND fecharecpecion<='" . mysqli_real_escape_string($mysqli, $_POST["Hasta"]) . "' " . $completa . "  ORDER BY e.cons desc";
  }
  $cont = 1;
  $fecha_actual = date('Y-m-d');

  $datos = mysqli_query($mysqli, $consulta);
  $tabla = '<div class="Texto-Inquietud" style="display: block; overflow: hidden;Margin: 7px  0px;
    background-color: white;border-radius: 5px;border: solid 1px #ccc;padding:4px">
      <p>
      Cambiar  a Cancelada:  <button type="button" class="btn btn-warning btn-circle" style="background-color: #757575;padding:7px !important" ><i class="fa fa-thumbs-down"></i></button>
      <!--Cambiar  a En proceso: <button type="button" class="btn btn-warning btn-circle" style="background-color: #1976D2;padding:7px !important"><i class="fa fa-tasks"></i></button>
      Cambiar  a Terminada:  <button type="button" class="btn btn-warning btn-circle" style="background-color: #388E3C;padding:7px !important" ><i class="fa fa-check"></i></button> -->
      Imprimir Planilla:     <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important"><i class="fa fa-file-text"></i></button>
       </p>


    </div>
    <table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>#</th>          
           <th>RECEPCION</th>
          <th>CLIENTE</th>
          <th>FECHA ENTREGA</th> 
          <th>ORDEN DE COMPRA</th> 
          <th>VALOR</th> 
          <th>SOLICITUD</th> 
          <th>ESTADO</th>
          <th>FECHA ESTADO</th> 
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
          <th>#</th>
           <th>RECEPCION</th>
          <th>CLIENTE</th>
          <th>FECHA ENTREGA</th> 
          <th>ORDEN DE COMPRA</th> 
          <th>VALOR</th> 
          <th>SOLICITUD</th> 
          <th>ESTADO</th>
          <th>FECHA ESTADO</th> 
          <th></th></tr>
        </tfoot>  <tbody> ';


  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_row($datos)) {
      $consecutivoOrdenCompra = "'".$row[0]."'";

      $fechaEntrega = $row[3];

      $cons = $row[0];
      $estado = $row[7];
      $estadoSiguiente = $estadosSiguiente[$estado];
      $iconoBtn = $iconos[$estadoSiguiente];
      $colorBtn = $colores[$estadoSiguiente];

      $onClickCambiarEstado = "cambiarEstado('$cons','$estadoSiguiente');";
      $onClickCancelar = "cambiarEstado('$cons','Cancelada');";

      $btnCambiarEstado = ($estado != "Cancelada" && $estado != "Terminada")
        ? '<button type="button" class="btn btn-warning btn-circle" style="background-color: ' . $colorBtn . ';padding:7px !important" onclick="' . $onClickCambiarEstado . '"><i class="' . $iconoBtn . '"></i></button>'
        : "";//ESTE BOTON YA NO SE UTILIZA PORQUE AHORA TODO SE HACE AUTOMATICAMENTE

      $btnCancelar = ($estado != "Terminada" && $estado != "Cancelada")
        ? '<button type="button" class="btn btn-warning btn-circle" style="background-color: ' . $colores["Cancelada"] . ';padding:7px !important" onclick="' . $onClickCancelar . '"><i class="fa fa-thumbs-down"></i></button>'
        : '';

      $btnImprimir = '  <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important" onclick="imprimirPlanilla('.$consecutivoOrdenCompra.');"><i class="fa fa-file-text"></i></button> ';

      $btns = "$btnCancelar $btnImprimir";


      $classEstado = ($fecha_actual > $fechaEntrega) && ($estado != "Cancelada" && $estado != "Terminada")
        ? 'style="background-color: ' . $colores["Atrasada"] . ';color:white;text-align:center"'
        : 'style="background-color: ' . $colores[$estado] . ';color:white;text-align:center"';

      $estado = ($fecha_actual > $fechaEntrega) && ($estado != "Cancelada" && $estado != "Terminada")
        ? 'Atrasada'
        : $row[7];
      $archivo = "Sin Adjunto";
      if ($row[6] != "") {
        $archivo = '<a href="Archivos/Soportes/' . $row[6] . '" target="_blank">Descargar</a>  ';
      }

      $tabla .= '<tr > 
            <td class="sobretd"  >' . $cont . '</td>
            <td class="sobretd"   >' . $row[1] . '</td>
            <td class="sobretd"   >' . $row[2] . '</td>
            <td class="sobretd"   >' . $row[3] . '</td>
            <td class="sobretd"   >' . $row[4] . '</td>
            <td class="sobretd"   >$' . number_format($row[5]) . '</td>
            <td class="sobretd"   >' . $archivo . '</td>

            <td  ' . $classEstado . '  >' . $estado . '</td>
            <td class="sobretd"   >' . $row[8] . '</td>
            <td class="sobretd"  >
            ' . $btns . '
            </td>
          </tr>';

      $cont++;
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}


function quitarElemento($mysqli)
{
  $codOrdenDeCompraElementos = $mysqli->real_escape_string($_POST["quitarElemento"]);

  $consultarRealizado = "select SUM(realizado) as cantidadRealizado from ordendecompraprocesos
  where codordendecompraelementos = '$codOrdenDeCompraElementos';";
  $result = $mysqli->query($consultarRealizado);
  if($result->num_rows > 0) {
    $row = $result->fetch_array();
    $cantidadRealizado= $row[0];
    if($cantidadRealizado > 0) echo "No se puede eliminar el elemento porque ya tiene procesos realizados.";
    else{
      $deleteElementos = "delete from ordendecompraelementos where cons = '$codOrdenDeCompraElementos';";
      $deleteProcesos = "delete from ordendecompraprocesos where codordendecompraelementos = '$codOrdenDeCompraElementos';";
      if ($mysqli->query($deleteElementos) && $mysqli->query($deleteProcesos)) echo 'OK';
      else echo "Error al eliminar los procesos o producto. $deleteElementos $deleteProcesos";
    }
  }

  // $consulta = " delete from  ordendecompraelementos WHERE cons='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["quitarElemento"])) . "' ";
  // if ($datos = mysqli_query($mysqli, $consulta)) {
  //   echo 'OK';
  // } else {
  //   echo 'No';
  // }
}


function addProducto($mysqli)
{
  $ordendeCompra = $mysqli->real_escape_string(trim($_POST["codigoOrdenAdd"]));
  $codElemento = $mysqli->real_escape_string(trim($_POST["Elementos"]));
  $codTalla = $mysqli->real_escape_string(trim($_POST["Talla"]));
  $cantidad = $mysqli->real_escape_string(trim($_POST["Cantidad"]));
  $valorProducto = $mysqli->real_escape_string(trim($_POST["valorProducto"]));
  $costoElemento = $mysqli->real_escape_string(trim($_POST["costoElemento"]));
  $procesoElementoArray = $_POST["procesoElemento"];
  $procesos = implode(', ', $procesoElementoArray);

  $consulta = "SELECT cons FROM  ordendecompraelementos    WHERE 
    codordendecompra='$ordendeCompra'  and
    codelemento='$codElemento'  and
    codtalla='$codTalla' ";
  $result =$mysqli->query($consulta); 
  if ($result->num_rows == 0) {

    $insert = " INSERT ordendecompraelementos(
               `codordendecompra`,`codelemento`,
               `codtalla`,
               `cantidad`, valor, costo, procesos) VALUE ( 
               '$ordendeCompra',
               '$codElemento',
               '$codTalla',
               '$cantidad', '$valorProducto', '$costoElemento', '$procesos'  ) ";


    if ($mysqli->query($insert) && mysqli_affected_rows($mysqli) > 0){
      $codOrdenDeCompraElementos = $mysqli->insert_id;
      $values = "";
      foreach ($procesoElementoArray as $proceso) {
        $values .= "('$codOrdenDeCompraElementos', '$proceso', '$cantidad', '0'),";
      }
      $values = rtrim($values, ",");
      $insert = "INSERT INTO ordendecompraprocesos (codordendecompraelementos, proceso, cantidad, realizado) VALUES $values";
      if($mysqli->query($insert)) echo 'OK';
      else echo "No se pudieron guardar los procesos.";
    } else {
      echo 'No se ha podido ingresar, verifique los datos';
    }
  } else {
    echo 'Ya Existe Producto';
  }
}

function cargarElementosOrden($mysqli)
{


  $consulta = "SELECT o.cons,e.nombre,t.nombre,o.`cantidad`,o.recibidos, o.procesos,o.valor,o.costo FROM ((`ordendecompraelementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons ) INNER JOIN tallasdetalle t ON t.cons=o.`codtalla`)
WHERE o.`codordendecompra`='" . mysqli_real_escape_string($mysqli, $_POST["codigoOrdenAdd"]) . "' ORDER BY e.cons ";


  $datos = mysqli_query($mysqli, $consulta);
  $tabla = '<table id="exampleElementosOrden" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>CODIGO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>COSTO</th>
          <th>VALOR</th>
          <th>CANTIDAD</th> 
          <th>RECIBIDOS</th> 
          <th>PENDIENTES</th> 
          <th>PROCESOS</th> 
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
          <th>CODIGO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>COSTO</th>
          <th>VALOR</th>
          <th>CANTIDAD</th> 
          <th>RECIBIDOS</th> 
          <th>PENDIENTES</th> 
          <th>PROCESOS</th> 
          <th></th></tr>
        </tfoot>  <tbody> ';


  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_array($datos)) {
      $procesos = $row['procesos'];

      $btn = "";
      if ($_POST["Tipo"] == "edit") {
        $btn = '  <button type="button" class="btn btn-danger btn-circle" style="padding:7px !important"  onclick="quitarElemento(' . $row[0] . ');"><i class="fa fa-trash"></i></button> ';
      }
      $tabla .= '<tr > 
            <td class="sobretd"  >' . $row[0] . '</td>
            <td class="sobretd"   >' . $row[1] . '</td>
            <td class="sobretd"   >' . $row[2] . '</td>
            <td class="sobretd"   >$' . number_format($row[7]) . '</td>
            <td class="sobretd"   >$' . number_format($row[6]) . '</td>
            <td class="sobretd"   >' . $row[3] . '</td> 
            <td class="sobretd"   >' . $row[4] . '</td> 
            <td class="sobretd"   >' . ($row[3] - $row[4]) . '</td> 
            <td class="sobretd"   >' . $procesos . '</td> 
            <td class="sobretd"  >' . $btn . '</td>
          </tr>';
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}

function AnularOC($mysqli)
{

  $consulta = " DELETE FROM ordendecompra WHERE cons='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["AnularOC"])) . "' ";
  if ($datos = mysqli_query($mysqli, $consulta)) {
    echo 'OK';
  } else {
    echo 'No';
  }
}

function Ingresar($mysqli)
{

  $ordenCompra = $mysqli->real_escape_string(trim($_POST["ordenCompra"]));
  $fechaRecepcion = $mysqli->real_escape_string(trim($_POST["fechaRecepcion"]));
  $valorTotal = $mysqli->real_escape_string(trim($_POST["valorTotal"]));
  $departamento = $mysqli->real_escape_string(trim($_POST["departamentoEntrega"]));
  $costo = $mysqli->real_escape_string(trim($_POST["costos"]));
  $plazoPago = $mysqli->real_escape_string(trim($_POST["plazoPago"]));
  $subCliente = $mysqli->real_escape_string(trim(strtoupper($_POST["subCliente"])));

  $ruta = "../Archivos/Soportes/";
  $swx = 0;
  $nombre = "";
  foreach ($_FILES as $key) {
    if ($key['error'] == UPLOAD_ERR_OK) { //Verificamos si se subio correctamente
      $valorext = explode(".", $key['name']);
      $nombre = time() . "." . $valorext[count($valorext) - 1];
      $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
      if (file_exists($ruta . $nombre)) $swx = 1;
    }
  }


  if (true) {

    $consulta = " INSERT ordendecompra(
             `fecharealizado`,`usuario`,
             `formadepago`,
             `fechaentrega`,
             `codcliente`, 
             `observaciones`,cotizaciones, departamento, valor, ordencompra, fecharecpecion, costo, plazo, subcliente) VALUE (CURDATE(),
             '" . mysqli_real_escape_string($mysqli, ($_POST["Usuario"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["Formadepago"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["FechaEntrega"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["Proyecto"])) . "', 
             '" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Observaciones"])) . "', 
             '" . mysqli_real_escape_string($mysqli, ($nombre)) . "', '$departamento', '$valorTotal', '$ordenCompra',  '$fechaRecepcion', '$costo', '$plazoPago', '$subCliente' ) ";
    if ($datos = mysqli_query($mysqli, $consulta)) {
      $consulta = "  SELECT max(cons) from ordendecompra where   usuario='" . mysqli_real_escape_string($mysqli, $_POST["Usuario"]) . "' ";
      $datos = mysqli_query($mysqli, $consulta);
      if ($row = mysqli_fetch_row($datos)) {
        echo $row[0];
      }
    } else {
      echo 'No se ha podido ingresar, verifique los datos' . $consulta;
    }
  } else {
    echo 'No se pudo subir el archivo';
  }
}

function Actualizar($mysqli)
{


  $consulta = " UPDATE empleados SET 
    Nombre='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Nombre"])) . "',
    Telefono='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Telefono"])) . "',
    Genero='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Genero"])) . "',
    codProyecto='" . mysqli_real_escape_string($mysqli, ($_POST["Proyecto"])) . "',
    codCargo='" . mysqli_real_escape_string($mysqli, ($_POST["Cargo"])) . "',
    Camisa='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Camisa"])) . "',
    Pantalon='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Pantalon"])) . "',
    Zapatos='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Zapatos"])) . "',
    Estado='" . mysqli_real_escape_string($mysqli, ($_POST["Estado"])) . "',
    FechaContratoI='" . mysqli_real_escape_string($mysqli, ($_POST["FechaContratoI"])) . "',
    FechaContratoF='" . mysqli_real_escape_string($mysqli, ($_POST["FechaContratoF"])) . "',
    Labor='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Labor"])) . "',
    Observaciones='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Observaciones"])) . "' 
    WHERE Cedula='" . mysqli_real_escape_string($mysqli, $_POST["codigoc"]) . "' ";
  if ($datos = mysqli_query($mysqli, $consulta)) {
    echo 'OK';
  } else {
    echo 'No se ha podido Actualizar, verifique los datos';
  }
}


function buscar($mysqli)
{

  $arreglo = array();
  $consulta = "SELECT e.cons,e.`usuario`,fecharealizado,formadepago,fechaentrega,p.Nombre,cotizaciones,e.estado,fechaestado,e.Observaciones,r.Nombre,e.observacionesResp,usuarioresp,soporteResp,e.estadoingreso  FROM 
((ordendecompra e INNER JOIN clientes c ON c.cons=e.codcliente)    LEFT JOIN proveedores r ON r.cons=e.`codproveedor`)  WHERE 
  e.cons='" . mysqli_real_escape_string($mysqli, $_POST["Buscar_Datos"]) . "' ";

  $datos = mysqli_query($mysqli, $consulta);
  if (mysqli_num_rows($datos) > 0) {
    $row = mysqli_fetch_row($datos);
    $arreglo[] = $row;
    echo json_encode($arreglo);
  } else {
    echo 'n';
  }
}

function Listar($mysqli)
{


  // $consulta = " SELECT e.cons,fecharealizado,formadepago,c.Nombre,fechaentrega,e.valor,fechaestado,e.fecharecpecion,e.ordencompra,e.cotizaciones,e.usuario  FROM (ordendecompra e inner join clientes c on c.cons=e.codcliente)  
  //         where  e.estado='Pendiente' ORDER BY e.cons desc ";//CONSULTA ANTERIOR
  $consulta = " SELECT e.cons,fecharealizado,formadepago,c.Nombre,fechaentrega,e.valor,fechaestado,e.fecharecpecion,e.ordencompra,e.cotizaciones,e.usuario, SUM(o.cantidad) as cantidadProductos
      FROM ((ordendecompra e inner join clientes c on c.cons=e.codcliente) 
      LEFT JOIN ordendecompraelementos o on e.cons=o.codordendecompra)  
      where  e.estado='Pendiente' GROUP by e.cons ORDER BY e.cons desc; ";
  // echo $consulta;

  $datos = mysqli_query($mysqli, $consulta);
  // <th >Empresa</th>
  $tabla = '<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>#</th>
          <th class="text-center">CODIGO</th>
          <th class="text-center">RECEPCION</th>
          <th class="text-center">CLIENTE</th>
          <th class="text-center">FECHA ENTREGA</th> 
          <th class="text-center">ORDEN DE COMPRA</th> 
          <th class="text-center">VALOR</th> 
          <th class="text-center">SOLICITUD</th> 
          <th class="text-center">PROD. AGREGADOS</th> 
          <th >OPCIONES</th>
          </tr>
        </thead><tfoot>
          <tr>
          <th>#</th>
          <th class="text-center" >CODIGO</th>
          <th class="text-center" >RECEPCION</th>
          <th class="text-center" >CLIENTE</th>
          <th class="text-center" >FECHA ENTREGA</th> 
          <th class="text-center" >ORDEN DE COMPRA</th> 
          <th class="text-center" >VALOR</th>  
          <th class="text-center" >SOLICITUD</th>  
          <th class="text-center" >PROD. AGREGADOS</th>  
          <th>OPCIONES</th>
          </tr>
        </tfoot>  <tbody> ';

  $cont = 1;
  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_row($datos)) {
      $cantidadProductos = $row[11];

      $btn = '';

      $archivo = "Sin Adjunto";
      if ($row[9] != "") {

        $archivo = '<a href="Archivos/Soportes/' . $row[9] . '" target="_blank">Descargar</a>  ';
      }
      $consecutivoOrdenCompra = "'".$row[0]."'";

      if ($row[10] == $_POST["Usuario"]) {
        $btn = '  <button type="button" class="btn btn-warning btn-circle" style="background-color:red;padding:7px !important" onclick="AnularOC(' . $row[0] . ');"><i class="fa fa-trash"></i></button> ';
        $btn .= '  <button type="button" class="btn btn-success btn-circle" style="background-color:#FFA000;padding:7px !important" onclick="mostrarFormuarlioEditarOrdenCompra('.$consecutivoOrdenCompra.');"><i class="fa fa-pencil"></i></button> ';

      }
      $btn .= '  <button type="button" class="btn btn-success btn-circle" style="background-color:#388E3C;padding:7px !important" onclick="mostrarModalCargarElementoExcel('.$consecutivoOrdenCompra.');"><i class="fa fa-upload"></i></button> ';
      $btn .= '  <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important" onclick="imprimirPlanilla('.$consecutivoOrdenCompra.');"><i class="fa fa-file-text"></i></button> ';

      $tabla .= '<tr > 
            <td   >' . $cont . '</td>
            <td class="text-center"  >' . $row[0] . '</td>
            <td class="text-center"   >' . $row[7] . '</td> 
            <td class="text-center"   >' . $row[3] . '</td>
            <td class="text-center"   >' . $row[4] . '</td>
            <td class="text-center"   >' . $row[8] . '</td>
            <td   >$' . number_format($row[5]) . '</td>  
            <td class="text-center"   >' . $archivo . '</td>
            <td class="text-center"   >' . $cantidadProductos . '</td>
            <td class="text-center"  >' . $btn . '
            </td>
          </tr>';
      $cont++;
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}

function cambiarEstado()
{
  global $mysqli;
  $estado = $mysqli->real_escape_string(trim($_POST["estado"]));
  $cons = $mysqli->real_escape_string(trim($_POST["cons"]));
  $consulta = "UPDATE ordendecompra SET estado='$estado', fechaestado=CURDATE() WHERE cons='$cons' ";
  if ($mysqli->query($consulta)) echo "OK";
  else echo "No se ha podido cambiar el estado";
}

function cargarOrdenesPorArchivo()
{
  global $mysqli;
  $usuario = $mysqli->real_escape_string(trim($_POST["usuario"]));
  $ruta = "../Archivos/Temporales/";
  $existeArchivo = false;
  $nombreArchivo = time() . rand(1, 999) . rand(1, 100) . ".xlsx";
  if (isset($_FILES["archivoCargarOrdenesPorArchivo"])) {
    $archivo = $_FILES['archivoCargarOrdenesPorArchivo'];
    $error_archivo = $archivo['error'];
    if ($error_archivo == UPLOAD_ERR_OK) {
      $informacion_archivo = pathinfo($archivo['name']);
      $ruta_destino_archivo = $ruta . $nombreArchivo;
      move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
      $existeArchivo = true;
    }
  } else {
    echo "No se subio el archivo";
  }
  if ($existeArchivo) {


    $rutaTotalAchivo = $ruta . $nombreArchivo;
    include './Excel/simplexlsx.class.php';
    $xlsx = new SimpleXLSX($rutaTotalAchivo);
    $filas = $xlsx->rows();
    // list($num_cols, $num_rows) = $xlsx->dimension(0);
    // unlink($ruta . $nombreArchivo);


    $totalRegistros = count($filas) - 1;

    $mensajesErrores = "";
    $contadorErrores = 0;


    $formasDePago = array(
      "CONTADO" => "CONTADO",
      "CREDITO" => "CREDITO",
      "EFECTIVO" => "EFECTIVO",
      "TRANSFERENCIA" => "TRANSFERENCIA",
      "INMEDIATO" => "INMEDIATO",
      "CORTESIA" => "CORTESIA",
    );
    $plazos = array(
      "8" => "8",
      "15" => "15",
      "30" => "30",
      "35" => "35",
      "45" => "45",
      "60" => "60",
      "90" => "90",
      "120" => "120",
    );

    $contador = 1;

    $arrayClientes = array();
    $arrayDepartamentos = array();

    $contadorGuardados = 0;


    if ($totalRegistros >= 1) {
      $consultaClientes = "SELECT cons, nombre FROM clientes";
      $consultaDepartamentos = "SELECT cons, nombre FROM departamentos";
      $clientes = $mysqli->query($consultaClientes);
      $departamentos = $mysqli->query($consultaDepartamentos);
      while ($clienteBD = $clientes->fetch_array()) {
        $arrayClientes[$clienteBD["nombre"]] = $clienteBD["cons"];
      }
      while ($departamentoBD = $departamentos->fetch_array()) {
        $arrayDepartamentos[$departamentoBD["nombre"]] = $departamentoBD["cons"];
      }

      foreach ($filas as $fila) {
        if ($contador != 1) {

          $guardar = true;
          $formaPago = $mysqli->real_escape_string(strtoupper(trim($fila[0])));
          $fechaRecepcion = $mysqli->real_escape_string(trim($fila[1]));
          $fechaEntrega = $mysqli->real_escape_string(trim($fila[2]));
          $cliente = $mysqli->real_escape_string(strtoupper(trim($fila[3])));
          $subCliente = $mysqli->real_escape_string(strtoupper(trim($fila[4])));
          $ordenCompra = $mysqli->real_escape_string(trim($fila[5]));
          $departamento = $mysqli->real_escape_string(trim($fila[6]));
          $valor = $mysqli->real_escape_string(trim($fila[7]));
          $costo = $mysqli->real_escape_string(trim($fila[8]));
          $plazo = $mysqli->real_escape_string(trim($fila[9]));
          $observaciones = $mysqli->real_escape_string(trim($fila[10]));

          if ($formaPago == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila Nº ' . $contador . ' campo Forma de Pago vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if ($formasDePago[$formaPago] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila Nº ' . $contador . ' campo Forma de Pago No valida, solo se aceptan los siguientes valores: CONTADO, CREDITO, EFECTIVO, TRANSFERENCIA, INMEDIATO, CORTESIA.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($fechaRecepcion == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Fecha Recepcion vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if (strtotime($fechaRecepcion) == false) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Fecha Recepcion No valida recuerda que se debe colocar en el formato YYYY-MM-DD.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($fechaEntrega == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Fecha Entrega vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if (strtotime($fechaEntrega) == false) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Fecha Entrega No valida recuerda que se debe colocar en el formato YYYY-MM-DD.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($cliente == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Cliente vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if ($arrayClientes[$cliente] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Cliente No Existe.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($ordenCompra == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Orden de Compra vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($departamento == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Departamento vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif ($arrayDepartamentos[$departamento] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Departamento No Existe.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($valor == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Valor Orden vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif (!is_numeric($valor)) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Valor Orden Solo acepta numeros enteros.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($costo == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Costo vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif (!is_numeric($costo)) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Costo Solo acepta numeros enteros.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($plazo == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Plazo vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif ($plazos[$plazo] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' campo Plazo solo acepta 8,15,30.35,45,60,90 o 120 .</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($guardar) {
            $consultaGuardar = "INSERT INTO ordendecompra (usuario, formadepago, fechaentrega, codcliente, observaciones,departamento, valor, ordencompra, fecharecpecion, costo, plazo, subcliente, fecharealizado) 
                VALUES ('$usuario', '$formaPago', '$fechaEntrega', '$arrayClientes[$cliente]', '$observaciones', '$arrayDepartamentos[$departamento]', '$valor', '$ordenCompra', '$fechaRecepcion', '$costo', '$plazos[$plazo]', '$subCliente', CURDATE() )";
            if ($mysqli->query($consultaGuardar)) {
              $contadorGuardados++;
            } else {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° ' . $contador . ' No se pudo guardar la orden de compra $consultaGuardar.</span></br>";
            }
          }
        }
        $contador++;
      }
    }
  }
  $response = array();
  $response["archivos"] = $rutaTotalAchivo;
  $response["errores"] = $mensajesErrores;
  $response["totalRegistros"] = $totalRegistros;
  $response["contadorGuardados"] = $contadorGuardados;
  $response["contadorErrores"] = $contadorErrores;
  header('Content-Type: application/json');
  echo json_encode($response);
}
function addProductosExcel()
{
  global $mysqli;
  $codOrdenCompra = $_POST["codOrdenCompra"];
  $arrayProcesos = array();
  $operacion = $_POST["operacion"];
  $response = array();


  
  $mensajesErrores = "";
  $contadorErrores = 0;

  $procesarReemplazo = false;

  if($operacion=="reemplazarProductos"){
    $tieneProductos = true;
    $tieneProcesos = false;
    $consecutivos = "";
    $selectProductosOrden = "select cons from ordendecompraelementos where codordendecompra ='$codOrdenCompra';";
    $resultProductosOrden = $mysqli->query($selectProductosOrden);
    if($resultProductosOrden->num_rows > 0){
      while($row = $resultProductosOrden->fetch_array()){
        $consecutivos.="'".$row["cons"]."',";
        $tieneProductos=true;
      }
      $consecutivos = rtrim($consecutivos, ",");
    }

    $selectCantidadProcesosRealizados = "select SUM(realizado) from ordendecompraprocesos where codordendecompraelementos in ($consecutivos);";
    $resultCantidadProcesosRealizados = $mysqli->query($selectCantidadProcesosRealizados);
    if($resultCantidadProcesosRealizados->num_rows > 0){
      $row = $resultCantidadProcesosRealizados->fetch_array();
      $cantidadProcesosRealizados = $row[0];
      if($cantidadProcesosRealizados==0){
        $deleteProductos = "delete from ordendecompraelementos where codordendecompra ='$codOrdenCompra';";
        $deleteProcesos = "delete from ordendecompraprocesos where codordendecompraelementos in ($consecutivos);";
        // $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $deleteProductos.</span></br>";
        // $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $deleteProcesos.</span></br>";
        if($mysqli->query($deleteProductos) && $mysqli->query($deleteProcesos) ) $procesarReemplazo = true;
      }else{
        $mensajesErrores .= "<span class='alert-danger'>* Error, la orden de compra ya cuenta con procesos realizados.</span></br>";
        $contadorErrores++;
        $procesarReemplazo = false;

      }
    }else{
      $mensajesErrores .= "<span class='alert-danger'>* Error la orden no tiene productos agregados.</span></br>";
      $contadorErrores++;
      $procesarReemplazo = false;

    }

  }
  if($operacion=="adicionarProductos" || $procesarReemplazo){


    $inserts = "";
    $ruta = "../Archivos/Temporales/";
    $existeArchivo = false;
    $nombreArchivo = time() . rand(1, 999) . rand(1, 100) . ".xlsx";
    if (isset($_FILES["archivoAddProductoExcel"])) {
      $archivo = $_FILES['archivoAddProductoExcel'];
      $error_archivo = $archivo['error'];
      if ($error_archivo == UPLOAD_ERR_OK) {
        $informacion_archivo = pathinfo($archivo['name']);
        $ruta_destino_archivo = $ruta . $nombreArchivo;
        move_uploaded_file($archivo['tmp_name'], $ruta_destino_archivo);
        $existeArchivo = true;
      }
    } else {
      echo "No se subio el archivo";
    }
  
  
    
    
  
    if ($existeArchivo) {
  
  
      $rutaTotalAchivo = $ruta . $nombreArchivo;
      include './Excel/simplexlsx.class.php';
      $xlsx = new SimpleXLSX($rutaTotalAchivo);
      $filas = $xlsx->rows();
  
      $totalRegistros = count($filas) - 1;
  
  
      $contador = 1;
  
      $contadorGuardados = 0;
      
  
  
      if ($totalRegistros >= 1) {
        $consultaProcesos = "select nombre from procesos p;";
        $procesos = $mysqli->query($consultaProcesos);
        while ($procesosDB = $procesos->fetch_array()) {
          $arrayProcesos[$procesosDB["nombre"]] = $procesosDB["nombre"];
        }
  
        foreach ($filas as $fila) {
          $valuesInsertProcesos = "";
          $codElemento = "";
          $codTalla = "";
          if ($contador != 1) {
  
            $guardar = true;
            $PRODUCTO = $mysqli->real_escape_string(strtoupper(trim($fila[0])));
            $TALLA = $mysqli->real_escape_string(trim($fila[1]));
            $CANTIDAD = $mysqli->real_escape_string(trim($fila[2]));
            $VALOR = $mysqli->real_escape_string(strtoupper(trim($fila[3])));
            $COSTO = $mysqli->real_escape_string(strtoupper(trim($fila[4])));
            $PROCESOSSTRING =  $mysqli->real_escape_string(strtoupper(strtoupper(trim($fila[5]))));
            $PROCESOSARRAY = explode(',', trim($fila[5]));
            $PROCESOSARRAY = array_map('trim', $PROCESOSARRAY);
            $PROCESOSARRAY = array_map('strtoupper', $PROCESOSARRAY);
            $PROCESOSARRAY = array_unique($PROCESOSARRAY);
  
            if($PRODUCTO == ""){
              $mensajesErrores = "<span class='alert-danger'>* Error Fila N°$contador campo PRODUCTO vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
            if($TALLA == ""){
              $mensajesErrores.= "<span class='alert-danger'>* Error Fila N°$contador campo TALLA vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
            $consultaProductoTalla = "select e.cons as CODELEMENTO , t2.cons as CODTALLA  
            from ((elementos e inner join tallas t on e.codTipoTalla = t.cons )
            INNER JOIN tallasdetalle t2  ON t2.codtalla =t.cons  ) where e.nombre='$PRODUCTO' and t2.nombre='$TALLA' LIMIT 1;";
            $inserts.= $consultaProductoTalla;
            $resultConsulta = $mysqli->query($consultaProductoTalla);
            if($resultConsulta->num_rows > 0){
              $data = $resultConsulta->fetch_array();
              $codElemento = $data["CODELEMENTO"];
              $codTalla = $data["CODTALLA"];
            }else{
              $mensajesErrores.= "<span class='alert-danger'>* Error Fila N°$contador PRODUCTO O TALLA NO EXISTE.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
            if ($PROCESOSSTRING == "") {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo PROCESOS vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
  
            foreach ($PROCESOSARRAY as $proceso) {
              $proceso = strtoupper(trim($proceso));
              if($arrayProcesos[$proceso]==""){
                $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo PROCESO '$proceso' NO EXISTE, verifique que no exista una coma demas.</span></br>";
                $contadorErrores++;
                $guardar = false;
              }
  
            }
  
            if ($CANTIDAD == "") {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo CANTIDAD vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            } elseif (!is_numeric($CANTIDAD)) {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo CANTIDAD Solo acepta numeros enteros.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
            if ($VALOR == "") {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo VALOR vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            } elseif (!is_numeric($VALOR)) {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo VALOR Solo acepta numeros enteros.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
            if ($COSTO == "") {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo COSTO vacio.</span></br>";
              $contadorErrores++;
              $guardar = false;
            } elseif (!is_numeric($COSTO)) {  
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo COSTO Solo acepta numeros enteros.</span></br>";
              $contadorErrores++;
              $guardar = false;
            }
  
  
            if ($guardar) {
  
              
  
  
  
              $consulta = "SELECT cons FROM  ordendecompraelementos    WHERE 
              codordendecompra='$codOrdenCompra'  and
              codelemento='$codElemento'  and
              codtalla='$codTalla' ";
              $result =$mysqli->query($consulta); 
              if ($result->num_rows == 0) {
                $inserts = " INSERT ordendecompraelementos(
                  codordendecompra,codelemento,
                  codtalla,
                  cantidad, valor, costo, procesos) VALUE ( 
                  '$codOrdenCompra',
                  '$codElemento',
                  '$codTalla',
                  '$CANTIDAD', '$VALOR', '$COSTO', '$PROCESOSSTRING'  ) ";
                
                if ($mysqli->query($inserts)) {
                  $codOrdenDeCompraElementos = $mysqli->insert_id;
                  foreach ($PROCESOSARRAY as $proceso) {
                    $proceso = strtoupper(trim($proceso));
                    if($arrayProcesos[$proceso]!=""){
                      $valuesInsertProcesos.="('$codOrdenDeCompraElementos', '$proceso', '$CANTIDAD', '0'),";
                    }
                  }
                  $valuesInsertProcesos = rtrim($valuesInsertProcesos, ",");
                  $insertProcesos = "INSERT INTO ordendecompraprocesos (codordendecompraelementos, proceso, cantidad, realizado) VALUES $valuesInsertProcesos";
                  if($mysqli->query($insertProcesos)) $contadorGuardados++;
                  else{
                    $mensajesErrores.="<span class='alert-danger'>* Error Fila N°$contador No se pudo guardar los procesos $insertProcesos.</span></br";
                    $contadorErrores++;
                  };
                } else {
                  $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador No se pudo guardar la orden de compra $inserts.</span></br>";
                }
              }else{
                $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador El Producto ya se encuentra registrado en la orden de compra $codOrdenCompra.</span></br>";
                $contadorErrores++;
              }
              
            }
          }
          $contador++;
        }
      }
    }
    $response["registros"] = $filas;
    $response["inserts"] = $inserts;
    $response["arrayProcesos"] = $arrayProcesos;
    $response["totalRegistros"] = $totalRegistros;
    $response["contadorGuardados"] = $contadorGuardados;
  }

  $response["errores"] = $mensajesErrores;
  $response["contadorErrores"] = $contadorErrores;
  $response["operacion"] = $operacion;
  header('Content-Type: application/json');
  echo json_encode($response);
}



function consultarDatosOrdenCompra(){
  global $mysqli;
  $codOrdenCompra = $mysqli->real_escape_string(trim($_POST["codOrdenCompra"]));
  $select = "SELECT o.formadepago as Formadepago, 
            o.fecharecpecion as fechaRecepcion, 
            o.fechaentrega as FechaEntrega, 
            codcliente  as Proyecto, 
            o.subcliente as subCliente, 
            o.ordencompra as ordenCompra,
            o.departamento  as departamentoEntrega, 
            o.valor as valorTotal,
            o.costo as costos, 
            o.plazo as plazoPago, 
            o.cotizaciones as archivoSubido,
            o.observaciones as Observaciones
            FROM ordendecompra o inner join clientes c on o.codcliente=c.cons inner join departamentos d on o.departamento = d.cons where o.cons ='$codOrdenCompra' order by o.cons";
  $result = $mysqli->query($select);
  header('Content-Type: application/json');
  echo json_encode($result->fetch_array());
}


function actualizarOrdenCompra()
{
  global $mysqli;
  $codOrdenCompra = $mysqli->real_escape_string(trim($_POST["codOrdenCompra"]));
  $ordenCompra = $mysqli->real_escape_string(trim($_POST["ordenCompra"]));
  $fechaRecepcion = $mysqli->real_escape_string(trim($_POST["fechaRecepcion"]));
  $valorTotal = $mysqli->real_escape_string(trim($_POST["valorTotal"]));
  $departamento = $mysqli->real_escape_string(trim($_POST["departamentoEntrega"]));
  $costo = $mysqli->real_escape_string(trim($_POST["costos"]));
  $plazoPago = $mysqli->real_escape_string(trim($_POST["plazoPago"]));
  $subCliente = $mysqli->real_escape_string(trim(strtoupper($_POST["subCliente"])));
  $usuario = $mysqli->real_escape_string(trim($_POST["Usuario"]));
  $formadepago = $mysqli->real_escape_string(trim($_POST["Formadepago"]));
  $fechaentrega = $mysqli->real_escape_string(trim($_POST["FechaEntrega"]));
  $proyecto = $mysqli->real_escape_string(trim($_POST["Proyecto"]));
  $observaciones = $mysqli->real_escape_string(trim(strtoupper($_POST["Observaciones"])));
  
  
  
  // $nombre

  $ruta = "../Archivos/Soportes/";
  $swx = 0;
  $nombre = "";
  foreach ($_FILES as $key) {
    if ($key['error'] == UPLOAD_ERR_OK) { //Verificamos si se subio correctamente
      $valorext = explode(".", $key['name']);
      $nombre = time() . "." . $valorext[count($valorext) - 1];
      $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
      move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
      if (file_exists($ruta . $nombre)) $swx = 1;
    }
  }


    $set = $nombre!="" ? ", cotizaciones='$nombre' " : "";


    $update = " UPDATE ordendecompra SET 
      formadepago='$formadepago', 
      fechaentrega='$fechaentrega', 
      codcliente='$proyecto', 
      observaciones='$observaciones', 
      departamento='$departamento',
      valor='$valorTotal', 
      ordencompra='$ordenCompra', 
      fecharecpecion='$fechaRecepcion', 
      costo='$costo', 
      plazo='$plazoPago', 
      subcliente='$subCliente'
      $set
      WHERE cons='$codOrdenCompra';";
    if ($mysqli->query($update)) echo "Se ha actualizado la orden de compra correctamente";
    else echo 'No se ha podido ingresar, verifique los datos' . $update;

}





mysqli_close($mysqli);
