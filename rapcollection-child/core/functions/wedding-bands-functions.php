<?php

//helper
function is_wedding_bands_template($id) {
	if( get_field('product_template', $id ) == 'wedding-bands') {
		return true;
	} else {
		return false;
	}
}

if ( ! function_exists( 'print_attribute_radio_plus' ) ) {
	function print_attribute_radio_plus( $checked_value, $value, $label, $name, $term_id ) {
		global $product;

		$input_name = 'attribute_' . esc_attr( $name ) ;
		$esc_value = esc_attr( $value );
		$id = esc_attr( $name . '_v_' . $value . $product->get_id() ); //added product ID at the end of the name to target single products
		$checked = checked( $checked_value, $value, false );
		$filtered_label = apply_filters( 'woocommerce_variation_option_name', $label, esc_attr( $name ) );
		printf( '<div class="%2$s"><input  type="radio" name="%1$s" value="%2$s" data-name="%5$s" data-term="%6$s" id="%3$s" %4$s><label for="%3$s">%5$s</label></div>', $input_name, $esc_value, $id, $checked, $filtered_label, $term_id );
	}
}