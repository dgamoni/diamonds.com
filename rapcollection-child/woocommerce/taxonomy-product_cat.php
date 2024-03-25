<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//Category data
$term_object = get_queried_object();


$childs = get_term_children( $term_object->term_id , $term_object->taxonomy );

if( $term_object->parent ){
	$parent = get_term($term_object->parent);
}

	
if( $term_object->slug == 'diamonds' || ($term_object->parent && $parent->slug == 'diamonds' ) ){
	wc_get_template( 'diamonds-product.php' );
}else if( $term_object->slug == 'bridal' || ($term_object->parent && $parent->slug == 'bridal' ) ){
    if( $term_object->slug == 'diamonds-bridal' || ($term_object->parent && $parent->slug == 'diamonds-bridal' ) ){
        wc_get_template( 'bridal-diamond-product.php' );
    } else if( $term_object->slug == 'settings-bridal' || ($term_object->parent && $parent->slug == 'settings-bridal' ) ){
        wc_get_template( 'bridal-settings-product.php' );
    } else {
        wc_get_template( 'engagement-rings-product.php' );
    }
}else if( !isset($childs->errors) && count($childs) && !$term_object->parent ) {
	wc_get_template( 'jewelry-product.php' );
}else if( isset($term_object->term_id) ){
	wc_get_template( 'other-cat-product.php' );
}



