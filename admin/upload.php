<?php 
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
	<meta charset="utf-8">
	<title>CADASTRO DE PRODUTOS</title>
</head>
<body>
<h1>CADASTRO DE PRODUTOS</h1>
<form action="upload.php" method="post" enctype="multipart/form-data">
	Arquivo:<input type="file" required="" name="arquivo">
	<input type="submit" value="Salvar">
</form>
</body>
</html>