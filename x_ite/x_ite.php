<?php
/*
Plugin Name: X_ITE
Plugin URI: http://wordpress.org/plugins/x_ite/
Description: [X3DCanvas src="https://cdn.rawgit.com/create3000/Library/master/Examples/X_ITE/info.x3d" width="100%" height="500"] shortcode
Version: 1.0.4
Author: create3000
Author URI: http://create3000.de/x_ite/
License: GPLv3
*/

define ('X_ITE_X3D_PLUGIN_VERSION', '1.0');

if (is_admin ()) :

add_action ('admin_menu', 'x_ite_admin_add_page');

function x_ite_admin_add_page ()
{
	add_options_page ('X_ITE Page', 'X_ITE', 'manage_options', 'x_ite', 'x_ite_options_page');

	add_action ('admin_init', 'x_ite_register_settings');
}

function x_ite_register_settings ()
{
 	register_setting ('x_ite_options', 'x_ite_options_debug');
	register_setting ('x_ite_options', 'x_ite_options_cache');
	register_setting ('x_ite_options', 'x_ite_options_include');
 	register_setting ('x_ite_options', 'x_ite_options_host');
 	register_setting ('x_ite_options', 'x_ite_options_version');
 	register_setting ('x_ite_options', 'x_ite_options_minified', array ('default' => 'true'));
}

function x_ite_versions ()
{
	// Fetch versions.

	$ch = curl_init ();
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($ch, CURLOPT_HTTPHEADER,     array ('User-Agent: PHP'));
	curl_setopt ($ch, CURLOPT_URL,            'https://api.github.com/repos/create3000/x_ite/tags');
	$result = curl_exec ($ch);
	curl_close ($ch);

	// Extract version names.

	$versions = array ();

	foreach (json_decode ($result) as $version)
	{
		if ($version -> name != 'latest')
			array_push ($versions, $version -> name);
	}

	return $versions;
}

function x_ite_options_page ()
{
	$debug    = get_option ('x_ite_options_debug');
	$cache    = get_option ('x_ite_options_cache');
	$include  = get_option ('x_ite_options_include');
	$host     = get_option ('x_ite_options_host');
	$version  = get_option ('x_ite_options_version');
	$minified = get_option ('x_ite_options_minified');

	if ($host    == '') $host    = '0';
	if ($version == '') $version = 'latest';

	$versions = x_ite_versions ();
?>

<div>
	<h1>X_ITE X3D Browser Preferences</h1>
	
	<fieldset>
		<legend><h2>Links</h2></legend>
		<p>
			<a href="https://github.com/create3000/x_ite/wiki" target="_blank">Home Page</a> |
			<a href="https://github.com/create3000/x_ite" target="_blank">GitHub</a>
		</p>
	</fieldset>

	<form action="options.php" method="post">
		<?php settings_fields ('x_ite_options'); ?>
		<?php do_settings_sections ('x_ite_options'); ?>

 		<fieldset>
 			<legend><h2>Plugin Options</h2></legend>
			<table class="form-table">

				<tr>
					<th scope="row">Host</th>
					<td>
						<select name="x_ite_options_host">
							<option value="0" <?php if ($host == '0' ) echo 'selected="selected"'; ?>>https://create3000.github.io</option>
							<option value="1" <?php if ($host == '1' ) echo 'selected="selected"'; ?>>https://cdn.jsdelivr.net</option>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">Version</th>
					<td>
						<select name="x_ite_options_version" size="5" style="height:100px">
							<optgroup label="For Production">
								<?php foreach ($versions as $value) : ?>
								<option value="<?php echo $value; ?>" <?php if ($value == $version) echo 'selected="selected"'; ?>><?php echo $value; ?></option>
								<?php endforeach ; ?>
							</optgroup>
							<optgroup label="For Developer">
								<option value="latest" <?php if ('latest' == $version) echo 'selected="selected"'; ?>><?php echo 'latest'; ?></option>
								<option value="alpha"  <?php if ('alpha'  == $version) echo 'selected="selected"'; ?>><?php echo 'alpha';  ?></option>
							</optgroup>
						</select>
						<br/>
						<small>Always use a tagged version for production!</small>
					</td>
				</tr>

				<tr>
					<th scope="row">Always Include External CSS and JavaScript</th>
					<td>
						<input type="checkbox" name="x_ite_options_include" value="true" <?php if ($include) echo 'checked'; ?>/>
					</td>
				</tr>

				<tr>
					<th scope="row">Enable Debug Mode</th>
					<td>
						<input type="checkbox" name="x_ite_options_debug" value="true" <?php if ($debug) echo 'checked'; ?>/>
					</td>
				</tr>

				<tr>
					<th scope="row">Disable Browser Cache</th>
					<td>
						<input type="checkbox" name="x_ite_options_cache" value="true" <?php if ($cache) echo 'checked'; ?>/>
					</td>
				</tr>

				<tr>
					<th scope="row">Inlcude minified version</th>
					<td>
						<input type="checkbox" name="x_ite_options_minified" value="true" <?php if ($minified) echo 'checked'; ?>/>
					</td>
				</tr>

			</table>
		</fieldset>

		<input id="submit" class="button-primary" type="submit" value="<?php esc_attr_e ('Save Changes'); ?>" />
	</form>

	<fieldset>
		<legend><h2>Examples</h2></legend>
		
		<pre style="display: inline-block; margin: 0px; border: 1px solid; padding: 5px;">[X3DCanvas src="example.x3d" class="check-out-x_ite" splashScreen="true" notifications="true" timings="true" contextMenu="true"]</pre>

		<p>The shortcode may have fallback content.</p>
		<pre style="display: inline-block; margin: 0px; border: 1px solid; padding: 5px;">[X3DCanvas]Fallback Content[/X3DCanvas]</pre>
	</fieldset>
</div>
 
<?php
}

else :

add_action ('wp_enqueue_scripts', 'x_ite_enqueue_scripts');

function x_ite_enqueue_scripts ()
{
	global $post;

	$include  = get_option ('x_ite_options_include');

	if (!($include == 'true' || has_shortcode ($post -> post_content, 'X3DCanvas')))
		return;

	x_ite_enqueue_scripts_impl ();
}

function x_ite_enqueue_scripts_impl ()
{
	$host     = get_option ('x_ite_options_host');
	$version  = get_option ('x_ite_options_version');
	$minified = get_option ('x_ite_options_minified');

	if ($host    == '') $host    = '0';
	if ($version == '') $version = 'latest';

	$hosts = array (
		'0' => 'https://create3000.github.io/code/x_ite/VERSION/dist/',
		'1' => 'https://cdn.jsdelivr.net/gh/create3000/x_ite@VERSION/dist/',
	);

	if ($version == 'alpha')
		$host = $hosts [0];
	else
		$host = $hosts [$host];

	$url = preg_replace ('/VERSION/', esc_attr ($version), $host);
	$min = $minified == '' && $minified !== false ? '' : '.min';

	wp_enqueue_style  ('x_ite', $url . 'x_ite.css');
	wp_enqueue_script ('x_ite', $url . 'x_ite' . $min . '.js');
}

add_shortcode ('X3DCanvas', 'x_ite_plugin_add_shortcode_cb');

function x_ite_plugin_add_shortcode_cb ($attributes, $content = null)
{
	// If not previously enqueued, do it now.
	x_ite_enqueue_scripts_impl ();

	// Must use array_merge.
	$attributes = array_merge (array (
		'src'   => 'https://cdn.rawgit.com/create3000/Library/master/Examples/X_ITE/info.x3d',
		'class' => 'x_ite-browser',
	), $attributes);

	if (get_option ('x_ite_options_debug'))
		$attributes ['debug'] = 'true';

	if (get_option ('x_ite_options_cache'))
		$attributes ['cache'] = 'false';

	$html  = "\n".'<!-- X_ITE Plugin v' . X_ITE_X3D_PLUGIN_VERSION . ' wordpress.org/plugins/x_ite/ -->'."\n";
	$html .= '<X3DCanvas';

	foreach ($attributes as $attr => $value)
	{
		// remove some attributes

		if ($value != '')
		{
			// adding all attributes
			$html .= ' ' . esc_attr ($attr) . '="' . esc_attr ($value) . '"';
		}
		else
		{
			// adding empty attributes
			$html .= ' ' . esc_attr ($attr);
		}
	}

	$html .= '>' . $content . '</X3DCanvas>' . "\n";

	return do_shortcode ($html);
}

add_filter ('plugin_row_meta', 'x_ite_plugin_row_meta_cb', 10, 2);

function x_ite_plugin_row_meta_cb ($links, $file)
{
	if ($file == plugin_basename ( __FILE__ ))
	{
		$row_meta = array (
			'support' => '<a href="http://create3000.de/x_ite/" target="_blank"><span class="dashicons dashicons-editor-help"></span> ' . __( 'X3DCanvas', 'X3DCanvas' ) . '</a>',
			'donate'  => '<a href="http://create3000.de/x_ite/" target="_blank"><span class="dashicons dashicons-heart"></span> ' . __( 'Donate', 'X3DCanvas' ) . '</a>'
		);

		$links = array_merge ($links, $row_meta);
	}

	return (array) $links;
}

endif; // ! admin

