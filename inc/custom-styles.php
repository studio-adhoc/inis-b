<?php
/*-----------------------------------------------------------------------------------*
/* Get Global Styles
/*-----------------------------------------------------------------------------------*/
add_action( 'inis_b_global_styles', 'inis_b_global_styles' );
function inis_b_global_styles() {
	$output = '';

	if (function_exists('wp_get_global_stylesheet')) {
		$output .= wp_get_global_stylesheet();
	}

	echo $output;
}

/*-----------------------------------------------------------------------------------*
/* Get Custom Styles
/*-----------------------------------------------------------------------------------*/
function inis_b_custom_color_image($pageID) {
	$output = '';

	if (get_theme_mod('inis_b_theme_header_image')) {
		$output .= "\t.a_header { background-image: url(" . get_theme_mod('inis_b_theme_header_image') . "); }";
	}

	if (get_theme_mod('inis_b_theme_header_text_color') && get_theme_mod('inis_b_theme_header_text_color') != 'theme-color' ) {
		$output .= "\t.has-inis-b-theme-header-text-color-color { color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
		$output .= "\t.has-inis-b-theme-header-text-color-background-color { background-color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-header-text-color-background-color { border-color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
		$output .= "\t.a_header { color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_header_background_color') && get_theme_mod('inis_b_theme_header_background_color') != 'theme-color') {
		$output .= "\t.has-inis-b-theme-header-background-color-color { color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
		$output .= "\t.has-inis-b-theme-header-background-color-background-color { background-color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-header-background-color-background-color { border-color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
		$output .= "\t.a_header { background-color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_button_color') && get_theme_mod('inis_b_theme_button_color') != '#000000') {
		$output .= "\t.has-inis-b-theme-button-color-color { color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
		$output .= "\t.has-inis-b-theme-button-color-background-color { background-color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-button-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
		$output .= "\t.button a, a.button, a.more-link, a.tribe-events-read-more, .tribe-block__event-website a, .wp-block-button:not(.has-custom-font-size) .wp-block-button__link, button, input[type=submit], input[type=button] { background-color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_navi_color')) {
		$output .= "\t.has-inis-b-theme-navi-color-color { color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
		$output .= "\t.has-inis-b-theme-navi-color-background-color { background-color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-navi-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
		$output .= "\t.a_navi { background-color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_navi_button_color') && get_theme_mod('inis_b_theme_navi_button_color') != '#000000') {
		$output .= "\t.has-inis-b-theme-navi-button-color-color { color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.has-inis-b-theme-navi-button-color-background-color { background-color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-navi-button-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.a_navi .main li a, .navi-button-inner:after { background-color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.a_navi .main .menu-social-navigation-container li a, .a_navi .main .menu-social-navigation-container li a:hover, .a_navi .main .menu-social-navigation-container li a:after { color: " . get_theme_mod('inis_b_theme_navi_button_color') . " !important; }";
	}

	if (get_theme_mod('inis_b_theme_banner_color')) {
		$output .= "\t.banner .banner-text, .internal-header { background-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		$output .= "\t.has-inis-b-theme-banner-color-color { color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		$output .= "\t.has-inis-b-theme-banner-color-background-color { background-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-banner-color-background-color { border-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		if (get_theme_mod('inis_b_theme_banner_color_for_navi_button_hover') == 1) {
			$output .= "\t.a_navi .main li a:hover, .a_navi .main .current-menu-item a, .a_navi .main .current-menu-item li a:hover, .navi-button-inner:hover:after, .button a:hover, a.button:hover, a.more-link:hover, a.tribe-events-read-more:hover, .wp-block-button__link:hover, button:hover, input[type=submit]:hover, input[type=button]:hover { background-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
			$output .= "\t.a_navi .main .menu-social-navigation-container li a:hover:after { color: " . get_theme_mod('inis_b_theme_banner_color') . " !important; }";
		}
	}

	if (get_theme_mod('inis_b_theme_color')) {
		$theme_color = get_theme_mod('inis_b_theme_color');
		$theme_color_formated = str_replace('#', '', $theme_color);
		list($r, $g, $b) = sscanf($theme_color_formated, "%02x%02x%02x");

		if (get_theme_mod('inis_b_theme_header_text_color') && get_theme_mod('inis_b_theme_header_text_color') == 'theme-color' ) {
			$output .= "\t.a_header { color: " . $theme_color . "; }";
		}
		if (get_theme_mod('inis_b_theme_header_background_color') && get_theme_mod('inis_b_theme_header_background_color') == 'theme-color') {
			$output .= "\t.a_header { background-color: " . $theme_color . "; }";
		}

		if ($theme_color == '#ffffff') {
			$selection_background = '#e2e4e7';
		} else {
			$selection_background = $theme_color;
		}

		if ($theme_color == '#000000') {
			$selection_text = '#ffffff';
		} else {
			$selection_text = '#000000';
		}

		$output .= "\t::selection { color:  " . $selection_text . "; background: " . $selection_background . "; }";
		$output .= "\t::-moz-selection { color:  " . $selection_text . "; background: " . $selection_background . "; }";
		$output .= "\t.a_navi .main li.current-menu-item > a, .a_navi .main li.current-menu-parent > a, .a_navi .main li.current-menu-ancestor > a, .a_navi .main li.current_page_parent > a { color: " . $theme_color . "; }";
		$output .= "\t.a_navi .main li.current-menu-item > button, .a_navi .main li.current-menu-parent > button, .a_navi .main li.current-menu-ancestor > button, .a_navi .main li.current_page_parent > button { color: " . $theme_color . "; }";
		$output .= "\t.a_navi .main li.intern > a {  color: #FFF; }";
		$output .= "\t.has-neon-color, .a_navi .main li:hover > a, .a_navi .main li:hover > button, .a_navi .main li.intern > a:hover, .a_navi .main a:hover, .navi-button-inner:hover:after, .wp-block-button a:hover, .button a:hover, a.button:hover, a.more-link:hover, button:not(.submenu-toggle):hover, input[type=submit]:hover, input[type=button]:hover { color: " . $theme_color . "; }";
		$output .= "\t#tribe-events .tribe-events-button:hover, .tribe-events-button.tribe-active:hover, .tribe-events-button.tribe-inactive, .tribe-events-button:hover, a.tribe-events-read-more:hover, .tribe-events-list .tribe-events-loop .tribe-event-featured a:hover { color: " . $theme_color . "; }";
		$output .= "\t#tribe-events-content table.tribe-events-calendar .type-tribe_events.tribe-event-featured, .tribe-events-list .tribe-events-loop .tribe-event-featured { background-color: " . $theme_color . "; }";
		$output .= "\t.has-neon-background-color, .member-list-compact .list-item { background-color: " . $theme_color . "; }";
		$output .= "\t.has-inis-b-theme-color-color { color: " . $theme_color . "; }";
		$output .= "\t.has-inis-b-theme-color-background-color { background-color: " . $theme_color . "; }";
		$output .= "\t.post-content .is-style-box-group.has-inis-b-theme-color-background-color { border-color: " . $theme_color  . "; }";
		$output .= "\t.wp-block-button a.has-inis-b-theme-color-background-color:hover { color: #000; }";

		$output .= "\t.n2g_embed_signup button:hover { color: " . $theme_color . " !important; }";

		if (get_theme_mod('inis_b_theme_banner_color_for_navi_button_hover') == 1) {
			$output .= "\t.a_navi .main .current-menu-item li a { background-color: " . $theme_color . "; }";
		}

		if (get_theme_mod('inis_b_underline_light') == 1) {
			$output .= "\t.post-title em, .post-title italic, .post-content em, .post-content italic { box-shadow: inset 0 0.1em white, inset 0 -0.5em rgba(" . $r . "," . $g . "," . $b . ",0.4); }";
		} else {
			$output .= "\t.post-title em, .post-title italic, .post-content em, .post-content italic { box-shadow: inset 0 0.1em white, inset 0 -0.5em " . $theme_color . "; }";
		}
	}

	if (get_theme_mod('inis_b_theme_navi_button_text_color_light') == 1) {
		$output .= "\t.a_navi .main li:hover > a, .a_navi .main li.intern > a:hover, .a_navi .main a:hover, .navi-button-inner:hover:after, .wp-block-button a:hover, .button a:hover, a.button:hover, a.more-link:hover, button:not(.submenu-toggle):hover, input[type=submit]:hover, input[type=button]:hover { color: #FFF; }";
		$output .= "\t.a_navi .main li.current-menu-item > a, .a_navi .main li.current-menu-parent > a, .a_navi .main li.current-menu-ancestor > a, .a_navi .main li.current_page_parent > a { color: #FFF; }";
		$output .= "\t.a_navi .main li.current-menu-item > button, .a_navi .main li.current-menu-parent > button, .a_navi .main li.current-menu-ancestor > button, .a_navi .main li.current_page_parent > button { color: #FFF; }";
	}

	/* Tribe Events Calendar */
	$tribe_customizer = get_option('tribe_customizer');
	if (isset($tribe_customizer['global_elements']['accent_color'])) {
		$accent_color_pure = str_replace('#', '', $tribe_customizer['global_elements']['accent_color']);
		$output .= "\t.tribe-events .tribe-events-c-messages__message--notice:before { background-image: url(\"data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 21 23'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cpath stroke='%23141827' d='M.5 2.5h20v20H.5z'/%3E%3Cpath stroke='%23" . $accent_color_pure . "' stroke-linecap='round' d='M7.583 11.583l5.834 5.834m0-5.834l-5.834 5.834'/%3E%3Cpath stroke='%23141827' stroke-linecap='round' d='M4.5.5v4m12-4v4'/%3E%3Cpath stroke='%23141827' stroke-linecap='square' d='M.5 7.5h20'/%3E%3C/g%3E%3C/svg%3E\"); }";
		$output .= "\t.tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date, .tribe-events .tribe-events-calendar-month__day--current .tribe-events-calendar-month__day-date-link { color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.tribe-events .tribe-events-c-ical__link, .tribe-events .tribe-events-c-ical__link:visited { color: " . $tribe_customizer['global_elements']['accent_color'] . "; border-color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.tribe-events .tribe-events-c-ical__link:active, .tribe-events .tribe-events-c-ical__link:focus, .tribe-events .tribe-events-c-ical__link:hover { background-color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.tribe-events .datepicker .day.active, .tribe-events .datepicker .day.active.focused, .tribe-events .datepicker .day.active:focus, .tribe-events .datepicker .day.active:hover, .tribe-events .datepicker .month.active, .tribe-events .datepicker .month.active.focused, .tribe-events .datepicker .month.active:focus, .tribe-events .datepicker .month.active:hover, .tribe-events .datepicker .year.active, .tribe-events .datepicker .year.active.focused, .tribe-events .datepicker .year.active:focus, .tribe-events .datepicker .year.active:hover { background: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.tribe-block__events-link .tribe-block__btn--link a { color:#FFF; border:0; background-color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.tribe-block__events-link .tribe-block__btn--link img { visibility: hidden; }";
		$output .= "\t.tribe-block__events-link .tribe-block__btn--link a { background-image: url(\"data:image/svg+xml,%3Csvg width='26' height='15' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.6 7.576H9.227v1.732H12.6v3.742a.2.2 0 0 1-.198.2H1.918a.199.199 0 0 1-.198-.2V5.092c0-.111.089-.201.198-.201h10.485a.2.2 0 0 1 .198.2v2.485zm5.755-3.86l-.066.067L17.16 4.93l2.601 2.646H14.33V2.843a.797.797 0 0 0-.79-.803h-.74c-.034.003-.32.004-.856.004V.804a.797.797 0 0 0-.79-.804c-.446 0-.8.36-.8.803v1.24H3.992V.804A.797.797 0 0 0 3.202 0c-.447 0-.8.36-.8.803v1.24h-.796c-.041 0-.058-.003-.075-.003H.79c-.436 0-.79.36-.79.803V3.91c0 .055.006.108.016.16v8.978a.36.36 0 0 0-.008.082v1.067c0 .443.354.803.79.803h.74a12956.843 12956.843 0 0 1 12.01 0c.437 0 .79-.36.79-.803V13.13a.36.36 0 0 0-.008-.082v-3.74h5.43l-2.599 2.643 1.192 1.215L23 8.44l-4.645-4.725z' fill='%23ffffff' fillRule='evenodd'/%3E%3C/svg%3E\"); background-repeat:no-repeat; background-position: 15px  center; }";
		$output .= "\t.tribe-common .tribe-common-c-btn, .tribe-common a.tribe-common-c-btn { background-color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
		$output .= "\t.single-tribe_events a.tribe-events-gcal, .single-tribe_events a.tribe-events-gcal:hover, .single-tribe_events a.tribe-events-ical, .single-tribe_events a.tribe-events-ical:hover { color: " . $tribe_customizer['global_elements']['accent_color'] . "; }";
	}

	return apply_filters('inis_b_custom_color_css_output', $output);
}

/*-----------------------------------------------------------------------------------*
/* Get Custom Block Editor Styles
/*-----------------------------------------------------------------------------------*/
function inis_b_custom_backend_color_image() {
	$output = '';

	if (get_theme_mod('inis_b_theme_color')) {
		$theme_color = get_theme_mod('inis_b_theme_color');
		$theme_color_formated = str_replace('#', '', $theme_color);
		list($r, $g, $b) = sscanf($theme_color_formated, "%02x%02x%02x");

		$output .= "\t.has-inis-b-theme-color-color { color: " . $theme_color . "; }";
		$output .= "\t.has-inis-b-theme-color-background-color { background-color:" . $theme_color . "; }";
		if (get_theme_mod('inis_b_underline_light') == 1) {
			$output .= "\t.edit-post-visual-editor em, .edit-post-visual-editor italic { box-shadow: inset 0 0.1em white, inset 0 -0.5em rgba(" . $r . "," . $g . "," . $b . ",0.4); }";
		} else {
			$output .= "\t.edit-post-visual-editor em, .edit-post-visual-editor italic { box-shadow: inset 0 0.1em white, inset 0 -0.5em " . $theme_color . "; }";
		}
	}

	if (get_theme_mod('inis_b_theme_header_text_color') && get_theme_mod('inis_b_theme_header_text_color') != 'theme-color' ) {
		$output .= "\t.has-inis-b-theme-header-text-color-color { color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
		$output .= "\t.has-inis-b-theme-header-text-color-background-color { background-color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-header-text-color-background-color { border-color: " . get_theme_mod('inis_b_theme_header_text_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_header_background_color') && get_theme_mod('inis_b_theme_header_background_color') != 'theme-color') {
		$output .= "\t.has-inis-b-theme-header-background-color-color { color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
		$output .= "\t.has-inis-b-theme-header-background-color-background-color { background-color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-header-background-color-background-color { border-color: " . get_theme_mod('inis_b_theme_header_background_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_button_color') && get_theme_mod('inis_b_theme_button_color') != '#000000') {
		$output .= "\t.has-inis-b-theme-button-color-color { color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
		$output .= "\t.has-inis-b-theme-button-color-background-color { background-color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-button-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_button_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_navi_color')) {
		$output .= "\t.has-inis-b-theme-navi-color-color { color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
		$output .= "\t.has-inis-b-theme-navi-color-background-color { background-color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-navi-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_navi_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_navi_button_color') && get_theme_mod('inis_b_theme_navi_button_color') != '#000000') {
		$output .= "\t.has-inis-b-theme-navi-button-color-color { color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.has-inis-b-theme-navi-button-color-background-color { background-color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-navi-button-color-background-color  { border-color: " . get_theme_mod('inis_b_theme_navi_button_color') . "; }";
	}

	if (get_theme_mod('inis_b_theme_banner_color')) {
		$output .= "\t.has-inis-b-theme-banner-color-color { color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		$output .= "\t.has-inis-b-theme-banner-color-background-color { background-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
		$output .= "\t.is-style-box-group.has-inis-b-theme-banner-color-background-color { border-color: " . get_theme_mod('inis_b_theme_banner_color') . "; }";
	}

	return apply_filters('inis_b_custom_backend_color_css_output', $output);
}
