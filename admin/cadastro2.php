<?php
// Precisamos usar sessões, portanto você deve sempre iniciar as sessões usando o código abaixo.
session_start();
// Se o usuário não estiver conectado, redirecione para a página de login...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../../index.php');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
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

				

			<h2>CADASTRO DE PRODUTOS</h2>


			<form method="POST" action="proc_upload.php" enctype="multipart/form-data">
			<input type="file" name="arquivo" id="arquivo" onchange="previewImagem()"><br><br>
			<img style="width: 150px; height: 150px;"><br><br>
			
			<input type="submit" value="Cadastrar">
		</form>		
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		
		<script>
			function previewImagem(){
				var imagem = document.querySelector('input[name=arquivo]').files[0];
				var preview = document.querySelector('img');
				
				var reader = new FileReader();
				
				reader.onloadend = function () {
					preview.src = reader.result;
				}
				
				if(imagem){
					reader.readAsDataURL(imagem);
				}else{
					preview.src = "";
				}
			}
		</script>

						

		</div>
	</body>
</html>
