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
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_firstname');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'am_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_log_type');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_log_ip');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_log_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="15%" class="left" f="admin_user_firstname" s="<?php echo @$a;?>">User Name</th>
              <th width="15%" class="left" f="am_name" s="<?php echo @$b;?>">Module Name</th>
              <td width="37%" class="left">Item Name</td>
              <th width="5%" class="left" f="admin_log_type" s="<?php echo @$c;?>">Type</th>
              <th width="10%" class="left" f="admin_log_ip" s="<?php echo @$d;?>">IP Address</th>
              <th width="15%" class="left" f="admin_log_created_date" s="<?php echo @$e;?>">Date</th>
            </tr>
            
            <tr class="filter" valign="top">
              <td class="left"></td>
              <td class="left"><input type="text" size="25" name="username_filter" value="<?php echo (@$username_filter);?>"></td>
              <td class="left"><input type="text" size="25" name="module_filter" value="<?php echo (@$module_filter);?>"></td>
              <td class="left"><input type="text" size="40" name="item_filter" value="<?php echo (@$item_filter);?>"></td>
              <td class="left"><select name="log_type_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="A" <?php echo (@$log_type_filter=='A')?'selected="selected"':'';?>>Add</option>
                                    <option value="E" <?php echo (@$log_type_filter=='E')?'selected="selected"':'';?>>Edit</option>
                                    <option value="V" <?php echo (@$log_type_filter=='V')?'selected="selected"':'';?>>View</option>
                                    <option value="D" <?php echo (@$log_type_filter=='D')?'selected="selected"':'';?>>Delete</option>
                                </select></td>
              <td class="left"><input type="text" size="20" name="ip_filter" value="<?php echo (@$ip_filter);?>"></td>
              <td class="left">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
				
				$strType = '';
				if($ar['admin_log_type'] == 'A')
					$strType = 'Add';
				elseif($ar['admin_log_type'] == 'E')
					$strType = 'Edit';
				elseif($ar['admin_log_type'] == 'D')
					$strType = 'Delete';
				elseif($ar['admin_log_type'] == 'V')
					$strType = 'View';	
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewUserDetails?user_id='._en($ar['admin_user_id'])); ?>" title="view user detail"><?php echo ($ar['admin_user_firstname'].' '.$ar['admin_user_lastname']);?></a></td>
              <td class="left"><?php echo $ar['am_name'];?></td>
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemDetails?item_id='._en($ar[$this->cAutoId])); ?>" title="view item detail"><?php echo $ar['module_item_name'];?></a></td>
              <td class="left"><?php echo $strType;?></td>
              <td class="left"><?php echo $ar['admin_log_ip'];?></td>
              <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['admin_log_created_date']);?></td>              
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='6'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>

      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>


