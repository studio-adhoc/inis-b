<?php
/*-----------------------------------------------------------------------------------*/
/* Add Help Tab to Post/Page Screen
/*-----------------------------------------------------------------------------------*/
function add_page() {
	add_action('load-post-new.php', 'add_help_tab');
	add_action('load-post.php', 'add_help_tab');
	add_action('load-edit.php', 'add_help_tab');
}
add_action('admin_menu', 'add_page');

/*-----------------------------------------------------------------------------------*/
/* Generate Help Tab
/*-----------------------------------------------------------------------------------*/
function add_help_tab () {
  $screen = get_current_screen();
	$content = '';

	if (get_shortcode_help()) {
		$content = get_shortcode_help();
	}

	if ($content != '') {
	   // Add my_help_tab if current screen is My Admin Page
	   $screen->add_help_tab( array(
		   'id' => 'shortcode', //unique id for the tab
		   'title' => 'Shortcodes', //unique visible title for the tab
		   'content' => $content  //actual help text
		));
	}
}

/*-----------------------------------------------------------------------------------*/
/* Generate Shortcode Help
/*-----------------------------------------------------------------------------------*/
function get_shortcode_help() {
	global $shortcode_tags;

	$output = '';

	$shortcode_code = file_get_contents( get_template_directory() . '/inc/custom-shortcodes.php' );

	if ($shortcode_code) {
		foreach($shortcode_tags as $code => $function) {
			if (stripos( $shortcode_code, '/* Shortcode ' . $code . ': ' ) !== false) {
				preg_match('/\s*\/\* Shortcode ' . $code . ':?[^!][.\s\t\S\n\r]*?\*\//', $shortcode_code, $matches);
				if ($matches) {
					$parts = explode('/*', $matches[0]);
					$shortcode_text = str_replace('Shortcode ' . $code . ':', '', $parts[1]);
					
					if (function_exists($function . '_default_atts')) {
						$shortcode_atts = call_user_func($function . '_default_atts');
						if ($shortcode_atts) {
							$shortcode_text .= '<br />Default Attribute:<br />';
							foreach ($shortcode_atts as $attribute => $value ) {
								$shortcode_text .= $attribute . ': ' . $value . '<br />';
							}
						}
					}
					$output .= '<p><strong>[' . $code . ']</strong> ' . $shortcode_text . '</p>';
				}
			}
		}
	}

	return $output;
}
