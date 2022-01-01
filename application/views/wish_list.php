		<!-- LOVE LIST BLOCK -->
		<section class="love_list_block">
			
			<!-- CONTAINER -->
			<div class="container">
				<!-- ROW -->
				<div class="row">
					
					<!-- CART TABLE -->
					<!-- Gautam Kakadiya Change Code -->
					<?php
						$grand_total = 0.0;
						$is_prod = false;
			        	if(is_array(@$wish_prod) && sizeof($wish_prod)>0):
					?>
							<div class="col-lg-9 col-md-9 padbot40">
								<table class="shop_table">
									<thead>
										<tr>
											<th class="product-thumbnail"></th>
											<th class="product-name">Item</th>
											<th class="product-price">Price</th>
											<th class="product-add-bag"></th>
											<th class="product-remove"></th>
										</tr>
									</thead>
									<tbody>
										<?php			
											$is_prod = true;
											foreach($wish_prod as $k=>$ar):
												/**
												 * product stock validation added on 23-04-2015
												 */
												$is_out_of_stock = isProductOutOfStock( $ar["product_id"], $ar["inventory_type_id"] );
												
												if(isset($ar['not_available'])):
												?>
												<tr valign="top" style="border-bottom: 1px dotted #582802;">
										            <td colspan="6"><?php echo $ar['not_available'];?></td>
										            <td class="product-remove"><a onclick="removeWishlist(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;"><span>Delete</span> <i>X</i></a></td>
												</tr>
										        <?php
							        			else:
													$prod_url = getProductUrl($ar['product_id'],$k);
													
													$angle_in = $ar['product_angle_in'];
													
												?>
												<tr class="cart_item">
													<td class="product-thumbnail">
														<a href="<?php echo $prod_url; ?>">
															<img title="<?php echo $ar['product_name']; ?>" alt="<?php echo $ar['product_name']; ?>" src="<?php echo load_image(@$ar['product_images'][$angle_in]);?>" height="100" width="80">
														</a>
													</td>
													<td class="product-name">
														<a href="<?php echo $prod_url; ?>"><?php echo pgTitle($ar['product_name']); ?></a>
														<ul class="variation">
															<?php
																if(  $ar["inventory_type_id"]  == "3"):
																	if( hewr_isGroceryInventoryCheckWithId( $ar["inventory_type_id"] ) ):
																	/**
																	 * Cloudwebs On 04-06-2015
																	 * Color attribute had been remove for all inventory, needs to be added if client requires it
																	 */
																	//<li class="variation-Color">Color: <span><?php echo $ar['pa_value_cs'];</span></li>
																?>
																		<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
																<?php
																	endif;
																else:
																?>
																		<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
																<?php
																endif;
															?>															
														</ul>
													</td>
				
													<td class="product-price chan_curr"><?php echo lp($ar['product_discounted_price']);?> </td>
				
													<td class="product-add-bag"><a class="add_bag cursor<?php echo (($is_out_of_stock)? ' hide' : '')?>" onclick="addProduct(<?php echo $k;?>,false)"><i class="fa fa-shopping-cart"></i><span><?php echo getLangMsg("atb");?></span></a>
                                                    
													<?php
													 	/**
													 	 * Cloudwebs On 18-05-2015
													 	 * removed use of config table for label, and fetched the label from language file. 
													 	 */
														//$outstok = getField( "config_value", "configuration", "config_key", "SOLD_OUT" );
														echo ($is_out_of_stock) ? '<a class="btn_sold_out">'.getLangMsg("outstokw").'</a>' : '';
													?>	
                                                    
                                                    </td>
				
													<td class="product-remove"><a class="cursor" onclick="removeWishlist(<?php echo $k; ?>,<?php echo $customer_id; ?>)"><span>Delete</span> <i>X</i></a></td>
												</tr>
												<?php 
												endif;
											endforeach;
										?>
									</tbody>
								</table>
							</div>
					<?php 
						else:
					?>
							<div class="col-lg-9 col-md-9 padbot40">
								<h3><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp; <?php echo getLangMsg("wemp");?></h3>
							</div>
					<?php 
						endif;
					?>
					
                    <div id="sidebar" class="col-lg-3 col-md-3">
                    <?php $this->load->view('elements/ads-verticle');?>
                    </div>
                    
                    
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //LOVE LIST BLOCK -->