<?php
session_start();
// Inclua funções e conecte-se ao banco de dados usando o DOP MySQL
include 'functions.php';
$pdo = pdo_connect_mysql();

// A página está definida como home (home.php) por padrão, portanto, quando o visitante visitar a página que ele visualizará.
$page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
// Incluir e mostrar a página solicitada
include $page . '.php';
?>

