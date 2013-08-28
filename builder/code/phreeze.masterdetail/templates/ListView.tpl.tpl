<?php
	$this->assign('title','{$appname} | {$plural}');
	$this->assign('nav','{$plural|lower}');

	$this->display('_Header.tpl.php');
?>

<script type="text/javascript">
	$LAB.script("bootstrap/js/bootstrap-datepicker.js")
	.script("bootstrap/js/bootstrap-timepicker.js")
	.script("bootstrap/js/bootstrap-combobox.js")
	.script("scripts/libs/underscore-min.js").wait()
	.script("scripts/libs/underscore.date.min.js")
	.script("scripts/libs/backbone-min.js")
	.script("scripts/app.js")
	.script("scripts/model.js").wait()
	.script("scripts/view.js").wait()
	.script("scripts/app/{$plural|lower}.js").wait(function(){
	     page.{$plural|lcfirst}.url =  page.{$plural|lcfirst}.url + '?{$configJSON["childField"]}_Equals=<?php echo $this->id ?>';
		$(document).ready(function(){
		    
			page.init();
		});
		
		// hack for IE9 which may respond inconsistently with document.ready
		setTimeout(function(){
			if (!page.isInitialized) page.init();
		},1000);
	});
</script>

<div class="container">
<h1>
	<i class="icon-th-list"></i> {$configJSON["masterTable"]}
	
</h1>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
            <?php
            foreach($this->masterData[0] as $key => $val)
            {
                $this->th .= "<th >".$key."</th>";
                $this->td .= "<td >".$val."</td>";    
            }
			
            echo $this->th;
            ?>
		</tr>
	
		
		</thead>
		<tbody>

			<tr >

			<?php echo $this->td ?>

		</tbody>
		</table>



<h3>
	<i class="icon-th-list"></i> {$plural}
	<span id=loader class="loader progress progress-striped active"><span class="bar"></span></span>
	<span class='input-append pull-right searchContainer'>
		<input id='filter' type="text" placeholder="Search..." />
		<button class='btn add-on'><i class="icon-search"></i></button>
	</span>
</h1>

	<!-- underscore template for the collection -->
	<script type="text/template" id="{$singular|lcfirst}CollectionTemplate">
		<table class="collection table table-bordered table-hover">
		<thead>
			<tr>
{foreach from=$table->Columns item=column name=columnsForEach}
{if $smarty.foreach.columnsForEach.index == 5}<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
{/if}
				<th id="header_{$column->NameWithoutPrefix|studlycaps}">{$column->NameWithoutPrefix|underscore2space}<% if (page.orderBy == '{$column->NameWithoutPrefix|studlycaps}') { %> <i class='icon-arrow-<%= page.orderDesc ? 'up' : 'down' %>' /><% } %></th>
{/foreach}
{if $smarty.foreach.columnsForEach.index >= 5}-->
{/if}
			</tr>
	
		
		</thead>
		<tbody>
		<% items.each(function(item) {ldelim} %>
			<tr id="<%= _.escape(item.get('{$table->GetPrimaryKeyName()|studlycaps|lcfirst}')) %>">
{foreach from=$table->Columns item=column name=columnsForEach}
{if $smarty.foreach.columnsForEach.index == 5}<!-- UNCOMMENT TO SHOW ADDITIONAL COLUMNS
{/if}
{if $column->Type == "date"}
				<td><%if (item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}')) { %><%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('MMM D, YYYY') %><% } else { %>NULL<% } %></td>
{elseif $column->Type == "datetime" ||  $column->Type == "timestamp"}
				<td><%if (item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}')) { %><%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('MMM D, YYYY h:mm A') %><% } else { %>NULL<% } %></td>
{elseif $column->Type == "time"}
				<td><%if (item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}')) { %><%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('h:mm A') %><% } else { %>NULL<% } %></td>
{else}
				<td><%= _.escape(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}') || '') %></td>
{/if}

{/foreach}
{if $smarty.foreach.columnsForEach.index >= 5}-->
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
{elseif $column->IsEnum()}
						<select id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" name="{$column->NameWithoutPrefix|studlycaps|lcfirst}">
							<option value=""></option>
{foreach from=$column->GetEnumValues() item=enumVal name=enumValForEach}
							<option value="{$enumVal|escape}"<% if (item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}')=='{$enumVal|escape}') { %> selected="selected"<% } %>>{$enumVal|escape}</option>
{/foreach}
						</select>
{elseif $column->Type == "date" || $column->Type == "datetime" || $column->Type == "timestamp"}
						<div class="input-append date date-picker" data-date-format="yyyy-mm-dd">
							<input id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" type="text" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('YYYY-MM-DD') %>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
{if $column->Type == "datetime" || $column->Type == "timestamp"}
						<div class="input-append bootstrap-timepicker-component">
							<input id="{$column->NameWithoutPrefix|studlycaps|lcfirst}-time" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
{/if}
{elseif $column->Type == 'time'}
						<div class="input-append bootstrap-timepicker-component">
							<input id="{$column->NameWithoutPrefix|studlycaps|lcfirst}" type="text" class="timepicker-default input-small" value="<%= _date(app.parseDate(item.get('{$column->NameWithoutPrefix|studlycaps|lcfirst}'))).format('h:mm A') %>" />
							<span class="add-on"><i class="icon-time"></i></span>
						</div>
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
						<button id="delete{$singular}Button" class="btn btn-mini btn-danger"><i class="icon-trash icon-white"></i> Delete {$singular}</button>
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
				<i class="icon-edit"></i> Edit {$singular}
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
		<button id="new{$singular}Button" class="btn btn-primary">Add {$singular}</button>
	</p>

</div> <!-- /container -->

<?php
	$this->display('_Footer.tpl.php');
?>
