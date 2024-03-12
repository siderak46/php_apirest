<?php
require_once("conexion/conexion.php");
require_once("respuestas.class.php");

class auth extends conexion{
    public function login($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        if(!isset($datos["usuario"]) || !isset($datos["password"])){
            //error con los compos
            return $_respuestas->error_400();
        }else{
            //todos esta bien
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $datos= $this->obtenerDatosUsuario($usuario);
            if($datos){
                //verificar si la contraseña es igual
                if($password==$datos[0]['Password']){
                    if($datos[0]['Estado']=="Activo"){
                        //crear el token
                        $verificar=$this->insertarToken($datos[0]['UsuarioId']) ;
                        if($verificar){ 
                            //si se guardo
                            $result= $_respuestas->response;
                            $result["result"]=array("token"=>$verificar);
                            return $result;
                        } else {
                            //error al guardar
                            return $_respuestas->error_500("error interno, no se guardo");
                        }       
                    }   else{
                        //el usuario esta inactivo
                    return $_respuestas->error_200("el usuario esta inactivo");
                    }
                }   else {
                    //la contraseña no es igual
                    return $_respuestas->error_200("el password es invalido");
                }
            }   else{   
                //no existe el usuario
                return $_respuestas->error_200("El usuario $usuario no existe");
            }
        }

    }
    
    public function estadoToken($etoken){
        $_respuestas = new respuestas;
        $query = "SELECT Estado FROM usuarios_token WHERE Token = '" . $etoken . "' AND Estado = 'Activo'";
        $resp=parent::obtenerDatos($query);
        if($resp){return $_respuestas->response;} else { return $_respuestas->error_200("el token esta inactivo"); }
       
    }
    private function obtenerDatosUsuario($correo){
        $query = "SELECT UsuarioId,Password,Estado FROM usuarios Where Usuario='$correo'";
        $datos = parent:: obtenerDatos($query);
        if(isset($datos[0]["UsuarioId"])){
            return $datos;
        }   else{
            return 0;
        }
    }

    private function insertarToken($usuarioid){
        $val=true;
        $token= bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date=date("Y-m-d H:i");
        $estado="Activo";
        $query="INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha) VALUES('$usuarioid','$token','$estado','$date')";
        $verifica= parent::nonQuery($query);
        if($verifica){
            return $token;
        }   else {  
            return 0;}
    }   
}
?>