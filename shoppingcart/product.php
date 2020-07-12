<?php
// Verifique se o parâmetro id está especificado no URL
if (isset($_GET['id'])) {
    // Prepare a instrução e execute, evita a injeção de SQL
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    // Busque o produto no banco de dados e retorne o resultado como uma Matriz
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verifique se o produto existe (a matriz não está vazia)
    if (!$product) {
        // Erro simples a ser exibido se o ID do produto não existir (a matriz está vazia)
        die ('O produto não existe!');
    }
} else {
    // Erro simples para exibir se o ID não foi especificado
    die ('O produto não existe!');
}
?>
<?=template_header('Product')?>

<div class="product content-wrapper">
    <img src="imgs/<?=$product['img']?>" width="500" height="500" alt="<?=$product['name']?>">
    <div>
        <h1 class="name"><?=$product['name']?></h1>
        <span class="price">
            &dollar;<?=$product['price']?>
            <?php if ($product['rrp'] > 0): ?>
            <span class="rrp">&dollar;<?=$product['rrp']?></span>
            <?php endif; ?>
        </span>
        <form action="index.php?page=cart" method="post">
            <input type="number" name="quantity" value="1" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
            <input type="hidden" name="product_id" value="<?=$product['id']?>">
            <input type="submit" value="Add To Cart">
        </form>
        <div class="description">
            <?=$product['desc']?>
        </div>
    </div>
</div>

<?=template_footer()?>