<?php
// Prevent direct access to file
defined('shoppingcart') or exit;
// Check for search query
if (isset($_GET['query']) && $_GET['query'] != '') {
    // Escape the user query, prevent XSS attacks
    $search_query = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
    // Select products ordered by the date added
    $stmt = $pdo->prepare('SELECT * FROM products WHERE name LIKE ? ORDER BY date_added DESC');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->execute(['%' . $search_query . '%']);
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of products
    $total_products = count($products);
} else {
    // Simple error, if no search query was specified why is the user on this page?
    $error = 'Nenhuma consulta de pesquisa foi especificada!';
}
?>

<?=template_header('pesquisar')?>

<?php if ($error): ?>

<p class="content-wrapper error"><?=$error?></p>

<?php else: ?>

<div class="products content-wrapper">

    <h1>Resultados da busca por "<?=$search_query?>"</h1>

    <p><?=$total_products?> Produtos</p>

    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="index.php?page=product&id=<?=$product['id']?>" class="product">
            <img src="imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['name']?>">
            <span class="name"><?=$product['name']?></span>
            <span class="price">
                <?=currency_code?><?=number_format($product['price'],2)?>
                <?php if ($product['rrp'] > 0): ?>
                <span class="rrp"><?=currency_code?><?=number_format($product['rrp'],2)?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>

</div>

<?php endif; ?>

<?=template_footer()?>
