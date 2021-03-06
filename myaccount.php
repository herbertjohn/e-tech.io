<?php
// Prevent direct access to file
defined('shoppingcart') or exit;
// When the user clicks the submit form after filling in the email and password to login, check for post data and validate email
if (isset($_POST['email'], $_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Check if the account exists
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // If account exists verify password
    if ($account && password_verify($_POST['password'], $account['password'])) {
        // User has logged in, create session data
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['id'];
        $_SESSION['account_admin'] = $account['admin'];
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
        if ($products_in_cart) {
            // user has products in cart, redirect them to the checkout page
            header('Location: index.php?page=checkout');
        } else {
            // Redirect the user back to the same page, they can then see their order history
            header('Location: index.php?page=myaccount');
        }
        exit;
    } else {
        $error = 'E-mail ou Senha incorreto!';
    }
}
// If user is logged in
if (isset($_SESSION['account_loggedin'])) {
    // Select all the users transations, this will appear under "My Orders"
    $stmt = $pdo->prepare('SELECT
        p.img AS img,
        p.name AS name,
        t.created AS transaction_date,
        ti.item_price AS price,
        ti.item_quantity AS quantity
        FROM transactions t
        JOIN transactions_items ti ON ti.txn_id = t.txn_id
        JOIN accounts a ON a.id = t.account_id
        JOIN products p ON p.id = ti.item_id
        WHERE t.account_id = ?
        ORDER BY t.created DESC');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?=template_header('Minha Conta')?>

<div class="myaccount content-wrapper">

    <?php if (!isset($_SESSION['account_loggedin'])): ?>

    <h1>Login</h1>

    <form action="index.php?page=myaccount" method="post">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="john@example.com" required>
        <label for="password">Senha</label>
        <input type="password" name="password" id="password" placeholder="Senha" required>
        <input type="submit" value="Login">
    </form>

    <?php if ($error): ?>

    <p class="content-wrapper error"><?=$error?></p>

    <?php endif; ?>

    <?php else: ?>

    <h1>Minha Conta</h1>

    <h2>Minhas Encomendas</h2>

    <table>
        <thead>
            <tr>
                <td colspan="2">Produto</td>
                <td class="rhide">Data</td>
                <td class="rhide">Preço</td>
                <td>Quantidade</td>
                <td>Total</td>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
            <tr>
                <td colspan="6" style="text-align:center;">Você não tem pedidos recentes</td>
            </tr>
            <?php else: ?>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td class="img">
                    <img src="imgs/<?=$transaction['img']?>" width="50" height="50" alt="<?=$transaction['name']?>">
                </td>
                <td><?=$transaction['name']?></td>
                <td class="rhide"><?=$transaction['transaction_date']?></td>
                <td class="price rhide"><?=currency_code?><?=number_format($transaction['price'],2)?></td>
                <td class="quantity"><?=$transaction['quantity']?></td>
                <td class="price"><?=currency_code?><?=number_format($transaction['price'] * $transaction['quantity'],2)?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php endif; ?>

</div>
<br><br><br><br><br><br><br><br><br>

<?=template_footer()?>
