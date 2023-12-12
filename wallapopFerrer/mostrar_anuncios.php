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

if(!isset($_SESSION['email'])){
    header('location: error.php');
    die();
}

$anuncioDAO=new AnuncioDAO($conn);
$anuncio=new Anuncio();
$nombreSession=$_SESSION['nombre'];
$anuncios=$anuncioDAO->mostrarAnuncio();
$anunciosTitulos="";
if($_SERVER['REQUEST_METHOD']=='POST'){
    $tituloAnuncio=htmlspecialchars($_POST['nomAnuncio']);
    $anunciosTitulos=$anuncioDAO->mostrarAnunciosPorTitulo($tituloAnuncio);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WALLFERRER</title>
    <link rel="stylesheet" href="./assets/styles1.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
</head>
<body>
    <div class="container_index">
    <header class="header_anuncios">
            <div class="image_anuncios">
                <img src="./assets/images/logo.png" alt="" class="img_anuncios">
            </div>
            <div class="filtradoNombre">
                <img src="./assets/images/buscar.png" alt="" class="icono_filtrado">
                <form action="mostrar_anuncios.php" method="POST">
                    <input type="search" name="nomAnuncio" class="input_filtrado" placeholder="Filtra por Nombre">
                    <input type="submit" class="button_buscar">
                </form>
            </div>
            <div class="div_title">
                <h2 class="title_anuncios">Bienvenido <?= $nombreSession ?></h2>
                <img src="./assets/images/usuario.png" alt="" class="icono_user">
            </div>
    </header>
        <main class="main_anuncios">
            <secion class="section_index">
                <div class="div_subtitle">
                    <h3 class="subtitle_anuncios">TODOS LOS ANUNCIOS</h3>
                </div>
                <div class="container_anuncios">
                <?php if($anunciosTitulos!=null):?>
                    <?php foreach($anunciosTitulos as $anunTitu):?>
                    <article class="anuncios">
                        <div class="anuncios_localidad">
                            <img src="./assets/images/ubicacion.png" alt="" class="anuncios_image">
                            <h5 class="h5_anuncios"><?= $_SESSION['poblacion'] ?></h5>
                        </div>
                        <?php
                            $rutaImg = $anunTitu->getFoto();
                            $id=$anunTitu->getId();
                            if (!empty($rutaImg)) {
                                echo '<a href="ver_anuncio.php?id=' . $id . '"><img src="' . $rutaImg . '" alt="Imagen Anuncio" class="imagenes"></a>';
                            } else {
                                echo '<p>Imagen no disponible</p>';
                            }
                        ?>
                        <h2 class="title_anuncio"><?= $anunTitu->getTitulo() ?></h2>
                        <h4 class="precio_anuncio"><?= $anunTitu->getPrecio() . "€" ?></h4>
                    </article>
                    <?php endforeach;?> 
                    <button class="button_anuncios"><a href="mostrar_anuncios.php" class="link_anuncios">Mostrar Anuncios</a></button>
                <?php else:?>
                    <?php foreach($anuncios as $anuncio):?>
                    <article class="anuncios">
                        <div class="anuncios_localidad">
                            <img src="./assets/images/ubicacion.png" alt="" class="anuncios_image">
                            <h5 class="h5_anuncios"><?= $_SESSION['poblacion'] ?></h5>
                        </div>
                        <?php
                            $rutaImg = $anuncio->getFoto();
                            $id=$anuncio->getId();
                            if (!empty($rutaImg)) {
                                echo '<a href="ver_anuncio.php?id=' . $id . '"><img src="' . $rutaImg . '" alt="Imagen Anuncio" class="imagenes"></a>';
                            } else {
                                echo '<p>Imagen no disponible</p>';
                            }
                        ?>
                        <h2 class="title_anuncio"><?= $anuncio->getTitulo() ?></h2>
                        <h4 class="precio_anuncio"><?= $anuncio->getPrecio() . "€" ?></h4>
                    </article>
                    <?php endforeach;?>
                <?php endif;?>    
                </div>
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
                        <button class="button_anuncios"><a href="logout.php" class="link_anuncios">CERRAR SESIÓN</a></button>
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
        <br>
        &copy; Copyrigth 2023
    </div>
</body>
</html>