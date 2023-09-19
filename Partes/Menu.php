<style type="text/css">
  /* Estilos para motores Webkit y blink (Chrome, Safari, Opera... )*/

  .navbar::-webkit-scrollbar {
    -webkit-appearance: none;
  }

  .navbar::-webkit-scrollbar:vertical {
    width: 1px;
  }

  .navbar::-webkit-scrollbar-button:increment,
  .navbar::-webkit-scrollbar-button {
    display: none;
  }

  .navbar::-webkit-scrollbar:horizontal {
    height: 10px;
  }

  .navbar::-webkit-scrollbar-thumb {
    background-color: #797979;
    border: 1px solid #f1f2f3;
  }

  .navbar::-webkit-scrollbar-track {
    border-radius: 10px;
  }
</style>
<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
  <!--left-fixed -navigation-->
  <aside class="sidebar-left">
    <nav class="navbar navbar-inverse" style=" overflow-y: scroll;height: 100%;  ">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="Home.php">
          <img src="images/logo.png" class="img-responsive">
        </a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="sidebar-menu">
          <li class="header"></li>
          <li class="treeview">
            <a href="Home.php">
              <i class="fa fa-home"></i> <span>Pagina Principal</span>
            </a>
          </li>


          <li class="treeview">
            <a href="#">
              <i class="fa fa-cogs"></i>
              <span>Parametrizacion</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="AdmAlmacenes.php"><i class="fa fa-sitemap"></i>Bodega</a></li>
              <li><a href="AdmElementos.php"><i class="fa fa-sitemap"></i>Productos</a></li>
              <li><a href="AdmEmpleados.php"><i class="fa fa-sitemap"></i>Empleados</a></li>
              <li><a href="AdmProveedores.php"><i class="fa fa-sitemap"></i>Proveedores</a></li>
              <li><a href="AdmClientes.php"><i class="fa fa-sitemap"></i>Clientes</a></li>
              <li><a href="AdmTallas.php"><i class="fa fa-sitemap"></i>Tallas / Medidas</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-file-text "></i>
              <span>Cotizaciones</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="AdmCotizaciones.php"><i class="fa fa-sitemap"></i>Gestionar</a></li>

            </ul>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-money"></i>
              <span>Compras</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="AdmOrdenesDeCompra.php"><i class="fa fa-sitemap"></i>Ordenes De Compra</a></li>
              <li><a href="AdmOrdenesDeCompraAutorizaciones.php"><i class="fa fa-sitemap"></i>Seguimiento/Control Ordenes</a></li>
              <li><a href="AdmOrdenesDeCompraReportes.php"><i class="fa fa-sitemap"></i>Reportes</a></li>


            </ul>
          </li>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-tasks"></i>
              <span>Operativa</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="AdmAsignacion.php"><i class="fa fa-check-square-o"></i>Asignacion</a></li>
              <li><a href="AdmProduccion.php"><i class="fa fa-tachometer"></i>Produccion</a></li>

            </ul>
          </li>
          <li><a href="AdmOrdenesDeCompraReportes.php"><i class="fa fa-sitemap"></i>Reporte Gerencial</a></li>


        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </nav>
  </aside>
</div>
<!--left-fixed -navigation-->

<!-- header-starts -->
<div class="sticky-header header-section ">
  <div class="header-left">
    <!--toggle button start-->
    <button id="showLeftPush"><i class="fa fa-bars"></i></button>
    <!--toggle button end-->
    <div class="profile_details_left"><!--notifications of menu start -->
      <ul class="nofitications-dropdown">
        <li class="dropdown head-dpdn">


          <script>
            $(document).ready(function() {


              $("#DepaDrop").change(DropDpto);
              $("#proyDrop").change(MproyDrop);



              $('.dropdown-toggle').click(function(e) {
                if ($(document).width() > 768) {
                  e.preventDefault();

                  var url = $(this).attr('href');


                  if (url !== '#') {

                    window.location.href = url;
                  }

                }
              });






              $(document).tooltip({
                position: {
                  my: "justify bottom-5",
                  at: "justify top",
                  using: function(position, feedback) {
                    $(this).css(position);
                    $("<div>")
                      .addClass("arrow")
                      .addClass(feedback.vertical)
                      .addClass(feedback.horizontal)
                      .appendTo(this);
                  }
                }
              });


              function DropDpto() {

                var DepaDrop = $("#DepaDrop").val();
                //alert(DepaDrop);  
                //$('#btna').button('loading');
                $.ajax({
                  data: {
                    DepaDrop: DepaDrop,
                    'DeptoUpdate': 'OK'
                  },
                  url: "Procesamiento/userloginSessions.php",
                  type: "POST",
                  success: function(resp) {
                    location.reload();

                  },
                  error: function(resp) {
                    // $('#btna').button('reset');  

                    alert("Error!", "Error Al Conectarse Al Servidor", "error");
                  }

                });
              }
              //*********************************************************************************************** */
              function MproyDrop() {

                var proyDrop = "" + $("#proyDrop").val();


                $.ajax({
                  data: {
                    proyDrop: proyDrop,
                    'STCK_proyectoUpdate': 'OK',
                  },
                  url: "Procesamiento/userloginSessions.php",
                  type: "POST",
                  success: function(resp) {
                    location.reload();

                  },
                  error: function(resp) {
                    // $('#btna').button('reset');
                    console.log("Error2!", "Error Al Conectarse Al Servidor", "error");
                  }

                });
              }


            });


            var contentHeight = $(".content").height();
            $(document).ready(function() {
              $(".sidebar").css('min-height', contentHeight);
            });
          </script>

          <style>
            .dropdown:hover .dropdown-menu {
              display: block;


            }



            .dropdown-menu>li>a {
              padding: 3px 1px;

            }
          </style>



        </li>
        <?php

        $selectunidad = "";
        $selectunidadview = "";
        if (isset($_SESSION['STCK_proyecto'])) {
          $selectunidad = str_replace(",", "','", $_SESSION["STCK_proyecto"]);
          $selectunidad = "'" . $selectunidad . "'";
          $selectunidadview = str_replace("'',", "", $selectunidad);
          $selectunidadview = str_replace("'", "", $selectunidadview);
        }
        ?>

        <li class="dropdown head-dpdn" style="font-weight: bold;padding-right: 10px">
        </li>
        <li class="dropdown head-dpdn" style="font-weight: bold;padding-right: 10px;font-size: 70%;margin-top: 10px">
        </li>
        <li class="dropdown head-dpdn" style="color: orange;font-weight: bold;font-size: 70%;margin-top: 10px">
        </li>
      </ul>

      <div class="clearfix"> </div>
    </div>
    <!--notification menu end -->



    <div class="clearfix"> </div>
  </div>



  <div class="header-right">

    <div class="profile_details" id="hack">
      <ul id="liAux" class="dropdown profile_details_drop ">
        <li>


          <a href="#" id="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <div class="profile_img">
              <span class="prfil-img"><img src="images/2.jpg" alt=""> </span>
              <div class="user-name">
                <p style="color: #00dd99">En Linea •</p>
                <span style="color: #000;font-weight: bold;"><?php echo  $_SESSION['STCK-USER_NOMBRE']; ?></span>
              </div>
              <!--  <i class="fa fa-angle-down lnr"></i> 
                  <i class="fa fa-angle-up lnr"></i>-->
              <span style="visibility:hidden"> Dropdown </span> <span class="caret" style="border-top-width: 10px;"></span>
          </a>
          <ul class="dropdown-menu">




            <li><a href="../" style="font-weight: bold; padding-top: 10px"><i class="fa fa-sign-out"></i>Cerrar Sesión</a></li>
          </ul>
        </li>
      </ul>
      <div class="clearfix"></div>
    </div>
    </a>



    </li>
    </ul>
  </div>
  <div class="clearfix"> </div>
</div>
<div class="clearfix"> </div>
</div>
<!-- //header-ends -->