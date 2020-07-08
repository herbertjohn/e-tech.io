<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../../index.html');
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
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Admin E-tech</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sair</a>
			</div>
		</nav>
		<div class="content">
			<h2>Página Inicial</h2>
			<p>Olá <?=$_SESSION['name']?>!</p>
			<br><a  href="tablesort.php"> CADASTRO DE PRODUTOS</a>
		</div>
	</body>
</html>