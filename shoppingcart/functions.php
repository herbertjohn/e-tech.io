<?php
function pdo_connect_mysql() {
    // Atualize os detalhes abaixo com seus detalhes do MySQL
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'herbert';
    $DATABASE_NAME = 'shoppingcart';
    try {
    	return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// Se houver um erro na conexão, pare o script e exiba o erro.
    	die ('Falha ao conectar ao banco de dados!');
    }
}
// Cabeçalho do modelo, fique à vontade para personalizar este
function template_header($title) {
    // Obter a quantidade de itens no carrinho de compras, isso será exibido no cabeçalho.
$num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                
                <div class="link-icons">
                    <a href="../carrinho.php">
						<i class="fas fa-shopping-cart"></i>
                        <span>$num_items_in_cart</span>
					</a>
                </div>
            </div>
        </header>
        <main>
EOT;
}
// Template footer
function template_footer() {
$year = date('Y');
echo <<<EOT
        </main>
        <fo
        </footer>
        <script src="script.js"></script>
    </body>
</html>
EOT;
}
?>