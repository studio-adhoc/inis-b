( function( $ ) {

  wp.customize( 'inis_b_theme_header_text_color', function( value ) {
		value.bind( function( newval ) {
      if (newval == 'theme-color') {
        var colorval = wp.customize( 'inis_b_theme_color')();
      } else {
        var colorval = newval;
      }
      $( '.a_header' ).css( 'color', colorval );
		} );
	} );

  wp.customize( 'inis_b_theme_header_background_color', function( value ) {
    value.bind( function( newval ) {
      if (newval == 'theme-color') {
        var colorval = wp.customize( 'inis_b_theme_color')();
      } else {
        var colorval = newval;
      }
      $( '.a_header' ).css( 'background-color', colorval );
    } );
  } );

  wp.customize( 'inis_b_theme_header_image', function( value ) {
    value.bind( function( newval ) {
      $( '.a_header' ).css( 'background-image', 'url(' + newval + ')' );
    } );
  } );

  wp.customize( 'inis_b_theme_navi_color', function( value ) {
    value.bind( function( newval ) {
      $( '.a_navi' ).css( 'background-color', newval );
    } );
  } );

  wp.customize( 'inis_b_theme_color', function( value ) {
    value.bind( function( newval ) {
      var header_tc_val = wp.customize( 'inis_b_theme_header_text_color')();
      var header_bc_val = wp.customize( 'inis_b_theme_header_background_color')();

      if (header_tc_val == 'theme-color') {
        $( '.a_header' ).css( 'color', newval );
      }

      if (header_bc_val == 'theme-color') {
        $( '.a_header' ).css( 'background-color', newval );
      }

      $( '::selection' ).css( 'background', newval );
      $( '::-moz-selection' ).css( 'background', newval );
      $( '.post-content em, .post-content italic' ).css( 'box-shadow', 'inset 0 0.1em white, inset 0 -0.5em ' + newval );
      $( '.a_navi .main li.current-menu-item  > a, .a_navi .main li.current-menu-parent > a, .a_navi .main li.current-menu-ancestor > a, .a_navi .main li.current_page_parent > a' ).css( 'color', newval );
      $( '.has-neon-color, .a_navi .main li:hover > a, .a_navi .main a:hover, .button a:hover, a.button:hover, button:hover, input[type=submit]:hover, .has-inis-b-theme-color-color' ).css( 'color', newval );
      $( '.has-neon-background-color, .member-list .list-item, .banner .banner-text, .has-inis-b-theme-color-background-color' ).css( 'background-color', newval );
    } );
  } );

} )( jQuery );
