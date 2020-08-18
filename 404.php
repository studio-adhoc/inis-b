<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<div class="a_content">
	<div class="a_content_inner">
		<article class="post not-found error-404">
			<header>
				<h2 class="post-title"><?php _e('Uppsss! The content is missing.', 'inis-b'); ?></h2>
			</header>
			
			<div class="post-content">
				<p><?php _e('Apologies, but the page you requested could not be found. Perhaps searching will help.', 'inis-b'); ?></p>
			</div>
			
			<?php get_search_form(); ?>
		</article>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>