<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>

<?php if ( !is_internal() && 'post' == get_post_type() ) : ?>
	<?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
		<div class="a_sidebar">
			<div class="a_sidebar_inner">
				<hr />
				<aside class="sidebar left">
					<?php dynamic_sidebar( 'sidebar-blog' ); ?>
				</aside>
				<div class="clear"></div>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
