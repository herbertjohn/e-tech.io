<?php
// Precisamos usar sessões, portanto você deve sempre iniciar as sessões usando o código abaixo.
session_start();
// Se o usuário não estiver conectado, redirecione para a página de login ...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>

		<style>
			html {
				font-family: Tahoma, Geneva, sans-serif;
			}</style>
		<meta charset="utf-8">
		<title>Admin E-tech</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<?php 	include("assets/menu.php"); ?>

			</div>
		</nav>
		<div class="content">
					

			<h3><a href="home.php"> CADASTRO 1 </a> </h3>
			<p>home</p>
			<br>
			<h3><a href="cadastro2.php"> CADASTRO 2 </a> </h3>
			<p>PopUp com JAVA</p>
			<br>
			<h3><a href="cadastro3.php"> CADASTRO 3 </a> </h3>
			<p>mensagem na pagina</p>
			<br>
			<h3> <a href="cadastro4.php"> CADASTRO 4 </a> </h3>
			<p>previa da imagem</p>
			<br>


	</body>
</html>