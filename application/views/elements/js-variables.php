<script type="text/javascript" src="<?php echo asset_url('js/prototype/prototype.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/lib/ccard.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/prototype/validation.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/scriptaculous/builder.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/scriptaculous/effects.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/scriptaculous/dragdrop.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/scriptaculous/controls.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/scriptaculous/slider.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/varien/js.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/varien/form.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/varien/menu.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/mage/translate.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/mage/cookies.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/varien/product.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/calendar/calendar.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/calendar/calendar-setup.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/varien/configurable.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery-2.1.1.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/camera.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/enfinity.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.easing.1.3.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.mobile.customized.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/bundle.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/msrp.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.noconflict.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/cartpro.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/cartpro_update.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/owl.carousel.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.lightSlider.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/easyResponsiveTabs.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.elevatezoom.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.fancybox.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.fancybox-buttons.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/configurableswatches/product-media.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/configurableswatches/swatches-list.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/configurableswatches/swatches-product.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/app.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/lib/imagesloaded.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/bootstrap/bootstrap.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.uniform.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/jquery.cookie.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/yt-theme.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/custom.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/common.js')?>"></script>
<script type="text/javascript" src="<?php //echo asset_url('js/jquery.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/sm-megamenu.js')?>"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var asset_url = "<?php echo asset_url(); ?>";
	var controller = "<?php echo ucfirst($this->router->class); ?>";
	//see UML - An-JGV for more information on below variables
	var is_mobile = <?php echo ($this->session->userdata('lType') == 'PC' ? 'false' : 'true') ?>;
	var is_listing_page = false; 	//for scroll pagination
	var is_sol_listing = false;
	var p_id = 0; 
	var proConfig;
	var baseDomain = '<?php echo base_domain(); ?>';	//base domain for XMPP service
	var sessions_id = <?php echo $this->session->userdata('sessions_id'); ?>;	//used in setting default XMPP connection for front user(Exper...)
	var appLaunch = "<?php echo getSysConfig('appLaunch'); ?>";
	var is_download_app = "<?php echo $this->session->userdata('is_SID_c'); ?>";	//When on mobile the webapp is first time launched show download app popup, when is app is launched. 
	var filter_page = '';

	/**
	 * notification variables
	 */
	var type = ""; 
	var message = ""; 
	
	/**
	 * lang
	 */	
	function getLangMsg( type )
	{
		if( type == "qtyw" )
		{
			return "<?php echo getLangMsg("qtyw");?>"; 
		}
	}
</script>
<?php if( isLoggedIn() ):
		$login_link =  '<a href="'.site_url('login').'">LOGIN</a>';
?>		
			<script type="text/javascript">
				var is_logged_in = false;	
			</script>
<?php  
	  else:
?>
			<script type="text/javascript">
				var is_logged_in = true;	
			</script>
<?php     
		$login_link =  '<a href="'.site_url('logout').'">LOGOUT</a>';
	  endif;	
?>

<script type="text/javascript">
	Mage.Cookies.path     = '<?php echo site_url()?>';
	Mage.Cookies.domain   = '<?php echo site_url()?>';
	optionalZipCountries = ["HK","IE","MO","PA"];

    <!-- BEGIN GOOGLE ANALYTICS CODE -->
    var _gaq = _gaq || [];
	            
	_gaq.push(['_setAccount', '']);//UA-34732571-1
	
	_gaq.push(['_trackPageview']);
            
            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();

        <!-- END GOOGLE ANALYTICS CODE -->
    
	var enable_ajax_cart = 1;
	var enable_ajax_compare = 1;
	var enable_ajax_wishlist = 1;
	var confirm_countdown_number = 3;
	var effect = 'hover'
	var cartpro_baseurl = 'index.html';
	var isLoggedIn = 0;
	var currencyCode = '$';
    var Translator = new Translate([]);
	var SKIN_URL = '';//skin/frontend/sm_stationery/default.html
	var TMPL_NAME = '';///sm_stationery
	var TMPL_COOKIE = ["layoutstyle","menustyle"];
</script>

<!--SLIDER THUMB IMAGE WHEN HOVER PRODUCT-->
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".slider-img-thumb").lightSlider({
			loop: false,
			vertical:false,
			slideMargin: 0,
			item: 1,

			controls : true, // Show next and prev buttons
																	
			pager: false,
		});  
	});	
</script>