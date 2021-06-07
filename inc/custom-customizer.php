<?php
/*-----------------------------------------------------------------------------------*/
/* Customizer Sections/Settings/Controls
/*-----------------------------------------------------------------------------------*/
function inis_b_customizer( $wp_customize ) {
  // Custom Banner
  $wp_customize->add_setting('inis_b_banner', array(
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'wp_filter_nohtml_kses',
  ));

  $wp_customize->add_control('inis_b_banner', array(
    'label'      => __('Banner Text', 'inis-b'),
    'section'    => 'title_tagline',
    'type'       => 'text',
    'settings'   => 'inis_b_banner',
  ));

  // Show Page Title
  $wp_customize->add_setting('inis_b_show_page_title', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
    'default'           => 1
  ));

  $wp_customize->add_control('inis_b_show_page_title', array(
    'label'      => __('Show Page Title', 'inis-b'),
    'section'    => 'title_tagline',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_show_page_title',
  ));

  // Custom Activate Website
  $wp_customize->add_setting('inis_b_active_website', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_active_website', array(
    'label'      => __('Activate Website', 'inis-b'),
    'section'    => 'title_tagline',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_active_website',
  ));

  // Custom Theme Options
  $wp_customize->add_section( 'inis_b_theme_options_section' , array(
    'title'       => __( 'Theme Options', 'inis-b' ),
    'priority'    => 61
  ) );

  $wp_customize->add_setting('inis_b_theme_font', array(
    'capability'        => 'edit_theme_options',
    'default'           => 'work-sans',
    'sanitize_callback' => 'sanitize_select',
  ));

  $wp_customize->add_control('inis_b_theme_font', array(
    'label'      => __('Theme Font', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'select',
    'choices'    => apply_filters('inis_b_theme_font_choices', array(
      'work-sans'  => 'Work Sans',
      'plex-serif' => 'Plex Serif',
      'plex-mono' => 'Plex Mono',
    )),
    'settings'   => 'inis_b_theme_font',
  ));

  $wp_customize->add_setting('inis_b_theme_color', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
  ));

  $wp_customize->add_control(
  	new WP_Customize_Color_Control(
  	$wp_customize,
  	'inis_b_theme_color',
  	array(
  		'label'      => apply_filters('inis_b_theme_color_label', __('Theme Color', 'inis-b')),
  		'section'    => 'inis_b_theme_options_section',
  		'settings'   => 'inis_b_theme_color',
  	) )
  );

  $wp_customize->add_setting('inis_b_underline_light', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_underline_light', array(
    'label'      => __('Lighter Color for Underline', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_underline_light',
  ));

  $wp_customize->add_setting('inis_b_theme_button_color', array(
    'capability'        => 'edit_theme_options',
    'default'           => '#000000',
    'sanitize_callback' => 'sanitize_hex_color',
  ));

  $wp_customize->add_control(
    new WP_Customize_Color_Control(
    $wp_customize,
    'inis_b_theme_button_color',
    array(
      'label'      => apply_filters('inis_b_theme_button_color_label', __('Button Color', 'inis-b')),
      'section'    => 'inis_b_theme_options_section',
      'settings'   => 'inis_b_theme_button_color',
    ) )
  );

  $wp_customize->add_setting('inis_b_theme_navi_color', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
  ));

  $wp_customize->add_control(
    new WP_Customize_Color_Control(
    $wp_customize,
    'inis_b_theme_navi_color',
    array(
      'label'      => apply_filters('inis_b_theme_navi_color_label', __('Theme Navi Color', 'inis-b')),
      'section'    => 'inis_b_theme_options_section',
      'settings'   => 'inis_b_theme_navi_color',
    ) )
  );

  $wp_customize->add_setting('inis_b_theme_navi_button_color', array(
    'capability'        => 'edit_theme_options',
    'default'           => '#000000',
    'sanitize_callback' => 'sanitize_hex_color',
  ));

  $wp_customize->add_control(
    new WP_Customize_Color_Control(
    $wp_customize,
    'inis_b_theme_navi_button_color',
    array(
      'label'      => apply_filters('inis_b_theme_navi_button_color_label', __('Theme Navi Button Color', 'inis-b')),
      'section'    => 'inis_b_theme_options_section',
      'settings'   => 'inis_b_theme_navi_button_color',
    ) )
  );

  $wp_customize->add_setting('inis_b_theme_navi_button_text_color_light', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_theme_navi_button_text_color_light', array(
    'label'      => apply_filters('inis_b_theme_navi_button_text_color_light_label', __('Lighter Color for Navi Button Text', 'inis-b')),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_theme_navi_button_text_color_light',
  ));

  $wp_customize->add_setting('inis_b_theme_banner_color', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_hex_color',
  ));

  $wp_customize->add_control(
    new WP_Customize_Color_Control(
    $wp_customize,
    'inis_b_theme_banner_color',
    array(
      'label'      => apply_filters('inis_b_theme_banner_color_label', __('Theme Banner Color', 'inis-b')),
      'section'    => 'inis_b_theme_options_section',
      'settings'   => 'inis_b_theme_banner_color',
    ) )
  );

  $wp_customize->add_setting('inis_b_theme_banner_color_for_navi_button_hover', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_theme_banner_color_for_navi_button_hover', array(
    'label'      => apply_filters('inis_b_theme_banner_color_for_navi_button_hover_label', __('Banner Color for Navi Button Hover', 'inis-b')),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_theme_banner_color_for_navi_button_hover',
  ));

  // Custom Header Options
  $wp_customize->add_setting('inis_b_theme_header_image', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_image',
  ));

  $wp_customize->add_control(
  	new WP_Customize_Image_Control(
  	$wp_customize,
  	'inis_b_theme_header_image',
  	array(
      'label'      => __('Header Image', 'inis-b'),
      'section'    => 'inis_b_theme_options_section',
      'settings'   => 'inis_b_theme_header_image',
  	) )
  );

  $wp_customize->add_setting('inis_b_theme_header_text_color', array(
    'capability'        => 'edit_theme_options',
    'default'           => '#000',
    'sanitize_callback' => 'sanitize_select',
  ));

  $wp_customize->add_control('inis_b_theme_header_text_color', array(
    'label'      => __('Header Text Color', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'select',
    'choices'    => apply_filters('inis_b_theme_header_text_color_choices', array(
      '#000'  => __('Black','inis-b'),
      '#FFF'  => __('White','inis-b'),
      'theme-color'  => __('Theme Color','inis-b'),
    )),
    'settings'   => 'inis_b_theme_header_text_color',
  ));

  $wp_customize->add_setting('inis_b_theme_header_background_color', array(
    'capability'        => 'edit_theme_options',
    'default'           => '#FFF',
    'sanitize_callback' => 'sanitize_select',
  ));

  $wp_customize->add_control('inis_b_theme_header_background_color', array(
    'label'      => __('Header Background Color', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'select',
    'choices'    => apply_filters('inis_b_theme_header_background_color_choices', array(
      '#000'  => __('Black','inis-b'),
      '#FFF'  => __('White','inis-b'),
      'theme-color'  => __('Theme Color','inis-b'),
    )),
    'settings'   => 'inis_b_theme_header_background_color',
  ));

  // Activate CPT
  /*$wp_customize->add_setting('inis_b_active_events', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_active_events', array(
    'label'      => __('Activate Events', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_active_events',
  ));*/

  // Activate Member CPT
  if (function_exists('get_field')) {
    $wp_customize->add_setting('inis_b_active_members', array(
      'capability'        => 'edit_theme_options',
      'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('inis_b_active_members', array(
      'label'      => __('Activate Members', 'inis-b'),
      'section'    => 'inis_b_theme_options_section',
      'type'       => 'checkbox',
      'settings'   => 'inis_b_active_members',
    ));
  }

  // Activate Project CPT
  $wp_customize->add_setting('inis_b_active_projects', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_active_projects', array(
    'label'      => __('Activate Projects', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_active_projects',
  ));

  // Activate Internal CPT
  $wp_customize->add_setting('inis_b_active_internal', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_active_internal', array(
    'label'      => __('Activate Internal', 'inis-b'),
    'section'    => 'inis_b_theme_options_section',
    'type'       => 'checkbox',
    'settings'   => 'inis_b_active_internal',
  ));

  $wp_customize->add_setting('inis_b_login_internal', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'absint',
  ));

  $wp_customize->add_control('inis_b_login_internal', array(
    'label'       => __('Login Page for Internal', 'inis-b'),
    'description' => __('This page needs to contain the [login] shortcode', 'inis-b'),
    'section'     => 'inis_b_theme_options_section',
    'type'        => 'dropdown-pages',
    'settings'    => 'inis_b_login_internal',
  ));

  // Custom Tribe Events OG Tags
  $wp_customize->add_setting('inis_b_tribe_events_og_image', array(
    'capability'        => 'edit_theme_options',
    'sanitize_callback' => 'sanitize_image',
  ));

  $wp_customize->add_control(
    new WP_Customize_Image_Control(
    $wp_customize,
    'inis_b_tribe_events_og_image',
    array(
      'label'      => __('Tribe Events OG Image', 'inis-b'),
      'section'    => 'global_elements',
      'settings'   => 'inis_b_tribe_events_og_image',
      'priority'    => 990
    ) )
  );

  $wp_customize->add_setting('inis_b_tribe_events_og_description', array(
    'capability'     => 'edit_theme_options',
    'sanitize_callback' => 'wp_filter_nohtml_kses',
  ));

  $wp_customize->add_control('inis_b_tribe_events_og_description', array(
    'label'      => __('Tribe Events OG Description', 'inis-b'),
    'section'    => 'global_elements',
    'type'       => 'text',
    'settings'   => 'inis_b_tribe_events_og_description',
    'priority'    => 991
  ));
}
add_action('customize_register', 'inis_b_customizer');

/*-----------------------------------------------------------------------------------*/
/* Sanitize Select
/*-----------------------------------------------------------------------------------*/
function sanitize_select( $input, $setting ) {
  // Ensure input is a slug.
  //$input = sanitize_key( $input );

  // Get list of choices from the control associated with the setting.
  $choices = $setting->manager->get_control($setting->id)->choices;

  // If the input is a valid key, return it; otherwise, return the default.
  return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/*-----------------------------------------------------------------------------------*/
/* Sanitize Image
/*-----------------------------------------------------------------------------------*/
function sanitize_image( $input, $setting ) {
	return esc_url_raw( validate_image( $input, $setting->default ) );
}

function validate_image( $input, $default = '' ) {
	// Array of valid image file types
	// The array includes image mime types
	// that are included in wp_get_mime_types()
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon'
	);
	// Return an array with file extension
	// and mime_type
	$file = wp_check_filetype( $input, $mimes );
	// If $input has a valid mime_type,
	// return it; otherwise, return
	// the default.
	return ( $file['ext'] ? $input : $default );
}

/*-----------------------------------------------------------------------------------*/
/* Add Styles to Customizer Controls
/*-----------------------------------------------------------------------------------*/
function inis_b_customizer_control_styles() { ?>
	<style>
    #customize-control-inis_b_active_members,
		#customize-control-inis_b_theme_header_image {
      border-top: 1px solid #72777c;
      padding-top: 10px;
		}
    #customize-control-inis_b_theme_header_image:before,
    #customize-control-inis_b_active_members:before {
      content: '<?php _e('Custom Post Types','inis-b'); ?>';
      display: block;
      font-size: 20px;
      font-weight: 200;
      line-height: 26px;
      margin-bottom: 10px;
    }
    #customize-control-inis_b_theme_header_image:before {
      content: '<?php _e('Header','inis-b'); ?>';
    }
	</style>
	<?php
}
add_action( 'customize_controls_print_styles', 'inis_b_customizer_control_styles', 999 );
