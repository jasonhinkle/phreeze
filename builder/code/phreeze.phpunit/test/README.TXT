/*
 * Phreeze Unit Test Scaffold
 *
 * This folder contains basic tests for the Phreeze application.
 * By default no data is inserted into your database, however
 * to fully test operations the tests should be customized to
 * create and update records.
 */

/* PREREQUISITES */

	sudo pear config-set auto_discover 1
	sudo pear install pear.phpunit.de/PHPUnit
	
	sudo pear channel-discover pear.phing.info
	sudo pear install --alldeps phing/phing

/* USAGE */

	sh /path/to/runner.sh
	
	
/* SELENIUM SETUP */

1. Install the Selenium IDE 2.4.0 Firefox add-on

2. Installl the Selenium PHP Formatter for Firefox

	> https://addons.mozilla.org/en-US/firefox/addon/selenium-ide-php-formatters/

3. Install Selenium's ChromeDriver
	
	Chrome, by itself, does not support selenium's WebDriver functionality.
	Follow and complete the steps below on all systems that will run tests within Chrome.

	Download the executable
	> https://code.google.com/p/selenium/wiki/ChromeDriver
	> copy to /usr/bin

4. Install PHPUnit using Pear
	
	PHPUnit Documentation:			http://phpunit.de/manual/3.7/en/installation.html#installation
	Pear package documentation: 	http://pear.phpunit.de/

	$ cd /path/to/php/binaries  # (ex. /Applications/MAMP/bin/php/php5.4.10/bin)
	$ sudo ./pear channel-update pear.php.net
	$ sudo ./pear upgrade pear
	$ sudo ./pear channel-discover pear.phpunit.de
	$ sudo ./pear install pear.phpunit.de/PHPUnit

5. Install the Selenium extension for PHPunit
	
	$ sudo ./pear install phpunit/PHPUnit_Selenium

6. (Optional for MAMP - link phpunit binary to add it to your path)
	
	$ sudo ln -s /Applications/MAMP/bin/php/php5.4.10/bin/phpunit /usr/local/bin/phpunit

7. Test PHPUnit installation

	$ phpunit --version

	// Returns:
	PHPUnit 3.7.28 by Sebastian Bergmann.


Start the Selenium Grid2 Hub and Grid2 Node
-------------------------------------------
- Requires Java
- This assumes the Grid Hub and Grid Node are running on the same machine. Final implementation may differ.


Start a Grid Hub
-----------------

1. Download the standalone Selenium-Server java service:
	
	Location: https://code.google.com/p/selenium/downloads/list
	
	File: selenium-server-standalone-X.XX.X.jar

2. Run the service as a Hub

	$ java -jar ~/Documents/Selenium/selenium-2.37.0/selenium-server-standalone-2.37.0.jar -role hub

3. Confirm the Hub is running and available.
	
	Open: http://localhost:4444/grid

	// Displays:
	You are using grid 2.37.0
	Find help on the official selenium wiki : more help here
	default monitoring page : console


Start a Grid Node
-----------------

1. Download the standalone Selenium-Server java service:
	
	Location: https://code.google.com/p/selenium/downloads/list
	
	File: selenium-server-standalone-X.XX.X.jar

2. Run the service as a Node

	$ java -jar ~/Documents/Selenium/selenium-2.37.0/selenium-server-standalone-2.37.0.jar -role node -hub http://localhost:4444/grid/register

3. Confirm the Node is running and registered with the Hub.
	
	Open: http://localhost:4444/grid

	Page displauys a listing of nodes and their configured browsers.
	Example: "id : http://192.168.1.17:5555, OS : MAC"

