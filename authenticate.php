	<?php
session_start();
// Altere isso para suas informações de conexão.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'herbert';
$DATABASE_NAME = 'phplogin';
// Tente conectar-se usando as informações acima.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// Se houver um erro na conexão, pare o script e exiba o erro.
	exit('Falha ao conectar com o MySQL ' . mysqli_connect_error());
}


// Agora verificamos se os dados do formulário de login foram enviados, isset () verificará se os dados existem.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Não foi possível obter os dados que deveriam ter sido enviados.
	exit('Por favor, preencha os campos de nome de usuário e senha!');
}


// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Parâmetros de ligação (s = string, i = int, b = blob, etc); no nosso caso, o nome de usuário é uma string, então usamos "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Armazene o resultado para que possamos verificar se a conta existe no banco de dados.
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
	$stmt->bind_result($id, $password);
	$stmt->fetch();
	// A conta existe, agora verificamos a senha.
	// Nota: lembre-se de usar password_hash no seu arquivo de registro para armazenar as senhas com hash.
	if (password_verify($_POST['password'], $password)) {
		// Sucesso na verificação! Usuário logado!
		// Crie sessões para que saibamos que o usuário está logado, eles agem basicamente como cookies, mas lembre-se dos dados no servidor.
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
	header('Location: /admin/home.php');	} else {
		echo 'Incorrect password!';
	}
} else {
	echo 'Incorrect username!';
}


	$stmt->close();
}
?>


