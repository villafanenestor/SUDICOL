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
<title>Rotacion | Lumens</title>
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
 
 $("#Almacen").change(buscaElementosAlmacen);
 $("#Elemento").change(buscaTallaxElementosAlmacen);
 $("#Talla").change(cambiotalla);
 
listadoenvios();

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
 

function Guardar(){




    if($("#Almacen").val()=='') {
      swal("Atención!","Seleccione Almacen Origen","warning"); 
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
  
 
    if($("#Cantidad").val()=='' || $("#Cantidad").val()=='0') {
      swal("Atención!","Escriba Cantidad","warning"); 
      return false;
    } 

    if($("#Disponibles").val()=='' || $("#Disponibles").val()=='0') {
      swal("Atención!","No Hay Elemento Disponible","warning"); 
      return false;
    } 
    if( parseInt($("#Cantidad").val()) > parseInt($("#Disponibles").val()) ) {
      swal("Atención!","Cantidad Incorrecta","warning"); 
      return false;
    }  
 

    if($("#AlmacenDestino").val()=='') {
      swal("Atención!","Seleccione Almacen Destino","warning"); 
      return false;
    } 


    if($("#Soportes").val()=='') {
      swal("Atención!","Seleccione Soporte","warning"); 
      return false;
    } 
 


    if($("#Almacen").val()==$("#AlmacenDestino").val() ) {
      swal("Atención!","Los almacenes deben ser diferentes","warning"); 
      return false;
    } 
 


 
      var archivos = document.getElementById("Soportes");//Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
       var data = new FormData();  
 
      data.append('archivo',archivo[0]);  
      data.append('CrearRotacion',"OK");
      data.append('Almacen',$("#Almacen").val());  
      data.append('AlmacenDestino',$("#AlmacenDestino").val());  
      data.append('Elemento',$("#Elemento").val());  
      data.append('Talla',$("#Talla").val());  
      data.append('Cantidad',$("#Cantidad").val());  
      data.append('Observaciones',$("#Observaciones").val());   
      data.append('Usuario',"<?php echo $_SESSION['STCK-USER_USUARIO'];?>");  
      $('#btnBuscar').button('loading'); 
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
                 $("#AlmacenDestino").val("");   
                 $("#Cantidad").val("");    
                 buscaTallaxElementosAlmacen(); 
                 listadoenvios(); 
                 swal("Registro Exitoso!","Se ha registrado correctamente", "success");  
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btnBuscar').button('reset'); 
      }).fail(  function(XMLHttpRequest, textStatus, errorThrown){
      $('#btnBuscar').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
      });  
 



  }


function listadoenvios(){  

    var Datos = {
      "listadoenvios" : "<?php echo $_SESSION['STCK-USER_USUARIO'];?>"
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#Listado').html(resp);  
              $('#exampleListadoenvios').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}


function GenerarReporteAU(){  

if ( $("#AlmacenDestinoAU").val()=="") {
      swal("Atención!","Seleccione Almacen Destino","warning"); 
  return false;
}
      $('#btnBuscarAU').button('loading'); 
    var Datos = {
      "GenerarReporteAU" : "OK",
      "Almacen" : $("#AlmacenAU").val(),
      "AlmacenDestino" : $("#AlmacenDestinoAU").val(),
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#btnBuscarAU').button('reset'); 
              $('#ListadoReporte').html(resp);  
              $('#exampleReporteAU').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}
 
 function gestionarRotacion(codigo){ 
    var Datos = {   
      "gestionarRotacion" : codigo
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
                codigoc=codigo;
                var valores = eval(resp);      
                $("#divFecha").html(valores[0][0]);
                $("#divUsuario").html(valores[0][1]);
                $("#divAlmacen").html(valores[0][2]);
                $("#divElemento").html(valores[0][3]);   
                $("#divTalla").html(valores[0][4]);   
                $("#divCantidad").html(valores[0][5]);   
                $("#divAlmacenDestino").html(valores[0][6]);   
                $("#divSoporte").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Rotacion/"+valores[0][7]+"' target='_blank'>Descargar</a>");   
                $("#divObservaciones").html(valores[0][8]);    
 

                $('#myModal').modal('show'); 
                $("#SoportesAU").val("");    
                $("#ObservacionesAU").val("");    
                $("#EstadoAU").val("");    
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error"); 
        }
     });

 }



function guardaGestionarRotacion(){ 

    if($("#EstadoAU").val()=='') {
      swal("Atención!","Seleccione Estado","warning"); 
      return false;
    }  

    if($("#SoportesAU").val()=='') {
      swal("Atención!","Seleccione Soporte","warning"); 
      return false;
    }  

      var archivos = document.getElementById("SoportesAU");//Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
      var data = new FormData();  
 
      data.append('archivo',archivo[0]);  
      data.append('guardaGestionarRotacion',"OK");
      data.append('Estado',$("#EstadoAU").val());   
      data.append('Observaciones',$("#ObservacionesAU").val());   
      data.append('codigo',codigoc);   
      data.append('Usuario',"<?php echo $_SESSION['STCK-USER_USUARIO'];?>");  
      $('#btnguardar').button('loading'); 
      $("#SoportesAU").val("");
      $.ajax({  
      url:'Procesamiento/mdlInventario.php', //Url a donde la enviaremos
      type:'POST', //Metodo que usaremos
      contentType:false, //Debe estar en false para que pase el objeto sin procesar
      data:data, //Le pasamos el objeto que creamos con los archivos
      processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
      cache:false //Para que el formulario no guarde cache    
      }).done(function(resp){   
           if(resp=="OK"){        
                 $("#EstadoAU").val("");   
                 $("#ObservacionesAU").val("");     
                 swal("Registro Exitoso!","Se ha registrado correctamente", "success");  
          }else{  
            swal("Atención!",resp, "error");          
          }   
          $('#btnguardar').button('reset'); 
      }).fail(  function(XMLHttpRequest, textStatus, errorThrown){
      $('#btnguardar').button('reset');
        swal("Error!","Verifique su conexion a internet", "error");
      });  
   
  }




function GenerarReporteRE(){  

      $('#btnBuscarRE').button('loading'); 
    var Datos = {
      "GenerarReporteRE" : "OK",
      "Almacen" : $("#AlmacenRE").val(),
      "AlmacenDestino" : $("#AlmacenDestinoRE").val(),
      "Estado" : $("#EstadoRE").val(),
      "Desde" : $("#Desde").val(),
      "Hasta" : $("#Hasta").val(),
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
              $('#btnBuscarRE').button('reset'); 
              $('#ListadoReporteRE').html(resp);  
              $('#exampleReporteRE').DataTable();
        },
        error:function(resp){
        swal("Error!","Verifique su conexion a internet", "error");
        }
     }); 
}

function ExportarReporteRE()
{
 window.open("Reportes/rptDetalleRotacion.php?Almacen="+$("#AlmacenRE").val()+'&AlmacenDestino='+$("#AlmacenDestinoRE").val()+'&Estado='+$("#EstadoRE").val()+'&Hasta='+$("#Hasta").val()+'&Desde='+$("#Desde").val()  ); 

} 



function gestionarRotacionRE(codigo){ 
    var Datos = {   
      "gestionarRotacion" : codigo
    };  
    $.ajax({
          data:Datos,
          url:"Procesamiento/mdlInventario.php", 
          type:"POST",
        success:function(resp){ 
                codigoc=codigo;
                var valores = eval(resp);      
                $("#divFecha2").html(valores[0][0]);
                $("#divUsuario2").html(valores[0][1]);
                $("#divAlmacen2").html(valores[0][2]);
                $("#divElemento2").html(valores[0][3]);   
                $("#divTalla2").html(valores[0][4]);   
                $("#divCantidad2").html(valores[0][5]);   
                $("#divAlmacenDestino2").html(valores[0][6]);   
                $("#divSoporte2").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Rotacion/"+valores[0][7]+"' target='_blank'>Descargar</a>");   
                $("#divObservaciones2").html(valores[0][8]);    
                $("#divEstado").html(valores[0][9]);    
                $("#divEstadoFecha").html(valores[0][10]);    
                $("#divEstadoUsuario").html(valores[0][11]);    
                $("#divEstadoSoporte").html("<a href='https://storage.googleapis.com/lumensarchivostemporales/Stock/Rotacion/"+valores[0][12]+"' target='_blank'>Descargar</a>");   
                $("#divEstadoObservaciones").html(valores[0][13]);   
                $('#myModalRE').modal('show');  
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
  width: 60%;
  padding: 0;
}

.modal-content { 
  border-radius: 0;
}
</style> 

<body class="cbp-spmenu-push">
	<div class="main-content" >




<div class="modal fade" id="myModalRE" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">


                      <div class="single-bottom row" style="">   
                          
                          <div class="col-md-6">
                            <p>Fecha: <span id="divFecha2">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Usuario: <span id="divUsuario2">xxxxxxxxxx</span></p> 
                          </div> 
                          <div class="col-md-6">
                            <p>Almacen Origen: <span id="divAlmacen2">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Elemento: <span id="divElemento2">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Talla: <span id="divTalla2">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Cantidad: <span id="divCantidad2">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Almacen Destino: <span id="divAlmacenDestino2">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Soporte: <span id="divSoporte2">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-12">
                            <p>Observaciones: <span id="divObservaciones2">xxxxxxxxxx</span></p> 
                          </div>


                          <div class="col-md-12" ><h3 class="title1" style="margin-bottom: 0em !important;margin-top: 10px !important; ">Recibir Rotacion</h3></div>
                          
                           <div class="col-md-6">
                            <p>Estado: <span id="divEstado">xxxxxxxxxx</span></p> 
                          </div>

                             <div class="col-md-6">
                            <p>Fecha Estado: <span id="divEstadoFecha">xxxxxxxxxx</span></p> 
                          </div>
                             <div class="col-md-6">
                            <p>Usuario Estado: <span id="divEstadoUsuario">xxxxxxxxxx</span></p> 
                          </div>


                                   <div class="col-md-6">
                            <p>Soporte: <span id="divEstadoSoporte">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-12">
                            <p>Observaciones: <span id="divEstadoObservaciones">xxxxxxxxxx</span></p> 
                          </div>




                       
 

                     <br><br>

                    <div class="clearfix"> </div>
      </div>
    </div>
  </div>
</div>
</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"> 
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">


                      <div class="single-bottom row" style="">   
                          
                          <div class="col-md-6">
                            <p>Fecha: <span id="divFecha">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Usuario: <span id="divUsuario">xxxxxxxxxx</span></p> 
                          </div> 
                          <div class="col-md-6">
                            <p>Almacen Origen: <span id="divAlmacen">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Elemento: <span id="divElemento">xxxxxxxxxx</span></p> 
                          </div>

                          <div class="col-md-6">
                            <p>Talla: <span id="divTalla">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Cantidad: <span id="divCantidad">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Almacen Destino: <span id="divAlmacenDestino">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-6">
                            <p>Soporte: <span id="divSoporte">xxxxxxxxxx</span></p> 
                          </div>
                          <div class="col-md-12">
                            <p>Observaciones: <span id="divObservaciones">xxxxxxxxxx</span></p> 
                          </div>


                          <div class="col-md-12" ><h3 class="title1" style="margin-bottom: 0em !important;margin-top: 10px !important; ">Recibir Rotacion</h3></div>
                             <div class="col-md-7">
                            <p><i class="fa fa-user-o"></i>Soporte</p>
                          <input id="SoportesAU" type="file"   name="SoportesAU" class="form-control" /> 
                          </div>    


                          <div class="col-md-5">
                            <p><i class="fa fa-user-o"></i>Estado</p>
                                <select id="EstadoAU" name="EstadoAU" class="form-control"> 
                                 <option  selected="selected" value="">Seleccionar...</option> 
                                 <option  value="Aprobado">Aprobado</option> 
                                 <option  value="Rechazado">Rechazado</option> 
                                </select>
                          </div>

                          <div class="col-md-12">
                            <p><i class="fa fa-user-o"></i>Observaciones</p>
                            <input id="ObservacionesAU"  type="text" name="ObservacionesAU" class="form-control" />  
                          </div>



                              <div class="col-md-4">
                                <p><i class="fa fa-id-card"></i>  </p>
                                <button id="btnguardar" type="button" class="btn btn-success" onclick="guardaGestionarRotacion();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>  
                          </div>

                     <br><br>

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
                             <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Formulario de ingreso</a></li>
                             <li role="presentation" class=""><a href="#Autorizaciones" role="tab" id="Autorizaciones-tab" data-toggle="tab" aria-controls="Autorizaciones" aria-expanded="true">Autorizaciones</a></li>
                             <li role="presentation" class=""><a href="#Reporte" role="tab" id="Reporte-tab" data-toggle="tab" aria-controls="Reporte" aria-expanded="true">Reporte</a></li>
                        </ul>


                    <div id="myTabContent" class="tab-content scrollbar1"> 
 
   <div role="tabpanel" class="tab-pane fade " id="Reporte" aria-labelledby="Reporte-tab"> 
                       <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Reporte Entregas</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
 
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen Origen</p>
      <?php     
        $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="AlmacenRE" name="AlmacenRE" class="form-control">
           <option  selected="selected" value="Todos">Todos...</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
  </div>

  
            
    <div class="col-md-3">
     <p><i class="fa fa-user-o"></i>Almacen Destino</p>
        <?php     
          $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <select id="AlmacenDestinoRE" name="AlmacenDestinoRE" class="form-control">
             <option  selected="selected" value="Todos">Todos...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 
        ?>
    </div>
            <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Estado</p>
                                <select id="EstadoRE" name="EstadoRE" class="form-control"> 
                                 <option  selected="selected" value="Todos">Todos...</option> 
                                 <option  value="Enviado">Enviado</option> 
                                 <option  value="Aprobado">Aprobado</option> 
                                 <option  value="Rechazado">Rechazado</option> 
                                </select>
                          </div> 
                    <div class="clearfix"> </div>
    <div class="col-md-3" id="divResumen1">
                          <p><i class="fa fa-user-o"></i>Desde </p>
                          <input id="Desde" type="date" name="Desde" class="form-control" />
                        </div>
                        <div class="col-md-3" id="divResumen2">
                          <p><i class="fa fa-user-o"></i>Hasta</p>
                          <input id="Hasta" type="date" name="Hasta" class="form-control" />
                        </div>
 <div class="col-md-6 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="GenerarReporteRE();"  id="btnBuscarRE">Generar Reporte</button>  
                      <button type="button" class="btn btn-success" onclick="ExportarReporteRE();"  id="btnBuscarRE">Exportar Reporte</button>  
                      </center>
                    </div>

                                        </div>

                                           <div class="single-bottom row" >
                                                        <div id="ListadoReporteRE" class="single-bottom row" style=""> </div>  
                                                </div>  

                                      </div>

                                    </p>
                    </div>








   <div role="tabpanel" class="tab-pane fade " id="Autorizaciones" aria-labelledby="Autorizaciones-tab"> 
                       <p>

                                    <div class="panel panel-primary"> 
                                        <div class="panel-heading"  style="background-color: #222D32 !important"> 
                                            <h3 class="panel-title">Lumens |  Autorizaciones Entregas</h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
 
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen Origen</p>
      <?php     
        $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="AlmacenAU" name="AlmacenAU" class="form-control">
           <option  selected="selected" value="Todos">Todos...</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
  </div>
 
            
    <div class="col-md-3">
     <p><i class="fa fa-user-o"></i>Almacen Destino</p>
        <?php     
          $consulta="SELECT cons, nombre FROM almacenes where encargado='".$_SESSION['STCK-USER_USUARIO']."'  or encargado='' or encargado is null ORDER BY nombre ";   
          $datos=mysqli_query($mysqli,$consulta);
          echo ' <select id="AlmacenDestinoAU" name="AlmacenDestinoAU" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>';           
          while($row=mysqli_fetch_row($datos)){                               
              echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
          }
          echo ' </select>'; 
        ?>
    </div>

 <div class="col-md-1 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="GenerarReporteAU();"  id="btnBuscarAU">Generar Reporte</button>  
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
                                            <h3 class="panel-title">Lumens |  Rotacion </h3> 
                                        </div> 
                                        <div class="panel-body" style="background-color: #fafafa"> 
 
 
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen Origen</p>
      <?php     
        $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
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
        <p><i class="fa fa-user-o"></i>Cantidad Rotacion</p>
        <input id="Cantidad" type="number" name="Cantidad"  class="form-control" />
      </div>
 
            
     <div class="col-md-3">
   <p><i class="fa fa-user-o"></i>Almacen Destino</p>
      <?php     
        $consulta="SELECT cons, nombre FROM almacenes  ORDER BY nombre ";   
        $datos=mysqli_query($mysqli,$consulta);
        echo ' <select id="AlmacenDestino" name="AlmacenDestino" class="form-control">
           <option  selected="selected" value="">Seleccionar...</option>';           
        while($row=mysqli_fetch_row($datos)){                               
            echo '<option   value="'.$row[0].'">'.$row[1].'</option>';    
        }
        echo ' </select>'; 
      ?>
  </div>
   <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Soporte</p>
                          <input id="Soportes" type="file"   name="Soportes" class="form-control" /> 
                          </div>
     <div class="col-md-6"   >
        <p><i class="fa fa-user-o"></i>Observaciones</p>
        <input id="Observaciones" type="text" name="Observaciones"  class="form-control" />
      </div>
                          

                          <div class="col-md-1 "><br>
                                  <center>
                      <button type="button" class="btn btn-primary" onclick="Guardar();"  id="btnBuscar">Guardar Rotacion</button>  
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