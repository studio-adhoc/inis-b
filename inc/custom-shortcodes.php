<?php
/*-----------------------------------------------------------------------------------*/
/* Custom Gallery
/*-----------------------------------------------------------------------------------*/
function custom_gallery($output, $atts) {
    global $post;

    static $instance = 0;
    $instance++;

    // Allow plugins/themes to override the default gallery template.
    //$output = apply_filters('post_gallery', '', $atts);

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $atts['orderby'] ) ) {
        $atts['orderby'] = sanitize_sql_orderby( $atts['orderby'] );
        if ( !$atts['orderby'] )
            unset( $atts['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 3,
        'size'       => 'full',
        'include'    => '',
        'exclude'    => '',
        'link'		 => 'file',
        'type'		 => 'normal',
        'replacement' => ''
    ), $atts));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

	$output = '';

	$itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';
    if ( apply_filters( 'use_default_gallery_style', true ) )

        $gallery_style = "
        <style type='text/css'>
            #{$selector} {
                margin: auto;
            }
            #{$selector} .gallery-item {
                float: {$float};
                margin-top: 10px;
                text-align: center;
                width: {$itemwidth}%;
            }
            #{$selector} img {
                border: 2px solid #cfcfcf;
            }
            #{$selector} .gallery-caption {
                margin-left: 0;
            }
        </style>
        <!-- see gallery_shortcode() in wp-includes/media.php -->";

    $size_class = sanitize_html_class( $size );
   	$gallery_div = "<div id='gallery-{$id}-{$instance}' class='gallery gallery-{$id} {$type}'>";
    $output .= apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
          /* Open each gallery row. */
          if ( $columns > 0 && $i % $columns == 0 && $type != 'item-float' )
              $output .= "<div class='gallery-row gallery-clear'>";

          $output .= "<{$itemtag} class='gallery-item col-{$columns}'>";
          $output .= "<{$icontag} class='gallery-icon'>";

          /* Add the image. */
          $fullImage = wp_get_attachment_image_src( $attachment->ID, 'full' );
          if ($link == 'none') {
              $output .= wp_get_attachment_image( $attachment->ID, $size );
          } else {
              $output .= "<a href='" . $fullImage[0] . "' class='thickbox' rel='gallery-{$instance}'>" . wp_get_attachment_image( $attachment->ID, $size ) . "</a>";
          }

          $output .= "</{$icontag}>";

          if ( $captiontag && trim($attachment->post_excerpt) ) {
              $output .= "
                  <{$captiontag} class='wp-caption-text gallery-caption'><span class='caption-inner'>
                  " . wptexturize($attachment->post_excerpt) . "
                  </span></{$captiontag}>";
          }

          $output .= "</{$itemtag}>";

          /* Close gallery row. */
          if ( $columns > 0 && ++$i % $columns == 0 && $type != 'item-float' )
              $output .= "</div>";
    }

		/* Close gallery row. */
		if ( $columns > 0 && $i % $columns !== 0 && $type != 'item-float' )
			$output .= "</div>";

	    $output .= "</div>\n";

    return $output;
}
add_filter( 'post_gallery', 'custom_gallery', 10, 2 );
add_filter( 'use_default_gallery_style', '__return_false' );

/*-----------------------------------------------------------------------------------*/
/* Remove wpautop for Shortcodes
/*-----------------------------------------------------------------------------------*/
function remove_wpautop($content) {
	$content = do_shortcode( shortcode_unautop( $content ) );
	$content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content);
	return $content;
}

/*-----------------------------------------------------------------------------------*/
/* Shortcode small: gibt Text in einer kleineren Schriftgröße aus
/*-----------------------------------------------------------------------------------*/
function small_default_atts() {
  return array(
    'display' => 'block'
	);
}

function small( $atts, $content = null ) {
  $default_atts = small_default_atts();
	$atts = shortcode_atts( $default_atts, $atts );

	if ( $atts['display'] == 'block' ) {
		$tag = 'div';
	} else {
		$tag = 'span';
	}

	return '<' . $tag . ' class="small">' . remove_wpautop($content) . '</' . $tag . '>';
}
add_shortcode('small', 'small');

/*-----------------------------------------------------------------------------------*/
/* Shortcode line: gibt eine Linie aus
/*-----------------------------------------------------------------------------------*/
function line() {
	return '<div class="clear line"></div>';
}
add_shortcode('line', 'line');

/*-----------------------------------------------------------------------------------*/
/* Shortcode divider: erzeugt einen Abstand
/*-----------------------------------------------------------------------------------*/
function divider_default_atts() {
  return array(
		'height' => ''
	);
}

function divider( $atts ) {
  $default_atts = divider_default_atts();
	$atts = shortcode_atts( $default_atts, $atts );

	$height_style = '';

	if ( $atts['height'] != '' ) {
		$height_style = ' style="height:' . $atts['height'] . 'px;"';
	}

	return '<div class="clear divider"' . $height_style . '></div>';
}
add_shortcode('divider', 'divider');

/*-----------------------------------------------------------------------------------*/
/* Shortcode button: erzeugt einen Button
/*-----------------------------------------------------------------------------------*/
function button_default_atts() {
  return array(
    'size' => 'medium',
		'visible' => 'normal',
    'display' => ''
	);
}

function button( $atts, $content = null ) {
  $default_atts = button_default_atts();
	$atts = shortcode_atts( $default_atts, $atts );

  if ( $atts['display'] != 'block' ) {
      $tag = 'span';
  } else {
      $tag = 'div';
  }

	return '<' . $tag . ' class="button ' . $atts['size'] . ' ' . $atts['visible'] . '">' . do_shortcode($content) . '</' . $tag . '><' . $tag . ' class="clear"></' . $tag . '>';
}
add_shortcode('button', 'button');

/*-----------------------------------------------------------------------------------*/
/* Shortcode mail: verschlüsselt eine E-Mailadresse.
/*-----------------------------------------------------------------------------------*/
function antispam_mails_default_atts() {
  return array(
		'address' => '',
		'linktext' => '',
    'linkicon' => '',
		'prefix' => '',
		'display' => 'inline',
    'class' => ''
	);
}

function antispam_mails( $atts ) {
  $default_atts = antispam_mails_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	$output = '';
	if ( $atts['address'] != '' ) {
		$icon = '';
    $link_class = '';
		if ($atts['linkicon'] != '') {
			$icon = '<img src="' . $atts['linkicon'] . '" alt="icon-mail" class="mail-icon" />';
		}

		if ( $atts['linktext'] != '' ) {
			$text = $icon . $atts['linktext'];
		} elseif ( $icon != '' ) {
			$text = $icon;
		} else {
			$text = antispambot($atts['address']);
		}

    if ( $atts['class'] != '' ) {
			$link_class = ' class="' .  $atts['class'] . '"';
		}

		$mail = '<a href="mailto:' . antispambot($atts['address']) . '"' . $link_class . '>' . $atts['prefix'] . $text . '</a>';

		if ( $atts['display'] != 'inline' ) {
			$output .= '<p class="mail__block">' . $mail . '</p>';
		} else {
			$output .= $mail;
		}
	}

	return $output;
}
add_shortcode('mail', 'antispam_mails');

/*-----------------------------------------------------------------------------------*/
/* Shortcode newsletter: setzt ein Newsletter-Formular ein.
/*-----------------------------------------------------------------------------------*/
function newsletter_default_atts() {
  return array(
		'list_url' => '',
		'user' => '',
		'id' => '',
    'type' => 'mailchimp',
		'show_firstname' => 'true',
		'show_lastname' => 'true',
		'use_placeholder' => 'false',
	);
}

function newsletter($atts) {
  $default_atts = newsletter_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	$output = '';

	$placeholder_class = '';
	$placeholder_mail = '';
	$placeholder_first_name = '';
	$placeholder_last_name = '';

	if ($atts['use_placeholder'] != 'false') {
		$placeholder_class = ' class="use-placeholder"';
		$placeholder_mail = ' placeholder="' . __('Email Address', 'inis-b') . ' *"';
		$placeholder_first_name = ' placeholder="' . __('First Name', 'inis-b') . '"';
		$placeholder_last_name = ' placeholder="' . __('Last Name', 'inis-b') . '"';
	}

  if ($atts['type'] == 'mailchimp' && $atts['list_url'] != '' && $atts['user'] != '' && $atts['id'] != '' ) {
      $output .= '<div id="mc_embed_signup"' . $placeholder_class . '>';
        $output .= '<form action="https://' . $atts['list_url'] . '/subscribe/post?u=' . $atts['user'] . '&amp;id=' . $atts['id'] . '" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>';
          $output .= '<div id="mc_embed_signup_scroll">';
            $output .= '<div class="mc-field-group">';
              $output .= '<label for="mce-EMAIL">' . __('Email Address', 'inis-b') . ' <span class="asterisk">*</span></label>';
              $output .= '<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL"' . $placeholder_mail . '>';
            $output .= '</div>';
    				if ($show_firstname == 'true') {
    					$output .= '<div class="mc-field-group">';
    						$output .= '<label for="mce-FNAME">' . __('First Name', 'inis-b') . ' </label>';
    						$output .= '<input type="text" value="" name="FNAME" class="" id="mce-FNAME"' . $placeholder_first_name . '>';
    					$output .= '</div>';
    				}
    				if ($show_lastname == 'true') {
    					$output .= '<div class="mc-field-group">';
    						$output .= '<label for="mce-LNAME">' . __('Last Name', 'inis-b') . ' </label>';
    						$output .= '<input type="text" value="" name="LNAME" class="" id="mce-LNAME"' . $placeholder_last_name . '>';
    					$output .= '</div>';
    				}
            $output .= '<div id="mce-responses" class="clear">';
              $output .= '<div class="response" id="mce-error-response" style="display:none"></div>';
              $output .= '<div class="response" id="mce-success-response" style="display:none"></div>';
            $output .= '</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->';
            $output .= '<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_' . $atts['user'] . '_' . $atts['id'] . '" tabindex="-1" value=""></div>';
            $output .= '<div><input type="submit" value="' . __('Subscribe', 'inis-b') . ' " name="subscribe" id="mc-embedded-subscribe" class="mc-button"></div>';
            $output .= '<div class="clear"></div>';
          $output .= '</div>';
        $output .= '</form>';
  		$output .= '<div class="indicates-required"><p><span class="asterisk">*</span> ' . __('indicates required', 'inis-b') . '</p></div>';
    $output .= '</div>';
  } elseif ($atts['type'] == 'newsletter2go' && $atts['id'] != '' ) {
    $output .= '<div class="n2g_embed_signup">';
      $output .= '<script id="n2g_script">!function(e,t,n,c,r,a,i){e.Newsletter2GoTrackingObject=r,e[r]=e[r]||function(){(e[r].q=e[r].q||[]).push(arguments)},e[r].l=1*new Date,a=t.createElement(n),i=t.getElementsByTagName(n)[0],a.async=1,a.src=c,i.parentNode.insertBefore(a,i)}(window,document,"script","https://static.newsletter2go.com/utils.js","n2g");var config = {"container": {"type": "div","class": "","style": ""},"row": {"type": "div","class": "","style": "margin-top: 15px;"},"columnLeft": {"type": "div","class": "","style": ""},"columnRight": {"type": "div","class": "","style": ""},"label": {"type": "label","class": "","style": ""}};n2g("create", "' . $atts['id'] . '");n2g("subscribe:createForm", config);</script>';
    $output .= '</div>';
  }

	return $output;
}
add_shortcode('newsletter', 'newsletter');

/*-----------------------------------------------------------------------------------*/
/* Shortcode login: setzt ein Login-Formular ein.
/*-----------------------------------------------------------------------------------*/
function member_login() {
  $args = array(
    'echo' => false,
    'label_username' => __( 'Username', 'inis-b' )
  );

  $output = '';

	if (get_theme_mod('inis_b_active_internal') == '1') {
    $internal_url = get_post_type_archive_link( 'internal' );
    $args['redirect'] = $internal_url;
  }

	if (function_exists('cn_cookies_accepted') && !cn_cookies_accepted()) {
		$output .= '<p class="cookie-status cookies-not-accepted red">';
			$output .= '<span class="acceptance-text">' . __('You need to accept cookies to log into this website.','inis-b') . '</span><br />';
			//$output .= do_shortcode( '[cookies_revoke title="' .  __('Change your cookie status?','inis-b') . '"]' );
      $output .= '<a href="#" id="cn-accept-cookie" data-cookie-set="accept" class="cn-set-cookie button">' . __('Accept Cookies?','inis-b') . '</a>';
		$output .= '</p>';
	} else {
  	$output = wp_login_form( $args );
	}

  return $output;
}
add_shortcode('login', 'member_login');

/*-----------------------------------------------------------------------------------*/
/* Shortcode cookies_not_accepted: blendet Inhalte ein, wenn Cookies nicht akzeptiert wurden.
/*-----------------------------------------------------------------------------------*/
function inis_cn_cookies_not_accepted( $atts, $content = null ) {
  if (function_exists('cn_cookies_accepted') && !cn_cookies_accepted()) {
	   return do_shortcode($content);
  } else {
    return '';
  }
}
add_shortcode('cookies_not_accepted', 'inis_cn_cookies_not_accepted');

/*-----------------------------------------------------------------------------------*/
/* Shortcode cn-status: stellt den Cookie-Status da.
/*-----------------------------------------------------------------------------------*/
function cn_status() {
  $output = '';

  if (function_exists('cn_cookies_accepted')) {
    $accepted_class = 'cookies-not-accepted red';
    $accepted_text = __('Cookies not accepted','inis-b');

    if (cn_cookies_accepted()) {
      $accepted_class = 'cookies-accepted green';
      $accepted_text = __('Cookies accepted','inis-b');
    }
    $output .= '<p class="box dark-box cookie-status ' . $accepted_class . '">';
      $output .= __('Current status','inis-b') . ': <span class="acceptance-text">' . $accepted_text . '</span><br />';
      $output .= do_shortcode( '[cookies_revoke title="' .  __('Change your cookie status?','inis-b') . '"]' );
    $output .= '</p>';
  }

	return $output;
}
add_shortcode('cn-status', 'cn_status');

/*-----------------------------------------------------------------------------------*/
/* Shortcode sharer: setzt einen Teilen-Button ein.
/*-----------------------------------------------------------------------------------*/
function sharer_default_atts() {
  global $post;

  $pID = '';

  if (isset($post)) {
    $pID = $post->ID;
  }

  return array(
    'id' => $pID,
    'align' => 'center',
    'display' => 'block',
    'button_text' => __('Share', 'inis-b')
	);
}

function sharer( $atts ) {
  $default_atts = sharer_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	return get_custom_sharer($atts['id'],$atts['align'],$atts['display'],$atts['button_text']) ;
}
add_shortcode('sharer', 'sharer');

function custom_sharer($pID,$align='center',$display='block',$button_text='Share') {
	echo get_custom_sharer($pID,$align,$display,$button_text);
}

function get_custom_sharer($pID,$align='center',$display='block',$button_text='Share') {
	$output = '<div class="sharer-wrapper align-' .  $align. ' display-' . $display . '">';
		$output .= '<div class="sharer">';
			$output .= '<div class="sharer-headline sharer-button">' . $button_text . '</div>';
			$output .= '<nav class="single-navigation sharer-navigation">';
				$output .= '<div class="filter-button button-permalink"><a href="' . get_permalink($pID) . '">' . __('Permalink', 'inis-b') . '</a></div>';

				$title_raw = get_the_title($pID);
				$title_raw_strip = strip_tags($title_raw);
				$title = esc_attr($title_raw_strip);

				$output .= '<div class="filter-button button-mail"><a href="mailto:?subject=' . $title . '&amp;body=Link%20zur%20Seite:%20' . get_permalink($pID) . '">' . __('Mail', 'inis-b') . '</a></div>';
				$output .= '<div class="filter-button button-facebook"><a href="http://www.facebook.com/sharer.php?u=' . urlencode(get_permalink($pID)) . '&amp;t=' . $title . '" target="_blank">' . __('Facebook', 'inis-b') . '</a></div>';
				$output .= '<div class="filter-button button-x"><a href="https://x.com/intent/post?url=' . urlencode(get_permalink($pID)) . '&amp;text=' . $title . '" target="_blank">' . __('X', 'inis-b') . '</a></div>';
        //$output .= '<div class="filter-button button-twitter"><a href="https://twitter.com/intent/tweet?url=' . urlencode(get_permalink($pID)) . '&amp;text=' . $title . '" target="_blank">' . __('Twitter', 'inis-b') . '</a></div>';
			$output .= '</nav>';
		$output .= '</div>';
	$output .= '</div>';

	return $output;
}

/*-----------------------------------------------------------------------------------*/
/* Shortcode link-list: wandelt eine UL in eine Link-Liste um.
/*-----------------------------------------------------------------------------------*/
function link_list_default_atts() {
  return array(
    'class' => 'download-list'
	);
}

function link_list( $atts, $content = null ) {
  $default_atts = link_list_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	$list = '<div class="link-list ' . $atts['class'] . '">';
	$list .= remove_wpautop($content);
	$list .= '</div>';

	return $list;
}
add_shortcode('link-list', 'link_list');

/*-----------------------------------------------------------------------------------*/
/* Shortcode column: setzt eine Mehrspaltigkeit ein.
/*-----------------------------------------------------------------------------------*/
function column_default_atts() {
  return array(
    'align' => 'left',
    'cols' => 2,
    'width' => '46%'
	);
}

function column( $atts, $content = null ) {
  $default_atts = column_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	$style = '';
	$clear_before = '';
	$clear_after = '';
  $col_width = ' style="width:' . $atts['width'] . ';"';

	if ($atts['align'] == 'left') {
		$clear_before = '<div class="clear"></div>';
		$style = ' col-left col-' . $atts['cols'];
	}

	if ($atts['align'] == 'right') {
		$clear_after = '<div class="clear"></div>';
		$style = ' col-right col-' . $atts['cols'];
	}

	return $clear_before . '<div class="column' . $style . '"' . $col_width . '>' . remove_wpautop($content) . '</div>' . $clear_after;
}
add_shortcode('column', 'column');

/*-----------------------------------------------------------------------------------*/
/* Shortcode page-teaser: setzt einen Teaser zu einer Seite ein.
/*-----------------------------------------------------------------------------------*/
function page_teaser_default_atts() {
  return array(
    'id' => ''
	);
}

function page_teaser( $atts ) {
  $default_atts = page_teaser_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

  $output = '';

  if ($atts['id']) {
    $output .= do_shortcode('[post-list id="' . $atts['id'] . '" type="page"]');
  }

  return $output;
}
add_shortcode( 'page-teaser', 'page_teaser' );

/*-----------------------------------------------------------------------------------*/
/* Shortcode post-list: generiert ein Postliste.
/*-----------------------------------------------------------------------------------*/
function post_list_default_atts() {
  $atts = array(
    'type' => 'post',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'meta_key' => '',
    'id' => '',
    'cat' => '',
    'tag' => '',
    'exclude_cat' => '',
    'post__in' => '',
		'posts_per_page' => -1,
    'layout' => 'post-list',
    'only_partner_posts' => '',
    'only_own_posts' => '',
    'width' => 'alignnormal',
    'month_header' => 'true',
    'letter_header' => '',
    'offset_header' => '',
    'get_offset_varname' => 'letter',
    'offset_archive_link' => '',
    'empty_text' => '',
    'display_content' => ''
	);

  return $atts;
}

function post_list( $atts ) {
  global $post;

  $output = '';

  if (isset($atts['layout']) && $atts['layout'] == 'members-accordion') {
    $output .= member_accordion($atts);
  } else {
    $order_set = array_key_exists('order', $atts) ? true : false;
    $orderby_set = array_key_exists('orderby', $atts) ? true : false;

    $default_atts = post_list_default_atts();
    $atts = shortcode_atts( $default_atts, $atts );

    if ( !is_admin() ) {
        $args = array(
          'post_type' => $atts['type'],
          'posts_per_page' => $atts['posts_per_page'],
          'post_status' => $atts['post_status'],
          'orderby' => $atts['orderby'],
          'order' => $atts['order']
        );

        if ($atts['meta_key'] != '') {
          $args['meta_key'] = $atts['meta_key'];
        }

        if ($atts['id'] != '') {
          $args['p'] = $atts['id'];
        }

        if ($atts['layout'] == 'subpages') {
          $args['post_type'] = 'page';
          $args['post_parent'] = $post->ID;
        }

        if (get_theme_mod('partner_post_topic') && $atts['only_partner_posts'] == 'true') {
          $args['post__not_in'] = get_sticky_posts('own');
          $args['meta_query'] = array(
            array(
              'key' => 'platform_post_id',
              'compare' => 'EXISTS'
            ),
            array(
              'key' => 'platform_post_id',
              'value' => '',
              'compare' => '!='
            )
          );
        }

        if (get_theme_mod('partner_post_topic') && $atts['only_own_posts'] == 'true') {
          $args['post__not_in'] = get_sticky_posts('external');
          $args['meta_query'] = array(
            'relation' => 'OR',
            array(
              'key' => 'platform_post_id',
              'compare' => 'NOT EXISTS'
            ),
            array(
              'key' => 'platform_post_id',
              'value' => ''
            )
          );
        }

        if ($atts['type'] == 'event') {
          $atts['layout'] = 'event-list';
          $args['order'] = 'ASC';
          $args['orderby'] = 'meta_value';
          $args['meta_key'] = 'event_start_date';
          $args['meta_query'] = array(
            array(
              'key' => 'event_start_date',
              'value' => date('Y-m-d'),
              'compare' => '>'
            )
          );
        }

        if ($atts['type'] == 'tribe_events') {
          $args['orderby'] = 'meta_value';
          $args['meta_key'] = '_EventStartDate';
          $args['meta_query'] = array(
            array(
              'key' => '_EventStartDate',
              'value' => date('Y-m-d H:i'),
              'compare' => '>'
            )
          );
        }

        if ($atts['type'] == 'project') {
          $args['order'] = $order_set == false ? 'ASC' : $atts['order'];
          $args['orderby'] = $orderby_set == false ? 'menu_order' : $atts['orderby'];
        }

        if ($atts['type'] == 'member') {
          $args['order'] = $order_set == false ? 'ASC' : $atts['order'];
          $args['orderby'] = $orderby_set == false ? 'meta_value title' : $atts['orderby'];
          if ( $orderby_set == false && $atts['meta_key'] == '' ) {
            $args['meta_key'] = 'member_sort';
            $args['meta_query'] = array(
              'relation' => 'OR',
              array(
                'key' => 'member_sort',
                'compare' => 'EXISTS'
              ),
              array(
                'key' => 'member_sort',
                'compare' => 'NOT EXISTS'
              )
            );
          }
        }

        if ( $atts['tag'] != '' ) {
          $args['tag'] = $atts['tag'];
        }

        if ( $atts['cat'] != '' ) {
          if ($atts['type'] == 'post') {
            $args['cat'] = $atts['cat'];
          } else {
            if ($atts['type'] == 'event') {
            	$taxonomy = 'event-category';
            } elseif ($atts['type'] == 'project') {
              $taxonomy = 'project-category';
            } elseif ($atts['type'] == 'member') {
              $taxonomy = 'member-category';
            }

            $args['tax_query'][] = array(
              'taxonomy' => $taxonomy,
              'field'    => 'term_id',
              'terms'    => $atts['cat'],
            );
          }
        }

        if ( $atts['exclude_cat'] != '' ) {
          $taxonomy = 'category';
          $term_array = explode(',', $atts['exclude_cat']);

          if ($atts['type'] == 'event') {
          	$taxonomy = 'event-category';
          } elseif ($atts['type'] == 'project') {
            $taxonomy = 'project-category';
          } elseif ($atts['type'] == 'member') {
            $taxonomy = 'member-category';
          }

          $args['tax_query'][] = array(
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $term_array,
            'operator' => 'NOT IN',
          );
        }

        if ($atts['post__in'] != '') {
          $post_ids = explode(',', $atts['post__in']);
          $args['post__in'] = $post_ids;
          $args['orderby'] = 'post__in';
        }

        $loop = new WP_Query( $args );

        $counter = 0;

        $w_day = array(
          'Sonntag',
          'Montag',
          'Dienstag',
          'Mittwoch',
          'Donnerstag',
          'Freitag',
          'Samstag'
        );

        $month = array(
          'Januar',
          'Februar',
          'März',
          'April',
          'Mai',
          'Juni',
          'Juli',
          'August',
          'September',
          'Oktober',
          'November',
          'Dezember'
        );


        if( $loop->have_posts() ) {
          $cur_month = '';
          $cur_letter = '';

          if ($atts['type'] == 'member' && $atts['offset_header'] == 'true') {
            $output .= post_list_offset($atts['type'],$atts['get_offset_varname'],$atts['offset_archive_link']);
          }

          $output .= '<div class="list ' . $atts['layout'] . ' ' . $atts['type'] . '-list order-' . $args['order'] . ' ' . $atts['width'] . '">';
          while ( $loop->have_posts() ) : $loop->the_post();
            $counter++;
            $location = '';
            $date = strtotime( get_post_meta(get_the_ID(),'event_start_date',true) );
            $title = get_the_title(get_the_ID());
            $firstCharacter = strtoupper(substr($title,0,1));

      			if ( isset($_GET[$atts['get_offset_varname']]) && $_GET[$atts['get_offset_varname']] != $firstCharacter ) {
      				continue;
      			}

            if ($counter == 1) {
              $firstClass = ' first-item';
            } elseif ($counter == $loop->found_posts ) {
              $firstClass = ' last-item';
            } else {
              $firstClass = '';
            }

            if ($atts['month_header'] == 'true' && $atts['type'] == 'event') {
              $single_month = $month[date('n', $date) - 1];
              if ($single_month != $cur_month) {
                $output .= '<h3 class="month-head" id="head-' . $single_month . '">' . $single_month . '</h3>';
                $cur_month = $single_month;
              }
            }

            if ($atts['letter_header'] == 'true' && $atts['type'] == 'member') {
              $single_letter = strtoupper(substr(get_the_title(get_the_ID()), 0, 1));
              if ($single_letter != $cur_letter) {
                $output .= '<h3 class="letter-head" id="head-' . $single_letter . '">' . $single_letter . '</h3>';
                $cur_letter = $single_letter;
              }
            }

            $output .= '<div class="list-item' . $firstClass . '">';
              if ($atts['layout'] == 'event-list') {
                $output .= '<article id="post-' . get_the_ID() . '" class="single-item event post-' . get_the_ID() . '">';
                  $output .= '<div class="event-date">';
                    $output .= date('d.m.', $date);
                    $output .= '<div class="event-day">' . $w_day[date('w', $date)] . '</div>';
                  $output .= '</div>';
                  $output .= '<div class="event-content">';
                    $output .= '<header class="post-header">';
                      if (get_post_meta(get_the_ID(),'event_location',true)) {
                        $location = ', ' . get_post_meta(get_the_ID(),'event_location',true);
                      }
                      $output .= '<h2 class="post-title"><em>' . get_the_title( get_the_ID() ) . '</em>' . $location . '</h2></header>';
                    $output .= '</header>';
                    if (get_the_content()) {
                      $output .= '<div class="post-content">';
                        $content = get_the_content();
                        $output .= apply_filters('the_content', $content);
                      $output .= '</div>';
                    }
                  $output .= '</div>';
                  $output .= '<div class="clear"></div>';
                $output .= '</article>';
              } else {
                if ($atts['layout'] == 'subpages' && get_post_meta(get_the_ID(),'page_subheadline',true)) {
                  $output .= '<h3 class="post-subheadline">' . get_post_meta(get_the_ID(),'page_subheadline',true) . '</h3>';
                }
                ob_start();
                $part = 'content';
                if ($args['post_type'] == 'page' && $atts['display_content'] == 'full' && isset($args['p'])) {
                  $part = 'page';
                }
  		          if ($args['post_type'] == 'post' && $atts['display_content'] == 'full' && isset($args['p'])) {
                  $part = 'single';
                }
                get_template_part( 'content', $part );
                $ret = ob_get_contents();
                ob_end_clean();

                $output .=  apply_filters('inis_b_post_list_sc_content', $ret, $atts);
              }
            $output .= '</div>';

          endwhile;
          $output .= '</div>';
        } elseif($atts['empty_text'] != '') {
          $output .= '<p>' . $atts['empty_text'] . '</p>';
        }

        wp_reset_query();
    }
  }

	return $output;
}
add_shortcode( 'post-list', 'post_list' );

// Postlist Offset
function post_list_offset($type='post', $get_varname='letter', $archive_link='') {
  $args = array(
    'post_type' => $type,
		'orderby' => 'title',
		'order' => 'ASC',
    'posts_per_page' => -1
  );
  $offset_posts = get_posts($args);

  $offset = '';

  if ($offset_posts) {
    $offset .= '<div class="offset alpha">';
      if (!isset($_GET[$get_varname])) {
        $sel_class_all = ' class="sel"';
      } else {
        $sel_class_all = '';
      }

  		if ($archive_link != '') {
      	$offset .= '<a href="' . $archive_link . '"' . $sel_class_all .'>' . __('Alle') . '</a>';
  		}
      $offset_curr_letter = '';
      foreach ( $offset_posts as $offset_post ) {
        $offset_first_letter = strtoupper(substr($offset_post->post_title,0,1));
          if ($offset_first_letter != $offset_curr_letter && ctype_alnum($offset_first_letter)) {
            if (isset($_GET[$get_varname]) &&  $_GET[$get_varname] == $offset_first_letter) {
              $sel_class = ' class="sel"';
            } else {
              $sel_class = '';
            }
            if ($archive_link != '') {
              $offset_link = '?' . $get_varname . '=' . $offset_first_letter;
            } else {
              $offset_link = '#head-' . $offset_first_letter;
            }
            $offset .= '<a href="' . $offset_link . '"' . $sel_class . '>' . $offset_first_letter . '</a>';
          }
        $offset_curr_letter = $offset_first_letter;
      }
    $offset .= '</div>';
  }

  return $offset;
}

/*-----------------------------------------------------------------------------------*/
/* Shortcode members-accordion: generiert ein spezielle Postliste für Mitglieder.
/*-----------------------------------------------------------------------------------*/
function member_accordion_default_atts() {
  $atts = array(
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_key' => 'member_sort',
    'cat' => '',
    'exclude_cat' => '',
    'layout' => 'show-toggle',
    'width' => 'normal',
    'thumbsize' => 'inis-b-accordion',
    'grayscale' => 'grayscale-active'
	);

  if ( function_exists('get_partner_entries') ) {
    $atts['partner_rest'] = '';
  }

  return $atts;
}

function member_accordion($atts) {
  global $post;

  $default_atts = member_accordion_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	return do_shortcode( '[accordion-list post_type="member" orderby="' . $atts['orderby'] . '" order="' . $atts['order'] . '" meta_key="' . $atts['meta_key'] . '" cat="' . $atts['cat'] . '" exclude_cat="' . $atts['exclude_cat']  . '" layout="' . $atts['layout']  . '" width="' . $atts['width'] . '" thumbsize="' . $atts['thumbsize'] . '" grayscale="' . $atts['grayscale'] . '"]' );
}
add_shortcode('members-accordion', 'member_accordion');

/*-----------------------------------------------------------------------------------*/
/* Shortcode projects-accordion: generiert ein spezielle Postliste für Projekte.
/*-----------------------------------------------------------------------------------*/
function project_accordion_default_atts() {
  $atts = array(
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'meta_key' => '',
    'cat' => '',
    'exclude_cat' => '',
    'layout' => 'show-toggle',
    'width' => 'normal',
    'thumbsize' => 'inis-b-accordion',
    'grayscale' => 'grayscale-active'
	);

  if ( function_exists('get_partner_entries') ) {
    $atts['partner_rest'] = '';
  }

  return $atts;
}

function project_accordion($atts) {
  global $post;

  $default_atts = project_accordion_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	return do_shortcode( '[accordion-list post_type="project" orderby="' . $atts['orderby'] . '" order="' . $atts['order'] . '" meta_key="' . $atts['meta_key'] . '" cat="' . $atts['cat'] . '" exclude_cat="' . $atts['exclude_cat']  . '" layout="' . $atts['layout']  . '" width="' . $atts['width'] . '" thumbsize="' . $atts['thumbsize'] . '" grayscale="' . $atts['grayscale'] . '"]' );
}
add_shortcode('projects-accordion', 'project_accordion');

/*-----------------------------------------------------------------------------------*/
/* Shortcode accordion-list: generiert ein Postliste mit Accordion.
/*-----------------------------------------------------------------------------------*/
function accordion_list_default_atts() {
  $atts = array(
    'post_type' => 'member',
		'orderby' => 'meta_value',
		'order' => 'ASC',
		'meta_key' => 'member_sort',
    'cat' => '',
    'exclude_cat' => '',
    'layout' => 'show-toggle',
    'width' => 'normal',
    'thumbsize' => 'thumbnail',
    'grayscale' => 'grayscale-active'
	);

  if ( function_exists('get_partner_entries') ) {
    $atts['partner_rest'] = '';
  }

  return $atts;
}

function accordion_list($atts) {
  global $post;

  $default_atts = accordion_list_default_atts();
  $atts = shortcode_atts( $default_atts, $atts );

	$output = '';
  $curID = $post->ID;

  $args = array(
    'post_type' => $atts['post_type'],
    'posts_per_page'=> -1,
    'post_status' => 'publish',
    'orderby' => $atts['orderby'],
    'order' => $atts['order'],
    'meta_key' => $atts['meta_key']
  );

  if ($atts['post_type'] == 'event') {
    $taxonomy = 'event-category';
  } elseif ($atts['post_type'] == 'project') {
    $taxonomy = 'project-category';
  } elseif ($atts['post_type'] == 'member') {
    $taxonomy = 'member-category';
  } else {
    $taxonomy = 'category';
  }

  if ( $atts['cat'] != '' ) {
    $args['tax_query'][] = array(
      'taxonomy' => $taxonomy,
      'field'    => 'term_id',
      'terms'    => $atts['cat'],
    );
  }

  if ($atts['exclude_cat'] != '') {
    $term_array = explode(',', $atts['exclude_cat']);
    $args['tax_query'][] = array(
      'taxonomy' => $taxonomy,
      'field'    => 'term_id',
      'terms'    => $term_array,
      'operator' => 'NOT IN',
    );
  }

  $loop = new WP_Query( $args );

  if ( $loop->have_posts() ) {
    $numItems = count($loop->posts);
    $i = 0;

    $output .= '<div class="' . $atts['post_type'] . '-list-extended post-list-extended ' . $atts['layout'] . ' group-field layout-' . $atts['width'] . ' ' . $atts['grayscale'] . '">';
    $desktop_view = '';
    $mobile_view = '';

    while ( $loop->have_posts() ) : $loop->the_post();
      $i++;

      if($i % 4 == 0) {
          $circle = ' last-item last-' . $atts['post_type'];
      } else {
          $circle = '';
      }

      if($i % 2 == 0) {
          $mobile_circle = ' last-mobile-item last-mobile-' . $atts['post_type'];
      } else {
          $mobile_circle = '';
      }

      if ($curID == get_the_ID()) {
          $item_active = ' item-active ' . $atts['post_type'] . '-active';
      } else {
          $item_active = '';
      }

      $single = '';
      $content = '';
      $data_atts = '';
      $item_link = '<a href="' . get_permalink(get_the_ID()) . '">';
      $item_link_end = '</a>';

      if ($atts['layout'] == 'show-toggle') {
        $data_atts = ' data-mobile-toggle="#post-mobile-toggle-' . get_the_ID() . '" data-toggle="#post-toggle-' .  get_the_ID() . '"';
        $item_link = '';
        $item_link_end = '';
      }

      $output .=  '<div id="post-' . get_the_ID() . '" class="page-wrapper single-item single-' . $atts['post_type'] . $circle . $mobile_circle . $item_active . '"' . $data_atts . '>';
          if (has_post_thumbnail( get_the_ID() )) {
            $output .= $item_link . get_the_post_thumbnail( get_the_ID(), $atts['thumbsize'], array( 'class' => 'grayscale grayscale-on' ) ) . $item_link_end;
          } else {
            $output .= $item_link . '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAJYCAIAAAAxBA+LAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyhpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQ1IDc5LjE2MzQ5OSwgMjAxOC8wOC8xMy0xNjo0MDoyMiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTkgKE1hY2ludG9zaCkiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MUI1OEEyRUY4NTM3MTFFOThERjVCRUQ1NjEyNkYyNjYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MUI1OEEyRjA4NTM3MTFFOThERjVCRUQ1NjEyNkYyNjYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDoxQjU4QTJFRDg1MzcxMUU5OERGNUJFRDU2MTI2RjI2NiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDoxQjU4QTJFRTg1MzcxMUU5OERGNUJFRDU2MTI2RjI2NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgCHylYAAAcnSURBVHja7NUxAQAACMMwwL+BucUFD4mEPu0kBQBfjQQAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEAGCEARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARgiAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQJghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghAAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQBGKAEARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARggARgiAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQJghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghAAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQAYIQBGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCABGCIARSgCAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQKAEQJghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABghABwYQUYAPQRBnt43UpDAAAAAElFTkSuQmCC" alt="' . get_the_title(get_the_ID()) . '" src="grayscale grayscale-on" />' . $item_link_end;
          }
          $output .= '<div class="' . $atts['post_type'] . '-title single-item-title"><h2 class="' . $atts['post_type'] . '-title-inner single-item-title-inner">' . $item_link . get_the_title(get_the_ID()) . $item_link_end . '</h2></div>';
      $output .=  '</div>';

      if ($atts['layout'] == 'show-toggle') {
        $single = '<article>';
            $single .= '<div class="post-wrapper ' . $atts['post_type'] . '-wrapper single-item-wrapper">';
                $single .= '<h2 class="' . $atts['post_type'] . '-title-inner single-item-title-inner">' . get_the_title(get_the_ID()) . '</h2>';
                $single .= '<div class="post-content">';
                    $content .= wpautop( get_the_content(get_the_ID()) );
                    $single .= apply_filters('the_content', $content);
                $single .= '</div>';
            $single .= '</div>';
        $single .= '</article>';
      }

      $desktop_view .= '<div id="post-toggle-' . get_the_ID() . '" class="desktop-content">' . $single . '</div>';
      $mobile_view .= '<div id="post-mobile-toggle-' . get_the_ID() . '" class="mobile-content">' . $single . '</div>';

      if( $i % 2 == 0 || $i == $numItems ) {
          $output .= '<div class="mobile-clear"></div>' . $mobile_view;
          $mobile_view = '';
      }

      if( $i % 4 == 0 || $i == $numItems ) {
          $output .= '<div class="clear"></div>' . $desktop_view;
          $desktop_view = '';
      }

    endwhile;
    $output .= '</div>';
  }
  wp_reset_query();

	return $output;
}
add_shortcode('accordion-list', 'accordion_list');

/*-----------------------------------------------------------------------------------*/
/* Shortcode shortcode-wrap: Stellt Shortcodes für die Dokumentation dar
/*-----------------------------------------------------------------------------------*/
function shortcode_wrap( $atts, $content = null ) {
  $output = '';

  if ($content) {
    $output = '<pre class="wp-block-code"><code>' . esc_html($content) . '</code></pre>';
  }

  return $output;
}
add_shortcode('shortcode-wrap', 'shortcode_wrap');
