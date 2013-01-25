<?php include_once '_header.php' ?>

<h3 id="top">Configuration Files</h3>

<h4>Related Files and Videos</h4>

<ul class="nobullets">
	<li><i class="icon-play"></i> <a href="#video1Modal" data-toggle="modal">Basic Training Video #1: File Structure</a></li>
</ul>

<div id="video1Modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="video1Label" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Phreeze Training Video</h3>
	</div>
	<div class="modal-body">
		<iframe width="530" height="298" src="http://www.youtube.com/embed/obIfetsy5Is" frameborder="0" allowfullscreen></iframe>
	</div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	</div>
</div>

<h4 id="overview">Overview</h4>

<img src="images/config.png" class="pull-right" />

<p>An application generated with Phreeze Builder will have three configuration files which are 
important to understand in order to customize and deploy your application.</p>

<p>When the Phreeze application executes, index.php is called.  The index file is very short and
has a single purpose, which is to initialize the <a href="dispatcher.php">Dispatcher</a>.  The Dispatcher
then determines which Controller + Method to call.  Before index.php can perform this function,
various framework classes need to be included and instantiated.  index.php relies on three configuration files
to handle this and they are loaded in the following order:</p>

<ol>
<li><a href="#global">_global_config.php</a></li>
<li><a href="#app">_app_config.php</a></li>
<li><a href="#machine">_machine_config.php</a></li>
</ol>

<p>There is nothing magical about the name of these files and you are free to customize, 
combine or rename them as necessary.  However, these configuration files were organized 
this way for the purpose of allowing a development team to work on a shared code base
in a version control system (git, svn, etc) without creating conflicts.</p>

<h4 id="global">_global_config.php</h4>

<p>The global configuration file defines a singleton factory class <b>GlobalConfig</b> which is responible for
instantiating all of the various components needed by the framework.  The framework needs various 
objects in order to do its work: A Phreezer object, a Router, a RenderEngine and a
Context (ie session).  You can think of GlobalConfig as a container for all of the 
various subcomponents of the Phreeze framework.</p>

<p>The reason this file is loaded first is because it creates the GlobalConfig object that 
contains all of the static properties and factory methods.  The other two configuration files, 
for the most part, set and change GlobalConfig's property values.</p>

<p>GlobalConfig is also a convenient place for you to store system-wide variables 
such as API credentials, mail server settings, etc.  Although it is recommended
that you only <i>define</i> the variables here, and then set their values either in 
_app_config.php or _machine_config.php as appropriate.</p>

<h4 id="app">_app_config.php</h4>

<p>_app_config.php is the file where the PHP include path is configured, the RenderEngine
is specified and <a href="routes.php">routes</a> are defined.  This is a file that you will almost 
certainly customize in order to add, remove and change routes.</p>

<p>The application configuration file should only contain settings that pertain to the application
<i>regardless of which environment it is running</i>.  What this means is that you only should put
settings in this file if the values would be the same on localhost, staging and production servers.
For example, your routes and the RenderEngine used by the application will be the same regardless of 
whether it is moved from one machine to another.  The application configuration settings should not have
to be customized or tweaked from one machine to the next.</p>

<h4 id="machine">_machine_config.php</h4>

<p>The machine configuration file contains the settings that pertain to a specific server environment.
What this means is that the settings in this file will most likely change from one machine
to the next.  This allows you to run an application on a localhost
server, a staging server, multiple production servers, etc.  Each environment is likely to 
have different settings such as the database connection and root URL.</p>

<p>There are two settings in this file that you will most likely change when you install
your application onto a new server environment: 
<b>GlobalConfig::$CONNECTION_SETTING</b> and <b>GlobalConfig::$ROOT_URL</b>.</p>

<p>For team development environments, a suggestion is to add _machine_config.php to the
ignore list for your version control system and, in it's place, create a file such as
_machine_config.default.  Each time the application is installed, the developer will copy the default
file and edit the various settings as necessary for their particular installation.
This way your developers won't be constantly overwriting each others' machine-specific
settings every time they commit their work.</p>

<?php include_once '_footer.php' ?>