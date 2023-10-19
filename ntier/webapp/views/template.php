<!DOCTYPE html>
<html lang="en">
<head><?php include VIEWS.DS.'_common'.DS.'html.head.php';?></head>
<body>
	<header><?php include VIEWS.DS.'_common'.DS.'html.body.navbar.php';?></header>

	<main>
		<div class="main">
			<?php include VIEWS.DS.$contentfile;?>
		</div>


	</main>

	<?php include VIEWS.DS.'_common'.DS.'html.body.footer.php';?>

</body>
</html>