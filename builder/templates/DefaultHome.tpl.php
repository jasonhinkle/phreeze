<?php include_once '_header.tpl.php'; ?>

<div class="hero-unit">
	<h1><img src="images/banner.png" alt="Phreeze Builder" /></h1>
	<div class="subheader">
		<div>Enter your MySQL connection information in the form below.</div>
		<div>Phreeze will analyze your schema and generate an awesome application.</div>
	</div>
</div>

<form action="analyze" method="post" class="form-horizontal">
	<fieldset class="well">
		<div id="hostPortContainer" class="control-group">
			<label class="control-label" for="host">MySQL Host : Port</label>
			<div class="controls inline-inputs">
				<input type="text" class="span2" id="host" name="host"  placeholder="example: localhost" /> :
				<input type="text" class="span1" id="port" name="port" value="3306" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="schemaContainer" class="control-group">
			<label class="control-label" for="schema">Schema Name</label>
			<div class="controls inline-inputs">
				<input type="text" class="span3" id="schema" name="schema" placeholder="example: mydatabase" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="usernameContainer" class="control-group">
			<label class="control-label" for="username">MySQL Username</label>
			<div class="controls inline-inputs">
				<input type="text" class="span3" id="username" name="username" placeholder="" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="passwordContainer" class="control-group">
			<label class="control-label" for="password">MySQL Password</label>
			<div class="controls inline-inputs">
				<input type="password" class="span3" id="password" name="password" placeholder="" />
				<span class="help-inline"></span>
			</div>
		</div>
	</fieldset>

	<p>
		<input type="submit" class="btn btn-inverse" value="Analyze Database &raquo;" />
	</p>
</form>
<div class="hero-unit">
<form action="generate" method="post">
<textarea cols="150" rows="30" name="jsonfile"></textarea>
<br />
<input type="submit" class="btn btn-inverse" value="Generate Project" >
</form>
</div >
<?php include_once '_footer.tpl.php'; ?>