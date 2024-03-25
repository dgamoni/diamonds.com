<?php
/**
 * Child theme functions
 *
 * Functions file for child theme, enqueues parent and child stylesheets by default.
 *
 * @since   1.0.0
 * @package aa
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter('woocommerce_get_item_data', 'woocommerce_get_item_data_filter', 10, 2);
function woocommerce_get_item_data_filter($item_data, $cart_item ) {
    if(isset($cart_item['variation']['attribute_settings']) && isset($cart_item['variation']['attribute_diamonds'])) {
        foreach($item_data as $key => $value) {
            if($value['key']=='Settings') $item_data[$key]['value'] = $item_data[$key]['value'].'<br/>'.get_post_meta($cart_item['variation_id'], '_custom_sku_setting', true);
            if($value['key']=='Diamonds') $item_data[$key]['value'] = $item_data[$key]['value'].'<br/>'.get_post_meta($cart_item['variation_id'], '_custom_sku_diamond', true);
        }
    }
    return $item_data;
}


/**
 * Force SSL on WPEngine install
 * 
 * @author Bill Erickson
 * @see https://www.billerickson.net/force-ssl-on-wpengine
 *
 */
function be_force_ssl_on_wpengine() {
	if( strpos( home_url(), 'wpengine' ) && ! is_ssl() ) {
		wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 301 );
		exit();
	}
}
add_action( 'template_redirect', 'be_force_ssl_on_wpengine' );

//wc_format_price_range($from, $to)

add_filter('woocommerce_variable_sale_price_html', 'shop_variable_product_price', 10, 2);
add_filter('woocommerce_variable_price_html','shop_variable_product_price', 10, 2 );
function shop_variable_product_price( $price, $product ){
    $variation_min_reg_price = $product->get_variation_regular_price('min', true);
    $variation_min_sale_price = $product->get_variation_sale_price('min', true);
    $variation_max_reg_price = $product->get_variation_regular_price('max', true);
    $variation_max_sale_price = $product->get_variation_sale_price('max', true);
    if(get_post_meta($product->get_id(), '_variations_min_price', true)) $variation_min_reg_price = get_post_meta($product->get_id(), '_variations_min_price', true);
    if(get_post_meta($product->get_id(), '_variations_min_price', true)) $variation_min_sale_price = get_post_meta($product->get_id(), '_variations_min_price', true);

			if ( $variation_max_reg_price !== $variation_min_reg_price ) {
				$price = wc_format_price_range( $variation_min_reg_price, $variation_max_reg_price );
			} elseif ( $product->is_on_sale() && $variation_max_reg_price === $variation_min_reg_price ) {
				$price = wc_format_sale_price( wc_price( $variation_max_reg_price ), wc_price( $variation_min_reg_price ) );
			} else {
				$price = wc_price( $variation_min_reg_price );
			}

    return $price;
}

// Fixing header CART menu item
/*
if ( ! function_exists( 'storefront_cart_link' ) ) {
    function storefront_cart_link() {
        ?>
            <a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'storefront' ); ?>">
                <?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?> <span class="count"><?php echo wp_kses_data( sprintf( '%d', WC()->cart->get_cart_contents_count() ) );?></span>
            </a>
        <?php
    }
} */

require_once('kni_layout_functions.php');

add_action('wp_head', 'deregister_woof_for_filter_centered', 9999);
function deregister_woof_for_filter_centered() {
    wp_dequeue_script( 'woof_select_radio_check_html_items' );
    wp_deregister_script( 'woof_select_radio_check_html_items' );
    wp_enqueue_script( 'woof_select_radio_check_html_items_custom', get_stylesheet_directory_uri() . '/js/select_radio_check.js', array( 'jquery' ));
}

add_action( 'wp_enqueue_scripts', 'kni_replace_plugin_scripts', 100 );
function kni_replace_plugin_scripts() {
    
    if(get_page_template_slug() == 'page-designers.php' ) {
        wp_enqueue_script( 'owl-carousel', '/wp-content/themes/rapcollection-child/js/owl.carousel.min.js', array( 'jquery' ), null, true);
        wp_enqueue_style( 'owl-carousel', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css' );
        wp_enqueue_style( 'owl-theme', get_stylesheet_directory_uri() . '/css/owl.theme.default.min.css' );
    }
    
    
    wp_dequeue_script( 'woocommerce-wishlists' );
    wp_deregister_script( 'woocommerce-wishlists' );
    wp_enqueue_script( 'woocommerce-wishlists', get_stylesheet_directory_uri() . '/woocommerce/wishlist-script/woocommerce-wishlists.js', array( 'jquery' ));
	$wishlist_params = array(
        'root_url'     => untrailingslashit( get_site_url() ),
		'current_url'  => esc_url_raw( add_query_arg( array() ) ),
		'are_you_sure' => __( 'Are you sure?', 'wc_wishlist' ),
    );
	wp_localize_script( 'woocommerce-wishlists', 'wishlist_params', apply_filters( 'woocommerce_wishlist_params', $wishlist_params ) );    
}

add_action('wp_enqueue_scripts','kni_scripts_and_styles');
function kni_scripts_and_styles(){

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ) );
	wp_enqueue_script ('kni_scripts', '/wp-content/themes/rapcollection-child/js/scripts.js', array( 'jquery' ), null, true);
	wp_enqueue_script ('kni_device', '/wp-content/themes/rapcollection-child/js/device.js', array( 'jquery' ), null, true);

}

add_action('admin_enqueue_scripts','kni_admin_scripts_and_styles');
function kni_admin_scripts_and_styles(){

    wp_enqueue_style( 'myadmin-style', get_stylesheet_directory_uri() . '/css/admin_css.css' );

}

/**
 * Theme Options
 */
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme_settings',
		'capability'	=> 'edit_posts'
	));

}

/**
 * Show empty categories
 */
add_filter( 'woocommerce_product_subcategories_hide_empty', 'hide_empty_categories', 10, 1 );
function hide_empty_categories ( $hide_empty ) {
    $hide_empty  =  FALSE;
    return $hide_empty;
}

/**
 * Override theme default specification for product # per row
 */
add_filter('storefront_loop_columns', 'loop_columns');
//add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3; // 2 products per row
	}
}


add_filter( 'woocommerce_order_button_text', 'woo_custom_order_button_text' );
function woo_custom_order_button_text() {
    return __( 'Place Order', 'woocommerce' );
}

/*
add_filter('storefront_loop_columns', 'storefront_loop_columns');
add_filter('loop_shop_columns', 'storefront_loop_columns');
if ( ! function_exists( 'storefront_loop_columns' ) ) {
	function storefront_loop_columns() {
		return apply_filters( 'storefront_loop_columns', 3 ); // 3 products per row
	}
} */


// Change number of columns per row
/*
add_filter('loop_shop_columns', 'change_loop_columns', 999);
add_filter('storefront_loop_columns', 'change_loop_columns', 999);
function change_loop_columns() {
    return 3;
} */


/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter( 'loop_shop_per_page', 'new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
  // $cols contains the current number of products per page based on the value stored on Options -> Reading
  // Return the number of products you wanna show per page.
  $cols = 9;
  return $cols;
}



/**
 * Product page
 */
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab_rsc' );
function woo_new_product_tab_rsc( $tabs ) {
/*
	$tabs['test_tab'] = array(
		'title' 	=> __( 'Request specific changes', 'woocommerce' ),
		'priority' 	=> 50,
		'callback' 	=> 'woo_new_product_tab_rsc_content'
	);
*/
	unset($tabs['additional_information']);

	return $tabs;

}
function woo_new_product_tab_rsc_content() {

	$page_id = get_id_by_slug('theme-settings');

	echo '<h2>Request specific changes</h2>';
	echo do_shortcode( get_field( "request_specific_changes_tab_form_shortcode", 'option'  ) );

}

//4 columns for related products
add_filter( 'storefront_related_products_args', "kni_related_products_args", 10, 1 );
function kni_related_products_args($args){

	$args['posts_per_page'] = 4;
	$args['columns'] = 4;

	return $args;
}

// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

// get_id_by_slug('any-page-slug');
function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}

//Object to array
function object_to_array($obj) {
	if(is_object($obj)) $obj = (array) $obj;
	if(is_array($obj)) {
		$new = array();
		foreach($obj as $key => $val) {
			$new[$key] = object_to_array($val);
		}
	}
	else $new = $obj;
	return $new;
}

// Is the product in the cart?
function woo_in_cart($product_id) {
    if(!WC()->cart->is_empty()):

        // Initialise the count
        $count = 0;

        foreach(WC()->cart->get_cart() as $cart_item ):

            $items_id = $cart_item['product_id'];

            // For an array of product IDS
            if(is_array($product_ids) && in_array($items_id, $product_ids))
                $count++; // incrementing the counted items

            // for a unique product ID (integer or string value)
            if($product_ids == $items_id)
                $count++; // incrementing the counted items

        endforeach;

        // returning counted items
        return $count;

    endif;
}

// php Imploade for multidimensional array
function multi_implode($glue, $array) {
    $_array=array();
    foreach($array as $val)
        $_array[] = is_array($val)? multi_implode($glue, $val) : $val;
    return implode($glue, $_array);
}

// Get Relevanssi to display excerpts from your custom fields

//add_filter('relevanssi_excerpt_content', 'kni_add_content_to_index', 10, 3);
add_filter('relevanssi_content_to_index', 'kni_add_content_to_index', 10, 3);
function kni_add_content_to_index($content, $post) {

	global $wpdb;
	$field_value_text = '';

	$fields = $wpdb->get_col("SELECT DISTINCT(meta_key) FROM $wpdb->postmeta");

	foreach($fields as $key => $field){

		$field_value = get_post_meta($post->ID, $field, TRUE);

		if( is_array($field_value) ) {
			$field_value_text .= ' '. multi_implode(' ', $field_value);
		} else {
			$field_value_text = $field_value;
		}

		$content .= ' ' . $field_value_text;

	}



	return $content;

}


// Breadcrumbs
function custom_breadcrumbs() {

    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Home';

    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';

    // Get the query & post information
    global $post,$wp_query;

    // Do not display on the homepage
    if ( !is_front_page() ) {

        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';

        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';

        } else if ( is_single() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            // Get post category info
            $category = get_the_category();

            if(!empty($category)) {



                // Get last category post is in
				$last_category = $category[count($category) - 1];



                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }

            }

            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;

            }

            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {

                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            } else {

                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            }

        } else if ( is_category() ) {

            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';

        } else if ( is_page() ) {

            // Standard page
            if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';

            } else {

                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';

            }

        } else if ( is_tag() ) {

            // Tag page

            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;

            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';

        } elseif ( is_day() ) {

            // Day archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_month() ) {

            // Month Archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_year() ) {

            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

        } else if ( is_author() ) {

            // Auhor archive

            // Get the author information
            global $author;
            $userdata = get_userdata( $author );

            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

        } else if ( get_query_var('paged') ) {

            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';

        } else if ( is_search() ) {

            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

        } elseif ( is_404() ) {

            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }

        echo '</ul>';

    }

}

/**
* Redirect users to my account page after login - GIVES BLANK SCREEN ON REGISTRATION
    function wc_custom_user_redirect( $redirect, $user ) {
        return get_permalink( get_option('woocommerce_myaccount_page_id'));
    }
    add_filter( 'woocommerce_login_redirect', 'wc_custom_user_redirect', 10, 2 );
    add_action('woocommerce_registration_redirect', 'wc_custom_user_redirect', 2);
*/


/**
 * @desc Remove in all product type
 */
function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

/**
 * Custom product order
 *
 */

function kni_custom_product_order($orderby,$order, $meta_key = ''){
    global $wpdb;

    //todo-delete-on-production
//    echo "<pre>";
//    var_dump($orderby);
//    echo "<br>";
//
//    var_dump($order);
//    echo "<br>";
//    var_dump($meta_key);
//
//    echo "</pre>";
    //todo-delete-on-production


    switch ($orderby) {
        case 'custom':
            $orderby = 'menu_order date';
            $order = 'ASC';
            break;
        case 'price-desc':
            $orderby = "meta_value_num {$wpdb->posts}.ID";

            if(!isset($order)){
                $order = 'DESC';
            }

            $meta_key = '_price';
            break;
        case 'price':
            $orderby = "meta_value_num {$wpdb->posts}.ID";

            if(!isset($order)){
                $order = 'ASC';
            }


            $meta_key = '_price';
            break;
        case 'popularity' :
            // Sorting handled later though a hook
            add_filter('posts_clauses', array(WC()->query, 'order_by_popularity_post_clauses'));
            $meta_key = 'total_sales';
            //$orderby = "meta_value_num {$wpdb->posts}.ID";
            //$order = 'DESC';
            //$meta_key = 'total_sales';
            break;
        case 'rating' :
            //$orderby = '';
            //$meta_key = '';
            //add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));

            $orderby = "meta_value_num {$wpdb->posts}.ID";
            $order = 'DESC';
            $meta_key = '_wc_average_rating';
            break;
        case 'title' :
            $orderby = 'title';
            break;
        case 'rand' :
            $orderby = 'rand';
            break;
        case 'date' :

            if(!isset($order)){
                $order = 'DESC';
            }

            $orderby = 'date';
            break;
        default:
            $orderby = 'menu_order date';
            break;
    }

    //todo-delete-on-production
    /*echo "<pre>";
    var_dump($orderby);
    echo "<br>";

    var_dump($order);
    echo "<br>";
    var_dump($meta_key);

    echo "</pre>";*/
    //todo-delete-on-production


    return array($orderby, $order, $meta_key);
}

/**
 * Custom excerpt length
 *
 * @param $string
 * @param $word_limit
 *
 * @return string
 */
function do_excerpt($string, $word_limit = 16)
{

    $string = strip_tags($string);
    $words = explode(' ', $string, ($word_limit + 1));
    if (count($words) > $word_limit) {
        array_pop($words);
    }

    return implode(' ', $words) . '...';
}



// Overriding Parent theme included functions
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];
$storefront = (object) array(
	'version' => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require get_stylesheet_directory() . '/inc/class-storefront.php',
	'customizer' => require get_template_directory() . '/inc/customizer/class-storefront-customizer.php',
);
require get_stylesheet_directory() . '/inc/storefront-template-functions.php';



// Tracing functions called
//error_log( wp_debug_backtrace_summary() );


add_action( 'save_post', 'update_price_attribute', 10, 3);

function update_price_attribute($post_id, $post, $update){
    if (!$product = wc_get_product( $post )) {
        return;
    }

                $price = round(get_post_meta( $post_id, '_regular_price', true));
                $slug = 'under-25000';
                if($price<25000) {
                    $slug = 'under-25000';
                }
                if($price>=25000 && $price<50000) {
                    $slug = '25000-to-50000';
                }
                if($price>=50000 && $price<100000) {
                    $slug = '50000-to-100000';
                }
                if($price>=100000 && $price<250000) {
                    $slug = '100000-to-250000';
                }
                if($price>=250000) {
                    $slug = '250000-and-over';
                }
                
            $attributes = $product->get_attributes();
            $term_taxonomy_ids = wp_set_object_terms($post_id, $slug, 'pa_price', false);              
                                
            $data = array(
                'pa_price' => array(
                    'name' => 'pa_price',
                    'value' => '',
                    'is_visible' => '1',
                    'is_variation' => '0',
                    'is_taxonomy' => '1'
                )
            );
            //First getting the Post Meta
            $_product_attributes = get_post_meta($post_id, '_product_attributes', TRUE);
            //Updating the Post Meta
            update_post_meta($post_id, '_product_attributes', array_merge($_product_attributes, $data));
    
    
    // PA PRICE 100
    

                $slug = '5000-10000';
                if($price<10000) {
                    $slug = '5000-10000';
                }
                if($price>=10000 && $price<15000) {
                    $slug = '10000-15000';
                }
                if($price>=15000 && $price<20000) {
                    $slug = '15000-20000';
                }
                if($price>=20000 && $price<25000) {
                    $slug = '20000-25000';
                }
                if($price>=25000 && $price<30000) {
                    $slug = '25000-30000';
                }
                if($price>=30000 && $price<50000) {
                    $slug = '30000-50000';
                }
                if($price>=50000 && $price<100000) {
                    $slug = '50000-100000';
                }
                if($price>=100000) {
                    $slug = '100000-and-over';
                }
                
            $attributes = $product->get_attributes();
            $term_taxonomy_ids = wp_set_object_terms($post_id, $slug, 'pa_price_100', false);              
                                
            $data = array(
                'pa_price_100' => array(
                    'name' => 'pa_price_100',
                    'value' => '',
                    'is_visible' => '1',
                    'is_variation' => '0',
                    'is_taxonomy' => '1'
                )
            );
            //First getting the Post Meta
            $_product_attributes = get_post_meta($post_id, '_product_attributes', TRUE);
            //Updating the Post Meta
            update_post_meta($post_id, '_product_attributes', array_merge($_product_attributes, $data));
}


// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );
/**
 * Create new fields for variations
 *
*/
function variation_settings_fields( $loop, $variation_data, $variation ) {
	// Text Field
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_text_field[' . $variation->ID . ']', 
			'label'       => __( 'Custom Variation Title', 'woocommerce' ), 
			'placeholder' => '',
			'desc_tip'    => 'true',
			'description' => __( 'Enter the custom variation title.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_text_field', true )
		)
	);
}
/**
 * Save new fields for variations
 *
*/
function save_variation_settings_fields( $post_id ) {
	// Text Field
	$text_field = $_POST['_text_field'][ $post_id ];
	if( ! empty( $text_field ) ) {
		update_post_meta( $post_id, '_text_field', esc_attr( $text_field ) );
	}
}


function designers_posttype() {
 
    register_post_type( 'designers',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Featured Designers' ),
                'singular_name' => __( 'Featured Designers' )
            ),
            'public' => true,
            'publicly_queryable'  => false,
            'supports' => array( 'title', 'thumbnail', 'custom-fields', ),
            'has_archive' => false,
            'rewrite' => array('slug' => 'featured-designer'),
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'designers_posttype' );





function woocommerce_ajax_add_to_cart_js() {
    wp_enqueue_script('woocommerce-ajax-add-to-cart', get_stylesheet_directory_uri() . '/js/ajax-add-to-cart.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'woocommerce_ajax_add_to_cart_js', 99);

add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'woocommerce_ajax_add_to_cart');
function woocommerce_ajax_add_to_cart() {
            $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
            $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
            $variation_id = absint($_POST['variation_id']);
            $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
            $product_status = get_post_status($product_id);

            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {

                do_action('woocommerce_ajax_added_to_cart', $product_id);

                if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
                    wc_add_to_cart_message(array($product_id => $quantity), true);
                }

                WC_AJAX :: get_refreshed_fragments();
            } else {

                $data = array(
                    'error' => true,
                    'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id));

                echo wp_send_json($data);
            }

            wp_die();
}


add_action( 'yikes-mailchimp-google-analytics', 'yikes_mailchimp_google_analytics', 10, 1 );

function yikes_mailchimp_google_analytics( $form_id ) {
	?>
		<script type="text/javascript">

			function yikes_mailchimp_google_analytics_success( response ) {
                var date = new Date();
                date.setTime(date.getTime() + 24 * 60 * 60 * 1000 * 365);
                cookie_string = "mailchimp_subscribed=1; expires=" + date.toGMTString();
                document.cookie = cookie_string;
                jQuery('.sf_popup_overlay').fadeOut('fast');
                jQuery('.subscription-add-to-cart').fadeOut('fast');
                jQuery('.sf_popup').fadeOut('fast');
                
                if(jQuery('.added-to-cart-info').length) {
                    jQuery('.sf_popup').css('top', jQuery(document).scrollTop()+25+'px');
                    jQuery('.added-to-cart-info').fadeIn('fast');
                    setTimeout(function() {
                       jQuery('.added-to-cart-info').fadeOut('fast');
                    }, 8000);
                }
			}

		</script>
	<?php
}

add_filter('gettext', 'change_checkout_btn');
add_filter('ngettext', 'change_checkout_btn');

//function
function change_checkout_btn($checkout_btn){
  $checkout_btn= str_ireplace('View cart', 'View shopping bag', $checkout_btn);
  $checkout_btn= str_ireplace('Checkout', 'Buy It Now', $checkout_btn);
  return $checkout_btn;
}

function my_text_strings( $translated_text, $text, $domain ) {
    switch ( strtolower( $translated_text ) ) {
        case 'View Cart' :
            $translated_text = __( 'View shopping bag', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'my_text_strings', 20, 3 );

/* 
   Debug preview with custom fields 
*/ 

add_filter('_wp_post_revision_fields', 'add_field_debug_preview');
function add_field_debug_preview($fields){
   $fields["debug_preview"] = "debug_preview";
   return $fields;
}

add_action( 'edit_form_after_title', 'add_input_debug_preview' );
function add_input_debug_preview() {
   echo '<input type="hidden" name="debug_preview" value="debug_preview">';
}