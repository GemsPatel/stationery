<?php $this->load->view('elements/breadcrumbs')?>
<div class="col-main">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div id="messages_product_view"></div>
				<div class="product-view product-view-3" itemscope itemtype="http://schema.org/Product">
					<div class="row">
						<div class="col-lg-9 col-md-9">
							<div class="view-wrapper">
								<div class="product-essential">
									<form action="http://demo.flytheme.net/themes/sm_stationery/checkout/cart/add/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9zdGF0aW9uZXJ5L3NpbWFzLWphcmVtYS5odG1s/product/925/form_key/i6sRJdXQG94lP5qV/" method="post" id="product_addtocart_form">
										<input name="form_key" type="hidden" value="i6sRJdXQG94lP5qV" />
										<?php
							        	if( isEmptyArr( $product_images ) )
										{
											$product_images = array('');
										}
										//__L/
										$largeImg = load_image( injectDirInImagePath($product_images[$product_angle_in], "", "assets/product/".$product_sku."/") );
										?>
										<div class="no-display">
											<input type="hidden" name="product" value="925" />
											<input type="hidden" name="related_product" id="related-products-field" value="" />
										</div>
										<div class="row">
											<div class="product-img-box col-lg-6 col-md-6 col-sm-6">
												<div class="">
													<div class="product-image product-image-zoom">
														<div class="product-image-gallery">
															<?php
															foreach ($product_images as $k=>$ar)
															{
															?>
																<img id="<?php echo ( $k==0 ) ? 'image-main' : 'image-'.$k ?>" class="gallery-image visible" src="<?php echo product_load_image( $product_images[$k] );?>" alt="<?php echo ucfirst($largeImg)?>" title="<?php echo ucfirst($largeImg)?>"/>
															<?php
															}
															?>	
															<div class="btn-lightbox">
																<?php
																foreach ($product_images as $k=>$ar)
																{
																?>
																	<a <?php echo ( $k==0 ) ? 'id="lightbox_btn"' : '' ?> data-placement="top" data-toggle="tooltip" title="Gallery Images" class="fancybox-buttons" data-fancybox-group="thumb" href="<?php echo product_load_image( $product_images[$k] );?>" data-fancybox-group="gallery"></a>
																<?php
																}
																?>
															</div>
														</div>
													</div>
													<div class="more-views">
														<ul class="product-image-thumbs">
															<?php
															foreach ($product_images as $k=>$ar)
															{
															?>
																<li>
																	<a class="thumb-link" href="javascript:void(0);" data-src-img="<?php echo product_load_image( $product_images[$k] );?>" title="" data-image-index="<?php echo $k;?>">
																		<img src="<?php echo product_load_image( $product_images[$k] );?>" alt=""/>
																	</a>
																</li>
															<?php
															}
															?>
														</ul>
													</div>
												</div>
											</div>
											<div class="product-shop col-lg-6 col-md-6 col-sm-6">
												<div class="product-name">
													<h2 itemprop="name"><?php echo $product_name;?></h2>
												</div>
												<div class="ratings" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
													<meta itemprop="ratingValue" content="5"/>
													<meta itemprop="reviewCount" content="1"/>
													<div class="rating-box">
														<div class="rating" style="width:100%"></div>
													</div>
													<p class="rating-links">
														<a href="http://demo.flytheme.net/themes/sm_stationery/review/product/list/id/925/">1 Review(s)</a>
														<span class="separator">|</span>
														<a href="http://demo.flytheme.net/themes/sm_stationery/review/product/list/id/925/#review-form">Add Your Review</a>
													</p>
												</div>
												<div class="short-description">
													<div class="std"><?php echo char_limit($product_short_description, 30)?></div>
												</div>
												<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
													<div class="price-box">
														<span class="regular-price" >
															<span itemprop="price" class="price"><?php echo lp($product_discounted_price,2,true)?></span>                                    
														</span>
													</div>
													<p class="availability in-stock">Availability: 
														<span>
															<link itemprop="availability" href="http://schema.org/InStock" /> 
															<?php
															if(isProductOutOfStock( $product_id, $inventory_type_id )):
																$sold_out = 1;
						                                       	echo getLangMsg("soldout");
				                                             else: 
				                                             	$sold_out = 0;
				                                        		echo getLangMsg("instock");
				                                            endif;
					                                        ?>			
														</span>
													</p>
												</div>
												<div style="clear:both"></div>	
												<div class="add-to-box">
													<div class="add-to-cart">
														<div class="control-qty">
															<span type="button" class="quantity-controls quantity-plus" onclick="$('qty').value=Number($('qty').value)+1" ></span>
															<input type="text" name="qty" id="qty" maxlength="12" value="<?php echo $qty?>" title="Qty" class="input-text qty" />
															<span type="button" class="quantity-controls quantity-minus" onclick="if(Number($('qty').value)>1){$('qty').value=Number($('qty').value)-1;}"></span>
														</div>
														<button type="button" title="Add to Cart" id="product-addtocart-button" class="button btn-cart" onclick="addProduct(0,true,pid);" class="add_bag<?php echo (($sold_out)? ' hide' : '')?>"><i class="fa fa-shopping-cart"></i><span>Add to Cart</span></button>
													</div>
													<ul class="add-to-links">
													    <li><a href="" title="Add to Wishlist" onclick="addWishList(0, pid)" class="link-wishlist">Add to Wishlist</a></li>
													    <li class="hide"><a href="http://demo.flytheme.net/themes/sm_stationery/catalog/product_compare/add/product/925/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9zdGF0aW9uZXJ5L3NpbWFzLWphcmVtYS5odG1s/form_key/i6sRJdXQG94lP5qV/" title="Add to Compare" class="link-compare">Add to Compare</a></li>
													</ul>
												</div>
											</div>
										</div>
										<div class="clearfix"></div>
									</form>
								</div>
								<div class="product-collateral">
									<div class="tab-product-info" id="tab-product-view">
										<?php 
										$res = executeQuery( " SELECT * FROM product_review where product_id=".$product_id." AND product_review_status = 0 " );
										$count = count($res);
										?>
										<ul class="resp-tabs-list">
											<li><?php echo getLangMsg("dtail")?></li>
											<li>Information</li>
											<li class="hide">Tags</li>
											<li><?php echo getLangMsg("rvu");?> (<?php if(isEmptyArr($res)):
														echo "0";
													else:
														echo $count;
													endif;?>)
											</li>
											<li class="hide">Custom Tab</li>
										</ul>
										<div class="resp-tabs-container">
											<div>
												<h2><?php echo getLangMsg("dtail");?></h2>
												<div class="std" itemprop="description">
													<?php echo $product_description;?> 
												</div>
											</div>
										<div>
											<h2><?php echo getLangMsg("pdtail");?></h2>
											<table class="data-table" id="product-attribute-specs-table">
												<col width="25%" />
												<col />
												<tbody>
													<?php
													foreach ($codeArr as $k=>$ar):
														if( $k >= 2 ):
															$tempA = explode(":", $ar);
														
															/**
															 * here $k stands for product_stone_number,
															 * minus it by 2 to reflect stone number in sequence.
															 */
															$k -= 2;
														
															$type = detailDiamondType( $k );
															if( $type === "dyn" )
															{
																$type = "ss".$k;
															}
															
															if( $tempA[1] === "JW_CS" || $tempA[1] === "JW_SS1" || $tempA[1] === "JW_SS2" || $tempA[1] === "JW_SSS" ):
																if( !empty( ${"diamond_type_name_".$type."_alias"} ) || !empty( ${"diamond_type_name_".$type} ) ):
											  					?>
	                                              					<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("type");?></th><td class="data"><?php echo ( isset( ${"diamond_type_name_".$type."_alias"} ) ? ${"diamond_type_name_".$type."_alias"} : ${"diamond_type_name_".$type} );?></td></tr>
                                              					<?php
	                                              				endif;
	                                              				if( !empty( ${"diamond_shape_name_".$type} ) ):
	                                             	 			?>					
	                                              					<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("shape");?></th><td class="data"><?php echo ${"diamond_shape_name_".$type}; ?></td></tr>
	                                              				<?php
	                                              				endif;
																
																if($diamond_type_key_cs=='DIAMOND'):
																	if( !empty( ${"diamond_purity_name_".$type} ) ):
												  					?>
												  						<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("purity");?></th><td class="data"><?php echo ${"diamond_purity_name_".$type}; ?></td></tr>
												  					<?php
												  					endif;
												  					if( !empty( ${"diamond_color_name_".$type} ) ):
											  						?>						
												  						<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("clr");?></th><td class="data"><?php echo ${"diamond_color_name_".$type}; ?></td></tr>
	                                              					<?php
	                                              					endif;
																endif;
			
																$no_of_pcs = ( $type === "cs" ? "product_center_stone_total" : "product_side_stone".$k."_total" );
																$tot_weight = ( $type === "cs" ? "product_center_stone_weight" : "product_side_stone".$k."_weight" );
																if( !empty( ${$no_of_pcs} ) ):
												  				?>
												  					<tr><th class="label"><?php echo $tempA[2]." No of pcs";?></th><td class="data" id="<?php echo $no_of_pcs;?>" ><?php echo ${$no_of_pcs}; ?></td></tr>
	                                              				<?php
	                                              				endif;
	                                              				
	                                              				if( !empty( ${$tot_weight} ) ):
	                                              				?>					
	                                              					<tr><th class="label"><?php echo $tempA[2]." Total Weight";?></th><td class="data" id="<?php echo $tot_weight;?>"><?php echo ${$tot_weight}; ?></td></tr>
												  				<?php 			
												  				endif;
												  				
															elseif( $tempA[1] === "SEL" || $tempA[1] === "CHK" || $tempA[1] === "RDO" ):
																
																if( !empty( ${"pa_value_".$type} ) ):
												  				?>
												  					<tr><th class="label"><?php echo $tempA[2];?></th><td class="data" id="pa_value_<?php echo $type;?>"><?php echo ${"pa_value_".$type}; ?></td></tr>
												  				<?php 	
												  				endif;	
												  				
															elseif( $tempA[1] === "JW_MTL" ):
												  			?>
												  				<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("type");?></th><td class="data" id="metal_name"><?php echo $metal_type_name; ?></td></tr>
												  				<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("purity");?></th><td class="data" id="metal_purity_name"><?php echo $metal_purity_name; ?></td></tr>
												  				<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("clr");?></th><td class="data" id="metal_color_name"><?php echo $metal_color_name; ?></td></tr>
												  				<tr><th class="label"><?php echo $tempA[2]." ".getLangMsg("weight");?></th><td class="data" id="product_metal_weight"><?php echo $product_metal_weight; ?></td></tr>
												  			<?php 			
												    	elseif( $tempA[1] === "TXT" ):
															$txt = ( $type == "cs" ? "product_center_stone_size" : "product_side_stone".$k."_size" );
												   			if( !empty( ${$txt} ) ):
												  			?>
											  					<tr><th class="label"><?php echo $tempA[2];?></th><td class="data" id="<?php echo $txt;?>"><?php echo ${$txt}; ?></td></tr>
	                                              					<li><span><b><?php echo $tempA[2];?>: </b> <span id="<?php echo $txt;?>"><?php echo ${$txt}; ?></span> </span></li>
	                                   						<?php
														endif; 			
	 												endif;
												endif;
											endforeach; 
	                                        ?>
												</tbody>
											</table>
											<script type="text/javascript">decorateTable('product-attribute-specs-table')</script>
										</div>
										<div class="hide">
											<div class="box-collateral box-tags">
												<h2>Product Tags</h2>
												<form id="addTagForm" action="http://demo.flytheme.net/themes/sm_stationery/tag/index/save/product/925/uenc/aHR0cDovL2RlbW8uZmx5dGhlbWUubmV0L3RoZW1lcy9zbV9zdGF0aW9uZXJ5L3NpbWFzLWphcmVtYS5odG1s/" method="get">
													<div class="form-add">
														<label for="productTagName">Add Your Tags:</label>
														<div class="input-box">
															<input type="text" class="input-text required-entry" name="productTagName" id="productTagName" />
														</div>
														<button type="button" title="Add Tags" class="button" onclick="submitTagForm()">
															<span>
																<span>Add Tags</span>
															</span>
														</button>
													</div>
												</form>
												<p class="note">Use spaces to separate tags. Use single quotes (') for phrases.</p>
											</div>
										</div>
										<div id="yt_tab_reviewform" class="tab-pane">
											<div class="box-collateral box-reviews" id="customer-reviews">
												<?php
													$dt["product_id"] = $product_id;
													$dt["res"] = $res;
													$this->load->view("elements/product_review", $dt);
												?>   
											</div>
											<div class="hide">
												<table class="data-table" style="width: 100%;" border="1">
													<tbody>
														<tr>
															<td>Brand</td>
															<td><img title="brand" src="http://demo.flytheme.net/themes/sm_stationery/media/wysiwyg/custom-tab/logo-client.jpg" alt="brand" /></td>
														</tr>
														<tr>
															<td>History</td>
															<td>Color sit amet, consectetur adipiscing elit. In gravida pellentesque ligula, vel eleifend turpis blandit vel. Nam quis lorem ut mi mattis ullamcorper ac quis dui. Vestibulum et scelerisque ante, eu sodales mi. Nunc tincidunt tempus varius. Integer ante dolor, suscipit non faucibus a, scelerisque vitae sapien.</td>
														</tr>
													</tbody>
												</table>								
											</div>
										</div>
									</div>
									<div class="row">
										<div class="policy-bottom">
											<div class="col-md-4 col-xs-12">
												<h2>Shipping policy</h2>
												<div class="content-policy">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
											</div>
											<div class="col-md-4 col-xs-12">
												<h2>Buying policy</h2>
												<div class="content-policy">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
											</div>
											<div class="col-md-4 col-xs-12">
												<h2>Return policy</h2>
												<div class="content-policy">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>
											</div>
										</div>
									</div>						
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-3" id="ytech_right">
							<div class="sidebar-view">
								<div class="box-related">
									<div class="overflow-slider">
										<?php
										if($this->session->userdata('lType') == 'PC')
										{
										  	$data['related_productsArr'] = @$related_productsArr;
											$data['related_links'] = @$related_links;
											$data['category_id'] = $category_id;
											$data['product_id'] = $product_id;
											$data['product_discounted_price'] = $product_discounted_price;
											$data['angle_in'] = $product_angle_in;
										  	$this->load->view('elements/related_products',$data);
										}
										?>
									</div>
								</div>
								<div class="box-up-sell">
									<div class="overflow-slider">
										<?php $this->load->view('elements/upsell_products');?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="map-popup" class="map-popup" style="display:none;">
					<a href="#" class="map-popup-close" id="map-popup-close">x</a>
					<div class="map-popup-arrow"></div>
					<div class="map-popup-heading"><h2 id="map-popup-heading"></h2></div>
					<div class="map-popup-content" id="map-popup-content">
						<div class="map-popup-checkout">
							<form action="#" method="POST" id="product_addtocart_form_from_popup">
								<input type="hidden" name="product" class="product_id" value="" id="map-popup-product-id" />
								<div class="additional-addtocart-box"> </div>
								<button type="button" title="Add to Cart" class="button btn-cart" id="map-popup-button"><span><span>Add to Cart</span></span></button>
							</form>
						</div>
						<div class="map-popup-msrp" id="map-popup-msrp-box"><strong>Price:</strong> <span style="text-decoration:line-through;" id="map-popup-msrp"></span></div>
						<div class="map-popup-price" id="map-popup-price-box"><strong>Actual Price:</strong> <span id="map-popup-price"></span></div>
						<script type="text/javascript">
							document.observe("dom:loaded", Catalog.Map.bindProductForm);
						</script>
					</div>
					<div class="map-popup-text" id="map-popup-text">Our price is lower than the manufacturer's &quot;minimum advertised price.&quot;  As a result, we cannot show you the price in catalog or the product page. <br /><br /> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
					<div class="map-popup-text" id="map-popup-text-what-this">Our price is lower than the manufacturer's &quot;minimum advertised price.&quot;  As a result, we cannot show you the price in catalog or the product page. <br /><br /> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
				</div>

				<script type="text/javascript">
				    var lifetime = 3600;
				    var expireAt = Mage.Cookies.expires;
				    if (lifetime > 0) {
				        expireAt = new Date();
				        expireAt.setTime(expireAt.getTime() + lifetime * 1000);
				    }
				    Mage.Cookies.set('external_no_cache', 1, expireAt);
				</script>
			</div>
		</div>
	</div>
</div>