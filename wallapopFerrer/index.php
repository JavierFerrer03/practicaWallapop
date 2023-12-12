<?php
session_start(); 
require_once('modelos/Anuncio.php');
require_once('modelos/AnuncioDAO.php');
require_once('modelos/Conexion.php');
require_once('modelos/conf.php');
require_once('modelos/Usuario.php');
require_once('modelos/UsuarioDAO.php');

$conexionBD=new Conexion(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_BD);
$conn=$conexionBD->getConexion();

$error="";

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=htmlspecialchars($_POST['email']);
    $password=htmlspecialchars($_POST['password']);

    $usuarioDAO=new UsuarioDAO($conn);
    //Condición para comprobar que el email existe en la base de datos
    if($usuario=$usuarioDAO->obtenerUsuarioPorEmail($email)){
        //Condición para verificar que la contraseña es correcta y crear las variables de sesión
        if(password_verify($password, $usuario->getPassword())){
            $_SESSION['email']=$usuario->getEmail();
            $_SESSION['password']=$usuario->getPassword();
            $_SESSION['nombre']=$usuario->getNombre();
            $_SESSION['idUsu']=$usuario->getId();
            $_SESSION['poblacion']=$usuario->getPoblacion();
            setcookie('email',$email,time()+7*24*60*60);
            header('location: mostrar_anuncios.php');
            die();
        }
        else{
            $error="Contraseña incorrecta";
        }
    }
    else{
        $error="Email y contraseña incorrectos";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WALLAPOP FERRER</title>
    <link rel="stylesheet" href="./assets/styles.css">
    <link rel="shortcut icon" href="./assets/images/metodo-de-pago.png" type="image/x-icon">
</head>
<body>
    <div>
    <div class="container">
        <form action="index.php" method="POST" class="form">
            <h1 class="title">Inicio</h1>
            <div class="inp">
                <input type="email" class="input" placeholder="Email" name="email">
            </div>
            <div class="inp">
                <input type="password" class="input" placeholder="Contraseña" name="password">
            </div>
            <span class="span_index"><?= $error ?></span>
            <button type="submit" class="button">Iniciar Sesión</button>
            <p class="footer">¿No tienes cuenta?<a href="registro.php" class="link">Por favor, Registrate</a></p>
        </form>
        <div class="banner">
            <h1 class="text_bienvenida">BIENVENIDO</h1>
        </div>
    </div>
    </div>
</body>
</html>