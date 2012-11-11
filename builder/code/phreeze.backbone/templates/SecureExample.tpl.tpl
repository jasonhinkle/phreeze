{literal}
{extends file="Master.tpl"}

{block name=title}{/literal}{$appname|escape}{literal} Secure Example{/block}

{block name=banner}
{/block}

{block name=content}


	{if ($feedback)}
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{$feedback|escape}
		</div>
	{/if}
	
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	{if ($page == 'login')}
	
		<div class="hero-unit">
			<h1>Login Example</h1>
			<p>This is an example of Phreeze authentication.  The default credentials are <strong>demo/pass</strong> and <strong>admin/pass</strong>.</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visit User Page</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visit Admin Page</a>
				{if (isset($currentUser))}
					<a href="logout" class="btn btn-primary btn-large">Logout</a>
				{/if}
			</p>
		</div>
	
		<form class="well" method="post" action="login">
			<fieldset>
			<legend>Enter your credentials</legend>
				<div class="control-group">
				<input id="username" name="username" type="text" placeholder="Username..." />
				</div>
				<div class="control-group">
				<input id="password" name="password" type="password" placeholder="Password..." />
				</div>
				<div class="control-group">
				<button type="submit" class="btn btn-primary">Login</button>
				</div>
			</fieldset>
		</form>
	
	{else}
	
		<div class="hero-unit">
			<h1>Secure {if ($page == 'userpage')}User{else}Admin{/if} Page</h1>
			<p>This page is accessible only to {if ($page == 'userpage')}authenticated users{else}administrators{/if}.  
			You are currently logged in as '<strong>{$currentUser->Username|escape}</strong>'</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visit User Page</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visit Admin Page</a>
				<a href="logout" class="btn btn-primary btn-large">Logout</a>
			</p>
		</div>
	{/if}

{/block}
{/literal}