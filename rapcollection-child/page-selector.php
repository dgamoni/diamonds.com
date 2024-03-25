<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 Template Name: Ring selector
 */

get_header(); ?>


		<?php while ( have_posts() ) : the_post(); ?>

        <ul class="ring_builder">
            <?php if($_GET['flow']==1) { ?>
            <li class="setting" data-link="/product-category/bridal/select-a-setting/?flow=1">
                <div>
                <span class="number">1</span>
                    <div>
                        <span class="top">CHOOSE A</span>
                        <span class="bottom">SETTING</span>
                    </div>
                </div>
            </li>
            <li class="diamond<?php if($_GET['type']=='diamond' || !isset($_GET['type'])) echo ' current'; ?>" data-link="/ring-builder-diamond-select/?type=diamond&flow=1">
                <div>
                <span class="number">2</span>
                    <div>
                    <span class="top">CHOOSE A</span>
                    <span class="bottom">DIAMOND</span>
                    </div>
                </div>
            </li>
            <?php } else { ?>
            <li class="diamond<?php if($_GET['type']=='diamond' || !isset($_GET['type'])) echo ' current'; ?>" data-link="/ring-builder-diamond-select/?type=diamond">
                <div>
                <span class="number">1</span>
                    <div>
                    <span class="top">CHOOSE A</span>
                    <span class="bottom">DIAMOND</span>
                    </div>
                </div>
            </li>
            <li class="setting" data-link="/product-category/bridal/select-a-setting/">
                <div>
                <span class="number">2</span>
                    <div>
                        <span class="top">CHOOSE A</span>
                        <span class="bottom">SETTING</span>
                    </div>
                </div>
            </li>
            <?php } ?>
            <li class="summary<?php if($_GET['type']=='summary') echo ' current'; ?>" data-link="/ring-builder-diamond-select/?type=summary<?php if($_GET['flow']==1) echo '&flow=1'; ?>">
                <div>
                <span class="number">3</span>
                    <div>
                        <span class="top">REVIEW</span>
                        <span class="bottom">SHOPPING BAG</span>
                    </div>
                </div>
            </li>
        </ul>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="woocommerce-products-header" >
                <div class="col-full">
                    <div class="woocommerce-category-description">

                            <h1 class="title "><?php if($_GET['type']=='diamond' || !isset($_GET['type'])) { the_field('diamond_select_title', 27807); } else { the_field('summary_title', 27807); } ?></h1>
                            <div class="line_and_dot_sep"><div class="dot"></div></div>
                        
					       <div class="subtitle"><?php if($_GET['type']=='diamond' || !isset($_GET['type'])) { the_field('diamond_select_subtitle', 27807); } else { the_field('summary_subtitle', 27807); } ?></div>

                    </div>
                </div>
            </header>

        <div class="col-full">
            <?php if($_GET['type']=='diamond' || !isset($_GET['type'])) { echo do_shortcode('[rapnet]'); } else { echo do_shortcode('[woocommerce_cart]'); } ?>
        </div>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php endwhile; ?>
    <?php if($_GET['flow']==1 && $_GET['type']!='summary' && $_GET['added']) { ?>
	<div class="sf_popup added-to-cart-info flows">
        <div class="sf_text">
        Wonderful choice, we've added your setting to the bag, you may proceed with selecting your diamond.
        </div>
        <div class="cart"><?php woocommerce_mini_cart(); ?></div>
        <a href="#" class="sf_close button">Keep Shopping</a>
    </div>
    <?php } ?>
<?php
do_action( 'storefront_sidebar' );
get_footer();
 