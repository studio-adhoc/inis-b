<?php
	header('Content-Type: text/css');

	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once( $parse_uri[0] . 'wp-load.php' );

	$style = '';

	global $wp;
	$url = home_url($wp->request);
	$pID = url_to_postid($url);
	$style .= inis_b_custom_color_image($pID);

echo $style;
?>
