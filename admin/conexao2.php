<?php 
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'herbert';
$DATABASE_NAME = 'phpgallery';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Falha na conexão com MYSQL: ' . mysqli_connect_error());
}
 ?>