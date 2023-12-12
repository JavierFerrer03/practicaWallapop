<?php 
class UsuarioDAO{

    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    public function insertarUsuarios(Usuario $usuario): int|bool{
        if(!$stmt=$this->conn->prepare("INSERT INTO usuarios(email, password, nombre, telefono, poblacion) VALUES (?,?,?,?,?)")){
            die("Error al ejecutar la consulta SQL " . $this->conn->error);
        }

        $email=$usuario->getEmail();
        $password=$usuario->getPassword();
        $nombre=$usuario->getNombre();
        $telefono=$usuario->getTelefono();
        $poblacion=$usuario->getPoblacion();

        $stmt->bind_param('sssss',$email, $password, $nombre, $telefono, $poblacion);

        if($stmt->execute()){
            return $stmt->insert_id;
        }else{
            return false;
        }
    }

    public function obtenerUsuarioPorEmail($email){
        if(!$stmt=$this->conn->prepare("SELECT * FROM usuarios WHERE email = ?")){
            die("Error al ejecutar la consulta SQL " . $this->conn->error);
        }
        
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result=$stmt->get_result();
    
        if($result->num_rows >= 1){
            $usuario = $result->fetch_object(Usuario::class);
            return $usuario;
        }
        else{
            return null;
        }
    }

    public function mostrarUsuarios():array{
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios")){
            die("Error al ejecutar la consulta SQL ") . $this->conn->error;
        }

        $stmt->execute();
        $result=$stmt->get_result();

        while($usuario=$result->fetch_object(Usuario::class)){
            $array_usuarios[]=$usuario;
        }
        return $array_usuarios;
    }

    public function mostrarUsuariosPorId($id){
        if(!$stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE id = ?")){
            die("Error al ejecutar la consulta SQL ") . $this->conn->error;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows >= 1){
            $usuario=$result->fetch_object(Usuario::class);
            return $usuario;
        }
        else{
            return false;
        }
    }

    
}
?>