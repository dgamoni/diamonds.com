<?php
/**
 * Template name: Home
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

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

		</main><!-- #main -->
	</div><!-- #primary -->
<!-- stage ftp -->

<?php
get_footer();
