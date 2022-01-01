				<div id="sidebar" class="col-lg-3 col-md-3 col-sm-3 padbot50">
						
						<!-- WIDGET SEARCH -->
						<div class="sidepanel widget_search">
							<form class="search_form" action="javascript:void(0);" method="get" name="search_form">
								<input type="text" name="Search..." value="Search..." onFocus="if (this.value == 'Search...') this.value = '';" onBlur="if (this.value == '') this.value = 'Search...';" />
							</form>
						</div><!-- //WIDGET SEARCH -->
						
						<!-- CATEGORIES -->
						<div class="sidepanel widget_categories">
							<h3><?php echo getLangMsg("sm");?></h3>
							<ul>
								<li><a href=""><?php echo getLangMsg("au");?></a></li>
								<li><a href=""><?php echo getLangMsg("veg");?></a></li>
								<li><a href=""><?php echo getLangMsg("frt");?></a></li>
								<li><a href=""><?php echo getLangMsg("na");?></a></li>
								<li><a href=""><?php echo getLangMsg("cu");?></a></li>
							</ul>
						</div><!-- //CATEGORIES -->
						
						<!-- NEWSLETTER FORM WIDGET -->
						<div class="sidepanel widget_newsletter">
							<div class="newsletter_wrapper">
								<h3><?php echo getLangMsg("nl");?></h3>
								<form class="newsletter_form clearfix" action="javascript:void(0);" method="get">
									<input type="text" name="newsletter" value="Enter E-mail & Get 10% off" onFocus="if (this.value == 'Enter E-mail & Get 10% off') this.value = '';" onBlur="if (this.value == '') this.value = 'Enter E-mail & Get 10% off';" />
									<input class="btn newsletter_btn" type="submit" value="Sign up & get 10% off">
								</form>
							</div>
						</div><!-- //NEWSLETTER FORM WIDGET -->
						
						<!-- WIDGET POPULAR POSTS -->
						<div class="sidepanel widget_popular_posts">
							<h3><?php echo getLangMsg("na");?></h3>
							<ul>
								<li class="widget_popular_post_item clearfix">
									<a class="widget_popular_post_img" href=""><img src="images/tovar/popular1.jpg" alt="" title="" width="70" /></a>
									<a class="tovar_item_small_title" href="">New Fashion Vintage Long</a>
									<span class="tovar_item_small_price">$118.00</span>
								</li>
								<li class="widget_popular_post_item clearfix">
									<a class="widget_popular_post_img" href="blog-post.html" ><img src="images/tovar/popular2.jpg" alt="" title="" width="70" /></a>
									<a class="tovar_item_small_title" href="blog-post.html" >In the Kitchen with…Potato</a>
									<span class="tovar_item_small_price">$118.00</span>
								</li>
								<li class="widget_popular_post_item clearfix">
									<a class="widget_popular_post_img" href="blog-post.html" ><img src="images/tovar/popular3.jpg" alt="" title="" width="70" /></a>
									<a class="tovar_item_small_title" href="blog-post.html" >2013 Hot Women Warm Coat Lady</a>
									<span class="tovar_item_small_price">$118.00</span>
								</li>
							</ul>
						</div><!-- //WIDGET POPULAR POSTS -->
						
						<!-- BANNERS WIDGET -->
						<?php //$this->load->view('elements/ads-verticle');?>
                        <!-- //BANNERS WIDGET -->
                        
					</div>