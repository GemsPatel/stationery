      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_firstname');
            	$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_group_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_emailid');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_phoneno');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_gender');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'customer_fax');
			  ?>
              <th width="30%" class="left" f="customer_firstname" s="<?php echo @$a;?>">Customer Name</th>
              <th width="15%" class="left" f="customer_group_name" s="<?php echo @$b;?>">Customer Group</th>
              <th width="15%" class="left" f="customer_emailid" s="<?php echo @$c;?>">Email</th>
              <th width="10%" class="left" f="customer_phoneno" s="<?php echo @$d;?>">Phone</th>
              <th width="7%" class="left" f="customer_gender" s="<?php echo @$e;?>">Gender</th>
              <th width="13%" class="right" f="customer_fax" s="<?php echo @$f;?>">Fax</th>
              <td width="10%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" name="customer_name_filter" value="<?php echo (@$customer_name_filter);?>" size="30"></td>
              <td class="left" valign="top"><input type="text" name="customer_group_name_filter" value="<?php echo (@$customer_group_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="email_filter" value="<?php echo (@$email_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="phone_filter" value="<?php echo (@$phone_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="gender_filter" value="<?php echo (@$gender_filter);?>" size="10"></td>
              <td class="right" valign="top"><input type="text" name="fax_filter" value="<?php echo (@$fax_filter);?>"></td>
              <td align="right" valign="top"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
                 
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoIdC];?>">
              <td class="left"><?php echo $ar['customer_firstname'].' '.$ar['customer_lastname'];?></td>
              <td class="left"><?php echo $ar['customer_group_name'];?></td>
              <td class="left"><?php echo $ar['customer_emailid'];?></td>
              <td class="left"><?php echo $ar['customer_phoneno'];?></td>
              <td class="left"><?php echo (@$ar['customer_gender'] != '')?(($ar['customer_gender'] == 'M')?'Male':'Female'):'';?></td>
              <td class="right"><?php echo $ar['customer_fax'];?></td>
              <td class="right"> [ <a href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?custid='._en($ar[$this->cAutoIdC]))?>" title="Edit">Select Customer</a> ] </td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='7'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
      </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>