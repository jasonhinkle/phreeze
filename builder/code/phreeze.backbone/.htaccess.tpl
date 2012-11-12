## Phreeze .htaccess file

## for php troubleshooting enable errors
# php_flag display_errors 1
# php_value error_reporting 1 # (or 8191 for all errors)

<IfModule mod_rewrite.c>
	Options +FollowSymLinks
	RewriteEngine On
	
	## some servers require this to be changed to app directory
	# RewriteBase /
</IfModule>

# redirect all requests to index.php except for directories
# and files that physically exist on the server
<IfModule mod_rewrite.c>
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule (.*) index.php?_REWRITE_COMMAND=$1 [QSA,L]
</IfModule>

# Add correct mime type for web fonts to prevent browser warnings
<IfModule mod_mime.c>
	AddType application/vnd.ms-fontobject eot
	AddType font/opentype otf
	AddType font/truetype ttf
	AddType application/x-font-woff woff
</IfModule>