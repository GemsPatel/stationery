<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=modal]').facebox()
})
</script>
<style type="text/css">
.list td.td-no-bottom-border
{
	border-bottom: 0 solid #DDDDDD;
}
.list td.td-top-border
{
	border-top: 1px solid #DDDDDD;
}
</style>
    <!--- Items Ordered --->
    <div id="tab-general" style="display: block;">
        <fieldset >
        <?php 
        	$para = (isset($_GET['custid'])?'&custid='.$_GET['custid']:'');
			$para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'&edit=true':''; 
			$para .= (isset($_GET['item_id']))?(($para!="")?'&':'').'item_id='.$_GET['item_id']:''; 
		?>
            <legend>
            	<?php
            		if( empty( $this->cPrimaryId )  ):
            	?>
            			Step 1: Items Ordered&nbsp;&nbsp;<a class="button" style="float:right;margin-top:-6px;" href="<?php echo site_url('admin/'.$this->controller.'?prod=list'.$para.'');?>">Add/Edit Products</a>
            	<?php
            		else:
            	?>
            			Update Order Status&nbsp;&nbsp;
            	<?php
            		endif;
            	?>		
            </legend>
            
            <table class="list">
                <thead>
                  <tr id="heading_tr" style="cursor:pointer;">
                    <td width="6%" class="left">Product Name</td>
                    <td width="5%" class="left">Product SKU</td>
                    <td width="6%" class="left">Code</td>
                    <td width="26%" class="center">Status</td>
                    
                    <?php
              			if( isSupportsJewelleryInventory() ): 
              		?>
		              		<td width="11%" class="center">Final Weight</td>
		              		<td width="4%" class="center">Ring Size</td>
		            <?php
		            	endif;
		            ?>  		
		              		
                    <td width="4%" class="right">Price</td>
                    <td width="4%" class="center">Qty</td>
                    <td width="5%" class="right">Total</td>
                    <td width="2%" class="center">Action</td>
                  </tr>
                </thead>
                <tbody>
                <?php
					$is_prod = false;
					if( isset($cart_prod) && is_array($cart_prod) && sizeof($cart_prod) > 0):	
					  foreach($cart_prod as $k=>$ar):
						$is_prod = true;
						if( isset($ar['not_available']) ):
				?>
    		              <tr id="<?php echo $k; ?>" class="products"><td colspan="10" class="center"><?php echo $ar['not_available'];?></td></tr>
	            <?php
						elseif( empty($ar['type']) || $ar['type'] == 'prod' ):
				?>      
                          <tr id="<?php echo $k; ?>" class="products">
                            <?php
//                                 $prod_link = getProductUrl($ar['product_id'], $ar['product_price_id']);
				                $prevURL = urlAppendParameter(getProductUrl($ar['product_id'], $ar['product_price_id']), "is_preview=1");
				            ?>
                            <td class="left"><a href="<?php echo $prevURL;?>" target="_blank" ><?php echo char_limit( $ar['product_name'], 15 );?></a></td>
                            <td class="left"><?php echo $ar['product_sku'];?></td>
                            <td class="left"><?php echo $ar['product_generated_code_displayable'];?></td>
                            <td class="center" >
								<?php
									if(isset($ar['order_details_id']) && (int)$ar['order_details_id'] != 0):
										$customer_access_validation_token = GetCustomerToken( $customer_id );

										$resSta = executeQuery(' SELECT order_status_id, order_tracking_comment, order_tracking_number FROM order_tracking 
																WHERE order_details_id='.$ar['order_details_id'].' 
																ORDER BY order_tracking_id DESC 
																LIMIT 1 ');
										$sql = "SELECT order_status_id, order_status_name FROM order_status WHERE order_status_status=0 ORDER BY order_status_sort_order";
										$trackArr = getDropDownAry($sql, "order_status_id", "order_status_name", array('' => "Select Status"), false);
										echo form_dropdown('order_status_id_'.$ar['order_details_id'], $trackArr, @$resSta[0]['order_status_id'], ' style="width:100px;" id="order_status_id_'.$ar['order_details_id'].'"'); 
								?>
		                                <input type="text" placeholder="Tracking URL" id="order_tracking_number_<?php echo $ar['order_details_id']?>" name="order_tracking_number_<?php echo $ar['order_details_id']?>" value="<?php echo ( !empty($resSta[0]['order_tracking_number']) ? $resSta[0]['order_tracking_number'] : "" );?>" size="12" style="margin-left:5px;margin-right:5px;"/>
		                                <input type="text" placeholder="Comment" id="order_tracking_comment_<?php echo $ar['order_details_id']?>" name="order_tracking_comment_<?php echo $ar['order_details_id']?>" value="<?php echo ( !empty($resSta[0]['order_tracking_comment']) ? $resSta[0]['order_tracking_comment'] : "" );?>" size="12" style="margin-left:5px;margin-right:5px;"/>
		                                
		                                <?php
		                                	if( $this->input->get('act') == 'upd_sta' ):
		                                ?>
		                                        <a class="button" onclick="updateStatus('<?php echo _en($this->cPrimaryId)?>', <?php echo $ar['order_details_id'] ?>, 0);" style="margin-right:5px;">Update</a>
		                                        <a class="button" target="_blank" href="<?php echo site_url("account/order-tracking?oid="._en($this->cPrimaryId)).'&acc='.$customer_access_validation_token?>" style="float:right;">Track</a>
		                                <?php
		                                	endif;
		                                ?>        
                                <?php
                                	endif;
								?>
                            </td>
                            
                            
                            <?php
		              			if( isSupportsJewelleryInventory() ): 
		              		?>
		                            <td class="center">
		                            	<?php
		                            		if( !empty( $this->cPrimaryId ) && $this->input->get('act') == 'upd_sta' ):
		                            	?>
				                            	<input type="text" id="product_final_weight_<?php echo $ar['order_details_id']?>" name="product_final_weight_<?php echo $ar['order_details_id']?>" value="<?php echo $ar['product_final_weight']?>" style="width: 50px;" placeholder="Product Final Weight"/>
				                                <a class="button" onclick="updateFinalWeight(<?php echo $ar['order_details_id'] ?>);" style="margin-right:5px;">Update</a>
				                        <?php
				                        	endif;
				                        ?>        
		                            </td>
		                            
		                            <td class="center" >
		                            	<?php
		                            		if( !empty( $this->cPrimaryId ) )
		                            		{
		                            			echo $ar['order_details_ring_size'];
		                            		}	
		                            	?>
		                            </td>
				            <?php
				            	endif;
				            ?>  		
                            
                            
                            <td class="right" >
                            <?php 
								$prodPrice = ($this->cPrimaryId) ? ($ar['order_details_amt']) : ($ar['product_discounted_price']);
								echo lp($prodPrice);
							?>
                            </td>
                            <td class="center"><?php echo $ar['qty']?></td>
                            <?php
                                $product_total_price = $prodPrice * $ar['qty'];
                            ?>
                            <td class="right" id="td_subtot_<?php echo $k; ?>" ><?php echo lp($product_total_price);?></td>
                            <td class="center">
                            
                            <?php
                            	if( empty( $this->cPrimaryId ) ):
                            ?>
		                            <select id="action_<?php echo $k; ?>" name="action_<?php echo $k; ?>" onchange="removeProduct(this.value)">
		                                <option value="">Select</option>
		                                <option value="<?php echo $k?>" >Remove</option>
		                            </select>
		                    <?php
		                    	endif;
		                    ?>        
                            </td>
                          </tr>
                 <?php
						elseif( $ar['type'] == 'dia' ):
						$diamondUrl = diamondUrl($ar['d_detail'][$k]['diamond_price_id']);
				 ?>
                 		<tr id="<?php echo $k; ?>" class="products">
                            <td class="left"><a href="<?php echo $diamondUrl;?>" target="_blank" ><?php echo $ar['d_detail'][$k]['diamond_shape_name']?></a></td>
                            <td class="center"><?php echo $ar['d_detail'][$k]['dp_rapnet_lot_no']?></td>
                            <td class="center"><?php echo $ar['d_detail'][$k]['dp_rapnet_lot_no']?></td>
                            <td class="center">
                            	<?php
									if(isset($ar['order_details_id']) && (int)$ar['order_details_id'] != 0):
										$customer_access_validation_token = GetCustomerToken( $customer_id );

										$resSta = executeQuery(' SELECT order_status_id, order_tracking_comment, order_tracking_number FROM order_tracking 
																WHERE order_details_id='.$ar['order_details_id'].' 
																ORDER BY order_tracking_id DESC 
																LIMIT 1 ');
										$sql = "SELECT order_status_id, order_status_name FROM order_status WHERE order_status_status=0 ORDER BY order_status_sort_order";
										$trackArr = getDropDownAry($sql, "order_status_id", "order_status_name", array('' => "Select Status"), false);
										echo form_dropdown('order_status_id_'.$ar['order_details_id'], $trackArr, @$resSta[0]['order_status_id'], ' style="width:100px;" id="order_status_id_'.$ar['order_details_id'].'"'); 
								?>
		                                <input type="text" placeholder="Tracking ID" id="order_tracking_number_<?php echo $ar['order_details_id']?>" name="order_tracking_number_<?php echo $ar['order_details_id']?>" value="<?php echo ( !empty($resSta[0]['order_tracking_number']) ? $resSta[0]['order_tracking_number'] : "" );?>" size="12" style="margin-left:5px;margin-right:5px;"/>
		                                <input type="text" placeholder="Comment" id="order_tracking_comment_<?php echo $ar['order_details_id']?>" name="order_tracking_comment_<?php echo $ar['order_details_id']?>" value="<?php echo ( !empty($resSta[0]['order_tracking_comment']) ? $resSta[0]['order_tracking_comment'] : "" );?>" size="12" style="margin-left:5px;margin-right:5px;"/>
                                        <a class="button" onclick="updateStatus('<?php echo _en($this->cPrimaryId)?>', <?php echo $ar['order_details_id'] ?>, 0);" style="margin-right:5px;">Update</a>
                                        <a class="button" target="_blank" href="<?php echo site_url("account/order-tracking?oid="._en($this->cPrimaryId)).'&acc='.$customer_access_validation_token?>" style="float:right;">Track</a>
                                <?php
                                	endif;
								?>
                            </td>
                            <td class="">-</td>
                            <td class=""></td>
                            <td class="right"><?php echo lp($ar['d_detail'][$k]['dp_price']);?></td>
                            <td class="center"><?php echo $ar['qty'];?></td>
                         <?php 
						 	$total_prod = round($ar['d_detail'][$k]['dp_price']*$ar['qty'],0); 
						 ?>
                            <td class="right"><?php echo lp($total_prod);?></td>
                            <td class="center">
                            	<select id="action_<?php echo $k; ?>" name="action_<?php echo $k; ?>" onchange="removeProduct(this.value)">
                                    <option value="">Select</option>
                                    <option value="<?php echo $k?>" >Remove</option>
                                </select>
                            </td>
                          </tr>
                 <?php
						elseif( $ar['type'] == 'sol' ):
						$prod_url = site_url('solitaires/pickDesign?pid='.$k);
						$total_prod =0;
						
				 ?>
                 		<tr id="<?php echo $k; ?>" class="products">
                            <td class="left td-no-bottom-border">
                                <a href="<?php echo $prod_url;?>" target="_blank" ><?php echo pgTitle($ar['product_name']); ?></a>
                            </td>
                            <td class="center td-no-bottom-border">
                                <?php echo $ar['product_sku']?>
                            </td>
                            <td class="center td-no-bottom-border">
                                <?php echo $ar['product_generated_code']?>
                            </td>
                            <td class="center td-no-bottom-border">
                            	<?php
									if(isset($ar['order_details_id']) && (int)$ar['order_details_id'] != 0):
										$customer_access_validation_token = GetCustomerToken( $customer_id );

										$resSta = executeQuery(' SELECT order_status_id, order_tracking_comment, order_tracking_number FROM order_tracking 
																WHERE order_details_id='.$ar['order_details_id'].' 
																ORDER BY order_tracking_id DESC 
																LIMIT 1 ');
										$sql = "SELECT order_status_id, order_status_name FROM order_status WHERE order_status_status=0 ORDER BY order_status_sort_order";
										$trackArr = getDropDownAry($sql, "order_status_id", "order_status_name", array('' => "Select Status"), false);
										echo form_dropdown('order_status_id_'.$ar['order_details_id'], $trackArr, @$resSta[0]['order_status_id'], ' style="width:100px;" id="order_status_id_'.$ar['order_details_id'].'"'); 
								?>
		                                <input type="text" placeholder="Tracking ID" id="order_tracking_number_<?php echo $ar['order_details_id']?>" name="order_tracking_number_<?php echo $ar['order_details_id']?>" value="<?php echo @$resSta[0]['order_tracking_number']?>" size="12" style="margin-left:5px;margin-right:5px;"/>
		                                <input type="text" placeholder="Comment" id="order_tracking_comment_<?php echo $ar['order_details_id']?>" name="order_tracking_comment_<?php echo $ar['order_details_id']?>" value="<?php echo @$resSta[0]['order_tracking_comment']?>" size="12" style="margin-left:5px;margin-right:5px;"/>
                                        <a class="button" onclick="updateStatus('<?php echo _en($this->cPrimaryId)?>', <?php echo $ar['order_details_id'] ?>, 0);" style="margin-right:5px;">Update</a>
                                        <a class="button" target="_blank" href="<?php echo site_url("account/order-tracking?oid="._en($this->cPrimaryId)).'&acc='.$customer_access_validation_token?>" style="float:right;">Track</a>
                                <?php
                                	endif;
								?>
                            </td>
                            <td class="td-no-bottom-border"><input type="text" id="product_final_weight_<?php echo $ar['order_details_id']?>" name="product_final_weight_<?php echo $ar['order_details_id']?>" value="<?php echo $ar['product_final_weight']?>" style="width: 130px;" placeholder="Product Final Weight"/>
                                <a class="button" onclick="updateFinalWeight(<?php echo $ar['order_details_id'] ?>);" style="margin-right:5px;">Update</a>
                            </td>
                            <td class="td-no-bottom-border"><?php echo $ar['order_details_ring_size']?></td>
                            <td class="right td-no-bottom-border">
								<?php 
									$prodPrice = ($this->cPrimaryId) ? ($ar['order_details_amt']) : ($ar['product_discounted_price']);
									echo lp($prodPrice);
								?>
                            </td>
                            <td class="center td-no-bottom-border">
                            	<?php echo $ar['qty'];?>
                            </td>
                         <?php 
							$total_prod += $product_total_price = $prodPrice * $ar['qty'];
						 ?>
                            <td class="right td-no-bottom-border">
                                <?php echo lp($product_total_price);?>
                            </td>
                            <td class="center td-no-bottom-border">
                            	<select id="action_<?php echo $k; ?>" name="action_<?php echo $k; ?>" onchange="removeProduct(this.value)">
                                    <option value="">Select</option>
                                    <option value="<?php echo $k?>" >Remove</option>
                                </select>
                            </td>
                          </tr>
                    	
                <?php
						foreach( $ar['d_detail'] as $no=>$dArr ):
							$diamondUrl = diamondUrl($dArr['diamond_price_id']);
				?>
                            <tr id="<?php echo $k; ?>" class="products">
                                <td class="left td-no-bottom-border">
                                    <a href="<?php echo $diamondUrl;?>" target="_blank" ><?php echo $dArr['diamond_shape_name']; ?></a><br />
                                </td>
                                <td class="center td-no-bottom-border">
                                    <?php echo $dArr['dp_rapnet_lot_no']?><br />
                                </td>
                                <td class="center td-no-bottom-border">
                                    <?php echo $dArr['dp_rapnet_lot_no']?><br />
                                </td>
                                <td class="center td-no-bottom-border"><!-- --></td>
                                <td class="td-no-bottom-border"><!-- --></td>
                                <td class="td-no-bottom-border"><!-- --></td>
                                <td class="right td-no-bottom-border"><?php echo lp($dArr['dp_price']);?></td>
                                <td class="center td-no-bottom-border">1</td>
                           <?php 
                           		$total_prod += $dArr['dp_price']; 
                           ?>
                                <td class="right td-no-bottom-border"><?php echo lp($dArr['dp_price']);?></td>
                                <td class="center td-no-bottom-border"><!-- --></td>
                           </tr>
				<?php
                       endforeach; //end loop solitaire
				?>
                           <tr id="<?php echo $k; ?>" class="products">
                                <td class="left td-top-border"><!-- --></td>
                                <td class="center td-top-border"><!-- --></td>
                                <td class="center td-top-border"><!-- --></td>
                                <td class="center td-top-border"><!-- --></td>
                                <td class="td-top-border"><!-- --></td>
                                <td class="td-top-border"><!-- --></td>
                                <td class="right td-top-border"><?php echo lp($total_prod);?></td>
                                <td class="center td-top-border">1</td>
                                <td class="right td-top-border"><?php echo lp($total_prod);?></td>
                                <td class="center td-top-border"><!-- --></td>
                           </tr>
                <?php
					
						endif;
                      endforeach;
                    else:
                ?>
                  <tr><td class="center" colspan="13" >No ordered items</td></tr>
                <?php
                    endif;
                ?>         
                </tbody>
                
                <?php
                    if( $is_prod ):
                ?>
                    <tfoot id="product_footer">
                        <tr>
                        
                        	<?php
                        		//this if condition only balance number of columns affected due to jewellery inventory variables
                        		if( isSupportsJewelleryInventory() ):
                        	?>	
		                            <td class="left"></td>
		                            <td class="center"></td>
							<?php
								endif;
							?>                            
                            
                            <td class="center" colspan="6">
							<?php
                                if( !empty( $this->cPrimaryId ) && $this->input->get('act') == 'upd_sta' ):
                                
									$sql = "SELECT order_status_id, order_status_name FROM order_status WHERE order_status_status=0 ORDER BY order_status_sort_order";
									$trackArr = getDropDownAry($sql, "order_status_id", "order_status_name", array('' => "Select Status"), false);
									echo form_dropdown('order_status_id', $trackArr, 0, ' style="width:100px;" id="order_status_id" ');

							?>
		                                <input type="text" placeholder="Tracking ID" id="order_tracking_number" name="order_tracking_number" value="" style="margin-left:5px;margin-right:5px;"/>
		                                <input type="text" placeholder="Comment" id="order_tracking_comment" name="order_tracking_comment" value="" style="margin-left:5px;margin-right:5px;"/>
                                        <input type="checkbox" name="is_email" value="1" id="is_email" /><label for="is_email" style="margin-right:5px;">Send Email/SMS</label>
                                        <a class="button" onclick="updateStatus('<?php echo _en($this->cPrimaryId)?>', 0, 1);" style="margin-right:5px;">Update All</a>
                                        <a class="button" target="_blank" href="<?php echo site_url("account/order-tracking?oid="._en($this->cPrimaryId)).'&acc='.$customer_access_validation_token?>" >Track</a>

							<?php
                                endif;
							?>
                            </td>
                            <td class="right" colspan="1">Total <?php echo $order_total_qty?> Items</td>
                            <td class="right" colspan="1"><?php echo lp($order_subtotal_amt); ?></td>
                        </tr>
                <?php
					if( isset($coupon_id) && (int)$coupon_id!=0 ):
                ?>
                        <tr id="tr_coupon">
                            <?php
                        		//this if condition only balance number of columns affected due to jewellery inventory variables
                        		if( isSupportsJewelleryInventory() ):
                        	?>	
		                            <td class="right" colspan="7"></td>
		                    <?php
		                    	else: 
		                    ?>       
		                    		<td class="right" colspan="5"></td>
		                    <?php
		                    	endif;
		                    ?> 
		                            
                            <td class="right" colspan="2">Coupon Discount: </td>
                            <td class="right"><?php echo lp($order_discount_amount); ?></td>
                        </tr>  
                        
                        <tr id="tr_coupon">
                        	<?php
                        		//this if condition only balance number of columns affected due to jewellery inventory variables
                        		if( isSupportsJewelleryInventory() ):
                        	?>	
		                            <td class="right" colspan="7"></td>
		                    <?php
		                    	else: 
		                    ?>       
		                    		<td class="right" colspan="5"></td>
		                    <?php
		                    	endif;
		                    ?> 
		                            
                            <td class="right" colspan="2">Total: </td>
                            <td class="right"><?php echo lp($order_total_amt); ?></td>
                        </tr>  
                <?php
                    endif;
                ?>
                
                    </tfoot>
                <?php
                    endif;
                ?>
              </table>

        </fieldset>
    </div>

<script type="text/javascript">
var edit = <?php echo json_encode((isset($_GET['edit']) && $_GET['edit']=="true")?'true':' '); ?>;
var reorder = <?php echo json_encode((isset($_GET['reorder']) && $_GET['reorder']=="true")?'true':'false'); ?>;

function removeProduct(pid)
{
	if(typeof pid !== 'undefined' && pid!='' && pid!='0')
	{
		form_data = { pid : pid, custid : custid };
		remProductAdmin(form_data);
		setTimeout(function(){window.location.reload();},1000);
	}
}

/**
 * @author Cloudwebs
 * @abstract function will update status of ordered products e.g. in manufacture, shipping etc.
 */
 function updateStatus(order_id, order_details_id, is_all)
 {
	var loc = (base_url+'admin/sales_order/updateOrderStatus');
	var is_email = 0;
	if($('#is_email').is(':checked'))
	{
		is_email = 1;	
	}
	
	
	if(is_all == 1)
	{
		var sta_id = $('#order_status_id').val();
		var track_no = $('#order_tracking_number').val();
		var track_com = $('#order_tracking_comment').val();
	}
	else
	{
		var sta_id = $('#order_status_id_'+order_details_id).val();
		var track_no = $('#order_tracking_number_'+order_details_id).val();
		var track_com = $('#order_tracking_comment_'+order_details_id).val();
	}

	/**
	 * order status
	 */
	if(sta_id == '')
	{
		alert('Please select status');
		return false;	
	}
	showLoader();
	
	form_data = {sta_id : sta_id, track_no : track_no, track_com : track_com, order_id : order_id, order_details_id : order_details_id, 
				 is_email : is_email, is_all : is_all};
	$.post(loc, form_data, function (data)
	{
		var arr = $.parseJSON(data);
		if(arr['type']=='success')
		{
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
		else 
		{
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
		hideLoader();
	});
	
 }
 
/**
 * @author Cloudwebs
 * @abstract function will update final weight of ordered products i.e. the weight of product when it was actually manufactured
 */
 function updateFinalWeight(order_details_id)
 {
	showLoader();
	var loc = (base_url+'admin/sales_order/updateFinalWeight');
	
	var final_weight = $('#product_final_weight_'+order_details_id).val();
	
	form_data = {order_details_id : order_details_id, final_weight : final_weight};
	$.post(loc, form_data, function (data)
	{
		var arr = $.parseJSON(data);
		if(arr['type']=='success')
		{
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
		else 
		{
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		}
	});

	hideLoader();
 }
 
</script>