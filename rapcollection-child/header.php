<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta name="p:domain_verify" content="147552c4b280c4c04712e4d1a9a1fdf7"/>
<meta name="google-site-verification" content="4EMcww52aIs_ie40BCxdWU0gjtDjn7nTpdajHNU0scc" />
    <?php
    if(is_single()){

        $pid       = get_the_ID();
        $image_url = wp_get_attachment_url(get_post_thumbnail_id($pid));
        $flex_content = get_field('flexible_content_field_name', $pid);

        $intro = '';
        if (is_array($flex_content)) {


            foreach ($flex_content as $layout) {
                if ($layout['acf_fc_layout'] == 'intro_paragraph') {
                    $intro = strip_tags($layout['text']);
                    break;
                }
            }

        }
		
		?>
        <meta property="og:image"         content="<?php echo $image_url; ?>" />
        <meta property="og:description" content="<?php echo $intro; ?>">
		<meta property="og:url" content="<?php echo get_permalink($pid); ?>">
		<meta property="og:type" content="website">
		<meta property="og:title" content="<?php echo wp_get_document_title(); ?>">
		<meta property="fb:app_id" content="437775367030570">
<?php
    } else {
		if ('page-designers.php' == get_page_template_slug($pid)) {
		?>
		<meta property="og:image" content="<?php the_field('fb_image', $pid); ?>" />
        <meta property="og:description" content="<?php the_field('fb_description', $pid); ?>">
		<meta property="og:url" content="<?php echo get_permalink($pid); ?>">
		<meta property="og:type" content="website">
		<meta property="og:title" content="<?php the_field('fb_title', $pid); ?>">
		<meta property="fb:app_id" content="437775367030570">
        <?php } else { ?>
		<meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>">
		<meta property="og:image" content="https://rapcollection.staging.wpengine.com/wp-content/uploads/2019/04/RONRIZZO_V2.jpg" />
		<meta property="og:image" content="https://rapcollection.staging.wpengine.com/wp-content/uploads/2019/04/RC125-PinkDiamond-PleveCuff.jpg" />
		<meta property="og:image:url" content="https://rapcollection.staging.wpengine.com/wp-content/uploads/2019/04/RC128Diamondand-WhiteGoldCocktailRing.png" />
		
		<?php 
		}
    }
    ?>

<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php the_field('header_custom_js_code', 'option'); ?>
<?php wp_head(); ?>
<script id="Cookiebot" src="https://consent.cookiebot.com/uc.js" data-cbid="3e881738-bb40-423b-91ef-689229f48f5b" type="text/javascript" async></script>
<script type='text/javascript'>
window.__lo_site_id = 118300;
(function() {
	var wa = document.createElement('script'); wa.type = 'text/javascript'; wa.async = true;
	wa.src = 'https://d10lpsik1i8c69.cloudfront.net/w.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wa, s);
})();
</script>
</head>
<?php

$term_object = get_queried_object();
$add_class = '';
if( isset($term_object) && isset($term_object->term_id) && isset($term_object->taxonomy)   ){
	$childs = get_term_children( $term_object->term_id , $term_object->taxonomy );

	if( $term_object->parent ){
		$parent = get_term($term_object->parent);
	}

	if( $term_object->slug == 'diamonds' || ($term_object->parent && $parent->slug == 'diamonds' ) ){
		$add_class = 'diamonds-product';
	}else if( !isset($childs->errors) && count($childs) && !$term_object->parent ) {
		$add_class = 'jewelry-product';
	}else{
		$add_class = 'archive-product';
	}


}

if(is_shop()){
	$add_class = 'shop';
};

?>
<body <?php body_class($add_class); ?>>
    <?php
    /* UPDATE PRICE ATTRIBUTE
    if(isset($_GET['test'])) {
        $args = ['post_type' => 'product', 'posts_per_page' => -1];
        $loop = new WP_Query($args);

        while ($loop->have_posts()) { $loop->the_post();
            global $product;
            //if(1 || get_the_ID()==1010) {
                //echo '---PRICE---- ';
                $price = round(get_post_meta( get_the_ID(), '_regular_price', true));
                $slug = 'under-25000';
                if($price<25000) {
                    $slug = 'under-25000';
                }
                if($price>=25000 && $price<50000) {
                    $slug = '25000-to-50000';
                }
                if($price>=50000 && $price<100000) {
                    $slug = '50000-to-100000';
                }
                if($price>=100000 && $price<250000) {
                    $slug = '100000-to-250000';
                }
                if($price>=250000) {
                    $slug = '250000-and-over';
                }
                
            $attributes = $product->get_attributes();
            $term_taxonomy_ids = wp_set_object_terms(get_the_ID(), $slug, 'pa_price', false);              
                                
            $data = array(
                'pa_price' => array(
                    'name' => 'pa_price',
                    'value' => '',
                    'is_visible' => '1',
                    'is_variation' => '0',
                    'is_taxonomy' => '1'
                )
            );
            //First getting the Post Meta
            $_product_attributes = get_post_meta(get_the_ID(), '_product_attributes', TRUE);
            //Updating the Post Meta
            update_post_meta(get_the_ID(), '_product_attributes', array_merge($_product_attributes, $data));
            
            //}
                                     
        }
    } */
    ?>
    
<?php do_action( 'storefront_before_site' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
		<div class="header_row1" >
			<div class="col-full top_parent_block">

				<div class="top_block" >
					<div class="tel" ><a href="tel:<?php the_field('phone', 'option'); ?>" ><?php the_field('phone', 'option'); ?></a></div>
					<div class="sep_line"></div>
					<div class="email"><a href="mailto:<?php the_field('email', 'option'); ?>" ><?php the_field('email', 'option'); ?></a></div>
					<div class="sep_line"></div>
                    <div class="vertical_separator">|</div>
					<div class="login ll_popup" ><a href="/my-account/" class="login_link ll_popup" ><?php if(is_user_logged_in()){ echo 'My Account'; }else{ echo 'Account'; } ?></a></div>
					<div class="sep_line"></div>
                    <div class="vertical_separator">|</div>
					<div class="sep_line"></div>
					<div class="tel" ><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?></div>
					<div class="header_cart"><?php storefront_header_cart(); ?></div>
					<div class="sep_line"></div>
                    <div class="vertical_separator">|</div>
					<div class="sep_line"></div>
					<div class="search_block" >
						<div class="s_icon" >Search</div>
						<div class="search_box">
							<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
								<label>
									<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
									<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'TYPE HERE...', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
								</label>
								<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'SEARCH', 'submit button' ) ?>" />
							</form>
						</div>
					</div>
				</div>

			</div>
			<div class="col-full">

				<?php
				/**
				 * Functions hooked into storefront_header action
				 *
				 * @hooked storefront_skip_links                       - 0
				 * @hooked storefront_social_icons                     - 10
				 * @hooked storefront_site_branding                    - 20
				 * @hooked storefront_secondary_navigation             - 30
				 * @hooked storefront_product_search                   - 40
				 * @hooked storefront_primary_navigation_wrapper       - 42
				 * @hooked storefront_primary_navigation               - 50
				 * @hooked storefront_header_cart                      - 60
				 * @hooked storefront_primary_navigation_wrapper_close - 68
				 */
				do_action( 'storefront_header' ); ?>
                <a href="#" class="s_icon product-mobile"></a>
                <a href="/cart/" class="mobile-cart-icon product-mobile"></a>
                <div class="mobile-wishlist-icon product-mobile"><?php echo do_shortcode('[ti_wishlist_products_counter]'); ?></div>
                <div class="site-search">
                    <form role="search" method="get" class="search-form woocommerce-product-search" action="https://rapcollection.staging.wpengine.com/">
                        <label>
                            <span class="screen-reader-text">Search for:</span>
                            <input type="search" class="search-field" placeholder="TYPE HERE..." value="" name="s" title="Search for:">
                        </label>
                        <input type="submit" class="search-submit" value="Search">
                    </form>
                </div>
			</div>
		</div>
		<div class="header_row2" >
			<div class="col-full primary_nav">

				<?php	do_action( 'storefront_header_primary_nav' ); ?>

			</div>
		</div>
	</header><!-- #masthead -->
	<?php /* <div class="login_logout_popup">
		<?php echo do_shortcode('[woocommerce_my_account]') ?>
	</div> */ ?>


	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">

		<?php
		/**
		 * Functions hooked in to storefront_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'storefront_content_top' );
