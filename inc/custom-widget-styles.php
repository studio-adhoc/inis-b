<?php
/*-----------------------------------------------------------------------------------*
/* Custom CSS-Class for Widgets
/*-----------------------------------------------------------------------------------*/
function inis_b_widget_classes($params) {
	global $my_widget_num; // Global a counter array
	$this_id = $params[0]['id']; // Get the id for the current sidebar we're processing
	$arr_registered_widgets = wp_get_sidebars_widgets(); // Get an array of ALL registered widgets

	if(!$my_widget_num) {// If the counter array doesn't exist, create it
		$my_widget_num = array();
	}

	if(!isset($arr_registered_widgets[$this_id]) || !is_array($arr_registered_widgets[$this_id])) { // Check if the current sidebar has no widgets
		return $params; // No widgets in this sidebar... bail early.
	}

	if(isset($my_widget_num[$this_id])) { // See if the counter array has an entry for this sidebar
		$my_widget_num[$this_id] ++;
	} else { // If not, create it starting with 1
		$my_widget_num[$this_id] = 1;
	}

	$class = 'class="widget-' . $my_widget_num[$this_id] . ' '; // Add a widget number class for additional styling options

	if($my_widget_num[$this_id] % 3 == 0) { // If this is the third widget
		$class .= 'widget-last ';
	}

	$params[0]['before_widget'] = str_replace('class="', $class, $params[0]['before_widget']); // Insert our new classes into "before widget"

	return $params;
}
add_filter('dynamic_sidebar_params','inis_b_widget_classes');

/*-----------------------------------------------------------------------------------*
/* Restrict native search widgets to the 'internal' post type
/*-----------------------------------------------------------------------------------*/
function inis_b_post_type_restriction( $html ) {
	if (is_internal()) {
		// Inject hidden post_type value
		return str_replace(
				'</form>',
				'<input type="hidden" name="post_type" value="internal" /></form>',
				$html
		);
	} else {
		return $html;
	}
}
add_filter( 'get_search_form', 'inis_b_post_type_restriction' );
