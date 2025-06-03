<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<main class="a_content">
	<?php the_post();?>

	<?php get_template_part( 'content', 'page' ); ?>

	<?php //comments_template( '', true ); ?>
</main>

<?php get_footer(); ?>
