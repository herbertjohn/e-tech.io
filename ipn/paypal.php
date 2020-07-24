<?php
include '../config.php';
include '../functions.php';
// Get all input variables and convert them all to URL string variables
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2) $myPost[$keyval[0]] = urldecode($keyval[1]);
}
$req = 'cmd=_notify-validate';
$get_magic_quotes_exists = function_exists('get_magic_quotes_gpc') ? true : false;
foreach ($myPost as $key => $value) {
    if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
        $value = urlencode(stripslashes($value));
    } else {
        $value = urlencode($value);
    }
    $req .= "&$key=$value";
}
// Below will verify the transaction, it will make sure the input data is correct, we wouldn't want anybody trying to cheat the system..
$ch = curl_init(strtolower(paypal_testmode) == 'true' ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);
curl_close($ch);
if (strcmp($res, 'VERIFIED') == 0) {
    // Transaction is verified and successful...
    $pdo = pdo_connect_mysql();
    // Insert the transaction into our transactions table, as the payment status changes the query will execute again and update it, make sure the "txn_id" column is unique
    $stmt = $pdo->prepare('INSERT INTO transactions (txn_id, payment_amount, payment_status, created, payer_email, first_name, last_name, address_street, address_city, address_state, address_zip, address_country, account_id, payment_method) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ON DUPLICATE KEY UPDATE payment_status = VALUES(payment_status)');
    $stmt->execute([
        $_POST['txn_id'],
        $_POST['mc_gross'],
        $_POST['payment_status'],
        date('Y-m-d H:i:s'),
        $_POST['payer_email'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['address_street'],
        $_POST['address_city'],
        $_POST['address_state'],
        $_POST['address_zip'],
        $_POST['address_country'],
        $_POST['custom'],
        'paypal'
    ]);
    // Iterate the cart items and insert into the MySQL database
    for ($i = 1; $i < ($_POST['num_cart_items']+1); $i++) {
        // Update product quantity in the products table
        $stmt = $pdo->prepare('UPDATE products SET quantity = quantity - ? WHERE id = ?');
        $stmt->execute([ $_POST['quantity' . $i], $_POST['item_number' . $i] ]);
        $stmt = $pdo->prepare('INSERT INTO transactions_items (txn_id, item_id, item_price, item_quantity, item_options) VALUES (?,?,?,?,?)');
        $options = preg_match('/\((.*?)\)/', $_POST['item_name' . $i], $match);
        $options = isset($match[1]) ? $match[1] : '';
        $stmt->execute([ $_POST['txn_id'], $_POST['item_number' . $i], $_POST['mc_gross_' . $i], $_POST['quantity' . $i], $options ]);
    }
}
?>
