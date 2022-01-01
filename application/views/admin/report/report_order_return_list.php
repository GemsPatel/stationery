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
				$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_id');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_return_reason');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_details_product_price');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_details_product_qty');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
			 ?>
			  
              
              <th width="15%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Customer Name</th>
              <th width="20%" class="left" f="product_name" s="<?php echo @$b;?>">Product Name</th>
              <th width="10%" class="left" f="order_id" s="<?php echo @$c;?>">Order Id</th>
              <th width="20%" class="left" f="order_return_reason" s="<?php echo @$d;?>">Return Reason</th>
              <th width="10%" class="right" f="order_details_product_price" s="<?php echo @$e;?>">Price</th>
              <th width="10%" class="right" f="order_details_product_qty" s="<?php echo @$f;?>">Total Qty</th>
              <th width="15%" class="right" f="order_return_created_date" s="<?php echo @$g;?>">Order Date</th>
            </tr>
            
            <tr class="filter">
              
              <td class="left" valign="top"><input type="text" size="20" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="20" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="20" name="order_id_filter" value="<?php echo (@$order_id_filter);?>"></td>
              
              <td class="left"></td>
              <td class="right">
                <span>From: </span><input type="text"  name="from_range_pr" size="10" value="<?php echo (@$from_range_pr);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pr"  value="<?php echo (@$to_range_pr);?>" />
              </td>
              <td class="right">
                <span>From: </span><input type="text"  name="from_range_tq" size="10" value="<?php echo (@$from_range_tq);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_tq"  value="<?php echo (@$to_range_tq);?>" />
              </td>
             <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" size="10" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" size="10" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
             
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		 ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
                           
             <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['customer_id'])); ?>"><?php  echo $ar['customer_firstname'];?> <?php  echo $ar['customer_lastname'];?></a></td>
              
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemProduct?item_id='._en($ar['product_id'])); ?>"><?php  echo $ar['product_name'];?></a></td>
              <td class="left"><?php echo $ar['order_id'];?></td>
              <td class="left"><?php echo $ar['order_return_reason_key'];?></td>
              <td class="right"><?php echo $ar['order_details_product_price'];?></td>
              <td class="right"><?php echo $ar['order_details_product_qty'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_return_created_date']);?></td> 
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


