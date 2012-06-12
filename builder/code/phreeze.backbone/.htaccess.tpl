## .htaccess file for re-writing URLs

## for php troubleshooting enable errors
# php_flag display_errors 1
# php_value error_reporting 1 # (or 8191 for all errors)

Options +FollowSymlinks
RewriteEngine on

## some servers require this to be changed to app directory
# RewriteBase /

## ommit these paths from rewriting
RewriteRule ^(.+)\.html$ - [L]
RewriteRule ^(.+)\.php$ - [L]
RewriteRule ^(.+)\.css$ - [L]
RewriteRule ^(.+)\.js$ - [L]
RewriteRule ^(.+)\.ico$ - [L]
RewriteRule ^(.+)\.png$ - [L]
RewriteRule ^(.+)\.jpg$ - [L]
RewriteRule ^(.+)\.gif$ - [L]
RewriteRule ^scripts/.*$ - [L]
RewriteRule ^styles/.*$ - [L]
RewriteRule ^images/.*$ - [L]
RewriteRule ^bootstrap/.*$ - [L]
RewriteRule ^qunit/.*$ - [L]

RewriteRule (.*) index.php?_REWRITE_COMMAND=$1 [QSA,L]