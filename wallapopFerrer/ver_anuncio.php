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

if(!isset($_SESSION['email'])){
    header('location: error.php');
    die();
}

$anuncioDAO=new AnuncioDAO($conn);
$idAnuncio=htmlspecialchars($_GET['id']);
$usuarioDAO=new UsuarioDAO($conn);

$anuncio=$anuncioDAO->obtenerAnuncioPorId($idAnuncio);
$fotos=$anuncioDAO->mostrarFotosAnuncios($idAnuncio);

$anuncioUsuario=$anuncioDAO->obtenerIdUsuario($idAnuncio);
$usuario=$usuarioDAO->mostrarUsuariosPorId($anuncioUsuario);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ANUNCIO</title>
    <link rel="stylesheet" href="./assets/styles1.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
</head>
<body>
    <header class="header_anuncios">
        <div class="image_anuncios">
            <img src="./assets/images/logo.png" alt="" class="img_anuncios">
        </div>
        <div class="div_title">
            <h2 class="title_anuncios">Bienvenido <?= $_SESSION['nombre'] ?></h2>
            <img src="./assets/images/usuario.png" alt="" class="icono_user">
        </div>
    </header>
    <main class="main_verAnuncio">
        <?php if($anuncio!=null):?>
            <h2 class="title_verAnuncio"><?= $anuncio->getTitulo() ?></h2>
            <div class="datos_usuario">
                <div class="div_usuarioAnuncio">
                    <img src="./assets/images/usuario1.png" alt="" class="usuario_img">
                    <h5 class="usuario_h5"><?= $usuario->getNombre() ?></h5>
                </div>
                <div class="usuario_button">
                    <button class="button_mg">Me Gusta</button>
                </div>
            </div>
            <article class="article_verAnuncio">
                <div class="fotosAnuncios">
                    <?php 
                        $rutaImg = $anuncio->getFoto();
                        if (!empty($rutaImg)) {
                            echo '<img src="' . $rutaImg . '" alt="Imagen Anuncio" class="imgAnuncios">';
                        } else {
                            echo '<p>Imagen no disponible</p>';
                        }
                    ?>
                    <?php foreach($fotos as $image):?>
                        <?php
                            $rutaImg = $image->getFoto();
                            if (!empty($rutaImg)) {
                                echo '<img src="' . $rutaImg . '" alt="Imagen Anuncio" class="imgAnuncios">';
                            } else {
                                echo '<p>Imagen no disponible</p>';
                            }
                        ?>
                    <?php endforeach;?>
                </div>
                <h2 class="h2_verAnuncio"><?= $anuncio->getPrecio() ?> €</h2>
                <h2 class="h2_verAnuncio"><?= $anuncio->getTitulo() ?></h2>
                <h3 class="h3_verAnuncio"><?= $anuncio->getDescripcion() ?></h3>
                <div class="poblacion_verAnuncio">
                    <div class="anuncios_poblacion">
                        <img src="./assets/images/ubicacion.png" alt="" class="anuncios_image">
                        <h5 class="h5_anuncios"><?= $usuario->getPoblacion() ?></h5>
                    </div>
                    <div class="iframe_poblacion">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12319.632091723688!2d-3.5429153811102876!3d39.471406545712!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6990fcfb5a6c5d%3A0xb035db616bb6aa52!2s45710%20Madridejos%2C%20Toledo!5e0!3m2!1ses!2ses!4v1700411343114!5m2!1ses!2ses" width="500" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="iframe"></iframe>
                    </div>
                </div>
                <div class="infoPropietario">
                    <h3 class="titlePropietario">Datos Del Propietario</h3>
                    <h4>Correo Electrónico: <a href="mailto:jferrermariblanca@gmail.com" class="link_correo"><?= $usuario->getEmail() ?></a></h4>
                    <h4>Teléfono: <?= $usuario->getTelefono() ?></h4>
                </div>
                <div class="button_verAnun">
                    <button class="button_anuncio"><a href="mostrar_anuncios.php" class="link_volver">VOLVER</a></button>
                </div>
            </article>
        <?php else:?>
                <?php echo "El anuncio no existe"?>    
        <?php endif;?>
    </main>
    &copy; Copyrigth 2023
</body>
</html>