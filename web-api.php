<?php
/*
   Plugin Name: Web Api
   Plugin URI: Webapi.com
   Description: a plugin to consume southtravels api
   Version: 1.0
   Author: Neil Salazar
   Author URI: 
   License: GPL2
   */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // don't access directly
};


/*** Register Hook ***/

/* Runs when plugin is activated */
register_activation_hook(__FILE__,'consume_rest_api_install'); 

/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, 'consume_rest_api_remove' );

function consume_rest_api_install(){
	add_option("api_token", 'token', '', 'yes');
	add_option("end_point_1", 'endpoint', '', 'yes');
	add_option("end_point_2", 'endpoint', '', 'yes');
	add_option("end_point_3", 'endpoint', '', 'yes');
	add_option("end_point_4", 'endpoint', '', 'yes');
	add_option("end_point_5", 'endpoint', '', 'yes');
	flush_rewrite_rules();
}

function consume_rest_api_remove(){
	delete_option("api_token");
	delete_option("end_point_1");
	delete_option("end_point_2");
	delete_option("end_point_3");
	delete_option("end_point_4");
	delete_option("end_point_5");
}

add_action('activated_plugin','save_error');
function save_error(){
	update_option('plugin_error',  ob_get_contents());
}


if ( is_admin() ){

	/* Call the html code */
	add_action('admin_menu', 'rest_api_admin_menu');
	function rest_api_admin_menu() {
		add_options_page('Rest API', 'Rest API', 'administrator',
						 'rest_api_consume', 'rest_api_admin_menu_page');
	}
}

function rest_api_admin_menu_page(){ ?>
<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<h2>Consume REST API</h2>
	<div class="rest_api_consume">
		<?php echo get_option('token'); ?>
		<label>API Token</label>
		<input type="text" name="api_token" id="api_token" value="<?php echo get_option('api_token'); ?>" style="width: 500px;"><br>
		<label>End Point 1 (end_point_1)</label>
		<input type="text" name="end_point_1" id="end_point_1" value="<?php echo get_option('end_point_1'); ?>" style="width: 500px;"><br>
		<label>End Point 2 (end_point_2)</label>
		<input type="text" name="end_point_2" id="end_point_2" value="<?php echo get_option('end_point_2'); ?>" style="width: 500px;"><br>
		<label>End Point 3 (end_point_3)</label>
		<input type="text" name="end_point_3" id="end_point_3" value="<?php echo get_option('end_point_3'); ?>" style="width: 500px;"><br>
		<label>End Point 4 (end_point_4)</label>
		<input type="text" name="end_point_4" id="end_point_4" value="<?php echo get_option('end_point_4'); ?>" style="width: 500px;"><br>
		<label>End Point 5 (end_point_5)</label>
		<input type="text" name="end_point_5" id="end_point_5" value="<?php echo get_option('end_point_5'); ?>" style="width: 500px;">
	</div>

	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="api_token, end_point_1, end_point_2, end_point_3, end_point_4, end_point_5" />
	<br />
	<input type="submit" value="<?php _e('Save Changes') ?>" />
</form>
<blockquote>
	<strong>USAGE:</strong><br />
	<p><em>consumeRestAPI( <EM>PARAMETER</EM> , <EM>ENDPOINT</EM>)</em></p>
	<h3>PARAMETER GUIDE</h3>
	<table style="width: 400px;">
		<tr>
			<td><H4>DATA</H4>
				<P>tours, packages, page, promotions</P>
			</td>
			<td>
				<h4>Lists</h4>
				<p>{slug}</p>
			</td>
			<td>
				<h4>Detail</h4>
				<p>{slug}</p>
			</td>
		</tr>
	</table>
	
</blockquote>
<?php }

function consumeRestAPI($type ,$endpoint){
	 
	$authorization = "Authorization: Bearer " .get_option('api_token');

	//$url = 'http://laravelapi.dev/api/data/' .$type;
	$url = get_option($endpoint).$type;

	$cURL = curl_init();
	curl_setopt($cURL, CURLOPT_URL, $url);
	curl_setopt($cURL, CURLOPT_HTTPGET, true);
	curl_setopt($cURL, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Accept: application/json', $authorization,
	));
	$result = curl_exec($cURL);
	curl_close($cURL);
	$json = json_decode($result);
	return $json;
}

/*
*	VIRTUAL PAGES
*/

function vp_qry_vars($vars)
{
	$vars[] = 'virtualpage';
	return $vars;
}
add_filter('query_vars', 'vp_qry_vars');

function vp_add_rewrite_rules() {
  add_rewrite_tag('%virtualpage%', '([^&]+)');
  add_rewrite_rule(
    'test/?$',
    'index.php?test',
    'top'
  );
}
add_action('init', 'vp_add_rewrite_rules');


function vp_template_include($template) {
  global $wp_query;
  $new_template = '';
 
  if (array_key_exists('virtualpage', $wp_query->query_vars)) {
    switch ($wp_query->query_vars['virtualpage']) {
      case 'tests':
        // We expect to find virtualpage-interesting-things.php in the 
        // currently active theme.
        $new_template = locate_template(array(
          'vp-page.php'
        ));
        break;
    }
 
    if ($new_template != '') {
      return $new_template;
    } else {
      // This is not a valid virtualpage value, so set the header and template
      // for a 404 page.
      $wp_query->set_404();
      status_header(404);
      return get_404_template();
    }
  }
 
  return $template;
}
add_filter('template_include', 'vp_template_include');	

?>