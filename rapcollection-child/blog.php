<?php
/**
 * The blog template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: http://codex.wordpress.org/The_Loop
 *
 * @package storefront
 */

do_action( 'storefront_loop_before' );

$read_more_btn_text = get_field('read_more_button_text', get_option( 'page_for_posts' ));

?>
<div>
    <h1 class="page-title"><?php if(is_category()) { echo single_term_title(); } else { echo get_field('subtitle', get_option( 'page_for_posts' )); } ?></h1>
    <div class="line_and_dot_sep under-page-title"><div class="dot"></div></div>
    <p class="page-description"><?php echo strip_tags(get_field('description', get_option( 'page_for_posts' ))); ?></p>
</div>

<div id="blog-post-list" style="max-width:1170px; display: block;">
<?php
while ( have_posts() ) : the_post();
$image_url = wp_get_attachment_url( get_post_thumbnail_id() );
	?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div id="block_<?php the_ID(); ?>" class="two_columns_image_title_subtitle_button " >

				<div class="kni_row" >
					<div class="kni_col">
<!--    					<div class="kni_col" style="background-image: url(<?php echo $image_url ?>)" ></div>-->
                            <div class="product_image">
                                <a href="<?php the_permalink(); ?>" ><?php
                                if (has_post_thumbnail()) {
                                    echo the_post_thumbnail(array(350, 350));
                                } else {
                                    echo '<img src="' . wc_placeholder_img_src() . '" alt="Placeholder" width="350" height="350" />';
                                }
                                    ?></a>
                            </div>

						<div class="content" >
                            <h1><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h1>
							<div class="subtitle" ><?php the_excerpt(); ?></div>
							<a class="kni_button" href="<?php the_permalink(); ?>" ><?php echo $read_more_btn_text ?></a>
                            <div class="line_and_dot_sep"><div class="dot"></div></div>

						</div>
					</div>

				</div>

		</div>
 	</div>
	<?php

endwhile; ?>

</div>

<?php
/**
 * Functions hooked in to storefront_paging_nav action
 *
 * @hooked storefront_paging_nav - 10
 */
do_action( 'storefront_loop_after' );

