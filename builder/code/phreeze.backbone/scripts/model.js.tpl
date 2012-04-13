/**
 * backbone model definitions for {$appname}
 */

// Uncomment the following if the server won't support PUT/DELETE or application/json requests
// Backbone.emulateHTTP = true;
// Backbone.emulateJSON = true

var model = {};

// duration in miliseconds to automatically re-fetch data from server (0 = do not use long polling)
// warning!  changing this setting can cause high server load.  change with caution
model.longPollDuration = 0;

{foreach from=$tables item=table}{if isset($tableInfos[$table->Name])}
{assign var=singular value=$tableInfos[$table->Name]['singular']}
{assign var=plural value=$tableInfos[$table->Name]['plural']}
model.{$singular}Model = Backbone.Model.extend({
	urlRoot: 'api/{$singular|lower}',
	idAttribute: '{$table->GetPrimaryKeyName()|studlycaps|lcfirst}',
{foreach from=$table->Columns item=column name=columnsForEach}
	{$column->NameWithoutPrefix|studlycaps|lcfirst}: '',
{/foreach}
	defaults: {
{foreach from=$table->Columns item=column name=columnsForEach}
		'{$column->NameWithoutPrefix|studlycaps|lcfirst}': {if $column->NameWithoutPrefix == $table->GetPrimaryKeyName()}null{elseif $column->Type == "date" or $column->Type == "datetime"}new Date(){else}''{/if}{if !$smarty.foreach.columnsForEach.last},{/if}

{/foreach}
	}
});

model.{$singular}Collection = Backbone.Collection.extend({
	url: 'api/{$singular|lower}',
	model: model.{$singular}Model,

	totalResults: 0,
	totalPages: 0,
	currentPage: 0,
	pageSize: 0,
	lastResponseText: null,
	collectionHasChanged: true,

	// override parse to track changes and handle pagination
	parse: function(response, xhr) {

		this.collectionHasChanged = (this.lastResponseText != xhr.responseText);
		this.lastResponseText = xhr.responseText;

		this.totalResults = response.totalResults;
		this.totalPages = response.totalPages;
		this.currentPage = response.currentPage;
		this.pageSize = response.pageSize;

		return response.rows;
	}
});

{/if}{/foreach}