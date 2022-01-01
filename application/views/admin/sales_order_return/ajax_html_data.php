      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_return_id');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_id');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_return_reason_key');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_status_id');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'order_return_created_date');
			  ?>
              <td width="3%" style="text-align: center;">
              	<!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"> -->
              </td>
              <th width="5%" class="left" f="order_return_id" s="<?php echo @$a;?>">Return Id</th>
              <th width="6%" class="left" f="order_id" s="<?php echo @$b;?>">Order Id</th>
              <th width="12%" class="left" f="customer_firstname" s="<?php echo @$c;?>">Customer Name</th>
              <th width="20%" class="left" f="product_name" s="<?php echo @$d;?>">Product</th>
              <td width="25%" class="left" f="order_return_reason_key" s="<?php echo @$e;?>">Reason</td>
              <th width="12%" class="left" f="order_status_id" s="<?php echo @$f;?>">Status</th>
              <th width="13%" class="right" f="order_return_created_date" s="<?php echo @$g;?>">Date</th>
              <td width="4%" class="right">Action</td>
            </tr>
            
            <tr class="filter" valign="top">
              <td></td>
              <td class="left"><input type="text" size="4" name="return_id_filter" value="<?php echo (@$return_id_filter);?>"></td>
              <td class="left"><input type="text" size="10" name="order_id_filter" value="<?php echo (@$order_id_filter);?>"></td>
              <td class="left"><input type="text" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>"></td>
              <td class="left"><input type="text" size="30" name="product_filter" value="<?php echo (@$product_filter);?>"></td>
              <td class="left"><input type="text" size="30" name="reason_filter" value="<?php echo (@$reason_filter);?>"></td>
              <td class="left"><?php echo getOrderStatusDropdown(@$order_status_id); ?></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>" size="20"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>" size="20">
              </td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
            
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td style="text-align: center;">
              	<!-- <input type="checkbox" value="<?php //echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> -->
              </td>
              <td class="left"><?php echo $ar['order_return_id'];?></td>
              <td class="left"><?php echo $ar['order_id'];?></td>
              <td class="left"><?php echo ($ar['customer_id'] != 0) ? ($ar['customer_firstname'].' '.$ar['customer_lastname']):'-';?></td>
              <td class="left"><?php echo $ar['product_name'];?></td>
              <td class="left"><?php echo getField("orr_name","order_return_reason","orr_key",$ar['order_return_reason_key']);?></td>
              <td class="left"><?php echo getField('order_status_name','order_status','order_status_id',@$ar['order_status_id']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['order_return_created_date']);?></td>
              <td class="right">
              <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/orderReturnForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ] <?php endif;?></a>
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