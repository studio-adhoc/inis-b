<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/ ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="post-header">
	  <h2 class="post-title"><?php the_title(); ?></h2>
	</header>

	<?php if ( has_post_thumbnail() && !has_blocks( $post->post_content ) ) : ?>
		<div class="post-thumbnail thumbnail">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
		</div>
	<?php endif; ?>

	<div class="post-content-inner">
	  <div class="post-wrapper">
	    <div class="post-content">
	        <?php the_content(); ?>
	        <?php wp_link_pages( array( 'before' => __('<div class="page-link">Pages: ', 'inis-b'), 'after' => '</div>' ) ); ?>
	    </div>
	    <div class="clear"></div>

			<?php inis_b_post_footer(); ?>
		</div>
	</div>

	<?php if ( 'post' == $post->post_type ) : ?>
		<div class="post-comment-navi-wrapper">
      <?php comments_template( '', true ); ?>

      <nav class="navigation">
          <div class="next-posts"><?php next_post_link('%link', '< %title'); ?></div>
          <div class="previous-posts"><?php previous_post_link('%link', '%title >'); ?></div>
          <div class="clear"></div>
      </nav>
		</div>
		<div class="clear"></div>
  <?php endif; ?>
</article>
