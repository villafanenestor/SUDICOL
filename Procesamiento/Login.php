<?php 

require_once("Conexion.php");
  if( isset($_POST["Action"]) AND isset($_POST["usuario"])){
					logearse($mysqli);							
	} 

  
											
function logearse($mysqli){

   $consulta="SELECT  nombre,usuario,tipo FROM usuarios 
  WHERE  usuario='".mysqli_real_escape_string($mysqli,$_POST["usuario"])."' 
  AND pass='".mysqli_real_escape_string($mysqli,$_POST["pass"])."'   AND estado='Activo'   "; 

$datos=mysqli_query($mysqli,$consulta);

if($row=mysqli_fetch_row($datos)){ 

                 session_start();
           
                $_SESSION['STCK-USER_NOMBRE']=$row[0];
                $_SESSION['STCK-USER_USUARIO']=$row[1];
                $_SESSION['STCK-USER_TIPO']=$row[2]; 

            echo 'OK';
          }else{
            echo 'Usuario o Contraseña invalido';
          }
}

?>