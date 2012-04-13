/**
 * View logic for the application.  Defines generic views for collections and
 * models as well as helper methods for template generation
 */
var view = {

	/**
	 * Given an object with properties totalPages and currentPage, return
	 * HTML page navigator
	 */
	getPaginationHtml: function(page)
	{
		var html = '';
		if (page.totalPages > 1) {
			html += '<div class="pagination"><ul>';

			var first = 1;
			var last = (1 * page.totalPages);

			if (last > 10)
			{
				first = (1 * page.currentPage) - 5;
				first = first > 1 ? first : 1;

				last = last > (first + 9) ? (first + 9) : last;
			}

			if (first > 1)
			{
				html += '<li><a class="pageButton" id="page-' + (first - 1) + '" href="#">&laquo;</a></li>';
			}

			for (var i = first; i <= last; i++) {
				html += '<li' + (page.currentPage == i ? ' class="active"' : '') + '>'
					+ '<a class="pageButton" id="page-' + i + '" href="#">'
					+ i + '</a></li>';
			}

			if (last < (1 * page.totalPages) )
			{
				html += '<li><a class="pageButton" id="page-' + (last+1) + '" href="#">&raquo;</a></li>';
			}
			html += '</ul></div>';
		}

		return html;
	},

	/**
	 * CollectionView implements a generic view for rendering collections
	 * Note that the property templateEl must be set after instantiation
	 */
	CollectionView: Backbone.View.extend({

		/** @var compiled underscore template */
		template: null,

		/** @var templateEl (required) is the element containing the underscore template code */
		templateEl: null,

		/** @var automatically update the model on every change  */
		automaticallyUpdateModel: false,

		/** @var override backbone.events - handle when the view has been changed */
		events: { 'change': 'handleViewChange' },

		/** initialize is fired by backbone immediately after construction */
		initialize: function() {

			// if the collection changes these will fire
			this.collection.bind('add', this.handleCollectionAdd, this);
			this.collection.bind('remove', this.handleCollectionRemove, this);
			this.collection.bind('reset', this.handleCollectionReset, this);

			// if a model inside the collection changes this will fire
			this.collection.bind('change', this.handleModelChange, this);
		},

		/** prepare will pre-compile the underscore template if necessary */
		prepare: function() {

			if (!this.template) {

				var tpl = this.templateEl.html();
				this.template = _.template( tpl );
			}
		},

		/** render populates the container element with contents of the template */
		render: function() {

			this.prepare();

			// convert the collection to a simple json object so the templates are not so verbose
			var html = this.template({
				items: this.collection,
				page: {totalResults: this.collection.totalResults, totalPages: this.collection.totalPages, totalPages: this.collection.totalPages, currentPage: this.collection.currentPage}
			});

			$(this.el).html(html);

			// let any interested parties know that render is complete
			this.trigger('rendered');
		},

		/** if collection changes re-render */
		handleCollectionAdd: function(m) {
			this.render();
		},

		/** if collection changes re-render */
		handleCollectionRemove: function(m) {
			this.render();
		},

		/** if collection changes re-render */
		handleCollectionReset: function(ev) {
			this.render();
		},

		/** if collection changes re-render */
		handleModelChange: function(ev) {
			this.render();
		},

		/**
		 * fires when the view has changed (normally via user input).  When the user
		 * updates the value of a form input within the view, this will fire.
		 *
		 * If automaticallyUpdateModel=true then model changes will be posted to the
		 * server automatically
		 *
		 * In order for this method to determine the primary key and property name
		 * of the input that was updated the id property of the input must be set
		 * in the following format:
		 *
		 * <input id="[prop]_[id]"  ... />
		 *
		 * where [prop] is the name of the model propery and [id] is the
		 * id (unique id) of the model
		 */
		handleViewChange: function(ev) {

			if (automaticallyUpdateModel) {

				// use the name of the input element to determine what field changed
				var pair = ev.target.id.split('_');
				var propName = pair[0];
				var id = pair[1];

				//  get the new value
				var val = $(ev.target).val();

				// get the model from the collection
				var m = this.collection.get(id);

				// specify the property and new value
				var options = {};
				options[propName] = val;

				// post model change to server (which will fire a change event on the model)
				m.set( options );
			}
		}

	}),

	/**
	 * ModelView implements a generic view for displaying an individual model
	 * as an editable form.
	 * Note that the property templateEl must be set after instantiation
	 */
	ModelView: Backbone.View.extend({

		/** @var compiled underscore template */
		template: null,

		/** @var templateEl (required) is the element containing the underscore template code */
		templateEl: null,

		/** @var automatically update the model on every change (recommended value is false)  */
		automaticallyUpdateModel: false,

		/** @var override backbone.events - handle when the view has been changed */
		events: { 'change': 'handleViewChange' },

		/** initialize is fired by backbone */
		initialize: function() {
		},

		/** prepare will pre-compile the underscore template if necessary */
		prepare: function() {

			if (!this.template) {

				var tpl = this.templateEl.html();
				this.template = _.template( tpl );
			}
		},

		/** render populates the container element with contents of the template */
		render: function() {

			this.prepare();

			var html = this.template({
				item: this.model
			});

			$(this.el).html(html);

			// let any interested parties know that render is complete
			this.trigger('rendered');
		},

		/**
		 * fires when the view has changed (normally via user input).  When the user
		 * updates the value of a form input within the view, this will fire.
		 *
		 * If automaticallyUpdateModel=true then model changes will be posted to the
		 * server automatically
		 *
		 * Implementing this can put a lot of load on the server because an update
		 * will be sent on every field change.  It is likely preferable to
		 * wait until a "save" button is clicked instead and save the changes all
		 * at once.
		 */
		handleViewChange: function(ev) {

			if (this.automaticallyUpdateModel) {

				var name = event.target.name;
				var newValue = $(event.target).val();

				var options = {};
				options[name] = newValue;

				// post model change to server (which will fire a change event on the model)
				model.set(options);
			}
		}
	}),

	version: 1.0

}