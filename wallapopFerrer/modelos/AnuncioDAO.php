<?php

class AnuncioDAO{
    
    private $conn;

    public function __construct($conn){
        $this->conn = $conn;
    }

    //Método para insertar un anuncio a la base de datos
    public function insertAnuncio(Anuncio $anuncio){
        if(!$stmt=$this->conn->prepare("INSERT INTO anuncios(titulo, precio, foto, idUsuario, descripcion) VALUES (?,?,?,?,?)")){
            echo "Error en la ejecución de la SQL " . $this->conn->error;
        }

        $titulo=$anuncio->getTitulo();
        $precio=$anuncio->getPrecio();
        $foto=$anuncio->getFoto();
        $descripcion=$anuncio->getDescripcion();
        $idUsuario=$anuncio->getIdUsuario();
        
        $stmt->bind_param('sdsis', $titulo, $precio, $foto, $idUsuario, $descripcion);

        if($stmt->execute()){
            $idAnuncio=$stmt->insert_id;
            $anuncio->setId($idAnuncio);
            return $anuncio;
        }else{
            return false;
        }
    }
   
    //Método para mostrar todos los anuncios registrados en la base de datos
    public function mostrarAnuncio():array{
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncios ORDER BY fecha DESC")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->execute();
        $result=$stmt->get_result();

        $arrayAnuncios=array();
        while($anuncio=$result->fetch_object(Anuncio::class)){
            $arrayAnuncios[]=$anuncio;
        }
        return $arrayAnuncios;
    }

    //Método para mostrar los anuncios que pertenecen al id de usuario
    public function mostrarAnunciosPorId($idUsuario):array{
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncios WHERE idUsuario = ? ORDER BY fecha DESC")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->bind_param('i',$idUsuario);
        $stmt->execute();
        $result=$stmt->get_result();

        $arrayAnunciosID=array();
        while($anuncioId=$result->fetch_object(Anuncio::class)){
            $arrayAnunciosID[]=$anuncioId;
        }

        return $arrayAnunciosID;
        
    }

    //Métedo para eliminar un anuncio por id
    public function eliminarAnuncios($id){
        if(!$stmt = $this->conn->prepare("DELETE FROM anuncios WHERE id = ?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();

        if($stmt->affected_rows==1){
            return true;
        }
        else{
            return false;
        }

    }

    //Método para obtener el anuncio por su id
    public function obtenerAnuncioPorId($id):Anuncio|null{
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncios WHERE id = ?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows==1){
            $anuncio=$result->fetch_object(Anuncio::class);
            return $anuncio;
        }
        else{
            return null;
        }
    }

    //Método para insertar las fotos en la tabla fotos de la base de datos
    public function insertFotoAnuncio($rutaFoto, $idAnuncio) {
        try {
            $query = "INSERT INTO fotos (foto, idAnuncio) VALUES (?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("si", $rutaFoto, $idAnuncio);

            if ($stmt->execute()) {
                return true;
            } else {
                return false; 
            }
        } catch (Exception $e) {
            return false;
        }
    }

    //Método para actualizar un anuncio que se encuentra en la base de datos
    public function modificarAnuncio($anuncio){
        if(!$stmt = $this->conn->prepare("UPDATE anuncios SET precio=?, titulo=?, descripcion=?, foto=?, idUsuario=? WHERE id=?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $precio=$anuncio->getPrecio();
        $titulo=$anuncio->getTitulo();
        $descripcion=$anuncio->getDescripcion();
        $foto=$anuncio->getFoto();
        $idUsuario=$anuncio->getIdUsuario();
        $id=$anuncio->getId();

        $stmt->bind_param('dsssii', $precio, $titulo, $descripcion, $foto, $idUsuario, $id);
        return $stmt->execute();
    }

    //Método para mostrar las fotos de la base de datos según el id del anuncio
    public function mostrarFotosAnuncios($idAnuncio){
        if(!$stmt = $this->conn->prepare("SELECT * FROM fotos WHERE idAnuncio=?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result=$stmt->get_result();

        $arrayFotos=array();
        while($fotoId=$result->fetch_object(Foto::class)){
            $arrayFotos[]=$fotoId;
        }

        return $arrayFotos;

    }

    //Método para mostrar los anuncios filtrados por su título
    public function mostrarAnunciosPorTitulo($titulo){
        if(!$stmt = $this->conn->prepare("SELECT * FROM anuncios WHERE UPPER(titulo) LIKE ?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }
        $tituloLike='%' . $titulo . '%';
        $stmt->bind_param('s', $tituloLike);
        $stmt->execute();
        $result=$stmt->get_result();

        $anunciosPorTitulo=array();
        while($anuncios=$result->fetch_object(Anuncio::class)){
            $anunciosPorTitulo[]=$anuncios;
        }
        return $anunciosPorTitulo;

    }

    public function obtenerIdUsuario($idAnuncio){
        if(!$stmt = $this->conn->prepare("SELECT idUsuario FROM anuncios WHERE id = ?")){
            die("Error al ejecutar la consulta SQL ")  . $this->conn->error;
        }

        $stmt->bind_param('i', $idAnuncio);
        $stmt->execute();
        $result=$stmt->get_result();

        if($result->num_rows > 0){
            $row=$result->fetch_assoc();
            return $row['idUsuario'];
        }
        else{
            return false;
        }
    }
    
}
?>