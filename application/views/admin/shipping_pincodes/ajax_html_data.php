      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'shipping_method_id');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'pincode_id');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'city_name');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'service_type');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'service_type_code');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'cod_limit');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'prepaid_limit');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'shipping_pincodes_status');
				
			  ?>
          	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="shipping_method_id" s="<?php echo @$a;?>"> Shipping Method</th>
              <th class="left" f="pincode_id" s="<?php echo @$b;?>"> Pincode </th>
              <th class="left" f="city_name" s="<?php echo @$c;?>"> City Name</th>
              <th class="left" f="service_type" s="<?php echo @$d;?>"> Service Type</th>
              <th class="left" f="service_type_code" s="<?php echo @$e;?>"> Service Type Code</th>
              <th class="right" f="cod_limit" s="<?php echo @$f;?>"> Cod Limit</th>
              <th class="right" f="prepaid_limit" s="<?php echo @$g;?>"> Prepaid Limit</th>
              <th class="right" f="shipping_pincodes_status" s="<?php echo @$h;?>">Status</th>
              <td class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left"> 
			  <?php 
					
					 	$sql = "SELECT shipping_method_id ,shipping_method_name FROM shipping_method WHERE shipping_method_status=0";
					  	$ship_pinArr = getDropDownAry($sql,"shipping_method_id", "shipping_method_name", array('' => "Select Shipping Method"), false);
					 	$ship_pin_ids =(@$shipping_method_id)? $shipping_method_id: @$_POST['shipping_method_id']; 
						echo form_dropdown('shipping_method_id',@$ship_pinArr,$ship_pin_ids,'class=""');
			 ?>
             </td>
              <td class="left"><input type="text" size="20" name="text_pincode" value="<?php echo (@$text_pincode);?>" id="text_pincode"></td>
              <td class="left"><input type="text" size="20" name="text_city_name" value="<?php echo (@$text_city_name);?>" id="text_city_name"></td>
              <td class="left"><input type="text" size="20" name="text_service_type" value="<?php echo (@$text_service_type);?>" id="text_service_type"></td>
              <td class="left"><input type="text" size="20" name="text_service_type_code" value="<?php echo (@$text_service_type_code);?>" id="text_service_type_code"></td>
              <td class="right">
                <span>From: </span><input type="text"  name="from_range_cod"  size="10" value="<?php echo (@$from_range_cod);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_cod"  value="<?php echo (@$to_range_cod);?>" />
              </td>
              <td class="right">
                <span>From: </span><input type="text"  name="from_range_pre"  size="10" value="<?php echo (@$from_range_pre);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range_pre"  value="<?php echo (@$to_range_pre);?>" />
              </td>
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
                <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"></td>
                <td class="left"><?php echo $ar['shipping_method_name']?></td>
                <td class="left"><?php echo $ar['pincode']?></td>
                <td class="left"><?php echo $ar['city_name']?></td>
                <td class="left"><?php echo $ar['service_type']?></td>
                <td class="left"><?php echo $ar['service_type_code']?></td>
                <td class="right"><?php echo $ar['cod_limit']?></td>
                <td class="right"><?php echo $ar['prepaid_limit']?></td>
               <td class="right">
                <?php if($ar['shipping_pincodes_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/shippingPincodesForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ] <?php endif;?> </td>
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