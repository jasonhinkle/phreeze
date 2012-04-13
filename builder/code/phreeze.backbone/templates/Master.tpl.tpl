<!DOCTYPE html>
<html lang="en">
	<head>
	{ldelim}block name=header{rdelim}
		<meta charset="utf-8">
		<base href="{ldelim}$ROOT_URL{rdelim}" />
		<title>{ldelim}block name=title{rdelim}{$appname|upper}{ldelim}/block{rdelim}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="{$appname} auto-generated application" />
		<meta name="author" content="phreeze.com" />

		<!-- Le styles -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="styles/{$appname|lower}.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link href="bootstrap/css/font-awesome.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-datepicker.css" rel="stylesheet" />

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />
	{ldelim}/block{rdelim}

	{ldelim}block name=customHeader{rdelim}
	{ldelim}/block{rdelim}

	</head>

	<body>

		{ldelim}block name=navbar{rdelim}

			{ldelim}if !isset($nav){rdelim}{ldelim}assign var="nav" value="home"{rdelim}{ldelim}/if{rdelim}

			<div class="navbar navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="./">{$appname|upper}</a>
						<div class="nav-collapse">
							<ul class="nav">
								<li {ldelim}if $nav=='home'{rdelim} class="active"{ldelim}/if{rdelim}><a href="./">Home</a></li>
{foreach from=$tables item=table}{if isset($tableInfos[$table->Name])}
								<li {ldelim}if $nav=='{$tableInfos[$table->Name]['plural']|lower}'{rdelim} class="active"{ldelim}/if{rdelim}><a href="./{$tableInfos[$table->Name]['plural']|lower}">{$tableInfos[$table->Name]['plural']}</a></li>
{/if}{/foreach}
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>
		{ldelim}/block{rdelim}

		{ldelim}block name=container{rdelim}
			<div class="container">

				{ldelim}block name="banner"{rdelim}
					<h1>{$appname|upper}</h1>
				{ldelim}/block{rdelim}

				{ldelim}block name="content"{rdelim}
				{ldelim}/block{rdelim}

				<hr>

				<footer>
					<p>&copy; {ldelim}$smarty.now|date_format:'%Y'{rdelim} {$appname|upper}</p>
				</footer>

			</div> <!-- /container -->

		{ldelim}/block{rdelim}

		{ldelim}block name=footerScripts{rdelim}
			<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
			<script type="text/javascript" src="scripts/libs/underscore-min.js"></script>
			<script type="text/javascript" src="scripts/libs/underscore.date.min.js"></script>
			<script type="text/javascript" src="scripts/libs/backbone-min.js"></script>
			<script type="text/javascript" src="scripts/libs/jquery.scrollIntoView.min.js"></script>

			<!-- Le javascript
			================================================== -->
			<!-- Placed at the end of the document so the pages load faster -->
			<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="bootstrap/js/bootstrap-datepicker.js"></script>

		{ldelim}/block{rdelim}

		{ldelim}block name=customFooterScripts{rdelim}
		{ldelim}/block{rdelim}

	</body>
</html>