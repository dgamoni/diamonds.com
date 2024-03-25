<?php
/**
 * Template Name: Landing Page 1
 */

get_header('lp1'); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<div class="kni_row" >
			
			
				<div class="kni_col" style="background-image: url(<?php the_field('image'); ?>)" >
				
				<!--
					<div class="title_box" >
						<h1><?php the_field('left_col_title'); ?></h1>
					</div>
				-->	
					<div class="description"   >
						<div class="kni_row" >
							<div class="kni_col" ><?php the_field('description_left_column'); ?></div>
							<div class="kni_col" ><?php the_field('description_right_column'); ?></div>
						</div>
					</div>
					
				</div>				
				
				<div class="kni_col" >
					<div class="title_container" >
						<h1><?php the_field('right_col_title'); ?></h1>
						<div class="line_and_dot_sep"><div class="dot"></div></div>
					</div>
					
					<div class="description" >
						<?php
							
							$links = get_field('links');

							if( count($links) ){
								foreach( $links as $k => $link){
									?>
										<a href="<?php echo $link['url']; ?>" ><?php echo $link['name']; ?></a>
									<?php
								}
							}
							
						?>
					</div>
				</div>
				
				
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer('lp1');
