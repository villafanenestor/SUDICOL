<?php
require_once("Conexion.php");

$option = isset($_POST['option']) ? $_POST['option'] : '';
if (isset($_POST["ListarParaAsignaciones"])) {
  ListarParaAsignaciones($mysqli);
}
elseif($option=="asignarProceso") asignarProceso();   

function ListarParaAsignaciones($mysqli)
{

  $estadoConsulta = $_POST["estado"];
  $procesos = count($_POST["procesos"]) >=1 ? implode(',', array_map(function($item) { return "'$item'"; }, $_POST["procesos"])) : "";

  $condicionesEstados = array(
    "Estado"=> "",
    "Pendientes" => " and p.realizado = 0 ",
    "En Proceso" => " (and p.realizado > 0 and p.realizado < p.cantidad) ",
    "Finalizados" => " and p.cantidad = p.realizado ",
    "sinAsignar" => " AND (pr.nombre is null or pr.nombre='') and (u.nombre is null or u.nombre='')  ",
  );

  $completa = "";
  if ($_POST["Proyecto"] != "Todos") {
    $completa = " and c.cons='" . mysqli_real_escape_string($mysqli, $_POST["Proyecto"]) . "'  ";
  }

  $completa .= " $condicionesEstados[$estadoConsulta] ";
  $completa .= $procesos != "" ? "and p.proceso in ($procesos)" : "";
//    $consulta = "  

// SELECT c.nombre,o.`ordencompra`,o.`fecharecpecion`,o.`fechaentrega`,el.nombre,t.nombre,p.`proceso`,p.`cantidad`,p.`realizado`,pr.`nombre`,u.`nombre`
// FROM ordendecompra o INNER JOIN ordendecompraelementos e ON o.cons=e.`codordendecompra` 
// INNER JOIN ordendecompraprocesos p ON p.`codordendecompraelementos`=e.`cons` 
// INNER JOIN elementos el ON el.cons=e.`codelemento` 
// INNER JOIN tallasdetalle t ON t.cons=e.`codtalla`
// INNER JOIN clientes c ON c.cons=o.`codcliente`
// LEFT JOIN proveedores pr ON pr.cons=p.codproveedor
// LEFT JOIN usuarios u ON u.usuario=p.`codproveedor`
// WHERE (NOT o.`estado`='Terminada' AND NOT o.`estado`='Cancelada') 
//         and  fecharecpecion>='" . mysqli_real_escape_string($mysqli, $_POST["Desde"]) . "'  
//         AND fecharecpecion<='" . mysqli_real_escape_string($mysqli, $_POST["Hasta"]) . "' 
//         AND o.cons='182' 
//         " . $completa . "  ORDER BY o.fecharecpecion asc";///CONSULTA ORIGINAL SIN MODIFICACIOENS
   $consulta = " 
    SELECT c.nombre,o.`ordencompra`,o.`fecharecpecion`,o.`fechaentrega`,el.nombre,t.nombre,p.`proceso`,p.`cantidad` as totalARealizar,p.`realizado` as cantidadRealizados,pr.`nombre`,u.`nombre`, p.cons, p.fechainicial, p.fechafinal
    FROM ordendecompra o INNER JOIN ordendecompraelementos e ON o.cons=e.`codordendecompra` 
    INNER JOIN ordendecompraprocesos p ON p.`codordendecompraelementos`=e.`cons` 
    INNER JOIN elementos el ON el.cons=e.`codelemento` 
    INNER JOIN tallasdetalle t ON t.cons=e.`codtalla`
    INNER JOIN clientes c ON c.cons=o.`codcliente`
    LEFT JOIN proveedores pr ON pr.cons=p.codproveedor
    LEFT JOIN usuarios u ON u.usuario=p.`codproveedor`
    WHERE (NOT o.`estado`='Terminada' AND NOT o.`estado`='Cancelada') 
            and  fecharecpecion>='" . mysqli_real_escape_string($mysqli, $_POST["Desde"]) . "'  
            AND fecharecpecion<='" . mysqli_real_escape_string($mysqli, $_POST["Hasta"]) . "' 
            " . $completa . "  ORDER BY o.fecharecpecion asc";


         
  // echo $consulta;

  $datos = mysqli_query($mysqli, $consulta);
  // <th >Empresa</th>
  $tabla = '<table id="example" class="display dataTable" cellspacing="0" width="100%" style="font-size: 12px; width: 100%;" role="grid" aria-describedby="example_info">
        <thead>
          <tr> 
          <th>#</th>
          <th>CLIENTE </th>
          <th>ORDEN </th>
          <th>FECHA RECEPCION  </th>
          <th>FECHA ENTREGA </th>
          <th>PRODUCTO </th>
          <th>TALLA </th>
          <th>PROCESO  </th>
          <th>CANTIDAD </th>
          <th>REALIZADOS</th>
          <th>PENDIENTES</th>
          <th>%</th>
          <th>FECHA INICIADO</th>
          <th>FECHA TERMINADO</th>
          <th>ASIGNACION </th>
          <th>ASIGNADO A</th> 
          <th>OPCIONES</th></tr> 
        </thead><tfoot>
          <tr>
          <th>#</th>
          <th>CLIENTE </th>
          <th>ORDEN </th>
          <th>FECHA RECEPCION  </th>
          <th>FECHA ENTREGA </th>
          <th>PRODUCTO </th>
          <th>TALLA </th>
          <th>PROCESO  </th>
          <th>CANTIDAD </th>
           <th>REALIZADOS</th>
           <th>PENDIENTES</th>
           <th>%</th>
           <th>FECHA INICIADO</th>
           <th>FECHA TERMINADO</th>
           <th>ASIGNACION </th>
           <th>ASIGNADO A</th> 
          <th>OPCIONES</th></tr>
        </tfoot>  <tbody> ';

  $cont = 1;
  if (mysqli_num_rows($datos) > 0) {
    while ($row = mysqli_fetch_array($datos)) {
      $consProceso = $row[11];
      $btn = '  <button type="button" class="btn btn-success btn-circle" style="background-color:#388E3C;padding:7px !important" id="btnMostrarModalAsignarProceso'.$consProceso.'" onclick="mostrarModalAsignarProceso('.$consProceso.');"><i class="fa fa-check"></i></button> ';
      $cantidad = $row['totalARealizar'];
      $cantidadRealizados = $row['cantidadRealizados'];
      $porcentajeRealizados = round(($cantidadRealizados / $cantidad * 100), 0);

      $fechainicial = $row["fechainicial"]; 
      $fechafinal = $row["fechafinal"];



      $asignado = "NO Asignado";
      $asignadoA = "";
      if ($row[9] != "") {
        $asignado = "Externo";
        $asignadoA = $row[9];
      }
      if ($row[10] != "") {
        $asignado = "Sudicol";
        $asignadoA = $row[10];
      }


      $tabla .= '<tr > 
            <td class="sobretd"  >' . $cont . '</td>
            <td class="sobretd"  >' . $row[0] . '</td>
            <td class="sobretd"  >' . $row[1] . '</td>
            <td class="sobretd"  >' . $row[2] . '</td>
            <td class="sobretd"  >' . $row[3] . '</td>
            <td class="sobretd"   >' . $row[4] . '</td> 
            <td class="sobretd"   >' . $row[5] . '</td>
            <td class="sobretd"   >' . $row[6] . '</td>
            <td class="sobretd"   >' . $row[7] . '</td> 
            <td class="sobretd"   >' . $row[8] . '</td> 
            <td class="sobretd"   >' . ($row[7] - $row[8]) . '</td> 
            <td class="sobretd"   >' . $porcentajeRealizados . '%</td>
            <td class="sobretd"   >' . $fechainicial . '</td> 
            <td class="sobretd"   >' . $fechafinal . '</td> 
            <td class="sobretd"   >' . $asignado. '</td> 
            <td class="sobretd"  >' . $asignadoA . '
            </td>       <td class="text-center"  >
            ' . $btn . '
            </td>
          </tr>';
      $cont++;
    }
  }

  $tabla .= '</tbody></table>';

  echo $tabla;
}


function asignarProceso(){
  global $mysqli;
  $usuario = $mysqli->real_escape_string($_POST["usuario"]);
  $consActividadProceso = $mysqli->real_escape_string($_POST["consActividadProceso"]);
  $tipoAsignacion = $mysqli->real_escape_string($_POST["tipoAsignacion"]);//No se utiliza aun.
  $usuarioConsecutivo = $mysqli->real_escape_string($_POST["usuarioConsecutivo"]);

  $update = "update ordendecompraprocesos o set codproveedor='$usuarioConsecutivo', fechaasignado=CURDATE(), usuarioasinado='$usuario' where cons='$consActividadProceso' ;";
  if($mysqli->query($update)){
    echo "ok";
  }else{
    echo "error $update";
  }
}

mysqli_close($mysqli);
