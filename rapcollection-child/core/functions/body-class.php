<?php

add_filter('body_class','add_custom_body_class', 99 );
function add_custom_body_class( $classes ) {
	global $post;
    if( get_field('product_template', $post->ID ) == 'wedding-bands') {
       $classes[] = 'template-wedding-bands';
    }
    return $classes;
}