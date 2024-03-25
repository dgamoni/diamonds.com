/*
 * Login/Logout popup

jQuery(function($){



	login_logout_popup_offset_right()
	$(window).resize(function(){
		login_logout_popup_offset_right()
	})


	$('.top_block div.login a').click(function(e){
		e.preventDefault();

		if( !$(this).hasClass('active') ){
			$('.login_logout_popup').css('display', 'block')
			$(this).addClass('active')
		}else{
			$('.login_logout_popup').css('display', 'none')
			$(this).removeClass('active')
		}

	})

	$(document).mouseup(function (e) {
		var container = $(".ll_popup, .login_logout_popup");
		if (container.has(e.target).length === 0){
			$('.login_logout_popup').css('display', 'none')
			$('a.ll_popup').removeClass('active')
		}
	});

	function login_logout_popup_offset_right(){
		var mItem = $('.top_block div.login a');
		var mItemLeft = mItem.offset().left;
		var mItemWidth = mItem.width();

		var popupContainer = $('.login_logout_popup');
		var popupContainerWidth = popupContainer.outerWidth();
		var popupContainerRight = $(window).outerWidth() - (mItemLeft+mItemWidth)
		var popupContainerPosition = popupContainerRight+popupContainerWidth;

		if( ($(window).outerWidth() - popupContainerPosition) < 0 ){
			popupContainerRight = ($(window).outerWidth() - popupContainerWidth)/2
		}

		$('.login_logout_popup').css('right', popupContainerRight+'px')
	}
})
*/

/*
 * Login/Logout popup
 */
jQuery(function ($) {
    var lastScrollTop = 0;
    var trigger = 0;
    jQuery(window).scroll(function() {
        if(jQuery('.woof_stable_zone').length) {
            var st = jQuery(this).scrollTop();
            
            if (jQuery(window).scrollTop()  > jQuery('#woof_results_by_ajax').height()+jQuery('#woof_results_by_ajax').offset().top-jQuery(window).height()) {
                trigger=1;
            }
			/* subscribe popup
            if(st < lastScrollTop && trigger==1) {
                    jQuery('.mc_popup_right.woocommerce').fadeIn();
            }
			*/
            lastScrollTop = st;
        } 
		/* subscribe popup
		else {
            if (jQuery(window).scrollTop()  > jQuery(window).height()/2) {
                if(jQuery('body').hasClass('product-template-default')) {
                    jQuery('.mc_popup_right.woocommerce').fadeIn();
                } else {
                    jQuery('.mc_popup_right.blog').fadeIn();
                }
            }
        }
		*/
    });
	/* subscribe popup 
    jQuery('.mc_popup_right .close').on('click', function(e) {
        e.preventDefault();
        jQuery('.mc_popup_right').remove();
        
        var cookie_string = "mc_popup_right=1";
            var date = new Date();
            date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
            cookie_string += "; expires=" + date.toGMTString() + "; path=/";
        document.cookie = cookie_string;
    });
	*/
    //onload

    var OMW = get_cookie('OMW');

    console.log(OMW)

    if (OMW && OMW != null) {
        $('#' + OMW).addClass('active');
    }


    //form modal
    $('body.page-template-coming_soon .cs_button_modal').click(function (e) {

        $modal = $(this).attr('data-modal');


        $('#' + $modal).addClass('active')

        if ($modal == 'posts_excerpts') {
            var current_date = new Date;
            var cookie_year = current_date.getFullYear();
            var cookie_month = current_date.getMonth();
            var cookie_day = current_date.getDate() + 1;
            set_cookie('OMW', $modal, cookie_year, cookie_month, cookie_day);
        }

        $("html, body").animate({scrollTop: 0}, 10);

    })

    //close
    $('body.page-template-coming_soon .kni_close_btn, body.page-template-coming_soon .kni_modal_bg').click(function (e) {


        var contentModal;

        contentModal = $(this).parents('.post_content_modal_box');

        if (contentModal.length) {
            contentModal.fadeOut(1);

            return false;
        }

        $(this).parents('.kni_modal_container').removeClass('active')
        delete_cookie('OMW')

    })

    //Share btns
    $('.share_btn').click(function () {

        $icons_box = $(this).parent().find('.share_icons');

        if ($icons_box.hasClass('active')) {
            $icons_box.animate({'display': 'none'}, 500, function () {

            });
            $icons_box.removeClass('active')
        } else {
            $icons_box.animate({'display': 'inline-block'}, 500, function () {

            });
            $icons_box.addClass('active')
        }

    })

    //scroll top
    $('.to_the_top').click(function () {
        $("html, body").animate({scrollTop: 0}, 500);
        return false;
    });

    //read more
    $('.read_more_btn').click(function () {
        var pid = $(this).attr('data-pid');
        $("html, body").animate({scrollTop: 0}, 10);
        $('.post_content_modal_box[data-pid=' + pid + ']').fadeIn(1);
    })


//set cookie
    function set_cookie(name, value, exp_y, exp_m, exp_d, path, domain, secure) {
        var cookie_string = name + "=" + escape(value);

        if (exp_y) {
            var expires = new Date(exp_y, exp_m, exp_d);
            cookie_string += "; expires=" + expires.toGMTString();
        }

        if (path)
            cookie_string += "; path=" + escape(path);

        if (domain)
            cookie_string += "; domain=" + escape(domain);

        if (secure)
            cookie_string += "; secure";

        document.cookie = cookie_string;
    }

//delete cookie
    function delete_cookie(cookie_name) {
        var cookie_date = new Date();  // Текущая дата и время
        cookie_date.setTime(cookie_date.getTime() - 1);
        document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
    }

//get cookie
    function get_cookie(cookie_name) {
        var results = document.cookie.match('(^|;) ?' + cookie_name + '=([^;]*)(;|$)');

        if (results)
            return (unescape(results[2]));
        else
            return null;
    }

});


/**
 * Change images for tablet and mobile devices
 */
jQuery(function ($) {

    if ($(window).width()<768 && $(window).width()>400) {
        $('div[data-img-tablet]').each(function () {
            if ($(this).attr('data-img-tablet')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-tablet') + ')')
            }

        })
    } else if ($(window).width()<401) {
        $('div[data-img-mobile]').each(function () {
            if ($(this).attr('data-img-mobile')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-mobile') + ')')
            }
        })
    } else {
        $('div[data-img-desktop]').each(function () {
            if ($(this).attr('data-img-desktop')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-desktop') + ')')
            }
        })
    }
    
    $(window).resize(function() {
    if ($(window).width()<768 && $(window).width()>400) {
        $('div[data-img-tablet]').each(function () {
            if ($(this).attr('data-img-tablet')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-tablet') + ')')
            }

        })
    } else if ($(window).width()<401) {
        $('div[data-img-mobile]').each(function () {
            if ($(this).attr('data-img-mobile')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-mobile') + ')')
            }
        })
    } else {
        $('div[data-img-desktop]').each(function () {
            if ($(this).attr('data-img-desktop')) {
                $(this).css('background-image', 'url(' + $(this).attr('data-img-desktop') + ')')
            }
        })
    }
    });
})

/**
 * Single product page
 */
jQuery(function ($) {

    $('form.cart .quantity').append('<span class="up" ></span><span class="down" ></span>')

    $('form.cart .quantity .up').click(function () {
        var val = $(this).parent().find('input.qty').val();
        val++;
        $(this).parent().find('input.qty').val(val);
    })

    $('form.cart .quantity .down').click(function () {
        var val = $(this).parent().find('input.qty').val();
        val--;
        if (val < 1) {
            val = 1;
        }
        $(this).parent().find('input.qty').val(val);
    })

})

/**
 * Make the navigation sticky on desktop
 */
jQuery(function ($) {
    if (device.desktop() && $(window).width() > 989) {

        var menu = $('.header_row2');
        if (menu.length) {
            var menuHeight = menu.outerHeight(true);
            var curPosition = menu.offset().top;
        }

        var blog2menu = $('body.blog .filter_block, body.single .filter_block, body.archive .filter_block');
        if (blog2menu.length) {
            var blog2menuHeight = blog2menu.outerHeight(true);
            var blog2menuCurPos = blog2menu.offset().top;
        }


        var prodFilter = $('body.tax-product_cat .filter_block');
        if (prodFilter.length) {
            var prodFilterHeight = prodFilter.outerHeight(true);
            var prodFilterCurPos = prodFilter.offset().top;
        }


        $(window).scroll(function () {

            if (menu.length) {

                //Header menu
                if ($(this).scrollTop() > curPosition && !menu.hasClass('fixed')) {

                    menu.addClass('fixed');
                    menu.parents('header').css('padding-bottom', menuHeight + 'px')

                } else if ($(this).scrollTop() <= curPosition) {

                    menu.removeClass('fixed')
                    menu.parents('header').css('padding-bottom', 0)
                }

            }

            //Blog second menu
            if (blog2menu.length) {
                if (($(this).scrollTop() + menuHeight) > blog2menuCurPos && !blog2menu.hasClass('fixed')) {

                    //console.log('fixed')

                    blog2menu.addClass('fixed').css('top', menuHeight + 'px');
                    blog2menu.parents('main').find('.woocommerce-products-header').css('margin-bottom', blog2menuHeight + 'px')

                } else if (($(this).scrollTop() + menuHeight) <= blog2menuCurPos) {

                    blog2menu.removeClass('fixed').css('top', 0);
                    blog2menu.parents('main').find('.woocommerce-products-header').css('margin-bottom', 0)

                }
            }

            //Product filter
            if (prodFilter.length) {
                if (($(this).scrollTop() + menuHeight) > prodFilterCurPos && !prodFilter.hasClass('fixed')) {

                    //console.log('fixed')

                    prodFilter.addClass('fixed').css('top', menuHeight + 'px');
                    prodFilter.parents('main').find('.woocommerce-products-header').css('margin-bottom', blog2menuHeight + 'px')

                } else if (($(this).scrollTop() + menuHeight) <= prodFilterCurPos) {

                    prodFilter.removeClass('fixed').css('top', 0);
                    prodFilter.parents('main').find('.woocommerce-products-header').css('margin-bottom', 0)

                }
            }


        })
    }
})


/**
 * Homepage slider
 */

jQuery(window).load(function() {
    jQuery('.full_width_slider_image_title_subtitle .next-slide,.full_width_slider_image_title_subtitle .prev-slide').fadeTo(300, 1);
    jQuery('.kni_slide').css('display', 'block');
})
jQuery(function ($) {


    var $slider = $('.full_width_slider_image_title_subtitle');
    var $effDuration = 1000;
    var $timeout = 6000;
    var $curSlide = '';
    var $nextSlide = '';
    jQuery('.full_width_slider_image_title_subtitle .next-slide').on('click', function() {
        turnSlide();
    });
    jQuery('.full_width_slider_image_title_subtitle .prev-slide').on('click', function() {
        turnSlide(1);
    });
    function turnSlide(back) {
        if(back === undefined) {
            back = 0;
        }
        console.log(back)
        $curSlide = $slider.find('> .kni_slide.active');
        if(back) {
            if ($curSlide.next().length) {
                $nextSlide = $curSlide.next()
            } else {
                $nextSlide = $slider.find('> .kni_slide:first-child');
            } 
        } else {
            if ($curSlide.prev().length) {
                $nextSlide = $curSlide.prev()
            } else {
                $nextSlide = $slider.find('> .kni_slide:last-child');
            }
        }

        $nextSlide.addClass('next');

        $curSlide.fadeOut($effDuration, function () {

            $curSlide.removeClass('active');
            $nextSlide.removeClass('next').addClass('active');
            $curSlide.fadeIn();

           

        })

    }

    setTimeout(turnSlide, $timeout)


})


/**
 * Header
 */
//search
jQuery(function ($) {
    $('header .s_icon').click(function () {
        $('header .search_box').slideToggle();
    });
    jQuery('.storefront-handheld-footer-bar .search a').on('click', function(e) {
        e.preventDefault();
        jQuery('.storefront-handheld-footer-bar .site-search').toggleClass('active');
    });
})

//current page underline
jQuery(function ($) {
    jQuery('ul.menu.nav-menu li.current-menu-item a').append('<span class="line" ></span>');
    console.log('UNDERLINE!');
})


jQuery(function ($) {
    $('.request-specific-changes #input_1_4').val(window.location.href);
    console.log(window.location.href)
})

/**
 *    Filtering system
 */
jQuery(function ($) {

    $('.filter_block .block_title').click(function () {

        var $this = $(this);

        $('.filter_block .col-full').slideToggle(20, function () {

            if ($(this).is(":visible")) {
                $this.addClass('active');
            } else {
                $this.removeClass('active');
            }

        })
    })
})

function blog_resize_items() {
    if(jQuery(window).width() > 960) {
        for(n=1;n<=12;n=n+3) {
            var height = jQuery('#blog-post-list .type-post:nth-child('+n+') h1 a').height();
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1 a').height() > height) height = jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1 a').height();
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') h1 a').height() > height) height = jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') h1 a').height();
            jQuery('#blog-post-list .type-post:nth-child('+n+') h1').css('height', height+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1').css('height', height+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') h1').css('height', height+'px');
            
            var height2 = jQuery('#blog-post-list .type-post:nth-child('+n+') .subtitle p').outerHeight(true);
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle p').outerHeight(true)>height2) height2 = jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle p').outerHeight(true);
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') .subtitle p').outerHeight(true)>height2) height2 = jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') .subtitle p').outerHeight(true);
            jQuery('#blog-post-list .type-post:nth-child('+n+') .subtitle').css('height', height2+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle').css('height', height2+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+2)+') .subtitle').css('height', height2+'px');
        }
    } else if(jQuery(window).width() <= 960 && jQuery(window).width() >=768) {
        for(n=1;n<=12;n=n+2) {
            var height = jQuery('#blog-post-list .type-post:nth-child('+n+') h1 a').height();
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1 a').height() >height) height = jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1 a').height();
            jQuery('#blog-post-list .type-post:nth-child('+n+') h1').css('height', height+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') h1').css('height', height+'px');
            
            var height2 = jQuery('#blog-post-list .type-post:nth-child('+n+') .subtitle p').outerHeight(true);
            if(jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle p').outerHeight(true)>height2) height2 = jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle p').outerHeight(true);
            jQuery('#blog-post-list .type-post:nth-child('+n+') .subtitle').css('height', height2+'px');
            jQuery('#blog-post-list .type-post:nth-child('+(n+1)+') .subtitle').css('height', height2+'px');
        }
    } else {
            jQuery('#blog-post-list .type-post h1').css('height', 'auto');
            jQuery('#blog-post-list .type-post .subtitle').css('height', 'auto');
        
    } 
}
function product_resize_items() {
        var length = jQuery('#woof_results_by_ajax .type-product').length;
    if(jQuery(window).width() > 767) {
        for(n=1;n<=length;n=n+3) {
            var height = jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') h2').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').outerHeight(true) > height) height = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') h2').outerHeight(true) > height) height = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') h2').outerHeight(true);
            jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') h2').css('height', height+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').css('height', height+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') h2').css('height', height+'px');
            var height2 = jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') .amount').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').outerHeight(true)>height2) height2 = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') .amount').outerHeight(true)>height2) height2 = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') .amount').outerHeight(true);
            jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') .amount').css('height', height2+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').css('height', height2+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+2)+') .amount').css('height', height2+'px');
        }
    } else if(jQuery(window).width() <= 767 && jQuery(window).width() >=569) {
        for(n=1;n<=length;n=n+2) {
            var height = jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') h2').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').outerHeight(true) >height) height = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').outerHeight(true);
            jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') h2').css('height', height+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') h2').css('height', height+'px');
            
            var height2 = jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') .amount').outerHeight(true);
            if(jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').outerHeight(true)>height2) height2 = jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').outerHeight(true);
            jQuery('#woof_results_by_ajax .type-product:nth-child('+n+') .amount').css('height', height2+'px');
            jQuery('#woof_results_by_ajax .type-product:nth-child('+(n+1)+') .amount').css('height', height2+'px');
        }
    } else {
            jQuery('#woof_results_by_ajax .type-product h2').css('height', 'auto');
            jQuery('#woof_results_by_ajax .type-product .price').css('height', 'auto');
        
    } 
}
jQuery(document).on('yith_infs_added_elem', function() {
    product_resize_items();
    console.log('elem')
});
jQuery(function ($) {
    
    var owl = $('.designers-carousel');
    if(owl.length!=0) {
        
    owl.children().each( function( index ) {
      jQuery(this).attr( 'data-position', index ); // NB: .attr() instead of .data()
    });
    $('.designers-carousel').show();
    owl.on('initialized.owl.carousel', function(property) {
        console.log('INITIALIZED');
        var current = property.item.index;
        var id = $(property.target).find(".owl-item").eq(current).find(".owl-image").data('id');
        $(property.target).find(".owl-item").removeClass('side');
        $(property.target).find(".owl-item").eq(current-2).addClass('side side-left');
		 $(property.target).find(".owl-item").eq(current-1).addClass('side side-lleft');
        $(property.target).find(".owl-item").eq(current+2).addClass('side side-right');
		$(property.target).find(".owl-item").eq(current+1).addClass('side side-rright');
    });
    owl.owlCarousel({
        center: true,
        items:1,
		mouseDrag: false,
        stagePadding: 100,
        loop:true,
        margin:0,
        dots:false,
        nav:true,
        navText : ['',''],
        responsive:{
            550:{
                items:1,
                stagePadding: 100,
            },
            768:{
                items:3
            },
            900:{
                items:5,
                stagePadding: 0,
            }
        }
    });
	/*
    $(document).on('click', '.owl-item>div', function() {
      owl.trigger('to.owl.carousel', $(this).data( 'position' ) );
    });
	*/
	
	var timer = false;
	$(document).on('click', '.owl-item', function() {
		if (timer == false) {
			timer = true;
			var that = $(this);
			var i = 0;
			$('.designers-carousel').find('.owl-item.active').each(function(){
				//console.log(i);
				if ($(this).find('.owl-image').data('id') == that.find('.owl-image').data('id')) {
					console.log(i);
					if (i == 0) {
						owl.trigger('prev.owl.carousel');
						owl.trigger('prev.owl.carousel');
					}
					if (i == 1) {
						owl.trigger('prev.owl.carousel');
					}
					if (i == 3) {
						owl.trigger('next.owl.carousel');
					}
					if (i == 4) {
						owl.trigger('next.owl.carousel');
						owl.trigger('next.owl.carousel');
					}
				} else {
					i++;
				}
			});
			
			setTimeout(function(){timer = false;}, 100);
		} else {
			return false;
		}
     // owl.trigger('to.owl.carousel', $(this).data( 'position' ) );
    });
	/*
	$(document).on('click', '.designers-carousel .side-right', function() {
	  owl.trigger('next.owl.carousel');
	  owl.trigger('next.owl.carousel');
    });
	
	$(document).on('click', '.designers-carousel .side-rright', function() {
	  owl.trigger('next.owl.carousel');
    });
	
	$(document).on('click', '.designers-carousel .side-lleft', function() {
      owl.trigger('prev.owl.carousel');
    });
	
	$(document).on('click', '.designers-carousel .side-left', function() {
      owl.trigger('prev.owl.carousel');
	  owl.trigger('prev.owl.carousel');
    });
	*/
    owl.on('changed.owl.carousel', function(property) {
        var current = property.item.index;
        var id = $(property.target).find(".owl-item").eq(current).find(".owl-image").data('id');
		
        $(property.target).find(".owl-item").removeClass('side');
        $(property.target).find(".owl-item").removeClass('side-left');
        $(property.target).find(".owl-item").removeClass('side-right');
		$(property.target).find(".owl-item").removeClass('side-lleft');
        $(property.target).find(".owl-item").removeClass('side-rright');
		$(property.target).find(".owl-item").eq(current-1).addClass('side side-lleft');
        $(property.target).find(".owl-item").eq(current-2).addClass('side side-left');
        $(property.target).find(".owl-item").eq(current+2).addClass('side side-right');
		$(property.target).find(".owl-item").eq(current+1).addClass('side side-rright');
		
        jQuery('.woocommerce-category-description .subtitle').fadeOut(function() {
          jQuery(this).text(jQuery('.featured_designer_'+id+' .designer_name').html()).fadeIn();
        });
		
		jQuery('.featured_designer').fadeOut(300);
		setTimeout(function() {
			jQuery('.featured_designer').hide();
			jQuery('.featured_designer_'+id).fadeIn(300);
        }, 300);
		
		var url = $(property.target).find(".owl-item").eq(current).find(".owl-image").data('url');
		history.pushState('', '', url);
    });
    owl.on('resized.owl.carousel', function(property) {
        var current = property.item.index;
        var id = $(property.target).find(".owl-item").eq(current).find(".owl-image").data('id');
        $(property.target).find(".owl-item").removeClass('side');
        $(property.target).find(".owl-item").eq(current-2).addClass('side side-left');
        $(property.target).find(".owl-item").eq(current+2).addClass('side side-right');
    });
    }
    
    blog_resize_items();
    product_resize_items();
    jQuery(window).on('resize', function() {
        blog_resize_items();
        product_resize_items();
    });
    
    if (device.mobile() || device.tablet() || jQuery(window).width() < 990) {
        jQuery('.menu-item-has-children > a').on('click', function(e) {
                e.preventDefault();
                jQuery(this).parent().find('.sub-menu').toggleClass('active');
        })
    }
    
    if (device.mobile() || device.tablet() || jQuery(window).width() < 768) {

        $('.kni_dropdown').click(function () {
            toggleEl($(this))
        })

    } else {

        $('.kni_dropdown').hover(function () {
            toggleEl($(this))
        })

    }

    function toggleEl($this) {
        $this.find('.item_box').slideToggle();
    }


})

/**
 * Other
 */

//Product page quantity


// Popup Scripts
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
jQuery(function ($) {
    jQuery('.header_row1 .s_icon').on('click', function(e) {
        e.preventDefault();
        jQuery('.header_row1 .site-search').toggleClass('active');
    });
    // Out of office
    if(jQuery('.out_of_office').length!=0 && getCookie('out_of_office')!=1) {
        jQuery('.sf_popup_overlay').fadeIn();
        jQuery('.out_of_office').fadeIn();
        
    }
    // Popup Close
    jQuery('.sf_popup_overlay, .sf_popup .sf_close').click(function(e) {
        e.preventDefault();
        if(jQuery('.buy-popup-now:visible').length>0) {
            window.location.href = "/checkout/";
        }
        jQuery('.sf_popup').fadeOut();
        jQuery('.sf_popup_overlay').fadeOut();
    })
    jQuery('.out_of_office .sf_popup_overlay, .out_of_office .sf_popup .sf_close').click(function(e) {
        var date = new Date();
        date.setTime(+ date + (180 * 60000)); //60 * 60 * 1000
        window.document.cookie = "out_of_office=1; expires=" + date.toGMTString() + "; path=/";
    })
    
    jQuery('.become_vip_button').click(function(e) {
        e.preventDefault();
        jQuery('.sf_popup_overlay').fadeIn();
        jQuery('.vip_member_form').fadeIn();
    })
    
    jQuery('#concierge_form').click(function(e) {
        e.preventDefault();
        jQuery('.sf_popup_overlay').fadeIn();
        jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
        jQuery('.concierge_support').fadeIn();
    });
    
    jQuery('.class_exp_modal').on('click', function(e) {
        e.preventDefault();
        jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
        if(jQuery(this).hasClass('wl-add-but')) {
            jQuery('.sf_popup_overlay').fadeIn();
            jQuery('.buy-popup-add').fadeIn();
        } else {
            jQuery('.sf_popup_overlay').fadeIn();
            jQuery('.buy-popup-now').fadeIn();
        }
        jQuery('.add_to_cart_button').trigger('click');
    });
    jQuery('.open_cart_box').on('click', function() {
        if(jQuery('.added-to-cart-info').length) {
            jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
            jQuery('.added-to-cart-info').fadeIn('fast');
            setTimeout(function() {
               jQuery('.added-to-cart-info').fadeOut('fast');
            }, 8000);
        }
    });
    
    
    jQuery('.single_add_to_cart_button').on('click', function(e) {
        if(jQuery('select[name=ring_size_select]').length) {
            if(jQuery('select[name=ring_size_select]').val()=='NA') { 
                jQuery('select[name=ring_size_select]').css('border-color', '#ff1515');
                return false;
            } else {
                jQuery('select[name=ring_size_select]').css('border-color', 'rgb(169, 169, 169)');
            }
        }
    });
    
    jQuery('.variations_form .single_add_to_cart_button').on('click', function(e) {
        
        if(jQuery('select[name=ring_size_select]').length) {
            if(jQuery('select[name=ring_size_select]').val()=='NA') { 
                jQuery('select[name=ring_size_select]').css('border-color', '#ff1515');
                return false;
            } else {
                jQuery('select[name=ring_size_select]').css('border-color', 'rgb(169, 169, 169)');
            }
        }
        
        console.log(jQuery('.variations_form .woocommerce-Price-amount').html().replace(/\D/g,''));
        if(jQuery('.variations_form .woocommerce-Price-amount').html().replace(/\D/g,'')>10000) {
            e.preventDefault();
            jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
            jQuery('.sf_popup_overlay').fadeIn();
            jQuery('.buy-popup-now').fadeIn();   
            jQuery('.add_to_cart_button').trigger('click');         
        }
    });
    
    jQuery('.size_guide_show').on('click', function(e) {
        e.preventDefault();
        jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
        jQuery('.sf_popup_overlay').fadeIn();
        jQuery('.ring-size-chart').fadeIn();   
    });
    jQuery(document).on('click', '.product .attribute-select', function() {
        jQuery(this).parent().find('.attribute-select').removeClass('active');
        jQuery(this).addClass('active');
        
        jQuery(this).parents('.product').find('img.attachment-woocommerce_thumbnail').attr('src', jQuery(this).data('attr_src'));
        jQuery(this).parents('.product').find('img.attachment-woocommerce_thumbnail').attr('srcset', jQuery(this).data('attr_srcset'));
        jQuery(this).parents('.product').find('img.attachment-woocommerce_thumbnail').attr('sizes', jQuery(this).data('attr_sizes'));
        
        jQuery(this).parents('.product').find('.price').html(jQuery(this).find('div').html());
        jQuery(this).parents('.product').find('.product-metal').html(jQuery(this).data('attr_name'));
    });
    jQuery('.ring_builder li').on('click', function() {
        window.location.href = jQuery(this).data('link');
    });
    jQuery('.storefront-sticky-add-to-cart__content-button').on('click', function(e) {
        e.preventDefault();
        jQuery('form.cart').find('.add_to_cart_button').trigger('click');
    });
    /*
    jQuery('.variations_form .add_to_cart_button').on('click', function(e) {
        if(jQuery('.variations_form .woocommerce-Price-amount').html().replace(/\D/g,'')>10000) {
            e.preventDefault();
            jQuery('.sf_popup').css('top', $(document).scrollTop()+25+'px');
            jQuery('.sf_popup_overlay').fadeIn();
            jQuery('.buy-popup-add').fadeIn();            
        }
    });*/
})


/* CUSTOM JS  class_exp_modal class_exp_modal class_exp_modal
** Change Button Text
*/