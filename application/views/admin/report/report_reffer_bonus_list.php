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
            	$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'from_member');//customer_firstname
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'to_member');//customer_gender
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_emailid_filter');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
			 ?>
			  
              <th width="1%" class="center" f="order_id" s="<?php echo @$a;?>">Order Id</th>
              <th width="10%" class="center" f="from_member" s="<?php echo @$c;?>">From Member</th>
              <th width="10%" class="center" f="to_member" s="<?php echo @$d;?>">To Member</th>
              <th width="10%" class="center" f="customer_emailid" s="<?php echo @$e;?>" >Email ID</th>
              <th width="5%" class="center" f="order_total_amt" s="<?php echo @$g;?>">Total Ammount</th>
              <th width="10%" class="center" f="order_created_date" s="<?php echo @$h;?>">Order Date</th>
<!--               <th width="10%" class="center">Action</th> -->
            </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="20" name="order_id_filter" value="<?php echo (@$order_id_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="20" name="from_member" value="<?php echo (@$from_member);?>"></td>
              <td class="right" valign="top"></td>
              <td class="left" valign="top"><input type="text" size="20" name="customer_emailid_filter" value="<?php echo (@$customer_emailid_filter);?>"></td>
               <td class="right">
                <span>From: </span><input type="text" size="10"  name="from_range_pr"  value="<?php echo (@$from_range_pr);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pr"  value="<?php echo (@$to_range_pr);?>" />
              </td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To:</span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
<!--                <td class="right"></td> -->
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
				//pr($ar);
				
	    		$partner_id = fetchRow("SELECT customer_id,customer_firstname,customer_lastname FROM customer WHERE customer_id = '".$ar['customer_partner_id']."'" )
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><?php echo $ar['order_id'];?></td>
              <!-- <td class="left"><?php //echo $ar['invoice_number'];?></td>-->
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['customer_id'])); ?>"><?php  echo $ar['customer_firstname']." ".$ar['customer_lastname'];?></a></td>
               <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($partner_id['customer_id'])); ?>"><?php  echo $partner_id['customer_firstname']." ".$partner_id['customer_lastname'];?></a></td>
               <td class="left"><?php  echo $ar['customer_emailid'];?></td>
               <td class="left"><?php  echo lp($ar['c_discount_amt']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td>
              <!-- <td align="right" valign="top"><!-- <a rel="modal" href="<?php //echo site_url('admin/'.$this->controller.'/viewFullDetails?item_id='._en($ar['order_id'])); ?>"><img src="<?php //echo asset_url('images/admin/information.png')?>" height="16" width="16" alt="view" title="view private message details"/></a></td>-->
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


