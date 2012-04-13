{ldelim}extends file="Master.tpl"{rdelim}

{ldelim}block name=title{rdelim}{$appname} | {$table->Name|plural|underscore2space}{ldelim}/block{rdelim}

{ldelim}block name=banner{rdelim}
	<h1>
		<i class="icon-th-list"></i> {$table->Name|plural|underscore2space}
		<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	</h1>
{ldelim}/block{rdelim}

{ldelim}block name=navbar prepend{rdelim}
	{ldelim}assign var="nav" value="{$plural|lower}"{rdelim}
{ldelim}/block{rdelim}

{ldelim}block name=content{rdelim}

	<!-- underscore template for the collection -->
	<script type="text/template" id="{$singular|lcfirst}CollectionTemplate">
		<table class="collection table table-bordered">
		<thead>
			<tr>
{foreach from=$table->Columns item=column name=columnsForEach}
{if $smarty.foreach.columnsForEach.index == 5}{ldelim}* uncomment to show additional colums *{rdelim}
{ldelim}*
{/if}
				<th>{$column->NameWithoutPrefix|underscore2space}</th>
{/foreach}
{if $smarty.foreach.columnsForEach.index >= 5}*{rdelim}
{/if}
			</tr>
		</thead>
		<tbody>
		<% items.each(function(item) {ldelim} %>
			<tr id="<%= _.escape(item.get('{$table->GetPrimaryKeyName()|studlycaps|lcfirst}')) %>">
{foreach from=$table->Columns item=column name=columnsForEach}
{if $smarty.foreach.columnsForEach.index == 5}{ldelim}* uncomment to show additional colums *{rdelim}
{ldelim}*
{/if}
{if $column->Type == "date"}
				<td><%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('MMM D, YYYY') %></td>
{elseif $column->Type == "datetime"}
				<td><%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('MMM D, YYYY h:mm A') %></td>
{else}
				<td><%= _.escape(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}') || '') %></td>
{/if}
{/foreach}
{if $smarty.foreach.columnsForEach.index >= 5}*{rdelim}
{/if}
			</tr>
		<% {rdelim}); %>
		</tbody>
		</table>

		<%=  view.getPaginationHtml(page) %>
	</script>

	<!-- underscore template for the model -->
	<script type="text/template" id="{$singular|lcfirst}ModelTemplate">
		<form class="form-horizontal" onsubmit="return false;">
			<fieldset>
{foreach from=$table->Columns item=column name=columnsForEach}
				<div id="{$column->NameWithoutPrefix|studlycaps|lcfirst}InputContainer" class="control-group">
					<label class="control-label" for="{$column->NameWithoutPrefix|studlycaps|lcfirst}">{$column->NameWithoutPrefix|underscore2space}</label>
					<div class="controls inline-inputs">
{if $column->Extra == 'auto_increment'}
						<span class="input-xlarge uneditable-input" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}"><%= _.escape(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}') || '') %></span>
{elseif $column->Key == "MUL" && $column->Constraints}
						<select id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" name="{$column->NameWithoutPrefix|studlycaps|lcfirst}"></select>
{elseif $column->Type == "datetime"}
						<input type="text" class="date-picker input-xlarge" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('YYYY-MM-DD') %>">
						<input type="text" class="time-picker input-xlarge" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}-time" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('h:mm A') %>">
{elseif $column->Type == "date"}
						<input type="text" class="date-picker input-xlarge" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('YYYY-MM-DD') %>">
{elseif $column->Type == 'text' || $column->Type == 'tinytext' || $column->Type == 'mediumtext' || $column->Type == 'longtext'}
						<textarea class="input-xlarge" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" rows="3"><%= _.escape(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}') || '') %></textarea>
{elseif false}
						<select id="{$column->NameWithoutPrefix|studlycaps|lcfirst}"><option>something</option><option>2</option></select>
{else}
						<input type="text" class="input-xlarge" id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" placeholder="{$column->NameWithoutPrefix|underscore2space}" value="<%= _.escape(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}') || '') %>">
{/if}
						<span class="help-inline"></span>
					</div>
				</div>
{/foreach}
			</fieldset>
		</form>

		<!-- delete button is is a separate form to prevent enter key from triggering a delete -->
		<form id="delete{$singular}ButtonContainer" class="form-horizontal" onsubmit="return false;">
			<fieldset>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<button id="delete{$singular}Button" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete {$table->Name|underscore2space}</button>
						<span id="confirmDelete{$singular}Container" class="hide">
							<button id="cancelDelete{$singular}Button" class="btn btn-mini">Cancel</button>
							<button id="confirmDelete{$singular}Button" class="btn btn-mini btn-danger">Confirm</button>
						</span>
					</div>
				</div>
			</fieldset>
		</form>
	</script>

	<!-- modal edit dialog -->
	<div class="modal hide fade" id="{$singular|lcfirst}DetailDialog">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">&times;</a>
			<h3>
				<i class="icon-edit"></i> Edit {$table->Name|underscore2space}
				<span id="modelLoader" class="loader progress progress-striped active"><span class="bar"></span></span>
			</h3>
		</div>
		<div class="modal-body">
			<div id="modelAlert"></div>
			<div id="{$singular|lcfirst}ModelContainer"></div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" >Cancel</button>
			<button id="save{$singular}Button" class="btn btn-primary">Save Changes</button>
		</div>
	</div>

	<div id="collectionAlert"></div>

	<div id="{$singular|lcfirst}CollectionContainer" class="collectionContainer">
	</div>

	<p id="newButtonContainer" class="buttonContainer">
		<button id="new{$singular}Button" class="btn btn-primary">Add {$table->Name|underscore2space}</button>
	</p>

{ldelim}/block{rdelim}

{ldelim}block name=customFooterScripts{rdelim}
	<script type="text/javascript" src="scripts/model.js"></script>
	<script type="text/javascript" src="scripts/app.js"></script>
	<script type="text/javascript" src="scripts/view.js"></script>
	<script type="text/javascript" src="scripts/{$plural|lower}.js"></script>
{ldelim}/block{rdelim}
