<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="post-wrapper field-wrapper">
		<div class="post-inner field-inner">
			<?php if ( get_post_meta( get_the_ID(), 'page_headline_visibility', true ) != 1 ) : ?>
				<header class="title-header">
					<h2 class="post-title"><?php the_title(); ?></h2>
				</header>
			<?php endif; ?>

			<div class="post-content">
				<?php the_content(); ?>

				<?php wp_link_pages( array( 'before' => __('<div class="page-link">Pages: ', 'inis-b'), 'after' => '</div>' ) ); ?>

				<?php edit_post_link( __('Edit', 'inis-b'), '<span class="edit-link">', '</span>' ); ?>
			</div>
			<div class="clear"></div>
		</div>
	</div>
</article>
