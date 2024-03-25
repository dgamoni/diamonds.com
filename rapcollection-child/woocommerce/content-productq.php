<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product, $category, $term_object, $wp_query;


/*
echo "<pre>";
var_dump($wp_query);
echo "</pre>";
*/
if( is_product() ){	

	$cur_cats = explode("/",$wp_query->query['product_cat']);
	$obj = get_term_by('slug', end($cur_cats), 'product_cat');
}else if( isset($term_object->term_id) ){

	$obj = $term_object;
}else if( is_array($category) && count($category) ){

	$obj = $category;
}else {

	$obj = '';
}
if( isset($obj->term_id) ){
	$cat_btn_text = get_field('button_text', $obj);
    if(strlen($cat_btn_text)==0) $cat_btn_text = 'VIEW JEWELRY';
}else{
	$cat_btn_text = 'VIEW JEWELRY';
}


// Ensure visibility

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>
<li <?php post_class(); ?> data-orderby="<?php $wp_query->query['orderby'] ?>" >
	<?php /* echo do_shortcode('[add_to_cart id="'.get_the_ID().'"]');
    <a href="https://rapcollection.staging.wpengine.com/?wc-api=WC_Quick_View&amp;product=369&amp;width=90%25&amp;height=90%25&amp;ajax=true" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="369" data-product_sku="RC131" aria-label="Add “Georgian Cluster Diamond Ring” to your cart" rel="nofollow">Add to cart</a> */ ?>
	<?php
    //if($quick_view) {
	   echo '<a href="'.get_bloginfo('url').'/?wc-api=WC_Quick_View&amp;product='.get_the_ID().'&amp;width=90%25&amp;height=90%25&amp;ajax=true" class="woocommerce-LoopProduct-link woocommerce-loop-product__link quick-view-button" style="background:none">';
    //} else {
	 //  echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
    //}
	
	//woocommerce_show_product_loop_sale_flash();
	woocommerce_template_loop_product_thumbnail();

	echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
    
    if($_GET['really_curr_tax']=='36-product_cat' || $obj->term_id==36) { 
        $carat = get_field('carat_field');
        
        $color = wc_get_product_terms( get_the_ID(), 'pa_color' );
        //print_r($color);
        $temp = array();
        if(is_array($color)) { 
            foreach($color as $item) {
                $temp[] = $item->name;
            }
            $color = implode(', ', $temp); 
        } else { $color = ''; }
        
        $clarity = wc_get_product_terms( get_the_ID(), 'pa_clarity' );
        if(is_array($clarity)) { 
            $clarity = implode(', ', $clarity); 
        } else { $clarity = ''; }
    ?>
    <table class="attribute-table">
        <tr>
            <?php if(strlen($carat)>0) { ?><th>CARAT</th><?php } ?>
            <?php if(strlen($color)>0) { ?><th>COLOR</th><?php } ?>
            <?php if(strlen($clarity)>0) { ?><th>CLARITY</th><?php } ?>
        </tr>
        <tr>
            <?php if(strlen($carat)>0) { ?><td><?php echo $carat; ?></td><?php } ?>
            <?php if(strlen($color)>0) { ?><td><?php echo $color; ?></td><?php } ?>
            <?php if(strlen($clarity)>0) { ?><td><?php echo $clarity; ?></td><?php } ?>
        </tr>
    </table>
    <?php }
	woocommerce_template_loop_rating();
	woocommerce_template_loop_price();
	
	echo "</a>";
	?>
    
<a href="<?php bloginfo('url'); ?>/?wc-api=WC_Quick_View&amp;product='.get_the_ID().'&amp;width=90%25&amp;height=90%25&amp;ajax=true" class="product_button quick-view-button" style="background:none;border:none;"><?php echo $cat_btn_text; ?></a>
	
</li>
 