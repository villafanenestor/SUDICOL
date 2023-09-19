<?php
require_once("Conexion.php");

$option = isset($_POST['option']) ? $_POST['option'] : '';
if ($option=="guardarCotizacion") guardarCotizacion($mysqli);
else if ($option=="listarCotizaciones") listarCotizaciones($mysqli);
// } else if (isset($_POST["Buscar_Datos"])) {
//   buscar($mysqli);
// } else if (isset($_POST["Actualizar"])) {
//   Actualizar($mysqli);
// } 
else if ($option=="eliminarCotizacion") eliminarCotizacion(); 
else if (isset($_POST["cargarElementosOrden"])) cargarElementosOrden($mysqli); 
else if ($option=="addProducto") addProducto($mysqli);
// else if (isset($_POST["quitarElemento"])) {
//   quitarElemento($mysqli);
// } else if (isset($_POST["Autorizaciones"])) {
//   Autorizaciones($mysqli);
// } else if (isset($_POST["ResponderAutorizaciones"])) {
//   ResponderAutorizaciones($mysqli);
// } else if (isset($_POST["Ingresos"])) {
//   Ingresos($mysqli);
// } else if (isset($_POST["cargarElementosListado"])) {
//   cargarElementosListado($mysqli);
// } else if (isset($_POST["buscatallasListado"])) {
//   buscatallasListado($mysqli);
// } else if (isset($_POST["IngresarElementos"])) {
//   IngresarElementos($mysqli);
// } else if (isset($_POST["cargarIngresos"])) {
//   cargarIngresos($mysqli);
else if ($option == "cambiarEstado") cambiarEstado();
else if ($option == "cargarOrdenesPorArchivo") cargarOrdenesPorArchivo();
else if ($option == "addProductosExcel") addProductosExcel();





function cargarIngresos($mysqli)
{


  $consulta = "SELECT l.cons,c.cons,e.`Nombre`,t.`nombre`,a.nombre,l.fecha,l.usuario,l.cantidad,l.soportelog FROM (((((cotizacioneslog l INNER JOIN ordendecompra o ON o.cons=l.codordencompra) INNER JOIN cotizacioneselementos c ON l.`codordencompraelementos`= c.cons) INNER JOIN elementos e ON e.cons=c.`codelemento`) INNER JOIN tallasdetalle t ON t.cons=c.codtalla) INNER JOIN almacenes a ON a.cons=l.`codalmacen`)
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

    $consulta = "SELECT cantidad,recibidos,cons FROM `cotizacioneselementos`  
          WHERE codordendecompra='" . mysqli_real_escape_string($mysqli, $_POST["Codigo"]) . "'
          AND codelemento='" . mysqli_real_escape_string($mysqli, $_POST["Elemento"]) . "'
          AND codtalla='" . mysqli_real_escape_string($mysqli, $_POST["Talla"]) . "'  ";


    $datos = mysqli_query($mysqli, $consulta);
    if ($row = mysqli_fetch_row($datos)) {
      $restan = $row[0] - $row[1];
      if ($restan >= $_POST["Cantidad"]) {

        $consulta = " UPDATE cotizacioneselementos SET 
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

                $consulta = "SELECT sum(cantidad),sum(recibidos) FROM `cotizacioneselementos`  
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

  $consulta = "SELECT t.cons,t.nombre FROM ((`cotizacioneselementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons ) INNER JOIN tallasdetalle t ON t.cons=o.`codtalla`)
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

  $consulta = "SELECT DISTINCT e.cons,e.nombre FROM (`cotizacioneselementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons )
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
 
      <p>Cambiar  a En proceso: <button type="button" class="btn btn-warning btn-circle" style="background-color: #1976D2;padding:7px !important"><i class="fa fa-tasks"></i></button>
Cambiar  a Cancelada: 
      <button type="button" class="btn btn-warning btn-circle" style="background-color: #757575;padding:7px !important" ><i class="fa fa-thumbs-down"></i></button>
      Cambiar  a Terminada:
<button type="button" class="btn btn-warning btn-circle" style="background-color: #388E3C;padding:7px !important" ><i class="fa fa-check"></i></button>
Imprimir Planilla: <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important"><i class="fa fa-file-text"></i></button>
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
        : "";

      $btnCancelar = ($estado != "Terminada" && $estado != "Cancelada")
        ? '<button type="button" class="btn btn-warning btn-circle" style="background-color: ' . $colores["Cancelada"] . ';padding:7px !important" onclick="' . $onClickCancelar . '"><i class="fa fa-thumbs-down"></i></button>'
        : '';

      $btnImprimir = '  <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important" onclick="imprimirCotizacion('.$consecutivoOrdenCompra.');"><i class="fa fa-file-text"></i></button> ';

      $btns = "$btnCambiarEstado $btnCancelar $btnImprimir";


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

  $consulta = " delete from  cotizacioneselementos WHERE cons='" . mysqli_real_escape_string($mysqli, strtoupper($_POST["quitarElemento"])) . "' ";
  if ($datos = mysqli_query($mysqli, $consulta)) {
    echo 'OK';
  } else {
    echo 'No';
  }
}


function addProducto($mysqli)
{
  $ordendeCompra = $mysqli->real_escape_string(trim($_POST["codigoOrdenAdd"]));
  $codElemento = $mysqli->real_escape_string(trim($_POST["Elementos"]));
  $codTalla = $mysqli->real_escape_string(trim($_POST["Talla"]));
  $cantidad = $mysqli->real_escape_string(trim($_POST["Cantidad"]));
  $valorProducto = $mysqli->real_escape_string(trim($_POST["valorProducto"]));
  $costoElemento = $mysqli->real_escape_string(trim($_POST["costoElemento"]));
  // $procesoElementoArray = $_POST["procesoElemento"];
  // $procesos = implode(', ', $procesoElementoArray);

  $consulta = "SELECT cons FROM  cotizacioneselementos    WHERE 
    codordendecompra='$ordendeCompra'  and
    codelemento='$codElemento'  and
    codtalla='$codTalla' ";
  $result =$mysqli->query($consulta); 
  if ($result->num_rows == 0) {

    $insert = " INSERT cotizacioneselementos(
               `codordendecompra`,`codelemento`,
               `codtalla`,
               `cantidad`, valor, costo) VALUE ( 
               '$ordendeCompra',
               '$codElemento',
               '$codTalla',
               '$cantidad', '$valorProducto', '$costoElemento'  ) ";


    if ($mysqli->query($insert) && mysqli_affected_rows($mysqli) > 0){
      echo 'OK';
    } else {
      echo 'No se ha podido ingresar, verifique los datos';
    }
  } else {
    echo 'Ya Existe Producto';
  }
}

function cargarElementosOrden($mysqli)
{


  $consulta = "SELECT o.cons,e.nombre,t.nombre,o.`cantidad`,o.recibidos FROM ((`cotizacioneselementos` o INNER JOIN elementos e ON o.`codelemento`=e.cons ) INNER JOIN tallasdetalle t ON t.cons=o.`codtalla`)
WHERE o.`codordendecompra`='" . mysqli_real_escape_string($mysqli, $_POST["codigoOrdenAdd"]) . "' ORDER BY e.cons ";


  $datos = mysqli_query($mysqli, $consulta);
  $tabla = '<table id="exampleElementosOrden" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>CODIGO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>CANTIDAD</th> 
          <th>RECIBIDOS</th> 
          <th>PENDIENTES</th> 
          <th ></th></tr></tr>
        </thead><tfoot>
          <tr>
          <th>CODIGO</th>
          <th>PRODUCTO</th>  
          <th>TALLA</th>
          <th>CANTIDAD</th> 
          <th>RECIBIDOS</th> 
          <th>PENDIENTES</th> 
          <th></th></tr>
        </tfoot>  <tbody> ';


  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_row($datos)) {

      $btn = "";
      if ($_POST["Tipo"] == "edit") {
        $btn = '  <button type="button" class="btn btn-danger btn-circle" style="padding:7px !important"  onclick="quitarElemento(' . $row[0] . ');"><i class="fa fa-trash"></i></button> ';
      }
      $tabla .= '<tr > 
            <td class="sobretd"  >' . $row[0] . '</td>
            <td class="sobretd"   >' . $row[1] . '</td>
            <td class="sobretd"   >' . $row[2] . '</td>
            <td class="sobretd"   >' . $row[3] . '</td> 
            <td class="sobretd"   >' . $row[4] . '</td> 
            <td class="sobretd"   >' . ($row[3] - $row[4]) . '</td> 
            <td class="sobretd"  >' . $btn . '            </td>
          </tr>';
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}

function eliminarCotizacion()
{
  global $mysqli; 
  $codCotizacion = $mysqli->real_escape_string(trim($_POST["codCotizacion"]));

  $deleteProductos = "DELETE FROM cotizacioneselementos WHERE codordendecompra ='$codCotizacion' ";
  $deleteCotizacion = " DELETE FROM cotizaciones WHERE cons='$codCotizacion' ";
  if ($mysqli->query($deleteProductos) && $mysqli->query($deleteCotizacion) ){
    echo 'OK';
  } else {
    echo 'No';
  }
}

function guardarCotizacion($mysqli)
{

  // $ordenCompra = $mysqli->real_escape_string(trim($_POST["ordenCompra"]));
  $fechaRecepcion = $mysqli->real_escape_string(trim($_POST["fechaRecepcion"]));
  $fechaCotizacion = $mysqli->real_escape_string(trim($_POST["fechaCotizacion"]));
  $valorTotal = $mysqli->real_escape_string(trim($_POST["valorTotal"]));
  $departamento = $mysqli->real_escape_string(trim($_POST["departamentoEntrega"]));
  // $costo = $mysqli->real_escape_string(trim($_POST["costos"]));
  // $plazoPago = $mysqli->real_escape_string(trim($_POST["plazoPago"]));
  // $subCliente = $mysqli->real_escape_string(trim(strtoupper($_POST["subCliente"])));

  // $ruta = "../Archivos/Soportes/";
  // $swx = 0;
  // $nombre = "";
  // foreach ($_FILES as $key) {
  //   if ($key['error'] == UPLOAD_ERR_OK) { //Verificamos si se subio correctamente
  //     $valorext = explode(".", $key['name']);
  //     $nombre = time() . "." . $valorext[count($valorext) - 1];
  //     $temporal = $key['tmp_name']; //Obtenemos el nombre del archivo temporal
  //     move_uploaded_file($temporal, $ruta . $nombre); //Movemos el archivo temporal a la ruta especificada  
  //     if (file_exists($ruta . $nombre)) $swx = 1;
  //   }
  // }


  if (true) {

    $consulta = " INSERT cotizaciones(
             `fecharealizado`,`usuario`,
             `formadepago`,
             `fechaentrega`,
             `codcliente`, 
             `observaciones`, departamento, valor, fecharecpecion, plazo) VALUE (CURDATE(),
             '" . mysqli_real_escape_string($mysqli, ($_POST["Usuario"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["Formadepago"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["FechaEntrega"])) . "',
             '" . mysqli_real_escape_string($mysqli, ($_POST["Proyecto"])) . "', 
             '" . mysqli_real_escape_string($mysqli, strtoupper($_POST["Observaciones"])) . "', 
             '$departamento', '$valorTotal','$fechaRecepcion', '$fechaCotizacion' ) ";
    if ($datos = mysqli_query($mysqli, $consulta)) {
      $consulta = "  SELECT max(cons) from cotizaciones where   usuario='" . mysqli_real_escape_string($mysqli, $_POST["Usuario"]) . "' ";
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
((cotizaciones e INNER JOIN clientes c ON c.cons=e.codcliente)    LEFT JOIN proveedores r ON r.cons=e.`codproveedor`)  WHERE 
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

function listarCotizaciones($mysqli)
{


  $consulta = " SELECT e.cons,fecharealizado,formadepago,c.Nombre,fechaentrega as plazoEntrega,e.valor,fechaestado,e.fecharecpecion,e.ordencompra,e.cotizaciones,e.usuario,
    SUM(o.cantidad) as cantidadProductos, e.plazo as fechaCotizacion
      FROM ((cotizaciones e inner join clientes c on c.cons=e.codcliente) 
      LEFT JOIN cotizacioneselementos o on e.cons=o.codordendecompra)  
      where  e.estado='Pendiente' GROUP by e.cons ORDER BY e.cons desc; ";


  $datos = mysqli_query($mysqli, $consulta);
  // echo $consulta;
  $tabla = '<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>#</th>
          <th class="text-center" >N° COTIZACION </th>
          <th class="text-center" >CLIENTE</th>
          <th class="text-center" >FECHA COTIZACION</th>
          <th class="text-center" >PLAZO ENTREGA</th>  
          <th class="text-center" >VALIDEZ</th>  
          <th class="text-center" >VALOR</th>  
          <th class="text-center" >PROD. AGREGADOS</th> 
          <th>OPCIONES</th>

          </tr>
        </thead><tfoot>
          <tr>
          <th>#</th>
          <th class="text-center" >N° COTIZACION </th>
          <th class="text-center" >CLIENTE</th>
          <th class="text-center" >FECHA COTIZACION</th>
          <th class="text-center" >PLAZO ENTREGA</th>  
          <th class="text-center" >VALIDEZ</th>  
          <th class="text-center" >VALOR</th>  
          <th class="text-center" >PROD. AGREGADOS</th>  
          <th>OPCIONES</th>
          </tr>
        </tfoot>  <tbody> ';

  $cont = 1;
  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_array($datos)) {
      $noCotizacion = $row[0];
      $cliente = $row[3];
      $fechaCotizacion = $row["fechaCotizacion"];
      $plazoEntrega = $row["plazoEntrega"];
      $validez = $row["fecharecpecion"];
      $valor = number_format($row[5]);
      $cantidadProductos = $row["cantidadProductos"];
      $btn = '';

      $archivo = "Sin Adjunto";
      if ($row[9] != "") {

        $archivo = '<a href="Archivos/Soportes/' . $row[9] . '" target="_blank">Descargar</a>  ';
      }

      if ($row[10] == $_POST["Usuario"]) {
        $btn = '  <button type="button" class="btn btn-warning btn-circle" style="background-color:red;padding:7px !important" onclick="anularCotizacion(' . $row[0] . ');"><i class="fa fa-trash"></i></button> ';
      }
      $consecutivoOrdenCompra = "'".$row[0]."'";
      $btn .= '  <button type="button" class="btn btn-success btn-circle" style="background-color:#388E3C;padding:7px !important" onclick="mostrarModalCargarElementoExcel('.$consecutivoOrdenCompra.');"><i class="fa fa-upload"></i></button> ';
      $btn .= '  <button type="button" class="btn btn-success btn-circle" style="background-color:#00206d;padding:7px !important" onclick="imprimirCotizacion('.$consecutivoOrdenCompra.');"><i class="fa fa-file-text"></i></button> ';

      $tabla .= '<tr > 
            <td   >' . $cont . '</td>
            <td class="text-center"   >' .$noCotizacion . '</td>
            <td class="text-center"   >' .$cliente  . '</td> 
            <td class="text-center"   >' .$fechaCotizacion  . '</td>
            <td class="text-center"   >' .$plazoEntrega  .'</td>
            <td class="text-center"  >$' .$validez  . '</td>  
            <td class="text-center"   >' .$valor  .'</td>
            <td class="text-center"   >' .$cantidadProductos  .'</td>
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
  $consulta = "UPDATE cotizaciones SET estado='$estado', fechaestado=CURDATE() WHERE cons='$cons' ";
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
          $fechaCotizacion = $mysqli->real_escape_string(trim($fila[1]));
          $plazoEntrega = $mysqli->real_escape_string(trim($fila[2]));
          $validez = $mysqli->real_escape_string(trim($fila[3]));
          $cliente = $mysqli->real_escape_string(strtoupper(trim($fila[4])));
          // $subCliente = $mysqli->real_escape_string(strtoupper(trim($fila[4])));//Antiguo
          // $ordenCompra = $mysqli->real_escape_string(trim($fila[5]));//Antiguo
          $departamento = $mysqli->real_escape_string(trim($fila[5]));
          $valor = $mysqli->real_escape_string(trim($fila[6]));
          // $costo = $mysqli->real_escape_string(trim($fila[8]));//Antiguo
          $observaciones = $mysqli->real_escape_string(trim($fila[7]));

          if ($formaPago == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila Nº $contador campo Forma de Pago vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if ($formasDePago[$formaPago] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila Nº $contador campo Forma de Pago No valida, solo se aceptan los siguientes valores: CONTADO, CREDITO, EFECTIVO, TRANSFERENCIA, INMEDIATO, CORTESIA.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($fechaCotizacion == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Fecha Cotizacion vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if (strtotime($fechaCotizacion) == false) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Fecha Cotizacion No valida recuerda que se debe colocar en el formato YYYY-MM-DD.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($validez == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Validez vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if (strtotime($validez) == false) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Validez No valida recuerda que se debe colocar en el formato YYYY-MM-DD.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($plazoEntrega == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Plazo De Entrega vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if (strtotime($plazoEntrega) == false) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Plazo De Entrega No valida recuerda que se debe colocar en el formato YYYY-MM-DD.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
    
          if ($cliente == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Cliente vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } else if ($arrayClientes[$cliente] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Cliente No Existe.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($departamento == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Departamento vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif ($arrayDepartamentos[$departamento] == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Departamento No Existe.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }
          if ($valor == "") {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Valor Orden vacio.</span></br>";
            $contadorErrores++;
            $guardar = false;
          } elseif (!is_numeric($valor)) {
            $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador campo Valor Orden Solo acepta numeros enteros.</span></br>";
            $contadorErrores++;
            $guardar = false;
          }

          if ($guardar) {
            $consultaGuardar = "INSERT INTO cotizaciones (usuario, formadepago, fechaentrega, codcliente, observaciones,departamento, valor, fecharecpecion, plazo, fecharealizado) 
                VALUES ('$usuario', '$formaPago', '$plazoEntrega', '$arrayClientes[$cliente]', '$observaciones', '$arrayDepartamentos[$departamento]', '$valor','$validez', '$fechaCotizacion', CURDATE() )";
            if ($mysqli->query($consultaGuardar)) {
              $contadorGuardados++;
            } else {
              $mensajesErrores .= "<span class='alert-danger'>* Error Fila N° $contador No se pudo guardar la orden de compra $consultaGuardar.</span></br>";
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

    $mensajesErrores = "";
    $contadorErrores = 0;

    $contador = 1;

    $contadorGuardados = 0;


    if ($totalRegistros >= 1) {
      // $consultaProcesos = "select nombre from procesos p;";
      // $procesos = $mysqli->query($consultaProcesos);
      // while ($procesosDB = $procesos->fetch_array()) {
      //   $arrayProcesos[$procesosDB["nombre"]] = $procesosDB["nombre"];
      // }

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
          // $PROCESOSSTRING =  $mysqli->real_escape_string(strtoupper(strtoupper(trim($fila[5]))));
          // $PROCESOSARRAY = explode(',', trim($fila[5]));
          // $PROCESOSARRAY = array_map('trim', $PROCESOSARRAY);
          // $PROCESOSARRAY = array_map('strtoupper', $PROCESOSARRAY);
          // $PROCESOSARRAY = array_unique($PROCESOSARRAY);

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
          // if ($PROCESOSSTRING == "") {
          //   $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo PROCESOS vacio.</span></br>";
          //   $contadorErrores++;
          //   $guardar = false;
          // }

          // foreach ($PROCESOSARRAY as $proceso) {
          //   $proceso = strtoupper(trim($proceso));
          //   if($arrayProcesos[$proceso]==""){
          //     $mensajesErrores .= "<span class='alert-danger'>* Error Fila N°$contador campo PROCESO '$proceso' NO EXISTE, verifique que no exista una coma demas.</span></br>";
          //     $contadorErrores++;
          //     $guardar = false;
          //   }

          // }

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
            $consulta = "SELECT cons FROM  cotizacioneselementos    WHERE 
            codordendecompra='$codOrdenCompra'  and
            codelemento='$codElemento'  and
            codtalla='$codTalla' ";
            $result =$mysqli->query($consulta); 
            if ($result->num_rows == 0) {
              $inserts = " INSERT cotizacioneselementos(
                codordendecompra,codelemento,
                codtalla,
                cantidad, valor, costo) VALUE ( 
                '$codOrdenCompra',
                '$codElemento',
                '$codTalla',
                '$CANTIDAD', '$VALOR', '$COSTO'  ) ";
              
              if ($mysqli->query($inserts)) {
                // $codcotizacioneselementos = $mysqli->insert_id;
                // foreach ($PROCESOSARRAY as $proceso) {
                //   $proceso = strtoupper(trim($proceso));
                //   // if($arrayProcesos[$proceso]!=""){
                //   //   $valuesInsertProcesos.="('$codcotizacioneselementos', '$proceso', '$CANTIDAD', '0'),";
                //   // }
                // }
                // $valuesInsertProcesos = rtrim($valuesInsertProcesos, ",");
                // $insertProcesos = "INSERT INTO ordendecompraprocesos (codcotizacioneselementos, proceso, cantidad, realizado) VALUES $valuesInsertProcesos";
                // if($mysqli->query($insertProcesos)) $contadorGuardados++;
                // else{
                //   $mensajesErrores.="<span class='alert-danger'>* Error Fila N°$contador No se pudo guardar los procesos $insertProcesos.</span></br";
                //   $contadorErrores++;
                // };
                $contadorGuardados++;
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
  $response = array();
  $response["registros"] = $filas;
  $response["inserts"] = $inserts;
  $response["arrayProcesos"] = $arrayProcesos;
  $response["errores"] = $mensajesErrores;
  $response["totalRegistros"] = $totalRegistros;
  $response["contadorGuardados"] = $contadorGuardados;
  $response["contadorErrores"] = $contadorErrores;
  header('Content-Type: application/json');
  echo json_encode($response);
}





mysqli_close($mysqli);
