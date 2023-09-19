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
<title>Proveedores | Lumens</title>
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

 //$('#example').DataTable();

 $('ul.nav-tabs li a').click(function(){
    var activeTab = $(this).attr('href');
    if(activeTab=='#profile'){
      listar();
      Nuevo();
    }
    if(activeTab=='#help'){
      Nuevo();
    }
 });

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
 
$("#btna").hide();
$("#btnc").hide();
 
 


});


function Guardar(){ 

    if($("#Nombre").val()=='') {
      swal("Atención!","Escriba Proveedor","warning"); 
      return false;
    } 
    $('#btng').button('loading'); 
    $.ajax({ 
    data:{
      "Nombre":$("#Nombre").val(),
      "Direccion":$("#Direccion").val(),
      "Telefono":$("#Telefono").val(),
      "Correo":$("#Correo").val(),
      "Contacto":$("#Contacto").val(),
      "NIT":$("#NIT").val(),
      "Bancarios":$("#Bancarios").val(),
      "Estado":$("#Estado").val(),
      "Observaciones":$("#Observaciones").val(),
      'Ingresar':'OK'},   
     url:"Procesamiento/mdlProveedores.php", 
     type:"POST",   
     success:function(resp){    
           if(resp=="OK"){              
            swal("Registro Exitoso!","Se ha registrado   correctamente", "success");
            Nuevo();
            limpiar();
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btng').button('reset');     
    },
    error:function(resp){
      $('#btng').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
    } 
  });  
}

function actualizar(){
   

    if($("#Nombre").val()=='') {
      swal("Atención!","Escriba Proveedor","warning"); 
      return false;
    } 
  
    $('#btna').button('loading');
    $.ajax({

    data:{
      "Nombre":$("#Nombre").val(),
      "Direccion":$("#Direccion").val(),
      "Telefono":$("#Telefono").val(),
      "Correo":$("#Correo").val(),
      "Contacto":$("#Contacto").val(),
      "NIT":$("#NIT").val(),
      "Bancarios":$("#Bancarios").val(),
      "Estado":$("#Estado").val(),
      "Observaciones":$("#Observaciones").val(),
      codigoc:codigoc,
      'Actualizar':'OK'},
     url:"Procesamiento/mdlProveedores.php",                                
     type:"POST",
     success:function(resp){    
        
     if(resp=="OK")
     {              
      swal("Registro Exitoso!","Se ha actualizado correctamente", "success");
      Nuevo();
      limpiar();
    }else{  
      swal("Atención!",resp, "error");           
    }   
    $('#btna').button('reset');   
  },
  error:function(resp){
    $('#btna').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
  }

  }); 
}

function listar(){ 
  var Datos = {"Listar" : 'OK'};  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlProveedores.php", 
          type:"POST",
        success:function(resp){
          $('#Listado').html(resp);  
            $('#example').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}

function Nuevo(){   limpiar();
}

function limpiar(){
  $("#Nombre").val('');
  $("#Estado").val('Activo');
  $("#Direccion").val('');
  $("#Telefono").val('');
  $("#Correo").val('');
  $("#Contacto").val('');
  $("#NIT").val('');
  $("#Bancarios").val('');
  $("#Observaciones").val('');
  $("#Fecha").val('');
  $("#hidenid").val('');  
  $("#btna").hide(); 
  $("#btnc").hide();
  $("#btng").show();  
 
}

function Buscar_Datos(codigo){ 
  Codigo_Registro= codigo; 
  var Datos = {  "Buscar_Datos" : Codigo_Registro }; 
  $.ajax({
          data:Datos,
          url:"Procesamiento/mdlProveedores.php",
          type:"POST",
        success:function(resp){ 

            if(resp=="n"){
                swal("Atención", "No se encontro resultado","error");
            }else{ 
                 var valores = eval(resp);      
                $("#hidenid").val(valores[0][0]);
                codigoc=valores[0][0];
                $("#Nombre").val(valores[0][1]);  
                $("#Direccion").val(valores[0][2]);  
                $("#Telefono").val(valores[0][3]);  
                $("#Correo").val(valores[0][4]);  
                $("#Contacto").val(valores[0][5]);  
                $("#NIT").val(valores[0][6]);  
                $("#Bancarios").val(valores[0][7]);  
                $("#Estado").val(valores[0][8]);  
                $("#Observaciones").val(valores[0][9]);  
                $("#Fecha").val(valores[0][10]);  
                $("#home-tab").click();
                $("#btng").hide();
                $("#btna").show();
                $("#btnc").show();     
          


 
            }
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
   });   
}

    </script>
<!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head> 
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
                            <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">Formulario  De Registro </a></li>
                             <li role="presentation" class=""><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Reporte - Informe</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 

                    <div role="tabpanel" class="tab-pane fade  active in" id="home" aria-labelledby="home-tab"> 
                        <p>
                        <div class="panel panel-primary"> 
                            <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                <h3 class="panel-title">Lumens | Proveedores Formulario</h3> 
                            </div> 
                            <div class="panel-body" style="background-color: #fafafa"> 
    

                      <div class="single-bottom row" style="">

                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Escriba Nombre Proveedor</p>
                            <input id="Nombre"  type="text" name="Nombre" class="form-control" /> 
                            <input type="hidden" id="hidenid" />   
                          </div>
                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Direccion</p>
                            <input id="Direccion"  type="text" name="Direccion" class="form-control" />  
                          </div>
                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Telefono</p>
                            <input id="Telefono"  type="text" name="Telefono" class="form-control" />  
                          </div>
                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Correo</p>
                            <input id="Correo"  type="text" name="Correo" class="form-control" />  
                          </div>
                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Contacto</p>
                            <input id="Contacto"  type="text" name="Contacto" class="form-control" />  
                          </div>
                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>NIT</p>
                            <input id="NIT"  type="text" name="NIT" class="form-control" />  
                          </div>
                          <div class="col-md-8">
                            <p><i class="fa fa-user-o"></i>Datos Bancarios</p>
                            <input id="Bancarios"  type="text" name="Bancarios" class="form-control" />  
                          </div> 

                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Estado</p>
                            <select id="Estado" name="Estado" class="form-control">
                              <option selected="" value="Activo">Activo</option>
                              <option value="Inactivo">Inactivo</option>
                            </select>
                        
                          
                          </div>
  <div class="col-md-8">
                            <p><i class="fa fa-user-o"></i>Observaciones</p>
                            <input id="Observaciones"  type="text" name="Observaciones" class="form-control" />  
                          </div>
                    <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Fecha Ingresado</p>
                            <input id="Fecha"  type="text" name="Fecha" class="form-control"  readonly="readonly" />  
                          </div>
                              <div class="col-md-4">
                                <p><i class="fa fa-id-card"></i>  </p>
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
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Proveedores Listado</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
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