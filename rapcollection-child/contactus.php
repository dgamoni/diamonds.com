<?php
/**
 * Template Name: Contact Us
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<?php while ( have_posts() ) : the_post();

				do_action( 'storefront_page_before' );

			?>
				<div id="post-<?php the_ID(); ?>" <?php post_class('kni_contact_us'); ?>>
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
