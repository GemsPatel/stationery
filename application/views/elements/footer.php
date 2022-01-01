    <?php 
    $this->load->view('elements/footer-menu');  
//  if( $this->session->userdata('is_download_app') )
// 	{
// 		$this->session->set_userdata('is_entersite_loaded',1);
// 	}
    ?>
	
	<!--GO TO TOP-->
	<a id="yt-totop" href="#" title="Go to Top"></a>
	<script type="text/javascript">
		jQuery(document).ready(function($){  
			$("#yt-totop").hide();
			$(function () {
				var wh = $(window).height();
				var whtml =  $(document).height();
				$(window).scroll(function () {
					if ($(this).scrollTop() > whtml/10) {
						$('#yt-totop').fadeIn();
					} else {
						$('#yt-totop').fadeOut();
					}
				});
				$('#yt-totop').click(function () {
					$('body,html').animate({
						scrollTop: 0
					}, 800);
					return false;
				});
			});
		});
	</script>

	<script type="text/javascript">
		jQuery(document).ready(function($){
			$('.theme-color').click(function(){
				$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			});
		});
	</script>

                
	<div id="cartpro_process" class="cartpro-process">
		<div class="cartpro-loadmark">
			<div class="cartpro-imageload">
				<img alt="Loading..." src="<?php echo asset_url('images/ajax_loading.gif')?>">
				<div class="cartpro-text">
					Please wait...			</div>
			</div>
		</div>
	</div>
	
	<div id="cartpro_modal" class="cartpro-modal ">
		<div class="cpmodal-wrapper">
			<a href="javascript:void(0)" title="Close" class="cpmodal-close cp-close"><span class="fa fa-times"></span></a>
			<div class="cpmodal-message"></div>
			<div class="cpmodal-iframe"></div>
			<div class="cpmodal-action">
				<a class="cpmodal-button cp-close" title="Continue"  href="#">
					Continue<span class="cpmodal-time  ">10</span></a>
				<a class="cpmodal-button cp-close cpmodal-viewcart cpmodal-display  cartpro-hidden" title="View cart & checkout" href="checkout/cart/index.html">
					View cart & checkout			</a>
				<button type="button" title="Compare" class="cp-close cpmodal-button cartpro-compare cpmodal-display  cartpro-hidden" onclick="popWin('http://demo.flytheme.net/themes/sm_stationery/catalog/product_compare/index/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9zdGF0aW9uZXJ5Lw,,/','compare','top:0,left:0,width=820,height=600,resizable=yes,scrollbars=yes')"><span><span>Compare</span></span></button>
				<a  class="cpmodal-button cp-close cartpro-wishlist cpmodal-display  cartpro-hidden " href="wishlist/index.html">Go to Wishlist</a>
			</div>
			<div class="cpmodal-form">
			</div>
		</div>
	</div>
	 
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
		   function _SmQuickView(){	
				var	pathbase = 'quickview/index/view',
					_item_cls = $('.products-grid .item-inner .box-info, .products-list .box-image-list'),
					_base_url = 'index.html',
					pathbase = 'quickview/index/view';
				var baseUrl = _base_url + pathbase;
				if(_item_cls.length > 0){
					_item_cls.each(function(index, el) {
						var $this = $(this)
						if($this.find("a.sm_quickview_handler").length <= 0){
							if( $this.find('a').length > 0 ){
								var _href =	$($this.find('a')[0]);				
								var	producturlpath = _href.attr('href').replace(_base_url,"");
									producturlpath = ( producturlpath.indexOf('index-2.html') >= 0 ) ? producturlpath.replace('index.php/index.html','') : producturlpath;
								var	reloadurl = baseUrl+ ("/path/"+producturlpath).replace(/\/\//g,"/"),
									_quickviewbutton = "<a  class='sm_quickview_handler' title='Quick View' href='"+reloadurl+"'></a>";
								$(el).append(_quickviewbutton);	
							}
						}
					});
				}
			}
		});
	</script>    
</div>
</div>
</body>
</html>