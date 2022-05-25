var $=jQuery;
var windowWidth = $(window).width();
var windowHeight = $(window).height();

function inis_b_Browser() {
  if (is.chrome()) {
    $('html').addClass('chrome');
  }
  if (is.ie()) {
    $('html').addClass('ie');

    if (is.ie(10)) {
      $('html').addClass('ie-10');
    } else if (is.ie(9)) {
      $('html').addClass('ie-9');
    }
  }
  if (is.safari()) {
    $('html').addClass('safari');
  }
  if (is.firefox()) {
    $('html').addClass('firefox');
  }
  if (is.opera()) {
    $('html').addClass('opera');
  }

	if (is.iphone() || is.androidPhone() || is.blackberry() || is.windowsPhone() || is.mobile() ) {
    $('html').addClass('phone');
  }

	if (is.ipad() || is.androidTablet() || is.windowsTablet() || is.tablet()) {
    $('html').addClass('tablet');
  }

	if (is.iphone() || is.androidPhone() || is.blackberry() || is.windowsPhone() || is.mobile() || is.ipad() || is.androidTablet() || is.windowsTablet() || is.tablet() ) {
    $('html').addClass('touch');
  }

	if (is.iphone() || is.ipad() ) {
    $('html').addClass('ios');
  }
}

function checkHeader() {
  if ($(window).width() > 972) {
    $('.main li:has(> ul)').doubleTapToGo();
  }

	if ( $(document).scrollTop() > $('.a_header').outerHeight() - 78 && $('.a_all').outerHeight() > windowHeight ) {
		$('html').addClass('header-fixed');
	} else {
		$('html').removeClass('header-fixed');
	}

  if($('#cookie-notice').length) {
    $('.a_all').css('margin-bottom', $('#cookie-notice').outerHeight());
  }
}

function close_toggle_post_list() {
  $('.post-list-extended .single-item').removeClass('item-active');
  $('.post-list-extended .single-item .grayscale').addClass('grayscale-off');
  $('.mobile-content, .desktop-content').removeClass('content-active').slideUp();
}

jQuery(document).ready(function($) {
	//Extra Classes
	$('html').removeClass('no-js').addClass('js-active');
  $('img').parent('a').addClass('contains-image');
  $('.wp-block-gallery a').each(function( index ) {
    if ($(this).attr('href').indexOf('/wp-content/uploads') >= 0) {
      $(this).addClass('thickbox').attr('rel','wp-block-gallery');
    }
  });
  $('.is-style-heading-toggle, .is-style-heading-toggle-boxed').addClass('toggle-headline closed').append('<span class="clear line"></span>');
  $('.is-style-heading-toggle + .wp-block-group, .is-style-heading-toggle-boxed + .wp-block-group').addClass('toggle-content closed');

	//Extra Functions
	inis_b_Browser();
	checkHeader();


  $('.cn-revoke-cookie').on('click', function() {
    setTimeout(function(){ checkHeader(); }, 500);
  });

	$(window).on('resize', function(e) {
		if ($(window).width() != windowWidth || $(window).height() != windowHeight) {
    	checkHeader();
		}
	});

	$(window).on('orientationchange', function() {
  	checkHeader();
	});

  // Toggle
  $('.is-style-heading-toggle, .is-style-heading-toggle-boxed').on('click', function() {
    //$(this).next().toggle('fast').toggleClass('closed');
    $(this).next().toggleClass('closed');
    $(this).toggleClass('closed');
    return false;
  });

  //Toggle Mobile Nav
	$('.navi-button').on('click', function() {
		$('body').toggleClass('navi-open');
	});

  //Toggle Post List
  $('.post-list-extended .single-item').on('click', function() {
    if ( window.innerWidth <= 600 ) {
      var toggleClass = 'mobile';
      var toToggle = $(this).attr('data-mobile-toggle');
    } else {
      var toggleClass = 'desktop';
      var toToggle = $(this).attr('data-toggle');
    }

    if ($(toToggle).hasClass('content-active')) {
      close_toggle_post_list();
    } else {
      close_toggle_post_list();
      $('.single-item .grayscale').not($(this).find('.grayscale')).removeClass('grayscale-off');
      $(this).addClass('item-active');
      $(toToggle).addClass('content-active').slideToggle('slow');
    }
  }).on('mouseover', function() {
    $('.single-item .grayscale').not('.item-active .grayscale').removeClass('grayscale-off');
    $(this).find('.grayscale').removeClass('grayscale-on').addClass('grayscale-off');
  }).on('mouseout', function() {
    if ($('.item-active').length == 0) {
      $('.single-item .grayscale').removeClass('grayscale-off').addClass('grayscale-on');
    } else {
      $('.single-item .grayscale').not('.item-active .grayscale').removeClass('grayscale-off').addClass('grayscale-on');
    }
  });
});

//Window Load Soft Scroll
$(window).on('load', function() {
	$('html').addClass('page-loaded');

  checkHeader();
});

$(window).on('scroll', function() {
	checkHeader();
});
