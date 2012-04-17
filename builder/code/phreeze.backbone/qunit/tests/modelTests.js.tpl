/**
 * Unit Tests for {$singular}.
 * These tests are auto-generated and meant only as a starting
 * point for full unit testing coverage.
 */

 // TODO: add test cases to insert/update/delete models.
 // these cases are not auto-generated to prevent adding garbage
 // to your database

/**
 * {$singular} Collection Fetch Test
 */
test("{$singular} Collection Fetch", function() {

	stop();
	expect(1);

	var c = new model.{$singular}Collection();

	c.fetch({
			params: { page: 1 },
			success: function()
			{
				ok(true);
				start();
			},
			error: function(m, r)
			{
				ok(false,app.getErrorMessage(r));
				start();
			}
	});

});

/**
 * {$singular} Model Fetch
  * (expects that there is a record with {$table->GetPrimaryKeyName()|studlycaps|lcfirst} = 1)
 */
test("{$singular} Model Fetch", function() {

	stop();
	expect(1);

	var m = new model.{$singular}Model();
	m.{$table->GetPrimaryKeyName()|studlycaps|lcfirst} = 1;

	m.fetch({
			success: function()
			{
				ok(true);
				start();
			},
			error: function(m, r)
			{
				ok(false,app.getErrorMessage(r));
				start();
			}
	});

});


/**
 * {$singular} Model Not Found Test
 * (expects that there are no records with {$table->GetPrimaryKeyName()|studlycaps|lcfirst} = 0)
 */
test("{$singular} Model Not Found", function() {

	stop();
	expect(1);

	var m = new model.{$singular}Model();
	m.{$table->GetPrimaryKeyName()|studlycaps|lcfirst} = 0;

	m.fetch({
			success: function()
			{
				ok(false,'An error reponse was expected');
				start();
			},
			error: function(m, r)
			{
				var msg = app.getErrorMessage(r);
				if (msg.indexOf('not found') > -1)
				{
					ok(true);
				}
				else
				{
					ok(false,app.getErrorMessage(r));
				}
				start();
			}
	});

});
