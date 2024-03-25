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
	
		$pid = get_option( 'woocommerce_shop_page_id' ); 
	
		$image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' )[0];



	?>

    <header class="woocommerce-products-header" style="background-image: url(<?php echo $image_url; ?>)" >
		<div class="col-full">
			<div class="woocommerce-category-description">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                    <?php
					
					$real_term_id = false;
					
					if (isset($_GET['really_curr_tax']) && $_GET['really_curr_tax']) {
						$real_term_id = explode('-', $_GET['really_curr_tax']);
						if ($real_term_id && (int)$real_term_id[0]) {
							$real_term_id = (int)$real_term_id[0];
							$term = get_term_by( 'id', $real_term_id, 'product_cat' ); 
						}
					}
					
					if (!$real_term_id && get_query_var('product_cat')) {
						$term = get_term_by( 'slug', get_query_var('product_cat'), 'product_cat' ); 
						$real_term_id = $term->term_id;
					}
					
                    if( $real_term_id) {
               

                
			/*	
$ancestors = get_ancestors( $term->term_id, 'product_cat' ); // Get a list of ancestors
$ancestors = array_reverse($ancestors); //Reverse the array to put the top level ancestor first

$ancestors[0] ? $top_term_id = $ancestors[0] : $top_term_id = $term_id; //Check if there is an ancestor, else use id of current term
//$term = get_term( $top_term_id, 'product_cat' ); //Get the term
//echo $term->name; // Echo name of top level ancestor
 */
                ?>
					   <h1 class="title"><?php if($term->name) { echo $term->name; } else { echo $term->name; } ?></h1>
                    <?php } else { ?>
					   <h1 class="title"><?php woocommerce_page_title(); ?></h1>
                    <?php } ?>
                    <div class="line_and_dot_sep"><div class="dot"></div></div>

				<?php endif; ?>
					<div class="subtitle"><?php echo get_field('subtitle', $pid); ?></div>

			</div>	      
				<?php  if( $real_term_id) { ?>
				<div class="col-full parent_description_container">
                       <div class="parent_description" ><?php echo $term->description; ?></div>
                    </div>
				<?php } else { ?>
                    <?php if(strlen(trim(get_post($pid)->post_content))>0) { ?>
                        <div class="col-full parent_description_container">
                           <div class="parent_description" ><?php echo get_post($pid)->post_content; ?></div>
                        </div>
                    <?php } ?>
				<?php } ?>
		</div>	
    </header>
	
	<div class="filter_block" data-term_id="<?php //echo $term_object->term_id ?>" >
	
		<div class="block_title" >Filters</div>
		<div class="col-full">
		
			<?php 
			
                $woof_shortcode = get_field('woof_filter_shortcode', $term);
                if($woof_shortcode) {
                    echo do_shortcode("[woof sid='kni_shop_filter' tax_only='".$woof_shortcode."' by_only=by_price ajax_redraw=0 is_ajax=1 taxonomies=product_cat:".$term->term_id." ]");
                } else {
    				echo do_shortcode("[woof sid='kni_shop_filter' tax_only='pa_style,pa_brand,product_cat,pa_price,pa_metal,pa_jewelry_color,pa_era' by_only=by_price ajax_redraw=0 is_ajax=1  ]");
                }
				
				
			?>
		
		</div>
	</div>
	
	<div class="categories_block" >
	
		
	

		
		
		
		<div class="col-full products_row">
<!--			--><?php //echo '[woof_products orderby='.get_field('shop_products_order_by', 'option').' order='.get_field('shop_products_order', 'option').' is_ajax=1]';
$term_data = '';
        if( $_REQUEST['really_curr_tax'] ){
$term_data = ' taxonomies=product_cat:'.str_replace('-product_cat', '', $_REQUEST['really_curr_tax']);
        }
            ?>
			<?php  echo do_shortcode('[woof_products'.$term_data.' orderby='.get_field('shop_products_order_by','option').' order='.get_field('shop_products_order','option').' is_ajax=1 ] '); ?>
				
		</div>		

		
	</div>

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
