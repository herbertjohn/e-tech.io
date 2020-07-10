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
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>$title</title>
        <link href="style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    </head>
    <body>
    
EOT;
}

// Template footer
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>