<?php
/*-----------------------------------------------------------------------------------*/
/* Add to Body Class
/*-----------------------------------------------------------------------------------*/
function custom_body_class($classes) {
	global $post, $wp_query;
	if ( is_single() && 'post' != get_post_type() ) {
		$classes[] = 'single-custom-post-type';
	}
	if ( is_tax() && 'post' != get_post_type() ) {
		$classes[] = 'post-type-archive';
	}
	if ( ( is_single() || is_page() ) && has_nav_menu('primary') ) {
		$classes[] = 'has-primary-nav';
	}
	if ( is_singular() && has_blocks( $post->post_content ) ) {
		$classes[] = 'uses-block-editor';
	}
	if ( get_theme_mod('inis_b_theme_font') ) {
		$classes[] = 'font-' . get_theme_mod('inis_b_theme_font');
	}
	if ( get_theme_mod('inis_b_banner') ) {
		$classes[] = 'has-banner';
	}
	if ( get_bloginfo( 'description' ) ) {
		$classes[] = 'has-description';
	}
	if ( is_internal() ) {
		$classes[] = 'internal-area';
	}
	return $classes;
}
add_filter('body_class', 'custom_body_class');

/*-----------------------------------------------------------------------------------*/
/* Add to Post Class
/*-----------------------------------------------------------------------------------*/
function custom_post_class($classes) {
	global $post, $wp_query;
	if ( is_archive() || is_home() || is_search() || is_singular() ) {
		$classes[] = 'single-item';
	}
	if ('internal' == get_post_type()) {
		$classes[] = 'post';
	}
	return $classes;
}
add_filter('post_class', 'custom_post_class');

/*-----------------------------------------------------------------------------------*/
/* Add to Admin Body Class
/*-----------------------------------------------------------------------------------*/
function custom_admin_body_class( $classes ) {
	$theme_font = '';

	if ( get_theme_mod('inis_b_theme_font') ) {
		$theme_font = ' font-' . get_theme_mod('inis_b_theme_font');
	}

  return $classes . $theme_font;
}

add_filter( 'admin_body_class', 'custom_admin_body_class' );
