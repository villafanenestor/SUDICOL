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
  <title>Clientes | SUDICOL</title>
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
    <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
    <script src="js/funcionesGenericas.js"></script>
  <!-- FUNCIONES GLOBALES Y COMPARTIDA POR ARCHIVOS -->
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
    let ciudades = {};
    let consecutivoEmpresa = -1;




    async function cargarCiudades(){
      // esta funcion asigna las ciudades a todos los inputs que necesitan ciudades, 
      // las ciudades se traen del listarCiudades del archivo funciones genericas
      ciudades = await listaCiudades();
      $("#ciudad").empty();
      $("#escrConsCiudad").empty();
      $("#ciudadAreaFTP").empty();
      $("#infoTributariaCiudad").empty();
      $("#infoAccionariaCiudad").empty();
      $("#bancosCiudad").empty();
      $("#proveedoresCiudad").empty();
      $("#clientesCiudad").empty();
      let options = '<option value="">Seleccionar...</option>';

      ciudades.forEach(element => {options += `<option value="${element[0]}">${element[1]}</option>`;});
      $("#ciudad").append(options);
      $("#escrConsCiudad").append(options);
      $("#ciudadAreaFTP").append(options);
      $("#infoTributariaCiudad").append(options);
      $("#infoAccionariaCiudad").append(options);
      $("#bancosCiudad").append(options);
      $("#proveedoresCiudad").append(options);
      $("#clientesCiudad").append(options);      
    }

    $(document).ready(function() {

      cargarCiudades();
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

      $("#btna").hide();
      $("#btnc").hide();



    });

    function guardar() {
      console.log("guardar");
      const datos = validarFormulario('guardarCliente');//Aqui estan todos los nobmres y campos que se utilizan
      // const datos = {'key': 'mensaje'};
      if (!datos) return;


      $('#btng').button('loading');
      $.ajax({
        url: "Procesamiento/mdlClientes.php",
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        data: datos, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache    
      }).done(function(resp) {
        console.log('respuesta', resp);
        if (resp == "OK") {
          swal("Atencion!", "Se ha guardado correctamente", "success");
          limpiar();
        }else{
          swal("Atencion!", "Hubo un error al guardar el Cliente", "error");
        }
        $('#btng').button('reset');       

      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        swal("Error!", "Verifique su conexion a internet", "error");
        $('#btng').button('reset');
      });
    
    }

    function actualizar() {
      const datos = validarFormulario('actualizarCliente');//Aqui estan todos los nobmres y campos que se utilizan
      $('#btna').button('loading');
      $.ajax({
        url: "Procesamiento/mdlClientes.php",
        type: 'POST', //Metodo que usaremos
        contentType: false, //Debe estar en false para que pase el objeto sin procesar
        data: datos, //Le pasamos el objeto que creamos con los archivos
        processData: false, //Debe estar en false para que JQuery no procese los datos a enviar
        cache: false //Para que el formulario no guarde cache    
      }).done(function(resp) {
        console.log('respuesta', resp);
        if (resp == "OK") {
            swal("Registro Exitoso!", "Se ha actualizado correctamente", "success");
              limpiar();
          } else {
            swal("Atención!", resp, "error");
          }
          $('#btna').button('reset');    
      }).fail(function(XMLHttpRequest, textStatus, errorThrown) {
        $('#btna').button('reset');
        swal("Error!", "Verifique su conexion a internet", "error");
      });
    }

    function listar() {
      $.ajax({
        data: {"option": 'listarClientes'},
        url: "Procesamiento/mdlClientes.php",
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

    function Nuevo() {
      limpiar();
    }

    function limpiar() {
      consecutivoEmpresa = -1;
      $("#razonSocial").val('');
      $("#nit").val('');
      $("#direccion").val('');
      $("#ciudad").val('');
      $("#telefono").val('');
      $("#direccionCorrespondencia").val('');
      $("#actividadEconomica").val('');
      $("#representanteLegal").val('');
      $("#cargo").val('');
      $("#escrConsNo").val('');
      $("#escrConstFecha").val('');
      $("#escrConsNotaria").val('');
      $("#escrConsCiudad").val('');
      $("#registroCamaraComercio").val('');
      $("#fechaRegistroCamaraComercio").val('');
      $("#nombreAreaCompra").val('');
      $("#cargoAreaCompra").val('');
      $("#telefonoAreaCompra").val('');
      $("#correoAreaCompra").val('');
      $("#nombreAreaFTP").val('');
      $("#cargoAreaFTP").val('');
      $("#telefonoAreaFTP").val('');
      $("#correoAreaFTP").val('');
      $("#ciudadAreaFTP").val('');
      $("#infoTributariaTipo").val('');
      $("#infoTributariaCiudad").val('');
      $("#infoTributariaFechaMaxRecepFact").val('');
      $("#infoTributariaCorreo").val('');
      $("#infoAccionariaNombre").val('');
      $("#infoAccionariaCCoNIT").val('');
      $("#infoAccionariaDireccion").val('');
      $("#infoAccionariaTelefono").val('');
      $("#infoAccionariaCiudad").val('');
      $("#infoAccionariaPorcenAcc").val('');
      $("#bancosNombre").val('');
      $("#bancosCuenta").val('');
      $("#bancosSucursal").val('');
      $("#bancosTelefono").val('');
      $("#bancosCiudad").val('');
      $("#proveedoresRazonSocial").val('');
      $("#proveedoresDireccion").val('');
      $("#proveedoresTelefono").val('');
      $("#proveedoresCiudad").val('');
      $("#clientesRazonSocial").val('');
      $("#clientesDireccion").val('');
      $("#clientesTelefono").val('');
      $("#clientesCiudad").val('');
      $("#cupoCreditoSolicitado").val('');
      $("#cupoCreditoPlazo").val('');
      $("#cupoCreditoObservaciones").val('');
      $("#certificadoExisRepreLegal").val('');
      $("#cedulaRepresentante").val('');
      $("#estadoFinanciero").val('');
      $("#rut").val('');
      $("#declaracionRenta").val('');
      $("#descargarAdjuntos").html("");
      $("#btna").hide();
      $("#btnc").hide();
      $("#btng").show();

    }

    function Buscar_Datos(codigo) {
      var Datos = {
        option: "buscarCliente",
        'consecutivo': codigo,
      };
      console.log(Datos);
      $.ajax({
        data: Datos,
        url: "Procesamiento/mdlClientes.php",
        type: "POST",
        success: function(resp) {
          // console.log(resp);
          const {nombre,NIT,Direccion,Ciudad,Telefono,Direccioncorrespondencia,Actividadeconomica,Representantelegal,Cargo,EscrituraNo,
            EscrituraFecha,EscrituraNotaria,EscrituraCiudad,RegistroCamaradeComercio,CamaraFecha,ComprasNombre,ComprasCargo,ComprasTelefono,
            ComprasCorreo,FinancieraNombre,FinancieraCargo,FinancieraTelefono,FinancieraCorreo,FinancieraCiudad,TipoContribuyente,CiudadICA,
            Fechafacturas,Correofacturacion,AccionariaNombre,AccionariaCC,AccionariaDireccion,AccionariaTelefono,AccionariaCiudad,Accionariaacc,
            Banco,BancoCuenta,BancoSucursal,BancoTelefono,BancoCiudad,ProveedoresRazonsocial,ProveedoresDireccion,ProveedoresTelefonos,
            ProveedoresCiudad,ClientesRazonsocial,ClientesDireccion,ClientesTelefonos,ClientesCiudad,Cupocredito,PlazoPago,CupocreditoObservaciones,
            soporte1,soporte2,soporte3,soporte4,soporte5, cons,} = resp;
          consecutivoEmpresa = cons;
          $("#razonSocial").val(nombre);
          $("#nit").val(NIT);
          $("#direccion").val(Direccion);
          $("#ciudad").val(Ciudad);
          $("#telefono").val(Telefono);
          $("#direccionCorrespondencia").val(Direccioncorrespondencia);
          $("#actividadEconomica").val(Actividadeconomica);
          $("#representanteLegal").val(Representantelegal);
          $("#cargo").val(Cargo);
          $("#escrConsNo").val(EscrituraNo);
          $("#escrConstFecha").val(EscrituraFecha);
          $("#escrConsNotaria").val(EscrituraNotaria);
          $("#escrConsCiudad").val(EscrituraCiudad);
          $("#registroCamaraComercio").val(RegistroCamaradeComercio);
          $("#fechaRegistroCamaraComercio").val(CamaraFecha);
          $("#nombreAreaCompra").val(ComprasNombre);
          $("#cargoAreaCompra").val(ComprasCargo);
          $("#telefonoAreaCompra").val(ComprasTelefono);
          $("#correoAreaCompra").val(ComprasCorreo);
          $("#nombreAreaFTP").val(FinancieraNombre);
          $("#cargoAreaFTP").val(FinancieraCargo);
          $("#telefonoAreaFTP").val(FinancieraTelefono);
          $("#correoAreaFTP").val(FinancieraCorreo);
          $("#ciudadAreaFTP").val(FinancieraCiudad);
          $("#infoTributariaTipo").val(TipoContribuyente);
          $("#infoTributariaCiudad").val(CiudadICA);
          $("#infoTributariaFechaMaxRecepFact").val(Fechafacturas);
          $("#infoTributariaCorreo").val(Correofacturacion);
          $("#infoAccionariaNombre").val(AccionariaNombre);
          $("#infoAccionariaCCoNIT").val(AccionariaCC);
          $("#infoAccionariaDireccion").val(AccionariaDireccion);
          $("#infoAccionariaTelefono").val(AccionariaTelefono);
          $("#infoAccionariaCiudad").val(AccionariaCiudad);
          $("#infoAccionariaPorcenAcc").val(Accionariaacc);
          $("#bancosNombre").val(Banco);
          $("#bancosCuenta").val(BancoCuenta);
          $("#bancosSucursal").val(BancoSucursal);
          $("#bancosTelefono").val(BancoTelefono);
          $("#bancosCiudad").val(BancoCiudad);
          $("#proveedoresRazonSocial").val(ProveedoresRazonsocial);
          $("#proveedoresDireccion").val(ProveedoresDireccion);
          $("#proveedoresTelefono").val(ProveedoresTelefonos);
          $("#proveedoresCiudad").val(ProveedoresCiudad);
          $("#clientesRazonSocial").val(ClientesRazonsocial);
          $("#clientesDireccion").val(ClientesDireccion);
          $("#clientesTelefono").val(ClientesTelefonos);
          $("#clientesCiudad").val(ClientesCiudad);
          $("#cupoCreditoSolicitado").val(Cupocredito);
          $("#cupoCreditoPlazo").val(PlazoPago);
          $("#cupoCreditoObservaciones").val(CupocreditoObservaciones);
          let bnts = '';
          bnts += soporte1==='' ? '': `<div class="col-md-2"><a href="Archivos/Soportes/${soporte1}" target="_blank" ><i class="fa fa-download"></i> Descargar Camara de Comercio</a></div>`;
          bnts += soporte2==='' ? '': `<div class="col-md-2"><a href="Archivos/Soportes/${soporte2}" target="_blank" ><i class="fa fa-download"></i> descargar Estad. Financiero</a></div>`;
          bnts += soporte3==='' ? '': `<div class="col-md-2"><a href="Archivos/Soportes/${soporte3}" target="_blank" ><i class="fa fa-download"></i> descargar Repr. Legal</a></div>`;
          bnts += soporte4==='' ? '': `<div class="col-md-2"><a href="Archivos/Soportes/${soporte4}" target="_blank" ><i class="fa fa-download"></i> descargar Rut</a></div>`;
          bnts += soporte5==='' ? '': `<div class="col-md-2"><a href="Archivos/Soportes/${soporte5}" target="_blank" ><i class="fa fa-download"></i> descargar Decla. de Renta</a></div>`;
          $("#descargarAdjuntos").html(bnts);
          $("#home-tab").click();
          $("#btng").hide();
          $("#btna").show();
          $("#btnc").show();
  
        },
        error: function(resp) {
          swal("Error!", "Verifique su conexion a internet", "error");
        }
      });
    }


    const validarFormulario = (option)=>{
      // 1. DATOS GENERALES DE LA EMPRESA
      const razonSocial = $("#razonSocial").val();
      const nit = $("#nit").val();
      const direccion = $("#direccion").val();
      const ciudad = $("#ciudad").val();
      const telefono = $("#telefono").val();
      const direccionCorrespondencia = $("#direccionCorrespondencia").val();
      const actividadEconomica = $("#actividadEconomica").val();
      const representanteLegal = $("#representanteLegal").val();
      const cargo = $("#cargo").val();
      let cantidadSoporteActualizados = 0;
      
      // Informacion de escritura de 
      const escrConsNo = $("#escrConsNo").val();
      const escrConstFecha = $("#escrConstFecha").val();
      const escrConsNotaria = $("#escrConsNotaria").val();
      const escrConsCiudad = $("#escrConsCiudad").val();
      // camara de comercio
      const registroCamaraComercio = $("#registroCamaraComercio").val();
      const fechaRegistroCamaraComercio = $("#fechaRegistroCamaraComercio").val();

      // 2. DATOS DE CONTACTO
      // 2.1 Contacto Área de Compras
      const nombreAreaCompra = $("#nombreAreaCompra").val();
      const cargoAreaCompra = $("#cargoAreaCompra").val();
      const telefonoAreaCompra = $("#telefonoAreaCompra").val();
      const correoAreaCompra = $("#correoAreaCompra").val();
      // Contacto Área Financiera/Tesorería/Pagos
      const nombreAreaFTP = $("#nombreAreaFTP").val();
      const cargoAreaFTP = $("#cargoAreaFTP").val();
      const telefonoAreaFTP = $("#telefonoAreaFTP").val();
      const correoAreaFTP = $("#correoAreaFTP").val();
      const ciudadAreaFTP = $("#ciudadAreaFTP").val();

      // INFORAMCION TRIBUTARIA
      const infoTributariaTipo = $("#infoTributariaTipo").val();
      const infoTributariaCiudad = $("#infoTributariaCiudad").val();
      const infoTributariaFechaMaxRecepFact = $("#infoTributariaFechaMaxRecepFact").val();
      const infoTributariaCorreo = $("#infoTributariaCorreo").val();

      // INFORMACION ACCIONARIO
      const infoAccionariaNombre = $("#infoAccionariaNombre").val();
      const infoAccionariaCCoNIT = $("#infoAccionariaCCoNIT").val();
      const infoAccionariaDireccion = $("#infoAccionariaDireccion").val();
      const infoAccionariaTelefono = $("#infoAccionariaTelefono").val();
      const infoAccionariaCiudad = $("#infoAccionariaCiudad").val();
      const infoAccionariaPorcenAcc = $("#infoAccionariaPorcenAcc").val();
      // INFORMACION BANCO
      const bancosNombre = $("#bancosNombre").val();
      const bancosCuenta = $("#bancosCuenta").val();
      const bancosSucursal = $("#bancosSucursal").val();
      const bancosTelefono = $("#bancosTelefono").val();
      const bancosCiudad = $("#bancosCiudad").val();
      // INFORMACION PROVEEDORES
      const proveedoresRazonSocial = $("#proveedoresRazonSocial").val();
      const proveedoresDireccion = $("#proveedoresDireccion").val();
      const proveedoresTelefono = $("#proveedoresTelefono").val();
      const proveedoresCiudad = $("#proveedoresCiudad").val();
      // INFORMACION CLIENTES
      const clientesRazonSocial = $("#clientesRazonSocial").val();
      const clientesDireccion = $("#clientesDireccion").val();
      const clientesTelefono = $("#clientesTelefono").val();
      const clientesCiudad = $("#clientesCiudad").val();

      const cupoCreditoSolicitado = $("#cupoCreditoSolicitado").val() 
      const cupoCreditoPlazo = $("#cupoCreditoPlazo").val() 
      const cupoCreditoObservaciones = $("#cupoCreditoObservaciones").val() 

      // DOCUMENTOS ADJUNTOS 
      const archivoCertificadoExisRepreLegal = $("#certificadoExisRepreLegal").prop('files')[0];
      const archivoCedulaRepresentante = $("#cedulaRepresentante").prop('files')[0];
      const archivoEstadoFinanciero = $("#estadoFinanciero").prop('files')[0];
      const archivoRut = $("#rut").prop('files')[0];
      const archivoDeclaracionRenta = $("#declaracionRenta").prop('files')[0];


      

      if(!validarCampo(razonSocial, "Razón social - Datos Generales De La Empresa")) return false;
      if(!validarCampo(nit, "NIT - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(direccion, "Dirección - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(ciudad, "Ciudad - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(telefono, "Teléfono - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(direccionCorrespondencia, "Dirección Correspondencia - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(actividadEconomica, "Actividad Económica - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(representanteLegal, "Represente Legal - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(cargo, "Cargo - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(escrConsNo, "Escritura Constitución No - Datos Generales De La Empresa")) return false;
      // if(!validarCampo(escrConstFecha, "Fecha - Escritura Constitución")) return false;
      // if(!validarCampo(escrConsNotaria, "Notaría - Escritura Constitución")) return false;
      // if(!validarCampo(escrConsCiudad, "Ciudad - Escritura Constitución")) return false;
      // if(!validarCampo(registroCamaraComercio, "Registro Camara de Comercio")) return false;
      // if(!validarCampo(fechaRegistroCamaraComercio, "Fecha Registro Camara de Comercio")) return false;

      // if(!validarCampo(nombreAreaCompra, "Nombre - Área de Compras")) return false;
      // if(!validarCampo(cargoAreaCompra, "Cargo - Área de Compras")) return false;
      // if(!validarCampo(telefonoAreaCompra, "Teléfono/Celular - Área de Compras")) return false;
      // if(!validarCampo(correoAreaCompra, "Correo - Área de Compras")) return false;
      // if(!validarCampo(nombreAreaFTP, "Nombre - Área Financiera/Tesorería/Pagos")) return false;
      // if(!validarCampo(cargoAreaFTP, "Cargo - Área Financiera/Tesorería/Pagos")) return false;
      // if(!validarCampo(telefonoAreaFTP, "Teléfono/Pagos - Área Financiera/Tesorería/Celular")) return false;
      // if(!validarCampo(correoAreaFTP, "Correo - Área Financiera/Tesorería/Pagos")) return false;
      // if(!validarCampo(ciudadAreaFTP, "Ciudad - Área Financiera/Tesorería/Pagos")) return false;

      // if(!validarCampo(infoTributariaTipo, "Tipo Contribuyente - Informacion Tributaria")) return false;
      // if(!validarCampo(infoTributariaCiudad, "Ciudad - Informacion Tributaria")) return false;
      // if(!validarCampo(infoTributariaFechaMaxRecepFact, "Fecha Máxima Recepcion de Facturas - Informacion Tributaria")) return false;
      // if(!validarCampo(infoTributariaCorreo, "Correo De Facturación Electrónica - Informacion Tributaria")) return false;

      // if(!validarCampo(infoAccionariaNombre, "Nombre - Informacion Accionaria")) return false;
      // if(!validarCampo(infoAccionariaCCoNIT, "CC/NIT - Informacion Accionaria")) return false;
      // if(!validarCampo(infoAccionariaDireccion, "Direccion - Informacion Accionaria")) return false;
      // if(!validarCampo(infoAccionariaTelefono, "Teléfono - Informacion Accionaria")) return false;
      // if(!validarCampo(infoAccionariaCiudad, "Ciudad - Informacion Accionaria")) return false;
      // if(!validarCampo(infoAccionariaPorcenAcc, "% ACC - Informacion Accionaria")) return false;

      // if(!validarCampo(bancosNombre, "Nombre Banco - Informacion Financiera y Comercial - Bancos")) return false;
      // if(!validarCampo(bancosCuenta, "Cuenta No. - Informacion Financiera y Comercial - Bancos")) return false;
      // if(!validarCampo(bancosSucursal, "Sucursal - Informacion Financiera y Comercial - Bancos")) return false;
      // if(!validarCampo(bancosTelefono, "Telefono - Informacion Financiera y Comercial - Bancos")) return false;
      // if(!validarCampo(bancosCiudad, "Ciudad - Informacion Financiera y Comercial - Bancos")) return false;
      // if(!validarCampo(proveedoresRazonSocial, "Razón Social - Informacion Financiera y Comercial - Proveedores")) return false;
      // if(!validarCampo(proveedoresDireccion, "Direccion - Informacion Financiera y Comercial - Proveedores")) return false;
      // if(!validarCampo(proveedoresTelefono, "Teléfono - Informacion Financiera y Comercial - Proveedores")) return false;
      // if(!validarCampo(proveedoresCiudad, "Ciudad - Informacion Financiera y Comercial - Proveedores")) return false;
      // if(!validarCampo(clientesRazonSocial, "Razón Social - Informacion Financiera y Comercial - Clientes")) return false;
      // if(!validarCampo(clientesDireccion, "Direccion - Informacion Financiera y Comercial - Clientes")) return false;
      // if(!validarCampo(clientesTelefono, "Teléfono - Informacion Financiera y Comercial - Clientes")) return false;
      // if(!validarCampo(clientesCiudad, "Ciudad - Informacion Financiera y Comercial - Clientes")) return false;

      // if(!validarCampo(cupoCreditoSolicitado, "Cupo de crédito solicitado")) return false;
      // if(!validarCampo(cupoCreditoPlazo, "Plazo Pago")) return false;
      // if(!validarCampo(cupoCreditoObservaciones, "Observaciones del cliente")) return false;

      if(option == "guardarCliente"){
        // if(!archivoCertificadoExisRepreLegal){
        // console.log("error en archivoCertificadoExisRepreLegal");
        // swal("Atención!", "Certificado de existencia y representación legal (Cámara de Comercio – 30 días de expedición)", "error");
        // return false;
        // }
        // if(!archivoCedulaRepresentante){
        //   console.log("error en archivoCedulaRepresentante");
        //   swal("Atención!", "Fotocopia de Cédula de ciudadanía Representante Legal", "error");
        //   return false;
        // }
        // if(!archivoEstadoFinanciero){
        //   console.log("error en archivoEstadoFinanciero");
        //   swal("Atención!", "Estados financieros últimos dos (2) cortes fiscales (Balance general y Estado de resultados)", "error");
        //   return false;
        // }
        // if(!archivoRut){
        //   console.log("error en archivoRut");
        //   swal("Atención!", "Copia RUT", "error");
        //   return false;
        // }
        // if(!archivoDeclaracionRenta){
        //   console.log("error en archivoDeclaracionRenta");
        //   swal("Atención!", "Fotocopia últimas dos (2) declaraciones de renta", "error");
        //   return false;
        // }
      }else{
        cantidadSoporteActualizados += archivoCertificadoExisRepreLegal ? 1 : 0;
        cantidadSoporteActualizados += archivoCedulaRepresentante ? 1 : 0;
        cantidadSoporteActualizados += archivoEstadoFinanciero ? 1 : 0;
        cantidadSoporteActualizados += archivoRut ? 1 : 0;
        cantidadSoporteActualizados += archivoDeclaracionRenta ? 1 : 0;

        console.log("cantidadSoporteActualizados: "+cantidadSoporteActualizados);
      }
      
      var data = new FormData();
      data.append('option',option);
      data.append('razonSocial',razonSocial);
      data.append('nit',nit);
      data.append('direccion',direccion);
      data.append('ciudad',ciudad);
      data.append('telefono',telefono);
      data.append('direccionCorrespondencia',direccionCorrespondencia);
      data.append('actividadEconomica',actividadEconomica);
      data.append('representanteLegal',representanteLegal);
      data.append('cargo',cargo);
      data.append('escrConsNo',escrConsNo);
      data.append('escrConstFecha',escrConstFecha);
      data.append('escrConsNotaria',escrConsNotaria);
      data.append('escrConsCiudad',escrConsCiudad);
      data.append('registroCamaraComercio',registroCamaraComercio);
      data.append('fechaRegistroCamaraComercio',fechaRegistroCamaraComercio);
      data.append('nombreAreaCompra',nombreAreaCompra);
      data.append('cargoAreaCompra',cargoAreaCompra);
      data.append('telefonoAreaCompra',telefonoAreaCompra);
      data.append('correoAreaCompra',correoAreaCompra);
      data.append('nombreAreaFTP',nombreAreaFTP);
      data.append('cargoAreaFTP',cargoAreaFTP);
      data.append('telefonoAreaFTP',telefonoAreaFTP);
      data.append('correoAreaFTP',correoAreaFTP);
      data.append('ciudadAreaFTP',ciudadAreaFTP);
      data.append('infoTributariaTipo',infoTributariaTipo);
      data.append('infoTributariaCiudad',infoTributariaCiudad);
      data.append('infoTributariaFechaMaxRecepFact',infoTributariaFechaMaxRecepFact);
      data.append('infoTributariaCorreo',infoTributariaCorreo);
      data.append('infoAccionariaNombre',infoAccionariaNombre);
      data.append('infoAccionariaCCoNIT',infoAccionariaCCoNIT);
      data.append('infoAccionariaDireccion',infoAccionariaDireccion);
      data.append('infoAccionariaTelefono',infoAccionariaTelefono);
      data.append('infoAccionariaCiudad',infoAccionariaCiudad);
      data.append('infoAccionariaPorcenAcc',infoAccionariaPorcenAcc);
      data.append('bancosNombre',bancosNombre);
      data.append('bancosCuenta',bancosCuenta);
      data.append('bancosSucursal',bancosSucursal);
      data.append('bancosTelefono',bancosTelefono);
      data.append('bancosCiudad',bancosCiudad);
      data.append('proveedoresRazonSocial',proveedoresRazonSocial);
      data.append('proveedoresDireccion',proveedoresDireccion);
      data.append('proveedoresTelefono',proveedoresTelefono);
      data.append('proveedoresCiudad',proveedoresCiudad);
      data.append('clientesRazonSocial',clientesRazonSocial);
      data.append('clientesDireccion',clientesDireccion);
      data.append('clientesTelefono',clientesTelefono);
      data.append('clientesCiudad',clientesCiudad);
      data.append('cupoCreditoSolicitado',cupoCreditoSolicitado);
      data.append('cupoCreditoPlazo',cupoCreditoPlazo);
      data.append('cupoCreditoObservaciones',cupoCreditoObservaciones);
      data.append('archivoCertificadoExisRepreLegal',archivoCertificadoExisRepreLegal);
      data.append('archivoCedulaRepresentante',archivoCedulaRepresentante);
      data.append('archivoEstadoFinanciero',archivoEstadoFinanciero);
      data.append('archivoRut',archivoRut);
      data.append('archivoDeclaracionRenta',archivoDeclaracionRenta);
      data.append('cantidadSoporteActualizados',cantidadSoporteActualizados);//SOLO APLICA CUANDO ES PARA ACTUALIZAR
      data.append('consecutivoEmpresa',consecutivoEmpresa);//consecutivo empresa SOLO APLICA CUANDO ES PARA ACTUALIZAR

      return data;

    }
  </script>
  <!-- //pie-chart --><!-- index page sales reviews visitors pie chart -->

</head>

<body class="cbp-spmenu-push">
  <div class="main-content">

    <?php
    include("Partes/Menu.php");
    ?>
    <!-- main content start-->
    <div id="page-wrapper">
      <div class="main-page">



        <div class="col-md-12  widget-shadow">


          <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#home" id="home-tab" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">Formulario De Registro </a></li>
            <li role="presentation" class=""><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Reporte - Informe</a></li>
          </ul>


          <div id="myTabContent" class="tab-content scrollbar1">

            <div role="tabpanel" class="tab-pane fade  active in" id="home" aria-labelledby="home-tab">
              <p>
              <div class="panel panel-primary">
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">SUDICOL | Clientes Formulario</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">


                  <div class="single-bottom row" >
                    <div>
                      <!-- DATOS GENERALES DE LA EMPRESA -->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">DATOS GENERALES DE LA EMPRESA</h3>
                        </div>
                        <div class="row">
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Razón social</p>
                            <input id="razonSocial" type="text" name="razonSocial" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>NIT</p>
                          <input id="nit" type="text" name="nit" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Dirección</p>
                          <input id="direccion" type="text" name="direccion" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Ciudad</p>
                            <select name="ciudad" id="ciudad" class="form-control" >
                              <option value="">Seleccionar</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Teléfono</p>
                          <input id="telefono" type="text" name="telefono" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Dirección Correspondencia</p>
                          <input id="direccionCorrespondencia" type="text" name="direccionCorrespondencia" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Actividad Económica</p>
                          <input id="actividadEconomica" type="text" name="actividadEconomica" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Represente Legal</p>
                          <input id="representanteLegal" type="text" name="representanteLegal" class="form-control" />
                          </div>
                          <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Cargo</p>
                          <input id="cargo" type="text" name="cargo" class="form-control" />
                          </div>

                        </div>

                        <div class="row">
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Escritura Constitución No</p>
                           <input id="escrConsNo" type="text" name="escrConsNo" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Fecha</p>
                           <input id="escrConstFecha" type="text" name="escrConstFecha" placeholder="Fecha Escritura Constitución" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Notaría</p>
                           <input id="escrConsNotaria" type="text" name="escrConsNotaria" placeholder="Notaría Escritura Constitución" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Ciudad</p>
                           <select name="escrConsCiudad" id="escrConsCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                           </select>
                          </div>
                        </div>

                        <div class="row">
                        
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Registro Camara de Comercio</p>
                           <input id="registroCamaraComercio" type="text" name="registroCamaraComercio" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Fecha</p>
                           <input id="fechaRegistroCamaraComercio" type="text" name="fechaRegistroCamaraComercio" class="form-control" placeholder="Fecha Registro Camara de Comercio" />
                          </div>
                        </div>

                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                        
                      </div>
                      <!-- DATOS GENERALES DE LA EMPRESA -->
                      <!-- DATOS DE CONTACTO-->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">DATOS DE CONTACTO</h3>
                        </div>
                        <div class="row">
                          <h4>Contacto Área de Compras</h4>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Nombre</p>
                            <input id="nombreAreaCompra"  laceholder="Encargado" type="text" name="nombreAreaCompra" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Cargo</p>
                            <input id="cargoAreaCompra" placeholder="Cargo del encargado" type="text" name="cargoAreaCompra" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Teléfono/Celular</p>
                            <input id="telefonoAreaCompra" type="text" name="telefonoAreaCompra" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Correo</p>
                            <input id="correoAreaCompra" type="text" name="correoAreaCompra" class="form-control" />
                          </div>
                        </div>
                        <div class="row">
                          <h4>Contacto Área Financiera/Tesorería/Pagos</h4>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Nombre</p>
                            <input id="nombreAreaFTP"  laceholder="Encargado" type="text" name="nombreAreaFTP" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Cargo</p>
                            <input id="cargoAreaFTP" placeholder="Cargo del encargado" type="text" name="cargoAreaFTP" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Teléfono/Celular</p>
                            <input id="telefonoAreaFTP" type="text" name="telefonoAreaFTP" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Correo</p>
                            <input id="correoAreaFTP" type="text" name="correoAreaFTP" class="form-control" />
                          </div>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Ciudad</p>
                            <select name="ciudadAreaFTP" id="ciudadAreaFTP" class="form-control" >
                              <option value="">Seleccionar</option>
                            </select>
                        </div>

                        </div>





                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                      </div>
                      <!-- DATOS DE CONTACTO-->
                      <!-- INFORMACION TRIBUTARIA -->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">INFORMACION TRIBUTARIA</h3>
                        </div>
                        <div class="col-md-3  ">
                          <p><i class="fa fa-user-o"></i>Tipo Contribuyente *</p>
                          <select name="infoTributariaTipo" id="infoTributariaTipo" class="form-control">
                            <option value="" selected="">Seleccionar...</option>
                            <option value="comun">Común</option>
                            <option value="simplificado">Simplificado</option>
                            <option value="granContribuyente">Gran Contribuyente</option>
                            <option value="autorretenedor">Autorretenedor</option>
                            <option value="tarifaI.C.A.">Tarifa I.C.A.</option>                          </select>
                        </div>
                        <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Ciudad</p>
                          <select name="infoTributariaCiudad" id="infoTributariaCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                          </select>
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>Fecha Máxima Recepcion de Facturas</p>
                         <input id="infoTributariaFechaMaxRecepFact" type="text" name="infoTributariaFechaMaxRecepFact" class="form-control" />
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>Correo De Facturación Electrónica</p>
                         <input id="infoTributariaCorreo" type="text" name="infoTributariaCorreo" class="form-control" />
                        </div>

                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                      </div>
                      <!-- INFORMACION TRIBUTARIA -->
                      <!-- INFORMACION ACCIONARIA -->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">INFORMACION ACCIONARIA</h3>
                        </div>
                        <div class="col-md-3">
                          <p><i class="fa fa-user-o"></i>Nombre</p>
                          <input id="infoAccionariaNombre" type="text" name="infoAccionariaNombre" class="form-control" />
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>CC/NIT</p>
                         <input id="infoAccionariaCCoNIT" type="text" name="infoAccionariaCCoNIT" class="form-control" />
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>Direccion</p>
                         <input id="infoAccionariaDireccion" type="text" name="infoAccionariaDireccion" class="form-control" />
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>Teléfono</p>
                         <input id="infoAccionariaTelefono" type="text" name="infoAccionariaTelefono" class="form-control" />
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>Ciudad</p>
                         <select name="infoAccionariaCiudad" id="infoAccionariaCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                         </select>
                        </div>
                        <div class="col-md-3">
                         <p><i class="fa fa-user-o"></i>% ACC</p>
                         <input id="infoAccionariaPorcenAcc" type="text" name="infoAccionariaPorcenAcc" class="form-control" />
                        </div>


                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                      </div>
                      <!-- INFORMACION ACCIONARIA -->
                      <!-- REFERENCIAS FINANCIERAS Y COMERCIALES -->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">REFERENCIAS FINANCIERAS Y COMERCIALES</h3>
                        </div>

                        <!-- INFORMACION BANCOS -->
                        <div class="row">
                          <h4>Bancos</h4>
                          <div class="col-md-3">
                            <p><i class="fa fa-user-o"></i>Nombre Banco</p>
                            <input id="bancosNombre" type="text" name="bancosNombre" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Cuenta No.</p>
                           <input id="bancosCuenta" type="text" name="bancosCuenta" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Sucursal</p>
                           <input id="bancosSucursal" type="text" name="bancosSucursal" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Telefono</p>
                           <input id="bancosTelefono" type="text" name="bancosTelefono" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Ciudad</p>
                           <select name="bancosCiudad" id="bancosCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                           </select>
                          </div>
                        </div>
                        <!-- INFORMACION BANCOS -->
                        <!-- INFORMACION PROVEEDORES  -->
                        <div class="row">
                          <h4>Proveedores</h4>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Razón Social</p>
                           <input id="proveedoresRazonSocial" type="text" name="proveedoresRazonSocial" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Direccion</p>
                           <input id="proveedoresDireccion" type="text" name="proveedoresDireccion" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Teléfono</p>
                           <input id="proveedoresTelefono" type="text" name="proveedoresTelefono" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Ciudad</p>
                           <select name="proveedoresCiudad" id="proveedoresCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                           </select>
                          </div>
                        </div>
                        <!-- INFORMACION PROVEEDORES  -->

                        <!-- CLIENTES -->
                        <div class="row">
                          <h4>Clientes</h4>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Razón Social</p>
                           <input id="clientesRazonSocial" type="text" name="clientesRazonSocial" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Direccion</p>
                           <input id="clientesDireccion" type="text" name="clientesDireccion" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Teléfono</p>
                           <input id="clientesTelefono" type="text" name="clientesTelefono" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Ciudad</p>
                           <select name="clientesCiudad" id="clientesCiudad" class="form-control" >
                            <option value="">Seleccionar</option>
                           </select>
                          </div>
                        </div>
                        <!-- CLIENTES -->




                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                      </div>
                      <!-- REFERENCIAS FINANCIERAS Y COMERCIALES -->
                      <!-- CUPO DE CREDITO -->
                      <div>
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">CUPO DE CREDITO</h3>
                        </div>

                        
                        <!-- INFORMACION CUPO DE CREDITO -->
                        <div class="row">
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Cupo De Crédito Solicitado</p>
                           <input id="cupoCreditoSolicitado" type="text" name="cupoCreditoSolicitado" class="form-control" />
                          </div>
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Plazo Pago</p>
                           <select name="cupoCreditoPlazo" id="cupoCreditoPlazo" class="form-control">
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
                          <div class="col-md-3">
                           <p><i class="fa fa-user-o"></i>Observaciones del cliente</p>
                           <input id="cupoCreditoObservaciones" type="text" name="cupoCreditoObservaciones" class="form-control" />
                          </div>
                        </div>
                        <!-- INFORMACION CUPO DE CREDITO -->
                        <!-- DOCUMENTOS  -->
                        <div >
                        <div class="panel-heading" style="background-color: #7696a5 !important;color:#fff !important">
                          <h3 class="panel-title">ADJUNTAR DOCUMENTOS</h3>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <p><i class="fa fa-user-o"></i>Certificado de existencia y representación legal (Cámara de Comercio – 30 días de expedición)</p>
                            <input id="certificadoExisRepreLegal" type="file" name="certificadoExisRepreLegal" class="form-control">
                          </div>
                          <div class="col-md-6">
                            <p><i class="fa fa-user-o"></i>Estados financieros últimos dos (2) cortes fiscales (Balance general y Estado de resultados)</p>
                            <input id="estadoFinanciero" type="file" name="estadoFinanciero" class="form-control">
                          </div>
                            <div class="col-md-4">
                              <p><i class="fa fa-user-o"></i>Fotocopia de Cédula de ciudadanía Representante Legal</p>
                              <input id="cedulaRepresentante" type="file" name="cedulaRepresentante" class="form-control">
                            </div>
                            <div class="col-md-4">
                              <p><i class="fa fa-user-o"></i>Copia RUT</p>
                              <input id="rut" type="file" name="rut" class="form-control">
                            </div>
                            <div class="col-md-4">
                              <p><i class="fa fa-user-o"></i>Fotocopia últimas dos (2) declaraciones de renta</p>
                              <input id="declaracionRenta" type="file" name="declaracionRenta" class="form-control">
                            </div>
                        </div>

                        </div>
                        <!-- DOCUMENTOS  -->

                        <div class="row" id="descargarAdjuntos">
                        </div>

                        <div class="col-md-12 espacio4"><p></p></div> <!-- Salto de Linea -->
                        <div class="clearfix espacio4"></div>
                      </div>
                      <!-- CUPO DE CREDITO -->
                      
                    </div>

                    
                    <div class="col-md-4">
                      <p><i class="fa fa-id-card"></i> </p>
                      <button id="btng" type="button" class="btn btn-success" onclick="guardar();" data-loading-text="<div class='loader'></div> Cargando, Espere...">Guardar</button>
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
                <div class="panel-heading" style="background-color: #222D32 !important">
                  <h3 class="panel-title">SUDICOL | Clientes Listado</h3>
                </div>
                <div class="panel-body" style="background-color: #fafafa">
                  <div class="single-bottom row">
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