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
<title>Tallas | Lumens</title>
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
var codigoc2='';
 $(document).ready(function(){

 //$('#example').DataTable();

 $('ul.nav-tabs li a').click(function(){
    var activeTab = $(this).attr('href');
    if(activeTab=='#profile'){
      listar();
      ListarDetalle();
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
 
 
$("#btna2").hide();
$("#btnc2").hide();
 
 buscatallas();


});

function buscatallas(){ 
   $.ajax({ 
    data:{"buscatallas":"OK"},
     url:"Procesamiento/mdlTallas.php",
     type:"POST",
     success:function(resp){  
         $("#TipoTalla").html(resp)       
  },
  error:function(resp){ 
      swal("Error!","Error Al Conectarse Al Servidor", "error");
  } 
  });  
}

function Guardar(){ 

    if($("#Nombre").val()=='') {
      swal("Atención!","Escriba Tipo Talla","warning"); 
      return false;
    } 
    $('#btng').button('loading');

    $.ajax({ 
    data:{
      "Nombre":$("#Nombre").val(),
      'Ingresar':'OK'},   
     url:"Procesamiento/mdlTallas.php", 
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
function Guardar2(){ 

    if($("#Nombre2").val()=='') {
      swal("Atención!","Escriba  Talla","warning"); 
      return false;
    } 
    if($("#TipoTalla").val()=='') {
      swal("Atención!","Seleccione  Tipo Talla","warning"); 
      return false;
    } 
    $('#btng2').button('loading');

    $.ajax({ 
    data:{
      "Nombre":$("#Nombre2").val(),
      "TipoTalla":$("#TipoTalla").val(),
      'IngresarDetalle':'OK'},   
     url:"Procesamiento/mdlTallas.php", 
     type:"POST",   
     success:function(resp){    
           if(resp=="OK"){              
            swal("Registro Exitoso!","Se ha registrado   correctamente", "success");
            Nuevo2();
            limpiar2();
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btng2').button('reset');     
    },
    error:function(resp){
      $('#btng2').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
    } 
  });  
}


function actualizar(){ 

    if($("#Nombre").val()=='') {
      swal("Atención!","Escriba Tipo Talla","warning"); 
      return false;
    } 
  
    $('#btna').button('loading');
    $.ajax({

    data:{
      "Nombre":$("#Nombre").val(),
      codigoc:codigoc,
      'Actualizar':'OK'},
     url:"Procesamiento/mdlTallas.php",                                
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


function actualizar2(){ 

    if($("#Nombre2").val()=='') {
      swal("Atención!","Escriba Tipo Talla","warning"); 
      return false;
    } 
  
    if($("#TipoTalla").val()=='') {
      swal("Atención!","Seleccione  Tipo Talla","warning"); 
      return false;
    } 
    $('#btna2').button('loading');
    $.ajax({

    data:{
      "Nombre":$("#Nombre2").val(),
      "TipoTalla":$("#TipoTalla").val(),
      codigoc:codigoc2,
      'ActualizarDetalle':'OK'},
     url:"Procesamiento/mdlTallas.php",                                
     type:"POST",
     success:function(resp){    
        
     if(resp=="OK")
     {              
      swal("Registro Exitoso!","Se ha actualizado correctamente", "success");
      Nuevo2();
      limpiar2();
    }else{  
      swal("Atención!",resp, "error");           
    }   
    $('#btna2').button('reset');   
  },
  error:function(resp){
    $('#btna2').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
  }

  }); 
}

function listar(){ 
  var Datos = {"Listar" : 'OK'};  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlTallas.php", 
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
function ListarDetalle(){ 
  var Datos = {"ListarDetalle" : 'OK'};  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlTallas.php", 
          type:"POST",
        success:function(resp){
          $('#Listado2').html(resp);  
            $('#exampleDetalle').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     });
}

function Nuevo(){ limpiar();
 buscatallas();
}

function Nuevo2(){ limpiar2(); 
}

function limpiar(){
  $("#Nombre").val('');
  $("#hidenid").val('');  
  $("#btna").hide(); 
  $("#btnc").hide();
  $("#btng").show();  
}

function limpiar2(){
  $("#Nombre2").val('');
  $("#hidenid2").val('');  
  $("#TipoTalla").val('');  
  $("#btna2").hide(); 
  $("#btnc2").hide();
  $("#btng2").show();  
}

function Buscar_Datos(codigo){ 
  Codigo_Registro= codigo; 
  var Datos = {  "Buscar_Datos" : Codigo_Registro }; 
  $.ajax({
          data:Datos,
          url:"Procesamiento/mdlTallas.php",
          type:"POST",
        success:function(resp){ 

            if(resp=="n"){
                swal("Atención", "No se encontro resultado","error");
            }else{ 
                 var valores = eval(resp);      
                $("#hidenid").val(valores[0][0]);
                codigoc=valores[0][0];
                $("#Nombre").val(valores[0][1]);  
                $("#home-tab").click();
                $("#btng").hide();
                $("#btna").show();
                $("#btnc").show();    
                $('#Codigo').attr("disabled", true);
          
            }
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
   });   
}

function Buscar_DatosDetalle(codigo){
  Codigo_RegistroDetalle= codigo; 
  var Datos = {  "Buscar_DatosDetalle" : Codigo_RegistroDetalle }; 
  $.ajax({
          data:Datos,
          url:"Procesamiento/mdlTallas.php",
          type:"POST",
        success:function(resp){  
            if(resp=="n"){
                swal("Atención", "No se encontro resultado","error");
            }else{ 
                 var valores = eval(resp);      
                $("#hidenid2").val(valores[0][0]);
                codigoc2=valores[0][0];
                $("#Nombre2").val(valores[0][1]);  
                $("#TipoTalla").val(valores[0][2]);  
                $("#home-tab").click();
                $("#btng2").hide();
                $("#btna2").show();
                $("#btnc2").show();     
          
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
                                <h3 class="panel-title">Lumens | Tallas Formulario</h3> 
                            </div> 
                            <div class="panel-body" style="background-color: #fafafa"> 
    
   <div class="panel panel-success">
      <div class="panel-heading">
      <h3 style="color:  ">Tipo Tallas</h3>
      </div>
      <div class="panel-body">
                      <div class="single-bottom row" style="">

                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Escriba Tipo Talla</p>
                            <input id="Nombre"  type="text" name="Nombre" class="form-control" /> 
                            <input type="hidden" id="hidenid" />   
                          </div>

                              <div class="col-md-4">
                                <p><i class="fa fa-id-card"></i>  </p>
                                <button id="btng" type="button" class="btn btn-success" onclick="Guardar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>
                                <button id="btna" type="button" class="btn btn-success" onclick="actualizar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Actualizar</button>
                                <button id="btnc" type="button" class="btn btn-danger" onclick="limpiar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cancelar</button>
                          </div>
                      
                   

                      </div>
</div></div>

   <div class="panel panel-success">
      <div class="panel-heading">
      <h3 style="color:  ">Detalle Tallas</h3>
      </div>
      <div class="panel-body">
                      <div class="single-bottom row" style="">
                  <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Tipo Talla</p>
                         <?php     
                                  $consulta="SELECT cons, nombre FROM tallas  ORDER BY nombre ";   
                                  $datos=mysqli_query($mysqli,$consulta);
                                  echo ' <select id="TipoTalla" name="TipoTalla" class="form-control">
                                     <option  selected="selected" value="">Seleccionar...</option>';           
                                  while($row=mysqli_fetch_row($datos)){                               
                                      echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
                                  }
                                  echo ' </select>'; 
                        ?>
                          </div>

                          <div class="col-md-4">
                            <p><i class="fa fa-user-o"></i>Detalle Talla</p>
                            <input id="Nombre2"  type="text" name="Nombre2" class="form-control" /> 
                            <input type="hidden" id="hidenid2" />   
                          </div>

                              <div class="col-md-4">
                                <p><i class="fa fa-id-card"></i>  </p>
                                <button id="btng2" type="button" class="btn btn-success" onclick="Guardar2();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>
                                <button id="btna2" type="button" class="btn btn-success" onclick="actualizar2();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Actualizar</button>
                                <button id="btnc2" type="button" class="btn btn-danger" onclick="limpiar2();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cancelar</button>
                          </div>
                      
                   

                      </div>
</div></div>


                            </div> 
                      </div>
                        </p> 
                    </div> 

                    <div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab"> 
                            <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Tallas Listado</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
                                                <div class="single-bottom row" >
                                                        <div id="Listado" class="single-bottom row col-md-6" style=" border-right: 1px solid #629aa9"></div>  
                                                        <div id="Listado2" class="single-bottom row col-md-6" style=" border-left: 1px solid #629aa9 "></div>  
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