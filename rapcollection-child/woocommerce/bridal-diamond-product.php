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



    ?>

    <header class="woocommerce-products-header"> <!--  style="background-image: url(<?php echo $image_url; ?>)"  -->
        <div class="col-full">
            <div class="woocommerce-category-description">
                <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

                    <h1 class="title"><?php woocommerce_page_title(); ?></h1>
                    <div class="line_and_dot_sep"><div class="dot"></div></div>

                <?php endif; ?>
                    <div class="subtitle"><?php echo $subtitle; ?></div>

            </div>                    
                <?php if(strlen(trim($term_object->description))>0) { ?>
                    <div class="col-full parent_description_container">
                       <div class="parent_description" ><?php echo $term_object->description; ?></div>
                    </div>
                <?php } ?>
        </div>
    </header>

    <div class="filter_block" data-term_id="<?php echo $term_object->term_id ?>" >

        <div class="block_title" >Filters</div>
        <div class="col-full">

            <?php

                $woof_shortcode = get_field('woof_filter_shortcode', $term_object);
                if($woof_shortcode) {
                    echo do_shortcode("[woof sid='kni_diamonds_filter' tax_only='".$woof_shortcode."' by_only=by_price ajax_redraw=0 is_ajax=1 taxonomies=product_cat:".$term_object->term_id." ]");
                } else {
				    echo do_shortcode("[woof sid='kni_diamonds_filter' tax_only='pa_shape,pa_color,pa_clarity,pa_diamonds-size,pa_colored-diamonds,pa_price_100' by_only=by_price ajax_redraw=0 is_ajax=1 taxonomies=product_cat:".$term_object->term_id." ]");
                }


            ?>
            <span class="advanced-filters-diamonds">Advanced Search</span>
            <div class="advanced-filters-diamonds-block">
                <div class="advanced-filters">
                    <ul>
                        <li>Cut
                            <ul>
                                <li>
                                    <input type="checkbox" data-tax="as_cut" value="Excellent" name="Excellent" id="cut_ex" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="cut_ex">Excellent</label>
                                </li>
                                <li>
                                    <input type="checkbox" data-tax="as_cut" value="Very Good" name="Very Good" id="cut_vg" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="cut_vg">Very Good</label>
                                </li>
                            </ul>
                        </li>
                        <li>Polish
                            <ul>
                                <li>
                                    <input type="checkbox" data-tax="as_pol" value="Excellent" name="Excellent" id="pol_ex" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="pol_ex">Excellent</label>
                                </li>
                                <li>
                                    <input type="checkbox" data-tax="as_pol" value="Very Good" name="Very Good" id="pol_vg" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="pol_vg">Very Good</label>
                                </li>
                            </ul>
                        </li>
                        <li>Symmetry
                            <ul>
                                <li>
                                    <input type="checkbox" data-tax="as_symm" value="Excellent" name="Excellent" id="symm_ex" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="symm_ex">Excellent</label>
                                </li>
                                <li>
                                    <input type="checkbox" data-tax="as_symm" value="Very Good" name="Very Good" id="symm_vg" class="woof_checkbox_term">
                                    <label class="woof_checkbox_label " for="symm_vg">Very Good</label>
                                </li>
                            </ul>
                        </li>
                        <li>Ratio
                            <ul>
                                <li>
                                    <input type="radio" data-slug="1" data-term-id="1" value="1" name="as_rat" id="ratio_1" class="woof_radio_term ">
                                    <label class="woof_checkbox_label " for="ratio_1">1</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="11" data-tax="as_rat" data-term-id="11" value="11" name="as_rat" id="ratio_11" class="woof_radio_term ">
                                    <label class="woof_checkbox_label " for="ratio_11">1 - 1.1</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="12" data-tax="as_rat" data-term-id="12" value="12" name="as_rat" id="ratio_12" class="woof_radio_term ">
                                    <label class="woof_checkbox_label " for="ratio_12">1.1 - 1.2</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="13" data-tax="as_rat" data-term-id="13" value="13" name="as_rat" id="ratio_13" class="woof_radio_term ">
                                    <label class="woof_checkbox_label " for="ratio_13">1.2 - 1.3</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="14" data-tax="as_rat" data-term-id="14" value="14" name="as_rat" id="ratio_14" class="woof_radio_term ">
                                    <label class="woof_checkbox_label " for="ratio_14">1.3 and Over</label>
                                </li>
                            </ul>
                        </li>
                        <li>Delivery Date
                            <ul>
                                <li>
                                    <input type="radio" data-slug="all" data-term-id="all" value="all" name="as_del" id="del_all" class="woof_radio_term">
                                    <label class="woof_checkbox_label " for="del_all">All</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="1" data-term-id="1"  value="1" name="as_del" id="del_1" class="woof_radio_term">
                                    <label class="woof_checkbox_label " for="del_1">Next Business Day</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="2" data-term-id="2"  value="2" name="as_del" id="del_2" class="woof_radio_term">
                                    <label class="woof_checkbox_label " for="del_2">2 - 4 Business Days</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="5" data-term-id="5"  value="5" name="as_del" id="del_5" class="woof_radio_term">
                                    <label class="woof_checkbox_label " for="del_5">5 - 7 Business Days</label>
                                </li>
                                <li>
                                    <input type="radio" data-slug="7" data-term-id="7"  value="7" name="as_del" id="del_7" class="woof_radio_term">
                                    <label class="woof_checkbox_label " for="del_7">7 Business Days and Over</label>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery('.advanced-filters-diamonds').click(function() {
                        console.log('123');
                        jQuery('.advanced-filters-diamonds-block .advanced-filters').toggleClass('show');
                    });
                });
            </script>
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


<?php get_footer( 'shop' ); ?>
  