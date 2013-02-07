{literal}
@layout('Master')

@section('title')Error@endsection

@section('content')
<div class="container">

	<h1>Oh Snap!</h1>

	<!-- this is used by app.js for scraping -->
	<!-- ERROR {{htmlentities($message)}} /ERROR -->

	<h2><i class="icon-cogs"></i> Oh Snap!</h2>

	<h3 onclick="$('#stacktrace').show('slow');" class="well" style="cursor: pointer;">{{htmlentities($message)}}</h3>

	<p>You may want to try returning to the the previous page and verifying that
	all fields have been filled out correctly.</p>

	<p>If you continue to experience this error please contact support.</p>

	<div id="stacktrace" class="well hide">
		<p style="font-weight: bold;">Stack Trace:</p>
		@if (isset($stacktrace))
			<p style="white-space: nowrap; overflow: auto; padding-bottom: 15px; font-family: courier new, courier; font-size: 8pt;">{{htmlentities($stacktrace)}}</p>
		@endif
	</div>

</div> <!-- /container -->
@endsection
{/literal}