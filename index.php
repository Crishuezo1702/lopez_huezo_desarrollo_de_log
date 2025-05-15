<?php
// Mostrar errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir funciones necesarias
include('inc/funciones.inc.php');
include('secure/ips.php');

// Configuración
$metodo_permitido = "POST";
$archivo_log = "../logs/log.log";
$dominio_autorizado = "localhost";
$usuario_autorizado = "admin";
$password_autorizado = "admin";

// Variables seguras desde $_SERVER
$referer = $_SERVER["HTTP_REFERER"] ?? 'No Referer';
$user_agent = $_SERVER["HTTP_USER_AGENT"] ?? 'Unknown';
$remote_addr = $_SERVER["REMOTE_ADDR"] ?? '0.0.0.0';
$request_uri = $_SERVER["REQUEST_URI"] ?? '/';
$host = $_SERVER["HTTP_HOST"] ?? 'localhost';

// Validar IP autorizada
$ip_autorizada = ip_in_ranges($remote_addr, $rango);

// Verificar que haya un REFERER
if (!empty($_SERVER["HTTP_REFERER"])) {

    // Validar dominio en REFERER
    if (strpos($referer, $dominio_autorizado) !== false) {

        // Validar IP
        if ($ip_autorizada) {

            // Validar método HTTP
            if ($_SERVER["REQUEST_METHOD"] === $metodo_permitido) {

                // Limpiar entradas
                $valor_usuario = isset($_POST["txt_user"]) ? htmlspecialchars(stripslashes(trim($_POST["txt_user"])), ENT_QUOTES) : "";
                $valor_password = isset($_POST["txt_pass"]) ? htmlspecialchars(stripslashes(trim($_POST["txt_pass"])), ENT_QUOTES) : "";

                // Verificar que no estén vacíos
                if (strlen($valor_usuario) > 0 && strlen($valor_password) > 0) {

                    // Validar patrón alfanumérico (1-10 caracteres)
                    $usuario_valido = preg_match('/^[a-zA-Z0-9]{1,10}$/', $valor_usuario);
                    $password_valido = preg_match('/^[a-zA-Z0-9]{1,10}$/', $valor_password);

                    if ($usuario_valido && $password_valido) {

                        // Validar credenciales
                        if ($valor_usuario === $usuario_autorizado && $valor_password === $password_autorizado) {
                            echo "HOLA MUNDO";
                            crear_editar_log($archivo_log, "EL CLIENTE INICIÓ SESIÓN SATISFACTORIAMENTE", 1, $remote_addr, $referer, $user_agent);
                        } else {
                            crear_editar_log($archivo_log, "CREDENCIALES INCORRECTAS ENVIADAS HACIA //{$host}{$request_uri}", 2, $remote_addr, $referer, $user_agent);
                            echo "Credenciales incorrectas. Redireccionando...";
                            echo "<meta http-equiv='refresh' content='3;url=/?status=7'>";
                            exit;
                        }

                    } else {
                        crear_editar_log($archivo_log, "ENVÍO DEL FORMULARIO CON CARACTERES NO SOPORTADOS", 3, $remote_addr, $referer, $user_agent);
                        echo "Datos con caracteres no válidos. Redireccionando...";
                        echo "<meta http-equiv='refresh' content='3;url=/?status=6'>";
                        exit;
                    }

                } else {
                    crear_editar_log($archivo_log, "ENVÍO DE CAMPOS VACÍOS AL SERVIDOR", 2, $remote_addr, $referer, $user_agent);
                    echo "Campos vacíos. Redireccionando...";
                    echo "<meta http-equiv='refresh' content='3;url=/?status=5'>";
                    exit;
                }

            } else {
                crear_editar_log($archivo_log, "ENVÍO DE MÉTODO NO AUTORIZADO", 2, $remote_addr, $referer, $user_agent);
                echo "Método HTTP no permitido. Redireccionando...";
                echo "<meta http-equiv='refresh' content='3;url=/?status=4'>";
                exit;
            }

        } else {
            crear_editar_log($archivo_log, "DIRECCIÓN IP NO AUTORIZADA", 2, $remote_addr, $referer, $user_agent);
            echo "Su dirección IP no está autorizada para visitar esta página. Será redirigido a un sitio seguro...";
            echo "<meta http-equiv='refresh' content='3;url=/?status=3'>";
            exit;
        }

    } else {
        crear_editar_log($archivo_log, "REFERER NO AUTORIZADO", 2, $remote_addr, $referer, $user_agent);
        echo "Origen de la solicitud no autorizado. Redireccionando...";
        echo "<meta http-equiv='refresh' content='3;url=/?status=2'>";
        exit;
    }

} else {
    crear_editar_log($archivo_log, "INTENTO DE ACCESO DIRECTO SIN REFERER", 2, $remote_addr, $referer, $user_agent);
    echo "Acceso directo no permitido. Redireccionando...";
    echo "<meta http-equiv='refresh' content='3;url=/?status=1'>";
    exit;
}
?>
