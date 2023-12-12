<?php 
session_start(); 
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Anuncio.php');
require_once('modelos/AnuncioDAO.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');

$conexion=new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexion->getConexion();

$error="";
$idAnuncio=htmlspecialchars($_GET['id']);

$anuncioDAO=new AnuncioDAO($conn);
$anuncio=$anuncioDAO->obtenerAnuncioPorId($idAnuncio);

if($_SERVER['REQUEST_METHOD']=='POST'){
    $titulo=htmlspecialchars($_POST['titulo']);
    $precio=htmlspecialchars($_POST['precio']);
    $descripcion=strip_tags($_POST['descripcion'],"<p> <br> <ul> <strong>");
    $fotos = $_FILES['nuevaImagen'];
        
    $fotoPaths = array(); 
    
    foreach ($fotos['tmp_name'] as $key => $tmp_name) {
        $formatos = ['image/jpeg', 'image/gif', 'image/webp', 'image/png'];
        if (in_array($fotos['type'][$key], $formatos)) {
            $nombreArchivo = md5(time() + rand());
            $partes = explode('.', $fotos['name'][$key]);
            $extension = end($partes); // Obtener la extensión
            $foto = $nombreArchivo . '.' . $extension;

            while (file_exists("fotosAnuncios/$foto")) {
                $nombreArchivo = md5(time() + rand());
                $foto = $nombreArchivo . '.' . $extension;
            }

            $rutaDestino = "fotosAnuncios/$foto";
            if (move_uploaded_file($tmp_name, $rutaDestino)) {
                $fotoPaths[] = $rutaDestino;
            } else {
                $error="La foto no se ha podido mover a la carpeta de destino";
            }
        } else {
            $error="El formato de los archivos no es compatible, Intentelo con otro formato";
        }
    }

    if(empty($titulo) || empty($precio) || empty($descripcion) || empty($fotos)){
        $error="Los datos para insertar los anuncios son obligatorios";
    }
    else{
        $anuncio->setTitulo($titulo);
        $anuncio->setPrecio($precio);
        $anuncio->setDescripcion($descripcion);
        $anuncio->setIdUsuario($_SESSION['idUsu']);
        if (count($fotoPaths) > 0) {
            $anuncio->setFoto($fotoPaths[0]);
        }
        if($anuncioDAO->modificarAnuncio($anuncio)){
            header('location: misAnuncios.php');
            die();
        }
        else{
            $error="Su anuncio no se ha podido modificar";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDITAR TU ANUNCIO</title>
    <link rel="stylesheet" href="./assets/styles1.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: 'ai tinycomments mentions anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed permanentpen footnotes advtemplate advtable advcode editimage tableofcontents mergetags powerpaste tinymcespellchecker autocorrect a11ychecker typography inlinecss',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | tinycomments | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        height: 300,
        width: 500,
        mergetags_list: [
        { value: 'First.Name', title: 'First Name' },
        { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
    });
    </script>
</head>
<body>
    <div class="container_editarAnuncios">
        <header class="header_anuncios">
            <div class="image_anuncios">
                <a href="mostrar_anuncios.php"><img src="./assets/images/logo.png" alt="" class="img_anuncios"></a>
            </div>
            <div class="div_title">
                <h2 class="title_anuncios">Bienvenido <?= $_SESSION['nombre'] ?></h2>
                <img src="./assets/images/usuario.png" alt="" class="icono_user">
            </div>
        </header>
        <main class="main_newAnuncio">
            <form action="editar_anuncio.php?id=<?= $idAnuncio ?>" method="POST" enctype="multipart/form-data" class="form_newAnuncio">
                <span class="span_insert"><?= $error ?></span>
                <h3 class="subtitle_newAnuncio">MODIFIQUE SU ANUNCIO</h3>
                <input type="text" class="input_newAnuncio" name="titulo" placeholder="Título" value="<?= $anuncio->getTitulo() ?>"><br>
                <input type="text" class="input_newAnuncio" name="precio" placeholder="Precio" value="<?= $anuncio->getPrecio() ?>"><br>
                <div class="div_textarea">
                    <textarea class="editor-texto" name="descripcion" placeholder="Añade tu descripción" id="mytextarea"><?= $anuncio->getDescripcion() ?></textarea><br>
                </div>
                <div class="photo">
                    <div class="div_image">
                        <button type="button" onclick="eliminarImagen()" class="button_eliminar">Eliminar Imagen</button>
                        <?php
                            $rutaImg = $anuncio->getFoto();
                            if (!empty($rutaImg)) {
                                echo '<img src="' . $rutaImg . '" alt="Imagen Anuncio" class="imagen_editar">';
                            } else {
                                echo '<p>Imagen no disponible</p>';
                            }
                        ?>
                    </div>
                </div>           
                    <!-- Input para cargar nueva imagen -->
                <div class="photo_add">
                    <input type="file" name="nuevaImagen[]">
                </div>
                <input type="submit" value="GUARDAR" class="button_anuncio">
                <button class="button_anuncio"><a href="misAnuncios.php" class="link_volver">VOLVER</a></button>
            </form>
        </main>
    </div>
    <script src="./assets/script.js"></script>
</body>
</html>