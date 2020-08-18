/*
 * Add Block Editor Block Styles
 */
const { __ } = wp.i18n;

wp.domReady(function() {
  wp.blocks.registerBlockStyle( 'core/paragraph', {
    name: 'paragraph-boxed',
    label: __('Boxed Paragraph', 'inis-b')
  } );
  wp.blocks.registerBlockStyle( 'core/heading', {
    name: 'heading-boxed',
    label: __('Boxed Heading', 'inis-b')
  } );
  if ( wp.data.select('core/blocks').getBlockType('core/group') ) {
    wp.blocks.registerBlockStyle( 'core/heading', {
      name: 'heading-toggle',
      label: __('Toggle Heading', 'inis-b')
    } );
    wp.blocks.registerBlockStyle( 'core/heading', {
      name: 'heading-toggle-boxed',
      label: __('Boxed Toggle Heading', 'inis-b')
    } );
  }
  wp.blocks.registerBlockStyle( 'core/list', {
    name: 'list-download',
    label: __('Download List', 'inis-b')
  } );
  wp.blocks.registerBlockStyle( 'core/list', {
    name: 'list-boxed',
    label: __('Boxed List', 'inis-b')
  } );
  wp.blocks.registerBlockStyle( 'core/list', {
    name: 'list-download-boxed',
    label: __('Boxed Download List', 'inis-b')
  } );
  wp.blocks.registerBlockStyle( 'core/buttons', {
    name: 'button-boxed',
    label: __('Boxed Button', 'inis-b')
  } );
  wp.blocks.registerBlockStyle( 'core/group', {
    name: 'box-group',
    label: __('Box', 'inis-b')
  } );
});

/*
 * Remove Block Editor Block Styles
 */
var inis_b_styles = (function () {
	'use strict';

	function adjustBlockStyles(settings, name) {
    //console.log(name, settings.styles);

		switch (name) {
			case 'core/quote':
				return removeStyles(settings, ['default', 'large']);
			case 'core/button':
				return removeStyles(settings, ['default', 'fill', 'outline']);
			case 'core/pullquote':
				return removeStyles(settings, ['default', 'solid-color']);
			case 'core/separator':
        return removeStyles(settings, ['default', 'wide', 'dots']);
			case 'core/table':
				return removeStyles(settings, ['regular', 'stripes']);
			default:
				return settings;
		}
	}

	function setDefaultLabel(settings, newLabel) {
		settings['styles'] = settings['styles'].map(function (style) {
			if (style.isDefault) style.label = newLabel;
			return style;
		});
	}

	function removeStyles(settings, styles) {
		settings['styles'] = settings['styles'].filter(function (style) {
			return styles.indexOf(style.name) === -1;
		});
		return settings;
	}

	return {
		adjustBlockStyles: adjustBlockStyles
	};

}());

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'inis_b/editor_styles',
	inis_b_styles.adjustBlockStyles
);

/*
 * Add Block Editor Supports
 */
var inis_b_supports = (function () {
	'use strict';

  function addBlockSupports(settings, name) {
    //console.log(name, settings.supports);

    switch (name) {
      case 'core/heading':
        return lodash.assign( {}, settings, {
          supports: lodash.assign( {}, settings.supports, {
            align: [ 'full', 'wide' ]
          } ),
        } );
      case 'core/separator':
        return lodash.assign( {}, settings, {
          supports: lodash.assign( {}, settings.supports, {
            align: [ 'full', 'wide' ]
          } ),
        } );
      default:
        return settings;
    }
  }

	return {
		addBlockSupports: addBlockSupports
	};

}());

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'inis_b/editor_supports',
	inis_b_supports.addBlockSupports
);
