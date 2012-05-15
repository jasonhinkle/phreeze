<?php
	$this->assign('title','{$appname} | File Not Found');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

<div class="container">

	<h1>Oh Snap!</h1>

	<!-- this is used by app.js for scraping -->
	<!-- ERROR The page you requested was not found /ERROR -->

	<p>The page you requested was not found.  Please check that you typed the URL correctly.</p>

<!-- footer -->
	<hr>

	<footer>
		<p>&copy; <?php echo date('Y'); ?> {$appname|escape}</p>
	</footer>

</div> <!-- /container -->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<?php
	$this->display('_Footer.tpl.php');
?>