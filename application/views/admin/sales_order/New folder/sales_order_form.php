<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a>
	  <?php
      	if(isset($_GET['edit'])):
	  ?>
      <a class="button" href="<?php echo site_url('admin/'.$this->controller.'/sendMail?item_id='._en(@$this->cPrimaryId))?>">Send Mail</a><a class="button" href="<?php echo site_url('admin/'.$this->controller.'/printInvoice?item_id='._en(@$this->cPrimaryId))?>">Invoice</a><a class="button" href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&reorder=true&item_id='._en(@$this->cPrimaryId))?>" >Reorder</a>
      <?php
      	endif;
	  ?>
      <a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <?php 
		$custid = (isset($_GET['custid']) && @$_GET['custid'] != '')?$_GET['custid']:((!$this->is_post)?_en(@$customer_id):_en(@$_POST['customer_id']));	
		$para = 'custid='.$custid.(isset($_GET['reorder'])?'&reorder=true':''); 
		if(empty($custid))
		{
			redirect(base_url('admin/'.$this->controller));
		}
	?>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?'.$para)?>">
      <input type="hidden" name="item_id" id="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>" />
      <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $custid; ?>" />
	<?php
		$custid = _de($custid);
	?>
        <div id="tab-general" >
        <!--- Items ordered --->
		<?php
			$data['prodArr'] = $prodArr;
            $this->load->view('admin/'.$this->controller.'/items_ordered',$data);
        ?>
        
        <!--- Coupon Code --->
        <?php
			$coupon_code = "";
			if(isset($coupon_id) && @$coupon_id!=0)
			{
				$coupon_code = getField("coupon_code","coupon","coupon_id",$coupon_id);
			}
		?>
        <div id="tab-general" style="display: block;float:left;width:50%;">
            <fieldset>
                <legend>Step 2: Apply Coupon Code</legend>
                <table class="form">
                  <tbody>
                    <tr>
                      <td><input type="text" name="coupon_code" value="<?php echo (!$this->is_post)?$coupon_code:@$_POST['coupon_code'];?>" />&nbsp;<a class="button" onclick="calcCouponDisc()" >Apply</a>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
            </fieldset>
		</div>

        <!--- Customer Account Information --->
        <?php
			$custData = array();
			$shipp_add_id = $bill_add_id = 0;
			if(!$this->is_post)
			{
				if(!isset($_GET['custid']))
				{
					$shipp_add_id = @$customer_shipping_address_id;	
					$bill_add_id = @$customer_billing_address_id;	
				}
			}

			$sql = "SELECT customer_address_id,CONCAT(customer_address_firstname,',',customer_address_lastname,',',customer_address_address,',',customer_address_company, ',', customer_address_city,',',country_name,',', customer_address_zipcode) as 'customer_address',c.country_id,customer_address_state_id  
					FROM customer_address a INNER JOIN pincode p 
                                        ON p.pincode_id=a.customer_address_zipcode
                                        INNER JOIN state s 
                                        ON s.state_id=p.state_id 
                                        INNER JOIN country c 
                                        ON c.country_id=s.country_id 
                                        WHERE customer_id=".$custid." ";
			$res = executeQuery($sql);
			$addArr = array(''=>'Select Addresses');
			if(!empty($res))
			{
				if(isset($_GET['custid']))
				{
					$shipp_add_id = $res[0]['customer_address_id'];	
					$bill_add_id = $res[0]['customer_address_id'];	
				}
				foreach($res as $key=>$val)
					$addArr[$val['customer_address_id']] =$val['customer_address'];				  		
			}
		?>
        <div id="tab-general" style="display: block;float:left;width:50%;" >
        <?php
        	if(!isset($customer_group_name))
			{
				$res = executeQuery("SELECT customer_emailid,customer_group_name from customer c INNER JOIN customer_group g WHERE customer_id=".$custid." ");	
				if(!empty($res))
				{
					$customer_group_name = $res[0]['customer_group_name'];
					$customer_emailid = $res[0]['customer_emailid'];
				}
			}
		?>
            <fieldset>
                <legend>Step 3: Customer Account Information</legend>
                <table class="form">
                  <tbody>
                    <tr>
                      <td> Customer Group:</td>
                      <td><input type="text" name="customer_group_name" value="<?php echo @$customer_group_name; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td> Customer Email:</td>
                      <td><input type="text" name="customer_emailid" value="<?php echo @$customer_emailid; ?>" />
                      </td>
                    </tr>
                  </tbody>
                </table>
            </fieldset>
        </div>    

        <!--- Billing address --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 4: Billing Address</legend>
                <table class="form">
                  <tbody>
                  <tr style="background-color:#E7EFEF;" id="address_bill_before">
                    <td > Select Existing Address:</td>
                    <td>
					<?php 
	                	echo form_dropdown('customer_billing_address_id',$addArr, $bill_add_id,' style="width:70%;" onchange="return changeAddress(this.value,\'bill\')" ');
						$data['customer_address_id'] = $bill_add_id;
						$data['type'] = "bill";
					?>
					</td>
                  </tr>
					<?php
						if($data['customer_address_id'])
							$this->load->view('admin/'.$this->controller.'/customer_address',$data);
					?>
                  <tr style="background-color:#E7EFEF;" id="address_bill_after" >
                  	  <td colspan="2"><label><input type="checkbox" value="1" <?php echo ($this->is_post)?($_POST['save_in_address_book_bill']==1?'checked="checked"':''):'' ?> name="save_in_address_book_bill" /><b>Save in address book</b></label></td>
                  </tr>
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Shipping address --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 5: Shipping Address</legend>
                <table class="form">
                  <tbody>
                  <tr style="background-color:#E7EFEF;" id="address_shipp_before">
                    <td > Select Existing Address:</td>
                    <td>
					<?php 
	                	echo form_dropdown('customer_shipping_address_id',$addArr, $shipp_add_id,' style="width:70%;" onchange="return changeAddress(this.value,\'shipp\')" ');
						$data['customer_address_id'] = $shipp_add_id;
						$data['type'] = "shipp";
					?>
					</td>
                        <label><input type="checkbox" name="same_as_address" value="" onchange="return disableCheckAdd(this)"/>Same as billing address</label>
                  </tr>
					<?php
						if($data['customer_address_id'])
							$this->load->view('admin/'.$this->controller.'/customer_address',$data);
					?>
                  <tr style="background-color:#E7EFEF;" id="address_shipp_after" >
                  	  <td colspan="2"><label><input type="checkbox" value="1" <?php echo ($this->is_post)?($_POST['save_in_address_book_shipp']==1?'checked="checked"':''):'';?> name="save_in_address_book_shipp" /><b>Save in address book</b></label></td>
                  </tr>
                  </tbody>
                </table>
                
            </fieldset>
		</div>

        <!--- Payment Method --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 6: Payment Method</legend>
                <table class="form">
                  <tbody>
				  <?php
						$sql = "SELECT payment_method_id, payment_method_name FROM payment_method WHERE payment_method_status=0";
						$pmArr = getDropDownAry($sql,"payment_method_id", "payment_method_name", '', false);
						$payment_method_id = (!$this->is_post)?@$payment_method_id:@$_POST['payment_method_id'];
						if(is_array($pmArr) && sizeof($pmArr)>0):
							foreach($pmArr as $k=>$ar):
				  ?>                  
                  <tr>
		                  <td><label><input type="radio"  name="payment_method_id" class="pay_method" value="<?php echo $k; ?>" <?php echo ($k == $payment_method_id)? 'checked="checked"':'';?>  /><?php echo $ar; ?></label>
                          	  <div class="pay_form" style="display: none" id="pay_form_<?php echo $k; ?>">
                              <br><br>Payment Form <br><br>
                              </div>
                          </td>
                  </tr>
				  <?php
                  			endforeach;
						endif;
				  ?>         
		          <span class="error_msg"><?php echo (@$error)?form_error('payment_method_id'):''; ?></span>
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Shipping Method --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 7: Shipping Method</legend>
                <table class="form">
                  <tbody>
				  <?php
				  		$order_total_amt = 0;
						$sql = "SELECT shipping_method_id, shipping_method_name FROM shipping_method WHERE shipping_method_status=0";
						$pmArr = getDropDownAry($sql,"shipping_method_id", "shipping_method_name", '', false);
						$shipping_method_id = (!$this->is_post)?@$shipping_method_id:@$_POST['shipping_method_id'];
						$order_total_amt += $shipping_method_shipping_charge =(!$this->is_post)?@$shipping_method_shipping_charge:@$_REQUEST['shipping_method_shipping_charge'];
						$order_total_amt += $shipping_method_handling_charge =(!$this->is_post)?@$shipping_method_handling_charge:@$_REQUEST['shipping_method_handling_charge'];
						
						if(is_array($pmArr) && sizeof($pmArr)>0):
							foreach($pmArr as $k=>$ar):
				  ?>                  
                  <tr>
		                  <td id="td_shipping_method_id_<?php echo $k; ?>"><label><input type="radio" name="shipping_method_id" value="<?php echo $k; ?>" <?php echo ($k == $shipping_method_id)? 'checked="checked"':'';?> onchange="return fetchShippingCost(this.value)"  /><?php echo $ar; ?></label><?php echo ($k==$shipping_method_id)?'<span class="shipp_method_values" >&nbsp;&nbsp;&nbsp;&nbsp;Shippping Charge: '.$shipping_method_shipping_charge.'&nbsp;&nbsp;&nbsp;&nbsp;Handling Charge: '.(int)$shipping_method_handling_charge.'</span>':''; ?></td>
                          
                  </tr>
				  <?php
                  			endforeach;
						endif;
				  ?>                
                  <input type="hidden" name="shipping_method_shipping_charge" id="shipping_method_shipping_charge" value="<?php echo @$shipping_method_shipping_charge; ?>"  />
                  <input type="hidden" name="shipping_method_handling_charge" id="shipping_method_handling_charge" value="<?php echo @$shipping_method_handling_charge; ?>"  />
	              <span class="error_msg"><?php echo (@$error)?form_error('shipping_method_id'):''; ?></span>
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Order History --->
        <div style="display: block; width:50%; float:left;">
	        <fieldset>
		<?php
			$order_status_id = 0; 
            if(isset($_GET['edit']) && isset($_GET['item_id']) && !isset($_GET['reorder'])):
				$res = executeQuery("SELECT t.order_status_id,order_tracking_comment,order_tracking_created_date, order_status_name 
									 FROM order_tracking t INNER JOIN order_status s ON s.order_status_id=t.order_status_id WHERE t.order_id=".$this->cPrimaryId." 
									 ORDER BY order_tracking_id DESC"); 

			if(!empty($res))
				$order_status_id = $res[0]['order_status_id'];
								
        ?>
            <legend>Step 8: Order Tracking</legend>
        <table class="form">
          <tbody>
          <tr>
          <td>Status: </td>
          <td><?php echo getOrderStatusDropdown(@$order_status_id,'id="order_status_id"'); ?>
              <span class="error_msg"><?php echo (@$error)?form_error('order_status_id'):''; ?></span></td>
          </tr>
          <tr>
          <td>Comments:</td>
          <td><textarea name="order_tracking_comment" id="order_tracking_comment" cols="50" rows="4"><?php echo ($this->is_post)?@$_POST['order_tracking_comment']:'';?></textarea></td>
          </tr>
          <tr>
          <td><label><input type="checkbox" value="1" name="email_confirm" id="email_confirm" <?php echo ($this->is_post)?($_POST['order_tracking_email_confirm']==1?'checked="checked"':''):'';?> />Notify Customer by Email</label></td>
          <td class="left"><a class="button" onclick="SubmitComment();" >Submit Comment</a>
</td>
          </tr>
          </tbody>
        </table>
				<!-- Order tracking history -->
          <table class="form" id="order_tra_his">
          <tbody>
		<?php
				if(!empty($res)):
	        		foreach($res as $k=>$ar):
		?>
          <tr>
          <td><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_tracking_created_date']);?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php echo $ar['order_status_name'];?></td>
          </tr>
        <?php
					endforeach;
				endif;	
		?>
		  </tbody></table>
        <?php
        	else:
        ?>
              <legend>Step 8: Order Comments</legend>
                <table class="form">
                  <tbody>
                  <tr>
                  <td>Status: </td>
                  <td><?php echo getOrderStatusDropdown(@$order_status_id,'id="order_status_id"'); ?>
                  	  <span class="error_msg"><?php echo (@$error)?form_error('order_status_id'):''; ?></span></td>
                  </tr>
                  <tr>
                  <td>Comments:</td>
                  <td><textarea name="customer_note" cols="50" rows="4"></textarea></td>
                  </tr>
                  </tbody>
                </table>
		<?php
            endif;
		?>
            </fieldset>
		</div>

        <!--- Order Totals --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
            <?php
				$order_total_amt += $this->prodAmt;
			?>
                <legend>Step 9: Order Totals</legend>
                <table class="form">
                  <tbody>
                  <tr>
                  <td>Tax Rate:</td>
                  <td >
				  <?php 
				  		$taxGenTot = 0;
						$taxRate = 0;

				  		if(sizeof($this->proArr) > 0 )
						{
							$taxRate = getField("config_value","configuration","config_key","TAX_RATE");
							if(!empty($taxRate))
							{
								foreach($this->proArr as $k=>$price)
									$this->taxTot += $taxGenTot += round($price * ($taxRate/100),2);	
							}
							echo $taxRate."%";
						}
						else
							echo "Product Wise Tax Applied"; 
							
						$order_total_amt += $taxGenTot;
				  ?>
                  </td>
                  <input type="hidden" name="order_tax_percent" value="<?php echo $taxRate; ?>" />
                  </tr>
                  <tr>
                  <td>Tax Amount:</td>
                  <td id="td_order_tax_amt" >
				  <?php echo lp($taxGenTot);?></td>
                  <input type="hidden" name="order_tax_amt" id="order_tax_amt"  value="<?php echo $this->taxTot; ?>" />
                  <input type="hidden" name="order_taxGen_amt" id="order_taxGen_amt"  value="<?php echo $taxGenTot; ?>" />
                  </tr>
                  <tr>
                  <td>Grand Total:</td>
                  <td id="td_order_total_amt" ><?php echo lp($order_total_amt); ?></td>
                  <input type="hidden" name="order_total_amt" id="order_total_amt" value="<?php echo (!$this->is_post)?$order_total_amt:@$_POST['order_total_amt']; ?>" />
                  </tr>
                  </tbody>
                </table>
				<?php
                	if(!isset($_GET['edit'])):
				?>
                  <hr />
                <table class="form">
                  <tbody>
                  <tr>
                  <td colspan="2"><label><input type="checkbox" value="1" name="append_com" <?php echo ($this->is_post)?(@$_POST['append_com']==1?'checked="checked"':''):'';?>/>Append Comments</label></td>
                  </tr>
                  <tr>
                  <td><label><input type="checkbox" value="1" name="email_confirm" <?php echo ($this->is_post)?(@$_POST['email_confirm']==1?'checked="checked"':''):'';?>/>Email Order Confirmation</label></td>
                  </tr>
                  </tbody>
                </table>
				<a class="button" onclick="$('#form').submit();" style="float:right;">Submit Order</a>
                <?php
					endif;
				?>
            </fieldset>
		</div>

		</div>
      </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
/*
	function will disable save address checkbox of shipping address
*/
function SubmitComment()
{
	var item_id = $('#item_id').val();
	var order_status_id = $('#order_status_id').val();
	var customer_id = $('#customer_id').val();
	var email_confirm =0;
	if($('#email_confirm').is(':checked'))
	{
		email_confirm =1;
	}
	var order_tracking_comment = $('#order_tracking_comment').val();
		
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller))+'/updateOrderStatus';
	form_data = {item_id : item_id,order_status_id : order_status_id,email_confirm : email_confirm,order_tracking_comment : order_tracking_comment,customer_id : customer_id};
	$.post(loc, form_data, function (data)
	{
		var html = $.parseJSON(data);
		$('#order_tra_his').html(html);
	});
	hideLoader();
}

/*
	function will disable save address checkbox of shipping address
*/
function disableCheckAdd(obj)
{
	if($(obj).is(":checked"))
	{
		$("input:checkbox[name='save_in_address_book_ship']").prop('checked',false);
		$("input:checkbox[name='save_in_address_book_ship']").attr("disabled", true);
		$('#address_bill_before').nextUntil('#address_bill_after').each(function(){
			transferAddress($(this).find(':input'));	
		});
	}
	else
	{
		$("input:checkbox[name='save_in_address_book_ship']").attr("disabled", false);
	}
}

/*
	function will disable save address checkbox of shipping address
*/
$('.pay_method').change(function(){
	$('.pay_form').css({'display':'none'});
	$('#pay_form_'+$(this).val()).css({'display':'block'});
});

/*
	function will change address as seleceted by user
*/
function changeAddress(add_id,type)
{
	if(add_id != '' && add_id != null && typeof add_id != 'undefined')
	{
		var loc = (base_url+'admin/'+lcFirst(controller))+'/getAddress';
		form_data = {add_id : add_id, type : type};
		$.post(loc, form_data, function (data) {
			$('#address_'+type+'_before').nextUntil('#address_'+type+'_after').each(function(){
				$(this).remove();	
			});
			$('#address_'+type+'_before').after(data);
		});
	}
};

/*
	function will copy address in shipping if same as selected
*/
function copyAddress(obj)
{
	if($('input:checkbox[name="same_as_address"]').is(":checked"))
	{
		transferAddress(obj);
	}
};

/*
* function 
*/
function transferAddress(obj)
{
	var name = $(obj).attr('name');
	if(name != '' && name != null && typeof name !== 'undefined')
	{
		name = name.substr(0,name.length-4)+'shipp';
		$('#'+name).val(''+$(obj).val()+'');
	}
}

/*
*	function will calc totals as per coupon code applied after fetching coupon discount using ajax
*/
function calcCouponDisc()
{
	var coup_code = $('input:text[name="coupon_code"]').val();
	if(coup_code == '' || coup_code == null || coup_code === 'undefined')
	{
		$('#content').before(getNotificationHtml('error','Specify coupon code first.'));
		return false;
	}
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller))+'/getCouponDiscount';
	form_data = {coup_code : coup_code};
	$.post(loc, form_data, function (data) {
			var arr = $.parseJSON(data);
			if(arr['type'] == "success")
			{
				updateItems(arr,0,0,true);
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
	});
	hideLoader();
}

/*
*	function will fetch shipping cost as per shipping cost selected
*/
function fetchShippingCost(shipp_id)
{
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller))+'/fetchShippingCost';
	form_data = {shipp_id : shipp_id};
	$.post(loc, form_data, function (data) {
			var arr = $.parseJSON(data);
			if(arr['type'] == "success")
			{
				updateItems(null,arr['shipp_charge'],arr['handling_charge'],false);
				
				if($('.shipp_method_values').length > 0)  //if shipp method value span exist then remove it
				{
					$('.shipp_method_values').remove();
				}
				
				$('#shipping_method_shipping_charge').val(arr['shipp_charge']);
				$('#shipping_method_handling_charge').val(arr['handling_charge']);
				$('#td_shipping_method_id_'+shipp_id).append('<span class="shipp_method_values" >&nbsp;&nbsp;&nbsp;&nbsp;Shippping Charge: '+arr['shipp_charge']+'&nbsp;&nbsp;&nbsp;&nbsp;Handling Charge: '+arr['handling_charge']+'</span>');
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
	});
	hideLoader();
}

</script>