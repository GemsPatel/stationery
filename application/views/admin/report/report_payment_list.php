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
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'payment_method_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'payment_method_description');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'payment_method_created_date');
				
			 ?>
			  
              <th width="30%" class="left" f="payment_method_name" s="<?php echo @$a;?>">Name</th>
              <th width="55%" class="left" f="payment_method_description" s="<?php echo @$b;?>">Description</th>
              <th width="15%" class="right" f="payment_method_created_date" s="<?php echo @$c;?>">Date</th>
              
            </tr>
            
             <tr class="filter">
              <td class="left" valign="top">
               <?php 
					
					 	$sql = "SELECT payment_method_id, payment_method_name FROM payment_method WHERE payment_method_status=0";
					  	$user_gpArr = getDropDownAry($sql,"payment_method_id", "payment_method_name", array('' => "Select Payment Method"), false);
					 	$user_group_ids =(@$payment_method_id)? $payment_method_id: @$_POST['payment_method_id']; 
						echo form_dropdown('payment_method_id',@$user_gpArr,$user_group_ids,'class=""');
					  ?>
              </td>
              <td class="right"></td>
             
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
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><?php echo $ar['payment_method_name'];?></td>
              <td class="left"><?php echo $ar['payment_method_description'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['payment_method_created_date']);?></td> 
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


