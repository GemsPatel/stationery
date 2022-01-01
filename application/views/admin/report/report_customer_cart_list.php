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
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_emailid');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_phoneno');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_price_id');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_generated_code');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_created_date');
			  ?>
              <th width="20%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Customer Name</th>
              <th width="15%" class="left" f="customer_emailid" s="<?php echo @$b;?>">Email Id</th>
              <th width="15%" class="left" f="customer_phoneno" s="<?php echo @$c;?>">Phone No</th>
              <th width="25%" class="left" f="product_price_id" s="<?php echo @$d;?>">Cart List Product</th>
              <th width="10%" class="left" f="product_generated_code" s="<?php echo @$e;?>">Product Code</th>
              <th width="15%" class="right" f="customer_created_date" s="<?php echo @$f;?>" >Date</th>
           </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="25" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="25" name="customer_emailid_filter" value="<?php echo (@$customer_emailid_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="25" name="customer_phoneno_filter" value="<?php echo (@$customer_phoneno_filter);?>"></td>
              <td class="left" valign="top">
               <?php 
					 	$sql = "SELECT product_id, product_name FROM product WHERE product_status=0";
					  	$proArr = getDropDownAry($sql,"product_id", "product_name", array('' => "Select Product"), false);
					 	$product_ids =(@$product_id)? $product_id: @$_POST['product_id']; 
						echo form_dropdown('product_id',@$proArr,$product_ids,'class=""');
			  ?>
              </td>
              <td class="left" valign="top"><input type="text" size="25" name="product_code_filter" value="<?php echo (@$product_code_filter);?>"></td>
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
				//pr($listArr);
				//die;
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
             <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['customer_id'])); ?>"><?php  echo $ar['customer_firstname'];?> <?php  echo $ar['customer_lastname'];?></a></td>
              <td class="left"><?php echo $ar['customer_emailid'];?></td>
              <td class="left"><?php echo $ar['customer_phoneno'];?></td>
             <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemProduct?item_id='._en($ar['product_id'])); ?>"><?php echo $ar['product_name'];?></a></td>
              <td class="left"><?php echo $ar['product_generated_code'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['customer_created_date']);?></td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='8'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>

      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>


