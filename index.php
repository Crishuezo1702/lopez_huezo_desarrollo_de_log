<?php
    ini_set("display_errors","1");
    ini_set("display_startup_errors","1");
    error_reporting(E_ALL);
   include("core/inc/funciones.inc.php");
   include("core/secure/ips.php");
   $archivo = "./logs/log.log";
   $ip = ip_in_range($_SERVER["REMOTE_ADDR"], $rango);

?>

<!DOCTYPE html>
<html lang="es-SV">
    <head>
        <title>INICIO DE SESION : CRISTIAN MARCELO LOPEZ HUEZO</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-widht, initial-scale=1.0"/>
        <link rel="stylesheet" href ="Css/bootstrap.css" />
        <link href="fonts/fontawesome/css/all.css" rel="Stylesheet" />
        <script type="text/javascript" scr="js/jquery-3.7.1.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script>
        <script type ="text/javascript" src="js/sweetalert.all.js"></script>
        <script type ="text/javascript" src ="fonts/fontawesome/js/all.js"></script>
    </head>
    <body>
        <div class="alert alert-warning" role ="alert">
            <b></b>
        </div>



        <div class="form-row">
            <div class ="form-group col-md-5 text-center">
                <img src="media/logo/logo_corporativo.png" class="mx-auto d-block" id="img" widht="65%" height="auto" />
            </div>
            <div class="form-group col-md-5 ml-4 mr-4 justify-content-center align-self-center">
                <h1>Diseñanando Estrategias para la Recuperacion y Migracion de Base de Datos (RBK0)</h1>
                <form name="frm_iniciar_Sesion" id ="frm_iniciar_Sesion" action="core/process.php" method="post">
                    <div class="form-group">
                        <label for="txt_user">Usuario:</label>
                        <input type="text" class="form-control" id="txt_user" name="txt_user" aria-describedby="txt_userHelp" maxlenght="10" requiered>
                        <small id ="text_userHelp" class="form-text text-muted">Digite un usuario(Campo obligatorio).</small>
                    </div>
                    <div class="form-group">
                        <label for="txt_pass">Contraseña</label>
                        <input type="password" class="form-control" id = "txt_pass" name="txt_pass" aria_describedby="txt_passHelp" maxlenght="10" requiered>
                        <small id="txt_passHelp" class ="form-text text-muted">La contraseña es obligatoria</small>
                    </div>
                    <button type="submit" id="btn_ingresar" class="btn btn-primary mx-auto d-block" value="ingresar">Iniciar Sesion</button>
                </form>
            </div>
        </div>
    </body>
</html>