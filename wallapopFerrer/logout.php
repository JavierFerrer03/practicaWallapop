<?php
//Con estas funciones borramos todas las variables de sesión que habíamos creado anteriormente
//y tambíen borramos la cookie del email y redirigimos a nuestro login 
session_start();
session_unset();
setcookie('email','',0,'/');
header('Location: index.php');
?>