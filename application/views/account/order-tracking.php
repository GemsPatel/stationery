		<!-- ACCOUNT PAGE -->
		<section class="faq_page">
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div id="comment_form" class="col-lg-12 col-md-12 col-sm-12 padbot30">
                    	<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?></h2>
                    	<?php
                    		if(is_array(@$listArr) && sizeof($listArr)>0):
	                    		$items = 0;
                    			
	                    		if(isset($order_details['data']['qtyTot']))
	                    			$items = $order_details['data']['qtyTot'];
	                    	?>
	                    	<div class="col-lg-6 col-md-6 col-sm-6 padbot30">
	                        	<div class="text_iframe">
	                            	<p><?php echo getLangMsg("o_sumery");?></p>
	                                <div class="billing_information_content">
	                                    <span><b><?php echo getLangMsg("order_no");?>: </b> <?php echo $listArr[0]['order_id'];?></span>
	                                    <span><b><?php echo getLangMsg("dt");?>: </b> <?php echo formatDate("d-m-Y <b>h:i:s A</b>",$listArr[0]['order_created_date']);?></span>
	                                    <span><b><?php echo getLangMsg("ttl");?>: </b> <?php echo lp($listArr[0]['order_total_amt']);?></span>
	                                </div>
	                            </div>
	                        </div>
	                        <?php
								if(isset($customer_shipping_address)  && is_array($customer_shipping_address) && sizeof($customer_shipping_address)>0):
							?>
			                        <div class="col-lg-6 col-md-6 col-sm-6 padbot30">
			                        	<div class="text_iframe">
			                            	<p><?php echo getLangMsg("shipadd");?></p>
			                                <div class="billing_information_content">
			                                    <span><b><?php echo $customer_shipping_address['customer_address_firstname']." ".$customer_shipping_address['customer_address_lastname'];?></b></span>
			                                    <span><?php echo $customer_shipping_address['customer_address_address'];?></span>
			                                    <span><?php echo $customer_shipping_address['cityname'].", ".$customer_shipping_address['state_name'].", <b>".$customer_shipping_address['country_name']."</b>";?></span>
                                                <span><?php echo $customer_shipping_address['customer_address_phone_no'];?></span>
			                                </div>
			                            </div>
			                        </div>
			                     <?php 
                            	endif;
                          
                            ?>
                            
                        <!-- LEAVE A COMMENT -->
                        <h3><i class="fa fa-desktop"></i>&nbsp; <?php echo getLangMsg("t_detail");?> </h3>
                        <?php 
                        	foreach ($order_details['data']['data_order'] as $j=>$val):
                        		$prodUrl = getProductUrl($val['product_id'], $val['product_price_id']);
                       		 ?>
                        		<h3 class="order_track_product_info">
                                	Product: <span><a href="<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>"><?php echo $val["product_name"];?></a></span>, &nbsp;
                                    Qty: <span><?php echo $val['qty']; ?></span>
                                    <br>
                                    <?php
                                    	$trackKey = doTracking($order_details['data']['order_tracking'][$val['order_details_id']]);
                                    	
                                    	$order_status_msg = "";
                                    	if( !empty( $trackKey['order_tracking_number'] ) )
                                    	{
                                    		if( !empty( $trackKey['shipping_method_url'] ) )
                                    		{
                                    			$method_url = $trackKey['shipping_method_url'];
                                    			 
                                   				$pos = strpos( strtolower($method_url), "http://");
                                   				if( $pos === FALSE )
                                   				{
                                   					$method_url = "http://".$method_url;
                                   				}
                                   				?>
                               					Click <a style="cursor:pointer;" href="<?php echo $method_url?>" target="_blank"><b>here</b></a> to track your product and tracking URL is <b><?php echo $trackKey['order_tracking_number'];?>
                                   			    <?php 
                                    		}
                                    		else
                                    		{
                                    			$tracking_url = $trackKey['order_tracking_number'];
                                    			
                                    			$pos = strpos( strtolower($tracking_url), "http://");
                                    			if( $pos === FALSE )
                                    			{
                                    				$tracking_url = "http://".$trackKey['order_tracking_number'];
                                    			}
                                    			
                                    			if( isValidUrl( $tracking_url ) )
                                    			{
                                    				?>
													Click <a style="cursor:pointer;" href="<?php echo $tracking_url;?>" target="_blank" ><b>here</b></a> to track your product
                                    				<?php 
                                    			}
                                    			else
                                    			{
                                    				?> Click <a style="cursor:pointer;" onclick="showPopUpNotification('success','Shipping information is not available.');" ><b>here</b></a> to track your product <?php
                                    			}
                                    		}
                                    	}
                                    	else 
                                    	{
                                    		?> 
                                    		Click <a style="cursor:pointer;" onclick="showPopUpNotification('success','Your product is not shipped yet!');" ><b>here</b></a> to track your product 
                                    		<?php 
                                    	}
                                    ?>
                                </h3>
								<div data-appear-top-offset='-100' data-animated='fadeInUp'>
									<table class="shop_table type1">
		                            	<thead>
		                            		<tr>
		                            			<th class="product-thumbnail center"></th>
			                                    <th class="product-name"><?php echo getLangMsg("status");?></th>
			                                    <th class="product-quantity"><?php echo getLangMsg("dt");?></th>
			                                </tr>
			                            </thead>
			                            <tbody>
			                            <?php 
			                            	foreach ($order_details['data']['order_tracking'][$val['order_details_id']] as $l=>$value):
			                            	?>
			                            		<tr class="cart_item toggle-btn">
					                           		<td class="product-thumbnail center">
                                                    	<img alt="<?php echo $value["order_status_name"];?>" title="<?php echo $value["order_status_name"];?>" src="<?php echo load_image($value["order_status_icon"]);?>" width="25">
                                                        </td>
							                        <td class="product-name"><?php echo $value["order_status_name"];?></td>
							                        <td class="product-quantity"><?php echo formatDate("d-m-Y <b>h:i:s A</b>",$value['order_tracking_created_date']);?></td>
							                    </tr>       
						                    <?php 
						                    endforeach;
						                 ?>                             
					                    </tbody>
			                                
			                        </table>
		                           
								</div>
							<?php
							endforeach;
						endif;
						?>
						
						<!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
		
<!--  -->
<?php 
function doTracking($res)
{
	$track = array();
	if(!empty($res)):
		foreach($res as $k=>$ar):
		
			if( $ar['order_status_key'] == "YET_TO_SHIP")
			{
				//$track['order_status_key'] = $ar['order_status_key'];
				$track['order_tracking_number'] = $ar['order_tracking_number'];
				$track['shipping_method_url'] = $ar['shipping_method_url'];
				$track['shipping_method_name'] = $ar['shipping_method_name'];
			}
							
		endforeach;
	endif;
	
	return $track;
	
}
?>