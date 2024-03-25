<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area blog">
		<main id="main" class="site-main" role="main">
		  <div id="content">
		<?php 
			
			$pageID = get_option( 'page_for_posts' );
		
		?>
		
<!--
		<header class="woocommerce-products-header" style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id($pageID) ); ?>)" >
			<div class="col-full">
				<div class="woocommerce-category-description">

						<h1 class="title"><?php echo get_the_title($pageID); ?></h1>
						<div class="subtitle"><?php echo get_field('subtitle', $pageID); ?></div>

				</div>
			</div>
		</header>

		<div class="filter_block" >

		<div class="block_title" >Filters</div>

			<div class="col-full">

				<?php

					$categories = get_terms('category', 'hide_empty=0' );
					$post_tag = get_terms('post_tag', 'hide_empty=0');

				?>

				<?php if( count($categories) ){ ?>

					<div class="kni_dropdown" >
						<div class="title" >Categories</div>
						<div class="item_box" >
							<?php foreach( $categories as $k => $cat ){ ?>

								<a class="kni_filter_item" href="<?php echo home_url().'/category/'.$cat->slug ?>/" ><?php echo $cat->name ?></a>

							<?php } ?>
						</div>
					</div>

				<?php } ?>

				<?php if( count($post_tag) ){ ?>

					<div class="kni_dropdown" >
						<div class="title" >Tags</div>
						<div class="item_box" >
							<?php foreach( $post_tag as $k => $tag ){ ?>

								<a class="kni_filter_item" href="<?php echo home_url().'/tag/'.$tag->slug ?>/" ><?php echo $tag->name ?></a>

							<?php } ?>
						</div>
					</div>

				<?php } ?>

				<a class="kni_filter_item title" href="<?php echo home_url() ?>/recent-post/" >Recent Posts</a>
				<a class="kni_filter_item title" href="<?php echo home_url() ?>/archives/" >Archives</a>
				<div class="search_box">
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
						<label>
							<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
							<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
						</label>
						<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
					</form>
				</div>
			</div>
		</div>
-->
		<?php if ( have_posts() ) :
			
			get_template_part( 'blog' );

		else :

			get_template_part( 'content', 'none' );

		endif; ?>
            </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
