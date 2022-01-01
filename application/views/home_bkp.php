        
        <?php 
        	$this->load->view('elements/home-slider');  
        	
        	$fpArr = $this->jew->getProducts( staticCategoryIDs( "featured-products" )  );
        ?>
        
        
        <!-- TOVAR SECTION -->
		<section class="tovar_section">
			
			<!-- CONTAINER -->
			<div class="container home_fp">
				<h2><?php echo getLangMsg("fp");?></h2>
				
				<!-- ROW -->
				<div class="row">
					
					<!-- TOVAR WRAPPER -->
					<div class="tovar_wrapper" data-appear-top-offset='-100' data-animated='fadeInUp'>
						
						<!-- TOVAR1 -->
						<?php 
							if(isset($fpArr["data"]["result_array"]) && sizeof($fpArr["data"]["result_array"])>0):
								
								foreach($fpArr["data"]["result_array"] as $key=>$val):
								
									if($key < 3):
								
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
										
// 									$imagefolder = getProdImageFolder( $val['product_generated_code'], $val['product_price_id'], $val["product_sku"] );
// 									$product_images = fetchProductImages( $imagefolder );			//images for particular selection
									$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
										
									
									/**
									 * product stock validation added on 23-04-2015
									 */
									$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] );
																		
						?>
										<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 col-ss-12 padbot40">
											<div class="tovar_item<?php echo (($is_out_of_stock)? ' sold_out' : '')?>"> <!-- class=sold_out -->
												<div class="tovar_img">
													<div class="tovar_img_wrapper">
														<a href="<?php echo $prodUrl ?>"><img src="<?php echo load_image( $product_images[$val['product_angle_in']] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>"/></a>
													</div>
													<div class="tovar_item_btns">
														<!-- <div class="dis-in-block">
															<a class="open-project tovar_view" href="%21projects/women/1.html" >quick view</a>
														</div>-->
														<div class="dis-in-block<?php echo (($is_out_of_stock)? ' hide' : '')?>">
															<?php
																/**
																 * only show qunatity option if product is of grocery inventory
																 */
																if( hewr_isGroceryInventoryCheckWithId( $val["inventory_type_id"] ) )
																{
																	echo form_dropdown( 'qty_'.$val['product_price_id'],
																			getProdQtyOptions( $val["product_id"], $val["product_generated_code_info"] ),
																			"",' id="qty_'.$val['product_price_id'].'" class="open-project tovar_view qty_box" ');
																}
															?>
														</div>
														<a class="add_bag<?php echo (($is_out_of_stock)? ' hide' : '')?>" href="javascript:void(0);" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')" title="Add to cart">
															<i class="fa fa-shopping-cart"></i>
														</a>
														<a class="add_bag" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" title="Add to wishlist">
															<i class="fa fa-heart"></i>
														</a>
													</div>                                                    
												</div>
												<div class="tovar_description clearfix">
												<a class="tovar_title" href="<?php echo $prodUrl;?>" ><?php echo char_limit($val['product_name'],MANUFACTURER_ID == 7 ? 15 : 32);?></a>
													<!-- <a class="tovar_title" href="<?php //echo site_url('home/testListing') ?>" ><?php //echo $val['product_name'];?></a>-->
													<span class="tovar_price"><?php echo lp($val['product_discounted_price'],2,true);?></span>
												</div>
											</div>
										</div>
								
						<?php
									endif;
								endforeach;
							endif;
						?>
					
						<!-- //TOVAR1 -->
						
												
						<div class="respond_clear_768"></div>
						
						<!-- BANNER -->
						<div class="col-lg-3 col-md-3 col-xs-6 col-ss-12">
							<?php 
								$artImg = fetchRow( "SELECT banner_name,banner_image,banner_link FROM banner WHERE banner_key='banner_one' LIMIT 1 " ); 
								
							?>
							<a class="banner type1 margbot30"  href="<?php echo site_url($artImg['banner_link'])?>">
								<img src="<?php echo asset_url($artImg['banner_image'])?>" alt="<?php echo $artImg['banner_name']?>" title="<?php echo $artImg['banner_name'];?>"/>
							</a>
<!-- 							<a class="banner type1 margbot30" href="javascript:void(0);" ><img src="images/tovar/banner1.jpg" alt="" /></a> -->
							<?php 
								$artImg = fetchRow( "SELECT banner_name,banner_image,banner_link FROM banner WHERE banner_key='banner_two' LIMIT 1 " ); 
								//echo $artRow['banner_image'];
							?>
							<a class="banner type2 margbot30"  href="<?php echo site_url($artImg['banner_link'])?>">
								<img src="<?php echo asset_url($artImg['banner_image'])?>" alt="<?php echo $artImg['banner_name']?>" title="<?php echo $artImg['banner_name'];?>"/>
							</a>
						</div><!-- //BANNER -->
					</div><!-- //TOVAR WRAPPER -->
				</div>
				
				<!-- //ROW -->
				
				
				<!-- ROW -->
				<div class="row">
					
					<!-- TOVAR WRAPPER -->
					<div class="tovar_wrapper" data-appear-top-offset='-100' data-animated='fadeInUp'>
						
						<!-- BANNER -->
						<div class="col-lg-3 col-md-3 col-xs-6 col-ss-12">
							<?php 
								$artImg = fetchRow( "SELECT banner_name,banner_image,banner_link FROM banner WHERE banner_key='banner_thrd' LIMIT 1 " ); 
								//echo $artRow['banner_image'];
							?>
							<a class="banner type3 margbot40" href="<?php echo site_url($artImg['banner_link'])?>" >
								<img src="<?php echo $artImg['banner_image']?>" alt="<?php echo $artImg['banner_name'];?>" title="<?php echo $artImg['banner_name'];?>"/>
							</a>
							
						</div><!-- //BANNER -->
						
						<div class="respond_clear_768"></div>
						
						<!-- TOVAR4 -->
						
						<?php 
							if(isset($fpArr["data"]["result_array"]) && sizeof($fpArr["data"]["result_array"])>0):
								
								foreach($fpArr["data"]["result_array"] as $key=>$val):
									if($key >= 3 && $key < 6):
									
									$catidArr = explode('|',$val['category_id']);
									if(is_array($catidArr) && sizeof($catidArr)>0)
									{
										$val['category_id'] = $catidArr[0];
									}
									$prodUrl = getProductUrl($val['product_id'],$val['product_price_id'],$val['product_alias'],$val['category_id']);
									
									$price = "";
									if($val['product_discount'] != 0)
									{
										$price = '<span class="tovar_price chan_curr tovar_view_price_old fleft">'.lp($val['product_price_calculated_price'],2,true).'</span>&nbsp;
																			  <span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
									
									}
									else
									{
										$price = '<span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
					
									}
// 									$imagefolder = getProdImageFolder( $val['product_generated_code'], $val['product_price_id'], $val["product_sku"] );
// 									$product_images = fetchProductImages( $imagefolder );			//images for particular selection
									$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
										
									/**
									 * product stock validation added on 23-04-2015
									 */
									$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] );
										
						?>
											<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 col-ss-12 padbot40">
												<div class="tovar_item clearfix<?php echo (($is_out_of_stock)? ' sold_out' : '')?>">
													<div class="tovar_img">
														<div class="tovar_img_wrapper">
															<a href="<?php echo $prodUrl ?>"><img src="<?php echo load_image( $product_images[$val['product_angle_in']] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>"/></a>
														</div>
														<div class="tovar_item_btns">
															<!-- <div class="dis-in-block">
																<a class="open-project tovar_view" href="%21projects/women/1.html" >quick view</a>
															</div>-->
															<div class="dis-in-block<?php echo (($is_out_of_stock)? ' hide' : '')?>">
																<?php
																	/**
																	 * only show qunatity option if product is of grocery inventory
																	 */
																	if( hewr_isGroceryInventoryCheckWithId( $val["inventory_type_id"] ) )
																	{
																		echo form_dropdown( 'qty_'.$val['product_price_id'],
																				getProdQtyOptions( $val["product_id"], $val["product_generated_code_info"] ),
																				"",' id="qty_'.$val['product_price_id'].'" class="open-project tovar_view qty_box" ');
																	}
																?>
															</div>
															<a class="add_bag<?php echo (($is_out_of_stock)? ' hide' : '')?>" href="javascript:void(0);" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')" title="Add to cart">
																<i class="fa fa-shopping-cart"></i>
															</a>
															<a class="add_bag" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" title="Add to wishlist">
																<i class="fa fa-heart"></i>
															</a>
														</div>
													</div>
													<div class="tovar_description clearfix">
														<a class="tovar_title" href="<?php echo $prodUrl ?>" ><?php echo char_limit($val['product_name'],MANUFACTURER_ID == 7 ? 15 : 32);?></a>
														<span class="tovar_price"><?php echo lp($val['product_discounted_price'],2,true);?></span>
													</div>
												</div>
											</div>
									
						<?php
										endif;
									
								endforeach;
							endif;
						?>
							
						
						<!-- //TOVAR4 -->
						
						
					</div><!-- //TOVAR WRAPPER -->
				</div><!-- //ROW -->
				
				
				<?php $this->load->view('elements/ads1') ?>
                
			</div><!-- //CONTAINER -->
		</section><!-- //TOVAR SECTION -->
		
		
		<!-- NEW ARRIVALS -->
        <section class="new_arrivals padbot50">
			<?php $this->load->view('elements/new-arrivals') ?>
        </section>
        <!-- //NEW ARRIVALS -->
		
		
		<!-- SERVICES SECTION -->
		<section class="services_section padbot30">
			
			<!-- CONTAINER -->
			<div class="container" data-appear-top-offset='-100' data-animated='fadeInUp'>
				
				<!-- ROW -->
				<div class="row">
					
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-ss-12">
						<div class="service_item">
							<div class="clearfix"><i class="fa fa-truck"></i><p>Fast Shipping</p></div>
						</div>
					</div>
						
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-ss-12">
						<div class="service_item">
							<div class="clearfix"><i class="fa fa-smile-o"></i><p>24 Hours Delivery</p></div>
						</div>
					</div>
						
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-ss-12">
						<div class="service_item">
							<div class="clearfix"><i class="fa fa-check-square-o"></i><p>Fresh Quality</p></div>
						</div>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-ss-12">
						<div class="service_item">
							<div class="clearfix"><i class="fa fa-credit-card"></i><p>Easy Return</p></div>
						</div>
					</div>
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //SERVICES SECTION -->