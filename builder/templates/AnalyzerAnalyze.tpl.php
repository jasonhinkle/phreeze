<?php include_once '_header.tpl.php'; ?>

<h2><i class="icon-check"></i> Select Tables</h2>

<p>Select the tables and views to include in this application.  The Singular and Plural names
are automatically detected and will be used in the names of generated classes.  You may
adjust them here.  If you prefix every column in a table consistently (ex a_id, a_name)
the Column Prefix will be removed for class properties.</p>

<p>Note that tables with no primary key or a
composite primary key are not supported.  Views are supported but depending on the contents
of the view, update operations may not work.  Views are de-selected by default.</p>

<form id="generateForm" action="generate" method="post" class="form-horizontal">

	<table class="collection table table-bordered table-striped">
	<thead>
		<tr>
			<th class="checkboxColumn"><input type="checkbox" id="selectAll" checked="checked"
				onclick="$('input.tableCheckbox').attr('checked', $('#selectAll').attr('checked')=='checked');"/></th>
			<th>Table</th>
			<th>Singular Name</th>
			<th>Plural Name</th>
			<th>Config</th>
			<th>Column Prefix</th>
		</tr>
	</thead>
	<tbody>
	<?php 
	
	/* these are reserved words that will conflict with phreeze */
	function is_reserved_table_name($name)
	{
		$reserved = array('criteria','phreezer','phreezable','reporter','controller','dataset');
		return in_array(strtolower($name), $reserved);
	} 

	/* these are property names that cannot be used due to conflicting with the client-side libraries */
	function is_reserved_column_name($name)
	{
		$reserved = array('url','urlroot','idattribute','attributes','isnew','changedattributes','previous','previousattributes','defaults');
		return in_array(strtolower($name), $reserved);
	} 
	?>
	
	<?php foreach ($this->dbSchema->Tables as $table) { 
	
		$invalidColumns = array();
		foreach ($table->Columns as $column) {
			if (is_reserved_column_name($column->NameWithoutPrefix) )
			{
				$invalidColumns[] = $column->Name;
			}
		} 
	?>
		<tr id="">
			<td class="checkboxColumn">
			<?php if (count($invalidColumns)>0) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="This table contains one or more column names that conflict with the client-side libraries.  To include this table, please rename the following column(s):<br/><br/><ul><li><?php $this->eprint( implode("</li><li>", $invalidColumns) ); ?></li></ul>"
					data-original-title="Reserved Word"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" />
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Phreeze does not currently support tables without a primary key column"
					data-original-title="No Primary Key"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Phreeze does not currently support tables with multiple/compound key columns"
					data-original-title="Compound Primary Key"><i class="icon-ban-circle">&nbsp;</i></a>
			<?php } else { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" checked="checked" />
			<?php } ?>
			</td>
			<td class="tableNameColumn">
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<a href="#" class="popover-icon error" rel="popover" onclick="return false;"
					data-content="This table name is a reserve word in the Phreeze framework.<br/><br/>'Model' has been appended to the end of your class name.  You can change this to something else as long as you do not use the reserved Phreeze classname as-is."
					data-original-title="Reserved Word"><i class="icon-info-sign">&nbsp;</i></a>
			<?php } elseif ($table->IsView) { ?>
				<a href="#" class="popover-icon view" rel="popover" onclick="return false;"
					data-content="Views are supported by Phreeze however only read-operations will be allowed by default.<br/><br/>Because views do not support keys or indexes, Phreeze will treat the leftmost column of the view as the primary key.  For optimal results please design your view so that the leftmost column returns a unique value for each row."
					data-original-title="View Information"><i class="icon-table">&nbsp;</i></a>
			<?php }else{ ?>
				<i class="icon-table">&nbsp;</i>
			<?php } ?>
			<?php $this->eprint($table->Name); ?></td>
			
			<?php if (is_reserved_table_name($table->Name)) { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Model" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>Models" /></td>
			<?php } else { ?>
				<td><input class="objname objname-singular" type="text" id="<?php $this->eprint($table->Name); ?>_singular" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>" /></td>
				<td><input class="objname objname-plural" type="text" id="<?php $this->eprint($table->Name); ?>_plural" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($this->plural($table->Name))); ?>" /></td>
				<td><textarea class="objname objname-config" id="<?php $this->eprint($table->Name); ?>_config" name="<?php $this->eprint($table->Name); ?>_config">
{
    "replaceVars":{
        "controllerName":""
    },
    "masterTable":"",
    "childField":""
}
                </textarea></td>
			<?php } ?>
			<td><input type="text" class="colprefix span2" id="<?php $this->eprint($table->Name); ?>_prefix" name="<?php $this->eprint($table->Name); ?>_prefix" value="<?php $this->eprint($table->ColumnPrefix); ?>" size="15" /></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

	<h2><i class="icon-cogs"></i> Application Options</h2>

	<p>These options do not need to be changed.  Most of them simply pre-fill a setting in one of the
	configuration files so that you don't have to manually edit them in order to run the application.
	Any of the options below can be changed or re-configured after the code is generated.</p>

	<fieldset class="well">

		<div id="packageContainer" class="control-group">
			<label class="control-label" for="package">Package To Generate <i class="popover-icon icon-question-sign" 
					data-title="Package To Generate" 
					data-content="You may choose from various packages to generate.  Most likely you will be interested in choosing the Phreeze App that uses your preferred template engine (RenderEngine) for the view layer.<br/><br/>The RenderEngine can be changed in <code>_app_config.php</code>, however changing the RenderEngine also requires re-generating the templates."></i></label>
			<div class="controls inline-inputs">
				<select name="package" class="input-xxlarge">
				<?php foreach ($this->packages as $package) { ?>
					<option value="<?php $this->eprint($package->GetConfigFile()) ?>"><?php $this->eprint($package->GetName()) ?></option>
				<?php } ?>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="appNameContainer" class="control-group">
			<label class="control-label" for=""appname"">Application Name <i class="popover-icon icon-question-sign" 
					data-title="Application Name" 
					data-content="The name of the application will appear in the top nav/header as well as the footer of the app.  You can change this later in the templates folder."></i></label>
			<div class="controls inline-inputs">
				<input type="text" name="appname" id="appname" value="<?php $this->eprint(strtoupper($this->appname)); ?>" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="appRootContainer" class="control-group">
			<label class="control-label" for="appRoot">Application Root URL <i class="popover-icon icon-question-sign" 
					data-title="Application Root URL" 
					data-content="Your Phreeze application must know it's root location in order to support clean URLs.  You will need to ensure this is the correct URL for your app.  When deploying your app to another server, this value will need to be adjusted.<br/><br/>The GlobalConfig::$ROOT_URL setting is found in <code>_machine_config.php</code>"></i></label>
			<div class="controls inline-inputs">
				<span>http://servername/</span>
				<input type="text" class="span2" name="appRoot" id="appRoot" value="<?php $this->eprint(strtolower($this->appname)); ?>/" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="includePathContainer" class="control-group">
			<label class="control-label" for="includePath">Path to /phreeze/libs <i class="popover-icon icon-question-sign" 
					data-title="Path to Phreeze Libs" 
					data-content="Unless your app is self-contained (see next option) then it must be able to locate the Phreeze framework class files in <code>/phreeze/libs/</code>.  The app will check the PHP include path, however you can specify an additional relative file path here.<br/><br/>This setting can be adjusted in <code>_app_config.php</code>"></i></label>
			<div class="controls inline-inputs">
				<input type="text" name="includePath" id="includePath" value="../phreeze/libs" />
				<span class="help-inline"></span>
			</div>
		</div>

		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="includePhar">Make Self-Contained <i class="popover-icon icon-question-sign" 
					data-title="Make Self-Contained" 
					data-content="Selecting 'Yes' will include the Phreeze Framework as a pre-built .phar file located in /libs/.  This will allow your application to stand-alone without the need for the Phreeze libraries on the server.<br/><br/>This is recommended when distributing pre-packaged apps and will make them easier to install.  It is not recommended during development."></i></label>
			<div class="controls inline-inputs">
				<select name="includePhar" id="includePhar"  class="input-xxlarge">
					<option value="0">No (Require External Phreeze Libraries)</option>
					<option value="1">Yes (Include Phreeze Libraries as Phar)</option>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>
		
		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="enableLongPolling">Long Polling <i class="popover-icon icon-question-sign" 
					data-title="Long Polling" 
					data-content="With long polling enabled, the table views will 'poll' the database every few seconds for changes and update the page automatically if necessary.  This will make your site appear to be a real-time collaberative app.  Use with caution as this will also place additional load on the server.<br/><br/>This setting can be adjusted in <code>/scripts/model.js</code>"></i></label>
			<div class="controls inline-inputs">
				<select name="enableLongPolling" id="enableLongPolling">
					<option value="0">Disabled</option>
					<option value="1">Enabled</option>
				</select>
				<span class="help-inline"></span>
			</div>
		</div>
		
	</fieldset>
	
	<div id="errorContainer"></div>

	<p>
		<input type="hidden" name="host" id="host" value="<?php $this->eprint($this->host) ?>" />
		<input type="hidden" name="port" id="port" value="<?php $this->eprint($this->port) ?>" />
		<input type="hidden" name="schema" id="schema" value="<?php $this->eprint($this->schema) ?>" />
		<input type="hidden" name="username" id="username" value="<?php $this->eprint($this->username) ?>" />
		<input type="hidden" name="password" id="password" value="<?php $this->eprint($this->password) ?>" />

		<button class="btn btn-inverse"><i class="icon-play"></i> Generate Application</button>
	</p>
</form>
<!DOCTYPE HTML>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="titu00" />

	<title>Untitled 2</title>
</head>


<script type="text/javascript" src="scripts/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="scripts/analyze.js"></script>

<?php include_once '_footer.tpl.php'; ?>