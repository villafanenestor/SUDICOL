<?php
session_start();
if (empty($_SESSION['STCK-USER_USUARIO'])) {
  echo "<script>document.location=('../');</script>";
} else {

  require_once("Procesamiento/Conexion.php");
}
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>Ordenes De Compra | SUDICOL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="application/x-javascript">
    addEventListener("load", function() {
      setTimeout(hideURLbar, 0);
    }, false);

    function hideURLbar() {
      window.scrollTo(0, 1);
    }
  </script>
  <!-- Bootstrap Core CSS -->
  <link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
  <!-- Custom CSS -->
  <link href="css/style.css" rel='stylesheet' type='text/css' />
  <!-- font-awesome icons CSS -->
  <link href="css/font-awesome.css" rel="stylesheet">
  <!-- //font-awesome icons CSS-->
  <!-- side nav css file -->
  <link href='css/SidebarNav.min.css' media='all' rel='stylesheet' type='text/css' />
  <!-- //side nav css file -->
  <!-- js-->
  <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
  <script src="js/funcionesGenericas.js"></script>
  <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
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
    var codigoc = '';
    var codigoOrdenAdd = '';
    let codOrdenCompra = '';//Esta varible solo se utiliza para cargar elementos por archivos a orden de compra y para editar orden de compras
    $(document).ready(function() {
      ocultarBtnsEditarOrdenCompra();

      //$('#example').DataTable();

      $('ul.nav-tabs li a').click(function() {
        var activeTab = $(this).attr('href');
        if (activeTab == '#profile') {
          listar();
          Nuevo();
        }
        if (activeTab == '#help') {
          Nuevo();
        }
      });

      var f = new Date();
      dia = "";
      if (f.getDate() < 10) {
        dia = "0" + f.getDate();
      } else {
        dia = f.getDate();
      }

      mes = "";
      if ((f.getMonth() + 1) < 10) {
        mes = "0" + (f.getMonth() + 1);
      } else {
        mes = (f.getMonth() + 1);
      }
      $("#FechaEntrega").val(f.getFullYear() + "-" + mes + "-" + dia);
      $("#fechaRecepcion").val(f.getFullYear() + "-" + mes + "-" + dia);

      $("#btnFinalizar").hide();
      $("#Elementos").change(buscatallas);
      $("#panelElementos").hide();


    });


    function buscatallas() {
      $("#Talla").html(' <option  selected="selected" value="">Cargando...</option>')
      $.ajax({
        data: {
          "buscatallasxProducto": $("#Elementos").val()
        },
        url: "Procesamiento/mdlTallas.php",
        type: "POST",
        success: function(resp) {
          $("#Talla").html(resp)
        },
        error: function(resp) {
          swal("Error!", "Error Al Conectarse Al Servidor", "error");
        }
      });
    }



    function Guardar() {
      const fechaRecepcion = $("#fechaRecepcion").val();
      const ordenCompra = $("#ordenCompra").val();
      const departamentoEntrega = $("#departamentoEntrega").val();
      const valorTotal = $("#valorTotal").val();
      const costo = $("#costos").val();
      const plazoPago = $("#plazoPago").val();
      const subCliente = $("#subCliente").val();


      console.log('costos: ',costo);

      if (!validarCampo(fechaRecepcion, "Fecha de Recepcion")) return false;
      if (!validarCampo(ordenCompra, "Orden de Compra")) return false;
      if (!validarCampo(departamentoEntrega, "Departamento de Entrega")) return false;
      if (!validarCampo(valorTotal, "Valor Orden")) return false;
      if (!validarCampo(costo, "costos")) return false;
      if (!validarCampo(plazoPago, "Plazo de Pago")) return false;



      if ($("#FechaEntrega").val() == '') {
        swal("Atención!", "Seleccione Fecha Entrega", "warning");
        return false;
      }

      if ($("#Formadepago").val() == '') {
        swal("Atención!", "Seleccione Forma de pago", "warning");
        return false;
      }

      if ($("#Proyecto").val() == '') {
        swal("Atención!", "Seleccione Proyecto", "warning");
        return false;
      }

      // if ($("#solicitud").val() == '') {
      //   swal("Atención!", "Seleccione archivo de solicitud", "warning");
      //   return false;
      // }


      var archivos = document.getElementById("solicitud"); //Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
      var data = new FormData();

      data.append('archivo', archivo[0]);
      data.append('Ingresar', "OK");
      data.append('Formadepago', $("#Formadepago").val());
      data.append('FechaEntrega', $("#FechaEntrega").val());
      data.append('Proyecto', $("#Proyecto").val());
      data.append('Observaciones', $("#Observaciones").val());
      data.append('Usuario', "<?php echo $_SESSION['STCK-USER_USUARIO']; ?>");
      data.append('fechaRecepcion', fechaRecepcion);
      data.append('ordenCompra', ordenCompra);
      data.append('departamentoEntrega', departamentoEntrega);
      data.append('valorTotal', valorTotal);
      data.append('costos', costo);
      data.append('plazoPago', plazoPago);
      data.append('subCliente', subCliente);
      $("#solicitud").val("");
      $('#btng').button('loading');


      $.ajax({
        url: 'Procesamiento/mdlOrdenesDeCompra.php', //Url a donde la enviaremos
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        data: data, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache    
      }).done(function(resp) {
        if (resp > 1) {
          $("#btng").hide();
          $("#tablistado").hide();
          $("#Masivo-tab").hide();
          $("#panelElementos").show();
          $("#btnFinalizar").show();
          $('#Formadepago').attr("disabled", true);
          $('#FechaEntrega').attr("disabled", true);
          $('#Proyecto').attr("disabled", true);
          $('#Observaciones').attr("disabled", true);
          $('#solicitud').attr("disabled", true);
          $('#fechaRecepcion').attr("disabled", true);
          $('#ordenCompra').attr("disabled", true);
          $('#departamentoEntrega').attr("disabled", true);
          $('#valorTotal').attr("disabled", true);
          // $('#costos').attr("disabled", true);
          $('#costos, #plazoPago, #subCliente').attr("disabled", true);
          codigoOrdenAdd = resp;
          cargarElementosOrden();
          swal("Registro Exitoso!", "Orden " + codigoOrdenAdd + " Se ha registrado correctamente , añada elementos", "success");
        } else {
          swal("Atención!", resp, "error");
        }
        $('#btng').button('reset');
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        $('#btng').button('reset');
        swal("Error!", "Verifique su conexion a internet", "error");
      });
    }


    
    function addProducto() {
      const valorProducto = $("#valorProducto").val();
      const costoElemento = $("#costoElemento").val();
      const procesoElemento = $("#procesoElemento").val();
      
      

      // console.log({procesoElemento});
      


      if ($("#Elementos").val() == '') {
        swal("Atención!", "Seleccione Elemento", "warning");
        return false;
      }
      
      if ($("#Talla").val() == '') {
        swal("Atención!", "Seleccione Talla", "warning");
        return false;
      }

      if (!$("#procesoElemento").val()) {
        swal("Atención!", "Seleccione por lo menos un Proceso", "warning");
        return false;
      }
      if ($("#Cantidad").val() == '') {
        swal("Atención!", "Escriba Cantidad", "warning");
        return false;
      }
      if (!validarCampo(valorProducto, "Valor Producto")) return false;
      if (!validarCampo(costoElemento,"Costo Elemento")) return false;


      $('#addProducto').button('loading');
      $.ajax({

        data: {
          valorProducto,
          "Elementos": $("#Elementos").val(),
          "Talla": $("#Talla").val(),
          "Cantidad": $("#Cantidad").val(),
          "codigoOrdenAdd": codigoOrdenAdd,
          costoElemento, 
          procesoElemento,
          'addProducto': 'OK'
        },
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {

          if (resp == "OK") {
            cargarElementosOrden();
            $("#Cantidad, #procesoElemento, #costoElemento, #valorProducto, #Elementos, #Talla").val("");
          } else {
            swal("Atención!", resp, "error");
          }
          $('#addProducto').button('reset');
        },
        error: function(resp) {
          $('#addProducto').button('reset');
          swal("Error!", "Verifique su conexion a internet", "error");
        }

      });
    }


    function cargarElementosOrden() {
      var Datos = {
        "cargarElementosOrden": 'OK',
        "Tipo": 'edit',
        "codigoOrdenAdd": codigoOrdenAdd
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {
          $('#Listadoelementos').html(resp);
          $('#exampleElementosOrden').DataTable();
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }

    function cargarElementosOrdenModal(codigoOrdenModal) {
      var Datos = {
        "cargarElementosOrden": 'OK',
        "Tipo": 'modal',
        "codigoOrdenAdd": codigoOrdenModal
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {
          $('#ListadoDetalle').html(resp);
          $('#exampleElementosDetalle').DataTable();
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }



    function quitarElemento(codigobtn) {
      swal({
        title: "Atención",
        text: "¿Confirma Quitar El Elemento?",
        icon: "error",
        buttons: true,
        dangerMode: false,
      }).then((willDelete) => {
        if (willDelete) {
          var parametros = {
            "quitarElemento": codigobtn
          };
          $.ajax({
            data: parametros,
            url: "Procesamiento/mdlOrdenesDeCompra.php",
            type: "POST",
            success: function(resp) {
              if (resp == "OK") {
                cargarElementosOrden();
              } else {
                swal("Atención", resp, "error");
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              swal("Atención", "Status: " + textStatus + " | Error: " + errorThrown + " | Error: " + XMLHttpRequest.responseText, "error");
            }
          });
        } else {
          return false;
        }
      });
    }

    function actualizar() {

      if ($("#Nombre").val() == '') {
        swal("Atención!", "Escriba Nombre", "warning");
        return false;
      }

      if ($("#Cedula").val() == '') {
        swal("Atención!", "Escriba Cedula", "warning");
        return false;
      }

      if ($("#Genero").val() == '') {
        swal("Atención!", "Seleccione Genero", "warning");
        return false;
      }

      if ($("#Proyecto").val() == '') {
        swal("Atención!", "Seleccione Proyecto", "warning");
        return false;
      }

      if ($("#Cargo").val() == '') {
        swal("Atención!", "Seleccione Cargo", "warning");
        return false;
      }

      $('#btna').button('loading');
      $.ajax({

        data: {
          "Cedula": $("#Cedula").val(),
          "Nombre": $("#Nombre").val(),
          "Telefono": $("#Telefono").val(),
          "Genero": $("#Genero").val(),
          "Proyecto": $("#Proyecto").val(),
          "Cargo": $("#Cargo").val(),
          "Camisa": $("#Camisa").val(),
          "Pantalon": $("#Pantalon").val(),
          "Zapatos": $("#Zapatos").val(),
          "Estado": $("#Estado").val(),
          "FechaContratoI": $("#FechaContratoI").val(),
          "FechaContratoF": $("#FechaContratoF").val(),
          "Labor": $("#Labor").val(),
          "Observaciones": $("#Observaciones").val(),
          codigoc: codigoc,
          'Actualizar': 'OK'
        },
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {

          if (resp == "OK") {
            swal("Registro Exitoso!", "Se ha actualizado correctamente", "success");
            Nuevo();
            limpiar();
          } else {
            swal("Atención!", resp, "error");
          }
          $('#btna').button('reset');
        },
        error: function(resp) {
          $('#btna').button('reset');
          swal("Error!", "Verifique su conexion a internet", "error");
        }

      });
    }

    function AnularOC(codigobtn) {
      swal({
        title: "Atención",
        text: "¿Confirma Anulacion De La Orden De Compra?",
        icon: "error",
        buttons: true,
        dangerMode: false,
      }).then((willDelete) => {
        if (willDelete) {
          var parametros = {
            "AnularOC": codigobtn
          };
          $.ajax({
            data: parametros,
            url: "Procesamiento/mdlOrdenesDeCompra.php",
            type: "POST",
            success: function(resp) {
              if (resp == "OK") {
                listar();
              } else {
                swal("Atención", "No se pudo anular", "error");
              }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
              swal("Atención", "Status: " + textStatus + " | Error: " + errorThrown + " | Error: " + XMLHttpRequest.responseText, "error");
            }
          });
        } else {
          return false;
        }
      });
    }

    function listar() {
      var Datos = {
        "Listar": 'OK',
        "Usuario": "<?php echo $_SESSION['STCK-USER_USUARIO']; ?>"
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {
          $('#Listado').html(resp);
          $('#example').DataTable();
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }


    function Finalizar() {

      swal("Registro Exitoso!", "Orden " + codigoOrdenAdd + " Finalizada Con Exito", "success");
      var f = new Date();
      dia = "";
      if (f.getDate() < 10) {
        dia = "0" + f.getDate();
      } else {
        dia = f.getDate();
      }
      mes = "";
      if ((f.getMonth() + 1) < 10) {
        mes = "0" + (f.getMonth() + 1);
      } else {
        mes = (f.getMonth() + 1);
      }
      $("#FechaEntrega").val(f.getFullYear() + "-" + mes + "-" + dia);
      $("#btng").show();
      $('#Formadepago').attr("disabled", false);
      $('#FechaEntrega').attr("disabled", false);
      $('#Proyecto').attr("disabled", false);
      $('#Observaciones').attr("disabled", false);
      $('#solicitud').attr("disabled", false).val("");
      $('#fechaRecepcion').attr("disabled", false).val(f.getFullYear() + "-" + mes + "-" + dia);
      $('#departamentoEntrega').attr("disabled", false).val("");
      $('#ordenCompra').attr("disabled", false).val("");
      $('#valorTotal, #costos, #plazoPago, #subCliente').attr("disabled", false).val("");
      $("#Formadepago").val('');
      $("#Proyecto").val('');
      $("#Observaciones").val('');
      $("#solicitud").val('');
      $("#Elementos").val('');
      $("#Talla").html('<select id="Talla" name="Talla" class="form-control"><option  selected="selected" value="">Seleccionar...</option> </select> ');
      $("#Cantidad").val('');
      $("#solicitud").val('');
      $("#btnFinalizar").hide();
      $("#panelElementos").hide();
      // $("#tablistado").show();
      $("#tablistado, #Masivo-tab").show();
      codigoOrdenAdd = '';
    }

    function Buscar_Datos(codigo) {
      Codigo_Registro = codigo;

      cargarElementosOrdenModal(Codigo_Registro);
      var Datos = {
        "Buscar_Datos": Codigo_Registro
      };
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {

          if (resp == "n") {
            swal("Atención", "No se encontro resultado", "error");
          } else {
            var valores = eval(resp);
            $("#divCodigo").html(valores[0][0]);
            codigoc = valores[0][0];
            $("#divUsuario").html(valores[0][1]);
            $("#divCreacion").html(valores[0][2]);
            $("#divForma").html(valores[0][3]);
            $("#divEntrega").html(valores[0][4]);
            $("#divProyecto").html(valores[0][5]);
            $("#divsolicitud").html("<a href='https://storage.googleapis.com/SUDICOLarchivostemporales/Stock/solicitud/" + valores[0][6] + "' target='_blank'>Descargar</a>");
            $("#divSoporte").html("");
            clase = valores[0][7];
            if (valores[0][7] == "Aprobado") {
              $("#divSoporte").html("<a href='https://storage.googleapis.com/SUDICOLarchivostemporales/Stock/Ordenes/" + valores[0][13] + "' target='_blank'>Descargar</a>");
              clase = '<span style="background-color: #5cb85c;color:white;text-align:center">' + valores[0][7] + '</span>';
            }
            if (valores[0][7] == "Anulado") {
              clase = '<span style="background-color: #f0ad4e;color:white;text-align:center">' + valores[0][7] + '</span>';
            }
            if (valores[0][7] == "Rechazado") {
              $("#divSoporte").html("<a href='https://storage.googleapis.com/SUDICOLarchivostemporales/Stock/Ordenes/" + valores[0][13] + "' target='_blank'>Descargar</a>");
              clase = '<span style="background-color: #d9534f;color:white;text-align:center">' + valores[0][7] + '</span>';
            }
            $("#divEstado").html(clase);
            $("#divFechaEstado").html(valores[0][8]);
            $("#divObservaciones").html(valores[0][9]);
            $("#divProveedores").html(valores[0][10]);
            $("#divObservacionesResp").html(valores[0][11]);
            $("#divUsuarioEstado").html(valores[0][12]);
            clase = valores[0][14];
            if (valores[0][14] == "Cerrado") {
              clase = '<span style="background-color: #5cb85c;color:white;text-align:center">' + valores[0][14] + '</span>';
            }
            if (valores[0][14] == "Abierto") {
              clase = '<span style="background-color: #d9534f;color:white;text-align:center">' + valores[0][14] + '</span>';

            }

            $("#divIngresoEstado").html(clase);
            $('#myModal').modal('show');

          }
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }


    const cargarOrdenesPorArchivo = () => {
      $("#ListadoResp").html('');
      const excel = $("#Base").prop('files')[0];
      if (!excel) {
        swal("Error!", "No selecciono archivo", "error");
        return false
      };
      $("#Base").val("");
      $("#ListadoResp").html('<center><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i> Cargando Archivo, por favor espere...</center>');
      var data = new FormData();
      data.append('option', "cargarOrdenesPorArchivo");
      data.append('archivoCargarOrdenesPorArchivo', excel);
      data.append('usuario', "<?php echo $_SESSION['STCK-USER_USUARIO']; ?>");
      $("#btnCargarOrdenesArchivo").prop('disabled', true);
      $.ajax({
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto  sin procesar
        data: data, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache		
      }).done(function(resp) {
        console.log({
          resp
        });
        $("#ListadoResp").html('');
        $("#ListadoResp").html(resp.errores);
        swal("Atención", `Se cargaron ${resp.contadorGuardados} registros de ${resp.totalRegistros}`, "success");
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        $("#ListadoResp").html('');
        swal("Atención", "Status: " + textStatus + " | Error: " + errorThrown + " | Error: " + XMLHttpRequest.responseText, "error");
      });

      $("#btnCargarOrdenesArchivo").prop('disabled', false);


    }


    const mostrarModalCargarElementoExcel = (consecutivoOrdenCompra) => {
      codOrdenCompra = consecutivoOrdenCompra;
      $("#divAddProductoExcel").html('');
      $("#divScroll").removeClass("myScroll")
      $('#modalAddProductoExcel').modal('show');

    }

    const addProductoExcel = () =>{
      const operacion = $("#operacionAddProductoExcel").val();
      const excel = $("#archivoAddProductoExcel").prop('files')[0];
      $("#divAddProductoExcel").html('');
      if(!validarCampo(operacion, "Operacion")) return false;
      if (!excel) {
        swal("Error!", "No selecciono archivo", "error");
        return false
      };
      $("#divAddProductoExcel").html('<center><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i> Trabajando, por favor espere...</center>');
      const data = new FormData();
      data.append('option', 'addProductosExcel');
      data.append('operacion', operacion);
      data.append('archivoAddProductoExcel', excel);
      data.append('codOrdenCompra', codOrdenCompra);

      $("#archivoAddProductoExcel").val('');
      $.ajax({
        url: 'Procesamiento/mdlOrdenesDeCompra.php', //Url a donde la enviaremos
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        data: data, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache    
      }).done(function(resp) {
        console.log(resp);
        const {contadorGuardados="0", totalRegistros="0",errores} = resp;
        
        swal("Atención", `Se cargaron ${contadorGuardados} registros de ${totalRegistros}`, "success");
        $("#divAddProductoExcel").html(`<center>${errores}</center>`);
        $("#divScroll").addClass("myScroll")
        console.log(resp);
        $('#btngAddProductoExcel').button('reset');
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        $("#divAddProductoExcel").html('');
        $('#btngAddProductoExcel').button('reset');
        swal("Error!", "Verifique su conexion a internet", "error");
      });
    }

    const cerrarModal = (idModal)=>{
      $(`#${idModal}`).modal('hide');
    } 

    const mostrarFormuarlioEditarOrdenCompra = (ordencompra)=>{
      codOrdenCompra = ordencompra;
      codigoOrdenAdd = ordencompra;
      const data = {
        option : 'consultarDatosOrdenCompra',
        codOrdenCompra
      };
      $.ajax({
        data,
        url: "Procesamiento/mdlOrdenesDeCompra.php",
        type: "POST",
        success: function(resp) {
          console.log(resp);
          const {FechaEntrega,Formadepago,Observaciones,Proyecto,archivoSubido,costos,departamentoEntrega,fechaRecepcion,ordenCompra,plazoPago,subCliente,valorTotal,} = resp;

          $("#FechaEntrega").val(FechaEntrega);
          $("#Formadepago").val(Formadepago);
          $("#Observaciones").val(Observaciones);
          $("#Proyecto").val(Proyecto);
          $("#costos").val(costos);
          $("#departamentoEntrega").val(departamentoEntrega);
          $("#fechaRecepcion").val(fechaRecepcion);
          $("#ordenCompra").val(ordenCompra);
          $("#plazoPago").val(plazoPago);
          $("#subCliente").val(subCliente);
          $("#valorTotal").val(valorTotal);
          if(archivoSubido!=""){
            url = `Archivos/Soportes/${archivoSubido}`;
            $("#soporteCargadoAlSistema").attr("href", url);
            $("#soporteCargadoAlSistema").show();
          }

          $('#home-tab').click();
          $('#btnEditarOrdenCompra').show();
          $('#btnEditarOrdenCompraCancelar').show();
          $('#btng').hide();
          $("#panelElementos").show();
          cargarElementosOrden();


        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });



    }

    const ocultarBtnsEditarOrdenCompra = ()=>{
      codOrdenCompra = '';
      $('#soporteCargadoAlSistema').hide();
      $('#btnEditarOrdenCompra').hide();
      $('#btnEditarOrdenCompraCancelar').hide();
      $('#btng').show();
    }

    const actualizarOrdenCompra = () =>{
      const fechaRecepcion = $("#fechaRecepcion").val();
      const ordenCompra = $("#ordenCompra").val();
      const departamentoEntrega = $("#departamentoEntrega").val();
      const valorTotal = $("#valorTotal").val();
      const costo = $("#costos").val();
      const plazoPago = $("#plazoPago").val();
      const subCliente = $("#subCliente").val();


      console.log('costos: ',costo);

      if (!validarCampo(fechaRecepcion, "Fecha de Recepcion")) return false;
      if (!validarCampo(ordenCompra, "Orden de Compra")) return false;
      if (!validarCampo(departamentoEntrega, "Departamento de Entrega")) return false;
      if (!validarCampo(valorTotal, "Valor Orden")) return false;
      if (!validarCampo(costo, "costos")) return false;
      if (!validarCampo(plazoPago, "Plazo de Pago")) return false;



      if ($("#FechaEntrega").val() == '') {
        swal("Atención!", "Seleccione Fecha Entrega", "warning");
        return false;
      }

      if ($("#Formadepago").val() == '') {
        swal("Atención!", "Seleccione Forma de pago", "warning");
        return false;
      }

      if ($("#Proyecto").val() == '') {
        swal("Atención!", "Seleccione Proyecto", "warning");
        return false;
      }

      var archivos = document.getElementById("solicitud"); //Damos el valor del input tipo file
      var archivo = archivos.files; //Obtenemos el valor del input (los arcchivos) en modo de arreglo 
      var data = new FormData();

      data.append('archivo', archivo[0]);
      data.append('option', "actualizarOrdenCompra");
      data.append('Formadepago', $("#Formadepago").val());
      data.append('FechaEntrega', $("#FechaEntrega").val());
      data.append('Proyecto', $("#Proyecto").val());
      data.append('Observaciones', $("#Observaciones").val());
      data.append('Usuario', "<?php echo $_SESSION['STCK-USER_USUARIO']; ?>");
      data.append('fechaRecepcion', fechaRecepcion);
      data.append('ordenCompra', ordenCompra);
      data.append('departamentoEntrega', departamentoEntrega);
      data.append('valorTotal', valorTotal);
      data.append('costos', costo);
      data.append('plazoPago', plazoPago);
      data.append('subCliente', subCliente);
      data.append('codOrdenCompra', codOrdenCompra);
      $("#solicitud").val("");
      $('#btnEditarOrdenCompra').button('loading');
      $.ajax({
        url: 'Procesamiento/mdlOrdenesDeCompra.php', //Url a donde la enviaremos
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        data: data, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache    
      }).done(function(resp) {
        swal("Mensaje informativo!", resp, "success");
        ocultarBtnsEditarOrdenCompra();
        limpiarFormulario();
        $('#btnEditarOrdenCompra').button('reset');
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        $('#btnEditarOrdenCompra').button('reset');
        swal("Error!", "Verifique su conexion a internet", "error");
      });
      
    }

    const editarOrdenCompraCancelar = ()=>{

      ocultarBtnsEditarOrdenCompra();
      limpiarFormulario();
    }


    const limpiarFormulario = ()=>{
      var f = new Date();
      dia = "";
      if (f.getDate() < 10) {
        dia = "0" + f.getDate();
      } else {
        dia = f.getDate();
      }
      mes = "";
      if ((f.getMonth() + 1) < 10) {
        mes = "0" + (f.getMonth() + 1);
      } else {
        mes = (f.getMonth() + 1);
      }
      $("#FechaEntrega").val(f.getFullYear() + "-" + mes + "-" + dia);
      $("#btng").show();
      $('#Formadepago').attr("disabled", false);
      $('#FechaEntrega').attr("disabled", false);
      $('#Proyecto').attr("disabled", false);
      $('#Observaciones').attr("disabled", false);
      $('#solicitud').attr("disabled", false).val("");
      $('#fechaRecepcion').attr("disabled", false).val(f.getFullYear() + "-" + mes + "-" + dia);
      $('#departamentoEntrega').attr("disabled", false).val("");
      $('#ordenCompra').attr("disabled", false).val("");
      $('#valorTotal, #costos, #plazoPago, #subCliente').attr("disabled", false).val("");
      $("#Formadepago").val('');
      $("#Proyecto").val('');
      $("#Observaciones").val('');
      $("#solicitud").val('');
      $("#Elementos").val('');
      $("#Talla").html('<select id="Talla" name="Talla" class="form-control"><option  selected="selected" value="">Seleccionar...</option> </select> ');
      $("#Cantidad").val('');
      $("#solicitud").val('');
      $("#btnFinalizar").hide();
      $("#panelElementos").hide();
      codigoOrdenAdd = '';
    }
  </script>
  <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head>


<style type="text/css">
  /* .modal-dialog {
    width: 90%;
    padding: 0;
  } */

  .modal-content {
    border-radius: 0;
  }
  .modal-tamano{
    width: 60%;
    padding: 0;
  }

  .myScroll{
    overflow-y: scroll; margin: 30px; height:350px;
  }
</style>


<body class="cbp-spmenu-push">
  <div class="main-content">


  <!-- Modal -->
  <div class="modal fade" id="modalAddProductoExcel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-tamano">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        </div>
        <div class="modal-body" style="background-color: white ">
          <!-- SECCION 1 -->
          <div>
            <div class="single-bottom row">
              <div class="col-md-3  ">
                <p><i class="fa fa-user-o"></i>Operación</p>
                <select name="operacionAddProductoExcel" id="operacionAddProductoExcel" class="form-control">
                  <option value="" selected="">Seleccionar...</option>
                  <option value="adicionarProductos" >Adicionar Productos</option>
                  <option value="reemplazarProductos" >Reemplazar Productos</option>
                </select>
              </div>
              <div class="col-md-3 ">
                  <p><i class="fa fa-user-o"></i> Base De Datos</p>
                  <input id="archivoAddProductoExcel" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="archivoAddProductoExcel" class="form-control" />
              </div>
              <div class="col-md-5 "> <br>
                <button id="btnAddProductoExcel" type="button" class="btn btn-success" onclick="addProductoExcel();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cargar Datos</button>
                <a href="Archivos/Instructivos/AddProductosOrdenCompra.xlsx" target="_blank"> <button id="btngin" type="button" class="btn btn-info" data-loading-text="<div class='loader'></div> Cargando, Espere...">Descargar Instructivo</button></a>
              </div>
            </div>
            <center>
                </br>
                <div id="divScroll" class="overflow:scroll " >
                  <div id="divAddProductoExcel">
                </div>
                </div>
            </center>

  
            <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
            <div class="clearfix espacio4"></div>
          </div>
          <!-- SECCION 1 -->
  
          <!-- CERRAR MODAL -->
          <div class="col-md-12 espacio4"><br>
           <!-- <div class="col-md-9"></div> -->
            <center>
              <button type="button" class="btn btn-dark" onclick="cerrarModal('modalAddProductoExcel');" id="Cerrar">Cerrar</button>
            </center>
          </div>
          <div class="clearfix espacio4"> </div>
          <!-- CERRAR MODAL -->
  
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal -->




    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          </div>
          <div class="modal-body">


            <div class="single-bottom row" style="">

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
                <b>Archivo solicitud: </b><span id="divsolicitud"> </span>
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

              <div class="col-md-12">
                <h3 class="title1" style="margin-bottom: 0em !important;margin-top: 20px !important"> DETALLE ELEMENTOS</h3>
              </div>

              <div class="clearfix"> </div>
              <div class="panel-body" style="background-color: #fafafa">
                <div class="single-bottom row">
                  <div id="ListadoDetalle" class="single-bottom row" style=""></div>
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
            <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">Formulario De Registro </a></li>
            <li role="presentation"><a href="#Masivo" id="Masivo-tab" role="tab" data-toggle="tab" aria-controls="Masivo" aria-expanded="false">Cargue Masivo </a></li>
            <li role="presentation" class="" id="tablistado"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Reporte - Informe</a></li>
          </ul>


          <div id="myTabContent" class="tab-content scrollbar1">

            <div role="tabpanel" class="tab-pane fade  active in" id="home" aria-labelledby="home-tab">
              <p>
              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">Sudicol | Ordenes De Compra Formulario</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">


                  <div class="single-bottom row" style="">

                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Forma de pago</p>
                      <select id="Formadepago" name="Formadepago" class="form-control">
                        <option selected="selected" value="">Seleccionar...</option>
                        <option value="CONTADO">Contado</option>
                        <option value="CREDITO">Credito</option>
                        <option value="EFECTIVO">Efectivo</option>
                        <option value="TRANSFERENCIA">Transferencia</option>
                        <option value="INMEDIATO">Inmediato</option>
                        <option value="CORTESIA">Cortesia</option>
                      </select>
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Fecha Recepción</p>
                      <input id="fechaRecepcion" type="date" name="fechaRecepcion" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Fecha Entrega</p>
                      <input id="FechaEntrega" type="date" name="FechaEntrega" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Cliente</p>
                      <?php
                      $consulta = "SELECT cons, nombre FROM clientes  ORDER BY nombre ";
                      $datos = mysqli_query($mysqli, $consulta);
                      echo ' <select id="Proyecto" name="Proyecto" class="form-control">
                              <option  selected="selected" value="">Seleccionar...</option>';
                      while ($row = mysqli_fetch_row($datos)) {
                        echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
                      }
                      echo ' </select>';
                      ?>
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>SubCliente</p>
                      <input id="subCliente" type="text" name="subCliente" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <p>Orden de Compra</p>
                      <input type="text" class="form-control" id="ordenCompra" name="ordenCompra" placeholder="Orden de Compra" />
                    </div>
                    <div class="col-md-4">
                      <p>Departamento Entrega</p>
                      <select name="departamentoEntrega" id="departamentoEntrega" class="form-control">
                        <option value="" selected>Seleccionar...</option>
                        <?php
                        $consulta = "SELECT cons, nombre FROM departamentos  ORDER BY nombre ";
                        $datos = mysqli_query($mysqli, $consulta);
                        while ($row = mysqli_fetch_row($datos)) {
                          echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
                        }
                        echo ' </select>';
                        ?>

                      </select>
                    </div>
                    <div class="col-md-4">
                      <p>Valor Orden</p>
                      <input type="number" class="form-control" id="valorTotal" name="valorTotal" placeholder="valor total" />
                    </div>

                    <div class="col-md-4">
                      <p>Costos</p>
                      <input type="number" class="form-control" id="costos" name="costos" placeholder="Costos" />
                    </div>
                    <div class="col-md-4">
                      <p>Plazo de pago(Días hábiles)</p>
                      <select name="plazoPago" id="plazoPago" class="form-control">
                        <option value="" selected>Seleccionar...</option>
                        <option value="8">8 Dias</option>
                        <option value="15">15 Dias</option>
                        <option value="30">30 Dias</option>
                        <option value="35">35 Dias</option>
                        <option value="45">45 Dias</option>
                        <option value="60">60 Dias</option>
                        <option value="90">90 Dias</option>
                        <option value="120">120 Dias</option>
                      </select>
                    </div>




                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Solicitud <a hidden id="soporteCargadoAlSistema" target="_blank">(Descargar Solicitud Cargada)</a> </p>
                      <input id="solicitud" type="file" name="solicitud" class="form-control" />
                    </div>
                    <div class="col-md-4">
                      <p><i class="fa fa-user-o"></i>Observaciones</p>
                      <input id="Observaciones" type="text" name="Observaciones" class="form-control" />
                    </div>

                    <!-- <div >
                      <a hidden id="divSoporteCargadoAlSistema" href="" >Descargar Solicitud Cargada</a>
                    </div> -->

                    
                    <div class="col-md-4">
                      <p><i class="fa fa-id-card"></i> </p>
                      <button id="btnEditarOrdenCompra" type="button" class="btn btn-success" onclick="actualizarOrdenCompra();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Actualizar</button>
                      <button id="btnEditarOrdenCompraCancelar" type="button" class="btn btn-warning" onclick="editarOrdenCompraCancelar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cancelar</button>
                      <button id="btng" type="button" class="btn btn-success" onclick="Guardar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>
                      <button id="btnFinalizar" type="button" class="btn btn-success" onclick="Finalizar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Finalizar Orden</button>
                    </div>



                  </div>

                  <div class="panel panel-success" id="panelElementos">
                    <div class="panel-heading">
                      <h3 style="color:  ">Gestion De Producto</h3>
                    </div>
                    <div class="panel-body">

                      <div class="col-md-4">
                        <p><i class="fa fa-user-o"></i>Productos</p>
                        <?php
                        $consulta = "SELECT cons, nombre FROM elementos  ORDER BY nombre ";
                        $datos = mysqli_query($mysqli, $consulta);
                        echo ' <select id="Elementos" name="Elementos" class="form-control">
             <option  selected="selected" value="">Seleccionar...</option>';
                        while ($row = mysqli_fetch_row($datos)) {
                          echo '<option   value="' . $row[0] . '">' . $row[1] . '</option>';
                        }
                        echo ' </select>';
                        ?>
                      </div>

                      <div class="col-md-4">
                        <p><i class="fa fa-user-o"></i> Talla / Medida Producto</p>
                        <select id="Talla" name="Talla" class="form-control">
                          <option selected="selected" value="">Seleccionar...</option>
                        </select>
                      </div>

                      <div class="col-md-4">
                        <p><i class="fa fa-user-o"></i>Procesos Producto</p>
                        <select id="procesoElemento" name="procesoElemento" multiple class="form-control">
                        <!-- <option  selected="selected" value="">Seleccionar...</option> -->
                        <?php
                          $consulta = "select nombre from procesos order by nombre asc";
                          $datos = mysqli_query($mysqli, $consulta);
                          while ($row = mysqli_fetch_row($datos)) {
                            echo '<option   value="' . $row[0] . '">' . $row[0] . '</option>';
                          }
                        ?>  
                        </select>
                    </div>

                      <div class="col-md-2">
                        <p><i class="fa fa-user-o"></i>Cantidad Producto</p>
                        <input id="Cantidad" type="number" name="Cantidad" class="form-control" />
                      </div>

                      <div class="col-md-2">
                        <p><i class="fa fa-user-o"></i>Valor Producto</p>
                        <input id="valorProducto" type="number" name="valorProducto" class="form-control" />
                      </div>
                      <div class="col-md-2">
                        <p><i class="fa fa-user-o"></i>Costo Producto</p>
                        <input id="costoElemento" type="number" name="costoElemento" class="form-control" />
                      </div>

                      <div class="col-md-2"> <br>
                        <button id="addProducto" type="button" class="btn btn-primary" onclick="addProducto();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Añadir Producto</button>
                      </div>
                      <div class="clearfix"> </div>
                      <br><br>
                      <div class="panel-body" style="background-color: #fafafa">
                        <div class="single-bottom row">
                          <div id="Listadoelementos" class="single-bottom row" style=""></div>
                        </div>
                      </div>

                    </div>
                  </div>

                </div>
              </div>
              </p>
            </div>





            <div role="tabpanel" class="tab-pane fade" id="profile" aria-labelledby="profile-tab">
              <p>

              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">Sudicol | Ordenes De Compra Listado</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">
                  <div class="single-bottom row">
                    <div id="Listado" class="single-bottom row" style=""></div>
                  </div>
                </div>
              </div>

              </p>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="Masivo" aria-labelledby="Masivo-tab">
              <p>

              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">Sudicol | Empleados Masivo</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">
                  <div class="single-bottom row">
                    <div class="col-md-4 col-md-offset-2">
                      <p><i class="fa fa-user-o"></i> Base De Datos</p>
                      <input id="Base" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" name="Base" class="form-control" />
                    </div>
                    <div class="col-md-6 "> <br>
                      <button id="btnCargarOrdenesArchivo" type="button" class="btn btn-success" onclick="cargarOrdenesPorArchivo();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Cargar Datos</button>
                      <a href="Archivos/Instructivos/ActualizarOrdenesMasivo.xlsx" target="_blank"> <button id="btngIntr2" type="button" class="btn btn-info" data-loading-text="<div class='loader'></div> Cargando, Espere...">Descargar Instructivo</button></a>

                    </div>
                  </div>
                </div>
                <div class="single-bottom row">
                  <center>
                    <div id="ListadoResp" class="single-bottom row" style=""></div>
                  </center>
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
        var menuLeft = document.getElementById('cbp-spmenu-s1'),
          showLeftPush = document.getElementById('showLeftPush'),
          body = document.body;

        showLeftPush.onclick = function() {
          classie.toggle(this, 'active');
          classie.toggle(body, 'cbp-spmenu-push-toright');
          classie.toggle(menuLeft, 'cbp-spmenu-open');
          disableOther('showLeftPush');
        };


        function disableOther(button) {
          if (button !== 'showLeftPush') {
            classie.toggle(showLeftPush, 'disabled');
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