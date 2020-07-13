<?php
// A quantidade de produtos a serem exibidos em cada página
$num_products_on_each_page = 4;
// A página atual, no URL, aparecerá como index.php? Page = produtos & p = 1, index.php? Page = produtos & p = 2, etc ...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// Selecionar produtos encomendados pela data de adição
$stmt = $pdo->prepare('SELECT * FROM products ORDER BY date_added DESC LIMIT ?,?');
// bindValue nos permitirá usar inteiro na instrução SQL, precisamos usar para LIMIT
$stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
$stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
$stmt->execute();
// Busque os produtos do banco de dados e retorne o resultado como uma Matriz
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Obtenha o número total de produtos
$total_products = $pdo->query('SELECT * FROM products')->rowCount();
?>


<div class="products content-wrapper">
    <h1>Products</h1>
    <p><?=$total_products?> Products</p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
        <a href="produto.php?page=shoppingcart/product&id=<?=$product['id']?>" class="product">
            <img src="shoppingcart/imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['name']?>">
            <span class="name"><?=$product['name']?></span>
            <span class="price">
                &dollar;<?=$product['price']?>
                <?php if ($product['rrp'] > 0): ?>
                <span class="rrp">&dollar;<?=$product['rrp']?></span>
                <?php endif; ?>
            </span>
        </a>
        <?php endforeach; ?>
    </div>
    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="index.php?page=products&p=<?=$current_page-1?>">Prev</a>
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
        <a href="index.php?page=products&p=<?=$current_page+1?>">Next</a>
        <?php endif; ?>
    </div>
</div>


