<?php
session_start(); 
require_once('modelos/Anuncio.php');
require_once('modelos/AnuncioDAO.php');
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');
require_once('modelos/Foto.php');


$error="";

$conexion=new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexion->getConexion();
if(!isset($_SESSION['email'])){
    header('location: error.php');
    die();
}
$anuncioDAO = new AnuncioDAO($conn);
$anuncio = new Anuncio();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = htmlentities($_POST['titulo']);
    $descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
    $precio = htmlentities($_POST['precio']);
    $fotos = $_FILES['foto'];
        
    $fotoPaths = array(); 
    
    foreach ($fotos['tmp_name'] as $key => $tmp_name) {
        $formatos = ['image/jpeg', 'image/gif', 'image/webp', 'image/png'];
        if (in_array($fotos['type'][$key], $formatos)) {
            $nombreArchivo = md5(time() + rand());
            $partes = explode('.', $fotos['name'][$key]);
            $extension = end($partes); 
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
    
    $anuncio->setTitulo($titulo);
    $anuncio->setDescripcion($descripcion);
    $anuncio->setPrecio($precio);
    $anuncio->setIdUsuario($_SESSION['idUsu']);

    if (count($fotoPaths) > 0) {
        $anuncio->setFoto($fotoPaths[0]);
        if ($anuncioDAO->insertAnuncio($anuncio)) {
            $idAnun = $anuncio->getId();
            for ($i = 1; $i < count($fotoPaths); $i++) {
                $anuncioDAO->insertFotoAnuncio($fotoPaths[$i], $idAnun);
            }
            header('location: mostrar_anuncios.php');
            die();
        } else {
            $error="No se ha podido insertar el anuncio en la base de datos";
        }
    } else {
        $error="Error al cargar las imágenes en la base de datos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Anuncio</title>
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
    <div class="container">
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
        <form action="insertar_anuncio.php" method="POST" enctype="multipart/form-data" class="form_newAnuncio">
            <h3 class="subtitle_newAnuncio">INTRODUCE LOS DATOS</h3>
            <input type="text" class="input_newAnuncio" name="titulo" placeholder="Título"><br>
            <input type="text" class="input_newAnuncio" name="precio" placeholder="Precio"><br>
            <div class="div_textarea">
                <textarea class="editor-texto" name="descripcion" placeholder="Añade tu descripción" id="mytextarea"></textarea><br>
            </div>
            <input type="file" name="foto[]" multiple><br>
            <span class="span_insert"><?= $error ?></span>
            <input type="submit" value="INSERTAR" class="button_anuncio">
            <button class="button_anuncio"><a href="mostrar_anuncios.php" class="link_volver">VOLVER</a></button>
        </form>
        </main>
        &copy; Copyrigth 2023
    </div>
</body>
</html>