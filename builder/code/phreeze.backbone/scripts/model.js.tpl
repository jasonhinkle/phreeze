/**
 * backbone model definitions for {$appname}
 */

/**
 * Use emulated HTTP if the server doesn't support PUT/DELETE or application/json requests
 */
Backbone.emulateHTTP = false;
Backbone.emulateJSON = false

var model = {};

/**
 * long polling duration in miliseconds.  (5000 = recommended, 0 = disabled)
 * warning: setting this to a low number will increase server load
 */
model.longPollDuration = {if $enableLongPolling != '0'}5000{else}0{/if};

/**
 * whether to refresh the collection immediately after a model is updated
 */
model.reloadCollectionOnModelUpdate = true;

{foreach from=$tables item=table}{if isset($tableInfos[$table->Name])}
{assign var=singular value=$tableInfos[$table->Name]['singular']}
{assign var=plural value=$tableInfos[$table->Name]['plural']}
/**
 * {$singular} Backbone Model
 */
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

/**
 * {$singular} Backbone Collection
 */
model.{$singular}Collection = Backbone.Collection.extend({
	url: 'api/{$plural|lower}',
	model: model.{$singular}Model,

	totalResults: 0,
	totalPages: 0,
	currentPage: 0,
	pageSize: 0,
	orderBy: '',
	orderDesc: false,
	lastResponseText: null,
	collectionHasChanged: true,

	/**
	 * override parse to track changes and handle pagination
	 * if the server call has returned page data
	 */
	parse: function(response, xhr) {

		this.collectionHasChanged = (this.lastResponseText != xhr.responseText);
		this.lastResponseText = xhr.responseText;

		var rows;

		if (response.currentPage)
		{
			rows = response.rows;
			this.totalResults = response.totalResults;
			this.totalPages = response.totalPages;
			this.currentPage = response.currentPage;
			this.pageSize = response.pageSize;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		}
		else
		{
			rows = response;
			this.totalResults = rows.length;
			this.totalPages = 1;
			this.currentPage = 1;
			this.pageSize = this.totalResults;
			this.orderBy = response.orderBy;
			this.orderDesc = response.orderDesc;
		}

		return rows;
	}
});

{/if}{/foreach}