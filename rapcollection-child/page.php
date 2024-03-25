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
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="woocommerce-products-header" style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id($id) ); ?>)" >
			<div class="col-full">
				<div class="woocommerce-category-description">

						<h1 class="title "><?php the_title(); ?></h1>
                        <div class="line_and_dot_sep"><div class="dot"></div></div>
						<div class="subtitle"><?php echo get_field('subtitle'); ?></div>

				</div>
			</div>
		</header>
			<?php while ( have_posts() ) : the_post();

				do_action( 'storefront_page_before' );

			?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php

						// check if the flexible content field has rows of data
						if( have_rows('flexible_content_field_name') ):

							$i = 0;

							//loop through the rows of data
							while ( have_rows('flexible_content_field_name') ) : the_row();
								$i++;
								kni_get_acf_flex_content( get_row_layout(), $i );

							endwhile;

						else :

							// no layouts found

						endif;

						?>
				</div><!-- #post-## -->
			<?php

				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' );

			endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
