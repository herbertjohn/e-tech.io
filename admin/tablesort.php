<?php
// Precisamos usar sessões, portanto você deve sempre iniciar as sessões usando o código abaixo.
session_start();
// Se o usuário não estiver conectado, redirecione para a página de login...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../../index.php');
	exit;
}
// Abaixo é opcional, remova se você já se conectou ao seu banco de dados.
$mysqli = mysqli_connect('localhost', 'root', 'herbert', 'tablesort');

// Para proteção extra, essas são as colunas pelas quais o usuário pode classificar (na tabela do banco de dados).
$columns = array('name','age','joined');

// Somente obtenha a coluna se ela existir na matriz de colunas acima; se não existir, a tabela do banco de dados será classificada pelo primeiro item na matriz de colunas.
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];

// Obter a ordem de classificação da coluna, crescente ou decrescente, o padrão é crescente.
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

// Obter o resultado ...
if ($result = $mysqli->query('SELECT * FROM students ORDER BY ' .  $column . ' ' . $sort_order)) {
	// Algumas variáveis que precisamos para a tabela.
	$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
	$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
	$add_class = ' class="highlight"';

	include("conexao.php");

	$msg = false;

	if(isset($_FILES['arquivo'])){

		$extensao = strtolower(substr($_FILES['arquivo']['name'], -4)) ; //pega a extensao do arquivo
		$novo_nome = md5(time()) . $extensao; //define o nome do arquivo
		$diretorio = "upload/"; //define o diretorio para onde enviaremos o arquivo

		move_uploaded_file($FILES['arquivo']['tmp_name'], $diretorio.$novo_nome); //efetua o upload

		$sql_code = "INSERT INTO arquivo (codigo, arquivo, data) VALUES(null, '$novo_nome', NOW())";
		if ($mysqli->query($sql_code))
			$msg = "Arquivo enviado com sucesso!";
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

		<style>
			html {
				font-family: Tahoma, Geneva, sans-serif;
			}
			table {
				border-collapse: collapse;
				display: flex;
				flex-direction: row;
				justify-content: center;
				align-items: center;
			}
			th {
				background-color: #54585d;
				border: 1px solid #54585d;
			}
			th:hover {
				background-color: #64686e;
			}
			th a {
				display: block;
				text-decoration:none;
				padding: 10px;
				color: #ffffff;
				font-weight: bold;
				font-size: 13px;
			}
			th a i {
				margin-left: 5px;
				color: rgba(255,255,255,0.4);
			}
			td {
				padding: 10px;
				color: #636363;
				border: 1px solid #dddfe1;
			}
			tr {
				background-color: #ffffff;
			}
			tr .highlight {
				background-color: #f9fafb;
			}
			</style>

	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<?php 	include("assets/menu.php"); ?>
			</div>
		</nav>
		<div class="content">

				

			<h2>CADASTRO DE PRODUTOS</h2>
	<?php if(msg !=false) echo "<p> $msg </p>"; ?>
			<form action="tablesort.php" method="post" enctype="multipart/form-data">
			Arquivo:<input type="file" required="" name="arquivo">
			<input type="submit" value="Salvar">

			<br><br>	<br>

						<table>
				<tr>
					<th><a href="tablesort.php?column=name&order=<?php echo $asc_or_desc; ?>">Produto<i class="fas fa-sort<?php echo $column == 'name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="tablesort.php?column=age&order=<?php echo $asc_or_desc; ?>">Valor<i class="fas fa-sort<?php echo $column == 'age' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="tablesort.php?column=joined&order=<?php echo $asc_or_desc; ?>">Entrada<i class="fas fa-sort<?php echo $column == 'joined' ? '-' . $up_or_down : ''; ?>"></i></a></th>
				</tr>
				<?php while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td<?php echo $column == 'name' ? $add_class : ''; ?>><?php echo $row['name']; ?> </td>
					<td<?php echo $column == 'age' ? $add_class : ''; ?>><?php echo $row['age']; ?> </td>
					<td<?php echo $column == 'joined' ? $add_class : ''; ?>><?php echo $row['joined']; ?> </td>
				</tr>
				<?php endwhile; ?>
			</table>

		</div>
	</body>
</html>
	<?php
	$result->free();
}
?>