<?php
$grand_total = 0.0;
$is_prod = false;
?>
<div class="main">
	<div class="col-main">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div id="map-popup" class="map-popup" style="display:none;">
						<a href="#" class="map-popup-close" id="map-popup-close">x</a>
						<div class="map-popup-arrow"></div>
						<div class="map-popup-heading">
							<h2 id="map-popup-heading"></h2>
						</div>
						<div class="map-popup-content" id="map-popup-content">
							<div class="map-popup-checkout">
								<form action="" method="POST" id="product_addtocart_form_from_popup">
									<input type="hidden" name="product" class="product_id" value="" id="map-popup-product-id">
									<div class="additional-addtocart-box"></div>
									<button type="button" title="Add to Cart" class="button btn-cart" id="map-popup-button">
										<span><span>Add to Cart</span></span>
									</button>
								</form>
							</div>
							<div class="map-popup-msrp" id="map-popup-msrp-box">
								<strong>Price:</strong> 
								<span style="text-decoration:line-through;" id="map-popup-msrp"></span>
							</div>
							<div class="map-popup-price" id="map-popup-price-box">
								<strong>Actual Price:</strong> 
								<span id="map-popup-price"></span>
							</div>
							<script type="text/javascript">
								document.observe("dom:loaded", Catalog.Map.bindProductForm);
							</script>
						</div>
						<div class="map-popup-text" id="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> 
							You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.
						</div>
						<div class="map-popup-text" id="map-popup-text-what-this">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. 
							<br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.
						</div>
					</div>
					<?php 
					if(is_array(@$cart_prod) && sizeof($cart_prod)>0)
					{
						?>
						<div class="cart">
						    <div class="page-title title-buttons">
						        <h1>Shopping Cart</h1>
						    </div>
							<!-- <form action="" method="post">
								<input name="form_key" type="hidden" value="BktltjSDUdH2MJNC"> -->
								<div class="overflow-table">
									<table id="shopping-cart-table" class="data-table cart-table">
										<colgroup>
											<col width="1">
											<col>
											<col width="1">
	                                        <col width="1">
	                                        <col width="1">
											<col width="1">
	                                        <col width="1">
										</colgroup>
										<thead>
											<tr class="first last">
						                        <th rowspan="1">&nbsp;</th>
						                        <th rowspan="1"><span class="nobr">Product Name</span></th>
						                        <th rowspan="1"></th>
												<th class="a-center" colspan="1"><span class="nobr">Unit Price</span></th>
						                        <th rowspan="1" class="a-center">Qty</th>
						                        <th class="a-center" colspan="1">Subtotal</th>
						                        <th rowspan="1" class="a-center">&nbsp;</th>
						                    </tr>
	                                    </thead>
										<tfoot>
						                    <tr class="first last">
						                        <td colspan="50" class="a-right last">
													<button type="button" title="Continue Shopping" class="button btn-continue" onclick="javascript:document.location.href= '<?php echo site_url('search') ?>';">
														<span><span>Continue Shopping</span></span>
													</button>
													<button type="submit" name="update_cart_action" value="update_qty" title="Update Shopping Cart" class="button btn-update hide">
														<span><span>Update Shopping Cart</span></span>
													</button>
													<button type="submit" name="update_cart_action" value="empty_cart" title="Clear Shopping Cart" class="button btn-empty hide" id="empty_cart_button">
														<span><span>Clear Shopping Cart</span></span>
													</button>
												</td>
											</tr>
										</tfoot>
										<tbody>
											<?php 
											$is_prod = true;
											foreach($cart_prod as $k=>$ar)
											{
												if( empty( $ar['type'] ) || $ar['type'] == 'prod' )
												{
													$is_out_of_stock = isProductOutOfStock( $ar["product_id"], $ar["inventory_type_id"] );
													$prod_url = getProductUrl($ar['product_id'],$k);
													?>
													<tr class="first last odd">
														<td>
															<a href="<?php echo $prod_url; ?>" title="<?php echo $ar['product_name']; ?>" class="product-image">
																<img src="<?php echo load_image(@$ar['product_images'][$ar['product_angle_in']]);?>" alt="<?php echo $ar['product_name']; ?>">
															</a>
														</td>
														<td>
															<h2 class="product-name">
																<a href=""><?php echo pgTitle( $ar['product_name'] ); ?></a>
															</h2>
														</td>
														<td class="a-center">
															<a href="<?php echo $prod_url; ?>" title="Edit item parameters">Edit</a>
														</td>
														<td class="a-right">
															<span class="cart-price">
																<span class="price"><?php echo lp($ar['product_discounted_price']);?></span>                
															</span>
														</td>
														<!-- inclusive price starts here -->
														<td class="a-center">
															<!-- <input name="cart[5644][qty]" value="1" size="4" title="Qty" class="input-text qty" maxlength="12"> -->
															<select name="product_qty-<?php echo $k; ?>" onchange="updateQty(this.value,<?php echo $k ?>,<?php echo $customer_id ?>, '<?php echo $cartArr[$customer_id][$k]['ring_size'] ?>')">
																<?php
																for($i=1;$i<=10;$i++)
																{ ?>
																	<option <?php echo (($cartArr[$customer_id][$k]['qty']==$i)?'selected="selected"':'') ?> value="<?php echo $i ?>" class="input-text qty" ><?php echo $i;?></option>
																<?php } ?>
															</select>
														</td>
														<!--Sub total starts here -->
														<td class="a-right">
															<span class="cart-price">
																<span class="price"><?php echo lp( $ar['product_price_calculated_price'] );?></span>                            
															</span>
														</td>
														<td class="a-center last">
															<a onclick="removeProduct(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;" title="Remove item" class="btn-remove btn-remove2">Remove item</a>
														</td>
													</tr>
													<?php 
												}
											}
											?>
										</tbody>
									</table>
									<script type="text/javascript">decorateTable('shopping-cart-table')</script>
								</div>
							<!-- </form> -->
							<div class="cart-collaterals">
								<div class="row">
									<div class="col-1 col-lg-4 col-md-4 col-sm-6 col-xs-12"></div>
									<div class="col-1 col-lg-4 col-md-4 col-sm-6 col-xs-12">
										<form id="discount-coupon-form" method="post" class="coupon_form">
											<div class="discount">
												<h2>Discount Codes</h2>
												<div class="discount-form">
													<label for="coupon_code">Enter your coupon code if you have one.</label>
													<input type="hidden" name="remove" id="remove-coupone" value="0">
													<div class="input-box">
														<input type="text" value="" name="coupon" id="coupon_in" class="input-text" />
													</div>
													<div class="buttons-set">
														<button type="button" title="Apply Coupon" class="button" onclick="discountForm.submit(false); applyCoupon()" value="Apply Coupon">
															<span><span>Apply Coupon</span></span>
														</button>
														<span id="coupon_msg" style="color:green;"></span>
													</div>
												</div>
											</div>
										</form>
										<script type="text/javascript">
											var discountForm = new VarienForm('discount-coupon-form');
											discountForm.submit = function (isRemove) 
											{
											    if (isRemove) {
											        $('coupon_in').removeClassName('required-entry');
											        $('remove-coupone').value = "1";
											    } else {
											        $('coupon_in').addClassName('required-entry');
											        $('remove-coupone').value = "0";
											    }
											    return VarienForm.prototype.submit.bind(discountForm)();
											}
										</script>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 total-wrapper">
										<div class="totals">
											<table id="shopping-cart-totals-table">
												<colgroup>
													<col>
													<col width="1">
												</colgroup>
												<tfoot>
													<tr>
														<td style="" class="a-right" colspan="1">
															<strong>Grand Total</strong>
														</td>
														<td style="" class="a-right">
															<strong><span class="price"><?php echo lp($grand_total);?></span></strong>
														</td>
													</tr>
												</tfoot>
												<tbody>
													<tr>
														<td style="" class="a-right" colspan="1">Subtotal </td>
														<td style="" class="a-right">
															<span class="price"><?php echo lp($grand_total);?></span>    
														</td>
													</tr>
												</tbody>
											</table>
											<ul class="checkout-types">
												<li>    
													<button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout btn-checkout" onclick="javascript:document.location.href= '<?php echo site_url('checkout') ?>';">
														<span><span>Proceed to Checkout</span></span>
													</button>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php
					}
					else 
					{
						?>
						<div class="page-title">
							<h1>Shopping Cart is Empty</h1>
						</div>
						<div class="cart-empty">
							<p>You have no items in your shopping cart.</p>
							<p>Click <a href="<?php echo site_url('search') ?>">here</a> to continue shopping.</p>
						</div>
						<?php 
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>