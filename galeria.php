<?php
function pdo_connect_mysql() {
    // Atualize os detalhes abaixo com seus detalhes do MySQL
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'herbert';
    $DATABASE_NAME = 'phpgallery';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // Se houver um erro na conexão, pare o script e exiba o erro.
        die ('Falha na conexão com o Banco de Dados');
    }
}

// Template do modelo, fique à vontade para personalizar este
function template_header($title) {
echo <<<EOT

EOT;
}

// Template footer
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}

// Connect to MySQL
$pdo = pdo_connect_mysql();
// MySQL query that selects all the images
$stmt = $pdo->query('SELECT * FROM images ORDER BY uploaded_date DESC');
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content home">
	<h2>Produtos a venda</h2>
	<p>Peças e produtos novos e usados.</p>
	
	<div class="images">
		<?php foreach ($images as $image): ?>
		<?php if (file_exists($image['path'])): ?>
		<a href="#">
			<img src="<?=$image['path']?>" alt="<?=$image['description']?>" data-id="<?=$image['id']?>" data-title="<?=$image['title']?>" width="300" height="200">
			<span><?=$image['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
