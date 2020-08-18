<?php
/*-----------------------------------------------------------------------------------*/
/* Init Block Editor Styles / Scripts
/*-----------------------------------------------------------------------------------*/
function inis_b_add_block_editor_assets() {
	wp_enqueue_style( 'inis-b-block-editor', get_bloginfo('template_directory') . '/assets/css/style_block-editor.css', false );
	wp_enqueue_style( 'inis-b-block-editor-custom', get_bloginfo('template_directory') . '/assets/css/style_block-editor_custom.php', false );

	wp_enqueue_script(
		'inis-b-editor',
		get_bloginfo('template_directory') . '/assets/js/functions_block-editor.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post', 'wp-i18n' ),
		'1.0.0'
	);

	wp_set_script_translations( 'inis-b-editor', 'inis-b', get_template_directory() . '/languages' );
}
add_action( 'enqueue_block_editor_assets', 'inis_b_add_block_editor_assets' );

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
/* Remove Blocks from CPT
/*-----------------------------------------------------------------------------------*/
function inis_b_editor_allowed_block_types( $allowed_block_types, $post ) {
	$restricted_cpt = apply_filters('inis_b_blockeditor_restricted_cpt', array('member','project'));

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

  return $allowed_block_types;
}
add_filter( 'allowed_block_types', 'inis_b_editor_allowed_block_types', 10, 2 );
