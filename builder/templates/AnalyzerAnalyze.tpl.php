<?php include_once '_header.tpl.php'; ?>

<h2><i class="icon-check"></i> Select Tables</h2>

<p>Select the tables and views to include in this application.  The Singular and Plural names
are automatically detected and will be used in the names of generated classes.  You may
adjust them here.  If you prefix every column in a table consistently (ex a_id, a_name)
the Column Prefix will be removed for class properties.</p>

<p>Note that tables with no primary key or a
composite primary key are not supported.  Views are supported but depending on the contents
of the view, update operations may not work.  Views are de-selected by default.</p>

<form action="generate" method="post" class="form-horizontal">

	<table class="collection table table-bordered">
	<thead>
		<tr>
			<th class="checkboxColumn"><input type="checkbox" id="selectAll"
				onclick="$('input.tableCheckbox').attr('checked', $('#selectAll').attr('checked')=='checked');"/></th>
			<th>Table</th>
			<th>Singular Name</th>
			<th>Plural Name</th>
			<th>Column Prefix</th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($this->dbSchema->Tables as $table) { ?>
		<tr id="">
			<td class="checkboxColumn">
			<?php if ($table->IsView) { ?>
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
			
			<?php if ($table->IsView) { ?>
				<a href="#" class="popover-icon view" rel="popover" onclick="return false;"
					data-content="Views are supported by Phreeze however only read operations will be allowed"
					data-original-title="View Information"><i class="icon-table">&nbsp;</i></a>
			<?php }else{ ?>
				<i class="icon-table">&nbsp;</i>
			<?php } ?>
			<?php $this->eprint($table->Name); ?></td>
			<td><input type="text" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($this->studlycaps($table->Name)); ?>" /></td>
			<td><input type="text" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($this->studlycaps($this->plural($table->Name))); ?>" /></td>
			<td><input type="text" class="span2" name="<?php $this->eprint($table->Name); ?>_prefix" value="<?php $this->eprint($table->ColumnPrefix); ?>" size="15" /></td>
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
			<label class="control-label" for="package">Package To Generate</label>
			<div class="controls inline-inputs">
				<select name="package">
				<?php foreach ($this->packages as $package) { ?>
					<option value="<?php $this->eprint($package->GetConfigFile()) ?>"><?php $this->eprint($package->GetName()) ?></option>
				<?php } ?>
				</select>
				<span class="help-inline">This specified which application package to generate</span>
			</div>
		</div>

		<div id="appNameContainer" class="control-group">
			<label class="control-label" for=""appname"">Application Name</label>
			<div class="controls inline-inputs">
				<input type="text" name="appname" id="appname" value="<?php $this->eprint(strtoupper($this->appname)); ?>" />
				<span class="help-inline">This can be adjusted in /templates/Master.tpl</span>
			</div>
		</div>

		<div id="appRootContainer" class="control-group">
			<label class="control-label" for="appRoot">Application Root URL</label>
			<div class="controls inline-inputs">
				<span>http://servername/</span>
				<input type="text" class="span2" name="appRoot" id="appRoot" value="<?php $this->eprint(strtolower($this->appname)); ?>/" />
				<span class="help-inline">This can be adjusted in /_machine_config.php</span>
			</div>
		</div>

		<div id="includePathContainer" class="control-group">
			<label class="control-label" for="includePath">Path to phreeze/libs</label>
			<div class="controls inline-inputs">
				<input type="text" name="includePath" id="includePath" value="../phreeze/libs" />
				<span class="help-inline">This can be adjusted in /_app_config.php</span>
			</div>
		</div>

		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="enableLongPolling">Long Polling</label>
			<div class="controls inline-inputs">
				<select name="enableLongPolling" id="enableLongPolling">
					<option value="0">Disabled</option>
					<option value="1">Enabled</option>
				</select>
				<span class="help-inline">This can be adjusted in /scripts/model.js</span>
			</div>
		</div>
	</fieldset>

	<p>
		<input type="hidden" name="host" id="host" value="<?php $this->eprint($this->host) ?>" />
		<input type="hidden" name="port" id="port" value="<?php $this->eprint($this->port) ?>" />
		<input type="hidden" name="schema" id="schema" value="<?php $this->eprint($this->schema) ?>" />
		<input type="hidden" name="username" id="username" value="<?php $this->eprint($this->username) ?>" />
		<input type="hidden" name="password" id="password" value="<?php $this->eprint($this->password) ?>" />

		<button class="btn btn-primary"><i class="icon-play-circle"></i> Generate Application</button>
	</p>
</form>

<?php include_once '_footer.tpl.php'; ?>