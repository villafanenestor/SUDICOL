function validarCampo(valor, nombreCampo) {
    if (valor == "") {
      swal("Atención!", `${nombreCampo} es obligatorio`, "warning");
      return false;
    }
    return true;  
  }
  

function imprimirPlanilla (consecutivoOrdenCompra){
  console.log(consecutivoOrdenCompra);
  url = `Reportes/rptPlanillaOrdenCompra.php?consOrdenCompra=${consecutivoOrdenCompra}`;
  window.open(url, '_blank');
}



async function listaCiudades() {
  let ciudades = [];
  await $.ajax({
    url: "Procesamiento/configOpciones.php",
    type: "POST",
    data: {option: "ciudades"},
    success: function(resp){
      // console.log(resp);
      ciudades = resp;
    }
  });

  return ciudades;
}