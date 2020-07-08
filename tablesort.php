<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../../index.html');
	exit;
}
// Below is optional, remove if you have already connected to your database.
$mysqli = mysqli_connect('localhost', 'root', 'herbert', 'tablesort');

// For extra protection these are the columns of which the user can sort by (in your database table).
$columns = array('name','age','joined');

// Only get the column if it exists in the above columns array, if it doesn't exist the database table will be sorted by the first item in the columns array.
$column = isset($_GET['column']) && in_array($_GET['column'], $columns) ? $_GET['column'] : $columns[0];

// Get the sort order for the column, ascending or descending, default is ascending.
$sort_order = isset($_GET['order']) && strtolower($_GET['order']) == 'desc' ? 'DESC' : 'ASC';

// Get the result...
if ($result = $mysqli->query('SELECT * FROM students ORDER BY ' .  $column . ' ' . $sort_order)) {
	// Some variables we need for the table.
	$up_or_down = str_replace(array('ASC','DESC'), array('up','down'), $sort_order); 
	$asc_or_desc = $sort_order == 'ASC' ? 'desc' : 'asc';
	$add_class = ' class="highlight"';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Admin E-tech</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

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
				<h1>Admin E-tech</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Perfil</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Sair</a>
			</div>
		</nav>
		<div class="content">
			<h2>CADASTRO DE PRODUTOS</h2>
			<P>nome:</P>
	<form action="gravar.php" method="POST" enctype="multipart/form-data">
        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem"/>
        <br/>
        <input type="submit" value="Enviar"/>
    </form>

			<br>

						<table>
				<tr>
					<th><a href="tablesort.php?column=name&order=<?php echo $asc_or_desc; ?>">Produto<i class="fas fa-sort<?php echo $column == 'name' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="tablesort.php?column=age&order=<?php echo $asc_or_desc; ?>">Valor<i class="fas fa-sort<?php echo $column == 'age' ? '-' . $up_or_down : ''; ?>"></i></a></th>
					<th><a href="tablesort.php?column=joined&order=<?php echo $asc_or_desc; ?>">Entrada<i class="fas fa-sort<?php echo $column == 'joined' ? '-' . $up_or_down : ''; ?>"></i></a></th>
				</tr>
				<?php while ($row = $result->fetch_assoc()): ?>
				<tr>
					<td<?php echo $column == 'name' ? $add_class : ''; ?>><?php echo $row['name']; ?></td>
					<td<?php echo $column == 'age' ? $add_class : ''; ?>><?php echo $row['age']; ?></td>
					<td<?php echo $column == 'joined' ? $add_class : ''; ?>><?php echo $row['joined']; ?></td>
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