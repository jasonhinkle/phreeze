/**
 * View logic for {$plural}
 */

/**
 * application logic specific to the {$singular} listing page
 */
var page = {

	{$plural|lcfirst}: new model.{$singular}Collection(),
	collectionView: null,
	{$singular|lcfirst}: null,
	modelView: null,
	isInitialized: false,
	isInitializing: false,

	fetchParams: { filter: '', orderBy: '', orderDesc: '', page: 1 },
	fetchInProgress: false,
	dialogIsOpen: false,

	/**
	 *
	 */
	init: function()
	{
		// ensure initialization only occurs once
		if (page.isInitialized || page.isInitializing) return;
		page.isInitializing = true;

		if (!$.isReady && console) console.warn('page was initialized before dom is ready.  views may not render properly.');

		// make the new button clickable
		$("#new{$singular}Button").click(function(e) {
			e.preventDefault();
			page.showDetailDialog();
		});

		// let the page know when the dialog is open
		$('#{$singular|lcfirst}DetailDialog').on('show',function(){
			page.dialogIsOpen = true;
		});

		// when the model dialog is closed, let page know and reset the model view
		$('#{$singular|lcfirst}DetailDialog').on('hidden',function(){
			$('#modelAlert').html('');
			page.dialogIsOpen = false;
		});

		// save the model when the save button is clicked
		$("#save{$singular}Button").click(function(e) {
			e.preventDefault();
			page.updateModel();
		});

		// initialize the collection view
		this.collectionView = new view.CollectionView({
			el: $("#{$singular|lcfirst}CollectionContainer"),
			templateEl: $("#{$singular|lcfirst}CollectionTemplate"),
			collection: page.{$plural|lcfirst}
		});

		// initialize the search filter
		$('#filter').change(function(obj){
			page.fetchParams.filter = $('#filter').val();
			page.fetchParams.page = 1;
			page.fetch{$plural}(page.fetchParams);
		});
		
		// make the rows clickable ('rendered' is a custom event, not a standard backbone event)
		this.collectionView.on('rendered',function(){

			/* attach click handler to the table rows for editing
			$('table.collection tbody tr').click(function(e) {
				e.preventDefault();
				var m = page.{$plural|lcfirst}.get(this.id);
				page.showDetailDialog(m);
			});
            */
            
            
            
			// make the headers clickable for sorting
 			$('table.collection thead tr th').click(function(e) {
 				e.preventDefault();
				var prop = this.id.replace('header_','');

				// toggle the ascending/descending before we change the sort prop
				page.fetchParams.orderDesc = (prop == page.fetchParams.orderBy && !page.fetchParams.orderDesc) ? '1' : '';
				page.fetchParams.orderBy = prop;
				page.fetchParams.page = 1;
 				page.fetch{$plural}(page.fetchParams);
 			});

			// attach click handlers to the pagination controls
			$('.pageButton').click(function(e) {
				e.preventDefault();
				page.fetchParams.page = this.id.substr(5);
				page.fetch{$plural}(page.fetchParams);
			});
			
			page.isInitialized = true;
			page.isInitializing = false;
		});

		// backbone docs recommend bootstrapping data on initial page load, but we live by our own rules!
		this.fetch{$plural}({ page: 1 });

		// initialize the model view
		this.modelView = new view.ModelView({
			el: $("#{$singular|lcfirst}ModelContainer")
		});

		// tell the model view where it's template is located
		this.modelView.templateEl = $("#{$singular|lcfirst}ModelTemplate");

		if (model.longPollDuration > 0)
		{
			setInterval(function () {

				if (!page.dialogIsOpen)
				{
					page.fetch{$plural}(page.fetchParams,true);
				}

			}, model.longPollDuration);
		}
	},

	/**
	 * Fetch the collection data from the server
	 * @param object params passed through to collection.fetch
	 * @param bool true to hide the loading animation
	 */
	fetch{$plural}: function(params, hideLoader)
	{
		// persist the params so that paging/sorting/filtering will play together nicely
		page.fetchParams = params;

		if (page.fetchInProgress)
		{
			if (console) console.log('supressing fetch because it is already in progress');
		}

		page.fetchInProgress = true;

		if (!hideLoader) app.showProgress('loader');;

		page.{$plural|lcfirst}.fetch({

			data: params,

			success: function() {

				if (page.{$plural|lcfirst}.collectionHasChanged)
				{
					// TODO: add any logic necessary if the collection has changed
					// the sync event will trigger the view to re-render
				}

				app.hideProgress('loader');
				page.fetchInProgress = false;
			},

			error: function(m, r) {
				app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'collectionAlert');
				app.hideProgress('loader');
				page.fetchInProgress = false;
			}

		});
	},

	/**
	 * show the dialog for editing a model
	 * @param model
	 */
	showDetailDialog: function(m) {

		// show the modal dialog
		$('#{$singular|lcfirst}DetailDialog').modal({ show: true });

		// if a model was specified then that means a user is editing an existing record
		// if not, then the user is creating a new record
		page.{$singular|lcfirst} = m ? m : new model.{$singular}Model();

		page.modelView.model = page.{$singular|lcfirst};

		if (page.{$singular|lcfirst}.id == null || page.{$singular|lcfirst}.id == '')
		{
			// this is a new record, there is no need to contact the server
			page.renderModelView(false);
		}
		else
		{
			app.showProgress('modelLoader');

			// fetch the model from the server so we are not updating stale data
			page.{$singular|lcfirst}.fetch({

				success: function() {
					// data returned from the server.  render the model view
					page.renderModelView(true);
				},

				error: function(m, r) {
					app.appendAlert(app.getErrorMessage(r), 'alert-error',0,'modelAlert');
					app.hideProgress('modelLoader');
				}

			});
		}

	},

	/**
	 * Render the model template in the popup
	 * @param bool show the delete button
	 */
	renderModelView: function(showDeleteButton)
	{
		page.modelView.render();

		app.hideProgress('modelLoader');

		// initialize any special controls
		try {
			$('.date-picker')
				.datepicker()
				.on('changeDate', function(ev){
					$('.date-picker').datepicker('hide');
				});
		} catch (error) {
			// this happens if the datepicker input.value isn't a valid date
			if (console) console.log('datepicker error: '+error.message);
		}
		
		$('.timepicker-default').timepicker({ defaultTime: 'value' });

{foreach from=$table->Columns item=column name=columnsForEach}
{if $column->Key == "MUL" && $column->Constraints}
{assign var=constraint value=$table->Constraints[$column->Constraints[0]]}
		// populate the dropdown options for {$column->NameWithoutPrefix|studlycaps|lcfirst}
		// TODO: load only the selected value, then fetch all options when the drop-down is clicked
		var {$column->NameWithoutPrefix|studlycaps|lcfirst|escape}Values = new model.{$tableInfos[$constraint->ReferenceTableName]['singular']}Collection();
		{$column->NameWithoutPrefix|studlycaps|lcfirst|escape}Values.fetch({
			success: function(c){
				var dd = $('#{$column->NameWithoutPrefix|studlycaps|lcfirst|escape}');
				dd.append('<option value=""></option>');
				c.forEach(function(item,index)
				{
					dd.append(app.getOptionHtml(
						item.get('{$constraint->ReferenceKeyColumnNoPrefix|studlycaps|lcfirst}'),
						item.get('{$constraint->ReferenceTable->GetDescriptorName()|studlycaps|lcfirst}'), // TODO: change fieldname if the dropdown doesn't show the desired column
						page.{$singular|lcfirst}.get('{$column->NameWithoutPrefix|studlycaps|lcfirst|escape}') == item.get('{$constraint->ReferenceKeyColumnNoPrefix|studlycaps|lcfirst}')
					));
				});
				
				if (!app.browserSucks())
				{
					dd.combobox();
					$('div.combobox-container + span.help-inline').hide(); // TODO: hack because combobox is making the inline help div have a height
				}

			},
			error: function(collection,response,scope){
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
			}
		});

{elseif $column->IsEnum()}
	if (!app.browserSucks()) 
	{
		$('#{$column->NameWithoutPrefix|studlycaps|lcfirst|escape}').combobox();
		$('div.combobox-container + span.help-inline').hide(); // TODO: hack because combobox is making the inline help div have a height
	}

{/if}
{/foreach}

		if (showDeleteButton)
		{
			// attach click handlers to the delete buttons

			$('#delete{$singular}Button').click(function(e) {
				e.preventDefault();
				$('#confirmDelete{$singular}Container').show('fast');
			});

			$('#cancelDelete{$singular}Button').click(function(e) {
				e.preventDefault();
				$('#confirmDelete{$singular}Container').hide('fast');
			});

			$('#confirmDelete{$singular}Button').click(function(e) {
				e.preventDefault();
				page.deleteModel();
			});

		}
		else
		{
			// no point in initializing the click handlers if we don't show the button
			$('#delete{$singular}ButtonContainer').hide();
		}
	},

	/**
	 * update the model that is currently displayed in the dialog
	 */
	updateModel: function()
	{
		// reset any previous errors
		$('#modelAlert').html('');
		$('.control-group').removeClass('error');
		$('.help-inline').html('');

		// if this is new then on success we need to add it to the collection
		var isNew = page.{$singular|lcfirst}.isNew();

		app.showProgress('modelLoader');

		page.{$singular|lcfirst}.save({
{foreach from=$table->Columns item=column name=columnsForEach}
{if $column->Extra != 'auto_increment'}
			'{$column->NameWithoutPrefix|studlycaps|lcfirst}': {if $column->Type == "datetime" || $column->Type == "timestamp"}$('input#{$column->NameWithoutPrefix|studlycaps|lcfirst}').val()+' '+$('input#{$column->NameWithoutPrefix|studlycaps|lcfirst}-time').val(){else}$('{if (($column->Key == "MUL" && $column->Constraints) || $column->IsEnum())}select{elseif $column->Type == 'text' || $column->Type == 'tinytext' || $column->Type == 'mediumtext' || $column->Type == 'longtext'}textarea{else}input{/if}#{$column->NameWithoutPrefix|studlycaps|lcfirst}').val(){/if}{if !$smarty.foreach.columnsForEach.last},{/if}
{/if}

{/foreach}
		}, {
			wait: true,
			success: function(){
				$('#{$singular|lcfirst}DetailDialog').modal('hide');
				setTimeout("app.appendAlert('{$singular} was sucessfully " + (isNew ? "inserted" : "updated") + "','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				// if the collection was initally new then we need to add it to the collection now
				if (isNew) { page.{$plural|lcfirst}.add(page.{$singular|lcfirst}) }

				if (model.reloadCollectionOnModelUpdate)
				{
					// re-fetch and render the collection after the model has been updated
					page.fetch{$plural}(page.fetchParams,true);
				}
		},
			error: function(model,response,scope){

				app.hideProgress('modelLoader');

				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');

				try {
					var json = $.parseJSON(response.responseText);

					if (json.errors)
					{
						$.each(json.errors, function(key, value) {
							$('#'+key+'InputContainer').addClass('error');
							$('#'+key+'InputContainer span.help-inline').html(value);
							$('#'+key+'InputContainer span.help-inline').show();
						});
					}
				} catch (e2) {
					if (console) console.log('error parsing server response: '+e2.message);
				}
			}
		});
	},

	/**
	 * delete the model that is currently displayed in the dialog
	 */
	deleteModel: function()
	{
		// reset any previous errors
		$('#modelAlert').html('');

		app.showProgress('modelLoader');

		page.{$singular|lcfirst}.destroy({
			wait: true,
			success: function(){
				$('#{$singular|lcfirst}DetailDialog').modal('hide');
				setTimeout("app.appendAlert('The {$singular} record was deleted','alert-success',3000,'collectionAlert')",500);
				app.hideProgress('modelLoader');

				if (model.reloadCollectionOnModelUpdate)
				{
					// re-fetch and render the collection after the model has been updated
					page.fetch{$plural}(page.fetchParams,true);
				}
			},
			error: function(model,response,scope){
				app.appendAlert(app.getErrorMessage(response), 'alert-error',0,'modelAlert');
				app.hideProgress('modelLoader');
			}
		});
	},
    actionEdit: function(id){
        var m = page.{$plural|lcfirst}.get(id);
	    page.showDetailDialog(m);
    }
};

