<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php
                    foreach ( $attributes as $name => $options ) : ?>
					<?php $sanitized_name = sanitize_title( $name ); ?>
					<tr class="attribute-<?php echo esc_attr( $sanitized_name ); ?>">
						<td class="label"><label for="<?php echo esc_attr( $sanitized_name ); ?>"><?php
                            //wedding-bands template
                            if( $sanitized_name == 'pa_metal' && is_wedding_bands_template($product->id) ) { echo '1. Choose your Metal'; }
                            elseif( $sanitized_name == 'pa_width-ring' && is_wedding_bands_template($product->id) ) { echo '3. Choose your Ring Width (mm)'; }

                            elseif($sanitized_name=='pa_metal') { echo 'Choose your Setting'; }
                            elseif($sanitized_name=='settings') { echo 'Choose your Setting'; }
                            elseif($sanitized_name=='diamonds') { echo 'Choose your Diamond'; }
                            else { echo wc_attribute_label( $name ); } ?></label></td>
						<?php
						if ( isset( $_REQUEST[ 'attribute_' . $sanitized_name ] ) ) {
							$checked_value = $_REQUEST[ 'attribute_' . $sanitized_name ];
						} elseif ( isset( $selected_attributes[ $sanitized_name ] ) ) {
							$checked_value = $selected_attributes[ $sanitized_name ];
						} else {
							$checked_value = '';
						}
						?>
						<td class="value">
							<?php
							if ( ! empty( $options ) ) {
								if ( taxonomy_exists( $name ) ) {
									// Get terms if this is a taxonomy - ordered. We need the names too.
									$terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );

                                    if(!isset($attributes['Diamonds'])) $available_variations = $product->get_available_variations();
									foreach ( $terms as $term ) {
										if ( ! in_array( $term->slug, $options ) ) {
											continue;
										}
                                        $custom = $term->name;
                                        if(!isset($attributes['Diamonds'])) {
                                            foreach($available_variations as $variation) {
                                                if(array_values($variation['attributes'])[0]==$term->slug){
                                                    $custom = get_post_meta( $variation['variation_id'], '_text_field', true );
                                                    if(!$custom) $custom = $term->name;
                                                }
                                            }
                                        }
                                        //wedding-bands template
                                        if ( is_wedding_bands_template($product->id) ) {
                                            print_attribute_radio_plus( $checked_value, $term->slug, $custom, $sanitized_name, $term->term_id );
                                        } else {
                                            print_attribute_radio( $checked_value, $term->slug, $custom, $sanitized_name );
                                        }
										// print_attribute_radio( $checked_value, $term->slug, $custom, $sanitized_name );
									}
								} else {
                                    if($sanitized_name=='diamonds') { // FILTER BLOCK
                                        
                                        $attr_color = array();
                                        $attr_clarity = array();
                                        if (is_array($attributes['Diamonds']) && count($attributes['Diamonds']) > 0) {
                                            foreach($attributes['Diamonds'] as $d_item) {
                                                $temp_item = explode(' ', $d_item);
                                                if(!in_array($temp_item[0], $attr_color)){
                                                    $attr_color[]=$temp_item[0];
                                                }
                                                if(!in_array($temp_item[1], $attr_clarity)){
                                                    $attr_clarity[]=$temp_item[1];
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="diamond_filters">
                                            <table class="diamond_buttons">
                                                <tr>
                                                    <td><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/diamond.jpg" style="max-width:30%;width:30px;height:30px;" alt=""/>
                                                    0.5 ct.</td>
                                                    <td><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/diamond.jpg" style="max-width:40%;width:35px;height:35px;" alt=""/>
                                                    1 ct.</td>
                                                    <td><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/diamond.jpg" style="max-width:50%;width:41px;height:41px;" alt=""/>
                                                    2 ct.</td>
                                                    <td><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/diamond.jpg" style="max-width:70%;width:51px;height:51px;" alt=""/>
                                                    3 ct.</td>
                                                    <td><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/diamond.jpg" style="max-width:100%;width:71px;height:71px;" alt=""/>
                                                    5 ct.</td>
                                                </tr>
                                            </table>
                                            <select class="carat_filter_variable">
                                                <option value="0">All Carat</option>
                                                <option value="1">0 - 1 Carat</option>
                                                <option value="2">1 - 2 Carat</option>
                                                <option value="3">2 - 3 Carat</option>
                                                <option value="4">3 - 4 Carat</option>
                                                <option value="5">4 - 5 Carat</option>
                                                <option value="6">5+ Carat</option>
                                            </select>
                                            <select class="color_filter_variable">
                                                <option value="0">All Color</option>
                                                <?php foreach($attr_color as $temp) { ?>
                                                <option value="<?php echo $temp; ?>"><?php echo $temp; ?></option>
                                                <?php } ?>
                                            </select>
                                            <select class="clarity_filter_variable">
                                                <option value="0">All Clarity</option>
                                                <?php foreach($attr_clarity as $temp) { ?>
                                                <option value="<?php echo $temp; ?>"><?php echo $temp; ?></option>
                                                <?php } ?>
                                            </select>
                                            <select class="price_filter_variable">
                                                <option value="0">All Price</option>
                                                <option value="1">$5,000 - $10,000</option>
                                                <option value="2">$10,000 - $25,000</option>
                                                <option value="3">$25,000 - $50,000</option>
                                                <option value="4">$50,000 - $100,000</option>
                                                <option value="5">$100,000 - $250,000</option>
                                                <option value="6">$250,000+</option>
                                            </select>
                                        </div>
                                        <script type="text/javascript">
                                            jQuery(document).ready(function() {
                                                jQuery('.attribute-diamonds .value div:not(.diamond_filters)').first().find('input').attr('checked', true);
                                                jQuery('.diamond_filters select').change(function() {
                                                    var carat = jQuery('.carat_filter_variable').val();
                                                    var color = jQuery('.color_filter_variable').val();
                                                    var clarity = jQuery('.clarity_filter_variable').val();
                                                    var price = jQuery('.price_filter_variable').val();
                                                    console.log(carat+' '+color+' '+clarity+' '+price);
                                                    jQuery('.attribute-diamonds .value div:not(.diamond_filters)').each(function() {
                                                        var current = jQuery(this).find('label').text().split(' ');
                                                        var show = 1;
                                                        if(color!=0 && current[0]!=color) show=0;
                                                        if(clarity!=0 && current[1]!=clarity) show=0;
                                                        
                                                        var curr_price = Number(current[current.length-1].replace(/[^0-9.]+/g,""));
                                                        var curr_carat = Number(current[2].replace(/[^0-9.-]+/g,""));
                                                        
                                                        switch(parseInt(price)) {
                                                          case 1:
                                                                if(curr_price>10000) show=0;
                                                            break;
                                                          case 2:
                                                                if(curr_price<10000 || curr_price>25000) show=0;
                                                            break;
                                                          case 3:
                                                                if(curr_price<25000 || curr_price>50000) show=0;
                                                            break;
                                                          case 4:
                                                                if(curr_price<50000 || curr_price>100000) show=0;
                                                            break;
                                                          case 5:
                                                                if(curr_price<100000 || curr_price>250000) show=0;
                                                            break;
                                                          case 6:
                                                                if(curr_price<250000) show=0;
                                                            break;
                                                        }
                                                        
                                                        switch(parseInt(carat)) {
                                                          case 1:
                                                                if(curr_carat>1) show=0;
                                                            break;
                                                          case 2:
                                                                if(curr_carat<1 || curr_carat>2) show=0;
                                                            break;
                                                          case 3:
                                                                if(curr_carat<2 || curr_carat>3) show=0;
                                                            break;
                                                          case 4:
                                                                if(curr_carat<3 || curr_carat>4) show=0;
                                                            break;
                                                          case 5:
                                                                if(curr_carat<4 || curr_carat>5) show=0;
                                                            break;
                                                          case 6:
                                                                if(curr_carat<5) show=0;
                                                            break;
                                                        }
                                                        
                                                        if(!show) { jQuery(this).addClass('filter_hidden'); } else { jQuery(this).removeClass('filter_hidden'); }
                                                    });
                                                });
                                            });
                                        </script>
                                        <style type="text/css">
                                            .filter_hidden {
                                                width:0 !important;
                                                height:0;
                                                overflow:hidden;
                                            }
                                            .diamond_filters {
                                                margin:10px 0;
                                            }
                                            .diamond_filters select {
                                                padding:5px;
                                                margin-right:5px;
                                                border:1px solid #ececec;
                                            }
                                            .attribute-diamonds .value div:not(.diamond_filters) {
                                                width:50%;
                                                display:inline-block;
                                            }
                                            .diamond_buttons {
                                                width:100%;
                                            }
                                            .diamond_buttons tr td {
                                                display:table-cell !important;
                                                width:20%;
                                                border:none;
                                                border-bottom:1px solid #eee;
                                                position:relative;
                                                vertical-align:bottom;
                                                text-align:center;
                                                font-family:'GothamMedium';
                                                font-size:12px;
                                                color:#9a2336;
                                                padding-bottom:15px !important;
                                                
                                            }
                                            .diamond_buttons tr td img {
                                                margin:0 auto 10px auto;
                                            }
                                            .diamond_buttons tr td:before {
                                                content:"";
                                                display:block;
                                                position:absolute;
                                                height:10px;
                                                width:1px;
                                                background:#eee;
                                                bottom:0;
                                                right:-1px;
                                            }
                                            
                                            .diamond_buttons tr td:first-child:after {
                                                content:"";
                                                display:block;
                                                position:absolute;
                                                height:10px;
                                                width:1px;
                                                background:#eee;
                                                bottom:0;
                                                left:-1px;
                                            }
                                        </style>
                                        <?php
                                    }
									foreach ( $options as $option ) {
                                        $custom = $option;
                                        if(!isset($attributes['Diamonds'])) {
                                            $available_variations = $product->get_available_variations();
                                            foreach($available_variations as $variation) {
                                                if(array_values($variation['attributes'])[0]==$custom){
                                                    $custom = get_post_meta( $variation['variation_id'], '_text_field', true );
                                                    if(!$custom) $custom = $option;
                                                }
                                            }
                                        }
										print_attribute_radio( $checked_value, $option, $custom, $sanitized_name );
									}
								}
							}

							//echo end( $attribute_keys ) === $name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear Selection', 'woocommerce' ) . '</a>' ) ) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>


		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
