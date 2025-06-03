<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js <?php if (function_exists('get_language_code') && is_multisite()) { echo 'lang-' . get_language_code(get_current_blog_id()); } ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> id="top">

<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'inis-b' ); ?></a>

<div class="a_all" id="content-top">
	<header class="a_header">
		<div class="header-inner">
			<?php if (function_exists('inis_b_header')) { inis_b_header(); } ?>
			<div class="clear"></div>
		</div>
	</header>

	<?php if (function_exists('inis_b_navi')) { inis_b_navi(); } ?>

	<div class="a_wrapper" id="content">
