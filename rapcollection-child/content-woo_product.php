<?php
/**
 * Template used to display post content.
 *
 * @package storefront
 */
 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('kni_search_result'); ?>>
<div class="all_content" >
	<header class="entry-header">
	<?php
	
		the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
		
		//woocommerce_show_product_loop_sale_flash();
		woocommerce_template_loop_product_thumbnail();
				

		
	?>
	</header><!-- .entry-header -->
	
	<div class="entry-content">
		<?php
		
			woocommerce_template_loop_rating();
			woocommerce_template_loop_price();
		
		?>
	</div>
	
	<a href="<?php echo get_the_permalink(); ?>" class="product_button" >VIEW PRODUCT</a>
	
	
</div>	
</article><!-- #post-## -->
