<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<base href="<?php $this->eprint($this->ROOT_URL) ?>" />
		<title>Phreeze | Builder</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Phreeze Framework for PHP - Application Builder" />
		<meta name="author" content="phreeze.com" />

		<!-- Le styles -->
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="styles/builder.css" rel="stylesheet" />
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link href="bootstrap/css/font-awesome.css" rel="stylesheet" />

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	</head>

	<body>
			<div class="navbar navbar-inverse navbar-fixed-top">
				<div class="navbar-inner">
					<div class="container">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</a>
						<?php if (!isset($this->hidenav)) { ?>
						<a class="brand" href="./"><img src="images/phreeze.png" alt="Phreeze" /> BUILDER</a>
						<?php } ?>
						<div class="nav-collapse collapse">
							<ul class="nav">
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>
			
			
			<div class="container">
