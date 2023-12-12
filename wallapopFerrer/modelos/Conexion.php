<?php 
class Conexion{
    private $usuario;
    private $password;
    private $host;
    private $dataBase;
    private $conn;

    function __construct($usuario, $password, $host, $dataBase){
        //Creamos la conexión a la Base de Datos
        $this->conn=new mysqli($host, $usuario, $password, $dataBase);
        if($this->conn->connect_error){
            die("Error al conectar con la base de datos");
        }
    }
    
    function getConexion(){
        return $this->conn;
    }
        
}


?>