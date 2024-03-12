<?php
    require_once '../clases/token.class.php';
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    $_token = new token;
    $fecha = date('Y-m-d H:i');
    echo $_token->actualizarToken($fecha);

?>