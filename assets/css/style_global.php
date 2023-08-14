<?php
	header('Content-Type: text/css');

	$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
	require_once( $parse_uri[0] . 'wp-load.php' );

	$style = '';
	$raw_style = '';

	ob_start();
	echo do_action('inis_b_global_styles');
	$raw_style = ob_get_contents();
	ob_end_clean();

	if ($raw_style != '') {
		$style .= strip_tags($raw_style);
	}

echo $style;
?>
