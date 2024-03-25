<?php
/**
 * Template Name: Landing Page 2
 */

?>

<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php the_field('header_custom_js_code', 'option'); ?>
<?php wp_head(); ?>
</head>
<?php 

$term_object = get_queried_object();
$add_class = '';
if( count($term_object) && isset($term_object->term_id) && isset($term_object->taxonomy)   ){
	$childs = get_term_children( $term_object->term_id , $term_object->taxonomy );
	
	if( $term_object->parent ){
		$parent = get_term($term_object->parent);
	}
		
	if( $term_object->slug == 'diamonds' || ($term_object->parent && $parent->slug == 'diamonds' ) ){
		$add_class = 'diamonds-product';
	}else if( !isset($childs->errors) && count($childs) && !$term_object->parent ) {
		$add_class = 'jewelry-product';
	}else{
		$add_class = 'archive-product';
	}
	
	
}

?>
<body <?php body_class($add_class); ?>>

<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
		<div class="logo_container" >
			<img src="http://diamonds.com/wp-content/uploads/2017/11/Rapaport-logo-FINAL-RGB.png" alt="logo" />
		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<style>
				.kni_content {
    font-size: 40px;
    font-family: GothamThin;
    padding-top: 130px;
    text-align: center;
}
			</style>
			<div class="kni_content" >				
				Coming Soon!
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
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
