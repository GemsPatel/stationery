     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_emailid');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_phoneno');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_group_name');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_ip_address');
				$i = get_sort_order($this->input->get('s'),$this->input->get('f'),'el_reference_source');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_approved');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_status');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="15%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Name</th>
              <th width="15%" class="left" f="customer_emailid" s="<?php echo @$b;?>">Email</th>
              <th width="10%" class="left" f="customer_phoneno" s="<?php echo @$c;?>">Phone</th>
              <th width="7%" class="left" f="customer_group_name" s="<?php echo @$d;?>">Group</th>
              <th width="13%" class="left" f="el_reference_source" s="<?php echo @$i;?>">Reference</th>
              <th width="7%" class="left" f="customer_ip_address" s="<?php echo @$h;?>">IP Address</th>
              <th width="5%" class="left" f="customer_approved" s="<?php echo @$e;?>">Approved</th>
              <th width="5%" class="right" f="customer_status" s="<?php echo @$f;?>">Status</th>
              <th width="15%" class="right" f="customer_created_date" s="<?php echo @$g;?>">Date</th>
              <td width="5%" class="right">Action</td>
            </tr> 
             <tr class="filter" valign="top">
              	<td class="right"></td>
                <td class="left"><input type="text" name="name_filter" value="<?php echo (@$name_filter);?>"></td>
                <td class="left"><input type="text" name="emailid_filter" value="<?php echo (@$emailid_filter);?>"></td>
                <td class="left"><input type="text" name="phoneno_filter" value="<?php echo (@$phoneno_filter);?>"></td>
                <td class="left">
					<?php 
                    $group_filter = (@$group_filter) ? $group_filter : @$_POST['group_filter'];
                    $sql = "SELECT customer_group_id, customer_group_name FROM customer_group WHERE customer_group_status=0";
                    $customerGroupArr = getDropDownAry($sql,"customer_group_name", "customer_group_name", array(''=>''), false);
                    
                    echo form_dropdown('group_filter',@$customerGroupArr,@$group_filter,' ');
                    ?>
                </td>
                <td class="left"><input type="text" name="reference_filter" value="<?php echo (@$reference_filter);?>"></td>
                <td class="left"><input type="text" name="ip_filter" value="<?php echo (@$ip_filter);?>"></td>
                <td class="left">
                	<select name="approved_filter">
                         <option value="" selected="selected"></option>
                         <option value="0" <?php echo (@$approved_filter=='0')?'selected="selected"':'';?>>No</option>
                         <option value="1" <?php echo (@$approved_filter=='1')?'selected="selected"':'';?>>Yes</option>
                    </select>
                </td>
              	<td class="right">
                	<select name="status_filter" id="status_filter">
                         <option value="" selected="selected"></option>
                         <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                         <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                    </select>
               	</td>
                <td class="right">
                    <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                    <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
                </td>
              	<td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>   
               
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
			 ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><a href="<?php echo site_url( 'account?acc='.GetCustomerToken( $ar[$this->cAutoId] ) );?>" target="_blank"><?php echo $ar['customer_firstname'].' '.$ar['customer_lastname'];?></a></td>
              <td class="left"><?php echo $ar['customer_emailid'];?></td>
              <td class="left"><?php echo $ar['customer_phoneno'];?></td>
              <td class="left"><?php echo $ar['customer_group_name'];?></td>
              <td class="left"><?php echo $ar['el_reference_source'];?></td>
              <td class="left"><a href="<?php echo redirectIpAddressUrl($ar['customer_ip_address'])?>" target="_blank"><?php echo $ar['customer_ip_address'];?></a></td>
              <td class="left"><?php echo ($ar['customer_approved']=='0')? 'No' : 'Yes';?></td>
              <td class="right">
                <?php if($ar['customer_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['customer_created_date']);?></td>
              <td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/customerForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>              
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