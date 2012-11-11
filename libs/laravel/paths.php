<?php 

/**
 * A global path helper function.
 *
 * <code>
 *     $storage = path('storage');
 * </code>
 *
 * @param  string  $path
 * @return string
 */
function path($path)
{
	return $GLOBALS['laravel_paths'][$path];
}

/**
 * A global path setter function.
 *
 * @param  string  $path
 * @param  string  $value
 * @return void
 */
function set_path($path, $value)
{
	$GLOBALS['laravel_paths'][$path] = $value;
}

?>