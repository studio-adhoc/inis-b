<?php
/**
 * @package WordPress
 * @subpackage Initiativen Berlin
**/?>
		<div class="clear"><hr /></div>
	</div>

	<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
	<footer class="a_footer" id="page-footer">
		<div class="footer-inner post-content">
			<?php dynamic_sidebar( 'sidebar-footer' ); ?>
		</div>
	</footer>
	<?php endif; ?>
</div>

<?php wp_footer(); ?>

</body>
</html>
