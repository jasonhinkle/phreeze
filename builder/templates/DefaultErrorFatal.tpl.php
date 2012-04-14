<?php include_once '_header.tpl.php'; ?>

<h1><i class="icon-cogs"></i> Oh Snap!</h1>

<h2 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;"><?php echo $this->eprint($this->message); ?></h2>

<p>You may want to try returning to the the previous page and verifying that
all fields have been filled out correctly.</p>

<p>If you continue to experience this error please contact support.</p>

<div id="stacktrace" class="well hide">
	<h5>Stack Trace:</h5>
	<?php if ($this->stacktrace) { ?>
		<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px;">
			<pre><?php echo $this->eprint($this->stacktrace); ?></pre>
		</p>
	<?php } ?>
</div>

<?php include_once '_footer.tpl.php'; ?>