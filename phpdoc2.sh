# PHREEZE DOCUMENTATION GENERATOR
# This script is for user with phpdoc v2 only.
# If you have phpdoc v1 installed then use phpdoc1.sh
# instead of this script.
#
# Prerequisites:
# 	sudo pear channel-discover pear.phpdoc.org
#	sudo pear install phpdoc/phpDocumentor-alpha 
#
# Configuration settings are located in phpdoc.dist.xml

rm -rfd docs
phpdoc
chmod -R 755 docs
