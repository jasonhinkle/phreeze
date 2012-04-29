<?php
	$this->assign('title','{$appname}');
	$this->assign('nav','home');

	$this->display('_Header.tpl.php');
?>

<h1>Oh Snap!</h1>

<div class="container">

	<!-- this is used by app.js for scraping -->
	<!-- ERROR <?php $this->eprint($this->message); ?> /ERROR -->

	<h3 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;"><?php $this->eprint($this->message); ?></h3>

	<p>You may want to try returning to the the previous page and verifying that
	all fields have been filled out correctly.</p>

	<p>If you continue to experience this error please contact support.</p>

	<div id="stacktrace" class="well hide">
		<p style="font-weight: bold;">Stack Trace:</p>
		<?php if ($this->stacktrace) { ?>
			<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px; font-family: courier new, courier; font-size: 8pt;"><pre><?php $this->eprint($this->stacktrace); ?></pre></p>
		<?php } ?>
	</div>

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