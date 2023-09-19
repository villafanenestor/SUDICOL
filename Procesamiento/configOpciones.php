<?php
require_once("Conexion.php");

$option = isset($_POST['option']) ? $_POST['option'] : '';

if($option == 'ciudades') getCiudades();
elseif($option == 'departamentos') getDepartamentos();

function getCiudades(){
    global $mysqli;
    $ciudades = array();
    $select = "select cons, nombre from u682444666_stock.ciudades order by nombre ASC";
    $result = $mysqli->query($select);
    if($result->num_rows > 0){
        while($ciudad = $result->fetch_array()){
            // $ciudades[$ciudad["cons"]] = $ciudad["nombre"];
            array_push($ciudades, $ciudad);
        }
        // $ciudades = $result->fetch_array();
    }
    header('Content-Type: application/json');
    echo json_encode($ciudades);
}

function getDepartamentos(){
    echo "Sin implementacion";
}


?>