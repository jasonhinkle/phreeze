<?php include_once '_header.tpl.php'; ?>

<h2 onclick="$('#stacktrace').show('slow');" style="cursor: pointer;"><?php echo $this->eprint($this->message); ?></h2>

<p>Please return the the previous page and verify that all required fields
have been completed.  If you continue to experience this error please
contact support.  We're sorry for the inconvenience.</p>

<div id="stacktrace" style="display: none; text-align: left; background-color: #eeeeee; border: solid 1px #cccccc; padding: 10px; font-family: courier new, courier; font-size: 8pt;">
	<p style="font-weight: bold;">Stack Trace:</p>
	<?php if ($this->stacktrace) { ?>
		<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px;">
		<pre><?php echo $this->eprint($this->stacktrace); ?></pre>
		</p>
	<?php } ?>

</div>

<?php include_once '_footer.tpl.php'; ?>