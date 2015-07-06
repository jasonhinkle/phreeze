# Phreeze Unit Test Scaffold

This folder contains basic tests for the Phreeze application. By default no data is inserted into your database, however to fully test operations the tests should be customized to create and update records. A DB fixture should be created for testing purposes.

## PREREQUISITES

* Install PHPUnit: [https://phpunit.de/getting-started.html]()
	
* (For code coverage) Install and Enable XDebug: [http://xdebug.org/docs/install]()
	
### Troubleshooting:
	
* If there is an error during installation regarding autoconf the run 
<pre><code>brew install autoconf</code></pre>

## USAGE

### Run unit tests

<pre><code>sh /path/to/runner.sh</code></pre>
	
### Run unit tests and generate code coverage report

<pre><code>sh /path/to/coverage.sh</code></pre>

## (OPTIONAL) SELENIUM SETUP USING FIREFOX

1. Download and install FireFox
2. Install Selenium IDE for test-writing purposes
3. Install Selenium PHP Formatter for either version 1 or 2
  * https://github.com/suzuki/PHP-Formatter-PHPUnit_Selenium2
2. Download selenium standalone .jar file
3. Launch selenium server:
<pre><code>java -jar selenium-server-standalone-2.46.0.jar</code></pre>

NOTE: Either PHPUnit_Extensions_SeleniumTestCase or PHPUnit_Extensions_Selenium2TestCase can be used.

## (OPTIONAL) SELENIUM SETUP USING PHANTOMJS

1. Download latest selenium standalone .jar file
2. Download latest phantomjs, unzip and install the binary:
<pre><code>cp /path/to/bin/phantomjs /usr/local/bin/phantomjs
chmod +x /usr/local/bin/phantomjs</code></pre>
   
3. Launch selenium server in "hub" mode
<pre><code>java -jar selenium-server-standalone-2.46.0.jar -role hub</code></pre>
   
4. Start phantomjs in webdriver mode
<pre><code>phantomjs --webdriver=8080 --webdriver-selenium-grid-hub=http://127.0.0.1:4444</code></pre>

NOTE: All test suites using the driver must implement PHPUnit_Extensions_Selenium2TestCase (not PHPUnit_Extensions_SeleniumTestCase)

### Troubleshooting:
   
* if phantomjs dies with message "Killed 9" then run the following 
<pre><code>brew install upx
upx -d /usr/local/bin/phantomjs</code></pre>
   
* if phantom dies with "main.fail" see: [https://gist.github.com/neuro-sys/41e368839a9b20dafb34](). Pre-built hotpatched versions of phantomjs for OSX and Ubuntu are available at: 
  * [http://phreeze.com/assets/phantomjs-2.0.0-macosx-hotpatch.zip]()
  * [http://phreeze.com/assets/phantomjs-2.0.0-ubuntu-hotpatch.zip]()

