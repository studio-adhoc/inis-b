<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<main class="a_content">
	<?php do_action( 'inis_b_before_single_post' ); ?>

	<div class="a_content_inner">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<?php get_template_part( 'content', 'single' ); ?>

	<?php endwhile; ?>
	</div>

	<?php do_action( 'inis_b_after_single_post' ); ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
