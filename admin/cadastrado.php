<?php
// Precisamos usar sessões, portanto você deve sempre iniciar as sessões usando o código abaixo.
session_start();
// Se o usuário não estiver conectado, redirecione para a página de login ...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.php');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'herbert';
$DATABASE_NAME = 'phpgallery';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Falha na conexão com MYSQL: ' . mysqli_connect_error());
}

$result = mysqli_query($con,"SELECT * FROM images");

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
		</nav>
		<div class="content">
			
			<h1>PRODUTOS</h1>

			
<?php

echo "<table border='1'>
<tr>
<th>Nome</th>
<th>Descrição</th>
<th>Data</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['title'] . "</td>";
echo "<td>" . $row['description'] . "</td>";
echo  "<td>" . $row['uploaded_date'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
				</div>
		</div>
	</body>
</html>