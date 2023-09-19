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
<title>Devolucion | Lumens</title>
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
 $(document).ready(function(){
  
 $("#Proyecto").change(buscataempleadosxproyecto);  
  
 $("#ProyectoRE").change(buscataempleadosxproyectoRE);  
 

 $("#Elementos").change(buscaTallaxelementosEmpleado); 
 $("#Talla").change(buscaEntregadasxTallaxelementosEmpleado); 

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
 
 

function buscataempleadosxproyecto(){  

  $("#Empleados").html('<option  selected="selected" value="">Cargando...</option>');  
  $("#Elementos").html('<option  selected="selected" value="">Seleccionar...</option>');  
  $("#Talla").html('<option  selected="selected" value="">Seleccionar...</option>');  
  $("#Entregadas").val("0"); 
  $("#Devueltas").val("0");

  var Datos = { 
    "Proyecto" :  $("#Proyecto").val(), 
    "buscataempleadosxproyecto" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlEmpleados.php", 
          type:"POST",
        success:function(resp){ 
                $("#Empleados").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}

function buscaTallaxelementosEmpleado(){  

  $("#Talla").html('<option  selected="selected" value="">Cargando...</option>');     
  $("#Entregadas").val("0");
  $("#Devueltas").val("0");

  var Datos = { 
    "codigo" :  codigoc, 
    "Elementos" :  $("#Elementos").val(), 
    "buscaTallaxelementosEmpleado" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
                $("#Talla").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
} 

function buscaEntregadasxTallaxelementosEmpleado(){  
    
  $("#Entregadas").val("0");
  $("#Devueltas").val("0");
 codtalla=$("#Talla").val().split("-"); 
  var Datos = { 
    "codigo" :  codigoc, 
    "Elementos" :  $("#Elementos").val(), 
    "Talla" :   codtalla[0], 
    "codasignacion" :   codtalla[1], 
    "buscaEntregadasxTallaxelementosEmpleado" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
          var valores = eval(resp);   
                $("#Entregadas").val(valores[0][0]);  
                $("#Devueltas").val(valores[0][1]);
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}
 

function buscaAsignacionesEmpleado(){ 

      $('#btnBuscar').button('loading'); 
    if ($("#Empleados").val()=="") {
          swal("Atención!","Seleccione Empleado","warning"); 
          return false; 
    }
    var Datos = {
      "buscaAsignacionesEmpleado" : 'OK', 
      "Empleados" :  $("#Empleados").val(),  
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
           $('#btnBuscar').button('reset'); 
              $('#Listado').html(resp);  
              $('#exampleListadoReporte').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}

function DetallarElementosXdevolucion(ecodigo){
 
codigoc=ecodigo;
 $("#Elementos").html('<option  selected="selected" value="">Cargando...</option>');    
  $("#Talla").html('<option  selected="selected" value="">Seleccionar...</option>');  
  $("#Entregadas").val("0");
  $("#Devueltas").val("0");

  var Datos = { 
    "codigoAsignacion" :  codigoc,  
    "buscaelementosEmpleado" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
                $("#Elementos").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });


     var Datos = {
      "AsignacionhistoricoActualxdevolucion" : 'OK', 
      "codigo" :  codigoc
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#ListadoDetalle').html(resp);  
              $('#exampleListadoEntregas').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 


     var Datos = {
      "DevolucionesActual" : 'OK', 
      "codigo" :  codigoc
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#ListadoDetalleDE').html(resp);  
              $('#exampleListadoDevoluciones').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 


         $('#myModal').modal('show');
}
 
 function DevolverElemento(){

    if ($("#Cantidad").val()=="") {
          swal("Atención!","Escriba Cantidad","warning"); 
          return false; 
    }
    if ($("#Estado").val()=="") {
          swal("Atención!","Seleccione Estado","warning"); 
          return false; 
    }
    if ($("#Elementos").val()=="") {
          swal("Atención!","Seleccione Elemento","warning"); 
          return false; 
    }
    if ($("#Talla").val()=="") {
          swal("Atención!","Seleccione Talla","warning"); 
          return false; 
    }

   var tieneempleado=parseInt($("#Entregadas").val()) - parseInt($("#Devueltas").val());
   if( tieneempleado <= 0 ){ 
          swal("Atención!","Ya devolvio Elementos","warning"); 
          return false; 
   }else{
      if( tieneempleado <  parseInt($("#Cantidad").val()) ){ 
          swal("Atención!","Cantidad Erronea","warning"); 
          return false; 

      }
   }
    if ($("#Almacen").val()=="") {
          swal("Atención!","Seleccione Almacen","warning"); 
          return false; 
    }
    if ($("#Soportes").val()=="") {
          swal("Atención!","Seleccione Soporte","warning"); 
          return false; 
    }


 codtalla=$("#Talla").val().split("-"); 
      var archivos = document.getElementById("Soportes");//Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
       var data = new FormData();  
 
      data.append('archivo',archivo[0]);  
      data.append('DevolverElemento',"OK");
      data.append('Codigo',codigoc); 
      data.append('Almacen',$("#Almacen").val()); 
      data.append('Elementos',$("#Elementos").val()); 
      data.append('Talla', codtalla[0]); 
      data.append('codasignacionesdetalle', codtalla[1]); 
      data.append('Estado',$("#Estado").val()); 
      data.append('Cantidad',$("#Cantidad").val()); 
      data.append('Observaciones',$("#Observaciones").val()); 
      data.append('codigo',codigoc); 
      data.append('Usuario',"<?php echo $_SESSION['STCK-USER_USUARIO'];?>");  
      $('#btnDevolver').button('loading'); 
      $("#Soportes").val("");
      $.ajax({  
      url:'Procesamiento/mdlInventario.php', //Url a donde la enviaremos
      type:'POST', //Metodo que usaremos
      contentType:false, //Debe estar en false para que pase el objeto sin procesar
      data:data, //Le pasamos el objeto que creamos con los archivos
      processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
      cache:false //Para que el formulario no guarde cache    
      }).done(function(resp){   
           if(resp=="OK"){         
                 $("#Cantidad").val(""); 
                 $("#Observaciones").val(""); 
                 buscaTallaxelementosEmpleado();
                 DetallarElementosXdevolucion(codigoc);
                 swal("Registro Exitoso!","Se ha registrado correctamente", "success");  
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btnDevolver').button('reset'); 
      }).fail(  function(XMLHttpRequest, textStatus, errorThrown){
      $('#btnDevolver').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
      });  



 }

 function Salir(){ 
         $('#myModal').modal('hide');
         buscaAsignacionesEmpleado();
 }


function buscataempleadosxproyectoRE(){  

  $("#EmpleadosRE").html('<option  selected="selected" value="">Cargando...</option>');   
  $("#Entregadas").val("0"); 
  $("#Devueltas").val("0");

  var Datos = { 
    "Proyecto" :  $("#ProyectoRE").val(), 
    "buscataempleadosxproyectoREP" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlEmpleados.php", 
          type:"POST",
        success:function(resp){ 
                $("#EmpleadosRE").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}



function generarReporteDevoluciones(){ 

      $('#btnBuscarRE').button('loading'); 

 
    var Datos = {
      "generarReporteDevoluciones" : 'OK', 
      "Empleados" :  $("#EmpleadosRE").val(),  
      "Proyecto" :  $("#ProyectoRE").val(),  
      "Desde" :  $("#Desde").val(),  
      "Hasta" :  $("#Hasta").val(),  
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
           $('#btnBuscarRE').button('reset'); 
              $('#ListadoRE').html(resp);  
              $('#exampleListadoReporteRE').DataTable();
        },
        error:function(resp){
           $('#btnBuscarRE').button('reset'); 
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}


function ExportarReporteRE()
{
  window.open("Reportes/rptDetalleDevolucion.php?Proyecto="+$("#ProyectoRE").val()+'&Empleados='+$("#EmpleadosRE").val()+'&Hasta='+$("#Hasta").val()+'&Desde='+$("#Desde").val()   );
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

                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>  Elementos </p>
                            <select id="Elementos" name="Elementos" class="form-control">
                               <option  selected="selected" value="">Seleccionar...</option> 
                            </select> 
                         </div> 

                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>  Talla / Medida</p>
                            <select id="Talla" name="Talla" class="form-control">
                               <option  selected="selected" value="">Seleccionar...</option> 
                            </select> 
                         </div>

                          <div class="col-md-2">
                            <p><i class="fa fa-user-o"></i>Entregadas</p>
                            <input id="Entregadas"  type="number" name="Entregadas" readonly="" class="form-control" />  
                          </div> 
                          <div class="col-md-2">
                            <p><i class="fa fa-user-o"></i>Devueltas</p>
                            <input id="Devueltas"  type="number" name="Devueltas" readonly="" class="form-control" />  
                          </div> 

                          <div class="col-md-2">
                            <p><i class="fa fa-user-o"></i>Cantidad</p>
                            <input id="Cantidad"  type="number" name="Cantidad" class="form-control" />  
                          </div>

                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i> Estado de Elementos</p>
                            <select id="Estado" name="Estado" class="form-control">
                               <option  selected="selected" value="">Seleccionar...</option> 
                               <option   value="BUENO">BUENO</option> 
                               <option   value="MALO">MALO</option> 
                               <option   value="REGULAR">REGULAR</option> 
                            </select> 
                         </div>

     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen Devuelto</p>
   <?php     
          $consulta="SELECT cons, nombre FROM almacenes where estado='Activo' ORDER BY nombre ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <select id="Almacen" name="Almacen" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 
?>
                           </div>

                          <div class="col-md-6">
                            <p><i class="fa fa-user-o"></i>Observaciones</p>
                            <input id="Observaciones"  type="text" name="Observaciones" class="form-control" />  
                          </div>

                             <div class="col-md-8">
                            <p><i class="fa fa-user-o"></i>Soporte</p>
                          <input id="Soportes" type="file"   name="Soportes" class="form-control" /> 
                          </div>

                  <div class="col-md-2 "><br>
                                  <center>
                      <button type="button" class="btn btn-success" onclick="DevolverElemento();"  id="btnDevolver">Guardar</button> 
                      <button type="button" class="btn btn-info" onclick="Salir();" >Salir</button> 
                      </center>
                    </div>
                       

                          <div class="col-md-12" >
                  <h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important">ELEMENTOS ENTREGADOS</h3></div>

                    <div class="clearfix"> </div>
                                        <div class="panel-body" style="background-color: #fafafa"> 
                                                <div class="single-bottom row" >
                                                        <div id="ListadoDetalle" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div>

                          <div class="col-md-12" >
                  <h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important">ELEMENTOS DEVUELTOS</h3></div>

                    <div class="clearfix"> </div>
                                        <div class="panel-body" style="background-color: #fafafa"> 
                                                <div class="single-bottom row" >
                                                        <div id="ListadoDetalleDE" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div>

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
                             <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Formulario de registro</a></li>
                             <li role="presentation" ><a href="#Reporte" role="tab" id="Reporte-tab" data-toggle="tab" aria-controls="Reporte" aria-expanded="true">Reporte - Informe</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 
 


                    <div role="tabpanel" class="tab-pane fade " id="Reporte" aria-labelledby="Reporte-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Reporte Devoluciones </h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 
       
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Proyecto</p>
      <?php     
        $consulta="SELECT cons, nombre FROM proyectos  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="ProyectoRE" name="ProyectoRE" class="form-control">
           <option  selected="selected" value="TODOS">TODOS...</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
                           </div> 
 

  <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Empleados</p> 
    <select id="EmpleadosRE" name="EmpleadosRE" class="form-control">
             <option  selected="selected" value="TODOS">TODOS...</option>     
        </select>   
      </div>
 
  
                            

    <div class="col-md-3" id="divResumen1">
                          <p><i class="fa fa-user-o"></i>Desde </p>
                          <input id="Desde" type="date" name="Desde" class="form-control" />
                        </div>
                        <div class="col-md-3" id="divResumen2">
                          <p><i class="fa fa-user-o"></i>Hasta</p>
                          <input id="Hasta" type="date" name="Hasta" class="form-control" />
                        </div>

                          <div class="col-md-12 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="generarReporteDevoluciones();"  id="btnBuscarRE">Buscar</button> 
                      <button type="button" class="btn btn-success" onclick="ExportarReporteRE();"  id="btnBuscarRE">Exportar Reporte</button>  
                      </center>
                    </div>
              </div>


                                                <div class="single-bottom row" >
                                                        <div id="ListadoRE" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div></p> 
                                    </div>


                    <div role="tabpanel" class="tab-pane fade  active in" id="profile" aria-labelledby="profile-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Devolucion </h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 
       
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Proyecto</p>
      <?php     
        $consulta="SELECT cons, nombre FROM proyectos  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="Proyecto" name="Proyecto" class="form-control">
           <option  selected="selected" value="">Seleccionar...</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
                           </div> 
 

  <div class="col-md-4">
   <p><i class="fa fa-user-o"></i>Empleados</p> 
    <select id="Empleados" name="Empleados" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>     
        </select>   
      </div>
 
  
                            


                          <div class="col-md-1 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="buscaAsignacionesEmpleado();"  id="btnBuscar">Buscar</button> 
                      </center>
                    </div>
              </div>


                                                <div class="single-bottom row" >
                                                        <div id="Listado" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div></p> 
                                    </div>

                             
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