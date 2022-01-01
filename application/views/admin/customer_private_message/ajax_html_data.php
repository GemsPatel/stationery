
      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'pm_email');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'pm_phone');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'pm_status');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'pm_ip_address');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'pm_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="10%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Customer Name</th>
              <th width="10%" class="left" f="pm_email" s="<?php echo @$b;?>">Customer Email</th>
              <th width="10%" class="left" f="pm_phone" s="<?php echo @$c;?>">Customer Phone</th>
              <td width="30%" class="left">Message</td>
              <th width="7%" class="left" f="pm_status" s="<?php echo @$d;?>">Status</th>
              <th width="10%" class="left" f="pm_ip_address" s="<?php echo @$e;?>">IP Address</th>
              <th width="15%" class="left" f="pm_created_date" s="<?php echo @$f;?>">Date</th>
              <td width="5%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td></td>
              <td class="left" valign="top"><input type="text" name="username_filter" value="<?php echo (@$username_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="email_filter" value="<?php echo (@$email_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="phone_filter" value="<?php echo (@$phone_filter);?>"></td>
              <td class="left"></td>
              <td class="left" valign="top">
              		<select name="status_filter" id="status_filter">
                        <option value="" selected="selected"></option>
                        <option value="O" <?php echo (@$status_filter=='O')?'selected="selected"':'';?>>Open</option>
                        <option value="C" <?php echo (@$status_filter=='C')?'selected="selected"':'';?>>Closed</option>
                    </select>
              </td>
              <td class="left" valign="top"><input type="text" name="ip_filter" value="<?php echo (@$ip_filter);?>"></td>
              <td class="left">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
              <td align="right" valign="top"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
            
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"></td>
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewCustomerDetails?customer_id='._en($ar['customer_id'])); ?>"><?php echo ($ar['customer_id'] != 0)? ($ar['customer_firstname'].' '.$ar['customer_lastname']):$ar['pm_name'];?></a></td>
              <td class="left"><?php echo $ar['pm_email'];?></td>
              <td class="left"><?php echo $ar['pm_phone'];?></td>
              <td class="left"><?php echo $ar['pm_message'];?></td>
              <td class="left"><?php echo ($ar['pm_status']=='O') ? 'Open' : 'Closed';?></td>
              <td class="left"><?php echo $ar['pm_ip_address'];?></td>
              <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['pm_created_date']);?></td>
              <td class="right">
              <a href="<?php echo site_url('admin/'.$this->controller.'/viewPrivateMsgDetails?pm_email='._en($ar['pm_email'])); ?>"><img src="<?php echo asset_url('images/admin/information.png')?>" height="16" width="16" alt="view" title="view private message details"/></a>&nbsp;&nbsp;<a href="<?php echo site_url('admin/email_system/sendEmailForm?private_email='._en($ar['pm_email']))?>"><img src="<?php echo asset_url('images/admin/mail.png')?>" height="16" width="16" alt="send mail" title="send private message email"/></a>
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