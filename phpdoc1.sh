# generate documentation with phpdocumentor v1.  for v2, phpdoc.dist.xml is used instead
rm -rf docs
phpdoc -d libs/verysimple/ -f /usr/lib/php/pear/PEAR.php,libs/savant/Savant3.php,libs/smarty/Smarty.class.php,libs/smarty/Smarty_Compiler.class.php,libs/phpmailer/class.phpmailer.php -i .*,class.*.php -t docs -dn verysimple--Phreeze -dc VerySimple -o HTML:frames:DOM/earthli -ti "Phreeze API Documentation"
chmod -R 755 docs