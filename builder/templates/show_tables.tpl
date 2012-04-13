{include file="_header.tpl" header_title="Generate Application"}

<h1><span class="iconlink">Generate Application</span></h1>

<form action="generate_class.php" method="post">

<h2>1. Select Tables:</h2>

<div style="height: 400px; overflow: auto; border: solid 1px #666666;">
<table class="basic" style="width: 100%;">
<tr>
    <th><input type="checkbox" name="table_toggle" value="0" onclick="checkAll(this.form, 'table_name[]',this.checked)" checked="checked" /></th>
    <th>Table</th>
    <th>Object (Singular)</th>
    <th>Object (Plural)</th>
    <th>Column Prefix</th>
</tr>

{foreach from=$schema->Tables item=table}
	<tr>
		<td>
		{if $table->NumberOfPrimaryKeyColumns() < 1}
			<span><a href="#" onclick="alert('Phreeze does not support tables without a primary key'); return false;"><img src="images/ico_error.png" alt="Phreeze does not support tables without a primary key" /></a></span>
		{elseif $table->NumberOfPrimaryKeyColumns() > 1}
			<span><a href="#" onclick="alert('Phreeze does not support tables with composite/multiple primary key columns'); return false;"><img src="images/ico_error.png" alt="Phreeze does not support tables with composite/multiple primary key columns" /></a></span>
		{else}
			<input type="checkbox" name="table_name[]" value="{$table->Name}" {if !$table->IsView}checked="checked"{/if} />
		{/if}
		</td>
		<td>{if $table->IsView}VIEW: {/if}{$table->Name}</td>
		<td><input type="text" name="{$table->Name}_singular" value="{$table->Name|studlycaps}" /></td>
		<td><input type="text" name="{$table->Name}_plural" value="{$table->Name|plural|studlycaps}" /></td>
		<td><input type="text" name="{$table->Name}_prefix" value="{$table->ColumnPrefix}" size="15" /></td>
	</tr>
{/foreach}

</table>
</div>

<h2>2. Select Package to Generate:</h2>


<div style="height: 150px; overflow: auto; border: solid 1px #666666;">
<table class="basic" style="width: 100%;">
<tr>
    <th><input type="checkbox" name="table_toggle" value="0" onclick="checkAll(this.form, 'package_name[]',this.checked)" /></th>
    <th>Package</th>
    <th>Description</th>
</tr>
	{foreach from=$packages item=package}
		<tr>
			<td><input type="checkbox" name="package_name[]" value="{$package->GetConfigFile()}" /></td>
			<td>{$package->GetName()|escape}</td>
			<td>{$package->GetDescription()|escape}</td>
		</tr>
	{/foreach}

</table>
</div>

<h2>3. (Optional) Additional Parameters (one per line):</h2>

<p>
<!-- laplix 2007-11-02. using the $param var instead of harcoding the parameters
<textarea name="parameters" style="width: 400px; height: 75px;">PathToVerySimpleScripts=/scripts/verysimple/
PathToExtScripts=/scripts/ext/
</textarea>
-->
<textarea name="parameters" style="width: 100%; height: 75px;">{foreach from=$params item=param}
{$param->name}={$param->value}
{/foreach}
</textarea>
</p>

<h2>4. Send Output To:</h2>

<p>
<input type="radio" name="debug" value="" checked="checked" /> Zip Archive
<input type="radio" name="debug" value="1" /> Browser
</p>

<p><input type="submit" value="Generate Application" /></p>

</form>

{include file="_footer.tpl"}
