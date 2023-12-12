<?php 
session_start(); 
require_once('modelos/Anuncio.php');
require_once('modelos/AnuncioDAO.php');
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');
require_once('modelos/Foto.php');

$conexion=new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexion->getConexion();

$anuncioDAO=new AnuncioDAO($conn);
$anuncio=new Anuncio();
//Mediante el envío get me coge la id del anuncio que quiero eliminar
$idAnuncio=htmlspecialchars($_GET['id']);
$anuncio=$anuncioDAO->obtenerAnuncioPorId($idAnuncio);
$fotos=$anuncioDAO->mostrarFotosAnuncios($idAnuncio);


$error="";
//Llamo al método de eliminar anuncio y le paso por parámetro el id que he recogido mediante el método GET
if($anuncioDAO->eliminarAnuncios($idAnuncio)){
    //Obtengo la foto principal y la elimino de la carpeta en la que estaba
    $rutaImg=$anuncio->getFoto();
    unlink($rutaImg);
    //Recorro el array con todas las fotos dependiente del id
    foreach($fotos as $foto){
        //Elimino las fotos de la carpeta que tenga el mismo id del anuncio
        unlink($foto->getFoto());
    }
}
else{
    $error="No se eliminó el anuncio correctamente";
}

//Redigirigo a la página donde se muestran los anuncios de ese usuario
header('location: misAnuncios.php');
die();