<?php include_once '_header.php' ?>

<h3 id="top">Server Configuration</h3>

<p>Phreeze has fairly minimal requirements and will run out of the box on most standard server installations.
	If you manage your own server there may be some configuration necessary.  On all servers, Phreeze requires 
	PHP 5.3 or higher and access to a MySQL server.  The MySQL server does
	not need to run on the same server as the application.</p>
	
<p>In order for Phreeze applications to run, the "phreeze/libs" folder must be somewhere in your PHP path.
	By default each application will check the PHP path, and additionally will look for phreeze in a
	relative path.  For example /var/www/myapp will look for /var/www/phreeze/libs.  If you cannot add
	phreeze to your server path or a relative path, then you can customize _app_config.php to tell your
	application where the phreeze libraries are located.</p>

<h4>Installing on Apache</h4>

<p>The only special consideration on Apache is that mod_rewrite must be enabled and "AllowOverride All" must 
	be specified in the apache conf file.  This allows phreeze to rewrite URLs in an .htaccess file.</p>

<h4>Installing on Ngnix</h4>

<p>In the root of the phreeze directory is an example config file for Ngnix called ngnix.conf.example.  The only
	special configuration that is required is a rewrite rule so that phreeze friendly URLs are recognized.

<h4>Installing on Internet Information Server (IIS)</h4>

<p>Phreeze runs on IIS with one additional component and some minimal configuration.  In order to recognize
friendly URLs, <a href="http://www.iis.net/downloads/microsoft/url-rewrite">URL Rewrite</a> must be installed.
(If you do not have permission to install this extention then see "other servers" below).  Phreeze builder and
apps include a web.config file that contains the necessary rewrite rules so that Phreeze will run properly.</p>

<p>In addition to URL rewriting, IIS may need to be enabled to recognize additional "verbs" for the REST 
service.  By default IIS only allows GET,HEAD,POST.  You must enable PUT and DELETE.  (see the screenshot
below if you do not know how to enable these).  If you do not have permission to alter the verbs on your
server environment then you may optionally tell phreeze to simulate HTTP verbs.  This is done in your
application /scripts/model.js file.  There is a setting "Backbone.emulateHTTP" which can be set to true.
</p>

<p><img src="images/iis-verb-config.png" alt=""/></p>

<h4>Installing on Other Servers</h4>

<p>Phreeze has not been tested on other servers, however it is likely to run on most servers as long
	as PHP is supported.  The only special requirement is that URL rewriting must be configured so that
	Phreeze can respond to friendly URLs.</p>
	
<p>Whatever rewrite mechanism is available on the server must match the following pattern:<p>
	
<pre>appfolder/(.+)</pre>
	
<p>If the file or directory exists it must be ignored, otherwise rewrite the url as:</p>

<pre>appfolder/index.php?_REWRITE_COMMAND={MATCH}</pre>

<?php include_once '_footer.php' ?>