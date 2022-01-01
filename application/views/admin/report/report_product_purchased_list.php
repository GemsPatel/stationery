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
			  	$grand_totalqty = 0.0;
			  	$grand_totalsp = 0.0;
			  	$total = 0.0; $grand_total = 0.0;
			  	$totalstock = 0; $grand_totalstock = 0.0;
			  	$totalinvest = 0.0; $grand_totalinvest = 0.0;
			  	$item_no = 0;
			  	
			  	$no = get_sort_order($this->input->get('s'),$this->input->get('f'),'Item No.');
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_details_product_qty');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_details_product_price');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_stock');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_investment');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_details_created_date');
				
				
			  ?>
              <th width="1%" class="left" f="product_name" s="<?php echo @$no;?>">Item No.</th>
              <th width="1%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="3%" class="right" f="order_details_product_qty" s="<?php echo @$b;?>" >Order Qty</th>
              <th width="3%" class="right" f="order_details_product_price" s="<?php echo @$c;?>" >Selling Price</th>
              <th width="3%" class="right" f="order_details_product_price" s="<?php echo @$e;?>" >Total</th>
              <th width="3%" class="right" f="order_details_product_price" s="<?php echo @$f;?>" >Stock</th>
              <th width="3%" class="right" f="order_details_product_price" s="<?php echo @$g;?>" >Investment</th>
              <th width="3%" class="right" f="order_details_created_date" s="<?php echo @$d;?>" >Date</th>
           	</tr>
            
            <tr class="filter">
              <td class="left " valign="top"></td>
              <td class="left" valign="top"><input type="text" size="50" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="right">
                <span>From: </span><input type="text" size="10" name="from_range_qty"  value="<?php echo (@$from_range_qty);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_qty"  value="<?php echo (@$to_range_qty);?>" />
              </td>
              <td class="right">
                <span>From: </span><input type="text" size="10" name="from_range_pr"  value="<?php echo (@$from_range_pr);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pr"  value="<?php echo (@$to_range_pr);?>" />
              </td>
              <td class="right">
                <input type="text" size="10" name="from_range_total"  value="<?php echo (@$from_range_total);?>" class="hide"/><br />
                <input type="text" size="10" name="to_range_total" class="hide"  value="<?php echo (@$to_range_total);?>" />
              </td>
              <td class="right">
                <input type="text" size="10" name="from_range_stock"  value="<?php echo (@$from_range_stock);?>" class="hide" /><br />
                <input type="text" size="10" name="to_range_stock" class="hide" value="<?php echo (@$to_range_stock);?>" />
              </td>
              <td class="right">
               <input type="text" size="10" name="from_range_investment" class="hide" value="<?php echo (@$from_range_investment);?>" /><br />
               <input type="text" size="10" name="to_range_investment" class="hide" value="<?php echo (@$to_range_investment);?>" />
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
              <?php 
              	$item_no += 1;
              ?>
              <td class="left"><?php echo $item_no;?></td>
              
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemProduct?item_id='._en($ar['product_id'])); ?>"><?php echo $ar['product_name'];?></a></td>
              <?php 
              	$grand_totalqty += $ar['order_details_product_qty'];
              ?>
              <td class="right"><?php echo $ar['order_details_product_qty'];?></td>
              <?php 
              	$grand_totalsp += $ar['order_details_product_price'];
              ?>
              <td class="right"><?php echo $ar['order_details_product_price'];?></td>
              <?php 
              	$total = $ar['order_details_product_price'] * $ar['order_details_product_qty'];
              	$grand_total += $total;
              ?>
              <td class="right"><?php echo $total;?></td>
              
              <?php 
              	if($ar['product_value_quantity'])
              		$totalstock = $ar['product_value_quantity'];
              	
              	$grand_totalstock += $totalstock;
              ?>
              <td class="right"><?php echo $totalstock;?></td>
              
              <?php 
              	$totalinvest = $totalstock * $ar['product_price'];
              	$grand_totalinvest += $totalinvest;
              ?>
              
              <td class="right"><?php echo $totalinvest;?></td>
              
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_details_created_date']);?></td>
            </tr>
          <?php 
		  		endforeach;
		  		?>
		  	<tr>
		  		<td>TOTAL</td>
		  		<td></td>
		  		<td class="right"><?php echo $grand_totalqty;?></td>
	  			<td class="right"><?php echo $grand_totalsp;?></td>
  		        <td class="right"><?php echo $grand_total;?></td>
  		        <td class="right"><?php  $grand_totalstock;?></td>
  		        <td class="right"><?php  $grand_totalinvest;?></td>
  		        <td class="right"></td>
  		   </tr>
		   <?php 
		   else:
			 	echo "<tr><td class='center' colspan='5'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>

      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>


