<?php
/**
 * Template Name: Coming soon
 */

get_header();

global $wp_query;

?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <div id="post-<?php the_ID(); ?>" class="content-box">

                <div class="mobile_logo_container">
                    <div class="mobile_logo"
                         style="background: url(<?php the_field('logo'); ?>) center no-repeat"></div>
                </div>


                <div class="general_bg_image"
                     style="background: url(<?php the_field('col1_background_image'); ?>) top center no-repeat"></div>

                <div class="white_mask_bg"
                     style="background: url(<?php the_field('col2_background_image_copy'); ?>) top left no-repeat"></div>

                <div class="right_block">

                    <div class="logo" style="background: url(<?php the_field('logo'); ?>) center no-repeat"></div>
                    <div class="cs_title"><?php the_field('title'); ?></div>
                    <div class="cs_summary"><?php the_field('text'); ?></div>

                    <div class="cs_button_modal_box">
                        <div class="cs_button_modal" data-modal="form_modal"><?php the_field('button_text'); ?></div>
                        <div class="cs_button_modal"
                             data-modal="posts_excerpts"><?php the_field('button_2_text'); ?></div>
                    </div>
                    <div class="cs_contact_info">
                        <?php the_field('phone'); ?>
                        <br>
                        <?php the_field('email'); ?>
                    </div>

                </div>

            </div><!-- #post-## -->


        </main><!-- #main -->
    </div><!-- #primary -->
    <div id="form_modal" class="kni_modal_container">

        <div class="kni_modal_bg"></div>

        <div class="kni_modal_content">

            <div class="form_title"><?php the_field('form_title'); ?>


                <div class="line_and_dot_sep">
                    <div class="dot"></div>
                </div>
            </div>


            <div class="cs_contact_info">
                <?php the_field('phone'); ?>
                <br>
                <?php the_field('email'); ?>
            </div>

            <div class="form_modal">
                <?php the_field('form_shortcode'); ?>
            </div>


            <div class="kni_close_btn"></div>
        </div>

    </div>


<?php
$page = 1;
if (isset($wp_query->query_vars['page'])) {
    $page = $wp_query->query_vars['page'];
}

?>

    <div id="posts_excerpts" class="kni_modal_container <?php //echo $page > 1 ? 'active' : ''; ?>">

        <div class="kni_modal_bg"></div>

        <div class="kni_modal_content">

            <div class="posts_excerpts_container">
                <?php


                $posts_per_page = 10;

                $args = array(
                    'numberposts' => -1,
                );

                $total       = count(get_posts($args));
                $pages_count = ceil($total / $posts_per_page);


                $args = array(
                    'posts_per_page' => $posts_per_page,
                    'paged'          => $page,
                );

                $rows = get_posts($args);


                if (is_array($rows)) {
                    foreach ($rows as $k => $row) {

                        $boxtype = '';
                        if (($k + 1) % 2 == 0) {
                            $boxtype = 'reverse';
                        }

                        $pid       = $row->ID;
                        $image_url = wp_get_attachment_url(get_post_thumbnail_id($pid));
                        $title     = $row->post_title;

                        $flex_content = get_field('flexible_content_field_name', $pid);

//                        $intro = '';
//                        if (is_array($flex_content)) {
//
//
//                            foreach ($flex_content as $layout) {
//                                if ($layout['acf_fc_layout'] == 'intro_paragraph') {
//                                    $intro = $layout['text'];
//                                    break;
//                                }
//                            }
//
//                        }



                        $excerpt = get_the_excerpt($pid);



                        ?>
                        <div class="post_excerpt_box <?php echo $boxtype; ?>">
                            <div class="pe_image"
                                 style="background: url(<?php echo $image_url; ?>) center no-repeat"></div>
                            <div class="pe_content">
                                <h1><?php echo $title; ?></h1>
                                <div class="excerpt">
                                    <?php echo $excerpt; ?>
                                </div>
                                <div class="read_more_btn" data-pid="<?php echo $pid; ?>">
                                    <span>Read More</span>
                                    <div class="line_and_dot_sep">
                                        <div class="dot"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="post_content_modal_box" data-pid="<?php echo $pid; ?>">
                            <div class="col-full">

                                <div class="title">
                                    <?php echo $title; ?>

                                </div>

                                <?php

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
                                            $title     = $title;
                                            $summary   = urlencode($excerpt);
                                            $url       = get_the_permalink($pid);
                                            $image_url = $image_url;
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


                            <div class="kni_close_btn"></div>
                        </div>


                        <?php

                    }
                }

                ?>


                <div class="pagination <?php echo ($pages_count < 2) ? 'hidden' : ''; ?>">
                    <?php

                    $args = array(

                        'total'     => $pages_count,
                        'current'   => $page,
                        'prev_text' => __('« Prev'),

                    );

                    echo paginate_links($args);

                    ?>
                </div>

            </div>

            <div class="kni_close_btn"></div>
        </div>

    </div>

<?php
do_action('storefront_sidebar');
get_footer();
