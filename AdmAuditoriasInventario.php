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
<title>Asignacion | Lumens</title>
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
  
revisarauditoriaactiva();
  $("#Almacen").change(buscaElementosAlmacen);
 $("#Elemento").change(buscaTallaxElementosAlmacen);
 $("#Talla").change(cambiotalla);
 $("#btnFinalizar").hide();
 $("#divcontenidos").hide();
 

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
$("#Inicio").val(f.getFullYear()+"-"+mes+"-"+dia);
$("#Final").val(f.getFullYear()+"-"+mes+"-"+dia); 

});
  
    
 
 
function cambiotalla(){    

    $("#Disponibles").val('');    
  var Datos = { 
    "Almacen" :  $("#Almacen").val(), 
    "Elemento" :  $("#Elemento").val(), 
    "Talla" :  $("#Talla").val(), 
    "buscaCantidadXTallaxElementosAlmacen" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){
                $("#Disponibles").val(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 

 }

 
function buscaTallaxElementosAlmacen(){  
 $("#Disponibles").val('');   
   $("#Talla").html('<option  selected="selected" value="">Cargando...</option>');  
  var Datos = { 
    "Almacen" :  $("#Almacen").val(), 
    "Elemento" :  $("#Elemento").val(), 
    "buscaTallaxElementosAlmacen" : "OK"
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


  

function buscaElementosAlmacen(){  
  $("#Disponibles").val('');   
  $("#Talla").html('<option  selected="selected" value="">Seleccionar...</option>');  
  $("#Elemento").html('<option  selected="selected" value="">Cargando...</option>');  
  var Datos = { 
    "Almacen" :  $("#Almacen").val(), 
    "buscaElementosAlmacen" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){
                $("#Elemento").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
  }


function Generar(){    


    $("#Codigob").val("");     
  
    

    $('#btnBuscar').button('loading');  
  var Datos = {  
    "Observaciones" :  $("#Observaciones").val(),  
    "Usuario" :  "<?php echo $_SESSION['STCK-USER_USUARIO'];?>", 
    "IniciarAuditoria" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){

          var valores = eval(resp);    
          if (valores[0][0]>0) {   
                 $("#Codigob").val(valores[0][0]);
                 $("#Inicio").val(valores[0][1]); 
                 $("#Final").val(valores[0][2]); 
                 $("#Observaciones").val(valores[0][3]); 
                 $("#btnFinalizar").show();
                 $("#divcontenidos").show();
                 $("#btnBuscar").hide();  
                 AsignacionhistoricoActual();  
          }else{
            swal("Error!",resp, "error");

          }
          $('#btnBuscar').button('reset'); 
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
          $('#btnBuscar').button('reset'); 
        }
     });
  }


function Finalizar(){    

 
   
  var Datos = {   
    "Codigo" :  $("#Codigob").val(), 
    "Observaciones" :  $("#Observaciones").val(), 
    "FinalizarAuditoria" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){
            if (resp=="OK") {  
                 swal("Registro Exitoso!","Se ha registrado correctamente", "success");   
                 $("#Codigob").val(""); 
                 $("#Observaciones").val("");
                 $("#btnFinalizar").hide();
                 $("#divcontenidos").hide();
                 $("#btnBuscar").show();   
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
                $("#Inicio").val(f.getFullYear()+"-"+mes+"-"+dia);
                $("#Final").val(f.getFullYear()+"-"+mes+"-"+dia); 
                 revisarauditoriaactiva();
          }else{
            swal("Error!",resp, "error");

          }
          $('#btnBuscar').button('reset'); 
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
          $('#btnBuscar').button('reset'); 
        }
     });
  }

function revisarauditoriaactiva(){    

 
   
  var Datos = {   
    "Usuario" :  "<?php echo $_SESSION['STCK-USER_USUARIO'];?>", 
    "revisarauditoriaactiva" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
                var valores = eval(resp);     
                 swal("Debe Finalizar!","Tiene una Auditoria Sin Finalizar", "success");  
                 $("#Codigob").val(valores[0][0]);
                 $("#Inicio").val(valores[0][1]); 
                 $("#Final").val(valores[0][2]); 
                 $("#Observaciones").val(valores[0][3]); 
                 $("#btnFinalizar").show();
                 $("#divcontenidos").show();
                 $("#btnBuscar").hide();  
                 AuditoriashistoricoActual();  
          
          $('#btnBuscar').button('reset'); 
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
          $('#btnBuscar').button('reset'); 
        }
     });
  }

 function AuditoriashistoricoActual(){
 
    var Datos = {
      "AuditoriashistoricoActual" : 'OK', 
      "Codigo" :  $("#Codigob").val()   
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#ListadoActuales').html(resp);  
              $('#exampleListadoAuditorias').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}





function AuditoriaElemento(){


  
    if($("#Soportes").val()=='') {
      swal("Atención!","Seleccione Soporte","warning"); 
      return false;
    } 

    if($("#Almacen").val()=='') {
      swal("Atención!","Seleccione Almacen","warning"); 
      return false;
    } 
  
    if($("#Elemento").val()=='') {
      swal("Atención!","Seleccione Elemento","warning"); 
      return false;
    } 

    if($("#Talla").val()=='') {
      swal("Atención!","Seleccione Talla","warning"); 
      return false;
    } 

    if($("#Encontrados").val()=='') {
      swal("Atención!","Escriba Encontrados","warning"); 
      return false;
    } 

 
      var archivos = document.getElementById("Soportes");//Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
       var data = new FormData();  
 
      data.append('archivo',archivo[0]);  
      data.append('AuditoriaElemento',"OK");
      data.append('Codigob',$("#Codigob").val()); 
      data.append('ObservacionesAUD',$("#ObservacionesAUD").val()); 
      data.append('Almacen',$("#Almacen").val()); 
      data.append('Elemento',$("#Elemento").val()); 
      data.append('Talla',$("#Talla").val()); 
      data.append('Encontrados',$("#Encontrados").val());
      data.append('Disponibles',$("#Disponibles").val()); 
      data.append('Resultado',$("#Resultado").val()); 
      data.append('Usuario',"<?php echo $_SESSION['STCK-USER_USUARIO'];?>");  
      $('#btnAsignar').button('loading'); 
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
                $("#Encontrados").val("");
                $("#ObservacionesAUD").val("");
                 swal("Registro Exitoso!","Se ha registrado correctamente", "success");  
                 AuditoriashistoricoActual();  
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btnAsignar').button('reset'); 
      }).fail(  function(XMLHttpRequest, textStatus, errorThrown){
      $('#btnAsignar').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
      });  

}


  function BuscarReporte(){
 
    $('#btnBuscarReporte').button('loading');  
    var Datos = {
      "BuscarReporteAuditorias" : 'OK', 
      "Desde" :  $("#Desde").val()   , 
      "Hasta" :  $("#Hasta").val()   , 
      "Filtro" :  $("#Filtro").val()   
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
    $('#btnBuscarReporte').button('reset');  
              $('#ListadoReporte').html(resp);  
              $('#exampleReporteAuditorias').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}

function impresionformatoAuditoria(econs)
{

    window.open("Reportes/rptFormatoAuditoria.php?cod="+econs ); 
}

function ExportarReporte(econs)
{

    window.open("Reportes/rptDetalleAuditoria.php?Desde="+$("#Desde").val() +"&Hasta="+$("#Hasta").val() +"&Filtro="+$("#Filtro").val()  ); 
}

 

    </script> 

</head> <style type="text/css">
.modal-dialog { 
  padding: 0;
}

.modal-content { 
  border-radius: 0;
}
</style> 

<body class="cbp-spmenu-push">
	<div class="main-content" >


 



		<?php
		include("Partes/Menu.php");
		?>
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">



<div class="col-md-12  widget-shadow">


                        <ul id="myTabs" class="nav nav-tabs" role="tablist">  
                             <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Formulario de registro</a></li>
                             <li role="presentation" class=""><a href="#Reporte" role="tab" id="Reporte-tab" data-toggle="tab" aria-controls="Reporte" aria-expanded="true">Reporte - Informe</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 
 

                    <div role="tabpanel" class="tab-pane fade " id="Reporte" aria-labelledby="Reporte-tab"> 
                       <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Reporte Entregas</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
 
  <div class="col-md-2  "> </div>

  <div class="col-md-2  ">
   <p><i class="fa fa-user-o"></i>Filtro</p> 
    <select id="Filtro" name="Filtro" class="form-control">
             <option  selected="selected" value="TODAS">TODAS</option>    
             <option   value="<?php echo $_SESSION['STCK-USER_USUARIO'];?>">PROPIAS</option>     
        </select>   </div>

 
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
                      <button type="button" class="btn btn-primary" onclick="BuscarReporte();"  id="btnBuscarReporte">Generar Reporte</button> 
                      <button type="button" class="btn btn-success" onclick="ExportarReporte();"  id="btnExportar">Exportar Detalle</button> 
                      </center>
                    </div>


                                        </div>

                                           <div class="single-bottom row" >
                                                        <div id="ListadoReporte" class="single-bottom row" style=""> </div>  
                                                </div>  

                                      </div>

                                    </p>
                    </div>



                    <div role="tabpanel" class="tab-pane fade  active in" id="profile" aria-labelledby="profile-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Asignacion</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 
  
      <div class="col-md-2"   >
                          <p><i class="fa fa-user-o"></i>Codigo Asignacion</p>
                          <input id="Codigob" type="text" name="Codigob" readonly="" class="form-control" />
                        </div> 




            <div class="col-md-3" id="divResumen1">
                          <p><i class="fa fa-user-o"></i>Inicio </p>
                          <input id="Inicio" type="date" name="Inicio"  readonly=""  class="form-control" />
                        </div>
                        <div class="col-md-3" id="divResumen2">
                          <p><i class="fa fa-user-o"></i>Final</p>
                          <input id="Final" type="date" name="Final"  readonly="" class="form-control" />
                        </div> 

 

 


                          <div class="col-md-2 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="Generar();"  id="btnBuscar">Iniciar Auditoria</button> 
                      <button type="button" class="btn btn-success" onclick="Finalizar();"  id="btnFinalizar">Finalizar Entrega</button> 
                      </center>
                    </div>    <div class="col-md-12 divdescuento"   >
        <p><i class="fa fa-user-o"></i>Observaciones</p>
        <input id="Observaciones" type="number" name="Observaciones" class="form-control" />
      </div>
              </div>



                    <div id="divcontenidos">

   <div class="panel panel-success">
      <div class="panel-heading">
      <h3 style="color:  ">Panel de asignacion</h3>
      </div>
      <div class="panel-body">
 
         <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen</p>
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

                             <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Elemento</p> 
    <select id="Elemento" name="Elemento" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>     
        </select>   </div>
  <div class="col-md-2">
   <p><i class="fa fa-user-o"></i>Talla </p> 
    <select id="Talla" name="Talla" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>     
        </select>   </div>
     <div class="col-md-2"   >
                          <p><i class="fa fa-user-o"></i>Disponibles</p>
                          <input id="Disponibles" type="text" name="Disponibles" readonly="" class="form-control" />
                        </div>
                        <div class="col-md-2"   >
                          <p><i class="fa fa-user-o"></i>Encontrados</p>
                          <input id="Encontrados" type="number" name="Encontrados" class="form-control" />
                        </div>                
    <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Resultado</p> 
    <select id="Resultado" name="Resultado" class="form-control">
             <option  selected="selected" value="CORRECTO">CORRECTO</option>   
             <option value="INCORRECTO">INCORRECTO</option>       
        </select>   
      </div>     <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Soporte</p>
                          <input id="Soportes" type="file"   name="Soportes" class="form-control" /> 
                          </div>

                        <div class="col-md-6"   >
                          <p><i class="fa fa-user-o"></i>Observaciones</p>
                          <input id="ObservacionesAUD" type="text" name="ObservacionesAUD" class="form-control" />
                        </div> 
      
                   <div class="col-md-1 "><br>
                                  <center>
                        <button type="button" class="btn btn-primary" onclick="AuditoriaElemento();"  id="btnAsignar">Guardar Auditoria Elementos</button> 
                        </center>
                      </div>
     <div class="single-bottom row" >
                <div id="ListadoActuales" class="single-bottom row" style=""></div>  
        </div>  
                    </div></div> 
                                           
 


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