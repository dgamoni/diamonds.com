<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
	exit;
}

global $product;
$upsells = $product->get_upsell_ids();
if (!empty($upsells)) {
    $related_products = $upsells;
}

if ( in_array(215, wc_get_product_cat_ids(get_the_ID())) ) {
    $shapes = wc_get_product_terms( $product->id, 'pa_shape', array( 'fields' => 'names' ) );
    $shapes = $shapes[0];
    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        'posts_per_page'        => '4',
        'tax_query'             => array(
            array(
                'taxonomy'      => 'product_cat',
                'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                'terms'         => 216,
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            ),
            array(
                'taxonomy'      => 'pa_shape',
                'field' => 'name', //This is optional, as it defaults to 'term_id'
                'terms'         => $shapes,
                'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
            ),
        )
    );
    $related_products = get_posts($args);
    if(is_array($related_products) && !empty($related_products)) { ?>

    <div class="clearfix"></div>
	<section class="related products">

		<h2 class="section_title" ><?php esc_html_e( 'Suggested for You', 'woocommerce' ); ?></h2> <!-- More Jewelry To Choose -->
		
		<?php storefront_product_columns_wrapper(); ?>
		
			<?php woocommerce_product_loop_start();
				foreach ( $related_products as $related_product ) : ?>
					<?php
$post_object = $related_product;

						setup_postdata( $GLOBALS['post'] =& $post_object );
						wc_get_template_part( 'content', 'productq' ); ?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>
		
		<?php storefront_product_columns_wrapper_close(); ?>

	</section>

<?php } } else if ( !in_array(36, wc_get_product_cat_ids(get_the_ID())) && $related_products ) { ?>

    <div class="clearfix"></div>
	<section class="related products">

		<h2 class="section_title" ><?php esc_html_e( 'Curated for you', 'woocommerce' ); ?></h2> <!-- More Jewelry To Choose -->
		
		<?php storefront_product_columns_wrapper(); ?>
		
			<?php woocommerce_product_loop_start(); ?>

				<?php foreach ( $related_products as $related_product ) : ?>

					<?php
                        if (!empty($upsells)) {
						  $post_object = get_post( $related_product );
                        } else {
						  $post_object = get_post( $related_product->get_id() );
                        }

						setup_postdata( $GLOBALS['post'] =& $post_object );

						wc_get_template_part( 'content', 'product' ); ?>

				<?php endforeach; ?>

			<?php woocommerce_product_loop_end(); ?>
		
		<?php storefront_product_columns_wrapper_close(); ?>

	</section>

<?php }



wp_reset_postdata();
