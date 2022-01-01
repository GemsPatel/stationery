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
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_emailid');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_phoneno');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_group_name');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_total_amt');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_created_date');
				
				
			  ?>
              <th width="20%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Customer Name</th>
              <th width="20%" class="left" f="customer_emailid" s="<?php echo @$c;?>" >Email ID</th>
              <th width="15%" class="left" f="customer_phoneno" s="<?php echo @$d;?>">Phone No</th>
              <th width="10%" class="left" f="customer_group_name" s="<?php echo @$e;?>" >Group</th>
              <th width="5%" class="right">No. of Orders </th>
              <th width="5%" class="right">No. of Products</th>
              <th width="10%" class="right" f="order_total_amt" s="<?php echo @$f;?>" >Price</th>
              <th width="15%" class="right" f="order_created_date" s="<?php echo @$g;?>" >Date</th>
            </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="30" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
               <td class="left" valign="top"><input type="text" size="20" name="customer_emailid_filter" value="<?php echo (@$customer_emailid_filter);?>"></td>
               <td class="left" valign="top"><input type="text" size="20" name="customer_phoneno_filter" value="<?php echo (@$customer_phoneno_filter);?>"></td>
               <td class="left" valign="top">
               <?php 
					
					 	$sql = "SELECT customer_group_id, customer_group_name FROM customer_group";
					  	$user_gpArr = getDropDownAry($sql,"customer_group_id", "customer_group_name", array('' => "Select Group"), false);
					 	$user_group_ids =(@$customer_group_id)? $customer_group_id: @$_POST['customer_group_id']; 
						echo form_dropdown('customer_group_id',@$user_gpArr,$user_group_ids,'class=""');
					  ?>
              </td>
              
               <td class="right"></td>
               <td class="right"></td>
               
              <td class="right">
                <span>From: </span><input type="text"  name="from_range"  size="10" value="<?php echo (@$from_range);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range"  value="<?php echo (@$to_range);?>" />
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
				//die();
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
               
               <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar[$this->cAutoId])); ?>"><?php  echo $ar['customer_firstname'];?> <?php  echo $ar['customer_lastname'];?></a></td>
               <td class="left"><?php echo $ar['customer_emailid'];?></td>
               <td class="left"><?php  echo $ar['customer_phoneno'];?></td>
               <td class="left"><?php echo $ar['customer_group_name'];?></td>
               <td class="right"><?php echo $ar['orders'];?></td>
               <td class="right"><?php echo $ar['products'];?></td>
               <td class="right"><?php echo $ar['order_total_amt'];?></td>
               <td class="right"><?php  echo formatDate('d m, Y <b>h:i a</b>',$ar['order_created_date']);?></td>
              
             
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


