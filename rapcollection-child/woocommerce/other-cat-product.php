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

		global $term_object;

		//Category data
		$term_object = get_queried_object();

		if( !isset($term_object->term_id) ){
			//return;
		}

		$subtitle = get_field('subtitle', $term_object);

		//Category image
		$thumbnail_id = get_woocommerce_term_meta( $term_object->term_id,'thumbnail_id', true );
		$image_url = wp_get_attachment_url( $thumbnail_id );


    if($term_object->term_id==341) { ?>
        <ul class="ring_builder">
            <?php if($_GET['flow']==1) { ?>
            <li class="setting current" data-link="/product-category/bridal/select-a-setting/?flow=1">
                <div>
                <span class="number">1</span>
                    <div>
                        <span class="top">CHOOSE A</span>
                        <span class="bottom">SETTING</span>
                    </div>
                </div>
            </li>
            <li class="diamond" data-link="/ring-builder-diamond-select/?type=diamond&flow=1">
                <div>
                <span class="number">2</span>
                    <div>
                    <span class="top">CHOOSE A</span>
                    <span class="bottom">DIAMOND</span>
                    </div>
                </div>
            </li>
            <?php } else { ?>
            <li class="diamond" data-link="/ring-builder-diamond-select/?type=diamond">
                <div>
                <span class="number">1</span>
                    <div>
                    <span class="top">CHOOSE A</span>
                    <span class="bottom">DIAMOND</span>
                    </div>
                </div>
            </li>
            <li class="setting current" data-link="/product-category/bridal/select-a-setting/">
                <div>
                <span class="number">2</span>
                    <div>
                        <span class="top">CHOOSE A</span>
                        <span class="bottom">SETTING</span>
                    </div>
                </div>
            </li>
            <?php } ?>
            <li class="summary" data-link="/ring-builder-diamond-select/?type=summary<?php if($_GET['flow']==1) echo '&flow=1'; ?>">
                <div>
                <span class="number">3</span>
                    <div>
                        <span class="top">REVIEW</span>
                        <span class="bottom">SHOPPING BAG</span>
                    </div>
                </div>
            </li>
        </ul>
    <?php } ?>

    <header class="woocommerce-products-header"> <!--  style="background-image: url(<?php echo $image_url; ?>)"  -->
		<div class="col-full">
			<div class="woocommerce-category-description">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

					<h1 class="title"><?php if($term_object->term_id==341) { the_field('setting_select_title', 27807); } else { woocommerce_page_title(); } ?></h1>
                    <div class="line_and_dot_sep"><div class="dot"></div></div>

				<?php endif; ?>
					<div class="subtitle"><?php if($term_object->term_id==341) { the_field('setting_select_subtitle', 27807); } else { echo $subtitle; } ?></div>
			</div>
                <?php if(strlen(trim($term_object->description))>0) { ?>
                    <div class="col-full parent_description_container">
                       <div class="parent_description" ><?php echo $term_object->description; ?></div>
                    </div>
                <?php } ?>
		</div>
    </header>
	<?php if ($term_object->term_id !== 341) { ?>
	<div class="filter_block" data-term_id="<?php echo $term_object->term_id ?>" >

		<div class="block_title" >Filters</div>
		<div class="col-full">

			<?php
                $woof_shortcode = get_field('woof_filter_shortcode', $term_object);
                if($woof_shortcode) {
                    echo do_shortcode("[woof sid='kni_jewelry_filter' tax_only='".$woof_shortcode."' by_only=by_price ajax_redraw=0 is_ajax=1 taxonomies='product_cat:".$term_object->term_id."' ]");
                } else {
				    echo do_shortcode("[woof sid='kni_jewelry_filter' tax_only='pa_style,pa_shape,pa_metal,pa_size,pa_price' by_only='by_price' ajax_redraw=0 is_ajax=1 taxonomies='product_cat:".$term_object->term_id."' ]");
                }


			?>

		</div>
	</div>
	<?php } ?>

	<div class="categories_block" >






			<?php

				// check if the flexible content field has rows of data
				if( have_rows('flexible_content_field_name', $term_object) ):
					echo '<div class="featured_blocks" >';
						$i = 0;

						//loop through the rows of data
						while ( have_rows('flexible_content_field_name', $term_object) ) : the_row();
							$i++;
							kni_get_acf_flex_content( get_row_layout(), $i );

						endwhile;
					echo '</div>';
				else:

					// no layouts found

				endif;

			?>


		<?php if ( have_posts() ) : ?>

		<div class="col-full products_row test">

			<?php  echo do_shortcode('[woof_products is_ajax=1 taxonomies=product_cat:'.$term_object->term_id.' orderby='.get_field('cat_page_products_order_by','option').' order='.get_field('cat_page_products_order','option').' ] '); ?>

		</div>

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

	</div>


<?php /*
	<div class="related_products_block" >
		<div class="col-full">
			<?php
            $args = array(
                'orderby' => get_field('cat_page_related_products_order_by','option'),
                'order' => get_field('cat_page_related_products_order','option')
            );
			 kni_wc_related_products_by_tag_attr($args);

			?>
		</div>
	</div> */ ?>

</main></div>
	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		//do_action( 'woocommerce_after_main_content' );
	?>

<?php
    if($term_object->term_id==341) { if(!$_GET['flow'] && $_GET['added']) { ?>
            <div class="sf_popup added-to-cart-info flows">
                <div class="sf_text">
                Wonderful choice, we've added your diamond to the bag, you may proceed with selecting the setting.
                </div>
                <div class="cart"><?php woocommerce_mini_cart(); ?></div>
                <a href="#" class="sf_close button">Keep Shopping</a>
            </div>
<?php } } ?>
<?php get_footer( 'shop' ); ?>
