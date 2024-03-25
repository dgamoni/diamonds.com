<?php


add_action( 'wp_enqueue_scripts', 'kni_wedding_bands_script', 101 );
function kni_wedding_bands_script() {
	global $post;
    if( get_field('product_template', $post->ID ) == 'wedding-bands') {
    	
    	//wp_enqueue_style( 'ion_rangeSlider_css', CORE_URL . '/css/ion.rangeSlider.css', array(), rand() );
        wp_enqueue_style( 'wedding_bands_css', CORE_URL . '/css/wedding_bands_style.css', array(), rand() );
        
        //wp_enqueue_script ('ion_rangeSlider_js', CORE_URL . '/js/ion.rangeSlider.js', array( 'jquery' ), rand(), true);
        wp_enqueue_script ('wedding_bands_template_js', CORE_URL . '/js/wedding_bands_template.js', array( 'jquery' ), rand(), true);

    }   
} 