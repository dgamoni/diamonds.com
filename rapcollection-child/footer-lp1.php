<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->



	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php the_field('footer_text'); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->



</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
