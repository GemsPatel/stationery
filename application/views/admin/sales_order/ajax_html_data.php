<link rel="stylesheet" href="<?php echo site_url('css/font-awesome.css') ?>" />
      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'invoice_number');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'payment_method_id');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'ip_address');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="4%" class="left" f="invoice_number" s="<?php echo @$a;?>">Invoice No.</th>
              <th width="10%" class="left" f="customer_firstname" s="<?php echo @$b;?>">Customer Name</th>
              <th width="13%" class="left" f="customer_emailid" s="<?php echo @$g;?>">Email</th>
              <th width="10%" class="left" f="payment_method_id" s="<?php echo @$c;?>">Payment Method</th>
              <td width="10%" class="left" f="order_status_id" s="<?php echo @$o;?>">Order Status</td>
              <th width="5%" class="left" f="ip_address" s="<?php echo @$g;?>">Ip Address</th>
              <th width="10%" class="right" f="order_total_amt" s="<?php echo @$e;?>">Total Amount</th>
              <th width="13%" class="right" f="order_created_date" s="<?php echo @$f;?>">Date</th>
              <td width="22%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td></td>
              <td class="left" valign="top"><input type="text" name="invoice_number_filter" value="<?php echo (@$invoice_number_filter);?>" size="6"></td>
              <td class="left" valign="top"><input type="text" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>" size="12"></td>
              <td class="left" valign="top"><input type="text" name="customer_email_filter" value="<?php echo (@$customer_email_filter);?>" size="12"></td>
              <td class="left" valign="top"><input type="text" name="payment_method_filter" value="<?php echo (@$payment_method_filter);?>" size="12"></td>
              <td class="left" valign="top">
              	<?php
					$orderArr = getDropDownAry( "SELECT order_status_id, order_status_name FROM order_status","order_status_id", "order_status_name",
											  array('' => "Select Status"), false);
					echo form_dropdown('order_status_id',$orderArr, @$order_status_id, ' id="order_status_id" style="width:110px;" ');
				?>
              </td>
              <td class="left" valign="top"><input type="text" name="ip_address_filter" value="<?php echo (@$ip_address_filter);?>" size="10"></td>
              <td class="right">
                <span>From: </span><input type="text" name="fromamt_filter" value="<?php echo (@$fromamt_filter);?>" size="10"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toamt_filter" value="<?php echo (@$toamt_filter);?>" size="10"></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>" size="10"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>" size="10"></td>
                <td align="right" valign="top"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
                 
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
					$customer_access_validation_token = GetCustomerToken( $ar['customer_id'] );
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar['invoice_number'];?></td>
              <td class="left"><?php echo $ar['customer_firstname'].' '.$ar['customer_lastname'];?></td>
              <td class="left"><?php echo $ar['customer_emailid'];?></td>
              <td class="left"><?php echo $ar['payment_method_name'];?></td>
              <td class="left"><?php echo cart_hlp_orderLatestStatus( $ar[$this->cAutoId] );?></td>
              <td class="left"><a href="<?php echo redirectIpAddressUrl($ar['ip_address'])?>" target="_blank"><?php echo $ar['ip_address'];?></a></td>
              <td class="right"><?php echo lp($ar['order_total_amt']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td>
              <td class="right"> 
              
              
	              <!-- [<a href="<?php //echo site_url('admin/'.$this->controller.'/invoice?view=true&item_id='._en($ar[$this->cAutoId]));?>" title="View">View</a>]&nbsp; -->
	              
	              [<a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=view&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>" title="View">View</a>]
				  &nbsp;
				  <?php 
				  	if($this->per_edit == 0):
				  		if( !cart_hlp_isOrderCompleted( $ar[$this->cAutoId] ) && !cart_hlp_isOrderCancelled( $ar[$this->cAutoId] ) ):
				  ?>
				  			[<a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=upd_sta&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>" title="Edit">Update Status</a>]
					  		&nbsp;
					  		[<a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=ship&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>" title="Edit">Ship</a>]
					  		&nbsp;
					  		[<a onclick="confirmEditOrder('<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=edit&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>', 'This order will be cancelled and a new one will be generated instead, are you sure to proceed?');" href="javascript:void(0);" title="Edit">Edit</a>]
					  		&nbsp;
					  		[<a onclick="confirmEditOrder('<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=cancel&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>', 'This order will be cancelled, are you sure to proceed?');" href="javascript:void(0);" title="Cancel">Cancel</a>]
					  		&nbsp;
				  <?php
				  		endif;
				  ?>
				  			[<a target="_blank" href="<?php echo site_url("account/order-tracking?oid="._en($ar['order_id'])).'&acc='.$customer_access_validation_token?>">Track</a>] 
				  	  		&nbsp;
				  	  		[<a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=send_mail&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>" title="Edit">Send Mail</a>]
				  <?php 
				  	endif;
				  ?>
				  &nbsp;
				  [<a href="<?php echo site_url('admin/'.$this->controller.'/printInvoice?item_id='._en($ar[$this->cAutoId]) )?>" title="Edit" target="_blank">Invoice</a>]
				  <?php
				  	if( aff_hlp_isSignupAffiliateCreditPostOrderProcessingApplicable($ar[$this->cAutoId], 3) ):
				  ?>
				  		<br>
				  		[<a onclick="confirmEditOrder('<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&act=rel_ref_bns&item_id='._en($ar[$this->cAutoId]).'&custid='._en($ar['customer_id']))?>', 'Are you sure to release bonus to affiliate account, the action can not be undo(revoked) once done?');" href="javascript:void(0);" title="Release Referrel Bonus">Release Referrel Bonus</a>]
				  <?php
				  	endif;
				  ?>
				  
			  </td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='9'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>