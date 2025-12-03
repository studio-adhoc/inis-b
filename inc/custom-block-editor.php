<?php
/*-----------------------------------------------------------------------------------*/
/* Init Block Editor Styles / Scripts
/*-----------------------------------------------------------------------------------*/
function inis_b_add_block_editor_assets() {
	global $pagenow;

	if (is_admin()) {
		wp_enqueue_style( 'inis-b-block-editor', get_bloginfo('template_directory') . '/assets/css/style_block-editor.css', false );
		wp_enqueue_style( 'inis-b-block-editor-custom', get_bloginfo('template_directory') . '/assets/css/style_block-editor_custom.php', false );

		$deps = array( 'wp-blocks', 'wp-dom-ready', 'wp-i18n' );

		if( $pagenow !== 'widgets.php' ) {
			$deps[] = 'wp-edit-post';
		}

		wp_enqueue_script(
			'inis-b-editor',
			get_bloginfo('template_directory') . '/assets/js/functions_block-editor.js',
			$deps,
			'1.0.0'
		);

		wp_set_script_translations( 'inis-b-editor', 'inis-b', get_template_directory() . '/languages' );
	}
}
add_action( 'enqueue_block_assets', 'inis_b_add_block_editor_assets' );

/*-----------------------------------------------------------------------------------*/
/* Add Block Editor Theme Support
/*-----------------------------------------------------------------------------------*/
function inis_b_setup_theme_supported_features() {
	// Theme supports wide images, galleries and videos.
	add_theme_support( 'align-wide' );

	// Disable color picker.
	add_theme_support( 'disable-custom-colors' );

	// Disable custom font sizes.
	add_theme_support('disable-custom-font-sizes');

	$custom_editor_colors = array(
		array(
			'name'  => esc_html__( 'Black', 'inis-b' ),
			'slug' => 'black',
			'color' => '#000000',
		),
		array(
			'name'  => esc_html__( 'White', 'inis-b' ),
			'slug' => 'white',
			'color' => '#ffffff',
		),
		array(
			'name'  => esc_html__( 'Gray', 'inis-b' ),
			'slug' => 'gray',
			'color' => '#727477',
		),
	);

	$custom_theme_colors = array(
		array(
			'inis_b_theme_color',
			'#e6ff00'
		),
		array(
			'inis_b_theme_button_color',
			'#000000'
		),
		array(
			'inis_b_theme_navi_color',
			'#ffffff'
		),
		array(
			'inis_b_theme_navi_button_color',
			'#000000'
		),
		array(
			'inis_b_theme_banner_color',
			'#e6ff00'
		)
	);

	$active_colors = array(
		'#000000',
		'#ffffff',
		'#727477'
	);

	foreach ($custom_theme_colors as $color) {
		$color_name = $color[0];
		$mod_color = '';

		if (get_theme_mod($color[0]) && get_theme_mod($color[0]) != '') {
			$mod_color = get_theme_mod($color[0]);
		}

		if ($mod_color != '') {
			if ( !in_array (strtolower($mod_color), $active_colors) ) {
				$active_colors[] = esc_html( $mod_color );
				$custom_editor_colors[] = array(
					'name'  => esc_html__( $color_name, 'inis-b' ),
					'slug' => $color_name,
					'color' => esc_html( $mod_color ),
				);
			}
		}
	}

	// Make specific theme colors available in the editor.
  add_theme_support( 'editor-color-palette', apply_filters( 'inis_b_custom_editor_colors', $custom_editor_colors ) );
}
add_action( 'after_setup_theme', 'inis_b_setup_theme_supported_features' );

/*-----------------------------------------------------------------------------------*/
/* Remove Openverse Tab
/*-----------------------------------------------------------------------------------*/
add_filter(
	'block_editor_settings_all',
	function( $settings ) {
	  $settings['enableOpenverseMediaCategory'] = false;
	  return $settings;
	},
	10
); 

/*-----------------------------------------------------------------------------------*/
/* Remove Blocks from CPT
/*-----------------------------------------------------------------------------------*/
function inis_b_editor_allowed_block_types( $allowed_block_types, $block_editor_context ) {
	$screen = get_current_screen();
	if ( $screen->parent_base == 'edit' ) {
		$post = $block_editor_context->post;
		$restricted_cpt = apply_filters('inis_b_blockeditor_restricted_cpt', array('member','project'));
		
		$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();

		if (!empty($block_types)) {
			$allowed_block_types = array();

			foreach ($block_types as $key => $value) {
			if ($value->category != 'theme') {
				$allowed_block_types[] = $key;
			}
			}
		}

		$block_types_restricted_cpt = apply_filters('inis_b_block_types_restricted_cpt', array(
			'core/paragraph',
			'core/image',
			'core/heading',
			'core/quote',
			'core/list',
			'core/button',
			'core/spacer'
		));

		if ( isset($restricted_cpt) && in_array($post->post_type, $restricted_cpt) ) {
			$allowed_block_types = $block_types_restricted_cpt;
		}
	}

	return $allowed_block_types;
}
add_filter( 'allowed_block_types_all', 'inis_b_editor_allowed_block_types', 10, 2 );
