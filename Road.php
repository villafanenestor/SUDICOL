<?php

    session_start();
if (  isset($_SESSION['STCK-USER_USUARIO']) ) { 


        header("Location: Home.php"); 
    }
    
?>
