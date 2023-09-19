<?php
session_start();
unset($_SESSION['STCK-USER_NOMBRE']);
unset($_SESSION['STCK-USER_USUARIO']);
unset($_SESSION['STCK-USER_TIPO']);
unset($_SESSION['STCK-USER_CODE']);
unset($_SESSION['STCK-USER_NOMBREC']);
unset($_SESSION['STCK-USER_USUARIOC']);
unset($_SESSION['STCK-USER_CODEC']);
unset($_SESSION['STCK-USER_CLIENTE']);
unset($_SESSION['STCK-USER_DPTO']);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <title>Sudicol</title>
    <!-- meta tags -->
    <meta charset="UTF-8" />

    <script src="js/jquery-1.11.1.min.js"></script>
    <!--bootstrap-js-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <!--script-->
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <!-- /meta tags -->
    <!-- custom style sheet -->
    <link href="cssLog/style.css" rel="stylesheet" type="text/css" />
    <!-- /custom style sheet -->
    <!-- fontawesome css -->
    <link href="cssLog/fontawesome-all.css" rel="stylesheet" />
    <!-- /fontawesome css -->
    <!-- google fonts-->
    <link href="//fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- /google fonts-->
    <script>
        $(document).ready(function() {
            $("#user").focus();
        });

        function Ingresar() {
            var usuario = $("#user");
            var pass = $("#pass");

            if (usuario.val() == '') {
                swal("Atención!", "Ingrese Usuario", "warning");
                usuario.focus();
                return false;
            }
            if (pass.val() == '') {
                swal("Atención!", "Ingrese Contraseña", "warning");
                pass.focus();
                return false;
            }

            //alert("si");

            $.ajax({
                data: {
                    usuario: usuario.val(),
                    pass: pass.val(),
                    'Action': 'OK'
                },
                url: "Procesamiento/Login.php",
                type: "POST",
                success: function(resp) {

                    if (resp == "OK") {
                        document.location = "Road.php"
                    } else {
                        swal("Atención!", resp, "error");
                    }
                },
                error: function(resp) {
                    swal("Error!", "Error Al Conectarse Al Servidor", "error");
                }
            });
        }

        function chequearEnter(event) {
            if (event.keyCode == 13) {
                Ingresar();
            }
        }
    </script>

</head>


<body>
    <h1 style='color: #164194;font-weight: bold;  font-family:"Palatino", sans-serif;padding: 2% !important'>Inicio De Sesión</h1>
    <div class=" w3l-login-form">

        <div class=" w3l-form-group">
            <label>Usuario:</label>
            <div class="group">
                <i class="fas fa-user"></i>
                <input type="text" class="form-control" id="user" placeholder="Usuario" required="required" onkeypress="chequearEnter(event)" />
            </div>
        </div>
        <div class=" w3l-form-group">
            <label>Contraseña:</label>
            <div class="group">
                <i class="fas fa-unlock"></i>
                <input type="password" class="form-control" id="pass" placeholder="Contraseña" required="required" onkeypress="chequearEnter(event)" />
            </div>
        </div>
        <button type="button" id="log" onclick="Ingresar();">Iniciar Sesión</button><br>

    </div>

</body>

</html>