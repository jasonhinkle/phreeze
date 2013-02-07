# PHREEZE DOCUMENTATION GENERATOR
# This script is for user with phpdoc v1 only.
# If you have phpdoc v2 installed then use phpdoc2.sh
# instead of this script.
#
# To install PhpDocumenter v1
#	sudo pear install PhpDocumentor-1.4.3
#
# To install PhpDocumenter v2
# 	sudo pear channel-discover pear.phpdoc.org
#	sudo pear install phpdoc/phpDocumentor-alpha 
#
# Configuration settings are in the command line below:

rm -rf html

# use phpdoc v1
phpdoc -d ../libs/verysimple/ -f /usr/lib/php/pear/PEAR.php,../libs/savant/Savant3.php,../libs/smarty/Smarty.class.php,../libs/smarty/Smarty_Compiler.class.php,../libs/phpmailer/class.phpmailer.php -i .*,class.*.php -t html -dn verysimple--Phreeze -dc VerySimple -o HTML:frames:DOM/earthli -ti "Phreeze API Documentation"

# uncomment to use phpdoc v2
# phpdoc

# set read permissions
chmod -R 755 html