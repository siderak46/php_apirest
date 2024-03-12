<?php
require_once("conexion/conexion.php");

class token extends conexion{

    public function actualizarToken($fecha){
        $query = "UPDATE usuarios_token SET Estado = 'Inactivo' WHERE Fecha < '$fecha' AND Estado = 'Activo'";
        $verificar = parent::nonquery($query);
        if ($verificar> 0){
            return 1;
        }   else{
            return 0;
        }

    }







}

?>