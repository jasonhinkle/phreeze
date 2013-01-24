<?php include_once '_header.php' ?>

<h3 id="top">Introduction to Phreeze</h3>

<h4 id="what">What is Phreeze?</h4>

<p>In simple terms Phreeze is a framework for building PHP applications.  A framework is
basically a toolkit of helper classes along with a consistent application structure.</p>

<p>Phreeze is comprised of three components.  A typical Phreeze application will use
all parts of the framework, however they can be used independently of each other.
The three components are:</p>

<ul>
	<li>An <a href="#mvc">MVC</a> (Model-view-controller) Framework</li>
	<li>An <a href="#orm">ORM</a> (Object-Relational Mapping) for manipulating the database via PHP classes</li>
	<li><a href="#builder">Phreeze Builder</a> - a utility for generating Phreeze applications</li>
</ul>

<p>The suggested way to get started with Phreeze is to utilize Phreeze Builder
to generate an application.  The application that is generated is a basic
database editor that allows you to view, search and modify data from your
MySQL database.  This application is usable and may be sufficient for certain
internal administrative utilities.  However for a public facing website
you would use this application as a starting point for your final product.</p>

<h4 id="mvc">The MVC Framework</h4>

<p>
<img src="images/mvc.png" class="pull-right" />
The Phreeze MVC Framework implements the Model-view-controller design pattern
which is commonly used in web appliations.   You can read more information about 
<a href="http://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller">MVC design pattern on Wikipedia</a>.
The MVC allows you to organize an application into three parts so that you can 
achive separation of concerns - meaning each part of your code serves a specific
function and can operate independently of the other parts.</p>

<p>The "Model" represents your data.  In the case of Phreeze, the Model is a 
one-to-one abstraction of your database tables into PHP classes.  For basic
interaction with the database you don't need to write any SQL code, instead
you can query and write using objects and methods in your PHP Model classes.
The Model layer is not concerned with any visual display of information
on the site.  It deals only with translating between your application code
and the database.  Model classes aren't required to be tied to a database,
they can also be used to abstract any information.  However in the basic app generated
by Phreeze all Models are tied to a database table.
</p>

<p>The "View" layer is comprised of the classes that output the visual display. 
In the case of a web application, view classes will generally output things
like HTML or JSON.  The view layer is not concerned with where data comes 
from, rather is expects data to be provided to it in the form of Models.
To give you a practical example, you may have multiple views for the same
page in a web application.  One view may be optimized for browsers and the
other may be optimized for mobile devices.  Your application will have the
same back-end code for both, but the multiple views handle the different
displays.</p>

<p>The "Controller" is a class that ties together the Model and the View.
The Controller receives input from the user, reads and writes data
as necessary using the Model and then determines which View to output.
Controllers do a lot of the descision making for the application.</p>

<p>There are other design patterns in use on websites, but the MVC is 
a popular one that is particularly suited to web applications.  These
three components work together to provide a flexible app that can
grow in complexity while keeping the code organized.</p>

<h4 id="orm">The ORM</h4>

<p><img src="images/orm.png" class="pull-right" />
The Phreeze ORM are the classes that are used by the Model layer
and handle the communication between your classes and the database.
ORM stands for "Object-Relational Mapping" which basically means
mapping an Object to a relational database.  More in-depth <a href="http://en.wikipedia.org/wiki/Object-relational_mapping">information
about ORMs is available on Wikipedia</a></p>

<p>Ultimately what an ORM does is let you work with classes and 
objects in your application and some lower layer of the code
figures out how to write the correct SQL statements.  In an 
ideal world you can think of this layer as a black box that you
don't need to understand.  But eventually when you need to do
a more complex query you have to dig into the ORM to make
it do your bidding.</p>

<p>Mapping a database to classes is fairly easy if you have no
relationships between the tables.  Any non-trivial database, though,
will have foreign keys and constraints.  Mapping these conceptually
to a database gets more complicated.  If you are a developer who
utilizes complicated queries in your applications, it can be
very challenging to use an ORM because it puts a new layer between
you and your schema which may not offer the most efficient access
to the data.  This is sometimes referred to as an 
<a href="http://en.wikipedia.org/wiki/Object-relational_impedance_mismatch">object-relational impedence mismatch</a>.
Different ORMs have different strategies
for dealing with this problem.  Some provide an abstracted query language
that you need to learn.  Some simply handle relationships badly and
result in poor performance.  Phreeze take the approach of handling
the basic code, but allowing you to override things with your own
SQL code when necessary.</p>

<h4 id="builder">Phreeze Builder</h4>

<p>The final component is the Phreeze Builder.  This is not technically part of the 
Framework itself because the builder is not utilized by Phreeze applications.  The builder
is a utility that analyzes a database and auto-generates a basic application
that is ready to use and/or customize.</p>

<p>The builder is not require to use the Phreeze Framework.  You can
write your code entirely from scratch utilizing the Phreeze libraries.  However,
as with any PHP application there can be a lot of setup involved.  This can include
setting up include path, requiring libraries, instantiating framework
classes, etc.  The builder makes this easy for you by generating all of this
somewhat tedious code as well as generic controllers, models and views for
each table.</p>

<p><a href="builder.php">More about Phreeze Builder...</a></p>

<?php include_once '_footer.php' ?>