<?php

/*
 * WooCommerce category page
 */



//add_action( 'woocommerce_before_add_to_cart_quantity', 'bbloomer_display_dropdown_variation_add_cart' );
 
function bbloomer_display_dropdown_variation_add_cart() {
     
    global $product;
     
    if ( $product->is_type('variable') ) {
        ?>
        <script>
        jQuery(document).ready(function($) {
             
            $('input.variation_id').change( function(){
                if( '' != $('input.variation_id').val() ) {
                     
                    var var_id = $('input.variation_id').val();
                    //console.log('You just selected variation #' + var_id);
                     
                }
                var prod_vars = $('.variations_form').data('product_variations');
                prod_vars.forEach(function(item, i, arr) {
                    if(item['variation_id']==var_id) {
                        jQuery('.jquery_variation_sku').html(' - ' +item['sku']);
                    }
                });
            });
             
        });
        </script>
        <?php
    }
}

function move_variation_price() {
    remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
    add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_single_variation', 10 );
}
add_action( 'woocommerce_before_add_to_cart_form', 'move_variation_price' );

// add SKU to Product page
add_action( 'woocommerce_after_add_to_cart_form', 'dev_designs_show_sku', 15 );
function dev_designs_show_sku(){
    global $product;
    if($product->get_sku()) {
    echo 'Item#: ' . $product->get_sku().'<span class="jquery_variation_sku"></span>';
    }
}


add_action('wp_enqueue_scripts', 'remove_gridlist_styles', 30);
function remove_gridlist_styles() {
    wp_dequeue_style( 'grid-list-layout' );
}


function order_filter_by_price( $query ) {
    if($_GET['price']) {
        $query['orderby']='meta_value_num';
        $query['order']='DESC';
        if($_GET['price']=='ASC') $query['order']='ASC';
        if($_GET['price']=='DESC') $query['order']='DESC';
        $query['meta_key']='_regular_price';
    }
    if($_GET['as_cut']) {
        $query['meta_query'][] = array(
            'key' => 'cut',
            'value' => explode(',', $_GET['as_cut']),
            'compare' => 'IN'
        );
    }
    if($_GET['as_pol']) {
        $query['meta_query'][] = array(
            'key' => 'polish',
            'value' => explode(',', $_GET['as_pol']),
            'compare' => 'IN'
        );
    }
    if($_GET['as_symm']) {
        $query['meta_query'][] = array(
            'key' => 'symm',
            'value' => explode(',', $_GET['as_symm']),
            'compare' => 'IN'
        );
    }
    if($_GET['as_rat']) {
        $data = array(1,1);
        switch($_GET['as_rat']) {
            case 1:
                $data = array(1,1);
                break;
            case 11:
                $data = array(1, 1.1);
                break;
            case 12:
                $data = array(1.1, 1.2);
                break;
            case 13:
                $data = array(1.2, 1.3);
                break;
            case 14:
                $data = array(1.3, 100);
                break;
        }
        $query['meta_query'][] = array(
            'key' => 'ratio',
            'value' => $data,
            'compare' => 'BETWEEN',
            'type' => 'DECIMAL'
        );
    }
    if($_GET['as_del']) {
        if($_GET['as_del']!='all') {
            $data = array(1,1);
            switch($_GET['as_del']) {
                case 1:
                    $data = array(1,1);
                    break;
                case 2:
                    $data = array(1, 4);
                    break;
                case 5:
                    $data = array(5, 7);
                    break;
                case 7:
                    $data = array(7, 1000);
                    break;
            }
            $query['meta_query'][] = array(
                'key' => 'delivery_time',
                'value' => $data,
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            );
        }
    }
    /*
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'cat', '-1,-1347' );
    }*/
    /*
    if(!empty($query->query)) {
        if($query->query['product_cat']=='jewelry') {
            //file_put_contents(__DIR__ . '/query_'.rand(0, 10000).'.txt', print_r($query, true));
        }
    }*/
    return $query;
}
add_filter( 'woof_products_query', 'order_filter_by_price' );

/*
add_filter( 'woocommerce_get_price_html', 'bbloomer_price_free_zero_empty', 100, 2 );
function bbloomer_price_free_zero_empty( $price, $product ) {
    if ( '' === $product->get_price() || 0 == $product->get_price() ) {
        $price = '<span class="woocommerce-Price-amount amount">Price Upon Request</span>';
    }
    return $price;
}*/


add_filter('woof_get_tax_query', 'woof_brand_no_style');
function woof_brand_no_style($res) {
    //file_put_contents(__DIR__ .'/ktest.txt', print_r($res, true));
    $remove_style = 0;
    foreach($res as $key => $value) {
        if(is_array($res[$key]) && $res[$key]['taxonomy']=='pa_brand') {
            $remove_style=1;
        }
    }
    if($remove_style) {      
        foreach($res as $key => $value) {
            if(is_array($res[$key]) && $res[$key]['taxonomy']=='pa_style') {
                unset($res[$key]);
            }
        }
    }
    return $res;
}

add_filter('woocommerce_empty_price_html', 'custom_call_for_price');

function custom_call_for_price() {
    return 'Price Upon Request';
}


// Change add_to_cart button text to PURCHASE
add_filter( 'add_to_cart_text', 'woo_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );  // 2.1 +
function woo_custom_single_add_to_cart_text() {

    return __( 'BUY NOW', 'woocommerce' );

}

add_filter( 'wc_empty_cart_message', 'custom_wc_empty_cart_message' );

function custom_wc_empty_cart_message() {
  return 'Your Shopping Bag is currently empty';
}


// Redirect Buy Now to checkout

add_filter ('woocommerce_cart_redirect_after_error', 'redirect_to_checkout');
add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

function kni_footer_modal_boxes() { ?>
    <div class="sf_popup_overlay"></div>
    <?php if(get_field('show_out_of_office_pop_up', 'option') && !isset($_COOKIE['out_of_office']) && (is_page( 7 ) || is_page( 278 ))) {?>
        <div class="sf_popup out_of_office">
            <div class="sf_popup_logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/contact-us-logo-rapaport-collection.png" alt=""/></div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <div class="sf_text">
                <h2>Office Closed</h2>
                <div><?php the_field('out_of_office_pop_up_text', 'option'); ?></div>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
        </div>
    <?php } ?>
        <div class="sf_popup vip_member_form">
            <div class="sf_popup_logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/contact-us-logo-rapaport-collection.png" alt=""/></div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <div class="sf_text">
                <h2>VIP Member Form</h2>
                <?php echo do_shortcode(get_field('become_a_vip_member_shortcode', 'option')); ?>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
        </div>
        <div class="sf_popup concierge_support concierge_support_style">
            <div class="sf_text" style="margin:0;">
                <?php echo do_shortcode(get_field('concierge_shortcode', 'option')); ?>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
        </div>
<?php 
/*
if(!$_COOKIE['mc_popup_right'] && !is_page() && $_COOKIE['mailchimp_subscribed']!=1 && !is_user_logged_in()) { ?>
        <div class="mc_popup_right blog">
            <a href="#" class="close">CLOSE</a>
            <?php echo get_field('right_mailchimp_popup_text', 'option'); ?>
            <?php echo do_shortcode('[yikes-mailchimp form="1"]'); ?>
        </div>
<?php /*
        <div class="mc_popup_right woocommerce">
            <a href="#" class="close">CLOSE</a>
            <?php echo get_field('right_mailchimp_popup_text_shop', 'option'); ?>
            <?php echo do_shortcode('[yikes-mailchimp form="3"]'); ?>
        </div>  ?>
<?php } */?>

    <?php if(is_product()) { // CTA FOR REGISTERED ADD TO CART ?>
	<div class="sf_popup added-to-cart-info">
        <div class="sf_text">
        Wonderful choice, we’ve added your item to the cart, click the cart and checkout button in the top right corner when you’re ready.
        </div>
        <div class="cart"></div>
        <a href="#" class="sf_close button">Keep Shopping</a>
    </div>
    <?php } ?>
    <?php if($_COOKIE['mailchimp_showed']!=1 && !is_user_logged_in() && $_COOKIE['mailchimp_subscribed']!=1) { // CTA FOR REGISTERED ADD TO CART ?>
	<div class="sf_popup subscription-add-to-cart">
        <div class="sf_popup_logo"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/contact-us-logo-rapaport-collection.png" alt=""/></div>
        <div class="line_and_dot_sep"><div class="dot"></div></div>
        <div class="sf_text">
            <h2>Let’s Keep Your Cart Saved</h2>
            <?php echo do_shortcode('[yikes-mailchimp form="4"]'); ?>
        </div>
        <a href="#" class="sf_close open_cart_box">No Thanks I don’t mind losing my list</a>
    </div>
    <?php } ?>
    <?php if(is_product()) { ?>
	<div class="sf_popup buy-popup-now concierge_support_style">
            <div class="sf_text" style="margin:0;">
                <?php echo do_shortcode('[gravityform id="8" title="false" description="false" ajax="true"]'); ?>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
    </div>
	<div class="sf_popup buy-popup-add concierge_support_style">
            <div class="sf_text" style="margin:0;">
                <?php echo do_shortcode('[gravityform id="9" title="false" description="false" ajax="true"]'); ?>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
    </div>
    <?php } ?>
	<div class="sf_popup ring-size-chart">
            <div class="sf_text" style="margin:0;">
                <?php echo get_field('text_before_table', 'option'); ?>
                <?php if( have_rows('lines', 'option') ) { ?>
                <table>
                    <thead>
                        <tr>
                            <td>General Size</td>
                            <td>Finger Diameter</td>
                            <td>Finger Circumference</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while( have_rows('lines', 'option') ){ the_row(); ?>
                        <tr>
                            <td><?php echo get_sub_field('general_size'); ?></td>
                            <td><?php echo get_sub_field('finger_diameter'); ?></td>
                            <td><?php echo get_sub_field('finger_circumference'); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?php } ?>
            </div>
            <div class="line_and_dot_sep"><div class="dot"></div></div>
            <a href="#" class="sf_close">CLOSE</a>
    </div>
<?php
}
add_action( 'wp_footer', 'kni_footer_modal_boxes' );

function kni_limit_post_types_in_search( $query ) {
    if ( $query->is_search ) {
        $query->set( 'post_type', array( 'post', 'product' ) );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'kni_limit_post_types_in_search' );

// Rename Checkout to thank you on order recieved
function kni_title_order_received( $title, $id ) {
	if ( is_order_received_page() && get_the_ID() === $id ) {
		$title = "Thank You";
	}
	return $title;
}
add_filter( 'the_title', 'kni_title_order_received', 10, 2 );

// Remove bank account details
//add_filter('woocommerce_bacs_accounts', function() { return array(); });


//  Output: removes woocommerce tabs
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

// Removes Product Description header
add_filter('woocommerce_product_description_heading', '__return_null');

// Adds Product Description under buttons
function woocommerce_template_product_description() {
//    woocommerce_get_template( 'single-product/tabs/description.php' );
    wc_get_template( 'single-product/tabs/description.php' );
}
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 50 );



function woocommerce_template_product_description_meta() {
    global $product;
?>
<p>
<?php $temp = get_post_meta($product->id, 'name', true); if(strlen($temp)>1) {?><?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'carat_field', true); if(strlen($temp)>1) {?>Carat Weight: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'cut', true); if(strlen($temp)>1) {?>Cut: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'polish', true); if(strlen($temp)>1) {?>Polish: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'symm', true); if(strlen($temp)>1) {?>Symmetry: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'ratio', true); if(strlen($temp)>1) {?>Ratio: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'lab', true); if(strlen($temp)>1) {?>Lab: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'fluor', true); if(strlen($temp)>1) {?>Fluor.: <?php echo $temp; ?><br/><?php } ?>
<?php $temp = get_post_meta($product->id, 'delivery_time', true); if(strlen($temp)>=1) {?>Delivery time: <?php echo date('F d, Y', strtotime('+'.$temp.'days')); ?><br/><?php } ?>
</p>
<?php }
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description_meta', 10 );


// add additional button on product page for woocommerce

/// Add designer name for desktop

add_action('woocommerce_single_product_summary', 'add_designer_name', 6);
function add_designer_name() {
    global $product;
    $designer = $product->get_attribute('pa_designer'); 
    if($designer) { ?>
    <h3 class="designer-name product-desktop"><?php echo $designer; ?></h3>
    <?php }
}

// add additional button on product page for woocommerce
// woocommerce_before_add_to_cart_button

// Add to cart above description
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
add_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 15);

//woocommerce_single_product_summary
add_action( 'woocommerce_after_add_to_cart_button', 'speak_to_concierge_button_on_product_page', 998 );
function speak_to_concierge_button_on_product_page() {
    global $product;
    
    /*if($product->get_price()>10000) {
        echo '<input type="hidden" name="product_id" value="'.$product->get_id().'"/>';
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="#" rel="nofollow" style="float:none" class="button class_exp_modal view-description wl-add-but clearfix product_type_">%s</a>',
                esc_html( 'Add to shopping bag' )
            ),
        $product );
    } else { */
        $classes = implode( ' ',  array(
            'button',
            'product_type_' . $product->get_type(),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
            $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
        ));
        echo '<input type="hidden" name="product_id" value="'.$product->get_id().'"/>';
        echo apply_filters( 'woocommerce_loop_add_to_cart_link',
            sprintf( '<a href="%s" value="%" rel="nofollow" style="float:none" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button view-description wl-add-but clearfix %s product_type_%s">%s</a>',
                esc_url( $product->add_to_cart_url() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_id() ),
                esc_attr( $product->get_sku() ),
                esc_attr( isset( $quantity ) ? $quantity : 1 ),
                esc_attr( isset( $classes ) ? $classes : 'button' ),
                esc_attr( $product->get_type() ),
                esc_html( 'Add to shopping bag' )
            ),
        $product );
    //}
    if(has_term( 'Designer Collection', 'product_cat', $product->get_id() )) {
        echo '<a class="button view-description wl-add-but clearfix" id="concierge_form" href="#" onclick="">SPEAK WITH DESIGNER</a>';
    }
}
/*
//woocommerce_single_product_summary
add_action( 'woocommerce_before_add_to_cart_button', 'wsb_add_to_cart_button', 1002 );
function wsb_add_to_cart_button( ) {
global $product;
    $classes = implode( ' ',  array(
        'button',
        'product_type_' . $product->get_type(),
        $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
        $product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
    ));

	return apply_filters( 'woocommerce_loop_add_to_cart_link',
	    sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
	        esc_url( $product->add_to_cart_url() ),
	        esc_attr( $product->get_id() ),
	        esc_attr( $product->get_sku() ),
	        esc_attr( isset( $quantity ) ? $quantity : 1 ),
	        esc_attr( isset( $classes ) ? $classes : 'button' ),
	        esc_attr( $product->get_type() ),
	        esc_html( $product->add_to_cart_text() )
	    ),
	$product );
}
*/



if ( ! function_exists( 'remove_anonymous_object_filter' ) )
{
    /**
     * Remove an anonymous object filter.
     *
     * @param  string $tag    Hook name.
     * @param  string $class  Class name
     * @param  string $method Method name
     * @return void
     */
    function remove_anonymous_object_filter( $tag, $class, $method )
    {
        $filters = $GLOBALS['wp_filter'][ $tag ];

        if ( empty ( $filters ) )
        {
            return;
        }

        foreach ( $filters as $priority => $filter )
        {
            foreach ( $filter as $identifier => $function )
            {
                if ( is_array( $function)
                    and is_a( $function['function'][0], $class )
                    and $method === $function['function'][1]
                )
                {
                    remove_filter(
                        $tag,
                        array ( $function['function'][0], $method ),
                        $priority
                    );
                }
            }
        }
    }
}

// Move Add To Wishlist button
/*
add_action( 'woocommerce_after_add_to_cart_button', 'kni_move_wishlist_button', 0 );
function kni_move_wishlist_button() {
    remove_anonymous_object_filter(
        'woocommerce_after_add_to_cart_button',
        'WC_Wishlists_Plugin',
        'add_to_wishlist_button'
    );
    
}
add_action( 'woocommerce_single_variation', 'kni_move_wishlist_button2', 0 );
function kni_move_wishlist_button2() {
    remove_anonymous_object_filter(
        'woocommerce_single_variation',
        'WC_Wishlists_Plugin',
        'add_to_wishlist_button'
    );
}
add_action( 'woocommerce_after_add_to_cart_button', array( $GLOBALS['wishlists'], 'add_to_wishlist_button' ), 999 );*/


add_action('init', 'kni_remove_copy_billing_to_shipping', 10);
function kni_remove_copy_billing_to_shipping() {
    if(is_user_logged_in()) {
        $user = wp_get_current_user();
        if($user->data->user_email!=get_user_meta( $user->ID, 'billing_email', true )) {
            remove_anonymous_object_filter(
                'woocommerce_after_edit_account_address_form',
                'Woocommerce_Duplicate_Billing_Address_Public',
                'my_account_copy_billing_to_shipping'
            );
        }
    }
	//remove_action('woocommerce_after_edit_account_address_form', array( $this, 'my_account_copy_billing_to_shipping' ) );
}

add_filter( 'storefront_handheld_footer_bar_links', 'jk_remove_handheld_footer_links' );
function jk_remove_handheld_footer_links( $links ) {
	unset( $links['my-account'] );
	unset( $links['search'] );

	return $links;
}

add_filter( 'storefront_handheld_footer_bar_links', 'jk_add_home_link' );
function jk_add_home_link( $links ) {
	$new_links = array(
		'my-account' => array(
			'priority' => 10,
			'callback' => 'jk_home_link',
		),
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

function jk_home_link() {
	echo '<a href="tel:'.get_field('phone', 'option').'">Phone</a>';
}


//Checkout product image
//echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );

if ( ! function_exists( 'storefront_sticky_single_add_to_cart_custom' ) ) {
	/**
	 * Sticky Add to Cart
	 *
	 * @since 2.3.0
	 */
	function storefront_sticky_single_add_to_cart_custom() {
		global $product;

		if ( class_exists( 'Storefront_Sticky_Add_to_Cart' ) || true !== get_theme_mod( 'storefront_sticky_add_to_cart' ) ) {
			return;
		}

		if ( ! is_product() ) {
			return;
		}

		$params = apply_filters(
			'storefront_sticky_add_to_cart_params', array(
				'trigger_class' => 'entry-summary',
			)
		);

		wp_localize_script( 'storefront-sticky-add-to-cart', 'storefront_sticky_add_to_cart_params', $params );

		wp_enqueue_script( 'storefront-sticky-add-to-cart' );
		?>
			<section class="storefront-sticky-add-to-cart">
				<div class="col-full">
					<div class="storefront-sticky-add-to-cart__content">
						<?php echo wp_kses_post( woocommerce_get_product_thumbnail() ); ?>
						<div class="storefront-sticky-add-to-cart__content-product-info">
							<span class="storefront-sticky-add-to-cart__content-title"><?php esc_attr_e( 'You\'re viewing:', 'storefront' ); ?> <strong><?php the_title(); ?></strong></span>
							<span class="storefront-sticky-add-to-cart__content-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
							<?php echo wp_kses_post( wc_get_rating_html( $product->get_average_rating() ) ); ?>
						</div>
						<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="storefront-sticky-add-to-cart__content-button button alt">
							Add to Bag
						</a>
					</div>
				</div>
			</section><!-- .storefront-sticky-add-to-cart -->
		<?php
	}
}

//Remove blocks
add_action('init', 'kni_remove_ctegory_page_blocks', 10);
function kni_remove_ctegory_page_blocks()
{
    remove_action( 'woocommerce_after_single_product_summary', 'storefront_upsell_display', 15 );
    //remove breadcrumb
    remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);

    //remove taxonomy and product description
    remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
    remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);

    //remove catalog ordering
    remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10);

    remove_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9);
    remove_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31);
    remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9);
    remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31);
    remove_action( 'woocommerce_before_shop_loop',       'storefront_woocommerce_pagination',        30 );

    //remove result_count
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
    
    remove_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart', 999 );
    add_action( 'storefront_after_footer', 'storefront_sticky_single_add_to_cart_custom', 999 );
    
add_filter( 'woocommerce_product_thumbnails_columns', 	function() { return 4; } );
}
//Related Products for category page
function kni_wc_related_products_by_tag_attr($f_args = array())
{
    global $woocommerce_loop;


    $category = get_queried_object();

    if ( ! $category) {
        return;
    }

    $defaults = array(
        'posts_per_page' => 4,
        'columns'        => 4,
        'orderby'        => 'rand',
        'order'          => 'desc',
    );

    $f_args = wp_parse_args($f_args, $defaults);

    //todo-delete-on-production
    /*echo "<pre>";
    echo "wp_parse_args";
    echo "<br>";

    var_dump($f_args);
    echo "</pre>";*/
    //todo-delete-on-production

    /*
     * Get all attributes and tags for products of current category
     */
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'product',
        'post_status' => 'publish',
        'tax_query'   => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $category->term_id,
            )
        )
    );

    $prod_tags = array();
    $attrbts   = array();

    $prdcts = get_posts($args);

    if (count($prdcts)) {
        foreach ($prdcts as $k => $prdct) {

            //get all products tags
            $cur_prod_tags = wc_get_product_terms($prdct->ID, "product_tag", array("fields" => "ids"));

            if (count($cur_prod_tags)) {
                foreach ($cur_prod_tags as $tk => $tag) {
                    array_push($prod_tags, $tag);
                }
            }

            //get all attributes
            $cur_prod_attrs = get_post_meta($prdct->ID, "_product_attributes", true);

            if (count($cur_prod_attrs) && is_array($cur_prod_attrs)) {
                foreach ($cur_prod_attrs as $slg => $attr) {
                    array_push($attrbts, $slg);
                }
            }


        }
    }

    $prod_tags = array_unique($prod_tags);
    $prod_tags = array_values($prod_tags);

    $attrbts = array_unique($attrbts);
    $attrbts = array_values($attrbts);

    $attr_args = array();

    $attr_args['relation'] = 'OR';

    array_push($attr_args, array(
        'taxonomy' => 'product_tag',
        'field'    => 'id',
        'terms'    => $prod_tags,
        'operator' => 'IN'
    ));

    if (count($attrbts)) {
        foreach ($attrbts as $j => $attr) {

            array_push($attr_args, array(
                'taxonomy' => $attr,
                'field'    => 'id',
                'terms'    => wp_list_pluck(get_terms(array('taxonomy' => $attr, 'hide_empty' => false)), 'term_id'),
                'operator' => 'IN',

            ));

        }
    }

    /**
     * Custom product order
     */


    $orderby  = $f_args['orderby'];
    $order    = $f_args['order'];
    $meta_key = '';
    list($orderby, $order, $meta_key) = kni_custom_product_order($orderby, $order, $meta_key);


    /*
     * Output realted products
     */


    $args = array(
        'posts_per_page' => $f_args['posts_per_page'],
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'meta_key'       => $meta_key,
        'orderby'        => $orderby,
        'order'          => $order,
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'id',
                'terms'    => $category->term_id,
                'operator' => 'NOT IN'
            ),
            $attr_args
        )
    );

    $related_products = get_posts($args);


    // Get visble related products then sort them at random.
    $f_args['related_products'] = array_filter(array_map('wc_get_product', $related_products),
        'wc_products_array_filter_visible');


    // Handle orderby.
//	$args['related_products'] = wc_products_array_orderby( $f_args['related_products'], $f_args['orderby'], $f_args['order'] );
    $args['related_products'] = $f_args['related_products'];

    // Set global loop values.
    $woocommerce_loop['name']    = 'related';
    $woocommerce_loop['columns'] = apply_filters('woocommerce_related_products_columns', $f_args['columns']);


    wc_get_template('single-product/related.php', $f_args);

}


/**
 * Header
 */

//Remove blocks
add_action('init', 'custom_remove_header_blocks', 10);
function custom_remove_header_blocks()
{

    //remove search block
    remove_action('storefront_header', 'storefront_product_search', 40);

    //remove primary menu
    remove_action('storefront_header', 'storefront_primary_navigation', 50);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper', 42);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper_close', 68);

    //remove header cart
    remove_action('storefront_header', 'storefront_header_cart', 60);
}

//Move primary menu
add_action('storefront_header_primary_nav', 'storefront_primary_navigation', 50);
add_action('storefront_header_primary_nav', 'storefront_primary_navigation_wrapper', 42);
add_action('storefront_header_primary_nav', 'storefront_primary_navigation_wrapper_close', 68);

//change layout of header cart link
if ( ! function_exists('storefront_cart_link')) {
    /**
     * Cart Link
     * Displayed a link to the cart including the number of items present and the cart total
     *
     * @return void
     * @since  1.0.0
     */
    function storefront_cart_link()
    {
        ?>
        <a class="cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>"
           title="<?php esc_attr_e('View your shopping cart', 'storefront'); ?>">
            <?php echo wp_kses_data( WC()->cart->get_cart_contents_count()); ?>
        </a>
        <?php
    }
}


//Search
if ( ! function_exists('storefront_product_search')) {
    /**
     * Display Product Search
     *
     * @since  1.0.0
     * @uses  storefront_is_woocommerce_activated() check if WooCommerce is activated
     * @return void
     */
    function storefront_product_search()
    {
        if (storefront_is_woocommerce_activated()) { ?>
            <div class="site-search">
                <form role="search" method="get" class="search-form woocommerce-product-search"
                      action="<?php echo home_url('/'); ?>">
                    <label>
                        <span class="screen-reader-text"><?php echo _x('Search for:', 'label') ?></span>
                        <input type="search" class="search-field"
                               placeholder="<?php echo esc_attr_x('TYPE HERE...', 'placeholder') ?>"
                               value="<?php echo get_search_query() ?>" name="s"
                               title="<?php echo esc_attr_x('Search for:', 'label') ?>"/>
                    </label>
                    <input type="submit" class="search-submit"
                           value="<?php echo esc_attr_x('Search', 'submit button') ?>"/>
                </form>
            </div>
            <?php
        }
    }
}


/**
 * Footer
 */
//Remove footer credit
add_action('init', 'custom_remove_footer_credit', 10);

function custom_remove_footer_credit()
{
    remove_action('storefront_footer', 'storefront_credit', 20);
    add_action('storefront_footer', 'custom_storefront_credit', 20);
}

function custom_storefront_credit()
{
    ?>
    <!--
    <div class="payment_system_logos">
        <img src="<?php the_field('payment_system_logos', 'option'); ?>" alt=""/>
    </div> -->
    <div class="site-info">
        <?php echo get_bloginfo('name') . " &copy;  " . date('Y'); ?>
    </div><!-- .site-info -->
    <?php
}

/*
 * ACF. Flexible contents.
 */

function kni_get_acf_flex_content($row_layout = false, $i = 0)
{

    if ($row_layout) {

        if ($row_layout == 'full_width_image_title_subtitle') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix full_width_image_title_subtitle_block"
                 style="background-image: url(<?php the_sub_field('image'); ?>)">
                <div class="col-full">
                    <div class="title_subtitle_block">
                        <h1><?php the_sub_field('title'); ?></h1>
                        <a class="subtitle" href="<?php the_sub_field('subtitle_link'); ?>">
                            <?php the_sub_field('subtitle'); ?>
                            <div class="line_and_dot_sep">
                                <div class="dot"></div>
                            </div>
                        </a>

                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'full_width_slider_image_title_subtitle') {

            ?>

            <div id="block_<?php echo $i; ?>" class="clearfix full_width_slider_image_title_subtitle">
                <?php

                if (have_rows('slider')):

                    $k = 0;

                    // loop through the rows of data
                    while (have_rows('slider')) : the_row();
                        $k++;
                        ?>
                        <div class="full_width_image_title_subtitle_block kni_slide <?php if ($k == 1) {
                            echo "active";
                        } ?> " style="background-image: url(<?php the_sub_field('image'); ?>)<?php if ($k != 1) {
                            echo ";display:none;";
                        } ?>"
                             data-img-desktop="<?php the_sub_field('image'); ?>"
                             data-img-tablet="<?php the_sub_field('image__tablet'); ?>"
                             data-img-mobile="<?php the_sub_field('image_mobile'); ?>">
                <div class="next-slide"></div>
                <div class="prev-slide"></div>
                            <div class="col-full">
                                <div class="title_subtitle_block">
                                    <h1><?php the_sub_field('title'); ?></h1>
                                    <a class="subtitle" href="<?php the_sub_field('subtitle_link'); ?>">
                                        <?php the_sub_field('subtitle'); ?>
                                    </a>
                                    <div class="line_and_dot_sep"><div class="dot"></div>                                        </div>
                                    <?php if(get_sub_field('button_show')) { ?>
                                    <a href="<?php the_sub_field('button_link'); ?>" class="slider_button"><?php the_sub_field('button_text'); ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php

                    endwhile;
                endif;
                ?>
            </div>


            <?php

        } elseif ($row_layout == 'one_column_text') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix one_column_text_block ">
                <div class="col-full">
                    <div class="content">
                        <?php the_sub_field('text'); ?>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'two_columns_baners_image_text_button') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix two_columns_baners_block ">
                <div class="col-full">

                    <?php

                    // check if the nested repeater field has rows of data
                    if (have_rows('baners')):

                        $k = 0;

                        // loop through the rows of data
                        while (have_rows('baners')) : the_row();
                            $k++;

                            /*
                            if ($k % 2 != 0) {
                                echo '<div class="kni_row" >';
                            } */

                            if (get_sub_field('title')!="") {
                            ?>
                            <div class="kni_col" id="<?php echo str_replace(' ', '', get_sub_field('title'));?>">
                                <div class="kni_baner">
                                    <div class="baner_content">
                                        <h1><a href="<?php the_sub_field('button_link'); ?>"><?php echo get_sub_field('title'); ?></a></h1>
                                        <div class="text"><a href="<?php the_sub_field('button_link'); ?>">
                                            <?php echo get_sub_field('text'); ?></a></div>
                                        <a class="kni_button"
                                           href="<?php the_sub_field('button_link'); ?>"><?php the_sub_field('button_text'); ?></a>
                                        <div class="line_and_dot_sep">
                                            <div class="dot"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kni_col">
                                <a href="<?php the_sub_field('button_link'); ?>"><div class="kni_baner" style="background-image: url(<?php the_sub_field('image'); ?>)"
                                     data-img-tablet="<?php the_sub_field('image_tablet'); ?>"
                                     data-img-mobile="<?php the_sub_field('image_mobile'); ?>">
                                    </div></a>
                            </div>

                            <?php
                            }

                            /*
                            if ($k % 2 == 0) {
                                echo '</div>';
                            } */

                        endwhile;
                    endif;
                    ?>

                </div>
            </div>
            <?php

        } elseif ($row_layout == 'featured_products') {

            $meta_query  = WC()->query->get_meta_query();
            $tax_query   = WC()->query->get_tax_query();
            $tax_query[] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => 'IN',
            );

            $args = array(
                'post_type'      => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => get_sub_field('limit'),
                'meta_query'     => $meta_query,
                'tax_query'      => $tax_query,
            );


            $featured_query = new WP_Query($args);

            if ($featured_query->have_posts()) :

                ?>
                <div id="block_<?php echo $i; ?>" class="clearfix woocommerce_featured_products ">
                    <div class="col-full">
                        <h1><?php the_sub_field('title'); ?></h1>

                        <?php
                        $j = 0;
                        while ($featured_query->have_posts()) :

                            $featured_query->the_post();
                            global $product;
                            $j++;
                            ?>

                            <?php

                            if ($j == 1) {
                                echo '<div class="kni_row" >';
                            }

                            ?>
                            <div class="kni_col">
                                <a href="<?php echo $product->get_permalink(); ?>" class="product_box">
                                    <div class="product_image">
                                        <?php
                                        if (has_post_thumbnail($featured_query->post->ID)) {
                                            echo get_the_post_thumbnail($featured_query->post->ID, array(270, 270));
                                        } else {
                                            echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="270" height="270" />';
                                        }
                                        ?>
                                    </div>
                                    <div class="product_title"><?php the_title(); ?></div>
                                    <div class="product_price"><?php echo $product->get_price_html(); ?></div>
                                    <a href="<?php echo $product->get_permalink(); ?>"
                                       class="product_button"><?php the_sub_field('product_button_text'); ?></a>
                                </a>
                            </div>

                            <?php

                            if ($j % 3 == 0 || ($featured_query->current_post + 1) == ($featured_query->post_count)) {
                                echo '</div>';
                                $j = 0;
                            } elseif ($j % 3 != 0 && ($featured_query->current_post + 1) == ($featured_query->post_count)) {
                                echo '<div class="kni_col empty" ></div></div>';
                            }

                            ?>


                        <?php

                        endwhile;

                        ?>

                        <a href="<?php the_sub_field('button_more_products_link'); ?>"
                           class="more_products_button"><?php the_sub_field('button_more_products_text'); ?></a>
                    </div>
                </div>
            <?php

            endif;

            wp_reset_query();


        } elseif ($row_layout == 'two_columns_image_title_subtitle_button') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix two_columns_image_title_subtitle_button ">

                <div class="kni_row">
                    <div class="kni_col" style="background-image: url(<?php the_sub_field('image'); ?>)"
                         data-img-tablet="<?php the_sub_field('image_tablet'); ?>"
                         data-img-mobile="<?php the_sub_field('image_mobile'); ?>"></div>
                    <div class="kni_col">
                        <div class="content">
                            <h1><?php the_sub_field('title'); ?></h1>
                            <div class="subtitle"><?php the_sub_field('subtitle'); ?></div>
                            <a class="kni_button"
                               href="<?php the_sub_field('button_link'); ?>"><?php the_sub_field('button_text'); ?></a>
                        </div>
                    </div>
                </div>

            </div>
            <?php

        } elseif ($row_layout == 'two_columns_singup_form_titles_subtitle_button') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix two_columns_singup_form_titles_subtitle_button ">
                <div class="col-full">
                    <div class="kni_row">
                        <div class="kni_col">
                            <h1><?php the_sub_field('column_1_title'); ?></h1>
                            <div class="form"><?php the_sub_field('form_shortcode'); ?></div>
                        </div>
                        <div class="kni_col">
                            <h1><?php the_sub_field('column_2_title'); ?></h1>
                            <div class="subtitle"><?php the_sub_field('subtitle'); ?></div>
                            <a class="kni_button"
                               href="<?php the_sub_field('button_link'); ?>"><?php the_sub_field('button_text'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'three_columns_recent_posts') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix three_columns_recent_posts ">
                <div class="col-full">

                    <h1><?php the_sub_field('section_title'); ?></h1>

                    <?php

                    $posts = get_posts(array(
                        'numberposts' => get_sub_field('limit'),
                        'post_type'   => 'post',
                        'post_status' => 'publish',
                    ));

                    if (count($posts)) {
                        $i = 0;
                        foreach ($posts as $k => $post) {
                            $i++;
                            if ($i == 1) {
                                echo '<div class="kni_row" >';
                            }

                            if (has_post_thumbnail($post->ID)) {
                                $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                            } else {
                                $thumbnail = wc_placeholder_img_src();
                            }

                            ?>
                            <div class="kni_col">
                                <div class="image" style="background-image: url(<?php echo $thumbnail; ?>)"></div>
                                <div class="date"><?php echo get_the_date('F j, Y', $post); ?></div>
                                <div class="title"><?php echo $post->post_title; ?></div>
                                <a href="<?php echo get_permalink($post); ?>" class="post_button">
                                    <div class="line"></div><?php the_sub_field('button_text'); ?></a>
                            </div>
                            <?php
                            if ($i % 3 == 0) {
                                echo "</div>";
                                $i = 0;
                            } elseif ($i == 1 && count($posts) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div></div>';
                            } elseif ($i == 2 && count($posts) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div><div class="kni_col empty" ></div></div>';
                            }


                        }
                    }

                    ?>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'three_columns_features_icon_title_subtitle') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix three_columns_features_icon_title_subtitle">
                <div class="col-full">

                    <?php

                    $features = get_sub_field('features');

                    if (count($features)) {

                        $i = 0;
                        foreach ($features as $k => $feature) {

                            $i++;

                            if ($i == 1) {
                                echo '<div class="kni_row" >';
                            }
                            ?>


                            <div class="kni_col">
                                <div class="icon" style="background-image: url(<?php echo $feature['icon'] ?>) "></div>
                                <div class="title"><?php echo $feature['title'] ?></div>
                                <div class="subtitle"><?php echo $feature['subtitle'] ?></div>
                            </div>


                            <?php

                            if ($i % 3 == 0) {
                                echo '</div>';
                                $i = 0;
                            } elseif ($i == 1 && count($features) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div><div class="kni_col empty" ></div></div>';
                            } elseif ($i == 2 && count($features) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div></div>';
                            }

                        }
                    }
                    ?>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'three_columns_contacts') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix three_columns_contacts">
                <div class="col-full">

                    <h1><?php the_sub_field('membership_benefits_title'); ?></h1>
                    <!--<h3><?php the_sub_field('membership_benefits_subtitle'); ?></h3>-->

                    <?php

                    $contacts = get_sub_field('contacts');

                    /*
                    echo "<pre>";
                        var_dump($features);
                    echo "</pre>";
                    */

                    if (count($contacts)) {

                        $i = 0;
                        foreach ($contacts as $k => $contact) {

                            $i++;

                            if ($i == 1) {
                                echo '<div class="kni_row" >';
                            }
                            ?>


                            <div class="kni_col">
                                <div class="title"><?php echo $contact['title'] ?></div>
                                <div class="line_and_dot_sep"><div class="dot"></div></div>
                                <!--<div class="value"><?php echo $contact['value'] ?></div>
                                <div class="description"><?php echo $contact['description'] ?></div>-->
                            </div>


                            <?php

                            if ($i % 3 == 0) {
                                echo '</div>';
                                $i = 0;
                            } elseif ($i == 1 && count($contacts) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div><div class="kni_col empty" ></div></div>';
                            } elseif ($i == 2 && count($contacts) == ($k + 1)) {
                                echo '<div class="kni_col empty" ></div></div>';
                            }

                        }
                    }
                    ?>
                    <div class="bottom_mc_form">
                    <?php echo do_shortcode('[yikes-mailchimp form="2"]'); ?>
                    </div>
                    <!--<a href="#" class="become_vip_button"><?php the_sub_field('membership_benefits_button_text'); ?></a>
                    button type="button"><?php the_sub_field('membership_benefits_button_text'); ?></button-->
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'featured_product') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix featured_product">
                <ul class="products">
                    <li class="product-category product first">
                        <ul class="category">

                            <li class="image" style="background-image: url(<?php the_sub_field('image'); ?>); "></li>
                            <li class="info">
                                <h2 class="title"><?php the_sub_field('title'); ?></h2>
                                <div class="description"><?php the_sub_field('text'); ?></div>
                                <a class="kni_button"
                                   href="<?php the_sub_field('button_link'); ?>"><?php the_sub_field('button_text'); ?></a>
                            </li>

                        </ul>
                    </li>
                </ul>
            </div>
            <?php

        } elseif ($row_layout == 'contact_us_block__title_form_fullwidth_image_') {

            ?>

            <div id="block_<?php echo $i; ?>" class="clearfix contact_us_block__title_form_fullwidth_image_"
                 style="background-image: url(<?php the_sub_field('image'); ?>)">
                <div class="col-full">
                    <div class="title_form_block">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/contact-us-logo-rapaport-collection.png">
                        <h1><?php the_sub_field('title'); ?></h1>
                        <div class="line_and_dot_sep"><div class="dot"></div></div>
                        <p> <?php the_sub_field('phone_email_line1'); ?><br>
                            <?php the_sub_field('phone_email_line2'); ?><br>
                            <?php the_sub_field('phone_email_line3'); ?></p>
                        <div class="form"><?php echo do_shortcode(get_sub_field('form_shortcode')); ?></div>
                    </div>
                </div>
            </div>

            <?php

        } elseif ($row_layout == 'one_column_text_block__grey_background') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix one_column_text_block__grey_background">
                <div class="col-full">
                    <div class="content">
                        <?php the_sub_field('text'); ?>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'kni_heading') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix kni_heading">
                <div class="col-full">
                    <div class="content">
                        <h1><?php the_sub_field('text'); ?></h1>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'two_columns_image_title_description') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix two_columns_image_title_description">
                <div class="col-full">
                    <div class="kni_row">
                        <div class="kni_col" style="background-image: url(<?php the_sub_field('image'); ?>)"></div>
                        <div class="kni_col">
                            <h1><?php the_sub_field('title'); ?></h1>
                            <div class="content"><?php the_sub_field('description'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'one_column_title_description') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix one_column_title_description">
                <div class="col-full" style="background-color: <?php the_sub_field('background_color'); ?>">
                    <div class="content">
                        <h1><?php the_sub_field('title'); ?></h1>
                        <div class="description"><?php the_sub_field('description'); ?></div>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'reverse_two_columns_image_title_description') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix two_columns_image_title_description reverse">
                <div class="col-full">
                    <div class="kni_row">
                        <div class="kni_col" style="background-image: url(<?php the_sub_field('image'); ?>)"></div>
                        <div class="kni_col">
                            <h1><?php the_sub_field('title'); ?></h1>
                            <div class="content"><?php the_sub_field('description'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php

        } elseif ($row_layout == 'team_blocks__two_columns_image_title_description_') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix team_blocks__two_columns_image_title_description_">


                <?php

                $blocks = get_sub_field('blocks');

                if (count($blocks)) {

                    $j = 1;
                    foreach ($blocks as $k => $block) {

                        ?>


                        <div class="kni_row">
                            <div class="kni_col">
                                <div class="content">
                                    <!-- div class="description" -->
                                        <div class="kni_col team_blocks_photo" style="background-image: url(<?php echo $block['image']; ?>)"></div>
                                        <h1><?php echo $block['full_name']; ?></h1>
                                        <h3><?php echo $block['title']; ?></h3>
                                        <hr>
                                        <?php echo $block['description']; ?>
                                        <?php if ($j<count($blocks)) : echo '<hr class="teammates-divider">'; $j++; endif; ?>
                                    <!-- /div -->
                                </div>
                            </div>
                        </div>

                        <?php

                    }
                }

                ?>


            </div>
            <?php

        } elseif ($row_layout == 'coming_soon_page_subtitle') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix coming_soon_page_subtitle">

                <?php the_sub_field('text'); ?>

            </div>
            <?php

        } elseif ($row_layout == 'intro_paragraph') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix intro_paragraph">

                <?php the_sub_field('text'); ?>

            </div>
            <?php

        } elseif ($row_layout == 'divider_line_with_title') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix divider_line_with_title">

                <div class="ttl"><?php the_sub_field('title'); ?></div>

                <div class="line_and_dot_sep"></div>

            </div>
            <?php

        } elseif ($row_layout == 'standard_text_box') {

            ?>
            <div id="block_<?php echo $i; ?>" class="clearfix standard_text_box">
                <div class="col-full">
                <?php


                $img_html = '';
                if(get_sub_field('include_featured_image')){
                    $img = get_sub_field('image');
                    $img_link = '';
                    $img_blank = '';
                    $img_link = get_sub_field('image_hyperlink');
                    $img_blank = get_sub_field('open_image_link_in_new_tab');
                    if($img_blank) $img_blank=' target="_blank"';
                    if($img_link) $img_html = '<a href="'.$img_link.'"'.$img_blank.'>';
                    $img_html .= '<img src="'.$img.'" alt="" >';
                    $img_html .= '</a>';
                }


                $img_desc = '';
                if (get_sub_field('show_image_description')) {
                    $img_desc = "<div class='img_desc' >" . get_sub_field('image_description') . "</div>";
                }




                if (get_sub_field('reverse')) {

                    $img_block = <<<EOD
<div class="img_block right" >
{$img_html}
{$img_desc}
</div>
EOD;


                    echo $img_block;

                } else {

                    $img_block = <<<EOD
<div class="img_block left" >
{$img_html}
{$img_desc}
</div>
EOD;

                    if(empty($img_html) && empty($img_desc)){
                        $img_block = '';
                    }

                    echo $img_block;


                }

                echo "<div class='text' >".get_sub_field('text')."</div>";

                ?>
            </div>
            </div>
            <?php

        }elseif ($row_layout == 'gap') {

            ?>
            <div id="block_<?php echo $i; ?>" style="height:<?php echo get_sub_field('height_in_pixels') ?>px" class="gap"></div>
            <?php

        }elseif ($row_layout == 'curator_about_author_block') {

            ?>
            <div id="block_<?php echo $i; ?> clearfix" class="curator_about_author">
                <div class="col-full">
                    <img src="<?php the_sub_field('image'); ?>" alt="">
                    <div class="text"><?php the_sub_field('text'); ?></div>
                </div>
            </div>
            <?php

        }elseif ($row_layout == 'curator_text_block') {

            ?>
            <div id="block_<?php echo $i; ?> clearfix" class="curator_text">
                <div class="col-full">
                    <h3 class="title"><?php the_sub_field('title'); ?></h3>
                    <div class="text"><?php the_sub_field('text'); ?></div>
                </div>
            </div>
            <?php

        }elseif ($row_layout == 'curator_blocks') {

            ?>
            <div id="block_<?php echo $i; ?> clearfix" class="curator_blocks">
                <div class="col-full">
                    <h3 class="title"><span><?php the_sub_field('title'); ?></span></h3>
                    <?php if( have_rows('blocks') ):?>
                        <ul class="list">
                        <?php  while ( have_rows('blocks') ) : the_row(); ?>
                            <li class="item clearfix">
                                <div class="line"></div>
                                <div class="textPart">
                                    <h4 class="heading"><?php the_sub_field('heading'); ?></h4>
                                    <div class="text"><?php the_sub_field('text'); ?></div>
                                </div>
                                <div class="imagePart">
                                    <img src="<?php the_sub_field('image'); ?>" alt="">
                                    <div class="description"><?php the_sub_field('image_description'); ?></div>
                                </div>
                            </li>
                        <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php

        }


    }

}

/**
 * Capital letter
 */
add_shortcode('cl', 'kni_capital_letter');
function kni_capital_letter($atts)
{

    return "<span class='kni_cap_letter'>" . $atts['letter'] . "</span>";
}


// Re-ordering Checkout form fields
//remove_action('woocommerce_checkout_fields', 'billing_company', 20 );
add_filter( 'woocommerce_checkout_fields', 'reorder_woo_fields', 999 );
function reorder_woo_fields( $fields ) {

    // let's make a copy
    $fields2['billing'] = $fields['billing'];


    // setting the priority for each field:
    $fields2['billing']['billing_first_name']['priority'] = 10;
    $fields2['billing']['billing_last_name']['priority'] = 20;
    $fields2['billing']['billing_email']['priority'] = 30;
    $fields2['billing']['billing_country']['priority'] = 35;
    $fields2['billing']['billing_address_1']['priority'] = 40;
    $fields2['billing']['billing_address_2']['priority'] = 50;
    $fields2['billing']['billing_city']['priority'] = 60;
    $fields2['billing']['billing_state']['priority'] = 80;
    $fields2['billing']['billing_postcode']['priority'] = 90;
    $fields2['billing']['billing_phone']['priority'] = 100;
    $fields2['billing']['billing_company']['priority'] = 110;

    // fixing sizes of the fields by setting classes to get good layout:
    $fields2['billing']['billing_email']['class'] = array('form-row-wide');
    $fields2['billing']['billing_postcode']['class'] = array('form-row-first');
    $fields2['billing']['billing_phone']['class'] = array('form-row-last');

    // Tweaking other details according to PSD:
    $fields2['billing']['billing_country']['required'] = false;
    $fields2['billing']['billing_phone']['required'] = false;
    $fields2['billing']['billing_email']['label'] = 'Email';
// !!! did not work well:
    $fields2['billing']['billing_address_1']['required'] = false;
    $fields2['billing']['billing_city']['required'] = false;
    $fields2['billing']['billing_state']['required'] = false;
    $fields2['billing']['billing_state']['placeholder'] = 'Select a State';
    $fields2['billing']['billing_postcode']['required'] = false;
    $fields2['billing']['billing_city']['label'] = 'City';
    $fields2['billing']['billing_postcode']['label'] = 'Postal Code/Zip';

    // Remove Checkout billing address placeholder
    $fields2['billing']['billing_address_1']['placeholder']  = '';
    $fields2['billing']['billing_address_2']['placeholder']  = '';
    unset($fields2['billing']['billing_address_2']);
    unset($fields2['billing']['billing_company']);
    unset($fields2['shipping']['shipping_address_2']);
    unset($fields2['shipping']['shipping_company']);

        $fields2['billing']['billing_city']['class']   = array('form-row-first'); //  50%
        $fields2['billing']['billing_state']['class']   = array('form-row-last');  //  50%
        //$fields['billing']['billing_company']['class'] = array('form-row-wide');  // 100%
    
    
    
    //just copying these (keeps the standard order)
    $fields2['shipping'] = $fields['shipping'];
    $fields2['account']  = $fields['account'];
    $fields2['order']    = $fields['order'];

    // Hide Checkout additional information label
    $fields2['order']['order_comments']['label'] = 'Any additional information';
    $fields2['order']['order_comments']['placeholder'] = '';

    // Fixing reordered fields clases:
/*
    $fields2['billing']['billing_email'] = array(
        'label'     => __('Email address', 'woocommerce'),
        'required'  => true,
        'class'     => array('form-row', 'form-row-wide', 'validate-required', 'validate-email'),
        'clear'     => true
    );
    //form-row form-row-last validate-required validate-email
*/

    return $fields2;
}

function wpb_custom_billing_fields( $fields = array() ) {
	unset($fields['shipping_address_2']);
	unset($fields['shipping_company']);
	return $fields;
}
add_filter('woocommerce_shipping_fields','wpb_custom_billing_fields', 999);


add_filter( 'woocommerce_default_address_fields' , 'wpse_120741_wc_def_state_label' );
function wpse_120741_wc_def_state_label( $address_fields ) {
     $address_fields['state']['label'] = 'Province';
     $address_fields['postcode']['label'] = 'Postal Code/Zip';
     return $address_fields;
}

add_filter( 'default_checkout_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_state', 'change_default_checkout_state' );
function change_default_checkout_country() {
  return 'US';
}
function change_default_checkout_state() {
  return 'Alabama';
}

add_filter('woof_sort_terms_before_out', 'brand_filter');
function brand_filter($terms) {
    $arr = reset($terms);
    if($arr['taxonomy']!='pa_brand') return $terms;
    foreach($terms as $key => $value) {
        if(!count(get_posts(array(
            'post_status' => 'publish',
            'post_type' => 'product',
            'tax_query' => array(
			     array(
				    	'taxonomy' 		=> 'pa_brand',
						'terms' 		=> array($key),
						'field' 		=> 'id',
						'operator' 		=> 'IN'
				)
			)
        )))) unset($terms[$key]);
    }
    return $terms;
}



// RING SIZE
/*
function decimalToFraction($decimal)
{
    if ($decimal < 0 || !is_numeric($decimal)) {
        // Negative digits need to be passed in as positive numbers
        // and prefixed as negative once the response is imploded.
        return false;
    }
    if ($decimal == 0) {
        return [0, 0];
    }

    $tolerance = 1.e-4;

    $numerator = 1;
    $h2 = 0;
    $denominator = 0;
    $k2 = 1;
    $b = 1 / $decimal;
    do {
        $b = 1 / $b;
        $a = floor($b);
        $aux = $numerator;
        $numerator = $a * $numerator + $h2;
        $h2 = $aux;
        $aux = $denominator;
        $denominator = $a * $denominator + $k2;
        $k2 = $aux;
        $b = $b - $a;
    } while (abs($decimal - $numerator / $denominator) > $decimal * $tolerance);

    return [
        $numerator,
        $denominator
    ];
}*/
function iconic_output_engraving_field() {
    global $product;
        if(get_field('show_ring_size_selector', $product->get_id())) { ?>
        <div class="ring-size-field">
            <label for="ring_size_select" style="font-family: GothamLight;font-size: 14px;">Please choose your ring size</label>
            <select name="ring_size_select" id="ring_size_select">
                <option value="NA">Ring Size</option>
                <?php 
                    $sizes = explode(',', get_field('ring_selector_sizes', $product->get_id()));
                    foreach($sizes as $size) { ?>
                    <option value="<?php echo trim($size); ?>"><?php echo trim($size); ?></option>
                <?php } ?>
                <?php /* for($i=300;$i<=1000;$i=$i+25) {
                    $whole = floor($i/100);
                    $fraction = $i/100 - $whole;
                    $num = decimalToFraction($fraction);
                ?>
                <option value="<?php echo $whole; if($num[0]!=0) { echo ' '.$num[0] . "/" . $num[1]; } ?>"><?php echo $whole; if($num[0]!=0) { echo ' '.$num[0] . "/" . $num[1]; }  ?></option>
                <?php } */ ?>
            </select>
            <a href="#" class="size_guide_show">Size Guide</a>
            <div class="error" style="display:none;">Please choose your ring size</div>
        </div>
        <?php }
} 
add_action( 'woocommerce_before_add_to_cart_button', 'iconic_output_engraving_field', 10 );


function iconic_add_engraving_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
    $ring_size = filter_input( INPUT_POST, 'ring_size_select' );
 
    if ( empty( $ring_size ) ) {
        return $cart_item_data;
    }
 
    $cart_item_data['ring_size_select'] = $ring_size;
 
    return $cart_item_data;
}
 
add_filter( 'woocommerce_add_cart_item_data', 'iconic_add_engraving_text_to_cart_item', 10, 3 );


add_action( 'woocommerce_before_calculate_totals', 'custom_cart_items_prices', 10, 1 );
function custom_cart_items_prices( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items
    foreach ( $cart->get_cart() as $cart_item ) {

        // Get an instance of the WC_Product object
        $product = $cart_item['data'];

        // Get the product name (Added Woocommerce 3+ compatibility)
        $original_name = method_exists( $product, 'get_name' ) ? $product->get_name() : $product->post->post_title;

        // SET THE NEW NAME
        
        if($cart_item['ring_size_select']) {
            $original_name = $original_name.'<br/>Ring Size: '.$cart_item['ring_size_select']; 
        }

        // Set the new name (WooCommerce versions 2.5.x to 3+)
        if( method_exists( $product, 'set_name' ) )
            $product->set_name( $original_name );
        else
            $product->post->post_title = $original_name;
    }
}

function iconic_add_engraving_text_to_order_items( $item, $cart_item_key, $values, $order ) {
    if ( empty( $values['ring_size_select'] ) ) {
        return;
    }
 
    $item->add_meta_data( 'Ring Size', $values['ring_size_select'] );
}
 
add_action( 'woocommerce_checkout_create_order_line_item', 'iconic_add_engraving_text_to_order_items', 10, 4 );
