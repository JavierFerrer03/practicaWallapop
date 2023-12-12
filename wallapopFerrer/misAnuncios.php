<?php 
session_start(); 
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Anuncio.php');
require_once('modelos/AnuncioDAO.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');

$conexion = new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexion->getConexion();
if(!isset($_SESSION['email'])){
    header('location: error.php');
    die();
}
$anuncioDAO=new AnuncioDAO($conn);
$nombreSession=$_SESSION['nombre'];
$idUsuario=$_SESSION['idUsu'];

$anuncios=$anuncioDAO->mostrarAnunciosPorId($idUsuario);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Anuncios</title>
    <link rel="stylesheet" href="./assets/styles1.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container_misAnuncios">
        <header class="header_anuncios">
            <div class="image_anuncios">
                <a href="mostrar_anuncios.php"><img src="./assets/images/logo.png" alt="" class="img_anuncios"></a>
            </div>
            <div class="div_title">
                <h2 class="title_anuncios">Bienvenido <?= $nombreSession ?></h2>
                <img src="./assets/images/usuario.png" alt="" class="icono_user">
            </div>
        </header>
        <main class="main_misAnuncios">
            <secion class="section_misAnuncios">
                <h3 class="subtitle_misAnuncios">MIS ANUNCIOS</h3>
                    <?php if($anuncios!=null):?>
                        <?php foreach($anuncios as $anuncio):?>
                        <article class="anuncios">
                        <?php
                            $imagePath = $anuncio->getFoto();
                            if (!empty($imagePath)) {
                                echo '<img src="' . $imagePath . '" alt="Imagen Anuncio" class="imagenes">';
                            } else {
                                echo '<p>Imagen no disponible</p>';
                            }
                        ?>
                            <h2 class="title_anuncio"><?= $anuncio->getTitulo() ?></h2>
                            <h4 class="precio_anuncio"><?= $anuncio->getPrecio() . "€" ?></h4>
                            <button class="button_remove"><a href="eliminar_anuncio.php?id=<?= $anuncio->getId() ?>" class="link_anuncios">ELIMINAR</a></button>
                            <button class="button_remove"><a href="editar_anuncio.php?id=<?= $anuncio->getId() ?>" class="link_anuncios">MODIFICAR</a></button>
                        </article>
                    <?php endforeach;?>
                    <?php else:?>
                        <div class="mensajeVerAnuncios">
                            <h3 class="titleVerAnuncios">NO TIENES NINGÚN ANUNCIO REGISTRADO</h3>
                        </div>
                    <?php endif;?>
            </secion>
            <aside class="aside_anuncios">
                <div class="buttons">
                    <div class="div_aside">
                        <button class="button_anuncios"><a href="insertar_anuncio.php" class="link_anuncios">AÑADIR UN NUEVO ANUNCIO</a></button>
                    </div>
                    <div class="div_aside">
                        <button class="button_anuncios"><a href="misAnuncios.php" class="link_anuncios">MIS ANUNCIOS</a></button>
                    </div>
                    <div class="div_aside">
                        <button class="button_anuncios"><a href="mostrar_anuncios.php" class="link_anuncios">MOSTRAR TODOS ANUNCIOS</a></button>
                    </div>
                </div>
            </aside>
        </main>
        <footer class="footer">
            <div class="footer_categorias">
                <h3 class="title_footer">Categorías</h3>
                <ul class="list_disorder">
                    <li class="list_items"><a href="#" class="link_items">Coches</a></li>
                    <li class="list_items"><a href="#" class="link_items">Motos</a></li>
                    <li class="list_items"><a href="#" class="link_items">Moda y Accesorios</a></li>
                    <li class="list_items"><a href="#" class="link_items">TV, Audio y Foto</a></li>
                    <li class="list_items"><a href="#" class="link_items">Informática y Electrónica</a></li>
                    <li class="list_items"><a href="#" class="link_items">Deporte y Ocio</a></li>
                    <li class="list_items"><a href="#" class="link_items">Hogar y Jardín</a></li>
                    <li class="list_items"><a href="#" class="link_items">Electrodomésticos</a></li>
                    <li class="list_items"><a href="#" class="link_items">Cine, Libros y Música</a></li>
                    <li class="list_items"><a href="#" class="link_items">Construcción y Reformas</a></li>
                    <li class="list_items"><a href="#" class="link_items">Industrias y Agricultura</a></li>
                    <li class="list_items"><a href="#" class="link_items">Otros</a></li>
                </ul>
            </div>
            <div class="footer_redesSociales">
                <h3 class="title_footer">Redes Sociales</h3>
                <div class="div_iconos">
                    <a href="https://twitter.com/wallapop" target="_blank"><img src="./assets/images/twitter.png" alt="" class="icono_footer"></a>
                    <a href="https://www.instagram.com/wallapop/" target="_blank"><img src="./assets/images/instagram.png" alt="" class="icono_footer"></a>
                    <a href="https://www.youtube.com" target="_blank"><img src="./assets/images/youtube.png" alt="" class="icono_footer"></a>
                    <a href="https://www.facebook.com/wallapop.es/?locale=es_ES" target="_blank"><img src="./assets/images/facebook.png" alt="" class="icono_footer"></a>
                </div>
            </div>
        </footer>
    </div>
    &copy; Copyrigth 2023
</body>
</html>