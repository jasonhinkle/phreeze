{literal}
{extends file="Master.tpl"}

{block name=title}Page Not Found{/block}

{block name=banner}
	<h1>Page Not Found</h1>
{/block}

{block name=content}

	<!-- this is used by app.js for scraping -->
	<!-- ERROR The page you requested was not found /ERROR -->

	<p>The page you requested was not found.  Please check that you typed the URL correctly.</p>

{/block}
{/literal}