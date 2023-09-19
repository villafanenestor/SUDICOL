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
  <title>Empleados | SUDICOL </title>
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
    $(document).ready(function() {

      //$('#example').DataTable();

      $('ul.nav-tabs li a').click(function() {
        var activeTab = $(this).attr('href');
        if (activeTab == '#profile') {
          listar();
          Nuevo();
        }
        if (activeTab == '#help') {
          Nuevo();
        }
      });

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
      $("#FechaContratoI").val(f.getFullYear() + "-" + mes + "-" + dia);
      $("#FechaContratoF").val(f.getFullYear() + "-" + mes + "-" + dia);

      $("#btna").hide();
      $("#btnc").hide();




    });

    function validarCampo(valor, nombreCampo) {
      if (valor == "") {
        swal("Atención!", `${nombreCampo} es obligatorio`, "warning");
        return false;
      }
      return true;  
    } 


    function Guardar() {
      const nombre = $("#nombre").val();
      const usuario = $("#usuario").val();
      const password = $("#password").val();
      const email = $("#email").val();
      const tipo = $("#tipo").val();
      const estado = $("#estado").val();

      if(!validarCampo(nombre, "Nombre")) return false;
      if(!validarCampo(usuario, "Usuario")) return false;
      if(!validarCampo(password, "Password")) return false;
      if(!validarCampo(tipo, "Tipo")) return false;

      $('#btng').button('loading');
      $.ajax({
        data: {
          option: 'guardarEmpleado',
          nombre, usuario, password, email, tipo, estado
        },
        url: "Procesamiento/mdlEmpleados.php",
        type: "POST",
        success: function(resp) {
          if (resp == "OK") {
            swal("Registro Exitoso!", "Se ha registrado   correctamente", "success");
            Nuevo();
            limpiar();
          } else {
            swal("Atención!", resp, "error");
          }
          $('#btng').button('reset');
        },
        error: function(resp) {
          $('#btng').button('reset');
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }

    function actualizar() {
      const nombre = $("#nombre").val();
      const usuario = $("#usuario").val();
      const password = $("#password").val();
      const email = $("#email").val();
      const tipo = $("#tipo").val();
      const estado = $("#estado").val();

      if(!validarCampo(nombre, "Nombre")) return false;
      if(!validarCampo(usuario, "Usuario")) return false;
      if(!validarCampo(password, "Password")) return false;
      if(!validarCampo(tipo, "Tipo")) return false;

      $('#btna').button('loading');
      $.ajax({
        data: {
          option: 'actualizarEmpleado',
          nombre, usuario, password, email, tipo, estado
        },
        url: "Procesamiento/mdlEmpleados.php",
        type: "POST",
        success: function(resp) {

          if (resp == "OK") {
            swal("Registro Exitoso!", "Se ha actualizado correctamente", "success");
            Nuevo();
            limpiar();
          } else {
            swal("Atención!", resp, "error");
          }
          $('#btna').button('reset');
        },
        error: function(resp) {
          $('#btna').button('reset');
          swal("Error!", "Verifique su conexion a internet", "error");
        }

      });
    }

    function listar() {
      var Datos = {
        option: 'listarEmpleados'
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlEmpleados.php",
        type: "POST",
        success: function(resp) {
          $('#Listado').html(resp);
          $('#example').DataTable();
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }

    function Nuevo() {
      limpiar();
    }

    function limpiar() {

      $("#nombre").val("");
      $("#usuario").val("");
      $("#password").val("");
      $("#email").val("");
      $("#tipo").val("");
      $("#estado").val("Activo");

      $("#btna").hide();
      $("#btnc").hide();
      $("#btng").show();
      $('#usuario').attr("disabled", false);

    }

    function buscarEmpleado(usuario) {
      var Datos = {
        option: 'buscarEmpleado',
        usuario: usuario
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlEmpleados.php",
        type: "POST",
        success: function(resp) {
            const {nombre, pass, correo, tipo, estado} = resp;
            $("#nombre").val(nombre);
            $("#usuario").val(usuario);
            $("#password").val(pass);
            $("#email").val(correo);
            $("#tipo").val(tipo);
            $("#estado").val(estado)
            $("#home-tab").click();
            $("#btng").hide();
            $("#btna").show();  
            $("#btnc").show();
            $('#usuario').attr("disabled", true);
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }

  </script>
  <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head>

<body class="cbp-spmenu-push">
  <div class="main-content">





    <?php
    include("Partes/Menu.php");
    ?>
    <!-- main content start-->
    <div id="page-wrapper">
      <div class="main-page">



        <div class="col-md-12  widget-shadow">


          <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">Formulario De Registro </a></li>
            <li role="presentation" class=""><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Reporte - Informe</a></li>
          </ul>


          <div id="myTabContent" class="tab-content scrollbar1">

            <div role="tabpanel" class="tab-pane fade  active in" id="home" aria-labelledby="home-tab">
              <p>
              <div class="panel panel-primary">

                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title"> SUDICOL | Empleados Formulario</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">


                  <div class="single-bottom row" style="">

                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Nombre</p>
                      <input id="nombre" type="text" name="nombre" class="form-control" />
                      <input type="hidden" id="hidenid" />
                    </div>
                    <div class="col-md-4">
                      <p>Usuario</p>
                      <input type="text" id="usuario" name="usuario" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <p>Contraseña</p>
                      <input type="text" id="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Tipo</p>
                      <select id="tipo" name="tipo" class="form-control">
                        <option value="">Seleccionar</option>
                        <option value="administrativo">Administrativo</option>
                        <option value="operativo">Operativo</option>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <p>Correo</p>
                      <input type="email" id="email" name="email" class="form-control">  
                    </div>

                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Estado</p>
                      <select id="estado" name="estado" class="form-control">
                        <option selected="" value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                      </select>
                    </div>

                    <div class="col-md-4">
                      <p><i class="fa fa-id-card"></i> </p>
                      <button id="btng" type="button" class="btn btn-success" onclick="Guardar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>
                      <button id="btna" type="button" class="btn btn-success" onclick="actualizar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Actualizar</button>
                      <button id="btnc" type="button" class="btn btn-danger" onclick="limpiar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cancelar</button>
                    </div>
                  </div>
                </div>
              </div>
              </p>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
              <p>

              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title"> SUDICOL | Empleados Listado</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">
                  <div class="single-bottom row">
                    <div id="Listado" class="single-bottom row" style=""></div>
                  </div>
                </div>
              </div>

              </p>
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