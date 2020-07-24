<?php
defined('admin') or exit;
// SQL query that will get all the accounts from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM accounts ORDER BY id DESC');
$stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_admin_header('E-Tech/Contas')?>

<h2>Contas</h2>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td class="responsive-hidden">#</td>
                    <td>Email</td>
                    <td>Nome</td>
                    <td>Endereço</td>
                    <td>Admin</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">Não há contas</td>
                </tr>
                <?php else: ?>
                <?php foreach ($accounts as $account): ?>
                <tr>
                    <td class="responsive-hidden"><?=$account['id']?></td>
                    <td><?=$account['email']?></td>
                    <td><?=$account['first_name']?> <?=$account['last_name']?></td>
                    <td>
                        <?=$account['address_street']?><br>
                        <?=$account['address_city']?><br>
                        <?=$account['address_state']?><br>
                        <?=$account['address_zip']?><br>
                        <?=$account['address_country']?><br>
                    </td>
                    <td><?=$account['admin']==1?'true':'false'?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
