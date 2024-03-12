<?php
require_once("clases/auth.class.php");
require_once("clases/respuestas.class.php");
$_auth = new auth;
$_respuestas= new respuestas;
if($_SERVER['REQUEST_METHOD'] == "POST"){
    //recibir datos
    $postBody = file_get_contents("php://input");
    //enviar datos al manejador
    $datosArray = $_auth->login($postBody);
    //devolvemos una respuesta
    header('content-type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, origin");
    if(isset($datosArray["result"]["error_id"])){
        $responseCode=$datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
}else if($_SERVER['REQUEST_METHOD']== "GET"){
    if(isset($_GET['token'])){
        $token = $_GET['token'];
        $verificarToken=$_auth->estadoToken($token);
        header('content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, origin");
        http_response_code(200);
        echo json_encode($verificarToken);
    }

}else{
    header('content-type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, origin");
    $datosArray= $_respuestas->error_405();
    echo json_encode($datosArray);
}      
?>