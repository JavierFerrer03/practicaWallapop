<?php
session_start(); 
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');

$conexion=new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexion->getConexion();

$usuarioDAO=new UsuarioDAO($conn);
$usuario=new Usuario();

$error=""; 


if($_SERVER['REQUEST_METHOD']=='POST'){
    $emailRegistro=htmlspecialchars($_POST['email_registro']);
    $passwordRegistro=htmlspecialchars($_POST['password_registro']);
    $nombreRegistro=htmlspecialchars($_POST['nombre_registro']);
    $telefonoRegistro=htmlspecialchars($_POST['telefono_registro']);
    $poblacionRegistro=htmlspecialchars($_POST['poblacion_registro']); 

    
    if($usuarioDAO->obtenerUsuarioPorEmail($emailRegistro)!=null){
        $error="El usuario ya existe";
    }
    if(empty(trim($emailRegistro)) || empty(trim($passwordRegistro))){
        $error="Email y contraseña obligatorios";
    }
    if($passwordRegistro < 5){
        $error="La contraseña debe contener al menos 4 caractéres";
    }
    

    if($error==""){
        $usuario->setEmail($emailRegistro);
        $passwordCifrado=password_hash($passwordRegistro, PASSWORD_DEFAULT);
        $usuario->setPassword($passwordCifrado);
        $usuario->setNombre($nombreRegistro);
        $usuario->setTelefono($telefonoRegistro);
        $usuario->setPoblacion($poblacionRegistro);
        if($usuarioDAO->insertarUsuarios($usuario)){
            header('location: index.php');
            die();
        }
        else{
            $error="No se puedo insertar el usuario";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="./assets/styles1.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
</head>
<body>
    <div class="container_registro">
    <header class="header_anuncios">
            <div class="image_anuncios">
                <a href="index.php"><img src="./assets/images/logo.png" alt="" class="img_anuncios"></a>
            </div>
            <div class="div_title">
                <h2 class="title_anuncios">Bienvenido</h2>
                <img src="./assets/images/usuario.png" alt="" class="icono_user">
            </div>
        </header>
        <main class="main_registro">
            <h1 class="title_registro">DATOS DEL NUEVO USUARIO</h1>
            <form action="registro.php" method="POST" class="form_registro">
                <input type="email" class="input_registro" name="email_registro" placeholder="example@example.com"> <br>
                <input type="password" class="input_registro" name="password_registro" placeholder="********"> <br>
                <input type="text" class="input_registro" name="nombre_registro" placeholder="Escribe tu nombre"> <br>
                <input type="text" class="input_registro" name="telefono_registro" placeholder="Escribe tu teléfono"> <br>
                <input type="text" class="input_registro" name="poblacion_registro" placeholder="Escribe tu poblacion"> <br>
                <div class="span">
                    <span class="span_registro"><?= $error ?></span>
                </div>
                <input type="submit" value="REGISTRARSE" class="button_anuncio">
                <button class="button_anuncio"><a href="index.php" class="link_volver">VOLVER</a></button>
            </form>
        </main>
    </div>
</body>
</html>