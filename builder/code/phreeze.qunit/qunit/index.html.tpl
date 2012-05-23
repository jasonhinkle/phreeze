<!DOCTYPE html>
<html>
<head>
	<title>{$appname} Test Suite</title>
	<base href="../" />
	<link rel="stylesheet" href="qunit/qunit.css" type="text/css" media="screen">
	<script type="text/javascript" src="qunit/qunit.js"></script>

	<!-- project files -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="scripts/libs/underscore-min.js"></script>
	<script type="text/javascript" src="scripts/libs/backbone-min.js"></script>
	<script type="text/javascript" src="scripts/model.js"></script>
	<script type="text/javascript" src="scripts/app.js"></script>

	<!-- test files -->
{foreach from=$tables item=table}{if isset($tableInfos[$table->Name])}
{assign var=singular value=$tableInfos[$table->Name]['singular']}
	<script type="text/javascript" src="qunit/tests/{$singular|lower}Tests.js"></script>
{/if}{/foreach}

</head>
<body>
	<h1 id="qunit-header">{$appname} Test Suite</h1>
	<h2 id="qunit-banner"></h2>
	<div id="qunit-testrunner-toolbar"></div>
	<h2 id="qunit-userAgent"></h2>
	<ol id="qunit-tests"></ol>
</body>
</html>
