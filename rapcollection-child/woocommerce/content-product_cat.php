<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
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
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $woocommerce_loop;

$parent_category = get_term($category->parent);

$cat_btn_text = get_field('button_text', $parent_category);
$loop_i = $woocommerce_loop["loop"]+1;

$thumbnail_id = get_woocommerce_term_meta( $category->term_id,'thumbnail_id', true );
//$image_url = wp_get_attachment_url( $thumbnail_id );
$image_url = get_field('image', $category);



?>
<li <?php wc_product_cat_class( '', $category ); ?>>
	<ul class="category" >
		<?php 
		
			if( $loop_i%2 != 0 ){
				?>
				
					<li class="image" style="background-image: url(<?php echo $image_url ?>); " ></li>				
					<li class="info" >
						<h2 class="title"><?php echo $category->name ?></h2>
						<div class="description" ><?php echo get_field('category_excerpt', $category) ?></div>
						<a class="kni_button" href="<?php echo get_term_link( $category, 'product_cat' ) ?>"><?php echo $cat_btn_text ?></a>
					</li>
				
				<?php
			}else if( $loop_i%2 == 0 ){
				
				?>
											
					<li class="info" >
						<h2 class="title"><?php echo $category->name ?></h2>
						<div class="description" ><?php echo get_field('category_excerpt', $category) ?></div>
						<a class="kni_button" href="<?php echo get_term_link( $category, 'product_cat' ) ?>"><?php echo $cat_btn_text ?></a>
					</li>
					<li class="image" style="background-image: url(<?php echo $image_url ?>); " ></li>		
				
				<?php
			}
		
		
		?>		
	</ul>
</li>
