<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/ ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action( 'inis_b_before_archive_post_header' ); ?>

	<header class="post-header">
		<h2 class="post-title">
			<?php echo get_post_title_link($post); ?>
				<?php the_title(); ?>
			<?php echo get_post_title_link_end($post); ?>
		</h2>
	</header>

	<?php do_action( 'inis_b_after_archive_post_content' ); ?>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail thumbnail">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
			<?php if ( get_the_post_thumbnail_caption(get_the_ID()) ) {
				$output .= '<span class="post-thumbnail-caption thumbnail-caption">' . get_the_post_thumbnail_caption(get_the_ID()) . '</span>';
			} ?>
		</div>
	<?php endif; ?>

	<?php do_action( 'inis_b_before_archive_post_content' ); ?>

	<div class="post-content-inner">
		<div class="post-wrapper">
			<?php if ( is_search() ) : // Only display Excerpts for search pages ?>
			<div class="post-content summary">
				<?php
				the_excerpt();
				echo apply_filters( 'inis_b_read_more_button', '<a href="' . get_permalink(get_the_ID()) . '#more-' . get_the_ID() . '" class="more-link">' . __('Read more >', 'inis-b') . '</a>' );
				?>
			</div>
			<?php else : ?>
			<div class="post-content">
				<?php if ( $post->post_excerpt == '' ) {
					if( !strpos( $post->post_content, '<!--more-->' ) && 'internal' == $post->post_type ) {
						the_excerpt();
						echo apply_filters( 'inis_b_read_more_button', '<a href="' . get_permalink(get_the_ID()) . '#more-' . get_the_ID() . '" class="more-link">' . __('Read more >', 'inis-b') . '</a>' );
					} else {
						$pt_obj = get_post_type_object( $post->post_type );
						if ($pt_obj->public != 1) {
							echo apply_filters( 'the_content', get_the_content() );
						} else {
							$content = wp_strip_all_tags(get_the_content());
							echo '<p>' . wp_trim_words( $content, 35 ) . '</p>';
							echo apply_filters( 'inis_b_read_more_button', '<a href="' . get_permalink(get_the_ID()) . '#more-' . get_the_ID() . '" class="more-link">' . __('Read more >', 'inis-b') . '</a>' );
						}
					}
				} else {
					the_excerpt();
					echo apply_filters( 'inis_b_read_more_button', '<a href="' . get_permalink(get_the_ID()) . '#more-' . get_the_ID() . '" class="more-link">' . __('Read more >', 'inis-b') . '</a>' );
				} ?>
				<?php if ( 'member' == $post->post_type && get_post_meta(get_the_ID(), 'member_link', true) ) : ?>
				<p class="button">
					<a href="<?php echo get_post_meta(get_the_ID(), 'member_link', true); ?>" target="_blank"><?php _e('Website', 'inis-b'); ?></a>
				</p>
				<?php endif; ?>
				<?php // wp_link_pages( array( 'before' => __('<div class="page-link">Pages: ', 'inis-b'), 'after' => '</div>' ) ); ?>
			</div>
			<?php endif; ?>

			<div class="clear"></div>

			<?php inis_b_post_footer(); ?>
		</div>
	</div>

	<?php do_action( 'inis_b_after_archive_post_content' ); ?>

	<div class="clear"></div>
</article>
