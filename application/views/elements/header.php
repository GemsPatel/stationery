<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- START META -->
	<?php
		/**
		 * global meta in case of local is missing
		 */ 
		$scArr;
		if( IS_CACHE )
		{
			$cache_key = cacheKey( 'site_config' );
			
			if ( ! $header_menu = get_cache( $cache_key ) )
			{
				$scArr = $this->db->where('manufacturer_id',MANUFACTURER_ID)->get('site_config')->row_array();
			
				saveCacheKey( $cache_key, 'site_config');
			
				// Save into the cache for infinite time
				save_cache( $cache_key, $header_menu, 0 );
			}
		}
		else
		{
			$scArr = $this->db->where('manufacturer_id',MANUFACTURER_ID)->get('site_config')->row_array();
		}
	?>
	<title><?php echo ( !empty($custom_page_title) ) ? $custom_page_title : $scArr['custom_page_title']; ?></title>
	<base href="<?php echo site_url();?>" />
	<link rel="shortcut icon" href="<?php echo asset_url('images/pink/favicon.png')?>">
	
	<meta name="description" content="<?php echo ( !empty($meta_description) ) ? $meta_description : $scArr['meta_description'];?>" />
	<meta name="keywords" content="<?php echo ( !empty($meta_keyword) ) ? $meta_keyword : $scArr['meta_keyword'];?>" />
	<meta name="robots" content="<?php echo ( !empty($robots) ) ? getField('robots_name','seo_robots','robots_id', $robots) : getField('robots_name','seo_robots','robots_id', $scArr['robots']); ?>" />
	<meta name="author" content="<?php echo ( !empty($author) ) ? $author : $scArr['author']; ?>" />
	<meta name="copyright" content="Copyright (c) <?php echo date('Y') ?>" />
	<meta name="generator" content="<?php echo getField('config_value','configuration','config_key','SEO_GENERATOR') ?>" />
	<meta http-equiv="vary" content="User-Agent">
	
	<?php if( !empty($canonical) ):?>
		<link rel="canonical" href="<?php echo $canonical; ?>" />
	<?php endif;?>
	
	<!-- END META -->

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/theme-pink.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('js/calendar/calendar-win2k-1.css')?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/widgets.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/cameraslide.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/animate.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/sm-listing-tabs.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/owl.carousel.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap/bootstrap.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap/bootstrap-theme.min.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/font-awesome/css/font-awesome.css')?>" media="all" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/print.css')?>" media="print" />
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/style.css')?>" media="all"/>
    
	<!-- FONTS -->
	<!--GENERAL GOOGLE FONT-->
	<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Bitter' />

	<!--GENERAL MAIN GOOGLE FONT-->
	<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Maven+Pro:900,400,700' />
	
    <!-- jQuery -->
    <?php $this->load->view('elements/js-variables');  ?>
     
</head>
<body class=" cms-index-index cms-home-v4 cmspage4">

<div class="wrapper">
	<noscript>
        <div class="global-site-notice noscript">
            <div class="notice-inner">
                <p>
                    <strong>JavaScript seems to be disabled in your browser.</strong><br />
                    You must have JavaScript enabled in your browser to utilize the functionality of this website.</p>
            </div>
        </div>
    </noscript>

	<!-- PAGE -->
	<div id="page">
		<div class="header-container header-style-4">
		    <div class="header">
				<div class="header-inner">
					<div class="header-top">
						<div class="container">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 contach-top">
									<div class="pull-left">
										<div class="contact-block">
											<div class="phone-number">
												<?php $tollFree = getField('config_value','configuration','config_key','TOLL_FREE_NO') ?>
												<i class="fa fa-phone">&nbsp;</i>
												<a href="tel:<?php echo $tollFree ?>" ><?php echo $tollFree ?></a>
											</div>
        									<div class="email-block">
        										<i class="fa fa-envelope">&nbsp;</i>
        										<a href="<?php echo getLangMsg("lc");?>" title="<?php echo getLangMsg("lc");?>"><?php echo getLangMsg("lc");?></a>
        									</div>
										</div>
									</div>
								</div>
								
								<style>
									.myacc_with_log a
									{
										background-repeat: no-repeat;
									    background-position: left center;
									    line-height: 25px;
									    color: #fff;
									    margin-left: 10px;	
									}
								</style>
								
								<div class="col-lg-6 col-md-6 col-sm-6 setting">
									<div class="pull-right">
										<div class="dropdown-block">
											<div class="lang-switcher">
												<div class="myacc_with_log">
													<?php
													if( !isLoggedIn() ){
													?>
														<div class="value">
								                        	<a href="<?php echo site_url('login'); ?>" title="Login"><?php echo getLangMsg("lgn");?></a>
								                        	<a href="<?php echo site_url('register') ?>" title="Register"><?php echo getLangMsg("reg");?></a>
								                        </div>
						        					<?php } 
						        					else { ?>
						        						<div class="value">
								                        	<a href="<?php echo site_url('logout') ?>" title="Logout"><?php echo getLangMsg("lo");?></a>
								                        </div>
						        					<?php } ?>
												</div>
											</div>
	    								</div>
									</div>
									
									<div class="pull-right">
										<div class="dropdown-block">
											<div class="lang-switcher">
												<div class="dropdown-toggle">
													<div class="value flag"><?php echo $this->session->userdata("LANG");?></div>
												</div>
												<ul class="dropdown-list">
													<li>
														<a onchange="updateLang(this);">
															<span class="dropdown-icon" >&nbsp;English</span>
														</a>
													</li>
													<li>
														<a onchange="updateLang(this);">
															<span class="dropdown-icon" >&nbsp;Hindi</span>
														</a>
													</li>
													<li>
														<a onchange="updateLang(this);">
															<span class="dropdown-icon" >&nbsp;Gujrati</span>
														</a>
													</li>
												</ul>		
											</div>
	    								</div>
	    								
										<div class="dropdown-block currency-block">
											<div class="currency-switcher">
												<div class="dropdown-toggle">
													<div class="value">Usd</div>
												</div>
												<ul class="dropdown-list">
													<?php
													$sql = "SELECT currency_id,currency_code FROM currency WHERE currency_status=0 GROUP BY currency_code ORDER BY currency_code ";
													
													$currArr = getDropDownAry( $sql, "currency_id", "currency_code", "", false );
													echo hover_dropdown( 'currency_id', @$currArr, CURRENCY_ID, 'onchange="changeCurrency(this.value)" class="" ');
												?>
												<!-- <li class="current">Usd</li> -->
												</ul>		
											</div>
	    								</div>
									</div>
									
									<?php
									if( isLoggedIn() ){
									?>
										<div class="pull-right">
											<div class="dropdown-block">
												<div class="lang-switcher">
													<div class="dropdown-toggle">
														<div class="value flag"><?php echo getField( "customer_firstname", "customer", "customer_id", $this->session->userdata('customer_id') );?></div>
													</div>
													<ul class="dropdown-list">
														<li><a href="<?php echo site_url('account');?>"><?php echo getLangMsg("ma");?></a></li>
														<li><a href="<?php echo site_url('account/address-books');?>"><?php echo getLangMsg("a_bok");?></a></li>
														<li><a href="<?php echo site_url('account/edit-account');?>"><?php echo getLangMsg("e_acc");?></a></li>
														<li class="hide"><a href="<?php echo site_url('account/change-password')?>"><?php echo getLangMsg("cng_pass");?></a></li>
														<li><a href="<?php echo site_url('account/order-history')?>"><?php echo getLangMsg("o_h");?></a></li>
														<li><a href="<?php echo site_url('sm/billAgre')?>">Billing Agreements</a></li>
														<li class="hide"><a href="<?php echo site_url('sm/recProfile')?>">Recurring Profiles</a></li>
														<li class="hide"><a href="<?php echo site_url('sm/productReview')?>">My Product Reviews</a></li>
														<li class="hide"><a href="<?php echo site_url('sm/tags')?>">My Tags</a></li>
														<li class="hide"><a href="<?php echo site_url('sm/application')?>">My Applications</a></li>
														<li><a href="<?php echo site_url('account/newsletter')?>"><?php echo getLangMsg("nl");?></a></li>
														<li class="last hide"><a href="<?php echo site_url('sm/products')?>">My Downloadable Products</a></li>
													</ul>	
												</div>
		    								</div>
		    								
		    								<div class="dropdown-block">
												<div class="lang-switcher">
													<div class="myacc_with_log">
														<div class="value">
								                        	<a href="<?php echo site_url('account/wishlist')?>"><?php echo getLangMsg("w_l");?></a>
								                        	<a href="<?php echo site_url()?>" title="Checkout" class="top-link-checkout">Checkout</a>
								                        </div>
													</div>
												</div>
											</div>
										</div>
									<?php } ?>
									
								</div>
							</div>
						</div>
					</div>
					<?php 
						$this->load->view('elements/header-menu');
					?>