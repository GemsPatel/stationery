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
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_status_id');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="10%" class="left" f="invoice_number" s="<?php echo @$a;?>">Invoice Number</th>
              <th width="15%" class="left" f="customer_firstname" s="<?php echo @$b;?>">Customer Name</th>
              <th width="20%" class="left" f="customer_emailid" s="<?php echo @$g;?>">Email</th>
              <th width="10%" class="left" f="payment_method_id" s="<?php echo @$c;?>">Payment Method</th>
              <th width="10%" class="left" f="order_status_id" s="<?php echo @$d;?>">Status</th>
              <th width="11%" class="right" f="order_total_amt" s="<?php echo @$e;?>">Total Amount</th>
              <th width="14%" class="right" f="order_created_date" s="<?php echo @$f;?>">Date</th>
              <td width="7%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td></td>
              <td class="left" valign="top"><input type="text" name="invoice_number_filter" value="<?php echo (@$invoice_number_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>" ></td>
              <td class="left" valign="top"><input type="text" name="customer_email_filter" value="<?php echo (@$customer_email_filter);?>" ></td>
              <td class="left" valign="top"><input type="text" name="payment_method_filter" value="<?php echo (@$payment_method_filter);?>"></td>
              <td class="left" valign="top"><?php echo getOrderStatusDropdown(@$status_filter); ?></td>
              <td class="right">
                <span>From: </span><input type="text" name="fromamt_filter" value="<?php echo (@$fromamt_filter);?>" size="12"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toamt_filter" value="<?php echo (@$toamt_filter);?>" size="12"></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>"></td>
                <td align="right" valign="top"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
                 
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar['invoice_number'];?></td>
              <td class="left"><?php echo $ar['customer_firstname'].' '.$ar['customer_lastname'];?></td>
              <td class="left"><?php echo $ar['customer_emailid'];?></td>
              <td class="left"><?php echo $ar['payment_method_name'];?></td>
              <td class="left"><?php echo $ar['order_status_name'];?></td>
              <td class="right"><?php echo lp($ar['order_total_amt']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td>
              <td class="right"> [<a href="<?php echo site_url('admin/'.$this->controller.'/invoice?view=true&item_id='._en($ar[$this->cAutoId]));?>" title="View">View</a>]&nbsp; &nbsp;<?php if($this->per_edit == 0):?>[<a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a>]  <?php endif;?> </td>
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