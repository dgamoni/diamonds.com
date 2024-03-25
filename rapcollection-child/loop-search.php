<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: http://codex.wordpress.org/The_Loop
 *
 * @package storefront
 */

do_action( 'storefront_loop_before' );
?>
<div class="search-shop-items">
    <header class="page-header">
        <h1 class="page-title">Search Results</h1>
        <div class="line_and_dot_sep"><div class="dot"></div></div>
    </header><!-- .page-header -->
    <?php storefront_paging_nav(); ?>
    <div class="woocommerce columns-3 woocommerce-page woof_shortcode_output">
        <div class="columns-3">
            <ul class="products">
    <?php
    $shop_item_count = 0;
    //if(!$paged) $paged=1;
    //$the_query = new WP_Query( 'tag='.$_GET['s'].'&post_type=product&numberposts=9&paged=1' );
    while ( have_posts() ) : the_post();
        if( get_post_type() == 'product' ){        
            $terms = get_the_terms ( get_the_ID(), 'product_cat' );
            if( isset($terms[0]->term_id) ){
                $cat_btn_text = get_field('button_text', $terms[0]);
            }else{
                $cat_btn_text = 'VIEW';
            }
            $shop_item_count++;
                ?>
    <li <?php post_class(); ?>>
        <?php
        echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';

        //woocommerce_show_product_loop_sale_flash();
        woocommerce_template_loop_product_thumbnail();

        echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';

        woocommerce_template_loop_rating();
        woocommerce_template_loop_price();

        echo "</a>";
        ?>

        <a href="<?php echo get_the_permalink(); ?>" class="product_button" ><?php echo $cat_btn_text; ?></a>

    </li>
    <?php
        }
    endwhile; ?>
            </ul>
        </div>
    </div>
</div>
<div class="search-blog-posts">
<h1 class="page-title">Blog Posts</h1>
<div class="line_and_dot_sep"><div class="dot"></div></div>
<div id="blog-post-list" style="max-width:1170px; display: block;"><?php

rewind_posts();

$read_more_btn_text = get_field('read_more_button_text', get_option( 'page_for_posts' ));
$blog_item_count = 0;
while ( have_posts() ) : the_post();
	if( get_post_type() == 'post' ){
        $blog_item_count++;
        $image_url = wp_get_attachment_url( get_post_thumbnail_id() );
	?><div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="block_<?php the_ID(); ?>" class="two_columns_image_title_subtitle_button " >

				<div class="kni_row" >
					<div class="kni_col">
<!--    					<div class="kni_col" style="background-image: url(<?php echo $image_url ?>)" ></div>-->
                            <div class="product_image">
                                <?php
                                if (has_post_thumbnail()) {
                                    echo the_post_thumbnail(array(270, 270));
                                } else {
                                    echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="270" height="270" />';
                                }
                                ?>
                            </div>

						<div class="content" >
							<h1><?php the_title(); ?></h1>
							<div class="subtitle" ><?php the_excerpt(); ?></div>
							<a class="kni_button" href="<?php the_permalink(); ?>" ><?php echo $read_more_btn_text ?></a>
                            <div class="line_and_dot_sep"><div class="dot"></div></div>

						</div>
					</div>

				</div>

		</div>
 	</div><?php
	}
endwhile;
?></div>
</div>
<?php
if($blog_item_count==0 || $shop_item_count==0) { ?>
<style type="text/css">
    <?php if(!$blog_item_count) { ?>.search-blog-posts { display:none; }<?php } ?>
    <?php if(!$shop_item_count) { ?>.search-shop-items { display:none; }<?php } ?>
</style>
<?php
}

/**
 * Functions hooked in to storefront_paging_nav action
 *
 * @hooked storefront_paging_nav - 10
 */
do_action( 'storefront_loop_after' );
