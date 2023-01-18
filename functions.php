<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/

/*-----------------------------------------------------------------------------------*/
/* Theme Setup
/*-----------------------------------------------------------------------------------*/
function inis_b_setup() {
	load_theme_textdomain( 'inis-b', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'inis_b_setup' );

if ( ! isset( $content_width ) )
	$content_width = 1100;

/*-----------------------------------------------------------------------------------*/
/* Init Navigation
/*-----------------------------------------------------------------------------------*/
register_nav_menus( array(
	'primary' => __( 'Main Navigation', 'inis-b' ),
	'social' => __( 'Social Navigation', 'inis-b' )
) );

/*-----------------------------------------------------------------------------------*/
/* Add Admin/Editor Styles
/*-----------------------------------------------------------------------------------*/
add_editor_style( 'assets/css/style_editor.css' );

function admin_style() {
  echo '<link rel="stylesheet" type="text/css" href="'. get_bloginfo('template_directory') .'/assets/css/style_admin.css" />';
}
add_action( 'admin_head', 'admin_style' );

/*-----------------------------------------------------------------------------------*/
/* Add Theme Support
/*-----------------------------------------------------------------------------------*/
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );
add_theme_support( 'custom-logo' );
add_theme_support( 'gutenberg' );
add_theme_support( 'post-thumbnails', array( 'post','page','project','member','internal','tribe_events' ) );

/*-----------------------------------------------------------------------------------*/
/* Add Custom Image Sizes
/*-----------------------------------------------------------------------------------*/
add_image_size( 'inis-b-accordion', 300, 300, true );

/*-----------------------------------------------------------------------------------*/
/* Add Shortcodes to Text Widgets
/*-----------------------------------------------------------------------------------*/
add_filter('widget_text', 'do_shortcode');

/*-----------------------------------------------------------------------------------*/
/* Add Custom MIME Types to Upload
/*-----------------------------------------------------------------------------------*/
function custom_mime ( $svg_mime ){
	$svg_mime['svg'] = 'image/svg+xml';
	return $svg_mime;
}
add_filter( 'upload_mimes', 'custom_mime' );

/*-----------------------------------------------------------------------------------*/
/* Init Widget Areas
/*-----------------------------------------------------------------------------------*/
function widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar Blog', 'inis-b' ),
		'id' => 'sidebar-blog',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array (
		'name' => __( 'Sidebar Interal', 'inis-b' ),
		'id' => 'sidebar-internal',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array (
		'name' => __( 'Sidebar Footer', 'inis-b' ),
		'id' => 'sidebar-footer',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'init', 'widgets_init' );

/*-----------------------------------------------------------------------------------*/
/* Add Paste as Text to Tiny MCE
/*-----------------------------------------------------------------------------------*/
function tinymce_paste_as_text( $init ) {
  $init['paste_as_text'] = true;
  return $init;
}
add_filter('tiny_mce_before_init', 'tinymce_paste_as_text');

/*-----------------------------------------------------------------------------------*/
/* Styling Tag Cloud
/*-----------------------------------------------------------------------------------*/
function style_tags($args) {
	$args['largest'] = '16';
	$args['smallest'] = '8';

	return $args;
}
add_filter('widget_tag_cloud_args','style_tags');

/*-----------------------------------------------------------------------------------*/
/* Sort SRCSETs
/*-----------------------------------------------------------------------------------*/
function sort_srcset($atts) {
  if (isset($atts['srcset']) && $atts['srcset']) {
    $srcset = explode(', ', $atts['srcset']);
    $srcset_reversed = array_reverse($srcset);
    $atts['srcset'] = join(', ', $srcset_reversed);
  }
	return $atts;
}
add_filter('wp_get_attachment_image_attributes','sort_srcset',1,1);

/*-----------------------------------------------------------------------------------*/
/* Modify Excerpts
/*-----------------------------------------------------------------------------------*/
function inis_b_custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'inis_b_custom_excerpt_length', 999 );

function inis_b_new_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'inis_b_new_excerpt_more');

function inis_b_modify_content_read_more_link() {
  return '';
}
add_filter( 'the_content_more_link', 'inis_b_modify_content_read_more_link' );

function inis_b_custom_read_more_button($output) {
	if ( !has_excerpt( get_the_ID() ) && !strpos( get_post_field( 'post_content', get_the_ID() ), '<!--more-->' ) ) {
		$output = '';
	}

	return $output;
}
add_filter( 'inis_b_read_more_button', 'inis_b_custom_read_more_button' );

function inis_b_custom_before_single_post_content() { ?>
	<?php if ( is_internal() ) { internal_header(); } ?>
<?php }
add_action( 'inis_b_before_single_post_content', 'inis_b_custom_before_single_post_content' );

/*-----------------------------------------------------------------------------------*/
/* Change Thickbox text
/*-----------------------------------------------------------------------------------*/
function patch_thickbox() {
	if (is_admin()) return;

	wp_localize_script('thickbox', 'thickboxL10n', array(
		'next' => __( 'Next &rsaquo;', 'inis-b' ),
		'prev' => __( '&lsaquo; Back', 'inis-b' ),
		'image' => __( 'Picture', 'inis-b' ),
		'of' => __( 'of', 'inis-b' ),
		'close' => __( 'Close', 'inis-b' ),
		'noiframes' => __( 'Your browser is too old :-)', 'inis-b' ),
		'loadingAnimation' => includes_url('js/thickbox/loadingAnimation.gif'),
		'l10n_print_after' => 'try{convertEntities(thickboxL10n);}catch(e){};'
	));
}
add_action('wp_head', 'patch_thickbox');

/*-----------------------------------------------------------------------------------*/
/* Custom Redirects
/*-----------------------------------------------------------------------------------*/
function custom_redirect() {
	global $post;

	$login_id = get_theme_mod('inis_b_login_internal');

	if (is_page() && get_post_meta($post->ID, 'page_redirect', true)) {
		$redirect_url = get_post_meta($post->ID, 'page_redirect', true);
		wp_redirect( $redirect_url );
		exit;
	} elseif (get_theme_mod('inis_b_active_website') != '1' && !is_user_logged_in()) {
		wp_redirect( admin_url() );
		exit;
	} elseif (!is_user_logged_in() && get_theme_mod('inis_b_login_internal') && is_internal()) {
		$login_url = get_permalink( $login_id );
		wp_redirect( $login_url );
		exit;
	} elseif ( is_user_logged_in() && get_theme_mod('inis_b_login_internal') && is_page() && $login_id == $post->ID ) {
		$internal_url = get_post_type_archive_link( 'internal' );
		wp_redirect( $internal_url );
		exit;
	}
}
add_action( 'template_redirect', 'custom_redirect' );

/*-----------------------------------------------------------------------------------*/
/* Custom Admin Redirects
/*-----------------------------------------------------------------------------------*/
function restrict_admin() {
	if ( ! current_user_can( 'edit_posts' ) && ! wp_doing_ajax() ) {
		if (get_theme_mod('inis_b_active_website') != '1') {
			$internal_url = home_url();
		} else {
			$internal_url = get_post_type_archive_link( 'internal' );
		}
    wp_redirect( $internal_url );
    exit;
  }
}
add_action( 'admin_init', 'restrict_admin', 1 );

function inis_b_login_redirect( $redirect_url, $POST_redirect_url, $user ) {
  if ( is_a( $user, 'WP_User' ) ) {
    if ( !$user->has_cap( 'edit_posts' ) && stripos($POST_redirect_url, '/wp-admin') !== false  && stripos($POST_redirect_url, '/uploads') === false) {
			if (get_theme_mod('inis_b_active_website') != '1') {
				$redirect_url = home_url();
			} else {
				$redirect_url = get_post_type_archive_link( 'internal' );
			}
    }
  }
  return $redirect_url;
}
add_action( 'login_redirect', 'inis_b_login_redirect', 10, 3 );

/*-----------------------------------------------------------------------------------*/
/* Remove Posts for Contributors
/*-----------------------------------------------------------------------------------*/
function post_remove () {
  if ( ! current_user_can( 'publish_posts' ) && ( ! wp_doing_ajax() ) ) {
    remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit.php?post_type=member' );
  }
}
add_action('admin_menu', 'post_remove');

/*-----------------------------------------------------------------------------------*/
/* Custom Rules for Subscribers
/*-----------------------------------------------------------------------------------*/
if ( ! current_user_can('edit_posts') ) {
  // Remove Admin Bar
  add_filter( 'show_admin_bar', '__return_false' );
}

/*-----------------------------------------------------------------------------------*/
/* Custom Title Links
/*-----------------------------------------------------------------------------------*/
function get_post_title_link($post, $link_end_tag = false) {
	$link = '';
	$link_end = '';

	$pt_obj = get_post_type_object( $post->post_type );

	if ($pt_obj->public == 1) {
		$link = '<a href="' . get_the_permalink($post->ID) . '">';
		$link_end = '</a>';
	} elseif ($post->post_type == 'member' && get_post_meta($post->ID, 'member_link', true)) {
		$link = '<a href="' . get_post_meta($post->ID, 'member_link', true) . '" target="_blank">';
		$link_end = '</a>';
	}

	if ($link_end_tag == true) {
		return $link_end;
	} else {
		return $link;
	}
}

function get_post_title_link_end($post) {
	return get_post_title_link($post, true);
}

/*-----------------------------------------------------------------------------------*/
/* Custom Archive Titles
/*-----------------------------------------------------------------------------------*/
function inis_b_archive_title($title, $original_title) {
  if (get_option('page_for_posts') && is_home()) {
    $title = get_the_title(get_option('page_for_posts'));
  }

  return $title;
}
add_action( 'get_the_archive_title', 'inis_b_archive_title', 10, 2 );

/*-----------------------------------------------------------------------------------*/
/* Custom Post Types
/*-----------------------------------------------------------------------------------*/
function post_type_inis_b() {
  $event_slug = __('events','inis-b');
  $event_cat_slug = __('event-category','inis-b');
	$member_slug = __('members','inis-b');
	$member_cat_slug = __('member-category','inis-b');
	$project_slug = __('projects','inis-b');
	$project_cat_slug = __('project-category','inis-b');
	$internal_slug = __('internal','inis-b');
	$internal_cat_slug = __('internal-category','inis-b');
	$internal_tag_slug = __('internal-tags','inis-b');

	register_taxonomy( 'member-category', 'member',
		array(
			'labels' => array (
			    'name' => __( 'Member Categories', 'inis-b' ),
			    'singular_name' => __( 'Member Category', 'inis-b' ),
			    'search_items' => __( 'Search Member Categories', 'inis-b' ),
			    'all_items' => __( 'All Member Categories', 'inis-b' ),
			    'parent_item' => __( 'Parent Member Category', 'inis-b' ),
			    'parent_item_colon'  => __( 'Parent Member Category:', 'inis-b' ),
			    'edit_item' => __( 'Edit Category', 'inis-b' ),
			    'update_item' => __( 'Update Category', 'inis-b' ),
			    'add_new_item' => __( 'Add New Category', 'inis-b' ),
			    'new_item_name' => __( 'New Category Name', 'inis-b' ),
			    'menu_name' => __( 'Categories', 'inis-b' )
			),
	    'hierarchical' => true,
	    'show_admin_column' => true,
			'show_in_rest' => true,
			'show_in_nav_menus' => false,
			'query_var' => 'member-category',
			'rewrite' => array('slug' => $member_cat_slug )
		)
	);

	register_post_type( 'member',
		array(
			'labels' => array(
				'name' => __( 'Members', 'inis-b' ),
				'singular_name' => __( 'Member', 'inis-b' ),
				'add_new' => __('Add new', 'inis-b'),
				'add_new_item' => __('Add new member', 'inis-b'),
				'edit_item' => __('Edit', 'inis-b'),
				'new_item' => __('New Member', 'inis-b'),
				'all_items' => __('All Members', 'inis-b'),
				'view_item' => __('View', 'inis-b'),
				'search_items' => __('Search Members', 'inis-b'),
				'not_found' =>  __('Nothing found', 'inis-b'),
				'not_found_in_trash' => __('No members found in Trash', 'inis-b'),
				'parent_item_colon' => '',
				'menu_name' => __( 'Members', 'inis-b' )
			),
			'public' => false,
			'show_ui' => false,
			'menu_icon' => 'dashicons-groups',
			'menu_position' => 15,
			'has_archive' => false,
			'show_in_rest' => true,
			'capabilities' => array(
				'edit_post' => 'publish_posts',
				'read_post' => 'edit_others_posts',
				'delete_post' => 'publish_posts',
				'edit_posts' => 'edit_others_posts',
				'delete_posts' => 'edit_others_posts',
				'edit_others_posts' => 'edit_others_posts',
				'publish_posts' => 'edit_others_posts',
				'read_private_posts' => 'edit_others_posts'
	    ),
			'rewrite' => array('slug' => $member_slug,'with_front' => false),
			'supports' => array('title','editor','author','custom-fields','thumbnail','revisions')
		)
	);

	register_taxonomy( 'project-category', 'project',
	  array(
	    'labels' => array (
	        'name' => __( 'Project Categories', 'inis-b' ),
	        'singular_name' => __( 'Project Category', 'inis-b' ),
	        'search_items' => __( 'Search Project Categories', 'inis-b' ),
	        'all_items' => __( 'All Project Categories', 'inis-b' ),
	        'parent_item' => __( 'Parent Project Category', 'inis-b' ),
	        'parent_item_colon'  => __( 'Parent Project Category:', 'inis-b' ),
	        'edit_item' => __( 'Edit Category', 'inis-b' ),
	        'update_item' => __( 'Update Category', 'inis-b' ),
	        'add_new_item' => __( 'Add New Category', 'inis-b' ),
	        'new_item_name' => __( 'New Category Name', 'inis-b' ),
	        'menu_name' => __( 'Categories', 'inis-b' )
	    ),
	    'hierarchical' => true,
	    'show_admin_column' => true,
	    'show_in_rest' => true,
	    'show_in_nav_menus' => false,
	    'query_var' => 'project-category',
	    'rewrite' => array('slug' => $project_cat_slug )
	  )
	);

	register_post_type( 'project',
	  array(
	    'labels' => array(
	      'name' => __( 'Projects', 'inis-b' ),
	      'singular_name' => __( 'Project', 'inis-b' ),
	      'add_new' => __('Add new', 'inis-b'),
	      'add_new_item' => __('Add new project', 'inis-b'),
	      'edit_item' => __('Edit', 'inis-b'),
	      'new_item' => __('New Project', 'inis-b'),
	      'all_items' => __('All Projects', 'inis-b'),
	      'view_item' => __('View', 'inis-b'),
	      'search_items' => __('Search projects', 'inis-b'),
	      'not_found' =>  __('Nothing found', 'inis-b'),
	      'not_found_in_trash' => __('No projects found in Trash', 'inis-b'),
	      'parent_item_colon' => '',
	      'menu_name' => __( 'Projects', 'inis-b' )
	    ),
	    'public' => false,
	    'show_ui' => false,
	    'menu_icon' => 'dashicons-networking',
	    'menu_position' => 16,
	    'has_archive' => false,
	    'show_in_rest' => true,
	    'capabilities' => array(
				'edit_post' => 'publish_posts',
				'read_post' => 'edit_others_posts',
				'delete_post' => 'publish_posts',
				'edit_posts' => 'edit_others_posts',
				'delete_posts' => 'edit_others_posts',
				'edit_others_posts' => 'edit_others_posts',
				'publish_posts' => 'edit_others_posts',
				'read_private_posts' => 'edit_others_posts'
	    ),
	    'rewrite' => array('slug' => $project_slug,'with_front' => false),
	    'supports' => array('title','editor','author','page-attributes','custom-fields','thumbnail','revisions')
	  )
	);

	register_taxonomy( 'internal-category', 'internal',
		array(
			'labels' => array (
					'name' => __( 'Internal Categories', 'inis-b' ),
					'singular_name' => __( 'Internal Category', 'inis-b' ),
					'search_items' => __( 'Search Internal Categories', 'inis-b' ),
					'all_items' => __( 'All Internal Categories', 'inis-b' ),
					'parent_item' => __( 'Parent Internal Category', 'inis-b' ),
					'parent_item_colon'  => __( 'Parent Internal Category:', 'inis-b' ),
					'edit_item' => __( 'Edit Category', 'inis-b' ),
					'update_item' => __( 'Update Category', 'inis-b' ),
					'add_new_item' => __( 'Add New Category', 'inis-b' ),
					'new_item_name' => __( 'New Category Name', 'inis-b' ),
					'menu_name' => __( 'Categories', 'inis-b' )
			),
			'hierarchical' => true,
			'show_admin_column' => true,
			'show_in_rest' => true,
			'show_in_nav_menus' => false,
			'query_var' => 'internal-category',
			'rewrite' => array('slug' => $internal_cat_slug )
		)
	);

	register_taxonomy( 'internal-tag', 'internal',
		array(
			'labels' => array (
					'name' => __( 'Internal Tags', 'inis-b' ),
					'singular_name' => __( 'Internal Tag', 'inis-b' ),
					'search_items' => __( 'Search Internal Tags', 'inis-b' ),
					'all_items' => __( 'All Internal Tags', 'inis-b' ),
					'parent_item' => __( 'Parent Internal Tag', 'inis-b' ),
					'parent_item_colon'  => __( 'Parent Internal Tag:', 'inis-b' ),
					'edit_item' => __( 'Edit Tag', 'inis-b' ),
					'update_item' => __( 'Update Tag', 'inis-b' ),
					'add_new_item' => __( 'Add New Tag', 'inis-b' ),
					'new_item_name' => __( 'New Tag Name', 'inis-b' ),
					'menu_name' => __( 'Tags', 'inis-b' )
			),
			'hierarchical' => false,
			'show_admin_column' => true,
			'show_in_rest' => true,
			'show_in_nav_menus' => false,
			'query_var' => 'internal-tags',
			'rewrite' => array('slug' => $internal_tag_slug )
		)
	);

	register_post_type( 'internal',
		array(
			'labels' => array(
				'name' => __( 'Internal Posts', 'inis-b' ),
				'singular_name' => __( 'Internal Post', 'inis-b' ),
				'add_new' => __('Add new', 'inis-b'),
				'add_new_item' => __('Add new internal post', 'inis-b'),
				'edit_item' => __('Edit', 'inis-b'),
				'new_item' => __('New Internal Post', 'inis-b'),
				'all_items' => __('All Internal Posts', 'inis-b'),
				'view_item' => __('View', 'inis-b'),
				'search_items' => __('Search Internal Posts', 'inis-b'),
				'not_found' =>  __('Nothing found', 'inis-b'),
				'not_found_in_trash' => __('No internal posts found in Trash', 'inis-b'),
				'parent_item_colon' => '',
				'menu_name' => __( 'Internal Posts', 'inis-b' )
			),
			'public' => true,
			'show_ui' => false,
			'menu_icon' => 'dashicons-media-document',
			'menu_position' => 17,
			'has_archive' => true,
			'show_in_rest' => true,
			'exclude_from_search' => false,
			'capabilities' => array(
				'edit_post' => 'edit_others_posts',
				'read_post' => 'edit_others_posts',
				'delete_post' => 'edit_others_posts',
				'edit_posts' => 'edit_others_posts',
				'delete_posts' => 'edit_others_posts',
				'edit_others_posts' => 'edit_others_posts',
				'publish_posts' => 'edit_others_posts',
				'read_private_posts' => 'edit_others_posts'
			),
			'rewrite' => array('slug' => $internal_slug,'with_front' => false),
			'supports' => array('title','editor','author','custom-fields','thumbnail', 'excerpt','revisions')
		)
	);

	//flush_rewrite_rules();
}
add_action( 'init', 'post_type_inis_b', 0 );

/*-----------------------------------------------------------------------------------*/
/* Check Internal
/*-----------------------------------------------------------------------------------*/
function is_internal() {
	global $wp_query;

	$output = false;

	if (is_singular('internal') || is_post_type_archive('internal') || is_tax('internal-category') || is_tax('internal-tag') || (is_search() && isset($wp_query->query['post_type']) && $wp_query->query['post_type'] == 'internal') ) {
		$output = true;
	}

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Get Internal Header
/*-----------------------------------------------------------------------------------*/
function internal_header() {
	echo get_internal_header();
}

function get_internal_header() {
	$output = '';

	$output .= '<div class="internal-header">';
		$output .= '<header class="page-header">';
			$output .= '<h3 class="archive-title">';
				$obj = get_post_type_object( get_post_type() );
				$output .= $obj->labels->name;
			$output .= '</h3>';
			if ( is_user_logged_in() && ! current_user_can('edit_posts') ) {
				$output .= '<div class="logout button logout-button"><a href="' . wp_logout_url( home_url() ) .'">' . __( 'Logout','inis-b' ) . '</a></div>';
			}
		$output .= '</header>';
		$output .= get_internal_sidebar();
	$output .= '</div>';

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Get Internal Sidebar
/*-----------------------------------------------------------------------------------*/
function internal_sidebar() {
	echo get_internal_sidebar();
}

function get_internal_sidebar() {
	$output = '';

	if ( is_active_sidebar( 'sidebar-internal' ) ) {
		$output .= '<div class="a_sidebar">';
			$output .= '<div class="a_sidebar_inner">';
				$output .= '<hr />';
				$output .= '<aside class="sidebar left">';
					ob_start();
					dynamic_sidebar( 'sidebar-internal' );
					$output .= ob_get_contents();
					ob_end_clean();
				$output .= '</aside>';
				$output .= '<div class="clear"></div>';
			$output .= '</div>';
		$output .= '</div>';
	}

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Custom Internal Category Output
/*-----------------------------------------------------------------------------------*/
function the_internal_category($sep = ' ', $pID = false) {
	echo get_internal_category($sep,$pID);
}

function get_internal_category($sep = ' ', $pID = false) {
	return get_cpt_category($sep, 'internal-category');
}

function the_internal_tag($sep = ' ', $pID = false) {
	echo get_internal_tag($sep,$pID);
}

function get_internal_tag($sep = ' ', $pID = false) {
	return get_cpt_category($sep,$pID, 'internal-tag');
}

function the_project_category($sep = ' ', $pID = false) {
	echo get_project_category($sep,$pID);
}

function get_project_category($sep = ' ', $pID = false) {
	return get_cpt_category($sep, 'project-category',$pID);
}

function get_cpt_category($sep = ' ', $tax = 'category', $pID = false) {
	$output = '';

	if ($pID) {
		$postID = $pID;
	} else {
		$postID = get_the_ID();
	}

	$terms = get_the_terms( $postID, $tax );
	$term_i = 0;

	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$term_i++;
			$term_link = get_term_link( $term );
			$output .= '<a href="' . $term_link . '">' . $term->name . '</a>';
			if ($term_i != count($terms)) {
				$output .= $sep;
			}
		}
	}

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Custom Post Type Visibility
/*-----------------------------------------------------------------------------------*/
function inis_b_cpt_visibility($args, $post_type){
	if ($post_type == 'member' && get_theme_mod('inis_b_active_members') == '1'){
		$args['show_ui'] = true;
	}

	if ($post_type == 'project' && get_theme_mod('inis_b_active_projects') == '1'){
		$args['show_ui'] = true;
	}

	if ($post_type == 'internal' && get_theme_mod('inis_b_active_internal') == '1'){
		$args['show_ui'] = true;
	}

  return $args;
}
add_filter('register_post_type_args', 'inis_b_cpt_visibility', 10, 2);

function inis_b_tax_visibility($args, $taxonomy){
	if ($taxonomy == 'member-category' && get_theme_mod('inis_b_active_members') == '1'){
		$args['show_in_nav_menus'] = true;
	}

	if ($taxonomy == 'project-category' && get_theme_mod('inis_b_active_projects') == '1'){
		$args['show_in_nav_menus'] = true;
	}

	if ($taxonomy == 'internal-category' && get_theme_mod('inis_b_active_internal') == '1'){
		$args['show_in_nav_menus'] = true;
	}

	if ($taxonomy == 'internal-tag' && get_theme_mod('inis_b_active_internal') == '1'){
		$args['show_in_nav_menus'] = true;
	}

  return $args;
}
add_filter('register_taxonomy_args', 'inis_b_tax_visibility', 10, 2);

/*-----------------------------------------------------------------------------------*/
/* Event Calendar Settings
/*-----------------------------------------------------------------------------------*/
function tribe_events_settings_inis_b() {
	if (!get_option('tribe_events_calendar_options')) {
		$settings = array(
			'recurring_events_are_hidden' => 'hidden',
			'postsPerPage' => 10,
			'liveFiltersUpdate' => 1,
			'disable_metabox_custom_fields' => 1,
			'eventsSlug' => __('events','inis-b'),
			'singleEventSlug' => __('event','inis-b'),
			'multiDayCutoff' => '00:00',
			'defaultCurrencySymbol' => '€',
			'reverseCurrencyPosition' => 1,
			'embedGoogleMaps' => 1,
			'embedGoogleMapsZoom' => 10,
			'stylesheetOption' => 'full',
			'tribeEnableViews' => array('list','month'),
			'dateWithYearFormat' => 'j. F Y',
			'dateWithoutYearFormat' => 'j. F',
			'monthAndYearFormat' => 'F Y',
			'dateTimeSeparator' => ' ',
			'timeRangeSeparator' => ' - ',
			'tribeEventsTemplate' => ''
		);

		add_option( 'tribe_events_calendar_options', $settings );
	}
}
add_action( 'init', 'tribe_events_settings_inis_b', 0 );

/*-----------------------------------------------------------------------------------*/
/* ACF Custom Location Rules
/*-----------------------------------------------------------------------------------*/
function acf_location_rules_values_post_type( $choices ) {
	$args = array(
	   'public'   => false,
	   '_builtin' => false
	);
	$output = 'objects';

	$post_types = get_post_types( $args, $output );

  if ( $post_types ) {
    foreach( $post_types as $post_type ) {
			if (strpos($post_type->name, 'acf-') === false) {
      	$choices[ $post_type->name ] = $post_type->labels->singular_name;
			}
    }
  }

  return $choices;
}
add_filter('acf/location/rule_values/post_type', 'acf_location_rules_values_post_type');

/*-----------------------------------------------------------------------------------*/
/* ACF Local JSON
/*-----------------------------------------------------------------------------------*/
function inis_b_acf_json_save_point( $path ) {
  // update path
  $path = get_template_directory() . '/assets/acf-json';

  return $path;
}
add_filter('acf/settings/save_json', 'inis_b_acf_json_save_point');

function inis_b_acf_json_load_point( $paths ) {
  // remove original path (optional)
  unset($paths[0]);

  $paths[] = get_template_directory() . '/assets/acf-json';

  return $paths;
}
add_filter('acf/settings/load_json', 'inis_b_acf_json_load_point');

/*-----------------------------------------------------------------------------------*/
/* Custom Query for Searchs >>  https://gist.github.com/joshuadavidnelson/127e51326c04367036da
/*-----------------------------------------------------------------------------------*/
function inis_b_modify_query( $query ) {
	// First, make sure this isn't the admin and is the main query, otherwise bail
	if( is_admin() || ! $query->is_main_query() )
		return;

	// If this is a search result query and not from the internal search
	if( $query->is_search() && (!isset($query->query['post_type']) || $query->query['post_type'] != 'internal' )) {
		// Gather all searchable post types
		$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );
		// The post type you're removing, in this example 'page'
		$post_type_to_remove = 'internal';
		// Make sure you got the proper results, and that your post type is in the results
		if( is_array( $in_search_post_types ) && in_array( $post_type_to_remove, $in_search_post_types ) ) {
			// Remove the post type from the array
			unset( $in_search_post_types[ $post_type_to_remove ] );
			// set the query to the remaining searchable post types
			$query->set( 'post_type', $in_search_post_types );
		}
	}
}
add_action( 'pre_get_posts', 'inis_b_modify_query' );

/*-----------------------------------------------------------------------------------*/
/* Post Footer Output
/*-----------------------------------------------------------------------------------*/
function inis_b_post_footer() {
	echo get_inis_b_post_footer();
}

function get_inis_b_post_footer() {
	global $post;

	$output = '';

	if ( 'post' == $post->post_type ) {
		$output .= '<footer class="post-meta-footer">';
			$output .= '<span class="date">' . get_the_date(__('j. F Y', 'inis-b')) . '</span> | ';
			$output .= '<span class="cat-links">' . __('Categories', 'inis-b') . ' ' . get_the_category_list( ', ' ) . '</span>';
			$output .=  get_the_tag_list( __('<span class="tag-links"><strong>Tags:</strong> ', 'inis-b'), ', ', ' </span>' );
		$output .= '</footer>';
	} elseif ( 'internal' == $post->post_type ) {
		$output .= '<footer class="post-meta-footer">';
			$output .= '<span class="date">' . get_the_date(__('j. F Y', 'inis-b')) . '</span>';
			if ( get_internal_category() ) {
			 $output .= ' | <span class="cat-links">' . __('Categories', 'inis-b') . ' ' . get_internal_category( ', ' ) . '</span>';
		 	}
			if ( get_internal_tag() ) {
			 $output .= __('<span class="tag-links"><strong>Tags:</strong> ', 'inis-b') . ' ' . get_internal_tag( ', ' ) . '</span>';
			}
		$output .= '</footer>';
	}

	return apply_filters('inis_b_post_footer_output', $output);
}

/*-----------------------------------------------------------------------------------*/
/* Get Sticky Posts (all, own, partner)
/*-----------------------------------------------------------------------------------*/
function get_sticky_posts($type = 'all') {
	$sticky_posts = get_option('sticky_posts');
	$sticky_posts_own = array(); // All sticky posts not on platform
	$sticky_posts_partner = array(); // All sticky posts on platform
	$sticky_posts_external = array(); // All sticky posts from external source

	if ($sticky_posts) {
		foreach ($sticky_posts as $sticky) {
			if (has_term( get_theme_mod('partner_post_topic'), 'city-topics', $sticky )) {
				if (get_post_meta($sticky, 'platform_post_id', true)) {
					$sticky_posts_external[] = $sticky;
				}
				$sticky_posts_partner[] = $sticky;
			}	else {
				$sticky_posts_own[] = $sticky;
			}
		}
	}

	if ($type == 'own') {
		$sticky_posts = $sticky_posts_own;
	} elseif ($type == 'partner') {
		$sticky_posts = $sticky_posts_partner;
	} elseif ($type == 'external') {
	 	$sticky_posts = $sticky_posts_external;
 	}

	return $sticky_posts;
}

/*-----------------------------------------------------------------------------------*/
/* Remove Users from XML Sitemap
/*-----------------------------------------------------------------------------------*/
add_filter(
  'wp_sitemaps_add_provider',
  function( $provider, $name ) {
    if ( 'users' === $name ) {
      return false;
    }

    return $provider;
  },
  10,
  2
);

/*-----------------------------------------------------------------------------------*/
/* Add lastmod to XML Sitemap
/*-----------------------------------------------------------------------------------*/
add_filter(
    'wp_sitemaps_posts_entry',
    function( $entry, $post ) {
			$timestamp = strtotime($post->post_modified_gmt);
      $entry['lastmod'] = date('c', $timestamp);
      return $entry;
    },
    10,
    2
);

/*-----------------------------------------------------------------------------------*/
/* Remove internal CPT from XML Sitemap
/*-----------------------------------------------------------------------------------*/
add_filter(
    'wp_sitemaps_post_types',
    function( $post_types ) {
        unset( $post_types['internal'] );
        return $post_types;
    }
);

/*-----------------------------------------------------------------------------------*/
/* Remove taxonomies from XML Sitemap
/*-----------------------------------------------------------------------------------*/
add_filter(
    'wp_sitemaps_taxonomies',
    function( $taxonomies ) {
        unset( $taxonomies['city-topics'] );
				unset( $taxonomies['member-category'] );
				unset( $taxonomies['project-category'] );
				unset( $taxonomies['internal-category'] );
				unset( $taxonomies['internal-tag'] );
        return $taxonomies;
    }
);

/*-----------------------------------------------------------------------------------*/
/* Remove Password Protected Posts XML Sitemap
/*-----------------------------------------------------------------------------------*/
add_filter(
    'wp_sitemaps_posts_query_args',
    function( $args, $post_type ) {
      $args['has_password'] = false;
      return $args;
    },
    10,
    2
);

/*-----------------------------------------------------------------------------------*/
/* Include Files
/*-----------------------------------------------------------------------------------*/
/* Custom Removes */
require_once ( get_template_directory() . '/inc/custom-removes.php' );

/* Custom Header */
require_once ( get_template_directory() . '/inc/custom-header.php' );

/* Custom Menu */
require_once ( get_template_directory() . '/inc/custom-menu.php' );

/* Custom Body & Post Classes */
require_once ( get_template_directory() . '/inc/custom-body-post-classes.php' );

/* Custom Comments */
require_once ( get_template_directory() . '/inc/custom-comments.php' );

/* Shortcodes */
require_once ( get_template_directory() . '/inc/custom-shortcodes.php' );

/* Custom Styles */
require_once ( get_template_directory() . '/inc/custom-styles.php' );

/* Custom CSS-Class for Widgets */
require_once ( get_template_directory() . '/inc/custom-widget-styles.php' );

/* Theme Customizer */
require_once ( get_template_directory() . '/inc/custom-customizer.php' );

/* Custom Help */
require_once ( get_template_directory() . '/inc/custom-help.php' );

/* Custom Block Editor */
if (get_theme_support( 'gutenberg' )) {
	require_once ( get_template_directory() . '/inc/custom-block-editor.php' );
}
