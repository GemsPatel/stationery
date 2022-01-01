<div class="header-bottom">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3  col-sm-4  col-xs-5  logo-wrapper">
				<h1 class="logo">
					<strong>Stationery</strong>
					<a href="<?php echo site_url()?>" title="Stationery" class="logo">
						<img src="<?php echo asset_url('images/pink/logo.png')?>" alt="Stationery" />
					</a>
				</h1>
			</div>
			
			<div class="col-lg-5 col-md-5 links-top hidden-xs hidden-sm  visible-md visible-lg">
				<div class="header-link-right hide">
					<div class="block-account-cusomer link-base">
						<ul class="links">
							<?php
							if( !isLoggedIn() ){
							?>
								<li class="first" >
		                        	<a href="<?php echo site_url('login'); ?>" title="My Account"><?php echo getLangMsg("lgn");?></a>
		                        </li>
		                        <li class="last" >
		                        	<a href="<?php echo site_url('register') ?>" title="Register"><?php echo getLangMsg("reg");?></a>
		                        </li>
        					<?php } 
        					else { ?>
        						<li class="first" >
		                        	<a href="<?php echo site_url('account'); ?>" title="My Account"><?php echo getLangMsg("ma");?></a>
		                        </li>
		                        <li class="last" >
		                        	<a href="<?php echo site_url('logout') ?>" title="Logout"><?php echo getLangMsg("lo");?></a>
		                        </li>
        					<?php } ?>
            			</ul>
					</div>
					
					<div class="block-account-cusomer hide">
						<div class="toplink-cus my-account">
							<a title="My Account" href="<?php echo site_url('sm/info')?>">My account</a>
							<ul class="dropdown-list">
								<li><a href="<?php echo site_url('sm/addBook')?>">Address Book</a></li>
								<li><a href="<?php echo site_url('sm/myOrder')?>">My Orders</a></li>
								<li><a href="<?php echo site_url('sm/billAgre')?>">Billing Agreements</a></li>
								<li class="hide"><a href="<?php echo site_url('sm/recProfile')?>">Recurring Profiles</a></li>
								<li class="hide"><a href="<?php echo site_url('sm/productReview')?>">My Product Reviews</a></li>
								<li class="hide"><a href="<?php echo site_url('sm/tags')?>">My Tags</a></li>
								<li><a href="<?php echo site_url('sm/wishllist')?>">My Wishlist</a></li>
								<li class="hide"><a href="<?php echo site_url('sm/application')?>">My Applications</a></li>
								<li class="last"><a href="<?php echo site_url('sm/newsletter')?>">Newsletter Subscriptions</a></li>
								<li class="last hide"><a href="<?php echo site_url('sm/products')?>">My Downloadable Products</a></li>
							</ul>
						</div>
					</div>
					
					<div class="block-account-cusomer link-base">
						<ul class="links">
	                        <li class="first" >
	                        	<a href="<?php echo site_url('sm/wishllist')?>" title="My Wishlist" class="my-wishlist">My Wishlist</a>
	                        </li>
	                        <li class=" last" >
	                        	<a href="<?php echo site_url()?>" title="Checkout" class="top-link-checkout">Checkout</a>
	                        </li>
            			</ul>
						<div class="block block-list block-compare hide">
    						<div class="block-title">
        						<strong>
        							<span>Compare</span>
        						</strong>
    						</div>
    						<div class="block-content">
            					<p class="empty">You have no items to compare.</p>
        					</div>
						</div>
					</div>
					
					<div class="block-account-cusomer  hide">
						<div class="toplink-cus cus-btn">
							<a class="login-btn" data-toggle="modal" data-target="#modal-login" title="Login">Login</a>
						</div>
					</div>
				</div> 
			</div>
			
			<div class="col-lg-4 col-md-4 col-sm-8 col-xs-7 header-bottom-right">						
				<div class="minicart-header">
					<div id="sm_cartpro" class="sm-cartpro">
						<?php $resArr = getCartWishCount();?>
						<a class="" href="<?php echo site_url("cart")?>" title="Cart">
							<div class="cartpro-title  cartpro-empty ">
								<span class="cartpro-icon"></span>
								<span class="cartpro-count"><?php echo ( $resArr['cart'] ) ? $resArr['cart'] : 0 ?></span>
								<span class="label-item"> Item(s)</span>
								<span class="total-price"><span class="price">$0.00</span></span> 
							</div>
						</a>
						<!-- <div class="cartpro-content">
							<div class="cartpro-wrapper">
	                     		<p class="empty">You have no items in your shopping cart.</p>

    						</div>
						</div> -->
						
						<!-- <div class="cartpro-content">
							<div class="cartpro-wrapper">
								<p class="label-recent">Recently Added Item(s)</p>
								<div class="cartpro-products">
									<ul class="cartpro-products-inner">
										<li class="item">
											<a href="http://demo.flytheme.net/themes/sm_stationery/dumas-chukame.html" title="Dumas chukame" class="product-image">
												<img src="http://demo.flytheme.net/themes/sm_stationery/media/catalog/product/cache/4/thumbnail/80x65/9df78eab33525d08d6e5fb8d27136e95/1/5/15_1.png" width="70" height="47" alt="Dumas chukame">
											</a>
											<div class="product-details">
												<p class="product-name">
													<a href="http://demo.flytheme.net/themes/sm_stationery/dumas-chukame.html">Dumas chukame</a>
												</p>
												<div class="product-price">
													<span class="price-value">
														<span class="price">$44.00</span>																	
													</span>
												</div>
												<div class="product-qty">
													<span class="qty-label"> Qty </span>
													<span class="qty-value">
														<input id="cpqinput-5644" name="5644" data-link="" data-item-id="5644" class="qty cart-item-quantity input-text" value="1" data-qtyvalue="1">
														<button id="cpqbutton-5644" data-item-id="5644" disabled="disabled" data-update="" class="button quantity-button">ok</button>
													</span>
												</div>
												<div class="product-action">
													<a href="http://demo.flytheme.net/themes/sm_stationery/checkout/cart/configure/id/5644/?___SID=U" title="Edit item" class="fa fa-pencil btn-edit"></a>
													<a href="" title="Remove This Item" data-confirm="Are you sure you would like to remove this item from the shopping cart?" class="fa fa-times remove"></a>
												</div>	
											</div>
										</li>
									</ul>
								</div>
								<div id="cartpro-widgets"> </div>
								<div class="cartpro-subtotal">
									<span class="label">Cart Subtotal:</span> 
									<span class="price">$44.00</span>							        
								</div>
								<div class="cartpro-actions">
									<a class="button cart-link" href="http://demo.flytheme.net/themes/sm_stationery/checkout/cart/?___SID=U"> View Cart </a>
									<a title="Checkout" class="button checkout-button" href="http://demo.flytheme.net/themes/sm_stationery/onepage/?___SID=U"> Checkout </a>
								</div>
							</div>
						</div> -->
					</div>
				</div>
				
				<div class="search-wrapper">
					<form id="wild_searchf" class="site-search" onSubmit="keywordSearch(); return false;">
					    <div class="form-search">
					        <input type="text" value="<?php echo @$searchf['search_terms_keywords']; ?>" name="search_terms_keywords" id="term" class="input-text" placeholder="Search entire store here...">
					        <button onClick="keywordSearch()" title="Search" class="button-search"><span><span>Search</span></span></button>
					        <script type="text/javascript">
					        </script>
					    </div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="yt-menu">
	<div class="menu-under">
		<div class="menu-larger">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="wrapper-menumain">
							<div class="sm_megamenu_wrapper_horizontal_menu sambar" id="sm_megamenu_menu5a2eff4d76c42" data-sam="7214495871513029453">
								<div class="sambar-inner">
									<ul class="sm-megamenu-hover sm_megamenu_menu sm_megamenu_menu_black" data-jsapi="on">
										<li>
											<a class="menu-home" href="<?php echo site_url();?>" title="Home"><span>Home</span></a>
										</li>
										
										<?php $this->load->view('elements/header-menu-sub');?>

										<li class="other-toggle sm_megamenu_lv1 sm_megamenu_nodrop">
											<a class="sm_megamenu_head sm_megamenu_nodrop " href="blog.html"  id="sm_megamenu_150">
												<span class="sm_megamenu_icon sm_megamenu_nodesc">		
													<span class="sm_megamenu_title">Blog</span>
												</span>
											</a>
											<li class="other-toggle sm_megamenu_lv1 sm_megamenu_drop parent hide">
												<a class="sm_megamenu_head sm_megamenu_drop " href="shop.html"  id="sm_megamenu_5">
													<span class="item-icon">
														<img alt="icon item" src="media/wysiwyg/icon-megamenu/hot-icon.png" />
													</span>
													<span style="" class="sm_megamenu_icon sm_megamenu_nodesc">		
														<span class="sm_megamenu_title">Shop</span>
													</span>
												</a>
											</li>
										</li>
									</ul>
								</div>
							</div>
							<!--End Module-->
							<div class="socials-wrap hide">
								<ul>
									<li class="li-social facebook-social">
										<a title="Facebook" href="<?php echo site_url()?>" target="_blank">
											<span class="fa fa-facebook icon-social"></span>
											<span class="name-social">Facebook</span>
										</a>
									</li>
									<li class="li-social twitter-social">
										<a title="Twitter" href="<?php echo site_url()?>" target="_blank">
											<span class="fa fa-twitter icon-social"></span>
											<span class="name-social">Twitter</span>
										</a>
									</li>
									<li class="li-social google-social">
										<a title="Google+" href="<?php echo site_url()?>" target="_blank">
											<span class="fa fa-google-plus icon-social"></span>
											<span class="name-social">Google+</span>
										</a>
									</li>
									<li class="li-social linkedin-social">
										<a title="Linkedin" href="<?php echo site_url()?>" target="_blank">
											<span class="fa fa-linkedin icon-social"></span>
											<span class="name-social">Linkedin</span>
										</a>
									</li>
									<li class="li-social pinterest-social">
										<a title="Pinterest" href="<?php echo site_url()?>" target="_blank">
											<span class="fa fa-pinterest icon-social"></span>
											<span class="name-social">Pinterest</span>
										</a>
									</li>
								</ul>
							</div>    
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>