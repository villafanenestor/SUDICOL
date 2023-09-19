<?php
session_start();     
if (empty($_SESSION['STCK-USER_USUARIO'])){      
	echo"<script>document.location=('../');</script>";	
}else{

  require_once("Procesamiento/ConexionStock.php"); 
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Inventario | Lumens</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font-awesome icons CSS -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons CSS-->
<!-- side nav css file -->
<link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css'/>
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

var codigoc='';
var eventog='0';
 $(document).ready(function(){
 
   var f = new Date();
dia="";
if(f.getDate()<10){
dia="0"+f.getDate();
}else{
dia=f.getDate();  
}

mes="";
if((f.getMonth() +1)<10){
mes="0"+(f.getMonth() +1);
}else{
mes=(f.getMonth() +1);  
}    
$("#Desde").val(f.getFullYear()+"-"+mes+"-"+dia);
$("#Hasta").val(f.getFullYear()+"-"+mes+"-"+dia);  


});
 
 

function Generar(){
 
      $('#btnBuscar').button('loading'); 
      if($("#Reporte").val()!="Busqueda") {
       

        var Datos = { 
              "Almacen" : $("#Almacen").val() ,  
              "TipoElemento" : $("#TipoElemento").val() ,  
              "ReporteInventario" : "OK"
               }; 

          $.ajax({
                data:Datos,
                url:"Procesamiento/mdlInventario.php",  
                type:"POST",
              success:function(resp){ 
                $('#Listado').html(resp);  
                $('#exampleListadoReporte').DataTable(); 
                $('#btnBuscar').button('reset');
                 
              },
              error:function(resp){
                swal("Error!","Error Al Conectarse Al Servidor", "error");
              }
           });

      }
}  
function impresionformato(codigo){ 
  Codigo_Registro= codigo; 
 
  var Datos = {  "HistorialInventario" : Codigo_Registro }; 
  $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php",
          type:"POST",
        success:function(resp){ 
 
                $("#ListadoHistorial").html(resp);  
                $('#exampleListadoHistorial').DataTable();     
                $('#myModal').modal('show'); 
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
   });   
}


    </script>
<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head> <style type="text/css">
.modal-dialog {
  width: 90%; 
  padding: 0;
}

.modal-content { 
  border-radius: 0;
}
</style> 

<body class="cbp-spmenu-push">
	<div class="main-content" >







<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">


                      <div class="single-bottom row" style="">
 

                                                        <div id="ListadoHistorial" class="single-bottom row" style=""></div>  
                    
                    <div class="clearfix"> </div>
      </div>
    </div>
  </div>
</div>
</div>


 



		<?php
		include("Partes/Menu.php");
		?>
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">



<div class="col-md-12  widget-shadow">


                        <ul id="myTabs" class="nav nav-tabs" role="tablist">  
                             <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Reporte - Informe</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 
 

                    <div role="tabpanel" class="tab-pane fade  active in" id="profile" aria-labelledby="profile-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Inventario </h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 
 
      
 
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen</p>
      <?php     
        $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="Almacen" name="Almacen" class="form-control">
           <option  selected="selected" value="Todos">Todos</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
                           </div>
 
          
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Tipo Elemento</p>
                            <select id="TipoElemento" name="TipoElemento" class="form-control">
                              <option  selected="selected" value="Todos">Todos</option>
                              <option  value="Dotacion">Dotacion</option>
                              <option value="Herramienta">Herramienta</option>
                              <option value="Consumible">Consumible</option>
                            </select>  
                          </div>  

                          <div class="col-md-1 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="Generar();"  id="btnBuscar">Buscar</button> 
                      </center>
                    </div>
              </div>


                                                <div class="single-bottom row" >
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
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			

			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
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