<?php 
if(isset($listArr) && sizeof($listArr)>0):
	foreach($listArr as $key=>$val):
			
		$catidArr = explode("|",$val['category_id']);
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

// 								$imagefolder = getProdImageFolder( $val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info'] ); 
// 								$product_images = fetchProductImages( $imagefolder );			//images for particular selection
		$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
								
		/**
		 * product stock validation added on 23-04-2015
		 */
		$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] ); 
?>
						
								<div class="tovar_wrapper col-lg-4 col-md-4 col-sm-6 col-xs-6 col-ss-12 padbot20" itemscope itemtype="http://schema.org/Product">
									<div class="tovar_item clearfix<?php echo (($is_out_of_stock)? ' sold_out' : '')?>">
										<div class="tovar_img">
											<div class="tovar_img_wrapper">
												<a itemprop="url" href="<?php echo $prodUrl ?>"><img itemprop="image" src="<?php echo load_image( $product_images[ $val["product_angle_in"] ] )?>" alt="<?php echo $val["product_name"];?>" title="<?php echo $val["product_name"];?>" /></a>
											</div>
											<div class="tovar_item_btns">
												<?php
													/**
													 * Cloudwebs: instead of hiding the qty block,
													 * Condition added on 11-05-2015, to only load qty drop down
													 * if produuct is in stock.
													 *
													 */
													if( !$is_out_of_stock ):
														//<?php echo (($is_out_of_stock)? ' hide' : '')
												?>
														<div class="dis-in-block">
															<?php
																/**
																 * only show qunatity option if qty is handled as product inventory attribute 
																 * 
																 */
																if( hewr_isQtyInAttributeInventoryCheckWithId( $val["inventory_type_id"] ) )
																{
																	echo form_dropdown( 'qty_'.$val['product_price_id'],
																			getProdQtyOptions( $val["product_id"], $val["product_generated_code_info"] ),
																			"",' id="qty_'.$val['product_price_id'].'" class="open-project tovar_view qty_box" ');
																}
															?>
			                                            </div>
		                                        <?php
		                                        	endif;  	
		                                        ?>  
		                                            
	                                            <a class="add_bag<?php echo (($is_out_of_stock)? ' hide' : '')?>" href="javascript:void(0);" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo ( isset($__this->cz) ? $__this->cz : "" );?>')" title="Add to cart">
	                                            	<i class="fa fa-shopping-cart"></i>
	                                            </a>
												<a class="add_bag" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)" title="Add to wishlist"><i class="fa fa-heart"></i></a>
											</div>
										</div>
										<div class="tovar_description clearfix">
											<a class="tovar_title" href="<?php echo $prodUrl;?>" itemprop="name"><?php echo char_limit($val["product_name"],30)?></a>
											<span class="tovar_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><?php echo $price;?></span>
										</div>
										<!-- <div class="tovar_content"></div> -->
									</div>
								</div>
							
<?php
	endforeach;
endif;
?>
