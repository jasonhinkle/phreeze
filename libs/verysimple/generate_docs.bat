@echo off
cls

echo *********************************************
echo *** DELETE ALL FILES IN THE _docs FOLDER ****
echo *********************************************
pause

phpdoc -d ./ -f ../../PEAR/PEAR.php,../Smarty.class.php,../Smarty_Compiler.class.php -i _svn*,class.*.php -t _docs -dn verysimple--Phreeze -dc VerySimple -o HTML:frames:DOM/earthli -ti "VerySimple API Documentation"

echo ******************
echo *** FINISHED! ****
echo ******************
pause