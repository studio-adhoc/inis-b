<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<div class="a_content">
	<?php if ( is_internal() ) { internal_header(); } ?>

	<div class="a_content_inner">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

	<?php endwhile; ?>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
