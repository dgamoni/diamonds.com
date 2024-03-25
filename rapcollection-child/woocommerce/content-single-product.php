<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
	 
	 global $product, $wp_query;
	 
	 $cur_cats = explode("/",$wp_query->query['product_cat']);
	 
?>
<div class="kni_breadcrumbs" >
	<div class="col-full">		
		<a class="breadcrumbs_link" href="/" >Home</a><?php 
		
			if( count($cur_cats) && is_array($cur_cats) ){
				foreach($cur_cats as $k=>$cat){
					
					?>
					
						<img src="<?php echo get_stylesheet_directory_uri() ?>/img/delimiter.png" alt="" /><a class="breadcrumbs_link" href="/product-category/<?php echo $cat ?>/" ><?php echo get_term_by('slug', $cat, 'product_cat')->name; ?></a>
					
					<?php
					
				}
			}
		
		?><img src="<?php echo get_stylesheet_directory_uri() ?>/img/delimiter.png" alt="" /><span class="breadcrumbs_link" ><?php the_title(); ?></span>
	</div>	
</div>	

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php 
	global $post;
	if ( is_wedding_bands_template( $post->ID ) ) { ?>
		<div class="wedding-bands_gallery_wrap">
	<?php } ?>

	<?php
	/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<?php
	if ( is_wedding_bands_template( $post->ID ) ) { ?>
		</div>
	<?php } ?>

	<div class="summary entry-summary">

		<?php
        /*
        if($product->is_type( 'variable' )) {
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_template_single_excerpt', 20 );
        }*/
        
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->


<?php do_action( 'woocommerce_after_single_product' ); ?>

