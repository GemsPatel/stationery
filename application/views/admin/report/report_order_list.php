<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=modal]').facebox()
})
</script>
	  <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
			  	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_id');
            	//$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'invoice_number');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_gender');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'payment_method_name');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'shipping_method_name');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
			 ?>
			  
              <th width="3%" class="left" f="order_id" s="<?php echo @$a;?>">Order Id</th>
              <!-- <th width="10%" class="left" f="invoice_number" s="<?php //echo @$b;?>">Invoice No</th>-->
              <th width="13%" class="left" f="customer_firstname" s="<?php echo @$c;?>">Customer Name</th>
              <th width="5%" class="left" f="customer_gender" s="<?php echo @$d;?>">Gender</th>
              <th width="10%" class="left" f="payment_method_name" s="<?php echo @$e;?>">Payment Method</th>
              <th width="10%" class="left" f="shipping_method_name" s="<?php echo @$f;?>">Shipping Method</th>              
              <th width="10%" class="right" f="order_total_amt" s="<?php echo @$g;?>">Total Ammount</th>
              <th width="15%" class="right" f="order_created_date" s="<?php echo @$h;?>">Order Date</th>
              <th width="5%" class="right">Action</th>
            </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="20" name="order_id_filter" value="<?php echo (@$order_id_filter);?>"></td>
              <!-- <td class="left" valign="top"><input type="text" size="20" name="invoice_number_filter" value="<?php //echo (@$invoice_number_filter);?>"></td>-->
              <td class="left" valign="top"><input type="text" size="20" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
             <td class="left" valign="top"><select name="gender_filter" id="gender_filter">
                                    <option value="" selected="selected">Select Gender</option>
                                    <option value="M" <?php echo (@$gender_filter=='M')?'selected="selected"':'';?>>Male</option>
                                    <option value="F" <?php echo (@$gender_filter=='F')?'selected="selected"':'';?>>Felame</option>
                                </select>
             </td>
              <td class="left" valign="top">
               <?php 
					
					 	$sql = "SELECT payment_method_id, payment_method_name FROM payment_method WHERE payment_method_status=0";
					  	$user_gpArr = getDropDownAry($sql,"payment_method_id", "payment_method_name", array('' => "Select Payment Method"), false);
					 	$user_group_ids =(@$payment_method_id)? $payment_method_id: @$_POST['payment_method_id']; 
						echo form_dropdown('payment_method_id',@$user_gpArr,$user_group_ids,'class=""');
					  ?>
              </td>
              <td class="left" valign="top">
               <?php 
					
					 	$sql = "SELECT shipping_method_id, shipping_method_name FROM shipping_method WHERE shipping_method_status=0";
					  	$user_gpArr = getDropDownAry($sql,"shipping_method_id", "shipping_method_name", array('' => "Select Shipping Method"), false);
					 	$user_group_ids =(@$shipping_method_id)? $shipping_method_id: @$_POST['shipping_method_id']; 
						echo form_dropdown('shipping_method_id',@$user_gpArr,$user_group_ids,'class=""');
					  ?>
              </td>
              <td class="right">
                <span>From: </span><input type="text" size="10"  name="from_range_pr"  value="<?php echo (@$from_range_pr);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pr"  value="<?php echo (@$to_range_pr);?>" />
              </td>
                          
             <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To:</span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
               <td class="right">
               </td>
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
				//pr($listArr); die;
				
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><?php echo $ar['order_id'];?></td>
              <!-- <td class="left"><?php //echo $ar['invoice_number'];?></td>-->
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['customer_id'])); ?>"><?php  echo $ar['customer_firstname'];?> <?php  echo $ar['customer_lastname'];?></a></td>
                         
               <td class="left"><?php  if($ar['customer_gender']=='F'){echo "Female";} else {echo "Male";}?></td>
               <td class="left"><?php  echo $ar['payment_method_name'];?></td>
               <td class="left"><?php  echo $ar['shipping_method_name'];?></td>
              <td class="right"><?php echo lp($ar['order_total_amt']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td> 
         <td align="right" valign="top"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewFullDetails?item_id='._en($ar['order_id'])); ?>"><img src="<?php echo asset_url('images/admin/information.png')?>" height="16" width="16" alt="view" title="view private message details"/></a></td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='11'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>

      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>


