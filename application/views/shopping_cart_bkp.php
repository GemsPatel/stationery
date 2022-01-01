		<!-- SHOPPING BAG BLOCK -->
		<section class="shopping_bag_block">
			
			<!-- CONTAINER -->
			<div class="container">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- CART TABLE Gautam Change code -->
					<?php
						$grand_total = 0.0;
						$is_prod = false;
			        	if(is_array(@$cart_prod) && sizeof($cart_prod)>0):
					?>
						<div class="col-lg-9 col-md-9 padbot40">
							
							<table class="shop_table">
								<thead>
									<tr>
										<th class="product-thumbnail"></th>
										<th class="product-name">Item</th>
                                        <?php if($this->session->userdata('lType') == 'PC'):?>
											<th class="product-price">Price</th>
                                        <?php endif; ?>
										<th class="product-quantity">Quantity</th>
										<th class="product-subtotal">Total</th>
										<th class="product-remove"></th>
									</tr>
								</thead>
								<tbody>
								<?php		
									$is_prod = true;
									foreach($cart_prod as $k=>$ar):

										if(isset($ar['not_available'])):			
								?>
									        <tr valign="top">
									            <td colspan="5"><?php echo $ar['not_available']; ?></td>
									            <td class="product-remove"><a onclick="removeProduct(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;"><span>Delete</span> <i>X</i></a></td>
											</tr>
						        <?php
						        		elseif( empty($ar['type']) || $ar['type'] == 'prod' ):
						        		
							        		/**
							        		 * product stock validation added on 23-04-2015
							        		 */
							        		$is_out_of_stock = isProductOutOfStock( $ar["product_id"], $ar["inventory_type_id"] );
						        		
											$prod_url = getProductUrl($ar['product_id'],$k);
						        		
								?>
											<tr class="cart_item">
												<td class="product-thumbnail">
													<a href="<?php echo $prod_url; ?>">
														<img title="<?php echo $ar['product_name']; ?>" alt="<?php echo $ar['product_name']; ?>" src="<?php echo load_image(@$ar['product_images'][$ar['product_angle_in']]);?>" width="100">
													</a>
												</td>
												<td class="product-name">
													<a href="<?php echo $prod_url; ?>"><?php echo pgTitle($ar['product_name']); ?></a>
													<ul class="variation">
														<?php
															/**
															 * Cloudwebs On 04-06-2015
															 * Color attribute had been remove for all inventory, needs to be added if client requires it
															 */
															//<li class="variation-Color">Color: <span><?php echo $ar['pa_value_cs'];</span></li>
														?>
															<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
														<?php
														?>
													</ul>
                                                    <?php
	                                                    /**
	                                                     * Cloudwebs On 18-05-2015
	                                                     * removed use of config table for label, and fetched the label from language file.
	                                                     */
                                                    	//$outstok = getField( "config_value", "configuration", "config_key", "SOLD_OUT" );
                                                    	echo ($is_out_of_stock)? '<a class="btn_sold_out">'.getLangMsg("outstokc").'</a>' : '';
                                                    ?>	
												</td>
												
                                                <?php
												if($this->session->userdata('lType') == 'PC'):
												?>
                                                <td class="product-price"><?php echo lp($ar['product_discounted_price']);?></td>
                                                <?php endif;
												?>
			
												<td class="product-quantity">
													<?php
													if($this->session->userdata('lType') != 'PC')
														echo "<b>Price:</b> ".lp( $ar['product_discounted_price'] )."<br><div align='center'>X</div>";
													
													if( hewr_isQtyInAttributeInventoryCheckWithId($ar["inventory_type_id"]) ):
														echo form_dropdown( 'qty_'.$ar['product_price_id'], 
																		getProdQtyOptions( $ar["product_id"], $ar["product_generated_code_info"] ), 
																		$cartArr[$customer_id][$k]['qty'],' id="qty_'.$ar['product_price_id'].'" class="open-project tovar_view qty_box cart_qty custom" onchange="updateQty(this.value,'.$k.','.$customer_id.',\'\')" ' );
														
													else:
													?>
														<select name="product_qty-<?php echo $k; ?>" onchange="updateQty(this.value,<?php echo $k ?>,<?php echo $customer_id ?>, '<?php echo $cartArr[$customer_id][$k]['ring_size'] ?>')">
														<?php
															for($i=1;$i<=10;$i++):
														?>
																<option <?php echo (($cartArr[$customer_id][$k]['qty']==$i)?'selected="selected"':'') ?> value="<?php echo $i ?>" ><?php echo $i;?></option>
																<?php
													         endfor;
													         ?>
													     </select>
													<?php 
													endif;	
													?>
												</td>
												
												<td class="product-subtotal">
													<?php echo lp($ar['product_discounted_price'] * $cartArr[$customer_id][$k]['qty']);
														$grand_total += $total_prod = round( $ar['product_discounted_price'] * $cartArr[$customer_id][$k]['qty'], 0 );
														$disc_span = "";
										            	if($ar['product_discount']!=0):
															$disc_span = '<span class="throght-out-font chan_curr"><br>'.lp($ar['product_price_calculated_price']).'</span>';
										            	endif;
													?>
												</td>
			
												<td class="product-remove"><a onclick="removeProduct(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;"><span>Delete</span> <i>X</i></a></td>
											</tr>
										<?php 
										elseif( $ar['type'] == 'cz' ):
										elseif( $ar['type'] == 'dia' ):
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
									<h3><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp;<?php echo getLangMsg("cemp");?></h3>
								</div>
						<?php 
							endif;
						?>
						<!-- //CART TABLE Gautam Change code-->
					
					
					<!-- SIDEBAR -->
					<div id="sidebar" class="col-lg-3 col-md-3 padbot50">
						
						<!-- BAG TOTALS -->
						<div class="sidepanel widget_bag_totals">
							<h3><?php echo getLangMsg("bttl");?></h3>
							<table class="bag_total" id="check_tbl">
								<tr class="cart-subtotal clearfix">
									<th><?php echo getLangMsg("sttl");?></th>
									<td><?php echo lp($grand_total);?></td>
								</tr>                                
                                <!--<tr class="shipping clearfix">
									<th><?php //echo getLangMsg("ship");?></th>
									<td><?php //echo getLangMsg("free");?></td>
								</tr>-->
								<tr class="total clearfix">
									<th><?php echo getLangMsg("ttl").":";?></th>
									<td><?php echo lp($grand_total) ?></td>
							    </tr>
							</table>
							<!-- Gautam change by 09/Apr/15 -->
							<form class="coupon_form" method="post">
						        <input type="text" value="" placeholder="<?php echo getLangMsg("hac");?>" name="coupon" id="coupon_in" class="button" />
						        <input type="button" value="Apply" name="submit" class="button1" onclick="applyCoupon()" />
						        <span id="coupon_msg" style="color:green;"></span>
						    </form>
						    <!-- //Gautam -->
							<!-- <form class="coupon_form" action="javascript:void(0);" method="get" >
								<input type="text" id="coupon_in" name="coupon" value="" placeholder="Have a coupon?" onFocus="if (this.value == 'Have a coupon?') this.value = '';" onBlur="if (this.value == '') this.value = 'Have a coupon?';" />
								<span id="coupon_msg" style="color:green;"></span>
								<input type="submit" value="Apply" onclick="applyCoupon()">
							</form>-->
							<?php
					      		if($is_prod):
					        ?>
						    <a class="btn active" onclick="javascript:document.location.href= '<?php echo site_url('checkout') ?>';" >Checkout</a>
							<?php 
								endif;
							?>
							<a class="btn inactive" onclick="javascript:document.location.href= '<?php echo site_url('search') ?>';" >Continue shopping</a>
						</div><!-- //REGISTRATION FORM -->
					</div><!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //SHOPPING BAG BLOCK -->
		
