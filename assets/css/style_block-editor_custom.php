<?php
	header('Content-Type: text/css');

	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once( $parse_uri[0] . 'wp-load.php' );

	$style = '';

	$style .= inis_b_custom_backend_color_image();

	echo $style;
?>
