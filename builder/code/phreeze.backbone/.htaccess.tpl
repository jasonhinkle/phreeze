Options +FollowSymlinks
RewriteEngine on

## ommit these from rewriting
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

RewriteRule (.*) index.php?_REWRITE_COMMAND=$1 [QSA,L]