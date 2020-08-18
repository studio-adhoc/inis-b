<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

	<section class="archive author-archive">
		<div class="a_content">
			<div class="a_content_inner">
				<?php if ( have_posts() ) : ?>

					<?php the_post(); ?>

					<header>
						<h3 class="archive-title">
							<?php printf( __('Author – %s', 'inis-b'), get_the_author() ); ?>
						</h3>
					</header>

					<?php rewind_posts(); ?>

					<?php do_action( 'inis_b_before_loop' ); ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php get_template_part( 'content', get_post_format() ); ?>
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
							<p><?php _e('Sorry, but no posts were found.', 'inis-b'); ?></p>
						</div>
					</article>

				<?php endif; ?>
			</div>
		</div>
	</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
