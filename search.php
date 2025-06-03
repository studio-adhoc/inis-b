<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<main class="a_content">
	<?php if ( is_internal() ) { internal_header(); } ?>

	<div class="a_content_inner">
		<?php if ( have_posts() ) : ?>

			<header>
				<h3 class="archive-title"><?php printf(__('Search Results <span class="sub-title">%s</span>', 'inis-b'), get_search_query() ); ?></h3>
			</header>

			<?php do_action( 'inis_b_before_loop' ); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'content', 'search' ); ?>
				<?php endwhile; ?>
			<?php do_action( 'inis_b_after_loop' ); ?>

			<?php if (  $wp_query->max_num_pages > 1 ) : ?>
			<nav class="navigation">
				<div class="next-posts"><?php previous_posts_link(__('< Newer Articles', 'inis-b')); ?></div>
				<div class="previous-posts"><?php next_posts_link(__('Older Articles >', 'inis-b')); ?></div>
				<div class="clear"></div>
			</nav>
			<?php endif; ?>

		<?php else : ?>

			<article class="post not-found error-404">
				<header>
					<h2 class="post-title"><?php _e( 'Nothing Found', 'inis-b' ); ?></h2>
				</header>

				<div class="post-content">
					<p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'inis-b'); ?></p>
				</div>

				<?php get_search_form(); ?>
			</article>

		<?php endif; ?>
	</div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
