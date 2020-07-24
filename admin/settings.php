<?php
defined('admin') or exit;
// Configuration file
$file = '../config.php';
// Open the configuration file for reading
$handle = fopen($file, 'r') or exit('Unable to read configuration file, make sure the file is readable!');
$contents = fread($handle, filesize($file));
fclose($handle);
// Get all the defined variable keys and values
preg_match_all('/define\(\'(.*?)\', \'(.*?)\'/', $contents, $matches);
if (!empty($_POST)) {
    // Update the configuration file with the new keys and values
    foreach ($_POST as $k => $v) {
        $contents = preg_replace('/define\(\'' . $k . '\'\, \'(.*?)\'/s', 'define(\'' . $k . '\', \'' . $v . '\'', $contents);
    }
    file_put_contents('../config.php', $contents);
    header('Location: index.php?page=settings');
    exit;
}

?>

<?=template_admin_header('Settings')?>

<h2>Settings</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <?php for($i = 0; $i < count($matches[1]); $i++): ?>
        <label><?=str_replace('_', ' ', $matches[1][$i])?></label>
        <input type="text" name="<?=$matches[1][$i]?>" value="<?=htmlspecialchars($matches[2][$i], ENT_QUOTES)?>" placeholder="<?=str_replace('_', ' ', $matches[1][$i])?>">
        <?php endfor; ?>
        <input type="submit" value="Salvar">
    </form>
</div>

<?=template_admin_footer()?>
