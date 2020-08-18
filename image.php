<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php get_header(); ?>

<div class="a_content">
	<div class="a_content_inner">
		<?php the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header>
				<h2 class="post-title"><?php the_title(); ?></h2>
			</header>

			<div class="post-wrapper">
				<div class="post-content">

				<div class="post-attachment">
					<div class="attachment">
	<?php
	/**
	* Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
	* or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
	*/
	$attachments = array_values( get_children( array( 'post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) ) );
	foreach ( $attachments as $k => $attachment ) {
	if ( $attachment->ID == $post->ID )
	break;
	}
	$k++;
	// If there is more than 1 attachment in a gallery
	if ( count( $attachments ) > 1 ) {
	if ( isset( $attachments[ $k ] ) )
	// get the URL of the next image attachment
	$next_attachment_url = get_attachment_link( $attachments[ $k ]->ID );
	else
	// or get the URL of the first image attachment
	$next_attachment_url = get_attachment_link( $attachments[ 0 ]->ID );
	} else {
	// or, if there's only 1 image, get the URL of the image
	$next_attachment_url = wp_get_attachment_url();
	}
	?>
						<a href="<?php echo $next_attachment_url; ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php
						$attachment_size = apply_filters( 'theme_attachment_size',  800 );
						echo wp_get_attachment_image( $post->ID, array( $attachment_size, 9999 ) ); // filterable image width with, essentially, no limit for image height.
						?></a>
					</div>

					<?php if ( ! empty( $post->post_excerpt ) ) : ?>
					<div class="post-caption">
						<?php the_excerpt(); ?>
					</div>
					<?php endif; ?>
				</div>

				<?php the_content(); ?>
				<?php wp_link_pages( array( 'before' => __('<div class="page-link">Pages: ', 'inis-b'), 'after' => '</div>' ) ); ?>

			</div>

			<div class="post-meta-footer post-utility">
				<?php
					$metadata = wp_get_attachment_metadata();
					printf( __( '<span class="meta-prep meta-prep-entry-date">Published </span> <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%7$s</a>.', 'inis-b' ),
						esc_attr( get_the_time() ),
						get_the_date(),
						wp_get_attachment_url(),
						$metadata['width'],
						$metadata['height'],
						get_permalink( $post->post_parent ),
						get_the_title( $post->post_parent )
					);
				?>
				<?php edit_post_link( __('Edit', 'inis-b'), '<span class="sep"></span><span class="edit-link">', '</span>' ); ?><br />
			</div>

			<?php comments_template(); ?>

			<nav class="navigation">
				<div class="next-posts"><?php next_image_link( false, __( '&laquo; Next Image', 'inis-b' ) ); ?></div>
				<div class="previous-posts"><?php previous_image_link( false,  __( 'Previous Image &raquo;', 'inis-b' ) ); ?></div>
			</nav>

			</div>
		</article>
	</div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
