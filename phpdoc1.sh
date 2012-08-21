# PHREEZE DOCUMENTATION GENERATOR
# This script is for user with phpdoc v1 only.
# If you have phpdoc v2 installed then use phpdoc2.sh
# instead of this script.
#
# Prerequisites:
#	sudo pear install PhpDocumentor-1.4.3
#
# Configuration settings are in the command line below:

rm -rf docs
phpdoc -d libs/verysimple/ -f /usr/lib/php/pear/PEAR.php,libs/savant/Savant3.php,libs/smarty/Smarty.class.php,libs/smarty/Smarty_Compiler.class.php,libs/phpmailer/class.phpmailer.php -i .*,class.*.php -t docs -dn verysimple--Phreeze -dc VerySimple -o HTML:frames:DOM/earthli -ti "Phreeze API Documentation"
chmod -R 755 docs