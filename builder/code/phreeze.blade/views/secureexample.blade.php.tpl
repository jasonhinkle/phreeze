@layout('Master')

@section('title'){$appname} | Secure Example@endsection

@section('content')
<div class="container">

	@if (isset($feedback))
		<div class="alert alert-error">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{ldelim}{ldelim} htmlentities($feedback) {rdelim}{rdelim}
		</div>
	@endif
	
	<!-- #### this view/tempalate is used for multiple pages.  the controller sets the 'page' variable to display differnet content ####  -->
	
	@if ($page == 'login')
	
		<div class="hero-unit">
			<h1>Login Example</h1>
			<p>This is an example of Phreeze authentication.  The default credentials are <strong>demo/pass</strong> and <strong>admin/pass</strong>.</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visit User Page</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visit Admin Page</a>
				@if (isset($currentUser))
					<a href="logout" class="btn btn-primary btn-large">Logout</a>
				@endif
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
	
	@else
	
		<div class="hero-unit">
			<h1>Secure @if ($page == 'userpage') User @else Admin @endif Page</h1>
			<p>This page is accessible only to @if ($page == 'userpage') authenticated users @else administrators @endif.  
			You are currently logged in as '<strong>{ldelim}{ldelim} htmlentities($currentUser->Username) {rdelim}{rdelim}</strong>'</p>
			<p>
				<a href="secureuser" class="btn btn-primary btn-large">Visit User Page</a>
				<a href="secureadmin" class="btn btn-primary btn-large">Visit Admin Page</a>
				<a href="logout" class="btn btn-primary btn-large">Logout</a>
			</p>
		</div>
	@endif

</div> <!-- /container -->
@endsection
