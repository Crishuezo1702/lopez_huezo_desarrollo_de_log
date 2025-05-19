<?php
ini_set('display_error',1);
ini_set('display_startup_error',1);
include('inc/funciones.inc.php');
include('secure/ips.php');

$metodo_permitido = "POST";
$archivo = "../logs/log.log";
$dominio_autorizado = "localhost";
$ip = ip_in_ranges($_SERVER["REMOTE_ADDR"],$rango);
$txt_usuario_autorizado = "admin";
$txt_password_autorizado = "admin";

// Verifica que se accedió a este archivo desde otra página del sistema
if(array_key_exists("HTTP_REFERER",$_SERVER)){
    // Comprueba que el origen de la solicitud corresponde al dominio permitido
    if(strpos($_SERVER["HTTP_REFERER"],$dominio_autorizado)){
        // Asegura que la IP del usuario esté en la lista autorizada
        if($ip === true){
            // Verifica que el método HTTP usado sea el correcto
            if($_SERVER["REQUEST_METHOD"] == $metodo_permitido){
                // Obtiene los datos enviados desde el formulario y los limpia
                $valor_campo_usuario = (array_key_exists("txt_user",$_POST)) ? htmlspecialchars(stripslashes(trim($_POST["txt_user"])),ENT_QUOTES) : "";
                $valor_campo_password = (array_key_exists("txt_pass",$_POST)) ? htmlspecialchars(stripslashes(trim($_POST["txt_pass"])),ENT_QUOTES) : "";
                
                // Se asegura de que ambos campos tengan contenido
                if(($valor_campo_usuario != "" || strlen($valor_campo_usuario) > 0) && ($valor_campo_password != "" || strlen($valor_campo_password) > 0)){
                    // Valida el formato de los datos ingresados
                    $usuario = preg_match('/^[a-zA-Z0-9]{1,10}+$/',$valor_campo_usuario);
                    $password = preg_match('/^[a-zA-Z0-9]{1,10}+$/',$valor_campo_password);
                    
                    // Revisa que los datos coincidan con los valores esperados
                    if($usuario !== false && $usuario !== 0 && $password !== false && $password !== 0){
                        // Verifica las credenciales
                        if($valor_campo_usuario === $txt_usuario_autorizado && $valor_campo_password === $txt_password_autorizado){
                            // Acceso concedido
                            echo("HOLA MUNDO");
                            crear_editar_log($archivo,"Inicio de sesión exitoso",1,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
                        } else {
                            // Credenciales incorrectas
                            crear_editar_log($archivo,"Intento fallido de inicio de sesión desde {$_SERVER['HTTP_HOST']}{$_SERVER['HTTP_REQUEST_URI']}",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
                            header("HTTP/1.1 301 Moved Permanently");
                            header("Location: ../?status=7");
                        }
                    } else {
                        // Formato inválido en los datos ingresados
                        crear_editar_log($archivo,"Datos del formulario con caracteres no válidos",3,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
                        header("HTTP/1.1 301 Moved Permanently");
                        header("Location: ../?status=6");
                    }
                } else {
                    // Campos vacíos recibidos
                    crear_editar_log($archivo,"Campos vacíos enviados al servidor",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
                    header("HTTP/1.1 301 Moved Permanently");
                    header("Location: ../?status=5");
                }
            } else {
                // Método HTTP no permitido
                crear_editar_log($archivo,"Método no autorizado",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: ../?status=4");
            }
        } else {
            // IP no autorizada
            crear_editar_log($archivo,"IP no permitida",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ../?status=3");
        }
    } else {
        // Referer no coincide con el dominio permitido
        crear_editar_log($archivo,"Origen de referencia no válido",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"],$_SERVER["HTTP_USER_AGENT"]);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ../?status=2");
    }
} else {
    // Acceso directo sin pasar por formulario
    crear_editar_log($archivo,"Intento de acceso directo no permitido",2,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_REFERER"] ?? "Sin Referer",$_SERVER["HTTP_USER_AGENT"]);
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ../?status=1");
}
?>
