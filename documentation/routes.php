<?php include_once '_header.php' ?>

<h3 id="top">Routes</h3>

<h4 id="related">Related Files and Videos</h4>

<ul class="nobullets">
	<li><i class="icon-play"></i> <a href="#video1Modal" data-toggle="modal">Basic Training Video #2: Routes and Controllers</a></li>
</ul>

<div id="video1Modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="video1Label" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Phreeze Training Video</h3>
	</div>
	<div class="modal-body">
		<iframe width="530" height="298" src="http://www.youtube.com/embed/p5pXlNqO1Tc" frameborder="0" allowfullscreen></iframe>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<h4 id="overview">Overview</h4>

<p>In a simple PHP application you might have a stand-alone PHP
file for each page such as /customer.php, /product.php, etc and each
script is executed when it's requested by name in the URL.  With a Phreeze
application (like many modern web apps) the URLs of the web app do not
necessarily relate to a specific PHP file with the same name.  Instead the 
URLs are "virtual" and  all requests go through one
PHP file (usually index.php).  This entry-point script analyzes the URL
and then decides what functions to execute. In order to get all requests
to go through one single file, you utilize the "rewrite" feature
of your web server.  For example, Apache can be configured using an 
.htaccess file.</p>

<p>For these virtual URLs to work, our index.php file has to know
which function to call when a particular URL is requested.  In other words,
we need to map public URLs to PHP classes, methods or functions in our app.
In a Phreeze application the variable <code>GlobalConfig::ROUTE_MAP</code>
holds this information.  By default this is configured in the 
<a href="configuration.php">_app_config.php</a> file.</p>

<p>The route map is really just a specially formatted array of key/value pairs.  
The key is a URL pattern and the value is the 'route' to execute.  Let's take a look
at a simple route map:</p>

<pre class="prettyprint linenums">
GlobalConfig::$ROUTE_MAP = array(

	'GET:customers' => array('route' => 'Customer.View'),
	'POST:product' => array('route' => 'Product.Insert')
	
);
</pre>

<p>This route map has exactly two routes and they each are in the following format:</p>

<pre class="prettyprint">
'[VERB]:[URL]' => array('route' => '[CONTROLLER].[METHOD]')
</pre>

<p>The first route in the above example <code>'GET:customers' => array('route' => 'Customer.View')</code> 
might look like this in your browser: <code>http://localhost/customers</code>.  When Phreeze encounters this 
URL it will instantiate the 'CustomerController' class and fire a method called View() on that class.</p>

<p>The second route in the example <code>'POST:product' => array('route' => 'Customer.Insert')</code> 
is slightly different.  Notice that it begins with 'POST' instead of 'GET'.  This tells Phreeze that
this route only matches POST requests from the browser.  So, simply typing the URL 
<code>http://localhost/product</code> into your browser will not trigger the route.  POST requests are 
usually the result of either submitting a form, or an AJAX request.  
In this example, a POST request to <code>http://localhost/product</code> would fire ProductController.Insert().
The common HTTP verbs used in web apps are GET, POST, PUT and DELETE and in your route map you can handle
them all separately.</p>

<h4 id="params">URL Parameters</h4>

<p>The previous example allowed you to map a URL to a controller class so long as there was an exact match.
However in a typical application you will have parameters as part of your URL, for example:
<code>http://localhost/api/sales/customer/25</code>.  Most likely this URL would have something to do
with a customer record with an ID of 25.  But, how would we get the route to respond to any ID number
such as 25, 26 27, etc?  This is done using wildcard patterns in the route map like so:</p>

<pre class="prettyprint linenums">
GlobalConfig::$ROUTE_MAP = array(

	'GET:api/sales/customer/(:num)' => array(
		'route' => 'Customer.View'
	)
	
);
</pre>

<p>Notice that <code>(:num)</code> is on the end of the URL. This tells Phreeze to map any URL that matches
the pattern <code>http://localhost/api/sales/customer/(:num)</code> where (:num) is a numerical value.</p>

<p>This solves the problem of routing the URL to the appropriate controller, but we now have another
issue.  From within our controller code, we need to get the value of that
parameter.  In other words, we need to get the ID for the customer from the URL so that our controller
method knows which Customer object is being requested.  Let's add some code to the route map:</p>

<pre class="prettyprint linenums">
GlobalConfig::$ROUTE_MAP = array(

	'GET:api/sales/customer/(:num)' => array(
		'route' => 'Customer.View', 
		'params' => array('customerId' => 3)
	)
	
);
</pre>

<p>We've added a second key called 'params' to the route array.  Before we look at that,
let's take a moment to analyze the URL from the persepective of the Router.
Using the forward slash / character as a delimiter, the URL
<code>http://localhost/api/sales/customer/25</code> would be split into 4 parts:</p>

<pre class="prettyprint">
0 = api
1 = sales
2 = customer
3 = 25
</pre>

<p>Given the URL above, our controller would likely be interested in obtaining the
value '25' without without manually parsing the URL.  Lets take another look at the 'params'
key: <code>'params' => array('customerId' => 3)</code>  What this tells Phreeze is that
the item of the exploded URL at position 3 is going to be assigned a name of 'customerId'.  
Notice that this is a zero-based array, so the count starts at 0 instead of 1.</p>

<p>To make things more clear, let's take a look at how we access that from within the Controller:</p>

<pre class="prettyprint linenums">
class CustomerController extends Controller
{
	public function View()
	{
		// get the value of customerId from the router
		$id = $this->GetRouter()->GetUrlParam('customerId');
	}
}
</pre>

<p>The controller is able to get the value '25' only using the assigned name of 'customerId'
so it doesn't need to know anything about the format of the URL.</p>

<p class="well"><em>Why use all of this abstraction and not just access the URL directly from within the controller?  
The reason is so that the Controller is not tightly coupled with the URL.  This allows us
flexibility to later change URLs without re-writing controller code.  The Router is the only
class that has to understand the URLs and route map and can provide information to the controller
in a more abstract manner.  This strategy also makes unit testing easier because we can test our 
controller methods from the command-line and use a mock router to provide information to the controller.
The controller won't know or care whether it is running in a web environment or from the command line.</em></p>

<h4 id="wildcard">Wildcard Patterns</h4>

<p>In the previous example we used the pattern <code>(:num)</code> as a placeholder for any valid number in the 
URL.  What if the ID we want is not a numerical value?  We can also use <code>(:any)</code> to match
any character in the URL for example:</p>

<pre class="prettyprint linenums">
GlobalConfig::$ROUTE_MAP = array(

	'GET:/customer/(:any)' => array(
		'route' => 'Customer.View', 
		'params' => array('customerCode' => 1)
	)
	
);
</pre>

<p>All of the following URLs would match in the above example: <code>http://localhost/customer/aaa</code>, <code>http://localhost/customer/123</code>, <code>http://localhost/customer/zzz</code></p>

<h4 id="order">Order of Operations</h4>

<p>One word of caution about using the (:any) pattern is that Phreeze will return the first match
that it finds.  In the example below, the 2nd route will never be hit because the pattern 
above it will be matched by the same URL.  When two routes match the same URL, Phreeze will
always use whichever one is first.</p>

<pre class="prettyprint linenums">
GlobalConfig::$ROUTE_MAP = array(

	'GET:/customer/(:any)' => array(
		'route' => 'Customer.View', 
		'params' => array('customerCode' => 1)
	),
	
	// THIS ROUTE WILL NEVER BE HIT!
	'GET:/customer/update' => array(
		'route' => 'Customer.Update'
	)
	
);
</pre>

<p>If you were to reverse the order of the two routes above, then the 'update' route would be hit and the (:any)
route would be hit for any other match.</p>

<h4 id="more">More Information</h4>

<p>The route map array is process by a class in the Phreeze library names "GenericRouter"  This class is an
implementation of the IRouter interface.  You can write your own implementation of IRouter to process your
own specialized routes.</p>

<p>Classes that use the Router are the <a href="controllers.php">Controller</a> 
and the <a href="dispatcher.php">Dispatcher</a>. </p>

<?php include_once '_footer.php' ?>