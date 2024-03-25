<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );
	?>
	
	<?php 
	
		//Category data
		$term_object = get_queried_object();
		$subtitle = get_field('subtitle', $term_object);

		//Category image
		$thumbnail_id = get_woocommerce_term_meta( $term_object->term_id,'thumbnail_id', true );
	    $image_url = wp_get_attachment_url( $thumbnail_id );
	 	
	?>

    <header class="woocommerce-products-header" >
		<div class="col-full">
			<div class="woocommerce-category-description">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

					<h1 class="title"><?php woocommerce_page_title(); ?></h1>

				<?php endif; ?>
					<div class="subtitle"><?php echo $subtitle; ?></div>
			</div>	
		</div>	
    </header>
	
	<div class="kni_breadcrumbs" >
		<div class="col-full">		
			<a class="breadcrumbs_link" href="/" >Home</a><img src="<?php echo get_stylesheet_directory_uri() ?>/img/delimiter.png" alt="" /><span class="breadcrumbs_link" ><?php echo $term_object->name; ?></span>
		</div>	
	</div>	
	
	<div class="categories_block parent_category" >
		<div class="col-full">
		   <div class="parent_description" ><?php echo $term_object->description; ?></div>
		</div>
		
		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked wc_print_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
			
			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>
				
			<?php woocommerce_product_loop_end(); ?>
		</div>
			

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
			<div class="col-full">
			<?php
				/**
				 * woocommerce_no_products_found hook.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action( 'woocommerce_no_products_found' );
			?>
			</div>
		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>


<?php get_footer( 'shop' ); ?>
