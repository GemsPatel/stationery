<?php 

$content = '<tr>
            	<td colspan="2">
					<p style="margin-top: 10px; margin-bottom: 10px;">'.(isset($order_status_msg)? $order_status_msg : getLangMsg("tyo")).'</p>
					<p style="margin-top: 0px; margin-bottom: 10px;"><a href="'.site_url("account/order-tracking?oid="._en($order_id)).'&acc='.$customer_access_validation_token.'">View Order Status</a></p>
					<table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
						<thead>
							<tr>
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" >Billing Address</td>
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">Shipping Address</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b>Name: </b> '.$customer_name.'<br /> <b>Email: </b> '.$customer_emailid.'<br /> <b>Phone: </b> '.$customer_address_phone_no_bill.'<br /> <b>Address: </b> '.$customer_address_address_bill.'<br />  <b>Area: </b> '.$customer_address_landmark_area_bill.'<br /> <b>City: </b> '.$cityname_bill.'<br /> <b>Pincode: </b> '.$pincode_bill.'<br />'.$state_name_bill." ".$country_name_bill.'<br /> </td>
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b>Name: </b> '.$customer_name.'<br /> <b>Email: </b> '.$customer_emailid.'<br /> <b>Phone: </b> '.$customer_address_phone_no.'<br /> <b>Address: </b> '.$customer_address_address.'<br /> <b>Area: </b> '.$customer_address_landmark_area.'<br /> <b>City: </b> '.$cityname.'<br /> <b>Pincode: </b> '.$pincode.'<br /> '.$state_name." ".$country_name.'<br /> </td>
							</tr>
						</tbody>
					</table>
					<table style="border-collapse: collapse; width: 100%; border: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
						<thead>
							<tr>
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="3">Order Details</td>
							</tr>
						</thead>
						<tbody>
							<tr height="100" style="border-bottom:none;">
								<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;" colspan="3">
										<b>Order Id : '.$order_id.' </b>
												<a href="'.site_url("account/order-tracking?oid="._en($order_id)).'&acc='.$customer_access_validation_token.'">Track Order Status</a>
														<br /> 
										<b>Purchase Date: </b>'.$order_created_date.'
								'.(isset($payment_status)? '<br /> <b>Payment Status: </b>'.pgTitle($payment_status).'':'').'</td>
							</tr>	
							<tr>
							<td colspan="3">
							<table style="border-collapse: collapse; width: 640px; border-top-width: 1px; border-top-style: solid; border-top-color: #dddddd; height: 143px;">
								<thead>
									<tr>
										<td width="500" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Product Name</td>
										<td width="40" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 7px; color: #222222;">Qty</td>
										<td width="100" style="font-size: 12px; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: right; padding: 7px; color: #222222;">Price</td>
									</tr>
								</thead>
							<tbody>';
			
			foreach($cartArr[ $customer_id ] as $k=>$ar)
			{
				$ring_sizeOrg = "";
				if( isset($ar["order_details_ring_size"]) )
				{
					$ring_sizeOrg = $ar["order_details_ring_size"];
				}
				else if( isset($ar["ring_size"]) )
				{
					$ring_sizeOrg = $ar["ring_size"];
				}
				
				$ring_size = '';
				if( $ar['type'] == '' || $ar['type'] == 'prod' )
				{
					$prod_url = getProductUrl($cart_prod[$k]['product_id'], $k);
					
					$angle_in = $cart_prod[$k]["product_angle_in"];

					if($cart_prod[$k]['ring_size_region']=='Y')
					{
						if( $cart_prod[$k]['product_accessories']!= 'COU' )
						{
							$ring_size = '<br><small>Ring Size: '.$cart_prod[$k]['order_details_ring_size'].'</small>';
						}
						else
						{
							$ring_sizeArr = explode("|",$cart_prod[$k]['order_details_ring_size']);
							$ring_size = '<br><small>Ring Size Women: '.@$ring_sizeArr[0].'</small><br><small>Ring Size Men: '.@$ring_sizeArr[1].'</small>';
						}
					}
						
					$content .= '<tr>
									<td colspan="3">
										<table style="width: 100%; border-bottom: 1px dotted #DDDDDD; padding: 5px 0; font-size:14px;">
											<tbody>
												<tr>
													<td width="10%"><a href="'.$prod_url.'" ><img width="70" height="70" border="0" src="'.asset_url($cart_prod[$k]['product_images'][$angle_in]).'" /></a></td>
													<td width="67%" valign="top">
														<a href="'.$prod_url.'" >'.$cart_prod[$k]['product_name'].'</a><br><small>Item Code: '.$cart_prod[$k]['product_generated_code_displayable'].'</small>
														'.$ring_size.'
													</td>
													<td width="3%" valign="top">'.$ar['qty'].'</td>
													<td width="20%" valign="top" class="chan_curr" style="font-size: 12px; text-align: right; padding: 7px;">
															'.lp($cart_prod[$k]['product_discounted_price'] * $ar['qty'], 2, false, $currency_id).'
															'.( isIncludeChain($ring_sizeOrg) ? "<br>".chainPriceMsg() : "" ).'		
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>';
				}
				else if( $ar['type'] == 'cz' ) 
				{
					$prod_url = getProductUrl($cart_prod[$k]['product_id'], $k);
					
					$angle_in = $cart_prod[$k]["product_angle_in"];
					
					if($cart_prod[$k]['ring_size_region']=='Y')
					{
						if( $cart_prod[$k]['product_accessories']!= 'COU' ){
							$ring_size = '<br><small>Ring Size: '.$cart_prod[$k]['order_details_ring_size'].'</small>';
						} else {
							$ring_sizeArr = explode("|",$cart_prod[$k]['order_details_ring_size']);
							$ring_size = '<br><small>Ring Size Women: '.@$ring_sizeArr[0].'</small><br><small>Ring Size Men: '.@$ring_sizeArr[1].'</small>';
						}
					}
	
					$content .= '<tr>
									<td colspan="3">
										<table style="width: 100%; border-bottom: 1px dotted #DDDDDD; padding: 5px 0; font-size:14px;">
											<tbody>
												<tr>
													<td width="10%"><a href="'.$prod_url.'" ><img width="70" height="70" border="0" src="'.asset_url($cart_prod[$k]['product_images'][$angle_in]).'" /></a></td>
													<td width="67%" valign="top">
														<a href="'.$prod_url.'" >'.$cart_prod[$k]['product_name'].'</a><br><br><small>Item Code: '.$cart_prod[$k]['product_generated_code_displayable'].'</small>
														'.$ring_size.'
													</td>
													<td width="3%" valign="top">'.$ar['qty'].'</td>
													<td width="20%" valign="top" class="chan_curr" style="font-size: 12px; text-align: right; padding: 7px;">
															'.lp($cart_prod[$k]['product_discounted_price'] * $ar['qty'], 2, false, $currency_id).'
															'.( isIncludeChain($ar['ring_size']) ? "<br>".chainPriceMsg() : "" ).'		
													</td>
															
												</tr>
											</tbody>
										</table>
									</td>
								</tr>';
				}
				else if( $ar['type'] == 'sol' )
				{
					if( isset($cart_prod[$k]['d_detail']) && is_array($cart_prod[$k]['d_detail']) )
					{
						foreach($cart_prod[$k]['d_detail'] as $did=>$dArr)
						{
							$diaUrl = diamondUrl( $dArr['diamond_price_id'] );
							
							$content .= '<tr>
											<td colspan="3">
												<table style="width: 100%; padding: 5px 0; font-size:14px;">
													<tbody>
														<tr>
															<td width="10%"><a href="'.$diaUrl.'" ><img width="70" height="70" border="0" src="'.load_image($dArr['diamond_shape_icon']).'" /></a></td>
															<td width="67%" valign="top"><a href="'.$diaUrl.'" >'.$dArr['diamond_shape_name'].'</a><br><br><small>Item Code: '.$dArr['dp_rapnet_lot_no'].'</small></td>
															<td width="3%" valign="top">1</td>
															<td width="20%" valign="top" class="chan_curr" style="font-size: 12px; text-align: right; padding: 7px;">'.lp($dArr['dp_price'], 2, false, $currency_id).'</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>';
						}
					}


					$prod_url = getProductUrl($cart_prod[$k]['product_id'], $k);
					
					$angle_in = $cart_prod[$k]["product_angle_in"];

					if($cart_prod[$k]['ring_size_region']=='Y')
					{
						if( $cart_prod[$k]['product_accessories']!= 'COU' ){
							$ring_size = '<br><small>Ring Size: '.$cart_prod[$k]['order_details_ring_size'].'</small>';
						} else {
							$ring_sizeArr = explode("|",$cart_prod[$k]['order_details_ring_size']);
							$ring_size = '<br><small>Ring Size Women: '.@$ring_sizeArr[0].'</small><br><small>Ring Size Men: '.@$ring_sizeArr[1].'</small>';
						}
					}
	
					$content .= '<tr>
									<td colspan="3">
										<table style="width: 100%; border-bottom: 1px dotted #DDDDDD; padding: 5px 0; font-size:14px;">
											<tbody>
												<tr>
													<td width="10%"><a href="'.$prod_url.'" ><img width="70" height="70" border="0" src="'.asset_url($cart_prod[$k]['product_images'][$angle_in]).'" /></a></td>
													<td width="67%" valign="top">
														<a href="'.$prod_url.'" >'.$cart_prod[$k]['product_name'].'</a><br><br><small>Item Code: '.$cart_prod[$k]['product_generated_code_displayable'].'</small>
														'.$ring_size.'
													</td>
													<td width="3%" valign="top">'.$ar['qty'].'</td>
															
													<td width="20%" valign="top" class="chan_curr" style="font-size: 12px; text-align: right; padding: 7px;">
														'.lp($cart_prod[$k]['product_discounted_price'] * $ar['qty'], 2, false, $currency_id).'
														'.( isIncludeChain($ar['ring_size']) ? "<br>".chainPriceMsg() : "" ).'		
													</td>
															
												</tr>
											</tbody>
										</table>
									</td>
								</tr>';
				}
				else if( $ar['type'] == 'dia' ) 
				{
					if( isset($cart_prod[$k]['d_detail']) && is_array($cart_prod[$k]['d_detail']) )
					{
						$diaUrl = diamondUrl( $cart_prod[$k]['d_detail'][$k]['diamond_price_id'] );
						
						$content .= '<tr>
										<td colspan="3">
											<table style="width: 100%; border-bottom: 1px dotted #DDDDDD; padding: 5px 0; font-size:14px;">
												<tbody>
													<tr>
														<td width="10%"><a href="'.$diaUrl.'" ><img width="70" height="70" border="0" src="'.load_image($cart_prod[$k]['d_detail'][$k]['diamond_shape_icon']).'" /></a></td>
														<td width="67%" valign="top"><a href="'.$diaUrl.'" >'.$cart_prod[$k]['d_detail'][$k]['diamond_shape_name'].'</a><br><br><small>Item Code: '.$cart_prod[$k]['d_detail'][$k]['dp_rapnet_lot_no'].'</small></td>
														<td width="3%" valign="top">1</td>
														<td width="20%" valign="top" class="chan_curr" style="font-size: 12px; text-align: right; padding: 7px;">'.lp($cart_prod[$k]['d_detail'][$k]['dp_price'], 2, false, $currency_id).'</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>';
					}
				}
			}
					
	$content .= '</tbody>
					<tfoot>';
						
						if($order_discount_amount>0)
						{
							$content .= '<tr>
											<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="2"><b>Sub - Total:</b></td>
											<td style="font-size: 12px; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">'.lp($order_subtotal_amt, 2, false, $currency_id).'</td>
										</tr>
										<tr>
											<td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="2"><b>Discount:</b></td>
											<td style="font-size: 12px; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">'.lp($order_discount_amount, 2, false, $currency_id).'</td>
										</tr>';
						}

			$content .= '<tr>
							<td style="font-size: 12px; border-right: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="2"><b>Total:</b></td>
							<td style="font-size: 12px; text-align: right; padding: 7px;">'.lp($order_total_amt, 2, false, $currency_id).'</td>
						</tr>
					</tfoot>
				</table>
			</td>
		</tr>
		</tbody>
		</table>';
echo $content;

?>


