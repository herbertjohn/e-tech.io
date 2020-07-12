<?php
// Abaixo é opcional, remova se você já se conectou ao seu banco de dados.
$mysqli = mysqli_connect('localhost', 'root', 'herbert', 'phpgallery');

// Obtenha o número total de registros da nossa tabela "alunos".
$total_pages = $mysqli->query('SELECT * FROM images')->num_rows;

// Verifique se o número da página está especificado e se é um número; se não, retorne o número da página padrão que é 1.
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Número de resultados a serem exibidos em cada página.
$num_results_on_page = 10;

if ($stmt = $mysqli->prepare('SELECT * FROM images ORDER BY id LIMIT ?,?')) {
	// Calcule a página para obter os resultados que precisamos da nossa tabela.
	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	// Obtenha os resultados ...
	$result = $stmt->get_result();
	?>
<!DOCTYPE html>
<html>
	<head>
		<style>

			.pagination {
				list-style-type: none;
				padding: 10px 0;
				display: inline-flex;
				justify-content: space-between;
				box-sizing: border-box;
				 margin: 0;
			    position: absolute;
			    left: 50%;
			    margin-right: -50%;
			    transform: translate(-50%, -50%) }
						}
			.pagination li {
				box-sizing: border-box;
				padding-right: 10px;
			}
			.pagination li a {
				box-sizing: border-box;
				background-color: #e2e6e6;
				padding: 8px;
				text-decoration: none;
				font-size: 12px;
				font-weight: bold;
				color: #616872;
				border-radius: 4px;
			}
			.pagination li a:hover {
				background-color: #d4dada;
			}
			.pagination .next a, .pagination .prev a {
				text-transform: uppercase;
				font-size: 12px;
			}
			.pagination .currentpage a {
				background-color: #518acb;
				color: #fff;
			}
			.pagination .currentpage a:hover {
				background-color: #518acb;
			}
			</style>
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
		<div class="content"><br><br>
			
			<table>
				<tr>
					<th>ID</th>
					<th>Nome</th>
					<th>Descrição</th>
					<th>Data</th>
				</tr>
				<?php while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td><?php echo $row['id']; ?></td>
					<td><?php echo $row['title']; ?></td>
					<td><?php echo $row['description']; ?></td>
					<td><?php echo $row['uploaded_date']; ?></td>
				</tr>
				<?php endwhile; ?>
			</table>
			<div class="centralizado">

			<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
			<ul class="pagination">
				<?php if ($page > 1): ?>
				<li class="prev"><a href="cadastrado.php?page=<?php echo $page-1 ?>">Prev</a></li>
				<?php endif; ?>

				<?php if ($page > 3): ?>
				<li class="start"><a href="cadastrado.php?page=1">1</a></li>
				<li class="dots">...</li>
				<?php endif; ?>

				<?php if ($page-2 > 0): ?><li class="page"><a href="cadastrado.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
				<?php if ($page-1 > 0): ?><li class="page"><a href="cadastrado.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

				<li class="currentpage"><a href="cadastrado.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

				<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="cadastrado.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
				<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="cadastrado.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
				<li class="dots">...</li>
				<li class="end"><a href="cadastrado.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
				<?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
				<li class="next"><a href="cadastrado.php?page=<?php echo $page+1 ?>">Next</a></li>
				<?php endif; ?>
			</ul>
			<?php endif; ?>

		</div>
				</div>
		</div>
	</body>
</html>
	<?php
	$stmt->close();
}
?>