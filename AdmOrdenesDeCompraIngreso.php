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
<title>Ingreso | Lumens</title>
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
$("#divBusqueda1").hide();
$("#divBusqueda2").hide();
$("#Desde").val(f.getFullYear()+"-"+mes+"-"+dia);
$("#Hasta").val(f.getFullYear()+"-"+mes+"-"+dia); 
 $("#Elemento").change(buscatallasListado);


});
 

function buscatallasListado(){  
   $("#Talla").html('<option  selected="selected" value="">Cargando...</option>');  
  var Datos = { 
    "codigoElemento" :  $("#Elemento").val(),
    "CodigoOrden" : $("#divCodigo").html(),
    "buscatallasListado" : "OK"
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlOrdenesDeCompra.php", 
          type:"POST",
        success:function(resp){
                $("#Talla").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}

function Generar(){
 
      $('#btnBuscar').button('loading'); 
      if($("#Reporte").val()!="Busqueda") {
       

        var Datos = {
              "Desde" : $("#Desde").val(),
              "Hasta" : $("#Hasta").val() , 
              "Proyecto" : $("#Proyecto").val() , 
              "Proveedor" : $("#Proveedor").val() , 
              "Codigob" : $("#Codigob").val() , 
              "Ingresos" : "OK"
               }; 

          $.ajax({
                data:Datos,
                url:"Procesamiento/mdlOrdenesDeCompra.php",  
                type:"POST",
              success:function(resp){
                $('#Listado').html(resp);  
                $('#example').DataTable(); 
                $('#btnBuscar').button('reset');
                 
              },
              error:function(resp){
                swal("Error!","Error Al Conectarse Al Servidor", "error");
              }
           });

      }
}


function Buscar_Datos(codigo){ 
            Codigo_Registro= codigo;  
             if(eventog=='0'){  
                 cargarElementosListado(Codigo_Registro);
                $("#Talla").html('<option  selected="selected" value="">Seleccionar...</option>');  
             }
                cargarElementosOrdenModal(Codigo_Registro);
  var Datos = {  "Buscar_Datos" : Codigo_Registro }; 
  $.ajax({
          data:Datos,
          url:"Procesamiento/mdlOrdenesDeCompra.php",
          type:"POST",
        success:function(resp){  
            if(resp=="n"){
                swal("Atención", "No se encontro resultado","error");
            }else{ 
                eventog='0';
                 $('#btng').attr("disabled", false);
                 var valores = eval(resp);      
                $("#divCodigo").html(valores[0][0]); 
                codigoc=valores[0][0];
                $("#divUsuario").html(valores[0][1]);  
                $("#divCreacion").html(valores[0][2]);  
                $("#divForma").html(valores[0][3]);  
                $("#divEntrega").html(valores[0][4]);  
                $("#divProyecto").html(valores[0][5]);  
                   $("#divCotizaciones").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Cotizaciones/"+valores[0][6]+"' target='_blank'>Descargar</a>");  
                $("#divSoporte").html(""); 
                clase=valores[0][7];
                if (valores[0][7]=="Aprobado") { 
                $("#divSoporte").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/"+valores[0][13]+"' target='_blank'>Descargar</a>"); 
                clase='<span style="background-color: #5cb85c;color:white;text-align:center">'+valores[0][7]+'</span>';
                }
                if (valores[0][7]=="Anulado") {  
                clase='<span style="background-color: #f0ad4e;color:white;text-align:center">'+valores[0][7]+'</span>';
                }
                if (valores[0][7]=="Rechazado") {
                $("#divSoporte").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Ordenes/"+valores[0][13]+"' target='_blank'>Descargar</a>");   
                clase='<span style="background-color: #d9534f;color:white;text-align:center">'+valores[0][7]+'</span>';
                }
                $("#divEstado").html(clase);  
                $("#divFechaEstado").html(valores[0][8]);  
                $("#divObservaciones").html(valores[0][9]);     
                $("#divProveedores").html(valores[0][10]);     
                $("#divObservacionesResp").html(valores[0][11]);     
                $("#divUsuarioEstado").html(valores[0][12]);       
                  clase=valores[0][14];
                if (valores[0][14]=="Cerrado") { 
                clase='<span style="background-color: #5cb85c;color:white;text-align:center">'+valores[0][14]+'</span>';
                } 
                if (valores[0][14]=="Abierto") {  
                clase='<span style="background-color: #d9534f;color:white;text-align:center">'+valores[0][14]+'</span>';
                }
                $("#divIngresoEstado").html(clase);     
 


                $('#myModal').modal('show');
                if ( valores[0][14]=="Cerrado"){
                       $('#btng').attr("disabled", true);

                }
 
            }
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
   });   
}


 


function cargarElementosListado(codigoOrdenModal){ 

   $("#Elemento").html('<option  selected="selected" value="">Cargando...</option>');  
  var Datos = { 
    "cargarElementosListado" : codigoOrdenModal
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlOrdenesDeCompra.php", 
          type:"POST",
        success:function(resp){ 
                $("#Elemento").html(resp);  
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}

function cargarElementosOrdenModal(codigoOrdenModal){ 
  var Datos = {
    "cargarElementosOrden" : 'OK',
    "Tipo" : 'modal',
    "codigoOrdenAdd" : codigoOrdenModal
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlOrdenesDeCompra.php", 
          type:"POST",
        success:function(resp){
          $('#ListadoDetalle').html(resp);  
            $('#exampleElementosOrden').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });


     var Datos = {
    "cargarIngresos" : 'OK', 
    "codigoOrdenIngreso" : codigoOrdenModal
  };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlOrdenesDeCompra.php", 
          type:"POST",
        success:function(resp){
          $('#ListadoIngresos').html(resp);  
            $('#exampleIngresos').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });

}

function Cancelar(){ 
  $('#myModal').modal('hide');
}


function Guardar(){


 

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
 
    if($("#Soportes").val()=='') {
      swal("Atención!","Seleccione Soporte","warning"); 
      return false;
    } 
 
    if($("#Cantidad").val()=='') {
      swal("Atención!","Escriba Cantidad","warning"); 
      return false;
    } 
 
 
 
      var archivos = document.getElementById("Soportes");//Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
       var data = new FormData();  
 
      data.append('archivo',archivo[0]);  
      data.append('IngresarElementos',"OK");
      data.append('Almacen',$("#Almacen").val());
      data.append('Elemento',$("#Elemento").val());
      data.append('Talla',$("#Talla").val());
      data.append('Cantidad',$("#Cantidad").val());
      data.append('Codigo',$("#divCodigo").html());
      data.append('Usuario',"<?php echo $_SESSION['STCK-USER_USUARIO'];?>");  
      $('#btng').button('loading'); 
      $("#Soportes").val("");
      $.ajax({  
      url:'Procesamiento/mdlOrdenesDeCompra.php', //Url a donde la enviaremos
      type:'POST', //Metodo que usaremos
      contentType:false, //Debe estar en false para que pase el objeto sin procesar
      data:data, //Le pasamos el objeto que creamos con los archivos
      processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
      cache:false //Para que el formulario no guarde cache    
      }).done(function(resp){   
           if(resp=="OK"){      

             eventog='1';
            $("#Cantidad").val("");
            swal("Registro Exitoso!","Se ha registrado correctamente", "success"); 
            Buscar_Datos($("#divCodigo").html());
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btng').button('reset'); 
      }).fail(  function(XMLHttpRequest, textStatus, errorThrown){
      $('#btng').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
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

                          <div class="col-md-12" >
<h3 class="title1" style="margin-bottom: 0em !important;margin-top: 0px !important"> DETALLE DE ORDEN DE COMPRA</h3></div>
                          <div class="col-md-4">
                            <b>Codigo Registro: </b><span id="divCodigo"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Usuario Creado: </b><span id="divUsuario"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Fecha Creacion: </b><span id="divCreacion"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Forma de pago: </b><span id="divForma"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Fecha Entrega: </b><span id="divEntrega"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Proyecto: </b><span id="divProyecto"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Archivo Cotizaciones: </b><span id="divCotizaciones"> </span>
                          </div>
                          <div class="col-md-12">
                            <b>Observaciones: </b><span id="divObservaciones"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Estado: </b><span id="divEstado"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Fecha Respuesta: </b><span id="divFechaEstado"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Usuario Respuesta: </b><span id="divUsuarioEstado"> </span>
                          </div>

                          <div class="col-md-4">
                            <b>Proveedor: </b><span id="divProveedores"> </span>
                          </div>

                          <div class="col-md-4">
                            <b>Soporte Respuesta: </b><span id="divSoporte"> </span>
                          </div>
                          <div class="col-md-4">
                            <b>Estado Ingreso: </b><span id="divIngresoEstado"> </span>
                          </div>

                          <div class="col-md-8">
                            <b>Observaciones Respuesta: </b><span id="divObservacionesResp"> </span>
                          </div>
                          <div class="col-md-12" >
<h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important"> DETALLE ELEMENTOS</h3></div>

                    <div class="clearfix"> </div>
                                        <div class="panel-body" style="background-color: #fafafa"> 
                                                <div class="single-bottom row" >
                                                        <div id="ListadoDetalle" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div>

 
                          <div class="col-md-12" >
<h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important">FORMULARIO DE INGRESO</h3></div>

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


                          <div class="col-md-2">
                            <p><i class="fa fa-user-o"></i>Cantidad</p>
                            <input id="Cantidad"  type="number" name="Cantidad" class="form-control" />  
                          </div>


                             <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Soporte</p>
                          <input id="Soportes" type="file"   name="Soportes" class="form-control" /> 
                          </div>

                              <div class="col-md-2">
                                <p><i class="fa fa-id-card"></i>  </p>
                                <button id="btng" type="button" class="btn btn-success" onclick="Guardar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>  
                          </div>

                          <div class="col-md-12" >
<h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important">HISTORICO DE INGRESOS</h3></div>
   <div class="panel-body" style="background-color: #fafafa"> 
                                                <div class="single-bottom row" >
                                                        <div id="ListadoIngresos" class="single-bottom row" style=""></div>  
                                                </div>
                                        </div>

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
                                            <h3 class="panel-title">Lumens |  Ingreso A Almacen</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 

 
 
      

                        <div class="col-md-3"  id="divBusqueda2">
                          <p><i class="fa fa-user-o"></i>Parametro </p>
                          <input id="Parametro" type="text" name="Parametro" class="form-control" />
                        </div>
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Proyecto</p>
      <?php     
        $consulta="SELECT cons, nombre FROM proyectos  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="Proyecto" name="Proyecto" class="form-control">
           <option  selected="selected" value="Todos">Todos</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
                           </div>
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Proveedor</p>
      <?php     
        $consulta="SELECT cons, nombre FROM proveedores  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="Proveedor" name="Proveedor" class="form-control">
           <option  selected="selected" value="Todos">Todos</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
                           </div>
                        <div class="col-md-3" id="divResumen1">
                          <p><i class="fa fa-user-o"></i>Desde </p>
                          <input id="Desde" type="date" name="Desde" class="form-control" />
                        </div>
                        <div class="col-md-3" id="divResumen2">
                          <p><i class="fa fa-user-o"></i>Hasta</p>
                          <input id="Hasta" type="date" name="Hasta" class="form-control" />
                        </div>
                        <div class="col-md-2"   >
                          <p><i class="fa fa-user-o"></i>Codigo</p>
                          <input id="Codigob" type="text" name="Codigob" class="form-control" />
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