<div id="wl-list-pop-wrap" style="display:none;"></div><!-- /wl-list-pop-wrap -->
<div class="wl-list-pop woocommerce" style="display:none;">
    <a rel="nofollow" class="wl-add-to-single button" data-listid="session" href="#"><span>+</span><span><?php _e( 'Create new wishlist', 'wc_wishlist' ); ?></span></a>
    <hr class="wl-pop-small-line"/>
    <dl>
		<?php $lists = WC_Wishlists_User::get_wishlists( 'Public' ); ?>
		<?php if ( $lists && count( $lists ) ) : ?>
            <dt><?php _e( 'Your Public Lists', 'wc_wishlist' ); ?></dt>
			<?php foreach ( $lists as $list ) : ?>
                <dd>
                    <a rel="nofollow" class="wl-add-to-single" href="#" data-listid="<?php echo $list->id; ?>"><?php $list->the_title(); ?></a>
                </dd>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php $lists = WC_Wishlists_User::get_wishlists( 'Shared' ); ?>
		<?php if ( $lists && count( $lists ) ) : ?>
            <dt><?php _e( 'Your Shared Lists', 'wc_wishlist' ); ?></dt>
			<?php foreach ( $lists as $list ) : ?>
                <dd>
                    <a rel="nofollow" class="wl-add-to-single" href="#" data-listid="<?php echo $list->id; ?>"><?php $list->the_title(); ?></a>
                </dd>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php $lists = WC_Wishlists_User::get_wishlists( 'Private' ); ?>
		<?php if ( $lists && count( $lists ) ) : ?>
            <dt><?php _e( 'Your Private Lists', 'wc_wishlist' ); ?></dt>
			<?php foreach ( $lists as $list ) : ?>
                <dd>
                    <a class="wl-add-to-single" href="#" data-listid="<?php echo $list->id; ?>"><?php $list->the_title(); ?></a>
                </dd>
			<?php endforeach; ?>
		<?php endif; ?>
    </dl>
</div>
