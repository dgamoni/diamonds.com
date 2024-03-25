(function ($) {
    $(document).on('click', '.add_to_cart_button.ajax_add_to_cart, .product_type_variable.add_to_cart_button', function (e) {
        e.preventDefault();
        if(jQuery('select[name=ring_size_select]').length) {
            if(jQuery('select[name=ring_size_select]').val()=='NA') { 
                jQuery('select[name=ring_size_select]').css('border-color', '#ff1515');
                jQuery('.ring-size-field .error').fadeIn();
                return false;
            } else {
                jQuery('select[name=ring_size_select]').css('border-color', 'rgb(169, 169, 169)');
                jQuery('.ring-size-field .error').fadeOut();
            }
        }
        var $thisbutton = $(this),
        $form = $thisbutton.closest('form.cart'),
        id = $thisbutton.val(),
        product_qty = $form.find('input[name=quantity]').val() || 1,
        product_id = $form.find('input[name=product_id]').val() || id,
        variation_id = $form.find('input[name=variation_id]').val() || 0;

        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            ring_size_select: jQuery('select[name=ring_size_select]').val(),
            quantity: product_qty,
            variation_id: variation_id,
        };
        function add_to_cart_popup_show(response) {
                    if(response.fragments['div.widget_shopping_cart_content']) {
                        if(jQuery('.added-to-cart-info').length) {
                            jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
                            jQuery('.added-to-cart-info').fadeIn('fast');
                            jQuery('.added-to-cart-info .cart').html(response.fragments['div.widget_shopping_cart_content']);
                            setTimeout(function() {
                               jQuery('.added-to-cart-info').fadeOut('fast');
                            }, 8000);
                        }
                    }
        }
        $(document.body).trigger('adding_to_cart', [$thisbutton, data]);

        $.ajax({
            type: 'post',
            url: woocommerce_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                $thisbutton.removeClass('added').addClass('loading');
            },
            complete: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
            },
            success: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
                if(jQuery('.buy-popup-now:visible').length==0) {
                    if (response.error & response.product_url) {
                    //    window.location = response.product_url;
                        return;
                    } else {
                        jQuery( document.body ).trigger( 'wc_fragment_refresh' );

                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                        /* Cookie */
                        if(jQuery('.subscription-add-to-cart').length) {
                            if(!document.cookie.match('(^|;) ?' + 'mailchimp_showed' + '=([^;]*)(;|$)')) {
                                var cookie_string = "mailchimp_showed=1";
                                var date = new Date();
                                date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
                                cookie_string += "; expires=" + date.toGMTString() + "; path=/";
                                document.cookie = cookie_string;
                                jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
                                jQuery('.subscription-add-to-cart').fadeIn('fast');
                                jQuery('.sf_popup_overlay').fadeIn('fast');
                                jQuery('.added-to-cart-info .cart').html(response.fragments['div.widget_shopping_cart_content']);
                                //setTimeout(function() { add_to_cart_popup_show(response) }, 4000);
                            } else {
                                add_to_cart_popup_show(response);
                            }
                        } else {  
                            add_to_cart_popup_show(response);
                        }


                        jQuery($thisbutton).text('Added to shopping bag');
                    }
                }
            },
        });

        return false;
    });
    
    
    
    $(document).on('click', '.setting-list-add-to-bag', function (e) {
        e.preventDefault();
        $thisbutton = jQuery(this);
        var product_id = jQuery(this).data('attr_id');
        var variation_id = jQuery(this).parents('.product').find('.attribute-select.active').data('attr_var_id');
        var ring_size_select = '';
        if(jQuery(this).parent().find('.ring-size-field').length>0) {
        var ring_size_select = jQuery(this).parent().find('select[name=ring_size_select]').val();
        }
        if(ring_size_select=='NA') { 
            jQuery(this).parent().find('select[name=ring_size_select]').css('border-color', '#ff1515');
            jQuery(this).parent().find('.error').fadeIn();
            return false;
        } else {
            jQuery(this).parent().find('select[name=ring_size_select]').css('border-color', 'rgb(169, 169, 169)');
            jQuery(this).parent().find('.error').fadeOut();
        }
        var data = {
            action: 'woocommerce_ajax_add_to_cart',
            product_id: product_id,
            product_sku: '',
            ring_size_select: ring_size_select,
            quantity: 1,
            variation_id: variation_id,
        };
        
        $(document.body).trigger('adding_to_cart');

        $.ajax({
            type: 'post',
            url: woocommerce_params.ajax_url,
            data: data,
            beforeSend: function (response) {
                jQuery(this).removeClass('added').addClass('loading');
            },
            complete: function (response) {
                jQuery(this).addClass('added').removeClass('loading');
            },
            success: function (response) {
                $thisbutton.addClass('added').removeClass('loading');
                $( document.body ).trigger( 'wc_fragment_refresh' );
                $thisbutton.text('ADDED TO BAG');
                if($thisbutton.data('attr_flow')==1) {
                window.location = '/ring-builder-diamond-select/?type=diamond&flow=1&added=1';
                } else {
                window.location = '/ring-builder-diamond-select/?type=summary';
                }
            },
        });

        return false;
    });
    
    
    jQuery('.added-to-cart-info.flows').css('top', $(document).scrollTop()+25+'px');
    jQuery('.added-to-cart-info.flows').fadeIn();
    
    
})(jQuery);