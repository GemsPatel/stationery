var newsletterSubscriberFormDetail = new VarienForm('newsletter-validate-detail');
var optionsPrice = new Product.OptionsPrice([]);

var productAddToCartForm = new VarienForm('product_addtocart_form');
productAddToCartForm.submit = function(button, url) {
	if (this.validator.validate()) {
		var form = this.form;
		var oldUrl = form.action;

		if (url) {
		   form.action = url;
		}
		var e = null;
		try {
			this.form.submit();
		} catch (e) {
		}
		this.form.action = oldUrl;
		if (e) {
			throw e;
		}

		if (button && button != 'undefined') {
			button.disabled = true;
		}
	}
}.bind(productAddToCartForm);

productAddToCartForm.submitLight = function(button, url){
	if(this.validator) {
		var nv = Validation.methods;
		delete Validation.methods['required-entry'];
		delete Validation.methods['validate-one-required'];
		delete Validation.methods['validate-one-required-by-name'];
		// Remove custom datetime validators
		for (var methodName in Validation.methods) {
			if (methodName.match(/^validate-datetime-.*/i)) {
				delete Validation.methods[methodName];
			}
		}

		if (this.validator.validate()) {
			if (url) {
				this.form.action = url;
			}
			this.form.submit();
		}
		Object.extend(Validation.methods, nv);
	}
}.bind(productAddToCartForm);

var dataForm = new VarienForm('review-form');
Validation.addAllThese(
[
       ['validate-rating', 'Please select one of each of the ratings above', function(v) {
            var trs = $('product-review-table').select('tr');
            var inputs;
            var error = 1;

            for( var j=0; j < trs.length; j++ ) {
                var tr = trs[j];
                if( j > 0 ) {
                    inputs = tr.select('input');

                    for( i in inputs ) {
                        if( inputs[i].checked == true ) {
                            error = 0;
                        }
                    }

                    if( error == 1 ) {
                        return false;
                    } else {
                        error = 1;
                    }
                }
            }
            return true;
        }]
]
);

jQuery(document).ready(function ($) {
	$('#tab-product-view').easyResponsiveTabs({
		type: 'default', //Types: default, vertical, accordion
		width: 'auto', //auto or any width like 600px
		fit: true,   // 100% fit in a container
		closed: 'accordion', // Start closed if in accordion view
		/* activate: function(event) { 
			console.log('actived');
		} */
	});
});

jQuery('document').ready(function($){
	$i = 0;
	$('#catrgory1  .wrapper-category').append('<div class="more-w"><span class="more-view">More</span></div>');
	$('#catrgory1  .wrapper-category ul.child-cat > li').each(function(){
		$i ++;
		if($i>7){
			$(this).css('display', 'none');
		}
		//$('div.sm_megamenu_wrapper_vertical_menu').css('height', $('ul.sm_megamenu_menu').outerHeight());
	});
	$('#catrgory1  .wrapper-category .more-w').click(function(){
		if($(this).hasClass('open')){
			$i=0;
			$('#catrgory1  .wrapper-category ul.child-cat > li').each(function(){
				$i ++;
				if($i>7){
					$(this).slideUp(200);
				}
			});
			$(this).removeClass('open');
			$('.more-w').removeClass('active-i');
			$("#catrgory1  .wrapper-category .more-w > .more-view").text('More');
		}else{
			$i=0;
			$('#catrgory1  .wrapper-category ul.child-cat > li').each(function(){
				$i ++;
				if($i>7){
					$(this).slideDown(200);
				}
			});
			$(this).addClass('open');
			$('.more-w').addClass('active-i');
			$("#catrgory1  .wrapper-category .more-w > .more-view").text('Less');
		}
	});
})

$$('.related-checkbox').each(function(elem){
	Event.observe(elem, 'click', addRelatedToProduct)
});

var relatedProductsCheckFlag = false;
function selectAllRelated(txt){
	if (relatedProductsCheckFlag == false) {
		$$('.related-checkbox').each(function(elem){
			elem.checked = true;
		});
		relatedProductsCheckFlag = true;
		txt.innerHTML="unselect all";
	} else {
		$$('.related-checkbox').each(function(elem){
			elem.checked = false;
		});
		relatedProductsCheckFlag = false;
		txt.innerHTML="select all";
	}
	addRelatedToProduct();
}

function addRelatedToProduct(){
	var checkboxes = $$('.related-checkbox');
	var values = [];
	for(var i=0;i<checkboxes.length;i++){
		if(checkboxes[i].checked) values.push(checkboxes[i].value);
	}
	if($('related-products-field')){
		$('related-products-field').value = values.join(',');
	}
}

jQuery('document').ready(function($){
	$i = 0;
	$('.megamenu-left .sambar-inner').append('<div class="more-w"><span class="more-view">More</span></div>');
	$('.megamenu-left .sm_megamenu_wrapper_vertical_menu ul.sm_megamenu_menu > li').each(function(){
		$i ++;
		if($i>7){
			$(this).css('display', 'none');
		}
		//$('div.sm_megamenu_wrapper_vertical_menu').css('height', $('ul.sm_megamenu_menu').outerHeight());
	});
	$('.megamenu-left .sambar-inner .more-w').click(function(){
		if($(this).hasClass('open')){
			$i=0;
			$('.megamenu-left .sm_megamenu_wrapper_vertical_menu ul.sm_megamenu_menu > li').each(function(){
				$i ++;
				if($i>7){
					$(this).slideUp(200);
				}
			});
			$(this).removeClass('open');
			$('.more-w').removeClass('active-i');
			$(".megamenu-left .sambar-inner .more-w > .more-view").text('More');
		}else{
			$i=0;
			$('.megamenu-left .sm_megamenu_wrapper_vertical_menu ul.sm_megamenu_menu > li').each(function(){
				$i ++;
				if($i>7){
					$(this).slideDown(200);
				}
			});
			$(this).addClass('open');
			$('.more-w').addClass('active-i');
			$(".megamenu-left .sambar-inner .more-w > .more-view").text('Less');
		}
	});
})

jQuery(document).ready(function($){
	$( ".sm_megamenu_wrapper_vertical_menu .sm_megamenu_menu > li" ).has( "div" ).addClass('parent-child');
});

jQuery(document).ready(function($) {
	var slider_post = $(".postWrapper-left");
	slider_post.owlCarousel({

		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			768:{
				items:1
			},
			992:{
				items:1
			},
			1200:{
				items:1
			}
		},
		
		autoplay:false,
		loop:false,
		nav : true, // Show next and prev buttons
		dots: false,
		autoplaySpeed : 500,
		navSpeed : 500,
		dotsSpeed : 500,
		autoplayHoverPause: true,
		margin:30,

	});	 

});	

jQuery(document).ready(function($) {
	var owl_brand = $(".brand-slider");
	owl_brand.owlCarousel({

		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			},
			768:{
				items:4
			},
			992:{
				items:5
			},
			1200:{
				items:6
			}
		},
		
		autoplay:false,
		loop:true,
		nav : true, // Show next and prev buttons
		dots: false,
		autoplaySpeed : 500,
		navSpeed : 500,
		dotsSpeed : 500,
		autoplayHoverPause: true,
                       margin:80,
		rtl: false,
	});	 

});	

jQuery(document).ready(function ($) {
	;
	(function (element) {
		var $element = $(element),
			$tab = $('.ltabs-tab', $element),
			$tab_label = $('.ltabs-tab-label', $tab),
			$tabs = $('.ltabs-tabs', $element),
			ajax_url = $tabs.parents('.ltabs-tabs-container').attr('data-ajaxurl'),
			effect = $tabs.parents('.ltabs-tabs-container').attr('data-effect'),
			delay = $tabs.parents('.ltabs-tabs-container').attr('data-delay'),
			duration = $tabs.parents('.ltabs-tabs-container').attr('data-duration'),
			rl_moduleid = $tabs.parents('.ltabs-tabs-container').attr('data-modid'),
			$items_content = $('.ltabs-items', $element),
			$items_inner = $('.ltabs-items-inner', $items_content),
			$items_first_active = $('.ltabs-items-selected', $element),
			$load_more = $('.ltabs-loadmore', $element),
			$btn_loadmore = $('.ltabs-loadmore-btn', $load_more),
			$select_box = $('.ltabs-selectbox', $element),
			$tab_label_select = $('.ltabs-tab-selected', $element);

		enableSelectBoxes();
		function enableSelectBoxes() {
			$tab_wrap = $('.ltabs-tabs-wrap', $element),
				$tab_label_select.html($('.ltabs-tab', $element).filter('.tab-sel').children('.ltabs-tab-label').html());
			if ($(window).innerWidth() <= 479) {
				$tab_wrap.addClass('ltabs-selectbox');
			} else {
				$tab_wrap.removeClass('ltabs-selectbox');
			}
		}

		$('span.ltabs-tab-selected, span.ltabs-tab-arrow', $element).click(function () {
			if ($('.ltabs-tabs', $element).hasClass('ltabs-open')) {
				$('.ltabs-tabs', $element).removeClass('ltabs-open');
			} else {
				$('.ltabs-tabs', $element).addClass('ltabs-open');
			}
		});

		$(window).resize(function () {
			if ($(window).innerWidth() <= 479) {
				$('.ltabs-tabs-wrap', $element).addClass('ltabs-selectbox');
			} else {
				$('.ltabs-tabs-wrap', $element).removeClass('ltabs-selectbox');
			}
		});

		function showAnimateItems(el) {
			var $_items = $('.new-ltabs-item', el), nub = 0;
			$('.ltabs-loadmore-btn', el).fadeOut('fast');
			$_items.each(function (i) {
				nub++;
				switch (effect) {
					case 'none' :
						$(this).css({'opacity': '1', 'filter': 'alpha(opacity = 100)'});
						break;
					default:
						animatesItems($(this), nub * delay, i, el);
				}
				if (i == $_items.length - 1) {
					$('.ltabs-loadmore-btn', el).fadeIn(delay);
				}
				$(this).removeClass('new-ltabs-item');
			});
		}

		function animatesItems($this, fdelay, i, el) {
			var $_items = $('.ltabs-item', el);
			$this.attr("style",
				"-webkit-animation:" + effect + " " + duration + "ms;"
				+ "-moz-animation:" + effect + " " + duration + "ms;"
				+ "-o-animation:" + effect + " " + duration + "ms;"
				+ "-moz-animation-delay:" + fdelay + "ms;"
				+ "-webkit-animation-delay:" + fdelay + "ms;"
				+ "-o-animation-delay:" + fdelay + "ms;"
				+ "animation-delay:" + fdelay + "ms;").delay(fdelay).animate({
					opacity: 1,
					filter: 'alpha(opacity = 100)'
				}, {
					//delay: 100
				});
			if (i == ($_items.length - 1)) {
				$(".ltabs-items-inner").addClass("play");
			}
		}

		showAnimateItems($items_first_active);
		$tab.on('click.tab', function () {
			var $this = $(this);
			if ($this.hasClass('tab-sel')) return false;
			if ($this.parents('.ltabs-tabs').hasClass('ltabs-open')) {
				$this.parents('.ltabs-tabs').removeClass('ltabs-open');
			}
			$tab.removeClass('tab-sel');
			$this.addClass('tab-sel');
			var items_active = $this.attr('data-active-content');
			var _items_active = $(items_active, $element);
			$items_content.removeClass('ltabs-items-selected');
			_items_active.addClass('ltabs-items-selected');
			$tab_label_select.html($tab.filter('.tab-sel').children('.ltabs-tab-label').html());
			var $loading = $('.ltabs-loading', _items_active);
			var loaded = _items_active.hasClass('ltabs-items-loaded');
			if (!loaded && !_items_active.hasClass('ltabs-process')) {
				_items_active.addClass('ltabs-process');
				var category_id = $this.attr('data-category-id');
				$loading.show();
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: {
						listing_tabs_moduleid: rl_moduleid,
						is_ajax_listing_tabs: 1,
						ajax_listingtags_start: 0,
						categoryid: category_id,
						config: 'eyJhY3RpdmUiOiIxIiwibGlzdGluZ3RhYnNfdGl0bGVfdGV4dCI6Ik5FVyBQUk9EVUNUUyIsInByb2R1Y3RfbGlua3NfdGFyZ2V0IjoiX3NlbGYiLCJuYmlfY29sdW1uMSI6IjMiLCJuYmlfY29sdW1uMiI6IjMiLCJuYmlfY29sdW1uMyI6IjIiLCJuYmlfY29sdW1uNCI6IjEiLCJzaG93X2xvYWRtb3JlX3NsaWRlciI6InNsaWRlciIsImZpbHRlcl90eXBlIjoiY2F0ZWdvcmllcyIsInByb2R1Y3RfY2F0ZWdvcnkiOiI2NCw2NSw2NyIsImZpbHRlcl9vcmRlcl9ieSI6ImNyZWF0ZWRfYXQsbGFzdGVzdF9wcm9kdWN0LHRvcF9yYXRpbmciLCJmaWVsZF9wcmVsb2FkIjoidG9wX3JhdGluZyIsImNhdGVnb3J5X3ByZWxvYWQiOiI2MSIsImNoaWxkX2NhdGVnb3J5X3Byb2R1Y3RzIjoiMSIsIm1heF9kZXB0aCI6IjEwIiwicHJvZHVjdF9mZWF0dXJlZCI6IjAiLCJwcm9kdWN0X29yZGVyX2J5IjoibmFtZSIsInByb2R1Y3Rfb3JkZXJfZGlyIjoiQVNDIiwicHJvZHVjdF9saW1pdGF0aW9uIjoiNiIsInRhYl9hbGxfZGlzcGxheSI6IjAiLCJjYXRfdGl0bGVfbWF4bGVuZ3RoIjoiOTAiLCJjYXRlZ29yeV9vcmRlcl9ieSI6Im5hbWUiLCJjYXRlZ29yeV9vcmRlcl9kaXIiOiJBU0MiLCJpY29uX2Rpc3BsYXkiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfaW1hZ2UiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfdGh1bWJuYWlsIjoiMCIsImltZ2NmZ2NhdF9mcm9tX2NhdGVnb3J5X2Rlc2NyaXB0aW9uIjoiMCIsImltZ2NmZ2NhdF9vcmRlciI6ImNhdGVnb3J5X2ltYWdlLCBjYXRlZ29yeV90aHVtYm5haWwsIGNhdGVnb3J5X2Rlc2NyaXB0aW9uIiwiaW1nY2ZnY2F0X2Z1bmN0aW9uIjoiMSIsImltZ2NmZ2NhdF93aWR0aCI6IjMwIiwiaW1nY2ZnY2F0X2hlaWdodCI6IjMwIiwiaW1nY2ZnY2F0X2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdjYXRfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnY2F0X2tlZXBGcmFtZSI6IiIsImltZ2NmZ2NhdF9rZWVwVHJhbnNwYXJlbmN5IjoiIiwiaW1nY2ZnY2F0X2JhY2tncm91bmQiOiJGRkZGRkYiLCJpbWdjZmdjYXRfcGxhY2Vob2xkZXIiOiJzbVwvbGlzdGluZ3RhYnNcL2ltYWdlc1wvbm9waG90by5qcGciLCJwcm9kdWN0X3RpdGxlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF90aXRsZV9tYXhsZW5ndGgiOiIyNSIsInByb2R1Y3RfaW1hZ2Vfd2lkdGgiOiIyMDAiLCJwcm9kdWN0X2ltYWdlX2hlaWdodCI6IjIwMCIsInByb2R1Y3RfZGVzY3JpcHRpb25fZGlzcGxheSI6IjAiLCJwcm9kdWN0X2Rlc2NyaXB0aW9uX21heGxlbmd0aCI6IjkwIiwicHJvZHVjdF9wcmljZV9kaXNwbGF5IjoiMSIsInByb2R1Y3RfZGF0ZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfaGl0c19kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmV2aWV3c19jb3VudCI6IjEiLCJwcm9kdWN0X2FkZGNhcnRfZGlzcGxheSI6IjEiLCJwcm9kdWN0X2FkZHdpc2hsaXN0X2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9hZGRjb21wYXJlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9yZWFkbW9yZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmVhZG1vcmVfdGV4dCI6IkRldGFpbHMiLCJpbWdjZmdfZnJvbV9wcm9kdWN0X2ltYWdlIjoiMSIsImltZ2NmZ19mcm9tX3Byb2R1Y3RfZGVzY3JpcHRpb24iOiIwIiwiaW1nY2ZnX29yZGVyIjoicHJvZHVjdF9pbWFnZSwgcHJvZHVjdF9kZXNjcmlwdGlvbiIsImltZ2NmZ19mdW5jdGlvbiI6IjEiLCJpbWdjZmdfd2lkdGgiOiIyNzAiLCJpbWdjZmdfaGVpZ2h0IjoiMjU0IiwiaW1nY2ZnX2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnX2tlZXBGcmFtZSI6IiIsImltZ2NmZ19rZWVwVHJhbnNwYXJlbmN5IjoiZmFsc2UiLCJpbWdjZmdfYmFja2dyb3VuZCI6IkZGRkZGRiIsImltZ2NmZ19wbGFjZWhvbGRlciI6InNtXC9saXN0aW5ndGFic1wvaW1hZ2VzXC9ub3Bob3RvLmpwZyIsImVmZmVjdCI6ImJvdW5jZUluIiwiZHVyYXRpb24iOiIyMDAiLCJkZWxheSI6IjIwMCIsImNlbnRlciI6IiIsIm5hdiI6IjAiLCJsb29wIjoiMCIsIm1hcmdpbiI6IjAiLCJzbGlkZUJ5IjoiMSIsImF1dG9wbGF5IjoiIiwiYXV0b3BsYXlIb3ZlclBhdXNlIjoiIiwiYXV0b3BsYXlTcGVlZCI6IjEwMDAiLCJuYXZTcGVlZCI6IjEwMDAiLCJzbWFydFNwZWVkIjoiMTAwMCIsInN0YXJ0UG9zaXRpb24iOiIxIiwibW91c2VEcmFnIjoiIiwidG91Y2hEcmFnIjoiIiwicHVsbERyYWciOiIiLCJpbmNsdWRlX2pxdWVyeSI6IjAiLCJwcmV0ZXh0IjoiIiwicG9zdHRleHQiOiIiLCJyb3dfY291bnQiOiIyIn0'
					},
					success: function (data) {
						if (data.items_markup != '') {
							$('.ltabs-items-inner', _items_active).html(data.items_markup);
							_items_active.addClass('ltabs-items-loaded').removeClass('ltabs-process');
							$loading.remove();
							showAnimateItems(_items_active);
							updateStatus(_items_active);

																CreateProSlider($('.ltabs-items-inner', _items_active));
								SliderImages($('.slider-img-thumb', _items_active));
							
						}
					},
					dataType: 'json'
				});

			} else {

				
									var owl = $('.ltabs-items-inner', _items_active);
				owl = owl.data('owlCarousel');
				if (typeof owl === 'undefined') {
				} else {
					owl.onResize();
				}
								}
		});

		function updateStatus($el) {
			$('.ltabs-loadmore-btn', $el).removeClass('loading');
			var countitem = $('.ltabs-item', $el).length;
			$('.ltabs-image-loading', $el).css({display: 'none'});
			$('.ltabs-loadmore-btn', $el).parent().attr('data-rl_start', countitem);
			var rl_total = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_total');
			var rl_load = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_load');
			var rl_allready = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_allready');

			if (countitem >= rl_total) {
				$('.ltabs-loadmore-btn', $el).addClass('loaded');
				$('.ltabs-image-loading', $el).css({display: 'none'});
				$('.ltabs-loadmore-btn', $el).attr('data-label', rl_allready);
				$('.ltabs-loadmore-btn', $el).removeClass('loading');
			}
		}

		$btn_loadmore.on('click.loadmore', function () {
			var $this = $(this);
			if ($this.hasClass('loaded') || $this.hasClass('loading')) {
				return false;
			} else {
				$this.addClass('loading');
				$('.ltabs-image-loading', $this).css({display: 'inline-block'});
				var rl_start = $this.parent().attr('data-rl_start'),
					rl_moduleid = $this.parent().attr('data-modid'),
					rl_ajaxurl = $this.parent().attr('data-ajaxurl'),
					effect = $this.parent().attr('data-effect'),
					category_id = $this.parent().attr('data-categoryid'),
					items_active = $this.parent().attr('data-active-content');
				var _items_active = $(items_active, $element);
				$.ajax({
					type: 'POST',
					url: rl_ajaxurl,
					data: {
						listing_tabs_moduleid: rl_moduleid,
						is_ajax_listing_tabs: 1,
						ajax_listingtags_start: rl_start,
						categoryid: category_id,
						config: 'eyJhY3RpdmUiOiIxIiwibGlzdGluZ3RhYnNfdGl0bGVfdGV4dCI6Ik5FVyBQUk9EVUNUUyIsInByb2R1Y3RfbGlua3NfdGFyZ2V0IjoiX3NlbGYiLCJuYmlfY29sdW1uMSI6IjMiLCJuYmlfY29sdW1uMiI6IjMiLCJuYmlfY29sdW1uMyI6IjIiLCJuYmlfY29sdW1uNCI6IjEiLCJzaG93X2xvYWRtb3JlX3NsaWRlciI6InNsaWRlciIsImZpbHRlcl90eXBlIjoiY2F0ZWdvcmllcyIsInByb2R1Y3RfY2F0ZWdvcnkiOiI2NCw2NSw2NyIsImZpbHRlcl9vcmRlcl9ieSI6ImNyZWF0ZWRfYXQsbGFzdGVzdF9wcm9kdWN0LHRvcF9yYXRpbmciLCJmaWVsZF9wcmVsb2FkIjoidG9wX3JhdGluZyIsImNhdGVnb3J5X3ByZWxvYWQiOiI2MSIsImNoaWxkX2NhdGVnb3J5X3Byb2R1Y3RzIjoiMSIsIm1heF9kZXB0aCI6IjEwIiwicHJvZHVjdF9mZWF0dXJlZCI6IjAiLCJwcm9kdWN0X29yZGVyX2J5IjoibmFtZSIsInByb2R1Y3Rfb3JkZXJfZGlyIjoiQVNDIiwicHJvZHVjdF9saW1pdGF0aW9uIjoiNiIsInRhYl9hbGxfZGlzcGxheSI6IjAiLCJjYXRfdGl0bGVfbWF4bGVuZ3RoIjoiOTAiLCJjYXRlZ29yeV9vcmRlcl9ieSI6Im5hbWUiLCJjYXRlZ29yeV9vcmRlcl9kaXIiOiJBU0MiLCJpY29uX2Rpc3BsYXkiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfaW1hZ2UiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfdGh1bWJuYWlsIjoiMCIsImltZ2NmZ2NhdF9mcm9tX2NhdGVnb3J5X2Rlc2NyaXB0aW9uIjoiMCIsImltZ2NmZ2NhdF9vcmRlciI6ImNhdGVnb3J5X2ltYWdlLCBjYXRlZ29yeV90aHVtYm5haWwsIGNhdGVnb3J5X2Rlc2NyaXB0aW9uIiwiaW1nY2ZnY2F0X2Z1bmN0aW9uIjoiMSIsImltZ2NmZ2NhdF93aWR0aCI6IjMwIiwiaW1nY2ZnY2F0X2hlaWdodCI6IjMwIiwiaW1nY2ZnY2F0X2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdjYXRfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnY2F0X2tlZXBGcmFtZSI6IiIsImltZ2NmZ2NhdF9rZWVwVHJhbnNwYXJlbmN5IjoiIiwiaW1nY2ZnY2F0X2JhY2tncm91bmQiOiJGRkZGRkYiLCJpbWdjZmdjYXRfcGxhY2Vob2xkZXIiOiJzbVwvbGlzdGluZ3RhYnNcL2ltYWdlc1wvbm9waG90by5qcGciLCJwcm9kdWN0X3RpdGxlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF90aXRsZV9tYXhsZW5ndGgiOiIyNSIsInByb2R1Y3RfaW1hZ2Vfd2lkdGgiOiIyMDAiLCJwcm9kdWN0X2ltYWdlX2hlaWdodCI6IjIwMCIsInByb2R1Y3RfZGVzY3JpcHRpb25fZGlzcGxheSI6IjAiLCJwcm9kdWN0X2Rlc2NyaXB0aW9uX21heGxlbmd0aCI6IjkwIiwicHJvZHVjdF9wcmljZV9kaXNwbGF5IjoiMSIsInByb2R1Y3RfZGF0ZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfaGl0c19kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmV2aWV3c19jb3VudCI6IjEiLCJwcm9kdWN0X2FkZGNhcnRfZGlzcGxheSI6IjEiLCJwcm9kdWN0X2FkZHdpc2hsaXN0X2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9hZGRjb21wYXJlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9yZWFkbW9yZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmVhZG1vcmVfdGV4dCI6IkRldGFpbHMiLCJpbWdjZmdfZnJvbV9wcm9kdWN0X2ltYWdlIjoiMSIsImltZ2NmZ19mcm9tX3Byb2R1Y3RfZGVzY3JpcHRpb24iOiIwIiwiaW1nY2ZnX29yZGVyIjoicHJvZHVjdF9pbWFnZSwgcHJvZHVjdF9kZXNjcmlwdGlvbiIsImltZ2NmZ19mdW5jdGlvbiI6IjEiLCJpbWdjZmdfd2lkdGgiOiIyNzAiLCJpbWdjZmdfaGVpZ2h0IjoiMjU0IiwiaW1nY2ZnX2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnX2tlZXBGcmFtZSI6IiIsImltZ2NmZ19rZWVwVHJhbnNwYXJlbmN5IjoiZmFsc2UiLCJpbWdjZmdfYmFja2dyb3VuZCI6IkZGRkZGRiIsImltZ2NmZ19wbGFjZWhvbGRlciI6InNtXC9saXN0aW5ndGFic1wvaW1hZ2VzXC9ub3Bob3RvLmpwZyIsImVmZmVjdCI6ImJvdW5jZUluIiwiZHVyYXRpb24iOiIyMDAiLCJkZWxheSI6IjIwMCIsImNlbnRlciI6IiIsIm5hdiI6IjAiLCJsb29wIjoiMCIsIm1hcmdpbiI6IjAiLCJzbGlkZUJ5IjoiMSIsImF1dG9wbGF5IjoiIiwiYXV0b3BsYXlIb3ZlclBhdXNlIjoiIiwiYXV0b3BsYXlTcGVlZCI6IjEwMDAiLCJuYXZTcGVlZCI6IjEwMDAiLCJzbWFydFNwZWVkIjoiMTAwMCIsInN0YXJ0UG9zaXRpb24iOiIxIiwibW91c2VEcmFnIjoiIiwidG91Y2hEcmFnIjoiIiwicHVsbERyYWciOiIiLCJpbmNsdWRlX2pxdWVyeSI6IjAiLCJwcmV0ZXh0IjoiIiwicG9zdHRleHQiOiIiLCJyb3dfY291bnQiOiIyIn0'
					},
					success: function (data) {
						if (data.items_markup != '') {
							$(data.items_markup).insertAfter($('.ltabs-item', _items_active).nextAll().last());
							$('.ltabs-image-loading', $this).css({display: 'none'});
							showAnimateItems(_items_active);
							updateStatus(_items_active);
						}
					}, dataType: 'json'
				});
			}
			return false;
		});

					if ($('.ltabs-items-inner', $element).parent().hasClass('ltabs-items-selected')) {
			var items_active = $('.ltabs-tab.tab-sel', $element).attr('data-active-content');
			var _items_active = $(items_active, $element);
			CreateProSlider($('.ltabs-items-inner', _items_active));
			SliderImages($('.slider-img-thumb', _items_active));
		}

		function SliderImages($items_inner_thumbs){
			$items_inner_thumbs.lightSlider({
				loop: false,
				vertical:false,
				slideMargin: 0,
				item: 1,

										controls : true, // Show next and prev buttons
																										
										pager: false,
								}); 
		}			
		
		function CreateProSlider($items_inner) {
			$items_inner.owlCarousel({
				center: false,
				nav: false,
				loop: false,
				margin: 0,
				slideBy: 1,
				autoplay: false,
				autoplayHoverPause: false,
				autoplaySpeed: 1000,
				navSpeed: 1000,
				smartSpeed: 1000,
				startPosition: 1,
				mouseDrag:false,
				touchDrag:false,
				pullDrag:false,
				dots: false,
				autoWidth: false,
				navClass: ['owl-prev', 'owl-next'],
				navText: ['&#139;', '&#155;'],
				responsive: {
					0: {
						items:1						},
					480: {
						items:2						},
					768: {
						items:3						},
					1200: {
						items:3						}
				}
			});
		}

		
	})('#sm_listing_tabs_17609256171501112828');
});

jQuery(document).ready(function ($) {
	;
	(function (element) {
		var $element = $(element),
			$tab = $('.ltabs-tab', $element),
			$tab_label = $('.ltabs-tab-label', $tab),
			$tabs = $('.ltabs-tabs', $element),
			ajax_url = $tabs.parents('.ltabs-tabs-container').attr('data-ajaxurl'),
			effect = $tabs.parents('.ltabs-tabs-container').attr('data-effect'),
			delay = $tabs.parents('.ltabs-tabs-container').attr('data-delay'),
			duration = $tabs.parents('.ltabs-tabs-container').attr('data-duration'),
			rl_moduleid = $tabs.parents('.ltabs-tabs-container').attr('data-modid'),
			$items_content = $('.ltabs-items', $element),
			$items_inner = $('.ltabs-items-inner', $items_content),
			$items_first_active = $('.ltabs-items-selected', $element),
			$load_more = $('.ltabs-loadmore', $element),
			$btn_loadmore = $('.ltabs-loadmore-btn', $load_more),
			$select_box = $('.ltabs-selectbox', $element),
			$tab_label_select = $('.ltabs-tab-selected', $element);

		enableSelectBoxes();
		function enableSelectBoxes() {
			$tab_wrap = $('.ltabs-tabs-wrap', $element),
				$tab_label_select.html($('.ltabs-tab', $element).filter('.tab-sel').children('.ltabs-tab-label').html());
			if ($(window).innerWidth() <= 479) {
				$tab_wrap.addClass('ltabs-selectbox');
			} else {
				$tab_wrap.removeClass('ltabs-selectbox');
			}
		}

		$('span.ltabs-tab-selected, span.ltabs-tab-arrow', $element).click(function () {
			if ($('.ltabs-tabs', $element).hasClass('ltabs-open')) {
				$('.ltabs-tabs', $element).removeClass('ltabs-open');
			} else {
				$('.ltabs-tabs', $element).addClass('ltabs-open');
			}
		});

		$(window).resize(function () {
			if ($(window).innerWidth() <= 479) {
				$('.ltabs-tabs-wrap', $element).addClass('ltabs-selectbox');
			} else {
				$('.ltabs-tabs-wrap', $element).removeClass('ltabs-selectbox');
			}
		});

		function showAnimateItems(el) {
			var $_items = $('.new-ltabs-item', el), nub = 0;
			$('.ltabs-loadmore-btn', el).fadeOut('fast');
			$_items.each(function (i) {
				nub++;
				switch (effect) {
					case 'none' :
						$(this).css({'opacity': '1', 'filter': 'alpha(opacity = 100)'});
						break;
					default:
						animatesItems($(this), nub * delay, i, el);
				}
				if (i == $_items.length - 1) {
					$('.ltabs-loadmore-btn', el).fadeIn(delay);
				}
				$(this).removeClass('new-ltabs-item');
			});
		}

		function animatesItems($this, fdelay, i, el) {
			var $_items = $('.ltabs-item', el);
			$this.attr("style",
				"-webkit-animation:" + effect + " " + duration + "ms;"
				+ "-moz-animation:" + effect + " " + duration + "ms;"
				+ "-o-animation:" + effect + " " + duration + "ms;"
				+ "-moz-animation-delay:" + fdelay + "ms;"
				+ "-webkit-animation-delay:" + fdelay + "ms;"
				+ "-o-animation-delay:" + fdelay + "ms;"
				+ "animation-delay:" + fdelay + "ms;").delay(fdelay).animate({
					opacity: 1,
					filter: 'alpha(opacity = 100)'
				}, {
					//delay: 100
				});
			if (i == ($_items.length - 1)) {
				$(".ltabs-items-inner").addClass("play");
			}
		}

		showAnimateItems($items_first_active);
		$tab.on('click.tab', function () {
			var $this = $(this);
			if ($this.hasClass('tab-sel')) return false;
			if ($this.parents('.ltabs-tabs').hasClass('ltabs-open')) {
				$this.parents('.ltabs-tabs').removeClass('ltabs-open');
			}
			$tab.removeClass('tab-sel');
			$this.addClass('tab-sel');
			var items_active = $this.attr('data-active-content');
			var _items_active = $(items_active, $element);
			$items_content.removeClass('ltabs-items-selected');
			_items_active.addClass('ltabs-items-selected');
			$tab_label_select.html($tab.filter('.tab-sel').children('.ltabs-tab-label').html());
			var $loading = $('.ltabs-loading', _items_active);
			var loaded = _items_active.hasClass('ltabs-items-loaded');
			if (!loaded && !_items_active.hasClass('ltabs-process')) {
				_items_active.addClass('ltabs-process');
				var category_id = $this.attr('data-category-id');
				$loading.show();
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: {
						listing_tabs_moduleid: rl_moduleid,
						is_ajax_listing_tabs: 1,
						ajax_listingtags_start: 0,
						categoryid: category_id,
						config: 'eyJhY3RpdmUiOiIxIiwibGlzdGluZ3RhYnNfdGl0bGVfdGV4dCI6IiIsInByb2R1Y3RfbGlua3NfdGFyZ2V0IjoiX3NlbGYiLCJuYmlfY29sdW1uMSI6IjMiLCJuYmlfY29sdW1uMiI6IjMiLCJuYmlfY29sdW1uMyI6IjIiLCJuYmlfY29sdW1uNCI6IjEiLCJzaG93X2xvYWRtb3JlX3NsaWRlciI6InNsaWRlciIsImZpbHRlcl90eXBlIjoiZmllbGRwcm9kdWN0cyIsInByb2R1Y3RfY2F0ZWdvcnkiOiI2MSw2Miw2MywxMDQsMTA1LDEwNiwxMjYsMTI3LDY5LDQsMTEsMTIsMTMsNSw3MSIsImZpbHRlcl9vcmRlcl9ieSI6ImNyZWF0ZWRfYXQsbGFzdGVzdF9wcm9kdWN0LHRvcF9yYXRpbmciLCJmaWVsZF9wcmVsb2FkIjoidG9wX3JhdGluZyIsImNhdGVnb3J5X3ByZWxvYWQiOiI2MSIsImNoaWxkX2NhdGVnb3J5X3Byb2R1Y3RzIjoiMSIsIm1heF9kZXB0aCI6IjEwIiwicHJvZHVjdF9mZWF0dXJlZCI6IjAiLCJwcm9kdWN0X29yZGVyX2J5IjoibmFtZSIsInByb2R1Y3Rfb3JkZXJfZGlyIjoiQVNDIiwicHJvZHVjdF9saW1pdGF0aW9uIjoiNiIsInRhYl9hbGxfZGlzcGxheSI6IjAiLCJjYXRfdGl0bGVfbWF4bGVuZ3RoIjoiOTAiLCJjYXRlZ29yeV9vcmRlcl9ieSI6Im5hbWUiLCJjYXRlZ29yeV9vcmRlcl9kaXIiOiJBU0MiLCJpY29uX2Rpc3BsYXkiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfaW1hZ2UiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfdGh1bWJuYWlsIjoiMCIsImltZ2NmZ2NhdF9mcm9tX2NhdGVnb3J5X2Rlc2NyaXB0aW9uIjoiMCIsImltZ2NmZ2NhdF9vcmRlciI6ImNhdGVnb3J5X2ltYWdlLCBjYXRlZ29yeV90aHVtYm5haWwsIGNhdGVnb3J5X2Rlc2NyaXB0aW9uIiwiaW1nY2ZnY2F0X2Z1bmN0aW9uIjoiMSIsImltZ2NmZ2NhdF93aWR0aCI6IjMwIiwiaW1nY2ZnY2F0X2hlaWdodCI6IjMwIiwiaW1nY2ZnY2F0X2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdjYXRfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnY2F0X2tlZXBGcmFtZSI6IiIsImltZ2NmZ2NhdF9rZWVwVHJhbnNwYXJlbmN5IjoiIiwiaW1nY2ZnY2F0X2JhY2tncm91bmQiOiJGRkZGRkYiLCJpbWdjZmdjYXRfcGxhY2Vob2xkZXIiOiJzbVwvbGlzdGluZ3RhYnNcL2ltYWdlc1wvbm9waG90by5qcGciLCJwcm9kdWN0X3RpdGxlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF90aXRsZV9tYXhsZW5ndGgiOiIyNSIsInByb2R1Y3RfaW1hZ2Vfd2lkdGgiOiIyMDAiLCJwcm9kdWN0X2ltYWdlX2hlaWdodCI6IjIwMCIsInByb2R1Y3RfZGVzY3JpcHRpb25fZGlzcGxheSI6IjAiLCJwcm9kdWN0X2Rlc2NyaXB0aW9uX21heGxlbmd0aCI6IjkwIiwicHJvZHVjdF9wcmljZV9kaXNwbGF5IjoiMSIsInByb2R1Y3RfZGF0ZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfaGl0c19kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmV2aWV3c19jb3VudCI6IjEiLCJwcm9kdWN0X2FkZGNhcnRfZGlzcGxheSI6IjEiLCJwcm9kdWN0X2FkZHdpc2hsaXN0X2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9hZGRjb21wYXJlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9yZWFkbW9yZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmVhZG1vcmVfdGV4dCI6IkRldGFpbHMiLCJpbWdjZmdfZnJvbV9wcm9kdWN0X2ltYWdlIjoiMSIsImltZ2NmZ19mcm9tX3Byb2R1Y3RfZGVzY3JpcHRpb24iOiIwIiwiaW1nY2ZnX29yZGVyIjoicHJvZHVjdF9pbWFnZSwgcHJvZHVjdF9kZXNjcmlwdGlvbiIsImltZ2NmZ19mdW5jdGlvbiI6IjEiLCJpbWdjZmdfd2lkdGgiOiIyNzAiLCJpbWdjZmdfaGVpZ2h0IjoiMjU0IiwiaW1nY2ZnX2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnX2tlZXBGcmFtZSI6IiIsImltZ2NmZ19rZWVwVHJhbnNwYXJlbmN5IjoiZmFsc2UiLCJpbWdjZmdfYmFja2dyb3VuZCI6IkZGRkZGRiIsImltZ2NmZ19wbGFjZWhvbGRlciI6InNtXC9saXN0aW5ndGFic1wvaW1hZ2VzXC9ub3Bob3RvLmpwZyIsImVmZmVjdCI6ImJvdW5jZUluIiwiZHVyYXRpb24iOiIyMDAiLCJkZWxheSI6IjIwMCIsImNlbnRlciI6IiIsIm5hdiI6IjAiLCJsb29wIjoiMCIsIm1hcmdpbiI6IjAiLCJzbGlkZUJ5IjoiMSIsImF1dG9wbGF5IjoiIiwiYXV0b3BsYXlIb3ZlclBhdXNlIjoiIiwiYXV0b3BsYXlTcGVlZCI6IjEwMDAiLCJuYXZTcGVlZCI6IjEwMDAiLCJzbWFydFNwZWVkIjoiMTAwMCIsInN0YXJ0UG9zaXRpb24iOiIxIiwibW91c2VEcmFnIjoiIiwidG91Y2hEcmFnIjoiIiwicHVsbERyYWciOiIiLCJpbmNsdWRlX2pxdWVyeSI6IjAiLCJwcmV0ZXh0IjoiIiwicG9zdHRleHQiOiIiLCJyb3dfY291bnQiOiIyIn0'
					},
					success: function (data) {
						if (data.items_markup != '') {
							$('.ltabs-items-inner', _items_active).html(data.items_markup);
							_items_active.addClass('ltabs-items-loaded').removeClass('ltabs-process');
							$loading.remove();
							showAnimateItems(_items_active);
							updateStatus(_items_active);

																CreateProSlider($('.ltabs-items-inner', _items_active));
								SliderImages($('.slider-img-thumb', _items_active));
							
						}
					},
					dataType: 'json'
				});

			} else {

				
									var owl = $('.ltabs-items-inner', _items_active);
				owl = owl.data('owlCarousel');
				if (typeof owl === 'undefined') {
				} else {
					owl.onResize();
				}
								}
		});

		function updateStatus($el) {
			$('.ltabs-loadmore-btn', $el).removeClass('loading');
			var countitem = $('.ltabs-item', $el).length;
			$('.ltabs-image-loading', $el).css({display: 'none'});
			$('.ltabs-loadmore-btn', $el).parent().attr('data-rl_start', countitem);
			var rl_total = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_total');
			var rl_load = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_load');
			var rl_allready = $('.ltabs-loadmore-btn', $el).parent().attr('data-rl_allready');

			if (countitem >= rl_total) {
				$('.ltabs-loadmore-btn', $el).addClass('loaded');
				$('.ltabs-image-loading', $el).css({display: 'none'});
				$('.ltabs-loadmore-btn', $el).attr('data-label', rl_allready);
				$('.ltabs-loadmore-btn', $el).removeClass('loading');
			}
		}

		$btn_loadmore.on('click.loadmore', function () {
			var $this = $(this);
			if ($this.hasClass('loaded') || $this.hasClass('loading')) {
				return false;
			} else {
				$this.addClass('loading');
				$('.ltabs-image-loading', $this).css({display: 'inline-block'});
				var rl_start = $this.parent().attr('data-rl_start'),
					rl_moduleid = $this.parent().attr('data-modid'),
					rl_ajaxurl = $this.parent().attr('data-ajaxurl'),
					effect = $this.parent().attr('data-effect'),
					category_id = $this.parent().attr('data-categoryid'),
					items_active = $this.parent().attr('data-active-content');
				var _items_active = $(items_active, $element);
				$.ajax({
					type: 'POST',
					url: rl_ajaxurl,
					data: {
						listing_tabs_moduleid: rl_moduleid,
						is_ajax_listing_tabs: 1,
						ajax_listingtags_start: rl_start,
						categoryid: category_id,
						config: 'eyJhY3RpdmUiOiIxIiwibGlzdGluZ3RhYnNfdGl0bGVfdGV4dCI6IiIsInByb2R1Y3RfbGlua3NfdGFyZ2V0IjoiX3NlbGYiLCJuYmlfY29sdW1uMSI6IjMiLCJuYmlfY29sdW1uMiI6IjMiLCJuYmlfY29sdW1uMyI6IjIiLCJuYmlfY29sdW1uNCI6IjEiLCJzaG93X2xvYWRtb3JlX3NsaWRlciI6InNsaWRlciIsImZpbHRlcl90eXBlIjoiZmllbGRwcm9kdWN0cyIsInByb2R1Y3RfY2F0ZWdvcnkiOiI2MSw2Miw2MywxMDQsMTA1LDEwNiwxMjYsMTI3LDY5LDQsMTEsMTIsMTMsNSw3MSIsImZpbHRlcl9vcmRlcl9ieSI6ImNyZWF0ZWRfYXQsbGFzdGVzdF9wcm9kdWN0LHRvcF9yYXRpbmciLCJmaWVsZF9wcmVsb2FkIjoidG9wX3JhdGluZyIsImNhdGVnb3J5X3ByZWxvYWQiOiI2MSIsImNoaWxkX2NhdGVnb3J5X3Byb2R1Y3RzIjoiMSIsIm1heF9kZXB0aCI6IjEwIiwicHJvZHVjdF9mZWF0dXJlZCI6IjAiLCJwcm9kdWN0X29yZGVyX2J5IjoibmFtZSIsInByb2R1Y3Rfb3JkZXJfZGlyIjoiQVNDIiwicHJvZHVjdF9saW1pdGF0aW9uIjoiNiIsInRhYl9hbGxfZGlzcGxheSI6IjAiLCJjYXRfdGl0bGVfbWF4bGVuZ3RoIjoiOTAiLCJjYXRlZ29yeV9vcmRlcl9ieSI6Im5hbWUiLCJjYXRlZ29yeV9vcmRlcl9kaXIiOiJBU0MiLCJpY29uX2Rpc3BsYXkiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfaW1hZ2UiOiIwIiwiaW1nY2ZnY2F0X2Zyb21fY2F0ZWdvcnlfdGh1bWJuYWlsIjoiMCIsImltZ2NmZ2NhdF9mcm9tX2NhdGVnb3J5X2Rlc2NyaXB0aW9uIjoiMCIsImltZ2NmZ2NhdF9vcmRlciI6ImNhdGVnb3J5X2ltYWdlLCBjYXRlZ29yeV90aHVtYm5haWwsIGNhdGVnb3J5X2Rlc2NyaXB0aW9uIiwiaW1nY2ZnY2F0X2Z1bmN0aW9uIjoiMSIsImltZ2NmZ2NhdF93aWR0aCI6IjMwIiwiaW1nY2ZnY2F0X2hlaWdodCI6IjMwIiwiaW1nY2ZnY2F0X2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdjYXRfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnY2F0X2tlZXBGcmFtZSI6IiIsImltZ2NmZ2NhdF9rZWVwVHJhbnNwYXJlbmN5IjoiIiwiaW1nY2ZnY2F0X2JhY2tncm91bmQiOiJGRkZGRkYiLCJpbWdjZmdjYXRfcGxhY2Vob2xkZXIiOiJzbVwvbGlzdGluZ3RhYnNcL2ltYWdlc1wvbm9waG90by5qcGciLCJwcm9kdWN0X3RpdGxlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF90aXRsZV9tYXhsZW5ndGgiOiIyNSIsInByb2R1Y3RfaW1hZ2Vfd2lkdGgiOiIyMDAiLCJwcm9kdWN0X2ltYWdlX2hlaWdodCI6IjIwMCIsInByb2R1Y3RfZGVzY3JpcHRpb25fZGlzcGxheSI6IjAiLCJwcm9kdWN0X2Rlc2NyaXB0aW9uX21heGxlbmd0aCI6IjkwIiwicHJvZHVjdF9wcmljZV9kaXNwbGF5IjoiMSIsInByb2R1Y3RfZGF0ZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfaGl0c19kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmV2aWV3c19jb3VudCI6IjEiLCJwcm9kdWN0X2FkZGNhcnRfZGlzcGxheSI6IjEiLCJwcm9kdWN0X2FkZHdpc2hsaXN0X2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9hZGRjb21wYXJlX2Rpc3BsYXkiOiIxIiwicHJvZHVjdF9yZWFkbW9yZV9kaXNwbGF5IjoiMCIsInByb2R1Y3RfcmVhZG1vcmVfdGV4dCI6IkRldGFpbHMiLCJpbWdjZmdfZnJvbV9wcm9kdWN0X2ltYWdlIjoiMSIsImltZ2NmZ19mcm9tX3Byb2R1Y3RfZGVzY3JpcHRpb24iOiIwIiwiaW1nY2ZnX29yZGVyIjoicHJvZHVjdF9pbWFnZSwgcHJvZHVjdF9kZXNjcmlwdGlvbiIsImltZ2NmZ19mdW5jdGlvbiI6IjEiLCJpbWdjZmdfd2lkdGgiOiIyNzAiLCJpbWdjZmdfaGVpZ2h0IjoiMjU0IiwiaW1nY2ZnX2NvbnN0cmFpbk9ubHkiOiIiLCJpbWdjZmdfa2VlcEFzcGVjdFJhdGlvIjoiIiwiaW1nY2ZnX2tlZXBGcmFtZSI6IiIsImltZ2NmZ19rZWVwVHJhbnNwYXJlbmN5IjoiZmFsc2UiLCJpbWdjZmdfYmFja2dyb3VuZCI6IkZGRkZGRiIsImltZ2NmZ19wbGFjZWhvbGRlciI6InNtXC9saXN0aW5ndGFic1wvaW1hZ2VzXC9ub3Bob3RvLmpwZyIsImVmZmVjdCI6ImJvdW5jZUluIiwiZHVyYXRpb24iOiIyMDAiLCJkZWxheSI6IjIwMCIsImNlbnRlciI6IiIsIm5hdiI6IjAiLCJsb29wIjoiMCIsIm1hcmdpbiI6IjAiLCJzbGlkZUJ5IjoiMSIsImF1dG9wbGF5IjoiIiwiYXV0b3BsYXlIb3ZlclBhdXNlIjoiIiwiYXV0b3BsYXlTcGVlZCI6IjEwMDAiLCJuYXZTcGVlZCI6IjEwMDAiLCJzbWFydFNwZWVkIjoiMTAwMCIsInN0YXJ0UG9zaXRpb24iOiIxIiwibW91c2VEcmFnIjoiIiwidG91Y2hEcmFnIjoiIiwicHVsbERyYWciOiIiLCJpbmNsdWRlX2pxdWVyeSI6IjAiLCJwcmV0ZXh0IjoiIiwicG9zdHRleHQiOiIiLCJyb3dfY291bnQiOiIyIn0'
					},
					success: function (data) {
						if (data.items_markup != '') {
							$(data.items_markup).insertAfter($('.ltabs-item', _items_active).nextAll().last());
							$('.ltabs-image-loading', $this).css({display: 'none'});
							showAnimateItems(_items_active);
							updateStatus(_items_active);
						}
					}, dataType: 'json'
				});
			}
			return false;
		});

					if ($('.ltabs-items-inner', $element).parent().hasClass('ltabs-items-selected')) {
			var items_active = $('.ltabs-tab.tab-sel', $element).attr('data-active-content');
			var _items_active = $(items_active, $element);
			CreateProSlider($('.ltabs-items-inner', _items_active));
			SliderImages($('.slider-img-thumb', _items_active));
		}

		function SliderImages($items_inner_thumbs){
			$items_inner_thumbs.lightSlider({
				loop: false,
				vertical:false,
				slideMargin: 0,
				item: 1,

										controls : true, // Show next and prev buttons
																										
										pager: false,
								}); 
		}			
		
		function CreateProSlider($items_inner) {
			$items_inner.owlCarousel({
				center: false,
				nav: false,
				loop: false,
				margin: 0,
				slideBy: 1,
				autoplay: false,
				autoplayHoverPause: false,
				autoplaySpeed: 1000,
				navSpeed: 1000,
				smartSpeed: 1000,
				startPosition: 1,
				mouseDrag:false,
				touchDrag:false,
				pullDrag:false,
				dots: false,
				autoWidth: false,
				navClass: ['owl-prev', 'owl-next'],
				navText: ['&#139;', '&#155;'],
				responsive: {
					0: {
						items:1						},
					480: {
						items:2						},
					768: {
						items:3						},
					1200: {
						items:3						}
				}
			});
		}

		
	})('#sm_listing_tabs_6740590521501112828');
});

jQuery(document).ready(function($) {
	var owl_testimonial = $(".testimonials-slider");
	owl_testimonial.owlCarousel({
		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			768:{
				items:1
			},
			992:{
				items:1
			},
			1200:{
				items:1
			}
		},
		
		autoplay:false,
		loop:false,
		nav : true, // Show next and prev buttons
		dots: false,
		autoplaySpeed : 500,
		navSpeed : 500,
		dotsSpeed : 500,
		autoplayHoverPause: true,
		margin:30,
	});
});

jQuery(function()
{
    jQuery('#enfinity_4').each(function()
    {
        var e = jQuery('#1_enfinity_4', this),
        t = parseFloat(e.attr('data-time')),
        a = parseFloat(e.attr('data-transperiod')),
        i = 'true' == e.attr('data-prevnext') ? jQuery('.filmore_prev', this) : '',
        r = 'true' == e.attr('data-prevnext') ? jQuery('.filmore_next', this) : '',
        o = 'true' == e.attr('data-playpause') ? jQuery('.filmore_pause', this) : '',
        s = 'true' == e.attr('data-playpause') ? jQuery('.filmore_play', this) : '',
        n = 'true' == e.attr('data-pagination') ? jQuery('.filmore_pagination', this) : '',
        u = jQuery('.filmore_loader', this),
        l = 'true' == e.attr('data-autoadvance') ? !0 : !1;
        e.filmore(
            {
                time: t,
                transPeriod: a,
                prev: i,
                next: r,
                pause: o,
                play: s,
                pagination: n,
                loader: u,
                autoadv: l,
                slide_id: '#enfinity_4'
            })
    })
});

jQuery(document).ready(function ($) {

	/*======================ZOOM====================*/
				function zoom() {
		$(".product-image-gallery .visible").elevateZoom({
			easing: true,
			loadingIcon: true,
			zoomType: "lens",
			cursor: "crosshair"
		});
	}

	zoom();

	$(".more-views .thumb-link").click(function () {
		setTimeout(function () {
			zoom();
		}, 100);
	});
	
	/*==================JS FOR IMAGE BOX======================*/
	var slider_thumbs_main = $(".product-image-thumbs").lightSlider({
		//prevHtml: '',
		//nextHtml: '',
		
		//slideMove: 1,
		easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
		speed: 600,
		auto: false,
		loop: false,
		rtl: false,
	
							vertical: false,
			item: 3,
			slideMargin: 15,
				
						
		
		pager: false,
		responsive: [
		
		 // RESPONSIVE CHO horizontal
			{
				breakpoint: 480,
				settings: {
					item: 2,
					slideMargin: 5,
					slideMove: 1
				}
			}
						
		]
	});
	
	$(".configurable-swatch-list .has-image").click(function () {
		setTimeout(function () {
			zoom();
		}, 100);
	});
	
	$(window).resize(function () {
							zoom();
			$('.zoomContainer').css({'left':'-9999px'});
					});

	$('.fancybox-buttons').fancybox({
		title: null,
		padding: 5,
		nextEffect: 'none', // 'elastic', 'fade' or 'none'
		prevEffect: 'none', // 'elastic', 'fade' or 'none'

		helpers: {
			title: {
				type: 'inside'
			},
			buttons: {}
		},
		afterLoad: function () {
			this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
		}
	});

	/*==FIX DUPLICATE IMAGE==*/
	$(".more-views .thumb-link").click(function () {
		//get current href #lightbox_btn
		var bk_srcImage = $("#lightbox_btn").attr('href');
		//get href .more-views .thumb-link after click event
		var thumb_srcImage = $(this).attr('data-src-img');

		//find and replace href duplicate
		$(".btn-lightbox a").each(function () {
			var href_this = $(this).attr('href');
			if (href_this == thumb_srcImage) {
				$(this).attr('href', bk_srcImage);
			}
		});

		//set thumb_srcImage to #lightbox_btn
		$("#lightbox_btn").attr('href', thumb_srcImage);
	});
	
	$(".col-main .more-views .thumb-link").click(function () {
		//add active class
		$(".col-main .more-views li").removeClass('active_thumbs');
		$(this).parent().addClass('active_thumbs');
	});

	$(".quickview-main .more-views .thumb-link").click(function () {
		//add active class
		$(".quickview-main .more-views li").removeClass('active_thumbs');
		$(this).parent().addClass('active_thumbs');
	});

});

var addTagFormJs = new VarienForm('addTagForm');
function submitTagForm(){
    if(addTagFormJs.validator.validate()) {
        addTagFormJs.form.submit();
    }
}

function pushState(data, link, replace) {
    var History = window.History;
    if (!History.enabled) {
        return false;
    }

    if (replace) {
        History.replaceState(data, document.title, link);
    } else {
        History.pushState(data, document.title, link);
    }
}

function handleEvent(el, event) {
    var url, fullUrl;
    if (typeof el === 'string') {
        url = el;
    } else if (el.tagName.toLowerCase() === 'a') {
        url = $(el).readAttribute('href');
    } else if (el.tagName.toLowerCase() === 'select') {
        url = $(el).getValue();
    }

                if (url.indexOf('?') != -1) {
        fullUrl = url + '&isLayerAjax=1';
    } else {
        fullUrl = url + '?isLayerAjax=1';
    }

    $('loading').show();
    $('ajax-errors').hide();

    pushState(null, url, false);

    new Ajax.Request(fullUrl, {
        method: 'get',
        onSuccess: function (transport) {
            if (transport.responseJSON) {
                $('catalog-listing').update(transport.responseJSON.listing);
				if($('layered-navigation')){
					$('layered-navigation').update(transport.responseJSON.layer);
				}
                pushState({
                    listing: transport.responseJSON.listing,
                    layer: transport.responseJSON.layer
                }, url, true);
				ConfigurableSwatchesList.init();
                ajaxListener();
            } else {
                $('ajax-errors').show();
            }
            $('loading').hide();
        }
    });

    if (event) {
        event.preventDefault();
    }
}
function ajaxListener() {
    var els;
    els = $$('div.pager a').concat(
        $$('div.sorter a'),
        $$('div.pager select'),
        $$('div.limiter select'),
        $$('div.sorter select'),
        $$('div.block-layered-nav a')
    );
    els.each(function (el) {
        if (el.tagName.toLowerCase() === 'a') {
            $(el).observe('click', function (event) {
                handleEvent(this, event);
            });
        } else if (el.tagName.toLowerCase() === 'select') {
            $(el).setAttribute('onchange', '');
            $(el).observe('change', function (event) {
                handleEvent(this, event);
            });
        }
    });
}
document.observe("dom:loaded", function () {
    ajaxListener();

    (function (History) {
        if (!History.enabled) {
            return false;
        }

        pushState({
            listing: $('catalog-listing').innerHTML,
            layer: $('layered-navigation').innerHTML
        }, document.location.href, true);

        // Bind to StateChange Event
        History.Adapter.bind(window, 'popstate', function (event) {
            if (event.type == 'popstate') {
                var State = History.getState();
                $('catalog-listing').update(State.data.listing);
                $('layered-navigation').update(State.data.layer);
                ajaxListener();
            }
        });
    })(window.History);
});

$j(document).on('product-media-loaded', function() {
    ConfigurableMediaImages.init('small_image');
    ConfigurableMediaImages.setImageFallback(913, $j.parseJSON('{"option_labels":[],"small_image":{"913":"http:\/\/demo.flytheme.net\/themes\/sm_stationery\/media\/catalog\/product\/cache\/4\/small_image\/9df78eab33525d08d6e5fb8d27136e95\/3\/_\/3_1.png"},"base_image":[]}'));
    ConfigurableMediaImages.setImageFallback(914, $j.parseJSON('{"option_labels":[],"small_image":{"914":"http:\/\/demo.flytheme.net\/themes\/sm_stationery\/media\/catalog\/product\/cache\/4\/small_image\/9df78eab33525d08d6e5fb8d27136e95\/9\/_\/9_1.png"},"base_image":[]}'));
    ConfigurableMediaImages.setImageFallback(915, $j.parseJSON('{"option_labels":[],"small_image":{"915":"http:\/\/demo.flytheme.net\/themes\/sm_stationery\/media\/catalog\/product\/cache\/4\/small_image\/9df78eab33525d08d6e5fb8d27136e95\/1\/0\/10_3.png"},"base_image":[]}'));
    ConfigurableMediaImages.setImageFallback(916, $j.parseJSON('{"option_labels":[],"small_image":{"916":"http:\/\/demo.flytheme.net\/themes\/sm_stationery\/media\/catalog\/product\/cache\/4\/small_image\/9df78eab33525d08d6e5fb8d27136e95\/1\/8\/18_4.png"},"base_image":[]}'));
    ConfigurableMediaImages.setImageFallback(918, $j.parseJSON('{"option_labels":[],"small_image":{"918":"http:\/\/demo.flytheme.net\/themes\/sm_stationery\/media\/catalog\/product\/cache\/4\/small_image\/9df78eab33525d08d6e5fb8d27136e95\/7\/_\/7_2.png"},"base_image":[]}'));
    $j(document).trigger('configurable-media-images-init', ConfigurableMediaImages);
});

function toggleRememberMepopup(event){
    if($('remember-me-popup')){
        var viewportHeight = document.viewport.getHeight(),
            docHeight      = $$('body')[0].getHeight(),
            height         = docHeight > viewportHeight ? docHeight : viewportHeight;
        $('remember-me-popup').toggle();
        $('window-overlay').setStyle({ height: height + 'px' }).toggle();
    }
    Event.stop(event);
}

document.observe("dom:loaded", function() {
    new Insertion.Bottom($$('body')[0], $('window-overlay'));
    new Insertion.Bottom($$('body')[0], $('remember-me-popup'));

    $$('.remember-me-popup-close').each(function(element){
        Event.observe(element, 'click', toggleRememberMepopup);
    })
    $$('#remember-me-box a').each(function(element) {
        Event.observe(element, 'click', toggleRememberMepopup);
    });
});

function toggleRememberMepopup(event){
    if($('remember-me-popup')){
        var viewportHeight = document.viewport.getHeight(),
            docHeight      = $$('body')[0].getHeight(),
            height         = docHeight > viewportHeight ? docHeight : viewportHeight;
        $('remember-me-popup').toggle();
        $('window-overlay').setStyle({ height: height + 'px' }).toggle();
    }
    Event.stop(event);
}

document.observe("dom:loaded", function() {
    new Insertion.Bottom($$('body')[0], $('window-overlay'));
    new Insertion.Bottom($$('body')[0], $('remember-me-popup'));

    $$('.remember-me-popup-close').each(function(element){
        Event.observe(element, 'click', toggleRememberMepopup);
    })
    $$('#remember-me-box a').each(function(element) {
        Event.observe(element, 'click', toggleRememberMepopup);
    });
});

function updateLang(obj)
{
	alert(obj);
	if( $(obj).val() != "" )
	{
		showLoader();
		var loc = (base_url+'home/setLangSession');
		$.get(loc, { set : "lang",  lang : $(obj).val() }, function (data)
		{
			hideLoader();
			var arr = $.parseJSON(data);
			if(arr['type']=='success')
			{
				window.location.reload();
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
			
		});
	}
}