<?php
require_once("clases/respuestas.class.php");
require_once("clases/pacientes.class.php");
$_respuestas = new respuestas;
$_pacientes= new pacientes;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
if($_SERVER['REQUEST_METHOD']== "GET"){
    if(isset($_GET["page"])){
        $pagina = $_GET["page"];
        $listaPacientes=$_pacientes->listaPacientes($pagina);
        header('content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, origin");
        http_response_code(200);
        echo json_encode($listaPacientes);
    }   else if(isset($_GET['id'])){
        $pacienteid = $_GET['id'];
        $datosPaciente = $_pacientes->obtenerPaciente($pacienteid);
        header('content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, origin");
        echo json_encode($datosPaciente);
        http_response_code(200);
    }

}   else if($_SERVER['REQUEST_METHOD']== "POST"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al manejador
    $datosArray = $_pacientes->post($postBody);
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

}   else if($_SERVER['REQUEST_METHOD']== "PUT"){
    //recibimos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos datos al manejador
    $datosArray = $_pacientes->put($postBody);
    //devolvemos una respuesta
    header('content-type: application/json');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Content-Type, origin");
    if(isset($datosArray["result"]["error_id"])){
        $responseCode=$datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);

}else if($_SERVER['REQUEST_METHOD'] == "DELETE"){

    $headers = getallheaders();
    if(isset($headers["token"]) && isset($headers["pacienteId"])){
        //recibimos los datos enviados por el header
        $send = [
            "token" => $headers["token"],
            "pacienteId" =>$headers["pacienteId"]
        ];
        $postBody = json_encode($send);
    }else{
        //recibimos los datos enviados
        $postBody = file_get_contents("php://input");
    }
    
    //enviamos datos al manejador
    $datosArray = $_pacientes->delete($postBody);
    //delvovemos una respuesta 
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, origin");
    if(isset($datosArray["result"]["error_id"])){
        $responseCode = $datosArray["result"]["error_id"];
        http_response_code($responseCode);
    }else{
        http_response_code(200);
    }
    echo json_encode($datosArray);
   

}else{
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, origin");
$datosArray = $_respuestas->error_405();
echo json_encode($datosArray);
}


?>