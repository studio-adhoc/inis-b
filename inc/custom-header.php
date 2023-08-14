<?php
/*-----------------------------------------------------------------------------------*/
/* Enqueue CSS and JS
/*-----------------------------------------------------------------------------------*/
function inis_b_scripts() {
	wp_enqueue_style( 'inis-b-global-style', get_bloginfo('template_directory').'/assets/css/style_global.php', array(),'', 'all' );
	
	if (get_theme_mod('inis_b_theme_font') == 'plex-serif' ) {
		wp_enqueue_style( 'inis-b-fonts', get_bloginfo('template_directory').'/assets/css/font-serif.css', '','1.' . strtotime(date('Y-m-d H:i')), 'all' );
	} elseif (get_theme_mod('inis_b_theme_font') == 'plex-mono') {
		wp_enqueue_style( 'inis-b-fonts', get_bloginfo('template_directory').'/assets/css/font-mono.css', '','1.' . strtotime(date('Y-m-d H:i')), 'all' );
	} else {
		wp_enqueue_style( 'inis-b-fonts', get_bloginfo('template_directory').'/assets/css/font-sans.css', '','1.' . strtotime(date('Y-m-d H:i')), 'all' );
	}

	wp_enqueue_style( 'inis-b-style', get_bloginfo('template_directory').'/assets/css/style.css', '','1.' . strtotime(date('Y-m-d H:i')), 'all' );

	if (!is_customize_preview() && !is_admin()) {
		wp_enqueue_style( 'inis-b-custom-style', get_bloginfo('template_directory').'/assets/css/style_custom.php', array( 'inis-b-style' ),'1.' . strtotime(date('Y-m-d H:i')), 'all' );
	}

	wp_enqueue_script( 'jquery' );

	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style('thickbox');

	wp_enqueue_script( 'is', get_bloginfo('template_directory').'/assets/js/is.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'doubletap-to-go', get_bloginfo('template_directory').'/assets/js/doubletaptogo.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'inis-b-functions', get_bloginfo('template_directory').'/assets/js/functions.js', array( 'jquery' ), '1.1', true );
}
add_action( 'wp_enqueue_scripts', 'inis_b_scripts' );

/*-----------------------------------------------------------------------------------*/
/* Custom WP Document Header Output
/*-----------------------------------------------------------------------------------*/
function inis_b_document_header() {
	global $post; ?>

<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/assets/css/style_ie.css" type="text/css" media="screen" />
<![endif]-->

<noscript>
	<link rel="stylesheet" href="<?php bloginfo( 'template_directory' ); ?>/assets/css/style_nojs.css" type="text/css" media="screen" />
</noscript>

<?php if (is_customize_preview()) : ?>
<style type="text/css">
	<?php echo inis_b_custom_color_image($post->ID); ?>
</style>
<?php endif; ?>

<?php if (is_page() || is_single()) {
	if ( get_post_meta(get_the_id(), 'metadescription', true) ) { ?>
		<meta name="description" content="<?php echo get_post_meta(get_the_id(), 'metadescription', true); ?>" />
	<?php }
} ?>

<?php if (!is_internal()) {
	inis_b_custom_og_tags($post);
}
}
add_filter( 'wp_head', 'inis_b_document_header');

/*-----------------------------------------------------------------------------------*/
/* Custom Title Output
/*-----------------------------------------------------------------------------------*/
function get_custom_title_parts($title) {
	global $post;

	if (isset($title['tagline'])) {
		$tagline = wp_strip_all_tags(wp_specialchars_decode($title['tagline']));
		$title['tagline'] = $tagline;
	}

	if (is_singular() && get_post_meta($post->ID, 'metatitle', true)) {
  	$title['title'] = get_post_meta($post->ID, 'metatitle', true);
		$title['site'] = '';
  }

	if (is_multisite()) {
		/*if (get_theme_mod('inis_b_translation_site_title')) {
			$title['site'] = get_theme_mod('inis_b_translation_site_title');
		} else {
			$title['site'] = get_blog_option( 1, 'blogname' );
		}*/
	}

	return $title;
}
add_filter( 'document_title_parts', 'get_custom_title_parts', 10, 1 );

/*-----------------------------------------------------------------------------------*/
/* Plugable: Custom OG Tag Output
/*-----------------------------------------------------------------------------------*/
if (!function_exists('inis_b_custom_og_tags')) {
	function inis_b_custom_og_tags($post) {
		$blog_name = get_bloginfo( 'name' );
		$description = '';

		if (is_singular()) {
			if (get_post_meta($post->ID, 'metadescription', true)) {
			  $description = get_post_meta($post->ID, 'metadescription', true);
			} else {
			  $content = inis_b_get_complete_content($post->ID);
			  $description = wp_trim_words( $content, 20, '...' );
			}
		}

		$image = '';

		if (is_home()) {
	    if (get_theme_mod( 'inis_b_posts_og_image' )) {
	      $image = get_theme_mod( 'inis_b_posts_og_image' );
	    }
	    if (get_theme_mod( 'inis_b_posts_og_description' )) {
	      $description = get_theme_mod( 'inis_b_posts_og_description' );
	    }
	  } elseif (is_post_type_archive( 'tribe_events' )) {
	    if (get_theme_mod( 'inis_b_tribe_events_og_image' )) {
	      $image = get_theme_mod( 'inis_b_tribe_events_og_image' );
	    }
	    if (get_theme_mod( 'inis_b_tribe_events_og_description' )) {
	      $description = get_theme_mod( 'inis_b_tribe_events_og_description' );
	    }
	  } elseif (is_singular()) {
			if (has_post_thumbnail()) {
			  $image_src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
			  $image = $image_src[0];
			} elseif( have_rows('content_fields', $post->ID) ) {
				while ( have_rows('content_fields', $post->ID) ) : the_row();
					if( get_row_layout() == 'gallery' ) {
						if ( have_rows('page_gallery') ) :
							$gal_i = 0;
							while ( have_rows('page_gallery') ) : the_row();
								if ( get_sub_field('image') ) {
									$gal_i++;
									if ( $gal_i == 1 ) {
										$image_src = wp_get_attachment_image_src( get_sub_field('image'), 'full' );
										$image = $image_src[0];
									}
								}
							endwhile;
						endif;
					}
				endwhile;
			}
		} ?>
		<meta property="og:title" content="<?php echo wp_get_document_title(); ?>" />
		<meta property="og:type" content="website" />
		<meta property="og:locale" content="<?php echo get_locale(); ?>" />
		<meta property="og:url" content="<?php echo get_permalink(); ?>" />
		<?php if ($image != '') : ?>
		<meta property="og:image" content="<?php echo $image; ?>" />
		<?php endif; ?>
		<meta property="og:description" content="<?php echo $description; ?>" />
		<meta property="og:site_name" content="<?php echo $blog_name; ?>" />

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:url" content="<?php echo get_permalink(); ?>" />
		<meta name="twitter:title" content="<?php echo wp_get_document_title(); ?>" />
		<meta name="twitter:description" content="<?php echo $description; ?>" />
		<?php if ($image != '') : ?>
		<meta name="twitter:image" content="<?php echo $image; ?>" />
		<?php endif;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plugable: Custom Header Output
/*-----------------------------------------------------------------------------------*/
if (!function_exists('inis_b_header')) {
	function inis_b_header() {
		$output = '';

		$title = get_bloginfo( 'name' );
		$title_description = '';

		if (is_front_page()) {
			$home_link = '#top';
		} else {
			$home_link = get_bloginfo( 'url' );
		}

		if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) {
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			$output .= '<h2 class="logo"><a href="' . $home_link . '"><img src="' . $image[0] . '" alt="' . $title . '" width="' . $image[1] . '" height="' . $image[2] . '" /></a></h2>';
		}

		if ( !get_theme_mod( 'custom_logo' ) || get_theme_mod( 'inis_b_show_page_title' ) ) {
			$title_description .= '<h2 class="title"><a href="' . $home_link . '">' . $title . '</a></h2>';
		}
		if ( get_bloginfo( 'description' ) ) {
			$title_description .= '<h3 class="description">' . html_entity_decode(get_bloginfo('description')) . '</h3>';
		}

		if ($title_description != '') {
			$output .= '<div class="title-description-wrapper">' . $title_description . '</div>';
		}

		echo $output;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plugable: Custom Navi Output
/*-----------------------------------------------------------------------------------*/
if (!function_exists('inis_b_navi')) {
	function inis_b_navi() {
		if ( has_nav_menu('primary') || has_nav_menu('social') ) : ?>
		<nav class="a_navi">
			<div class="navi-wrapper">
				<div class="navi-button">
					<div class="navi-button-inner">
						<span><?php _e('Menu', 'inis-b'); ?></span>
					</div>
				</div>
				<div class="navi-inner">
					<div class="main">
						<?php if ( has_nav_menu('primary') ) { wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '' ) ); } ?>
						<?php if ( has_nav_menu('social') ) { wp_nav_menu( array( 'theme_location' => 'social', 'depth' => 1 ) ); } ?>
					</div>
				</div>
			</div>
		</nav>
		<?php endif;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Plugable: Custom Footer Output
/*-----------------------------------------------------------------------------------*/
if (!function_exists('inis_b_footer')) {
	function inis_b_footer() { ?>
		<div class="banner fixed">
			<?php if (get_theme_mod( 'inis_b_banner' )) : ?>
				<div class="banner-inner"><div class="banner-text"><?php echo get_theme_mod( 'inis_b_banner' ); ?></div></div>
			<?php endif; ?>
			<a class="top-link" href="#top"><img src="data:image/svg+xml,%3Csvg version='1.0' id='arrow_up' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' viewBox='0 0 13 12' xml:space='preserve'%3E%3Cpath style='fill:%23FFFFFF;' d='M6.6,0H6.4C5.7,1,4.9,2,3.9,2.8C3,3.6,1.8,4.5,0.4,5.3l1.3,1.9c1.6-1.1,2.9-2.2,3.8-3.5C5.3,4.6,5.3,5.7,5.3,7 v5h2.5V7c0-1.1-0.1-2.2-0.2-3.3c0.9,1.3,2.2,2.5,3.8,3.5l1.4-1.9c-1.4-0.9-2.6-1.7-3.5-2.5C8.1,1.9,7.3,1,6.6,0'/%3E%3C/svg%3E%0A" alt="<?php _e('Top','inis-b'); ?>" width="13" height="12" class="arrow" /></a>
		</div>

		<!--[if lt IE 9]><div id="browser-warning"><p><?php _e('<strong>Your browser is too old!</strong><br />Please update your browser','inis-b'); ?></p></div><![endif]-->
	<?php }
}
add_filter( 'wp_footer', 'inis_b_footer');

/*-----------------------------------------------------------------------------------*/
/* Plugable: Get complete content
/*-----------------------------------------------------------------------------------*/
if (!function_exists('inis_b_get_complete_content')) {
	function inis_b_get_complete_content($postID) {
		$output = '';

		$post_complete = get_post($postID);
		$output .= apply_filters('the_content', $post_complete->post_content);
		
		$output = wp_strip_all_tags($output);
		$output = preg_replace('#<[^>]+>#', ' ', $output);
		$output = str_replace('"', '', $output);

		return $output;
	}
}
