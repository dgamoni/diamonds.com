/*
jQuery(function ($) {

	if( $(window).width() > 767 ){
	
		$(document).bind('click', function (e) {		
		
			console.log('e.target = ',e.target);
			console.log('e.target.hasClass = ',$(e.target).hasClass("woof_select_radio_check"));
		
			if (!$(e.target).parent().hasClass("woof_select_radio_check")) {
				//$(".woof_select_radio_check dd .woof_mutliSelect").hide(200);
				$(".woof_select_radio_check dd .woof_mutliSelect").css('display', 'none');
				$(".woof_select_radio_check_opened").removeClass('woof_select_radio_check_opened');
			}
		});
	
	}else if($(window).width() < 768 ){
		$(document).bind('hover', function (e) {		
			if (!$(e.target).hasClass("woof_select_radio_check")) {
				//$(".woof_select_radio_check dd .woof_mutliSelect").hide(200);
				$(".woof_select_radio_check dd .woof_mutliSelect").css('display', 'none');
				$(".woof_select_radio_check_opened").removeClass('woof_select_radio_check_opened');
			}
		});
	}
	
});
*/function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}
jQuery(document).ready(function() {
    jQuery('.woof_container_inner_price dd ul').append('<li style="width:100%;padding: 10px 0 0 5px !important;font-family: GothamBook !important;font-size: 14px;">Sort By</li>');
    
    jQuery('.woof_container_inner_price dd ul').append('<li><input type="radio" value="ASC" name="woof_price_ordering" id="woof_lth" class="woof_checkbox_term"><label class="woof_checkbox_label " for="woof_lth">Lowest to Highest</label></li>');
    
    jQuery('.woof_container_inner_price dd ul').append('<li><input type="radio" value="DESC" name="woof_price_ordering" id="woof_htl" class="woof_checkbox_term"><label class="woof_checkbox_label " for="woof_htl">Highest to Lowest</label></li>');
    
    jQuery('input[name=woof_price_ordering]').on('change',function() {
        console.log('woof_price_ordering changed')
        var curr_url = location.href;
        console.log(curr_url)
        history.pushState({}, "", updateURLParameter(curr_url, 'price', jQuery(this).val()));
        woof_submit_link(location.href);
    });
});

function woof_init_select_radio_check() {
	
	if( device.mobile() || device.tablet() || jQuery(window).width() < 768 ){
	
		jQuery(".woof_select_radio_check").on('click', function () {
			var _this = this;
			
			
			jQuery.each(jQuery(".woof_select_radio_check"), function (i, sel) {
				if (sel !== _this ) {
					console.log('sel !== _this')
					jQuery(this).find("dd .woof_mutliSelect").css('display', 'none');
					jQuery(this).find('.woof_select_radio_check_opened').removeClass('woof_select_radio_check_opened');
				}
			});


			//+++
			//jQuery(this).parents('.woof_select_radio_check').find("dd .woof_mutliSelect").slideToggle(200);
			var el = jQuery(this).find("dd .woof_mutliSelect");
			el.toggle(1);
		
			
			
			if (jQuery(this).find('dt').hasClass('woof_select_radio_check_opened')) {
				jQuery(this).find('dt').removeClass('woof_select_radio_check_opened');
			} else {
				jQuery(this).find('dt').addClass('woof_select_radio_check_opened');
			}
			
			
		});

	}else {
		jQuery(".woof_select_radio_check").on('hover', function () {
			
			var _this = this;
			var windowWidth = jQuery(window).width();
			var left = jQuery(this).offset().left;
			var right = windowWidth - left;
			var elRight = windowWidth - (left + jQuery(this).find('.woof_select_radio_check_opener').outerWidth(true));
			
			
			/*
			console.log('left = ',left)
			console.log('right = ',right)			
			console.log('$(window).width() = ',windowWidth)			
			console.log('elWidth = ',jQuery(this).find('.woof_select_radio_check_opener').outerWidth())
			console.log('elRight = ',elRight)
            */
            
			//console.log('POSITION = ', windowWidth/2 - jQuery(this).width()/2)
            //var showing_block = jQuery(this).parent().find('dd > div');
            //showing_block.css('visibility', 'hidden').show();
			//console.log('WIDTH = ', jQuery(this).parent().find('.woof_list').outerWidth());
            //var outer_bl_width = jQuery(this).parent().find('.woof_list').outerWidth();
            //jQuery(this).find('dt').removeClass('woof_select_radio_check_opened');
            //showing_block.css('visibility', 'visible').hide();
			
							
			
			if( left >= right ){
				jQuery(this).find('.woof_list').css('left', (windowWidth/2 - 650/2)+'px');				
			}else	if( left < right ){
				jQuery(this).find('.woof_list').css('left', (windowWidth/2 - 650/2)+'px');
			}
		
			
		
			
			jQuery.each(jQuery(".woof_select_radio_check"), function (i, sel) {
				
				if (sel !== _this ) {

					jQuery(this).find("dd .woof_mutliSelect").css('display', 'none');
					jQuery(this).find('.woof_select_radio_check_opened').removeClass('woof_select_radio_check_opened');
				}
			});


			//+++
			//jQuery(this).parents('.woof_select_radio_check').find("dd .woof_mutliSelect").slideToggle(200);
			var el = jQuery(this).find("dd .woof_mutliSelect");
			
		

			el.toggle(1, function(){
				
				var listContainer = jQuery(this).find('.woof_list').outerWidth();

				if( jQuery(this).find('.woof_list_slider').length > 0 && !jQuery(this).find('.woof_list_slider').hasClass('full_width') && (left+listContainer) >  windowWidth ){
					
					//jQuery(this).find('.woof_list_slider').addClass('full_width');
					
				}else {
					jQuery(this).find('.woof_list_slider').removeClass('full_width');
				}
			});

			
			
			if (jQuery(this).find('dt').hasClass('woof_select_radio_check_opened')) {
				jQuery(this).find('dt').removeClass('woof_select_radio_check_opened');
			} else {
				jQuery(this).find('dt').addClass('woof_select_radio_check_opened');
			}
			
			
		});
		
	}
    //+++

    if (Object.keys(woof_current_values).length > 0) {
        jQuery.each(woof_current_values, function (index, value) {

            if (!jQuery('.woof_hida_' + index).size()) {
                return;
            }

            value = value.toString().trim();
            if (value.search(',')) {
                value = value.split(',');
            }
            //+++
            var txt_results = new Array();
            var v_results = new Array();
            jQuery.each(value, function (i, v) {
                var txt = v;
                var is_in_custom = false;
                if (Object.keys(woof_lang_custom).length > 0) {
                    jQuery.each(woof_lang_custom, function (i, tt) {
                        if (i == index) {
                            is_in_custom = true;
                            txt = tt;
                        }
                    });
                }

                if (!is_in_custom) {
                    try {
                        txt = jQuery("input[data-anchor='woof_n_" + index + '_' + v + "']").val();
                    } catch (e) {
                        console.log(e);
                    }

                    if (typeof txt === 'undefined')
                    {
                        txt = v;
                    }
                }

                txt_results.push(txt);
                v_results.push(v);

            });

            if (txt_results.length) {
                jQuery('.woof_hida_' + index).addClass('woof_hida_small');
                jQuery('.woof_hida_' + index).html('<div class="woof_products_top_panel2"></div>');
                var panel = jQuery('.woof_hida_' + index).find('.woof_products_top_panel2');
                panel.show();
                panel.html('<ul></ul>');
                jQuery.each(txt_results, function (i, txt) {
                    panel.find('ul').append(
                            jQuery('<li>').append(
                            jQuery('<a>').attr('href', v_results[i]).attr('data-tax', index).append(
                            jQuery('<span>').attr('class', 'woof_remove_ppi').append(txt)
                            )));
                });

                //jQuery('.woof_hida_' + index).html(txt_results.join(','));
            } else {
                jQuery('.woof_hida_' + index).removeClass('woof_hida_small');
                jQuery('.woof_hida_' + index).html(jQuery('.woof_hida_' + index).data('title'));
            }

        });

        //woof_draw_products_top_panel();
    }

    //***

    jQuery.each(jQuery('.woof_mutliSelect'), function (i, txt) {
        if (parseInt(jQuery(this).data('height'), 10) > 0) {
            jQuery(this).find('ul.woof_list:first-child').eq(0).css('max-height', jQuery(this).data('height'));
        } 
    });


}
