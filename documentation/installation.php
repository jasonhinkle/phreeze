<?php include_once '_header.php' ?>

<h3 id="top">Installing Phreeze</h3>

<h4>Related Files and Videos</h4>

<ul class="nobullets">
	<li><i class="icon-play"></i> <a href="#video1Modal" data-toggle="modal">Basic Training Video #1: File Structure</a></li>
	<li><i class="icon-link"></i> <a href="serverconfig.php">Apache, Ngnix, IIS Server Configuration</a></li>
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



<h4 id="requirements">System Requirements</h4>

<ul>
<li>A web server (Apache, IIS or Ngnix)</li>
<li>PHP 5.2 (or higher)</li>
<li>MySQL (doesn't need to be on the same machine)</li>
</ul>

<h4 id="download">Download or Clone Phreeze</h4>

<p>Phreeze is hosted on <a href="https://github.com/jasonhinkle/phreeze">github</a>.
You can either clone the git repository or download the master revision as
a zip file.  Cloning the repository makes it easy to stay up to date with 
the most recent changes, however either method is fine.</p>

<h5>Clone Phreeze Repository</h5>

<p>To clone the repository you must have git installed on your server.
Once installed, it is recommended to open the command line to your
web root directory and type the following:</p>

<pre class="prettyprint linenums">
git clone git://github.com/jasonhinkle/phreeze.git
</pre>

<p>Once you've cloned the repository you can always update to the latest version with the following:</p>

<pre class="prettyprint linenums">
cd /path/to/web-root/phreeze
git pull
</pre>

<h5>Or Download Phreeze</h5>

<p><a class="btn" href="https://github.com/jasonhinkle/phreeze/archive/master.zip"><i class=" icon-download-alt"></i> Download master.zip</a></p>

<h4 id="install" style="margin-top: 1.2em;">Install Phreeze</h4>

<p>Phreeze doesn't require installation per se, so long as either /phreeze is saved in your web root directory
or /phreeze/libs can be found in your PHP include path.</p>

<p>To get started it is recommended to go with the option of saving the /phreeze directory in your
web server root.  On production systems you may want to move phreeze to a non-accessible
location so that visitors cannot access the builder or documentation folders.
To keep things simple however, the instructions and tutorials will assume that /phreeze exists 
in your web root.</p>

<p>Your directory structure should look something like this:</p>

<div class="well">
	<ul>
		<li>/path/to/web-root
		<ul>
			<li>/phreeze
			<ul>
				<li>/builder</li>
				<li>/documentation</li>
				<li>/libs</li>
				<li>/tests</li>
			</ul></li>
		</ul></li>
	</ul>
</div>

<h4 id="test">Run Phreeze Builder</h4>

<p>Now that you have /phreeze saved in your web root, open the builder application to 
verify that everything is working by opening the following URL in your browser:</p>

<p><code>
http://localhost/phreeze/builder/</code></p>

<p>If you run into any issues with 401 page-not-found errors, then you will need to check that
your web server URL rewriting functionality is enabled.  Phreeze includes three config files 
that can be used for reference depending on your web server: /.htaccess for Apache, 
/builder/web.config for IIS and /nginx.conf.example for Ngnix.  If you need further assistance
you can refer to this page for 
<a href="serverconfig.php">additional information about URL rewrite configuration</a>.</p>

<?php include_once '_footer.php' ?>