<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/orderReturnForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Return Details</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Order Id:</td>
                <td><input type="text" name="order_id" onchange="fetchOrderDetails(this.value)" value="<?php echo (@$order_id)?$order_id:set_value('order_id');?>" style="width:10%;">
				<span class="error_msg"><?php echo (@$error)?form_error('order_id'):''; ?></span>
                </td>
              </tr>
              <tr>
              	<td><span class="required">*</span> Products</td>
                <td id="td_order_details">
				<?php
					$order_id = (@$order_id)?$order_id:set_value('order_id');
					$order_details_id = (@$order_details_id)?$order_details_id:set_value('order_details_id');
					$order_return_quantity = (@$order_return_quantity)?$order_return_quantity:set_value('order_return_quantity');
                	if(isset($order_id) && (int)$order_id!=0):
						echo $this->sor->fetchOrderDetails( $order_id, @$order_details_id );
					else:
				?>
                <select name="order_details_id" style="width:16%;"><option value="" >- Select Product -</option></select> 
                <?php
					endif;
				?>
                <span class="error_msg"><?php echo (@$error)?form_error('order_details_id'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td> Return Quantity:</td>
                <td id="td_order_return_quantity">
				<?php
                	if(isset($order_details_id) && (int)$order_details_id!=0):
                		echo $this->sor->fetchQuantity( $order_details_id, @$order_return_quantity ); 
					else: 
				?>
                <select name="order_return_quantity" style="width:16%;"><option value="">- Select Qty -</option></select>
                <?php
					endif;
				?>
                <span class="error_msg"><?php echo (@$error)?form_error('order_return_quantity'):''; ?></span>
                </td>
              </tr>
              <tr>
              	<td><span class="required">*</span> Return Reason</td>
                <td>
				<?php
					$order_return_reason_key = (@$order_return_reason_key)?$order_return_reason_key:@$_POST['order_return_reason_key'];
					$sql = "SELECT orr_key, orr_name FROM order_return_reason WHERE orr_status = 0 ";
					$reaArr = getDropDownAry($sql,"orr_key", "orr_name", array('' => "Select Return Reason"), false);
					echo form_dropdown('order_return_reason_key',$reaArr,@$order_return_reason_key,'style="width:16%;"'); 
				?>
                   	<span class="error_msg"><?php echo (@$error)?form_error('order_return_reason_key'):''; ?></span>
                </td>
              </tr>
              
              <!-- Cloudwebs: ON 04-05-2015 return action removed -->
              <inpu name="order_return_action" type="hidden" value="-"/>
              
              <!-- 
              <tr>
              	<td><span class="required">*</span> Return Action</td>
                <td>
					<?php
						//$order_return_action = (@$order_return_action)?$order_return_action:@$_POST['order_return_action'];
					?>                	
                    <select name="order_return_action" style="width:10%;">
                	<option value="C" <?php //echo ($order_return_action=='C')?'selected="selected"':''; ?>>Credit Issued</option>
                	<option value="R" <?php //echo ($order_return_action=='R')?'selected="selected"':''; ?>>Refunded</option>
                	<option value="S" <?php //echo ($order_return_action=='S')?'selected="selected"':''; ?>>Replacement Sent</option>
                    </select>
                   	<span class="error_msg"><?php //echo (@$error)?form_error('order_return_action'):''; ?></span>
                </td>
              </tr>
              -->
               
              <tr>
              	<td><span class="required">*</span> Return Statuses</td>
                <td>
				<?php
					$order_status_id = (@$order_status_id)?$order_status_id:@$_POST['order_status_id'];
					//echo getOrderStatusDropdown(@$order_status_id,'style="width:10%;"');
				?>
					<select <?php echo ( empty($this->cPrimaryId) )?'':'disabled="disabled"'; ?> name="order_status_id" style="width:16%;">
						<option>Select</option>
						<option value="9" <?php echo ( ( isset( $order_status_id ) && $order_status_id == 9 ) || ( isset( $_POST["order_status_id"] ) && $_POST["order_status_id"] == 9 )  ) ? 'selected="selected"':''; ?>>ORDER_PEFUNDED</option>
				       	<option value="23" <?php echo ( ( isset( $order_status_id ) && $order_status_id == 23 ) || ( isset( $_POST["order_status_id"] ) && $_POST["order_status_id"] == 23 ) )? 'selected="selected"':''; ?>>ORDER_REFUND_BUCKS</option>
				    </select> (Warning: Selection can not be edited once saved.)
				   
                   	<span class="error_msg"><?php echo (@$error)?form_error('order_status_id'):''; ?></span>
                </td>
              </tr>
            </tbody></table>
            
        </fieldset>
        </div>
        
        
        
      </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
/*
+----------------------------------------------+
	function will fetch products orderd in particular order
+----------------------------------------------+
*/
	function fetchOrderDetails(order_id)
	{
		form_data = {order_id : order_id};
		var loc = (base_url+'admin/'+lcFirst(controller))+'/fetchOrderDetails';
		$.post(loc, form_data, function (data)
		{
			var html = $.parseJSON(data);
			$('#td_order_details').html(html);
		});
	}

/*
+----------------------------------------------+
	function will fetch products orderd in particular order
+----------------------------------------------+
*/
	function fetchQuantity(order_details_id)
	{
		form_data = {order_details_id : order_details_id};
		var loc = (base_url+'admin/'+lcFirst(controller))+'/fetchQuantity';
		$.post(loc, form_data, function (data)
		{
			var html = $.parseJSON(data);
			$('#td_order_return_quantity').html(html);
		});
	}
</script>