<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<div class="a_content">
	<?php the_post();?>

	<?php get_template_part( 'content', 'page' ); ?>

	<?php //comments_template( '', true ); ?>
</div>

<?php get_footer(); ?>
