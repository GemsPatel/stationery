		
		<!-- ACCOUNT PAGE -->
		<section class="faq_page">
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
						
                        <!-- LEAVE A COMMENT -->
                        <form method="post" action="">
						<div id="comment_form" data-appear-top-offset='-100' data-animated='fadeInUp'>
							<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?></h2>
                            <?php
                            	$grand_total = 0.0;
                                $is_prod = false;
                                if(is_array(@$wish_prod) && sizeof($wish_prod)>0):
                            ?>
                            <table class="shop_table type1">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail"></th>
                                        <th class="product-name"><?php echo getLangMsg("item");?></th>
                                        <th class="product-subtotal"><?php echo getLangMsg("price");?></th>
                                        <th class="product-remove center"><?php echo getLangMsg("action")?></th>
                                    </tr>
                                </thead>
                                <?php			
                                	$is_prod = true;
                                    foreach($wish_prod as $k=>$ar):
                                    	if(isset($ar['not_available'])):
								?>
								<tr valign="top" style="border-bottom: 1px dotted #582802;">
									<td colspan="6"><?php echo $ar['not_available'];?></td>
								</tr>
								<?php
										else:
											$prod_url = getProductUrl($ar['product_id'],$k);
								?>
                                <tbody id="wishlist-row-<?php echo $k; ?>">
                                    <tr class="cart_item">
                                        <td class="product-thumbnail center">
                                        	<a href="<?php echo $prod_url; ?>">
												<img title="<?php echo $ar['product_name']; ?>" alt="<?php echo $ar['product_name']; ?>" src="<?php echo load_image(@$ar['product_images'][$ar["product_angle_in"]]);?>" height="100" width="80">
											</a>
										</td>
                                        <td class="product-name"><a href="<?php echo $prod_url; ?>"><?php echo $ar['product_name']; ?>
                                        	<ul class="variation">
												<?php
													if(  $ar["inventory_type_id"]  == "3"):
														if( hewr_isGroceryInventoryCheckWithId( $ar["inventory_type_id"] ) ):
													?>
															<li class="variation-Color">Color: <span><?php echo $ar['pa_value_cs'];?></span></li>
															<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
													<?php
														endif;
													else:
													?>
															<li class="variation-Color">Color: <span><?php echo $ar['pa_value_cs'];?></span></li>
															<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
													<?php
													endif;
												?>		
											</ul>
                                        </td>
                                        
                                        <td class="product-price"><?php echo lp($ar['product_discounted_price']); ?></td>
                                        
                                        <td class="product-remove">
                                        	<a onclick="addProduct(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;"><i class="fa fa-shopping-cart"></i></a>&nbsp;
                                           
                                            <a onclick="removeWishlist(<?php echo $k; ?>,<?php echo $customer_id; ?>)" style="cursor:pointer;"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                                <?php
                                		endif;													
                                	endforeach;
                                ?>
                            </table>
							<?php
                             	else:
							?>
                            <!-- <table>
                              	<tr>
    					           	 <td class="name">Wish list is empty!</td>                
	                            </tr>
                            </table>-->
                            <h3 class="product-price cart_item"><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp;<?php echo getLangMsg("n_wish");?> </h3>
                            <?php
                               	endif;
							?>
                         
						</div><!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        
