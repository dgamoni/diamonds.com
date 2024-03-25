<?php

add_action( 'after_setup_theme', 'kni_wedding_bands_theme_setup' );
function kni_wedding_bands_theme_setup() {
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
} 