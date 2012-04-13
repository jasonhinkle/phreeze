<?php include_once '_header.tpl.php'; ?>

<h2>Select Tables</h2>

<p>Select the tables and views to include in this application.  The Singular and Plural names
are automatically detected and will be used in the names of generated classes.  You may
adjust them here.  If you prefix every column in a table consistently (ex a_id, a_name)
the Column Prefix will be removed for class properties.</p>

<p>Note that ables with no primary key or a
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
					data-original-title="No Primary Key"><img src="images/error.png" alt="X" /></a>
			<?php } elseif ($table->NumberOfPrimaryKeyColumns() < 1) { ?>
				<a href="#" class="popover-icon" rel="popover" onclick="return false;"
					data-content="Phreeze does not currently support tables with multiple/compound key columns"
					data-original-title="Compound Primary Key"><img src="images/error.png" alt="X" /></a>
			<?php } else { ?>
				<input type="checkbox" class="tableCheckbox" name="table_name[]" value="<?php $this->eprint($table->Name); ?>" checked="checked" />
			<?php } ?>
			</td>
			<td><span class="<?php if ($table->IsView) { ?>view<?php }else{ ?>table<?php } ?>"><?php $this->eprint($table->Name); ?></span></td>
			<td><input type="text" name="<?php $this->eprint($table->Name); ?>_singular" value="<?php $this->eprint($table->Name); ?>|sc" /></td>
			<td><input type="text" name="<?php $this->eprint($table->Name); ?>_plural" value="<?php $this->eprint($table->Name); ?>|sc|p" /></td>
			<td><input type="text" class="span2" name="<?php $this->eprint($table->Name); ?>_prefix" value="<?php $this->eprint($table->ColumnPrefix); ?>" size="15" /></td>
		</tr>
	<?php } ?>
	</tbody>
	</table>

	<h2>Application Options</h2>

	<p>Changing these options will affect the output of generated files.  All options can be changed
	after the code is generated, but some may require updating multiple files.  Each option will
	list the file(s) that are affected so you can locate and re-configure them later.</p>

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


		<div id="enableLongPollingContainer" class="control-group">
			<label class="control-label" for="enableLongPolling">Long Polling</label>
			<div class="controls inline-inputs">
				<select name="enableLongPolling" id="enableLongPolling">
					<option value="0">Disabled</option>
					<option value="1">Enabled</option>
				</select>
				<span class="help-inline">This setting can be adjusted in /scripts/model.js</span>
			</div>
		</div>
	</fieldset>

	<p>
		<input type="hidden" name="host" id="host" value="<?php $this->eprint($this->host) ?>" />
		<input type="hidden" name="port" id="port" value="<?php $this->eprint($this->port) ?>" />
		<input type="hidden" name="schema" id="schema" value="<?php $this->eprint($this->schema) ?>" />
		<input type="hidden" name="username" id="username" value="<?php $this->eprint($this->username) ?>" />
		<input type="hidden" name="password" id="password" value="<?php $this->eprint($this->password) ?>" />
		<input type="submit" class="btn btn-primary" value="Generate Application" />
	</p>
</form>

<?php include_once '_footer.tpl.php'; ?>