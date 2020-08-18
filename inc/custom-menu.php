<?php
/*-----------------------------------------------------------------------------------*/
/* Add Custom Class to Navi Elements
/*-----------------------------------------------------------------------------------*/
function add_menu_custom_class( $items ) {
	global $post;

	if (is_singular()) {
		$post_link = get_permalink($post->ID) . '#';
		foreach ( $items as $item ) {
			if (strpos($item->url, $post_link) !== false) {
				$item->classes[] = 'intern';
			}
		}
	}

	if (is_internal()) {
		foreach ( $items as $item ) {
			if ($item->object_id == get_theme_mod('inis_b_login_internal')) {
				$item->classes[] = 'current_page_parent';
			}
			if ( get_option('page_for_posts') && $item->object_id == get_option('page_for_posts') ) {
				if (($key = array_search('current_page_parent', $item->classes)) !== false) {
    			unset($item->classes[$key]);
				}
			} elseif ($item->url == get_bloginfo( 'url' )) {
				if (($key = array_search('current_page_parent', $item->classes)) !== false) {
    			unset($item->classes[$key]);
				}
			}
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'add_menu_custom_class' );

/*-----------------------------------------------------------------------------------*/
/* Add Logout to Service Navi
/*-----------------------------------------------------------------------------------*/
function autov_add_loginout_navitem($items) {
	if ( is_user_logged_in() && ! current_user_can('edit_posts') ) {
		$items .= '<li class="menu-item logout"><a href="' . wp_logout_url( home_url() ) .'">' . __( 'Logout','inis-b' ) . '</a></li>';
  }
	return $items;
}
add_filter('wp_nav_menu_servicenavigation_items', 'autov_add_loginout_navitem');
