<?php
// Precisamos usar sessões, portanto você deve sempre iniciar as sessões usando o código abaixo.
session_start();
// Se o usuário não estiver conectado, redirecione para a página de login ...
if (!isset($_SESSION['loggedin'])) {
	header('Location: home.php');
	exit;
}

include 'functions.php';
// A mensagem de saída
$msg = '';
// Verifique se o usuário enviou uma nova imagem
if (isset($_FILES['image'], $_POST['title'], $_POST['description'])) {
	// A pasta onde as imagens serão armazenadas
	$target_dir = '../images/';
	// O caminho da nova imagem carregada
	$image_path = $target_dir . basename($_FILES['image']['name']);
	// Verifique se a imagem é válida
	if (!empty($_FILES['image']['tmp_name']) && getimagesize($_FILES['image']['tmp_name'])) {
		if (file_exists($image_path)) {
			$msg = 'A imagem já existe, escolha outra ou renomeie essa imagem.';
		} else if ($_FILES['image']['size'] > 500000) {
			$msg = 'Tamanho do arquivo de imagem muito grande; escolha uma imagem com menos de 500kb.';
		} else {
			// Tudo está certo agora podemos mover a imagem carregada
			move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
			// Connect to MySQL
			$pdo = pdo_connect_mysql();
			// Inserir informações da imagem no banco de dados (título, descrição, caminho da imagem e data de adição)
			$stmt = $pdo->prepare('INSERT INTO images VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)');
	        $stmt->execute([$_POST['title'], $_POST['description'], $image_path]);
			$msg = 'Image uploaded successfully!';
		}
	} else {
		$msg = 'Faça o upload de uma imagem!';
	}
}
?>

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
					

<div class="content upload">
	<h2>Upload Image</h2>
	<form action="upload.php" method="post" enctype="multipart/form-data">
		<label for="image">Choose Image</label>
		<input type="file" name="image" accept="image/*" id="image">
		<label for="title">Title</label>
		<input type="text" name="title" id="title">
		<label for="description">Description</label>
		<textarea name="description" id="description"></textarea>
	    <input type="submit" value="Upload Image" name="submit">
	</form>
	<p><?=$msg?></p>
</div>

	</body>
</html>