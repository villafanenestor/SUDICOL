<?php 
session_start(); 
if (empty($_SESSION['STCK-USER_USUARIO'])){      
  echo"<script>document.location=('../');</script>";  
}else{
  
  require_once("Procesamiento/Conexion.php"); 
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Lumens | Bienvenido!</title>
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
 <script type="text/javascript">

        $(document).ready(function () {


            $('#demo-pie-1').pieChart({
                barColor: '#2dde98',
                trackColor: '#eee',
                lineCap: 'round',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
            $('#demo-pie-2').pieChart({
                barColor: '#8e43e7',
                trackColor: '#eee',
                lineCap: 'butt',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });
            $('#demo-pie-3').pieChart({
                barColor: '#ffc168',
                trackColor: '#eee',
                lineCap: 'square',
                lineWidth: 8,
                onStep: function (from, to, percent) {
                    $(this.element).find('.pie-value').text(Math.round(percent) + '%');
                }
            });           
        });

    </script>
<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">

		<?php
		include("Partes/Menu.php");

$agentes=0; /*
$consulta=" SELECT COUNT(*) FROM agentes a INNER JOIN usuarios u ON u.usuario = a.usuario  WHERE u.estado='Activo' ";  
$datos=mysqli_query($mysqli,$consulta);      
if($row=mysqli_fetch_row($datos)){ 
$agentes=$row[0];
}*/


$actividades=0;/*
//$consulta="SELECT count(*) FROM actividad WHERE codempresa='".$_SESSION['LMNS-USER_CODE']."'"; 
$consulta="SELECT count(*) FROM actividad  "; 
 $datos=mysqli_query($mysqli,$consulta);      
if($row=mysqli_fetch_row($datos)){ 
$actividades=$row[0];
}*/

$cantidad=0;
$efectivos=0;
$noefectivos=0;
$textcantidad="";
$textefectivos="";
$textnoefectivos=""; 


/*

$completaconsulta="";
 
    
  if(isset($_SESSION['LMNS_unidad'])){ 
     $selectunidad=str_replace(",", "','", $_SESSION["LMNS_unidad"]);
    $selectunidad="'".$selectunidad."'";    

     if ($selectunidad!="''") {
            $completaconsulta.=" AND unidad_negocio in (".$selectunidad.") ";
          }
  }
       
     if ($completaconsulta!="") {
    $consulta=" SELECT fecha, SUM(cantidad), SUM(efectivos), SUM(noefectivos) FROM actividad a inner join  actividaproyectos p on p.proyecto=a.proyecto  WHERE 
fecha >= DATE_ADD( CURDATE(), INTERVAL -6 DAY) and fecha <=  CURDATE()  ".$completaconsulta."   GROUP BY fecha ";
     }else{

  $consulta=" SELECT fecha, SUM(cantidad), SUM(efectivos), SUM(noefectivos) FROM actividad WHERE 
fecha >= DATE_ADD( CURDATE(), INTERVAL -6 DAY) and fecha <=  CURDATE()   GROUP BY fecha ";
     }



 $datos=mysqli_query($mysqli,$consulta);      
while($row=mysqli_fetch_row($datos)){ 
    $cantidad=$cantidad+$row[1];
    $efectivos=$efectivos+$row[2];
    $noefectivos=$noefectivos+$row[3];

    $textefectivos.='{ X: "'.$row[0].'", Y: '.$row[2].' },';
    $textnoefectivos.='{ X: "'.$row[0].'", Y: '.$row[3].' },';
    $textcantidad.='{ X: "'.$row[0].'", Y: '.($row[1]-($row[2]+$row[3])).' },';
}*/
		?>




		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">


        	<div class="col-md-3 widget widget1">
        		<div class="r3_counter_box">
                    <i class="pull-left fa fa-users icon-rounded"></i>
                    <div class="stats">
                      <h5><strong><?php echo $agentes;?></strong></h5>
                      <span>Agentes Activos</span>
                    </div>
                </div>
        	</div>


        	<div class="col-md-3 widget widget1">
        		<div class="r3_counter_box">
                    <i class="pull-left fa fa-laptop user1 icon-rounded"></i>
                    <div class="stats">
                      <h5><strong><?php echo $actividades;?></strong></h5>
                      <span>Actividades</span>
                    </div>
                </div>
        	</div>

            <div class="col-md-4 content-top-2 card widget" style="width: 51% !Important">
        		<div class="r3_counter_box">
                    <div class="stats">
                     <h2 class="title1 " style="margin: 0PX !important;color: #F2B33F ">
                    <i class="pull-left fa fa-star" style="font-size: 50PX"></i>
                     ¡Bienvenido  a Lumens!
					<br><b><?php echo  $_SESSION['STCK-USER_NOMBRE'];?></b></h2>

                    </div>
                </div>
        	</div>
        	<div class="clearfix"> </div>		
		<div class="row-one widgettable">
			<div class="col-md-8 content-top-2 card">
				<div class="agileinfo-cdr">
					<div class="card-header">
                        <h3>
                            <div class="col-md-9" align="left">Estatus - Ultimos  7 Dias</div>
                            <div class="col-md-3" style="color: #164194; font-weight: bold; ">
                                <?php echo $cantidad?> Visitas</div>
                        </h3>
                    </div>					
						<div id="Linegraph" style="width: 100%; height: 375px;">
						</div>						
				</div>
			</div>
           
			<div class="col-md-3 stat">
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>Efectivas</h5>
					<label><?php echo $efectivos;?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-2" class="pie-title-center" data-percent="<?php  if($cantidad!=0){echo (($efectivos/$cantidad)*100);}else{echo 0;} ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>No Efectivas</h5>
                    <label><?php echo $noefectivos;?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-1" class="pie-title-center" data-percent="<?php if($cantidad!=0){echo  (($noefectivos/$cantidad)*100);}else{echo 0;}  ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
				</div>
				<div class="content-top-1">
				<div class="col-md-6 top-content">
					<h5>No Realizadas</h5>
                    <label><?php $norealizadas=($cantidad-($efectivos+$noefectivos)); echo $norealizadas; ?></label>
				</div>
				<div class="col-md-6 top-content1">	   
					<div id="demo-pie-3" class="pie-title-center" data-percent="<?php if($cantidad!=0){echo  (($norealizadas/$cantidad)*100);}else{echo 0;}  ?>"> <span class="pie-value"></span> </div>
				</div>
				 <div class="clearfix"> </div>
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
	<!-- //side nav js -->
   
	<!-- for index page weekly sales java script -->


	<script src="js/SimpleChart.js"></script>
    <script >



        var graphdata1 = {
            linecolor: "#FFC168",
            title: "No Realizadas",
            values: [
            <?php echo $textcantidad;?>
            ]
        };
        var graphdata2 = {
            linecolor: "#00CC66",
            title: "No Efectivas",
            values: [              
            <?php echo $textnoefectivos;?>
            ]
        };
        var graphdata3 = {
            linecolor: "#8E43E7",
            title: "Efectivas",
            values: [
           
            <?php echo $textefectivos;?>
            ]
        };


      

        //$(function () {
            function Graph (){
            $("#Linegraph").SimpleChart({
                ChartType: "Line",
                toolwidth: "40",
                toolheight: "20",
                axiscolor: "#E6E6E6",
                textcolor: "#6E6E6E",
                showlegends: true,
                data: [ graphdata3, graphdata2, graphdata1],
                legendsize: "50",
                legendposition: 'bottom',
                xaxislabel: 'Dias',
                title: '',
                yaxislabel: 'Cantidad De Registros'
            });

            console.log("paso");
        }
       // });

            Graph();

 

    </script>
<script src="js/sweetalert.min.js"></script>
   <script src="js/bootstrap.js"> </script>	

</body>
</html>