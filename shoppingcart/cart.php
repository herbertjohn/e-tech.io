<?php 
// Se o usuário clicou no botão adicionar ao carrinho na página do produto, podemos verificar os dados do formulário
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Defina as variáveis de postagem para identificá-las facilmente, e também verifique se elas são inteiras
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Prepare a instrução SQL, basicamente estamos verificando se o produto existe em nosso banco de dados
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_POST['product_id']]);
    // Busque o produto no banco de dados e retorne o resultado como uma Matriz
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Verifique se o produto existe (a matriz não está vazia)
    if ($product && $quantity > 0) {
        // O produto existe no banco de dados, agora podemos criar / atualizar a variável de sessão para o carrinho
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // O produto existe no carrinho, basta atualizar a quantidade
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // O produto não está no carrinho, adicione-o
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // Não há produtos no carrinho, isso adicionará o primeiro produto ao carrinho
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    // Impedir o reenvio de formulário ...
    header('location: produtos.php?page=shoppingcart/cart');
    exit;
}

// Remova o produto do carrinho, verifique o parâmetro "remover" do URL, este é o ID do produto, verifique se é um número e verifique se está no carrinho
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // Retire o produto do carrinho de compras
    unset($_SESSION['cart'][$_GET['remove']]);
}

// Atualize as quantidades do produto no carrinho se o usuário clicar no botão "Atualizar" na página do carrinho de compras
if (isset($_POST['update']) && isset($_SESSION['cart'])) {
    // Faça um loop pelos dados da postagem para que possamos atualizar as quantidades de cada produto no carrinho
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'quantity') !== false && is_numeric($v)) {
            $id = str_replace('quantity-', '', $k);
            $quantity = (int)$v;
            // Sempre faça verificações e validação
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $quantity > 0) {
                // Atualizar nova quantidade
                $_SESSION['cart'][$id] = $quantity;
            }
        }
    }
    // Impedir o reenvio de formulário ...
    header('location: carrinho.php?page=shoppingcart/cart');
    exit;
}

// Envie o usuário para a página de pedido do local, se ele clicar no botão Fazer pedido, também o carrinho não deverá estar vazio
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: encomenda.php?page=shoppingcart/placeorder');
    exit;
}

// Verifique a variável da sessão para produtos no carrinho
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;
// Se houver produtos no carrinho
if ($products_in_cart) {
    // Existem produtos no carrinho, portanto, precisamos selecionar esses produtos no banco de dados
     // Produtos na matriz de carrinho para matriz de cadeia de ponto de interrogação, precisamos que a instrução SQL inclua IN (?,?,?, ... etc)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ')');
    // Precisamos apenas das chaves da matriz, não dos valores, as chaves são os IDs dos produtos
    $stmt->execute(array_keys($products_in_cart));
    // Busque os produtos do banco de dados e retorne o resultado como uma Matriz
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calcular o subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
    }
}
?>


<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <form action="carrinho.php?page=shoppingcart/cart" method="post">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Você não possui produtos adicionados ao seu carrinho de compras</td>
                </tr>
                <?php else: ?>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="produto.php?page=shoppingcart/product&id=<?=$product['id']?>">
                            <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                        </a>
                    </td>
                    <td>
                        <a href="produto.php?page=shoppingcart/product&id=<?=$product['id']?>"><?=$product['name']?></a>
                        <br>
                        <a href="carrinho.php?page=shoppingcart/cart&remove=<?=$product['id']?>" class="remove">Remove</a>
                    </td>
                    <td class="price">&dollar;<?=$product['price']?></td>
                    <td class="quantity">
                        <input type="number" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                    </td>
                    <td class="price">&dollar;<?=$product['price'] * $products_in_cart[$product['id']]?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$subtotal?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Update" name="update">
            <input type="submit" value="Place Order" name="placeorder">
        </div>
    </form>
</div>

<?=template_footer()?>