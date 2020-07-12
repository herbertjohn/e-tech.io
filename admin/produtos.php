<?php
include 'functions.php';
// Conecte-se ao MySQL
$pdo = pdo_connect_mysql();
// Consulta MySQL que seleciona todas as imagens
$stmt = $pdo->query('SELECT * FROM images ORDER BY uploaded_date DESC');
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>E-Tech</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body class="is-preload">
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner">
			 
			<?php include("assets/header.php"); ?>			
							<!-- Banner -->
								<section id="">
									<div class="content">
									<br><br>


<div class="content home">
	
	<div class="images">
		<?php foreach ($images as $image): ?>
		<?php if (file_exists($image['path'])): ?>
		<a href="#">
			<img src="<?=$image['path']?>" alt="<?=$image['description']?>" data-id="<?=$image['id']?>" data-title="<?=$image['title']?>" width="300" height="200">
			<span><?=$image['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>
<div class="image-popup"></div>



<script>
// Container que usaremos para mostrar uma imagem
let image_popup = document.querySelector('.image-popup');
// Faça um loop em cada imagem para adicionar o evento ao clicar
document.querySelectorAll('.images a').forEach(img_link => {
	img_link.onclick = e => {
		e.preventDefault();
		let img_meta = img_link.querySelector('img');
		let img = new Image();
		img.onload = () => {
			// Crie a imagem pop-out
			image_popup.innerHTML = `
				<div class="con">
					<h3>${img_meta.dataset.title}</h3>
					<p>${img_meta.alt}</p>
					<img src="${img.src}" width="${img.width}" height="${img.height}">
					
				</div>
			`;
			image_popup.style.display = 'flex';
		};
		img.src = img_meta.src;
	};
});
// Ocultar o container pop-up da imagem se o usuário clicar fora da imagem
image_popup.onclick = e => {
	if (e.target.className == 'image-popup') {
		image_popup.style.display = "none";
	}
};
</script>										
<?=template_footer()?>


									</div>
								</section>
						</div>
					</div>
								<?php include("assets/menu2.php"); ?>

			</div>
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>
	</body>
</html>
