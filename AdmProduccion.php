<?php
session_start();
if (empty($_SESSION['STCK-USER_USUARIO'])) {
    echo "<script>document.location=('../');</script>";
} else {

    require_once("Procesamiento/Conexion.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Autorizaciones | Sudicol</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="application/x-javascript">
    addEventListener("load", function() {
      setTimeout(hideURLbar, 0);
    }, false);

    function hideURLbar() {
      window.scrollTo(0, 1);
    }
  </script>
  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
  <!-- Custom CSS -->
  <link href="css/style.css" rel='stylesheet' type='text/css' />
  <!-- font-awesome icons CSS -->
  <link href="css/font-awesome.css" rel="stylesheet">
  <!-- //font-awesome icons CSS-->
  <!-- side nav css file -->
  <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
  <!-- //side nav css file -->
  <!-- js-->
  <script src="js/jquery-1.11.1.min.js"></script>
  <script src="js/modernizr.custom.js"></script>

  <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
  <script src="js/funcionesGenericas.js"></script>
  <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
  <!--webfonts-->
  <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
  <script src="js/Chart.js"></script>
  <!-- //chart -->

  <!-- Metis Menu -->
  <script src="js/metisMenu.min.js"></script>
  <script src="js/custom.js"></script>
  <link href="css/custom.css" rel="stylesheet">

  <!--pie-chart --><!-- index page sales reviews visitors pie chart -->
  <script src="js/pie-chart.js" type="text/javascript"></script>
  <script type="text/javascript" language="javascript" src="Ext/jquery.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" href="Ext/jquery.dataTables.css">
  <script type="text/javascript">
    var codigoc = '';
    const usuario = "<?php echo $_SESSION['STCK-USER_USUARIO']; ?>";

    $(document).ready(function() {

      $("#tipoAsignacion").change(cambiarListadoTipoAsignacion);


      var f = new Date();
      dia = "";
      if (f.getDate() < 10) {
        dia = "0" + f.getDate();
      } else {
        dia = f.getDate();
      }

      mes = "";
      if ((f.getMonth() + 1) < 10) {
        mes = "0" + (f.getMonth() + 1);
      } else {
        mes = (f.getMonth() + 1);
      }
      $("#divBusqueda1").hide();
      $("#divBusqueda2").hide();
      $("#Desde").val(f.getFullYear() + "-" + mes + "-" + dia);
      $("#Hasta").val(f.getFullYear() + "-" + mes + "-" + dia);



    });


    function Generar() {
      const cliente = $("#Proyecto").val();
      const procesos = $("#Procesos").val().filter(item => item !== 'Todos')
      $('#btnBuscar').button('loading');
      var datos = {
        procesos,
        usuario,
        cliente,
        option: "listarProduccion"
      };
      console.log(datos)
      $.ajax({
        data: datos,
        url: "Procesamiento/mdlProduccion.php",
        type: "POST",
        success: function(resp) {
          // console.log(resp);
          $('#Listado').html(resp);
          $('#example').DataTable();
          $('#btnBuscar').button('reset');
        },
        error: function(resp) {
          swal("Error!", "Error Al Conectarse Al Servidor", "error");
        }
      });


    }


    function cerrarModal() {
      $('#myModal').modal('hide');
    }


    let consActividadProceso = 0;
    const mostrarModalAsignarProceso = (consProceso) => {
      console.log(consProceso)
      consActividadProceso = consProceso;
      const tipoAsignacion = $("#tipoAsignacion").val();
      if (tipoAsignacion == 'externa') {
        $("div#divUsuarioInterno").hide();
      } else if (tipoAsignacion == 'interna') {
        $("div#divProveedor").hide();
      } else {
        $("div#divUsuarioInterno").hide();
        $("div#divProveedor").hide();
      }

      $('#myModal').modal('show');

    }

    const actualizarRealizados = (consProceso, codordendecompraelementos, codOrdenCompra) => {

      const cantidadRealizadosActualizado = $(`#realizados${consProceso}`).val();
      const maxPermitido =$(`#realizados${consProceso}`).attr('max') *1;
      const cantidadRealizados =$(`#realizados${consProceso}`).attr('min') *1;
      const observacion  = $(`#observacion${consProceso}`).val();
      const pendientes  = $(`#pendientesProceso${consProceso}`).val();
      const finalizarProceso = (pendientes-cantidadRealizadosActualizado)>=1 ? false : true;//ESTA VARIABLE SE UTILIZA PARA SABER CUANDO SE FINALIZA EL PROCESO Y COLOCAR LA FECHA DE FINALIZADO
      const procesoSinIniciar = cantidadRealizados == 0 ? true : false;

    
      // if(cantidadRealizadosActualizado<cantidadRealizados){
      //   swal("Error!", "La cantidad minima es "+cantidadRealizados, "error");
      //   return false;
      // }
      if(cantidadRealizadosActualizado>maxPermitido){
        swal("Error!", "La cantidad maxima es "+(maxPermitido-cantidadRealizados), "error");
        console.log("error 1: ", cantidadRealizadosActualizado, maxPermitido, cantidadRealizados);
        return false;
      }else if(((cantidadRealizadosActualizado*1)+(cantidadRealizados*1))> maxPermitido){
        console.log(" Los actualizados superan al maximo permitido: ", cantidadRealizadosActualizado, maxPermitido, cantidadRealizados);
        console.log(" Los actualizados superan al maximo permitido: ", (cantidadRealizadosActualizado*1)+(cantidadRealizados*1));
        swal("Error!", "La cantidad maxima es "+(maxPermitido-cantidadRealizados), "error");
        return false;
      }
      else if(cantidadRealizadosActualizado==''){
        swal("Error!", "La cantidad no puede ser vacia", "error");
        return false;
      }else if(cantidadRealizadosActualizado<=0){
        swal("Error!", "La cantidad no puede ser menor o igual a 0", "error");
        return false;
      }


      const data = {
        consProceso,
        cantidadRealizadosActualizado,
        option: 'actualizarRealizadosProceso',
        observacion,
        maxPermitido,
        pendientes,
        finalizarProceso,
        cantidadRealizados,
        procesoSinIniciar,
        codordendecompraelementos,
        codOrdenCompra,
      }
      console.log(data);
      // $(`#btnActualizarRealizados${consProceso}`).attr("disabled", true);

      $.ajax({
        data,
        url: "Procesamiento/mdlProduccion.php",
        type: "POST",
        success: function(resp) {
          console.log(resp);

          const {status, message} = resp;

          if (status == 'ok') {
            swal("Exito!", message, "success");
            Generar();
          } else {
            swal("Error!", "Error Al Asignar Proceso, revisa la consola", "error");
            console.log(resp);
          }

          $(`#btnActualizarRealizados${consProceso}`).attr("disabled", false);
        },
        error: function(resp) {
          swal("Error!", "Error Al Conectarse Al Servidor", "error");
          $(`#btnActualizarRealizados${consProceso}`).attr("disabled", false);
        }
      });

    }

    const cambiarListadoTipoAsignacion = () => {
      const tipoAsignacion = $("#tipoAsignacion").val();
      if (tipoAsignacion == 'externa') {
        $("div#divUsuarioInterno").hide();
        $("div#divProveedor").show();
      } else if (tipoAsignacion == 'interna') {
        $("div#divProveedor").hide();
        $("div#divUsuarioInterno").show();
      } else {
        $("div#divUsuarioInterno").hide();
        $("div#divProveedor").hide();
      }
    }
  </script>
  <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head>
<style type="text/css">
  .modal-dialog {
    width: 40%;
    padding: 0;
  }

  .modal-content {
    border-radius: 0;
  }
</style>

<body class="cbp-spmenu-push">
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="background-color: white ">
          <!-- SECCION 1 -->
          <div>
            <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
              <h3 class="panel-title">ASIGNACION DE PROCESO </h3>
            </div>



            <div class="row ">
              <div class="col-md-6">
                <p>Tipo Asignacion</p>
                <select name="tipoAsignacion" id="tipoAsignacion" class="form-control">
                  <option value="">Seleccionar...</option>
                  <option value="interna">Interna</option>
                  <option value="externa">Externa</option>
                </select>
              </div>
              <div class="col-md-6" id="divProveedor">
                <p><i class="fa fa-user-o"></i>Proveedor</p>
                <select id="proveedor" name="proveedor" class="form-control">
                  <option selected="selected" value="">Seleccionar...</option>
                  <?php
                  $consulta = "SELECT cons, nombre FROM proveedores  ORDER BY nombre ";
$datos = mysqli_query($mysqli, $consulta);
while ($row = mysqli_fetch_row($datos)) {
    echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
}
?>
                </select>
              </div>
              <div class="col-md-6" id="divUsuarioInterno">
                <p><i class="fa fa-user-o"></i>Usuarios Internos</p>
                <select id="usuarioInterno" name="usuarioInterno" class="form-control">
                  <option selected="selected" value="">Seleccionar...</option>
                  <?php
$consulta = "SELECT usuario, nombre FROM usuarios u WHERE estado='Activo' ORDER BY nombre ASC ;";
$datos = mysqli_query($mysqli, $consulta);
while ($row = mysqli_fetch_row($datos)) {
    echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
}
?>
                </select>
              </div>

            </div>
            <div class="">
              <br>
              <br>


            </div>



            <div class="col-md-12 espacio4">
              <p></p>
            </div> <!-- Salto de Linea -->
            <div class="clearfix espacio4"></div>
          </div>
          <!-- SECCION 1 -->

          <!-- CERRAR MODAL -->
          <div class="col-md-12 espacio4"><br>
            <center>
              <button type="button" class="btn btn-warning" onclick="cerrarModal();" id="Cerrar">Cancelar</button>
              <button type="button" id="btnAsignarProceso" class="btn btn-success" onclick="asignarProceso();">Asignar</button>
            </center>
          </div>
          <div class="clearfix espacio4"> </div>
          <!-- CERRAR MODAL -->

        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="main-content">













    <?php
    include("Partes/Menu.php");
?>
    <!-- main content start-->
    <div id="page-wrapper">
      <div class="main-page">



        <div class="col-md-12  widget-shadow">


          <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Sudicol - Asignacion</a></li>
          </ul>


          <div id="myTabContent" class="tab-content scrollbar1">


            <div role="tabpanel" class="tab-pane fade  active in" id="profile" aria-labelledby="profile-tab">
              <p>

              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">Sudicol | Autorizaciones Listado</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">




                  <div class="col-md-3">
                    <p><i class="fa fa-user-o"></i>Clientes</p>
                    <?php
                      $consulta = "SELECT cons, nombre FROM clientes  ORDER BY nombre ";
                    $datos = mysqli_query($mysqli, $consulta);
                    echo ' <select id="Proyecto" name="Proyecto" class="form-control">
                              <option  selected="selected" value="Todos">Todos</option>';
                    while ($row = mysqli_fetch_row($datos)) {
                        echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
                    }
                    echo ' </select>';
                    ?>
                  </div>

                  <div class="col-md-3">
                    <p><i class="fa fa-user-o"></i>Procesos</p>
                    <?php
                      $consulta = "SELECT  nombre FROM procesos  ORDER BY nombre ";
                      $datos = mysqli_query($mysqli, $consulta);
                      echo ' <select id="Procesos" name="Procesos" class="form-control" multiple>
                                <option  selected="selected" value="Todos">Todos</option>';
                      while ($row = mysqli_fetch_row($datos)) {
                          echo '<option   value="' . $row[0] . '">' . $row[0] . '</option>';
                      }
                      echo ' </select>';
                      ?>
                  </div>


                  <!-- <div class="col-md-3" id="divResumen1">
                    <p><i class="fa fa-user-o"></i>Desde </p>
                    <input id="Desde" type="date" name="Desde" class="form-control" />
                    <p><i class="fa fa-user-o"></i>Hasta</p>
                    <input id="Hasta" type="date" name="Hasta" class="form-control" />
                  </div> -->

                  <!-- <div class="col-md-3">
                    <p><i class="fa fa-user-o"></i>Estado</p>

                    <select id="estado" name="estado" class="form-control">
                      <option selected="selected" value="Todos">Todos</option>
                      <option  value="Pendientes">Pendientes</option>
                      <option value="En Proceso">En Proceso</option>
                      <option value="Finalizados">Finalizados</option>
                    </select>

                  </div> -->
                  <div class="col-md-1 "><br>
                    <center>
                      <button type="button" class="btn btn-primary" onclick="Generar();" id="btnBuscar">Buscar</button>
                    </center>
                  </div>
                </div>


                <div class="single-bottom row">
                  <div id="Listado" class="single-bottom row" style=""></div>
                </div>
              </div>
            </div>

            </p>
          </div>
        </div>
        <div class="clearfix"> </div>
      </div>













    </div>
  </div>

  <!-- new added graphs chart js-->

  <script src="js/Chart.bundle.js"></script>
  <script src="js/utils.js"></script>

  <!-- new added graphs chart js-->

  <!-- Classie --><!-- for toggle left push menu script -->
  <script src="js/classie.js"></script>
  <script>
    var menuLeft = document.getElementById('cbp-spmenu-s1'),
      showLeftPush = document.getElementById('showLeftPush'),
      body = document.body;

    showLeftPush.onclick = function() {
      classie.toggle(this, 'active');
      classie.toggle(body, 'cbp-spmenu-push-toright');
      classie.toggle(menuLeft, 'cbp-spmenu-open');
      disableOther('showLeftPush');
    };


    function disableOther(button) {
      if (button !== 'showLeftPush') {
        classie.toggle(showLeftPush, 'disabled');
      }
    }
  </script>
  <!-- //Classie --><!-- //for toggle left push menu script -->

  <!-- side nav js -->
  <script src='js/SidebarNav.min.js' type='text/javascript'></script>
  <script>
    $('.sidebar-menu').SidebarNav()
  </script>
  <script src="js/sweetalert.min.js"></script>
  <script src="js/bootstrap.js"> </script>

</body>

</html>