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
			<h1>CADASTRO DE PRODUTOS</h1>
			<form action="cadastrado.php" method="post" enctype="multipart/form-data">
			Arquivo:<input type="file" required="" name="arquivo">
			<input type="submit" value="Salvar">
		</div>

		<div>
			<table  width="100px">
				<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'herbert';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$result = mysqli_query($con,"SELECT * FROM arquivo");

echo "<table border='1'>
<tr>
<th>Código</th>
<th>Arquivo</th>
<th>data</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td>" . $row['codigo'] . "</td>";
echo "<td>" . $row['arquivo'] . "</td>";
echo "<td>" . $row['data'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>
				
				

		
			</div>


		</div>

		





		


	</body>
</html>