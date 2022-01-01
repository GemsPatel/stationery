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
            	
				$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'coupon_code');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'coupon_type');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'invoice_number');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_qty');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
			 ?>
			  
              <th width="10%" class="left" f="coupon_code" s="<?php echo @$a;?>">Coupon Code</th>
              <th width="25%" class="left" f="customer_firstname" s="<?php echo @$b;?>">Customer Name</th>
              <th width="10%" class="left" f="coupon_type" s="<?php echo @$c;?>">Discount type</th>
              <th width="20%" class="left" f="invoice_number" s="<?php echo @$d;?>">Invoice No</th>
              <th width="10%" class="right" f="order_total_amt" s="<?php echo @$e;?>">Total Amount</th>
              <th width="10%" class="right" f="order_total_qty" s="<?php echo @$f;?>">Total Qty</th>
              <th width="15%" class="right" f="order_created_date" s="<?php echo @$g;?>">Order Date</th>
            </tr>
            
            <tr class="filter">
              
              <td class="left" valign="top"><input type="text" size="20" name="coupon_code_filter" value="<?php echo (@$coupon_code_filter);?>"></td>
              <td class="right"></td>
              <td class="left" valign="top"><input type="text" size="20" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="20" name="invoice_filter" value="<?php echo (@$invoice_filter);?>"></td>
           	  <td class="right">
                <span>From: </span><input type="text"  name="from_range_pr" size="10" value="<?php echo (@$from_range_pr);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pr"  value="<?php echo (@$to_range_pr);?>" />
              </td>
              <td class="right">
                <span>From: </span><input type="text"  name="from_range_tq" size="10" value="<?php echo (@$from_range_tq);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_tq"  value="<?php echo (@$to_range_tq);?>" />
              </td>
             
             <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
             
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><?php echo $ar['coupon_code'];?></td>
             
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['customer_id'])); ?>"><?php  echo $ar['customer_firstname'];?> <?php  echo $ar['customer_lastname'];?></a></td>
              <td class="left"><?php echo $ar['coupon_type'];?>(<?php echo $ar['coupon_discount_amt'];?>)</td>
              <td class="left"><?php echo $ar['invoice_number'];?></td>
              <td class="right"><?php echo lp($ar['order_total_amt']);?></td>
              <td class="right"><?php echo $ar['order_total_qty'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td> 
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


