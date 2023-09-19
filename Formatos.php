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
<title>Formatos | Lumens</title>
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
  


});
 
  

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
                             <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Formatos</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 
 

                    <div role="tabpanel" class="tab-pane fade  active in" id="profile" aria-labelledby="profile-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Formatos </h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/IngresoOrdenesDeCompra.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i>Ingreso Ordenes De Compra</a></div>
 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/AsignacionEmpleados.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i>Asignacion A Empleados </a></div> 

  
 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/EnvioRotacion.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i> Envio Rotacion</a></div> 
 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/RecibidoRotacion.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i> Recibido Rotacion</a></div> 
 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/Devolucion.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i> Devolucion</a></div> 
 <div class="icon-box col-md-4 col-sm-4"><a class="agile-icon" href="Archivos/Instructivos/Auditoria.xlsx"><i class="fa fa-file-text" aria-hidden="true"></i> Auditoria</a></div> 
  
            
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