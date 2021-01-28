<?php
/*-----------------------------------------------------------------------------------*/
/* Remove Generator Tag
/*-----------------------------------------------------------------------------------*/
remove_action('wp_head', 'wp_generator');

/*-----------------------------------------------------------------------------------*/
/* Remove Header Elements
/*-----------------------------------------------------------------------------------*/
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

/*-----------------------------------------------------------------------------------*/
/* Remove Emoji Support
/*-----------------------------------------------------------------------------------*/
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

/*-----------------------------------------------------------------------------------*/
/* Remove Recent Comment Inline CSS
/*-----------------------------------------------------------------------------------*/
function remove_recent_comments_style() {
  global $wp_widget_factory;
  remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
}
add_action('widgets_init', 'remove_recent_comments_style');

/*-----------------------------------------------------------------------------------*/
/* Remove Comments from Pages
/*-----------------------------------------------------------------------------------*/
function remove_comments_on_pages() {
	remove_post_type_support( 'page', 'comments' );
}
add_action( 'init', 'remove_comments_on_pages' );

/*-----------------------------------------------------------------------------------*
/* Remove p around images
/*-----------------------------------------------------------------------------------*/
function filter_ptags_on_images($content){
  return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}
add_filter('the_content', 'filter_ptags_on_images');
add_filter('acf_the_content', 'filter_ptags_on_images', 30);

/*-----------------------------------------------------------------------------------*
/* Remove REST API for internal post type if user is not logged in
/*-----------------------------------------------------------------------------------*/
function rest_only_for_authorized_users($wp_rest_server){
  global $wp;

  if ( isset($wp->request) && strpos($wp->request, 'internal') !== false && !is_user_logged_in() ) {
    wp_die('Sorry you are not allowed to access this data','cheatin eh?',403);
  }
}
add_filter( 'rest_api_init', 'rest_only_for_authorized_users', 99 );

/*-----------------------------------------------------------------------------------*/
/* Youtube NoCookie Domain for Embeds / Cookie Box for other Embeds
/*-----------------------------------------------------------------------------------*/
function inis_b_custom_embeds( $html, $url, $attr, $post_ID ) {
	if (!is_admin() && (function_exists('cn_cookies_accepted') && !cn_cookies_accepted())) {
		$html = '<div class="box dark-box cookie-box">';
			$html .= '<h3>' . __('External Content','inis-b') . '</h3>';
			$html .= '<p>';
				$html .= __('Link','inis-b') . ': ' . $url . '<br />';
				$html .= __('You need to accept cookies to load this content.','inis-b') . '<br />';
        $html .= '<a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie cn-button button">' . __('Accept Cookies?','inis-b') . '</a>';
			$html .= '</p> ';
		$html .= '</div>';
	} elseif ( preg_match('#https?://(www\.)?youtu#i', $url) ) {
		return preg_replace(
			'#src=(["\'])(https?:)?//(www\.)?youtube\.com#i',
			'src=$1$2//$3youtube-nocookie.com',
			$html
		);
	}

	return $html;
}
add_filter( 'embed_oembed_html', 'inis_b_custom_embeds', 10, 4);

/*-----------------------------------------------------------------------------------*/
/* Youtube NoCookie Domain for Embeds / Cookie Box for other Embeds via Content Filter
/*-----------------------------------------------------------------------------------*/
function inis_b_custom_content_filter($content) {
	global $post, $blog_id;

    $output = '';

		if (!function_exists('cn_cookies_accepted') || !cn_cookies_accepted()) {
			$pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
		  preg_match_all($pattern, $content, $matches);

		  foreach ($matches[0] as $match) {
				preg_match('/src="([^"]+)"/', $match, $iframe_src);
				$src = $iframe_src[1];

		    // wrap matched iframe with div
		    $wrappedframe = '<div class="box dark-box cookie-box">';
	        $wrappedframe .= '<h3>' . __('External Content','inis-b') . '</h3>';
	        $wrappedframe .= '<p>';
						if ($src) {
							$wrappedframe .= __('Link','inis-b') . ': ' . $src . '<br />';
						}
	          $wrappedframe .= __('You need to accept cookies to load this content.','inis-b') . '<br />';
						$wrappedframe .= '<a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie cn-button button">' . __('Accept Cookies?','inis-b') . '</a>';
	        $wrappedframe .= '</p> ';
	      $wrappedframe .= '</div>';

		    //replace original iframe with new in content
		    $content = str_replace($match, $wrappedframe, $content);
		  }
		}

    $output .= $content;

    return $output;
}
add_filter('the_content', 'inis_b_custom_content_filter', 5);

/*-----------------------------------------------------------------------------------*/
/* Produces cleaner filenames for uploads
 *
 * @param  string $filename
 * @return string
/*-----------------------------------------------------------------------------------*/
function inis_b_sanitize_file_name( $filename ) {
  $sanitized_filename = remove_accents( $filename ); // Convert to ASCII

  // Standard replacements
  $invalid = array(
    ' '   => '-',
    '%20' => '-',
    '_'   => '-',
  );
  $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );

  $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
  $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
  $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
  $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
  $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase

  return $sanitized_filename;
}
add_filter( 'sanitize_file_name', 'inis_b_sanitize_file_name', 10, 1 );
