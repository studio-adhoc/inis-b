<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/

if ( ! function_exists( 'wp_comment' ) ) :
	function wp_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case '' :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div class="comment_inner" id="comment-<?php comment_ID(); ?>">
				<div class="comment-gravatar">
					<?php echo get_avatar( $comment, 60 ); ?>
				</div>

				<div class="comment-data">
					<p class="comment-meta">
						<span class="comment-author"><?php comment_author_link() ?></span><span class="sep">|</span><span class="comment-meta"><?php comment_date() ?></span>
					</p>
					<?php if ($comment->comment_approved == '0') { ?><span class='unapproved'><?php _e('Your comment will be reviewed.', 'inis-b'); ?></span><?php } ?>
					<?php comment_text() ?>

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __('Reply', 'inis-b'),'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php
				break;
			case 'pingback'  :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e('Pingback:', 'inis-b'); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('Edit', 'inis-b'), ' ' ); ?></p>
		</li>
		<?php
				break;
		endswitch;
	}
endif; ?>

<?php if ( post_password_required() ) : ?>
	<div id="comments">
		<p><?php _e('This post is protected.', 'inis-b'); ?></p>
	</div>
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php if ( have_comments() ) : ?>
<div id="comments">
	<h3 class="section-title"><?php comments_number( __('Write a comment', 'inis-b'), __('1 Comment', 'inis-b'), __('% Comments', 'inis-b') );?></h3>

	<ol class="commentlist">
		<?php wp_list_comments( array( 'callback' => 'wp_comment' ) ); ?>
	</ol>
</div>
<?php else :
	if ( ! comments_open() && 'glossary' != get_post_type() ) :
?>
	<?php /*<div id="comments">
		<p><?php _e('Comments are closed.', 'inis-b'); ?></p>
	</div>*/ ?>
	<?php endif; ?>

<?php endif; ?>

<?php comment_form(); ?>
