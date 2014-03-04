<!DOCTYPE html>
<html lang="en">
	<head>
	{ldelim}block name=header{rdelim}
		<meta charset="utf-8">
		<meta http-equiv="X-Frame-Options" content="deny">
		<base href="{ldelim}$ROOT_URL{rdelim}" />
		<title>{ldelim}block name=title{rdelim}{$appname}{ldelim}/block{rdelim}</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="{$appname}" />
		<meta name="author" content="phreeze builder | phreeze.com" />

		<!-- Le styles -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="styles/style.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link href="bootstrap/css/font-awesome.min.css" rel="stylesheet" />
		<!--[if IE 7]>
		<link rel="stylesheet" href="bootstrap/css/font-awesome-ie7.min.css">
		<![endif]-->
		<link href="bootstrap/css/datepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/timepicker.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-combobox.css" rel="stylesheet" />
		
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<link rel="shortcut icon" href="images/favicon.ico" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
		<link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

		<script type="text/javascript" src="scripts/libs/LAB.min.js"></script>
		<script type="text/javascript">
			$LAB
				.script("//code.jquery.com/jquery-1.8.2.min.js").wait()
				.script("bootstrap/js/bootstrap.min.js")
				.script("bootstrap/js/bootstrap-datepicker.js")
				.script("bootstrap/js/bootstrap-timepicker.js")
				.script("bootstrap/js/bootstrap-combobox.js")
				.script("scripts/libs/underscore-min.js").wait()
				.script("scripts/libs/underscore.date.min.js")
				.script("scripts/libs/backbone-min.js")
				.script("scripts/app.js")
				.script("scripts/model.js").wait()
				.script("scripts/view.js").wait()
		</script>

	{ldelim}/block{rdelim}

	{ldelim}block name=customHeader{rdelim}
	{ldelim}/block{rdelim}

	</head>

	<body>

		{ldelim}block name=navbar{rdelim}

			{ldelim}if !isset($nav){rdelim}{ldelim}assign var="nav" value="home"{rdelim}{ldelim}/if{rdelim}

			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<a class="brand" href="./">{$appname}</a>
						<div class="nav-collapse collapse">
							<ul class="nav">
{foreach from=$selectedTables item=table name=ddForEach}{if isset($tableInfos[$table->Name])}
{if $smarty.foreach.ddForEach.index == $max_items_in_topnav && !$smarty.foreach.ddForEach.last}
							</ul>
							<ul class="nav">
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
								<ul class="dropdown-menu">
{/if}
								<li {ldelim}if $nav=='{$tableInfos[$table->Name]['plural']|lower}'{rdelim} class="active"{ldelim}/if{rdelim}><a href="./{$tableInfos[$table->Name]['plural']|lower}">{$tableInfos[$table->Name]['plural']}</a></li>
{if $smarty.foreach.ddForEach.last && $smarty.foreach.ddForEach.index != $max_items_in_topnav}
								</ul>
								</li>
{/if}
{/if}{/foreach}
							</ul>

							<ul class="nav pull-right">
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-lock"></i> Login <i class="caret"></i></a>
								<ul class="dropdown-menu">
									<li><a href="./loginform">Login</a></li>
									<li><a href="./secureuser">Example User Page <i class="icon-lock"></i></a></li>
									<li><a href="./secureadmin">Example Admin Page <i class="icon-lock"></i></a></li>
								</ul>
								</li>
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>
		{ldelim}/block{rdelim}

		{ldelim}block name=container{rdelim}
			<div class="container">

				{ldelim}block name="banner"{rdelim}
					<h1>{$appname}</h1>
				{ldelim}/block{rdelim}

				{ldelim}block name="content"{rdelim}
				{ldelim}/block{rdelim}

				<hr>

				<footer>
					<p class="muted"><small>&copy; {ldelim}$smarty.now|date_format:'%Y'{rdelim} {$appname}</small></p>
				</footer>

			</div> <!-- /container -->

		{ldelim}/block{rdelim}

		{ldelim}block name=customFooterScripts{rdelim}
		{ldelim}/block{rdelim}

	</body>
</html>