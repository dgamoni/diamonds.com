<?php
/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">








		<?php while ( have_posts() ) : the_post();

			do_action( 'storefront_single_post_before' );
		?>


			<div id="post-<?php the_ID(); ?>" class="single_post_content" >

                <div class="col-full">

                    <div class="title">
                        <?php echo the_title(); ?>

                    </div>

                    <?php

                    $pid = get_the_ID();
                    $title = get_the_title();
                    $image_url = wp_get_attachment_url(get_post_thumbnail_id($pid));

                    $flex_content = get_field('flexible_content_field_name', $pid);


                    if (is_array($flex_content)) {

                        $i = 0;


                        //loop through the rows of data
                        while (have_rows('flexible_content_field_name', $pid)) : the_row();
                            $i++;
                            kni_get_acf_flex_content(get_row_layout(), $i);

                        endwhile;
                    }
                    ?>
                </div>

                <div class="post_modal_footer">
                    <div class="col-full">

                        <div class="share_btn_box">

                            <div class="share_btn">Share This Post</div>

                            <div class="share_icons">

                                <?php

                                $summary   = '';
                                $url       = get_the_permalink($pid);

                                ?>



                                <a class="facebook social"
                                   href="http://www.facebook.com/sharer.php?s=100&p[url]=<?php echo urlencode($url); ?>&p[title]=<?php echo $title ?>4444&p[description]='<?php echo $summary ?>'&p[images][0]=<?php echo $image_url ?>"
                                   onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                                   target="_parent"></a>


                                <?php
                                $text = $title;
                                $url  = get_the_permalink($pid);
                                ?>
                                <a class="social twitter "
                                   href="http://twitter.com/share?text=<?php echo $text ?>&url=<?php echo urlencode($url) ?>"
                                   onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                                   target="_parent"></a>

								<a class="social pinterest"
												href="http://pinterest.com/pin/create/button/?url=<?php echo urlencode($url); ?>&media=<?php echo urlencode($image_url); ?>&description=<?php echo $summary ?>" class="pin-it-button" count-layout="horizontal"
												onclick="window.open(this.href, this.title, 'toolbar=0, status=0, width=548, height=325'); return false"
                                               target="_parent"
												>
												
											</a>
								<a class="social instagram"
												href="<?php the_field('instagram', 'options'); ?>" class="pin-it-button" count-layout="horizontal"
												target="_blank"
												>
												
											</a>
                            </div>

                        </div>

                        <a class="to_the_top" href="#"></a>

                    </div>
                </div>

			</div><!-- #post-## -->


		<?php
			do_action( 'storefront_single_post_after' );

		endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php
do_action( 'storefront_sidebar' );
get_footer();
