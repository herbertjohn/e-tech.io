<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../../index.html');
	exit;
}
include("conexao.php");

	$msg = false;

	if(isset($_FILES['arquivo'])){

		$extensao = strtolower(substr($_FILES['arquivo']['name'], -4)) ; //pega a extensao do arquivo
		$novo_nome = md5(time()) . $extensao; //define o nome do arquivo
		$diretorio = "upload/"; //define o diretorio para onde enviaremos o arquivo

		move_uploaded_file($FILES['arquivo']['tmp_name'], $diretorio.$novo_nome); //efetua o upload

		$sql_code = "INSERT INTO arquivo (codigo, arquivo, data) VALUES(null, '$novo_nome', NOW())";
		if ($mysqli->query($sql_code))
			$msg = "Arquivo enviado com sucesso!;";
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
				<h1><a href="home.php"> Admin E-tech</a></h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sair</a>
			</div>
		</nav>
		<div class="content">
			<h2>Página Inicial</h2>
			<p>Olá <?=$_SESSION['name']?>!</p>
			<br>

			<h1>CADASTRO DE PRODUTOS</h1>
			<form action="cadastrado.php" method="post" enctype="multipart/form-data">
			Arquivo:<input type="file" required="" name="arquivo">
			<input type="submit" value="Salvar">
		</div>

		





		


	</body>
</html>