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


<?php // displaying Membership Benefits banner for specified pages:
$pid = get_option( 'page_for_posts' );
if ( isset($post) && $post->ID != 278

/*
    ($post->ID == 7)
    or (is_home())
    or (is_single())
    or (is_tax() ) */

    ) {
    $flex_content = get_field('flexible_content_field_name', $pid);
    if (is_array($flex_content)) {
        $i = 0;
        //loop through the rows of data
        while (have_rows('flexible_content_field_name', $pid)) : the_row();
            $i++;
            kni_get_acf_flex_content("three_columns_contacts", $i);
        endwhile;
    }
}
?>

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->

	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->
<!-- <script type="text/javascript" src="//downloads.mailchimp.com/js/signup-forms/popup/unique-methods/embed.js" data-dojo-config="usePlainJson: true, isDebug: false"></script><script type="text/javascript">window.dojoRequire(["mojo/signup-forms/Loader"], function(L) { L.start({"baseUrl":"mc.us20.list-manage.com","uuid":"e4ce5f1695f58d4bef38ea0d9","lid":"68c134fbf1","uniqueMethods":true}) })</script> -->
<?php wp_footer(); ?>
</body>
</html>