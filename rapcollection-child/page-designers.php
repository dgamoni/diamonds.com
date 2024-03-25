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
 Template Name: Featured Designers
 */
$poid = get_field('designer');
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<header class="woocommerce-products-header" style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id($id) ); ?>)" >
                <div class="col-full">
                    <div class="woocommerce-category-description">

                            <h1 class="title ">Featured Designers</h1>
                            <div class="line_and_dot_sep"><div class="dot"></div></div>
                            <?php $posts = get_posts('post_type=designers'); ?>
                            <div class="subtitle"><?php echo $posts[0]->post_title; ?></div>

                    </div>
                </div>
            </header>

        <div class="col-full">
        <?php $posts = get_posts('post_type=designers&numberposts=10'); ?>
        <div class="designers-carousel" style="display:none;">
		<?php 
		
		$i = 0;
		$t_posts = 0;
		
		foreach($posts as $post) {
			if ($t_posts) continue;
			if ($post->ID == $poid) {
				$t_posts = 1;
				continue;
			}
			$i++;
		}
		
		if($t_posts && $i) {
			$t_posts = array();
			$t_posts = array_merge ($t_posts, array_slice($posts, $i));
			$t_posts = array_merge ($t_posts, array_slice($posts, 0, ($i-1)));
		} else {
			$t_posts = $posts;
		}
		
		?>
            <?php foreach($t_posts as $post) { ?>
            <div>
                <div data-id="<?php echo $post->ID; ?>" style="background-image:url(<?php echo get_the_post_thumbnail_url( $post->ID, 'medium_large' ); ?>)" class="owl-image"></div>
            </div>
            <?php } ?>
        </div>
        <?php
		
        $k=0;
        foreach($t_posts as $post) { ?>
        <div class="featured_designer featured_designer_<?php echo $post->ID; ?>" <?php if($k) { ?>style="display:none;"<?php } ?>>
            <div class="left_part">
                <img src="<?php echo get_the_post_thumbnail_url( $post->ID, 'medium_large' ); ?>" alt=""/>
            </div>
            <div class="right_part">
                <h2 class="designer_name"><?php echo $post->post_title; ?></h2>
                <span><?php the_field('top_string', $post->ID); ?></span>
<?php if( have_rows('work_image') ): ?>
 <div class="works">
    <?php while( have_rows('work_image') ): the_row(); ?>
     <a href="<?php the_sub_field('link'); ?>">
         <div class="image" style="background-image:url(<?php the_sub_field('image'); ?>);"></div>
     <div><h4><?php the_sub_field('title'); ?></h4>
     <h5><?php the_sub_field('price'); ?></h5></div>
     </a>
    <?php endwhile; ?>
</div>
<?php endif; ?>
                
                <div class="buttons">
                <a class="slider_button" href="<?php the_field('collection_link', $post->ID); ?>"><?php the_field('collection_link_text', $post->ID); ?></a>
                <a class="slider_button grey" href="<?php the_field('story_link', $post->ID); ?>"><?php the_field('story_button_text', $post->ID); ?></a>
                </div>
            </div>
        </div>
        <?php $k++; } ?>
        </div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
