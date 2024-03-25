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
	//   echo '<a href="https://rapcollection.staging.wpengine.com/?wc-api=WC_Quick_View&amp;product='.get_the_ID().'&amp;width=90%25&amp;height=90%25&amp;ajax=true" class="woocommerce-LoopProduct-link woocommerce-loop-product__link button" style="background:none">';
    //} else {
    if(get_field('setting_without_product_page')) {
	   echo '<div class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
    } else {
	   echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
    }
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
    if(get_field('setting_without_product_page')) {
        $temp='';
        $available_variations = $product->get_available_variations();
        $k=0;
        $active=0;
        foreach($available_variations as $m_var) { 
            if($m_var['attributes']['attribute_pa_metal']) {
                $product_var = new WC_Product_Variation($m_var['variation_id']); 
                $term = get_term_by( 'slug', $m_var['attributes']['attribute_pa_metal'], 'pa_metal' );
                if(get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_single' )==$m_var['image']['src'] && !$active) $temp = $term->name;
                ?>
            <span class="attribute-select <?php echo $m_var['attributes']['attribute_pa_metal']; if(get_the_post_thumbnail_url( $product->get_id(), 'woocommerce_single' )==$m_var['image']['src'] && !$active) { echo ' active'; $active=1; } ?>"
                  data-attr_src="<?php echo $m_var['image']['src']; ?>"
                  data-attr_srcset="<?php echo $m_var['image']['srcset']; ?>"
                  data-attr_sizes="<?php echo $m_var['image']['sizes']; ?>"
                  data-attr_name="<?php echo $term->name; ?>"
                  data-attr_var_id="<?php echo $m_var['variation_id']; ?>"
                  ><div style="display:none;"><?php echo $product_var->get_price_html(); ?></div></span>
        <?php $k++; } 
        }
        echo '<div class="product-metal">'.$temp.'</div>';
        //print_r($available_variations);
    }
        
        if(get_field('show_ring_size_selector', $product->get_id()) && $term_object->term_id==341) { ?>
        <div class="ring-size-field" style="margin-top:10px;">
            <select name="ring_size_select">
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
        
	woocommerce_template_loop_rating();
	woocommerce_template_loop_price();
	
    if(get_field('setting_without_product_page')) {
	   echo "</div>";
    } else {
	   echo "</a>";
    }
	?>
    <?php 
    if(get_field('setting_without_product_page')) { ?>
	<a href="#" class="product_button setting-list-add-to-bag" data-attr_flow="<?php echo $_GET['flow']; ?>" data-attr_id="<?php echo $product->get_ID(); ?>">SELECT SETTING</a>
    <?php } else { if ('VIEW JEWELRY' !== $cat_btn_text) { ?>
    
	<a href="<?php echo get_the_permalink(); ?>" class="product_button" ><?php echo $cat_btn_text; ?></a>
    <?php } } ?>
	
</li>
