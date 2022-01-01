<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=modal]').facebox()
})
</script>
    <!--- Items Ordered --->
    <div id="tab-general" style="display: block;">
        <fieldset >
        <?php $para = (isset($_GET['custid'])?'&custid='.$_GET['custid']:'');
			  $para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'&edit=true':''; 
			  $para .= (isset($_GET['item_id']))?(($para!="")?'&':'').'item_id='.$_GET['item_id']:''; ?>
            <legend>Step 1: Items Ordered&nbsp;&nbsp;<a class="button" style="float:right;margin-top:-6px;" href="<?php echo site_url('admin/'.$this->controller.'?prod=list'.$para.'');?>">Add Products</a></legend>
            <table class="list">
                <thead>
                  <tr id="heading_tr" style="cursor:pointer;">
                    <td width="6%" class="left">Product Name</td>
                    <td width="4%" class="left">Product SKU</td>
                    <td width="9%" class="left">Code</td>
                    <td width="4%" class="left">Engraving Text</td>
                    <td width="4%" class="left">Engraving Font</td>
                    <td width="5%" class="left">Gift Name</td>
                    <td width="4%" class="right">Price</td>
                    <td width="3%" class="left">Quantity</td>
                    <td width="4%" class="right">Subtotal</td>
                    <td width="4%" class="right">Discount</td>
                    <td width="4%" class="right">Tax</td>
                    <td width="4%" class="right">Row Total</td>
                    <td width="4%" class="left">Action</td>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $subTot = 0.0;
                    $disTot = 0.0;
                    $taxTot = 0.0;
                    $rowTot = 0.0;
                    $prodArr = (!$this->is_post)?(isset($prodArr) ? $prodArr:''):@$_POST['hid_product_id'];
                    $order_details_idArr = (!$this->is_post)?'':@$_POST['order_details_id'];
                    
					if(count($prodArr)):
                        foreach($prodArr as $k=>$ar):
							$order_details_id = (!$this->is_post)?$ar['order_details_id']:@$order_details_idArr[$k];
                ?>
                  <tr id="<?php echo $order_details_id; ?>" class="products">
                    <?php
						$product_generate_code = (!$this->is_post)?$ar['product_generate_code']:@$_POST['product_generate_code_'.$order_details_id];
						$gift_id = (!$this->is_post)?$ar['gift_id']:@$_POST['hid_gift_id_'. $order_details_id];
						$product_center_stone_id = (!$this->is_post)?$ar['product_center_stone_id']:@$_POST['product_center_stone_id_'. $order_details_id];
						$product_side_stone1_id = (!$this->is_post)?$ar['product_side_stone1_id']:@$_POST['product_side_stone1_id_'. $order_details_id];
						$product_side_stone2_id = (!$this->is_post)?$ar['product_side_stone2_id']:@$_POST['product_side_stone2_id_'. $order_details_id];
						$product_metal_id = (!$this->is_post)?$ar['product_metal_id']:@$_POST['product_metal_id_'. $order_details_id];
						$product_engraving_text = (!$this->is_post)?$ar['product_engraving_text']:@$_POST['product_engraving_text_'.$order_details_id];
						$product_engraving_font = (!$this->is_post)?$ar['product_engraving_font']:@$_POST['product_engraving_font_'.$order_details_id];
						$quantity = (!$this->is_post)?$ar['quantity']:@$_POST['quantity_'. $order_details_id];
						$prod_price = (!$this->is_post)?$ar['hid_product_price']:@$_POST['hid_product_price_'. $order_details_id];
						$product_discount = (!$this->is_post)?$ar['hid_product_discount']:@$_POST['hid_product_discount_'. $order_details_id];
						$order_details_product_tax = (!$this->is_post)?$ar['order_details_product_tax']:@$_POST['order_details_product_tax_'. $order_details_id];
						$prod_ship_cost = (!$this->is_post)?$ar['hid_product_shipping_cost']:@$_POST['hid_product_shipping_cost_'.$order_details_id];
						$prod_cod_cost = (!$this->is_post)?$ar['hid_product_cod_cost']:@$_POST['hid_product_cod_cost_'.$order_details_id];
						$prod_name = (!$this->is_post)?$ar['hid_product_name']:@$_POST['hid_product_name_'. $order_details_id];
						$prod_sku = (!$this->is_post)?$ar['hid_product_sku']:@$_POST['hid_product_sku_'. $order_details_id];

						if(!$this->is_post)	//if post method then shipping and cod cost is already added then no need to add again							
							$prod_price = $prod_price + (int)$prod_ship_cost + (int)$prod_cod_cost;
	
						$subTot += $temp_sub = round($prod_price * (int)$quantity,2);
						$disTot += $temp_dis = round((int)$quantity * $prod_price * ((int)$product_discount/100),2);
						$taxAmt = 0.0;
						$taxPer = 0.0;
							
						if($order_details_product_tax == "")
						{
							$this->proArr[] = $temp_sub;
						}
						else
						{
							$taxArr = explode("|",$order_details_product_tax);
							foreach($taxArr as $key=>$val)
							{
								$valArr = explode(",",$val); 
								if($valArr[0] == "Fix")
									$taxAmt += round((float)@$valArr[1]*$quantity,2);	
								else
									$taxAmt += round($temp_sub * ((float)@$valArr[1]/100),2);	
							}
						}
						$taxTot += $taxAmt;
						$this->taxTot += $taxAmt;

						$rowTot += $temp_row = ($temp_sub + $taxAmt - $temp_dis);
						
						$prod_link = $prod_name;
						if(strpos($ar['order_details_id'],"New") == false)
						{
							$prod_link = '<a href="'.site_url('admin/'.$this->controller.'/popupProductDetail?id='._en($ar['order_details_id']).'').'" rel="modal">'.$prod_name.'</a>';
						}
					?>
					
                    <td class="left"><?php echo $prod_link;?></td>
                    <td class="left"><?php echo $prod_sku;?></td>
                    <td class="left"><?php echo $product_generate_code;?></td>
                    <td class="left"><?php echo $product_engraving_text;?></td>
                    <td class="left"><?php echo $product_engraving_font;?></td>
                    <td class="left"><?php echo getField("gift_name","gift","gift_id",$gift_id);?>
                    </td>
                    <td class="right" ><?php echo lp($prod_price);?>
                    </td>
                    <td class="left"><select id="quantity_<?php echo $order_details_id ?>" name="quantity_<?php echo $order_details_id ?>" >
                    				<?php
										for($i=1;$i<=10;$i++)
										{
											$selected = "";
											if($i == $quantity)	
												$selected = 'selected="selected"';
												
											echo '<option value="'.$i.'" '.$selected.' >'.$i.'</option>';
										}
									?>
                                      </select></td>
                    <td class="right" id="td_subtot_<?php echo $order_details_id; ?>" ><?php echo lp($temp_sub);?></td>
                    <td class="right" id="td_distot_<?php echo $order_details_id; ?>" ><?php echo lp($temp_dis);?></td>
                    <td class="right" id="td_taxtot_<?php echo $order_details_id; ?>" ><?php echo lp($taxAmt);?></td>
                    <td class="right" id="td_rowtot_<?php echo $order_details_id; ?>" ><?php echo lp($temp_row);?></td>
                    <td class="left"><select id="action_<?php echo $order_details_id; ?>" name="action_<?php echo $order_details_id; ?>" >
                    <option value="">Select</option>
                    <option value="rem|<?php echo $ar['product_id']."|".$order_details_id; ?>" >Remove</option>
                    </select></td>
																												
                    <input type="hidden" value="<?php echo $order_details_id;?>" name="order_details_id[]" />
                    <input type="hidden" value="<?php echo $ar['product_id'];?>" name="hid_product_id[]" />
                    <input type="hidden" value="<?php echo $product_generate_code;?>" name="product_generate_code_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $gift_id;?>" name="hid_gift_id_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_center_stone_id;?>" name="product_center_stone_id_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_side_stone1_id;?>" name="product_side_stone1_id_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_side_stone2_id;?>" name="product_side_stone2_id_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_metal_id;?>" name="product_metal_id_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_engraving_text;?>" name="product_engraving_text_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_engraving_font;?>" name="product_engraving_font_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $prod_price;?>" name="hid_product_price_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $product_discount; ?>" name="hid_product_discount_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $order_details_product_tax;?>" name="order_details_product_tax_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $prod_ship_cost;?>" name="hid_product_shipping_cost_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $prod_cod_cost;?>" name="hid_product_cod_cost_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $prod_name;?>" name="hid_product_name_<?php echo $order_details_id; ?>" />
                    <input type="hidden" value="<?php echo $prod_sku;?>" name="hid_product_sku_<?php echo $order_details_id; ?>" />

                  </tr>
                <?php
                        endforeach;
                    else:
                ?>
                  <tr><td class="center" colspan="13" ><?php echo (@$error)?'<span class="error_msg">'.form_error('hid_product_id').'</span>':'No ordered items'; ?> </td></tr>
                <?php
                    endif;
                ?>         
                </tbody>
                <?php
                    if(sizeof($prodArr) > 0):
                ?>
                <tfoot id="product_footer">
                <tr>
                    <td class="left" id="td_product_count">Total <?php echo sizeof($prodArr) ?> Products</td>
                    <td class="right" colspan="7">Subtotal: </td>
                    <td class="right" id="td_order_subtotal_amt" ><?php echo lp($subTot); ?>
                    </td>
                    <input type="hidden" value="<?php echo $subTot;?>" name="order_subtotal_amt" id="order_subtotal_amt" >
                    <td class="right" id="td_order_discount_amount"><?php echo lp($disTot); ?>
                    </td>
                    <input type="hidden" value="<?php echo $disTot;?>" name="order_discount_amount" id="order_discount_amount" />
                    <td class="right" id="td_order_total_taxamt_product"><?php echo lp($taxTot); ?>
                    </td>
                    <input type="hidden" value="<?php echo $taxTot;?>" name="order_total_taxamt_product" id="order_total_taxamt_product" />
                    <td class="right" id="td_order_total_rowamt_product"><?php echo lp($rowTot); ?>
                    </td>
                    <input type="hidden" value="<?php echo $rowTot;?>" name="order_total_rowamt_product" id="order_total_rowamt_product" />
                    <td class="right"></td>
                </tr>
                <?php
					$coupDiscAmt =0.0;
					$coupon_id = (!$this->is_post)?@$coupon_id:@$_POST['coupon_id'];
					$res = executeQuery("SELECT coupon_discount_amt, coupon_type FROM coupon WHERE coupon_id=".(int)$coupon_id);
					if(!empty($res)):
						if($res[0]['coupon_type'] == 'Fix')
							$coupDiscAmt = $res[0]['coupon_discount_amt'];	
						else
							$coupDiscAmt = round($subTot * ($res[0]['coupon_discount_amt']/100),2);	
                ?>
				<tr id="tr_coupon">
				<td class="right" colspan="9">Coupon Discount: </td>
                <input type="hidden" value="<?php echo $coupon_id;?>" name="coupon_id" id="coupon_id">
                <input type="hidden" value="<?php echo $res[0]['coupon_type'];?>" name="disc_type" id="disc_type">
                <input type="hidden" value="<?php echo $res[0]['coupon_discount_amt'];?>" name="coupon_discount" id="coupon_discount">
				<td class="right"><?php echo ($res[0]['coupon_type'] == 'Fix')?'Fix':$res[0]['coupon_discount_amt'].'%'; ?></td>
				<td class="right"><?php echo lp($coupDiscAmt); ?></td>
				<td class="right"><?php echo lp($rowTot - $coupDiscAmt); ?></td>
				<td class="left"></td>
				</tr>                
                <?php
                    endif;
                ?>
                </tfoot>
                <?php
						$this->prodAmt = $rowTot-$coupDiscAmt; 
                    endif;
                ?>
              </table>
              <input type="hidden" value="<?php echo $this->prodAmt;?>" name="order_total_amt_product" id="order_total_amt_product" />
              <a class="button" onclick="return updateItems(null,0,0,true)" style="float:right;">Update Items & Qty's</a>
        </fieldset>
    </div>

<script type="text/javascript">
var edit = <?php echo json_encode((isset($_GET['edit']) && $_GET['edit']=="true")?'true':' '); ?>;
var reorder = <?php echo json_encode((isset($_GET['reorder']) && $_GET['reorder']=="true")?'true':'false'); ?>;
/*
	function will update subtotal and total of ordered items
*/
function updateItems(coup_discArr,shipp_charge,handling_charge,is_update_items)
{
	var subTot = 0;				//subtotal of all product prices
	var disTot = 0;				//product wise discount total if available
   	var prodtaxTot = 0;			//product wise tax total
	var rowTot = 0;				//all included product cost total after deducting discount and adding tax in sub total
	var prodAmt = 0.0;			 //all included product cost total after deducting coupon discount if applicable
	var taxGenTot = 0;			 //general tax total if applied when product tax not available
	var grand_tot = 0.0;		   //grand total after adding taxGenTot in prodAmt total

	var order_details_id = '';    //value is stored in variable only of edit mode is true therefore remove item from database using ajax
	var product_id = '';		   //product id array	
	var is_ajax_needed = false;   
	var prod_id = 0;
	var val = '';
	var prod_cnt = 0;
	if(is_update_items)
	{
		$('.products').each(function()
		{
			prod_id = $(this).attr('id');		//change: right now in use is order_details_id for mapping input field not product_id
			val = $('#action_'+prod_id).val();
			if(typeof val !== 'undefined')
			{
				if(val != '')
				{
					val = val.split('|');
				}
						
				if(val != '' && val[0] == 'rem')
				{
					if(edit == 'true' && reorder=='false' && val[2].indexOf("-") == -1) //id id copntains '-' that means details id is still not exist in database
					{
						is_ajax_needed = true;   
						order_details_id +=  val[2]+'|';
						product_id +=  val[1]+'|';
					}
					$(this).remove();
				}
				else
				{
					prod_cnt++;					
					var temp_dis = 0;
					var temp_sub = 0;
					var temp_tax = 0;
					var temp_row = 0;
					var tot_tax_rate = 0.0;	
	
					var prod_price = $('input:hidden[name=hid_product_price_'+prod_id+']').val(); //here price is some of prod_price, shipping and cod cost
					//var shipp_cost = $('input:hidden[name=hid_product_shipping_cost_'+prod_id+']').val();
					//var cod_cost = $('input:hidden[name=hid_product_cod_cost_'+prod_id+']').val();
					//prod_price = Number(prod_price) + Number(shipp_cost) + Number(cod_cost);
					var quantity = $('#quantity_'+prod_id).val();
					var discount = $('input:hidden[name=hid_product_discount_'+prod_id+']').val();
					var tax = $('input:hidden[name=order_details_product_tax_'+prod_id+']').val();
					subTot += temp_sub = Math.round(prod_price * quantity,2);
					$('#td_subtot_'+prod_id).html(lp(temp_sub));
					disTot += temp_dis = Math.round(temp_sub * (discount/100),2);
					$('#td_distot_'+prod_id).html(lp(temp_dis));
			
					if(tax != '' && tax != null && typeof tax != 'undefined')
					{
						taxArr = tax.split('|');
						for(var i = 0; i < taxArr.length; i++)
						{
							valArr = taxArr[i].split(',');
							if(valArr.length>1)
							{
								if(valArr[0]=='Fix')
									temp_tax += Math.round(Number(valArr[1])*quantity,2);
								else
									temp_tax += Math.round(temp_sub * (Number(valArr[1])/100),2);
							}
						}					
						prodtaxTot += temp_tax;
						$('#td_taxtot_'+prod_id).html(lp(temp_tax));
					}
					else
					{													
						tot_tax_rate = $('input:hidden[name=order_tax_percent]').val();
						taxGenTot += Math.round(temp_sub * (tot_tax_rate/100),2);
					}
					rowTot += temp_row = (temp_sub + temp_tax - temp_dis);
					$('#td_rowtot_'+prod_id).html(lp(temp_row));
				}
			}
		});
		
		shipp_charge = $('#shipping_method_shipping_charge').val();
		handling_charge = $('#shipping_method_handling_charge').val();
	}

	var temp = $('#coupon_discount').val();
	if(coup_discArr == null && temp != null && temp != '' && typeof temp!='undefined')
	{
		var coup_discArr = Array();
		coup_discArr['disc_type'] = $('#disc_type').val();
		coup_discArr['coup_disc'] = temp;
		coup_discArr['coupon_id'] = $('#coupon_id').val();
	}

	if((coup_discArr != null || temp != '') && is_update_items==true)
	{
		var coupDiscamt = 0;
		var coupDisc = '';
		
		if(coup_discArr['disc_type'] == 'Fix')
		{
			coupDiscamt = coup_discArr['coup_disc'];
			coupDisc = 'Fix';
		}
		else
		{
			coupDiscamt = Math.round(subTot * (coup_discArr['coup_disc']/100),2);
			coupDisc = coup_discArr['coup_disc']+'%';
		}
			
		prodAmt = rowTot - coupDiscamt;
		if($('#tr_coupon').length > 0 )
		{
			$('#tr_coupon').remove();	
		}
		
		var html = '<tr id="tr_coupon" >';
		html += '<td class="right" colspan="9">Coupon Discount: </td>';
		html += '<td class="right">'+coupDisc+'</td>';
		html += '<td class="right">Rs.'+coupDiscamt+'</td>';
		html += '<input type="hidden" value="'+coup_discArr['coupon_id']+'" name="coupon_id">';
        html += '<input type="hidden" value="'+coup_discArr['disc_type']+'" name="disc_type" id="disc_type">';
        html += '<input type="hidden" value="'+coup_discArr['coup_disc']+'" name="coupon_discount" id="coupon_discount">';
		html += '<td class="right" >Rs.'+prodAmt+'</td>';
		html += '<td class="left"></td>';
		html += '</tr>';
	
		$('#product_footer').append(html);
	}
	else
	{
		prodAmt = rowTot;	
	}
	
	if(is_update_items)
	{
		$('#order_subtotal_amt').val(subTot);
		$('#order_discount_amount').val(disTot);
		$('#order_total_rowamt_product').val(rowTot);
		$('#order_total_amt_product').val(prodAmt);
		$('#td_order_subtotal_amt').html(lp(subTot));
		$('#td_order_discount_amount').html(lp(disTot));
		$('#td_order_total_taxamt_product').html(lp(prodtaxTot));
		$('#td_order_total_rowamt_product').html(lp(rowTot));
		$('#order_taxGen_amt').val(taxGenTot);
		$('#order_tax_amt').val(Number(taxGenTot) + Number(prodtaxTot));
		$('#td_order_tax_amt').html(lp(taxGenTot));
	}
																													
	if(rowTot == 0) 									//initialize rowTot if not set when function call is made for applying shipping charges
	{
		prodAmt = $('#order_total_amt_product').val();
		taxGenTot = $('#order_taxGen_amt').val();
	}

	grand_tot = (Number(prodAmt) + Number(taxGenTot) + Number(shipp_charge) + Number(handling_charge));
	$('#td_product_count').html('Total '+prod_cnt+' Products');
	$('#order_total_amt').val(grand_tot);
	$('#td_order_total_amt').html(lp(grand_tot));
			
	if(is_ajax_needed)
	{
		var loc = (base_url+'admin/'+lcFirst(controller))+'/deleteOrderDetail';
		form_data = {order_details_id : order_details_id.substring(0,order_details_id.length-1), product_id : product_id.substring(0,product_id.length-1)};
		$.post(loc, form_data, function (data)
		{
			var arr = $.parseJSON(data);
			$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		});
	}
										
};
</script>