<style>
</style>
<div class="main-container col2-left-layout">
	<div class="main">
		<div class="container">
			<div class="row">					
				<div id="ytech_left" class="col-lg-3 col-md-3 col-left sidebar">
					<?php //$this->load->view('elements/home-category');?>
					<div class="effect-banner">
						<a href="#" class="banner-img">
							<div class="img">
								<img src="<?php echo asset_url('images/home-page-image/banner-index4.jpg')?>" alt="" />
							</div>
							<div class="info">
								<h3>Top Sale</h3>
								<p>Product</p>
							</div>
						</a>
					</div>
					
					<?php $this->load->view('elements/feature-product');?>
					
					<div id="messages_product_view"> </div>
					
					<?php 
					$this->load->view('elements/blog-post');
					$this->load->view('elements/top-rated');
					$this->load->view('elements/news-letter');
					$this->load->view('elements/testimonial');
					?>
				</div>						
				<div class="col-lg-9 col-md-9 col-main">
					<div class="std">
						<p class="no-display">&nbsp;</p>
					</div>
					
					<?php $this->load->view('elements/home-slider');?>
					
					<div class='clearfix_cameraslide cameraslide'></div>
					<div class="lintting-tabs-no-slider4">
						<div id="sm_listing_tabs_6740590521501112828" class="sj-listing-tabs listing-tabs-top first-load"><!--<![endif]-->
							<div class="ltabs-wrap ">
								<div class="tab-listing-title" style="margin-bottom: -70px;">
									<h2>DEAL OF THE DAY</h2>
								</div>
								<div class="ltabs-tabs-container" data-delay="200" data-duration="200" data-effect="bounceIn" data-ajaxurl="http://demo.flytheme.net/themes/sm_stationery/listingtabs/index/ajax" data-modid="sm_listing_tabs_6740590521501112828">
									<div class="ltabs-tabs-wrap">
										<span class='ltabs-tab-selected'></span>
										<span class='ltabs-tab-arrow'>&#9660;</span>
										<ul class="ltabs-tabs cf">
											<li class="ltabs-tab   tab-sel tab-loaded hide" data-category-id="top_rating" data-active-content=".items-category-top_rating">
												<span class="ltabs-tab-label"> Top Rating					</span>
											</li>
											<li class="ltabs-tab  hide " data-category-id="lastest_product" data-active-content=".items-category-lastest_product">
												<span class="ltabs-tab-label"> Latest Products					</span>
											</li>
											<li class="ltabs-tab hide " data-category-id="created_at" data-active-content=".items-category-created_at">
												<span class="ltabs-tab-label"> Created Date					</span>
											</li>
										</ul>
									</div>
								</div>
								<?php $fpArr = $this->jew->getProducts( staticCategoryIDs( "deal-of-the-day" )  );?>
								<div class="ltabs-items-container show-slider"><!--Begin Items-->
									<div class="products-grid">
										<div class="ltabs-items ltabs-items-selected ltabs-items-loaded items-category-top_rating">
											<div class="ltabs-items-inner ltabs00-3 ltabs01-3 ltabs02-3 ltabs03-2 ltabs04-1 bounceIn">
												<?php 
												if(isset($fpArr["data"]["result_array"]) && sizeof($fpArr["data"]["result_array"])>0)
												{
													foreach($fpArr["data"]["result_array"] as $key=>$val)
													{
														if($key < 3 )
														{
															$catidArr = explode('|',$val['category_id']);
															if(is_array($catidArr) && sizeof($catidArr)>0)
															{
																$val['category_id'] = $catidArr[0];
															}
															$prodUrl = getProductUrl($val['product_id'],$val['product_price_id'],$val['product_alias'],$val['category_id']);
															
															$price = "";
															if($val['product_discount'] != 0)
																$price = '<span class="tovar_price chan_curr tovar_view_price_old fleft">'.lp($val['product_price_calculated_price'],2,true).'</span>&nbsp;
																									  <span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															else
																$price = '<span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															
															$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
															
														
															/**
															 * product stock validation added on 23-04-2015
															 */
															$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] );
															
															//($key + 1 ) %4 == 0
															?>
															<div class="row-item">    
																<div class="ltabs-item new-ltabs-item item">
																	<div class="item-inner">
																		<div class="box-image item-image">
																			<div class="effect-default">
																				<a href="<?php echo $prodUrl ?>" title="Simas jarema" class="product-image">
																					<img id="product-collection-image-925" src="<?php echo load_image( $product_images[$val['product_angle_in']] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>" style="width: 270px; height: 255px;" />
																				</a>
																			</div>
																		</div>
																		<div class="box-info">
																			<h2 class="product-name">
																				<a href=""<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>"><?php echo $key.char_limit($val['product_name'],MANUFACTURER_ID == 7 ? 20 : 32);?></a>
																			</h2>
																			<div class="item-review">
																				<div class="ratings">
																					<div class="rating-box">
																						<div class="rating" style="width:100%"></div>
																					</div>
																					<p class="rating-links">
																						<a href="review/product/list/id/925/index.html">1 Review(s)</a>
																					</p>
																				</div>
																			</div>
																			<div class="bs-price">
																				<div class="sale-price">
																					<div class="price-box">
																						<span class="regular-price" >
																							<span class="price"><?php echo lp($val['product_discounted_price'],2,true);?></span>                                    
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="actions">
																				<button type="button" title="Add to Cart" class="button btn-cart<?php echo (($is_out_of_stock)? ' hide' : '')?>" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')">
																					<i class="fa fa-shopping-cart"></i>
																				</button>
																				<ul class="add-to-links">
																					<li>
																						<a title="Add to Wishlist" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" class="link-wishlist"></a>
																					</li>
																					<li class="hide">
																						<span class="separator">|</span>
																						<a title="Add to Compare" href="catalog/product_compare/add/product/925/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9/form_key/tamEEQVYAjMzOa7h/index.html" class="link-compare">                                    </a>
																					</li>
																				</ul>
																			</div>
																			<!-- <div class="other-infor"> </div> -->
																		</div>
																	</div>
																</div>
															</div> 
															<?php
														}
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="ltabs-wrap ">
								<div class="tab-listing-title" style="margin-bottom: -70px;">
									<h2>LATEST PRODUCTS</h2>
								</div>
								<div class="ltabs-tabs-container" data-delay="200" data-duration="200" data-effect="bounceIn" data-ajaxurl="http://demo.flytheme.net/themes/sm_stationery/listingtabs/index/ajax" data-modid="sm_listing_tabs_6740590521501112828">
									<div class="ltabs-tabs-wrap">
										<span class='ltabs-tab-selected'></span>
										<span class='ltabs-tab-arrow'>&#9660;</span>
										<ul class="ltabs-tabs cf">
											<li class="ltabs-tab   tab-sel tab-loaded hide" data-category-id="top_rating" data-active-content=".items-category-top_rating">
												<span class="ltabs-tab-label"> Top Rating					</span>
											</li>
											<li class="ltabs-tab  hide " data-category-id="lastest_product" data-active-content=".items-category-lastest_product">
												<span class="ltabs-tab-label"> Latest Products					</span>
											</li>
											<li class="ltabs-tab hide " data-category-id="created_at" data-active-content=".items-category-created_at">
												<span class="ltabs-tab-label"> Created Date					</span>
											</li>
										</ul>
									</div>
								</div>
								<?php $fpArr = $this->jew->getProducts( staticCategoryIDs( "latest-products" )  );?>
								<div class="ltabs-items-container show-slider"><!--Begin Items-->
									<div class="products-grid">
										<div class="ltabs-items ltabs-items-selected ltabs-items-loaded items-category-top_rating">
											<div class="ltabs-items-inner ltabs00-3 ltabs01-3 ltabs02-3 ltabs03-2 ltabs04-1 bounceIn">
												<?php 
												if(isset($fpArr["data"]["result_array"]) && sizeof($fpArr["data"]["result_array"])>0)
												{
													foreach($fpArr["data"]["result_array"] as $key=>$val)
													{
														if($key < 3 )
														{
															$catidArr = explode('|',$val['category_id']);
															if(is_array($catidArr) && sizeof($catidArr)>0)
															{
																$val['category_id'] = $catidArr[0];
															}
															$prodUrl = getProductUrl($val['product_id'],$val['product_price_id'],$val['product_alias'],$val['category_id']);
															
															$price = "";
															if($val['product_discount'] != 0)
																$price = '<span class="tovar_price chan_curr tovar_view_price_old fleft">'.lp($val['product_price_calculated_price'],2,true).'</span>&nbsp;
																									  <span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															else
																$price = '<span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															
															$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
															
														
															/**
															 * product stock validation added on 23-04-2015
															 */
															$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] );
															
															//($key + 1 ) %4 == 0
															?>
															<div class="row-item">    
																<div class="ltabs-item new-ltabs-item item">
																	<div class="item-inner">
																		<div class="box-image item-image">
																			<div class="effect-default">
																				<a href="<?php echo $prodUrl ?>" title="Simas jarema" class="product-image">
																					<img id="product-collection-image-925" src="<?php echo product_load_image( $product_images[$val['product_angle_in']] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>" style="width: 270px; height: 255px;" />
																				</a>
																			</div>
																		</div>
																		<div class="box-info">
																			<h2 class="product-name">
																				<a href=""<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>"><?php echo $key.char_limit($val['product_name'],MANUFACTURER_ID == 7 ? 20 : 32);?></a>
																			</h2>
																			<div class="item-review">
																				<div class="ratings">
																					<div class="rating-box">
																						<div class="rating" style="width:100%"></div>
																					</div>
																					<p class="rating-links">
																						<a href="review/product/list/id/925/index.html">1 Review(s)</a>
																					</p>
																				</div>
																			</div>
																			<div class="bs-price">
																				<div class="sale-price">
																					<div class="price-box">
																						<span class="regular-price" >
																							<span class="price"><?php echo lp($val['product_discounted_price'],2,true);?></span>                                    
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="actions">
																				<button type="button" title="Add to Cart" class="button btn-cart<?php echo (($is_out_of_stock)? ' hide' : '')?>" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')">
																					<i class="fa fa-shopping-cart"></i>
																				</button>
																				<ul class="add-to-links">
																					<li>
																						<a title="Add to Wishlist" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" class="link-wishlist"></a>
																					</li>
																					<li class="hide">
																						<span class="separator">|</span>
																						<a title="Add to Compare" href="catalog/product_compare/add/product/925/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9/form_key/tamEEQVYAjMzOa7h/index.html" class="link-compare">                                    </a>
																					</li>
																				</ul>
																			</div>
																			<!-- <div class="other-infor"> </div> -->
																		</div>
																	</div>
																</div>
															</div> 
															<?php
														}
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="ltabs-wrap ">
								<div class="tab-listing-title" style="margin-bottom: -70px;">
									<h2>FEATURE PRODUCTS</h2>
								</div>
								<div class="ltabs-tabs-container" data-delay="200" data-duration="200" data-effect="bounceIn" data-ajaxurl="http://demo.flytheme.net/themes/sm_stationery/listingtabs/index/ajax" data-modid="sm_listing_tabs_6740590521501112828">
									<div class="ltabs-tabs-wrap">
										<span class='ltabs-tab-selected'></span>
										<span class='ltabs-tab-arrow'>&#9660;</span>
										<ul class="ltabs-tabs cf">
											<li class="ltabs-tab   tab-sel tab-loaded hide" data-category-id="top_rating" data-active-content=".items-category-top_rating">
												<span class="ltabs-tab-label"> Top Rating					</span>
											</li>
											<li class="ltabs-tab  hide " data-category-id="lastest_product" data-active-content=".items-category-lastest_product">
												<span class="ltabs-tab-label"> Latest Products					</span>
											</li>
											<li class="ltabs-tab hide " data-category-id="created_at" data-active-content=".items-category-created_at">
												<span class="ltabs-tab-label"> Created Date					</span>
											</li>
										</ul>
									</div>
								</div>
								<?php $fpArr = $this->jew->getProducts( staticCategoryIDs( "featured-products" )  );?>
								<div class="ltabs-items-container show-slider"><!--Begin Items-->
									<div class="products-grid">
										<div class="ltabs-items ltabs-items-selected ltabs-items-loaded items-category-top_rating">
											<div class="ltabs-items-inner ltabs00-3 ltabs01-3 ltabs02-3 ltabs03-2 ltabs04-1 bounceIn">
												<?php 
												if(isset($fpArr["data"]["result_array"]) && sizeof($fpArr["data"]["result_array"])>0)
												{
													foreach($fpArr["data"]["result_array"] as $key=>$val)
													{
														if($key < 3 )
														{
															$catidArr = explode('|',$val['category_id']);
															if(is_array($catidArr) && sizeof($catidArr)>0)
															{
																$val['category_id'] = $catidArr[0];
															}
															$prodUrl = getProductUrl($val['product_id'],$val['product_price_id'],$val['product_alias'],$val['category_id']);
															
															$price = "";
															if($val['product_discount'] != 0)
																$price = '<span class="tovar_price chan_curr tovar_view_price_old fleft">'.lp($val['product_price_calculated_price'],2,true).'</span>&nbsp;
																									  <span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															else
																$price = '<span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
															
															$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
															
														
															/**
															 * product stock validation added on 23-04-2015
															 */
															$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] );
															
															//($key + 1 ) %4 == 0
															?>
															<div class="row-item">    
																<div class="ltabs-item new-ltabs-item item">
																	<div class="item-inner">
																		<div class="box-image item-image">
																			<div class="effect-default">
																				<a href="<?php echo $prodUrl ?>" title="Simas jarema" class="product-image">
																					<img id="product-collection-image-925" src="<?php echo product_load_image( $product_images[$val['product_angle_in']] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>" style="width: 270px; height: 255px;" />
																				</a>
																			</div>
																		</div>
																		<div class="box-info">
																			<h2 class="product-name">
																				<a href=""<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>"><?php echo $key.char_limit($val['product_name'],MANUFACTURER_ID == 7 ? 20 : 32);?></a>
																			</h2>
																			<div class="item-review">
																				<div class="ratings">
																					<div class="rating-box">
																						<div class="rating" style="width:100%"></div>
																					</div>
																					<p class="rating-links">
																						<a href="review/product/list/id/925/index.html">1 Review(s)</a>
																					</p>
																				</div>
																			</div>
																			<div class="bs-price">
																				<div class="sale-price">
																					<div class="price-box">
																						<span class="regular-price" >
																							<span class="price"><?php echo lp($val['product_discounted_price'],2,true);?></span>                                    
																						</span>
																					</div>
																				</div>
																			</div>
																			<div class="actions">
																				<button type="button" title="Add to Cart" class="button btn-cart<?php echo (($is_out_of_stock)? ' hide' : '')?>" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')">
																					<i class="fa fa-shopping-cart"></i>
																				</button>
																				<ul class="add-to-links">
																					<li>
																						<a title="Add to Wishlist" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" class="link-wishlist"></a>
																					</li>
																					<li class="hide">
																						<span class="separator">|</span>
																						<a title="Add to Compare" href="catalog/product_compare/add/product/925/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9/form_key/tamEEQVYAjMzOa7h/index.html" class="link-compare">                                    </a>
																					</li>
																				</ul>
																			</div>
																			<!-- <div class="other-infor"> </div> -->
																		</div>
																	</div>
																</div>
															</div> 
															<?php
														}
													}
												}
												?>
											</div>
										</div>
									</div>
								</div>
							</div>
					
					</div>
				</div>						
			</div>
		</div>
		<div class="row">
			<?php $this->load->view('elements/brand-slider')?> 
		</div>           
	</div>
</div>