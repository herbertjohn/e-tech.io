<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';
// Verifique se o ID da pesquisa existe
if (isset($_GET['id'])) {
    // Selecione o registro que será excluído
    $stmt = $pdo->prepare('SELECT * FROM images WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$image) {
        die ('A imagem não existe com esse ID!');
    }
    // Verifique se o usuário confirma antes da exclusão
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // O usuário clicou no botão "Sim", excluiu o arquivo e excluiu o registro
            unlink($image['path']);
            $stmt = $pdo->prepare('DELETE FROM images WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            // Msg de saída
            $msg = 'Você excluiu a imagem!<a href="gallery.php">Galeria</a>';
        } else {
            // O usuário clicou no botão "Não", redirecione-o de volta para a página inicial / índice
            header('Location: index.php');
            exit;
        }
    }
} else {
    die ('Nenhum ID especificado!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete Image #<?=$image['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
    <p>Tem certeza de que deseja excluir<?=$image['title']?>?</p>
    <div class="yesno">
        <a href="delete.php?id=<?=$image['id']?>&confirm=yes">Sim</a>
        <a href="delete.php?id=<?=$image['id']?>&confirm=no">Não</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>