<?php
session_start();
unset( $_SESSION['LMNS-USER_NOMBRE']);
unset( $_SESSION['LMNS-USER_USUARIO']);
unset( $_SESSION['LMNS-USER_TIPO']);
unset( $_SESSION['LMNS-USER_CODE']);
unset( $_SESSION['LMNS-USER_NOMBREC']);
unset( $_SESSION['LMNS-USER_USUARIOC']);
unset( $_SESSION['LMNS-USER_CODEC']);
unset( $_SESSION['LMNS-USER_CLIENTE']);
unset( $_SESSION['LMNS-USER_DPTO']);
unset( $_SESSION['LMNS_dpto']);
unset( $_SESSION['LMNS_unidad']);
header('Location: Entrar.php');

?>
