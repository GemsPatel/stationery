<script type="text/javascript">
	//cust_order_id for order
	var custid = '<?php echo _en($this->cust_order_id);?>';
</script>
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
	<div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
		  <?php
	      	if(!isset($_GET['edit'])):
		  ?>      
		      <a class="button" onclick="$('#form').submit(); return false;">Save</a>
		  <?php
	      	elseif(isset($_GET['edit'])):
		  ?>
	      	<!-- <a class="button" href="<?php //echo site_url('admin/'.$this->controller.'/sendMail?item_id='._en(@$this->cPrimaryId))?>">Send Mail</a> -->
	      	<!-- <a class="button" href="<?php //echo site_url('admin/'.$this->controller.'/invoice?item_id='._en(@$this->cPrimaryId))?>">Invoice</a> -->
	    	  <a class="button" target="_blank" href="<?php echo site_url('admin/'.$this->controller.'/printInvoice?item_id='._en(@$this->cPrimaryId))?>">Print Invoice</a>
	      	<!-- <a class="button" href="<?php //echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&reorder=true&item_id='._en(@$this->cPrimaryId))?>" >Reorder</a> -->
	      <?php
	      	endif;
		  ?>
	      <a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a>
	  </div>
    </div>
    
    <?php 
		$custid = (isset($_GET['custid']) && $_GET['custid'] != '')?$_GET['custid']:'';	
		$para = 'custid='.$custid; 
		if(empty($custid))
		{
			redirect(base_url('admin/'.$this->controller));
		}
	?>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/payment?'.$para)?>">
      <input type="hidden" name="item_id" id="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>" />
      <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $custid; ?>" />      
      
	<?php
		$custid = _de($custid);
	?>
        <div id="tab-general" >
        <!--- Items ordered --->
		<?php
			$data = array();
			if( isset($customer_id) && isset($cartArr) )
			{
				if($this->cPrimaryId != '') { $data['customer_id'] = $this->cPrimaryId; }
				else { $data['customer_id'] = $customer_id; }
					
				$data['cartArr'] = $cartArr;
				$data['order_total_qty'] = $order_total_qty;
				$data['order_subtotal_amt'] = $order_subtotal_amt;
				$data['coupon_id'] = @$coupon_id;
				$data['order_discount_amount'] = $order_discount_amount;
				$data['order_total_amt'] = $order_total_amt;
			}
			//pr($data);
			$this->load->view('admin/'.$this->controller.'/items_ordered',$data);
        ?>
        
        <!-- Order add mode or View mode -->
        <?php
        	if( empty($this->cPrimaryId) || $this->input->get('act') == 'view' ):
        ?>
        
        <!--- Coupon Code --->
        <?php
			$coupon_code = "";
			if(isset($coupon_id) && (int)$coupon_id!=0)
			{
				$coupon_code = getField("coupon_code","coupon","coupon_id",$coupon_id);
			}
		?>
        <div id="tab-general">
            <fieldset>
                <legend>Step 2: Apply Coupon Code</legend>
                <table class="form">
                  <tbody>
                    <tr>
                      <td><input type="text" name="coupon_code" value="<?php echo (isset($coupon_code))?$coupon_code:'';?>" />&nbsp;
					  <?php
                        if(!isset($_GET['edit'])):
                      ?>      
	                      <a class="button" onclick="applyCoupon()" >Apply</a>
    				  <?php
                      	endif;
					  ?>
                      </td>
                    </tr>
                  </tbody>
                </table>
                
            </fieldset>
		</div>

        <!--- Customer Account Information --->
        <div id="tab-general">
        <?php
			$res = executeQuery("SELECT customer_emailid,customer_group_name from customer c INNER JOIN customer_group g WHERE customer_id=".$custid." ");	
			if(!empty($res))
			{
				$customer_group_name = $res[0]['customer_group_name'];
				$customer_emailid = $res[0]['customer_emailid'];
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

        <?php
			$shipp_add_id = (int)@$customer_shipping_address_id;
			$bill_add_id = (int)@$customer_billing_address_id;

			$sql = "SELECT customer_address_id,CONCAT(customer_address_firstname,',',customer_address_lastname,',',customer_address_address,',',customer_address_company, ',', customer_address_city,',',country_name,',', p.pincode) as 'customer_address',c.country_id,customer_address_state_id  
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
				if(empty( $shipp_add_id ))
				{
					$shipp_add_id = $res[0]['customer_address_id'];	
				}
				
				if(empty( $bill_add_id ))
				{
					$bill_add_id = $res[0]['customer_address_id'];	
				}
				
				foreach($res as $key=>$val)
					$addArr[$val['customer_address_id']] =$val['customer_address'];				  		
			}
		?>
        <!--- Billing address --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 4: Billing Address</legend>
				<?php
					$data['customer_address_id'] = $bill_add_id;
					$data['type'] = "bill";
				?>
                <table class="form">
                  <tbody>
                  <tr style="background-color:#E7EFEF;" id="address_bill_before">
                    <td > Select Existing Address:</td>
                    <td>
					<?php 
	                	echo form_dropdown('customer_billing_address_id',$addArr, $bill_add_id,' style="width:70%;" id="customer_billing_address_id" onchange="return changeAddress(this.value,\'bill\')" ');
					?>
					</td>
                  </tr>
					<?php
						if((int)$data['customer_address_id']!=0)
							$this->load->view('admin/'.$this->controller.'/customer_address',$data);
					?>
                  <tr style="background-color:#E7EFEF;" id="address_bill_after" >
                  	  <td colspan="2"><label><input type="checkbox" value="1" name="save_in_address_book_bill"/><b>Save in address book</b></label></td>
                  </tr>
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Shipping address --->
        <div style="display: block; float:left; width:50%;">
            <fieldset>
                <legend>Step 5: Shipping Address</legend>
				<?php
					$data['customer_address_id'] = $shipp_add_id;
					$data['type'] = "shipp";
				?>
                <table class="form">
                  <tbody>
                  <tr style="background-color:#E7EFEF;" id="address_shipp_before">
                    <td > Select Existing Address:</td>
                    <td>
					<?php 
	                	echo form_dropdown('customer_shipping_address_id',$addArr, $shipp_add_id,' style="width:70%;" onchange="return changeAddress(this.value,\'shipp\')" ');
					?>
					</td>
                        <label>
                        	<input type="checkbox" name="same_as_address" value="" onchange="return disableCheckAdd(this)"/>Same as billing address
                        </label>
                  </tr>
					<?php
						if($data['customer_address_id'])
							$this->load->view('admin/'.$this->controller.'/customer_address',$data);
					?>
                  <tr style="background-color:#E7EFEF;" id="address_shipp_after" >
                  	  <td colspan="2">
                  	  	<label>
                  	  		<input type="checkbox" value="0" name="save_in_address_book_shipp" /><b>Save in address book</b>
                  	  	</label>
                  	  </td>
                  </tr>
				  <?php
                    if(!isset($_GET['edit'])):
                  ?>      
                  <tr>
                    <td>&nbsp;<a class="button" onclick="applyAddresses()" >Save Addresses</a></td>
                  </tr>
				  <?php
                    endif;
                  ?>      
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Payment Method --->
        <div style="display: block; float:left; width:100%;">
            <fieldset>
                <legend>Step 6: Payment Method <small style="color: #000000;">(Right now only supports COD, Soon start supporting BUCKs and payment gateway transctions!)</small></legend>
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
		                  <td><label><input type="radio" id="payment_method_id" name="payment_method_id" class="pay_method" value="<?php echo $k; ?>" <?php echo ($k == $payment_method_id)? 'checked="checked"':'';?>  /><?php echo $ar; ?></label>
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
        <div style="display: block; float:left; width:100%;">
            <fieldset>
                <legend>Step 7: Shipping Method</legend>
                <table class="form">
                  <tbody>
				  <?php
						$sql = "SELECT shipping_method_id, shipping_method_name FROM shipping_method WHERE shipping_method_status=0";
						$pmArr = getDropDownAry($sql,"shipping_method_id", "shipping_method_name", '', false);
						$shipping_method_id = (int)@$shipping_method_id;
						
						if(is_array($pmArr) && sizeof($pmArr)>0):
							foreach($pmArr as $k=>$ar):
				  ?>                  
                  <tr>
		                  <td id="td_shipping_method_id_<?php echo $k; ?>"><label><input type="radio" name="shipping_method_id" value="<?php echo $k; ?>" <?php echo ($k == $shipping_method_id)? 'checked="checked"':'';?> onchange="return fetchShippingCost(this.value)" disabled="disabled" /><?php echo $ar; ?></label></td>
                          
                  </tr>
				  <?php
                  			endforeach;
						endif;
				  ?>                
	              <span class="error_msg"><?php echo (@$error)?form_error('shipping_method_id'):''; ?></span>
                  </tbody>
                </table>
            </fieldset>
		</div>

        <!--- Order History --->
        <div style="display: block; float:left; width:100%;">
            <fieldset>
                <legend>Step 8: Order Comments</legend>
                <table class="form">
                  <tbody>
                  <tr>
                  <td colspan="2"><textarea name="customer_note" cols="50" rows="4" placeholder="Order Comment"><?php echo @$customer_note ?></textarea></td>
                  </tr>
				  <?php
                      if(!isset($_GET['edit'])):
                  ?>
				  <tr>
                  <td><label><input type="checkbox" value="1" name="email_confirm" />Email Order Confirmation</label></td>
                  </tr>
                  <?php
                  	  endif;
				  ?>
                  </tbody>
                </table>
			</fieldset>
        </div>
            
        <!--- Order Totals --->
        <div style="display: block; float:left; width:100%;">
            <fieldset>
                <legend>Step 9: Order Totals</legend>
                <table class="form">
                  <tbody>
                  <tr>
                  <td>Subtotal:</td>
                  <td ><?php echo lp(@$order_subtotal_amt); ?></td>
                  </tr>
                  <tr>
                  <td>Discount:</td>
                  <td ><?php echo lp(@$order_discount_amount); ?></td>
                  </tr>
                  <tr>
                  <td>Grand Total:</td>
                  <td ><?php echo lp(@$order_total_amt); ?></td>
                  </tr>
                  </tbody>
                </table>
				<?php
                    if(isset($is_shipping_valid) && $is_shipping_valid==true):
                ?>
                        <a class="button" onclick="$('#form').submit();" style="float:right;">Submit Order</a>
                <?php
                    elseif(!isset($_GET['edit'])):	
                ?>       
                        <span style="float:right;"> You can submit order after shipping address is available. </span>
                <?php
                    endif;
                ?>
            </fieldset>
		</div>
		
		<?php
			endif;
		?>
		<!-- Order add mode end -->

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
function applyCoupon()
{
	var coupon_code = $('input:text[name="coupon_code"]').val();
	if(coupon_code == '' || coupon_code == null || coupon_code === 'undefined')
	{
		$('#content').before(getNotificationHtml('error','Specify coupon code first.'));
		return false;
	}
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller))+'/applyCoupon';
	form_data = {coupon_code : coupon_code, custid : custid};
	$.post(loc, form_data, function (data) {
			var arr = $.parseJSON(data);
			if(arr['type'] == "success")
			{
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
			setTimeout(function(){window.location.reload();},1000);
	});
	hideLoader();
}

/*
*	function will calc totals as per coupon code applied after fetching coupon discount using ajax
*/
function applyAddresses()
{
	showLoader();
	var loc = (base_url+'admin/'+lcFirst(controller))+'/applyAddresses';
	
	var html = '<input type="hidden" name="custid" value="'+custid+'" />';
	$('#form').append( html );
	
	$.post(loc, $('#form').serialize(), function (data)
	{
			var arr = $.parseJSON(data);
			if(arr['type'] == "success")
			{
				$('#content').before( getNotificationHtml(arr['type'], arr['msg']) );
				setTimeout(function(){window.location.reload();},1000);
			}
			else
			{
				$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
			}
	});
	
	hideLoader();
}

</script>