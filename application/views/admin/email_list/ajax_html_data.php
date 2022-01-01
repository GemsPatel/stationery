     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'email_id');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'el_optout_level');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'el_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'el_reference_source');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'el_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="5%" class="center">#</th>
              <th width="30%" class="left" f="email_id" s="<?php echo @$a;?>">Email</th>
              <th width="10%" class="left" f="el_optout_level" s="<?php echo @$b;?>">Optout Level</th>
              <th width="10%" class="left" f="el_status" s="<?php echo @$c;?>">Status</th>
              <th width="20%" class="left" f="el_reference_source" s="<?php echo @$d;?>">Reference Source</th>
              <th width="15%" class="right" f="el_created_date" s="<?php echo @$e;?>">Date</th>
              <td width="7%" class="right">Action</td>
            </tr>
            <tr class="filter" valign="top">
              <td class="left"></td>
              <td class="left"></td>
              <td class="left"><input type="text" name="email_filter" value="<?php echo (@$email_filter);?>" size="40"></td>
              <td class="left"><select name="optlevel_filter" style="width:150px;">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$optlevel_filter=='0')?'selected="selected"':'';?>>0 - No authority send mail</option>
                                    <option value="1" <?php echo (@$optlevel_filter=='1')?'selected="selected"':'';?>>1 - Email entered by user but still not confirmed by clicking on mail</option>
                                    <option value="2" <?php echo (@$optlevel_filter=='2')?'selected="selected"':'';?>>2 - Registered and confirmed mail allows mailing to end user</option>
                                </select></td>
              <td class="left"><select name="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="N" <?php echo (@$status_filter=='N')?'selected="selected"':'';?>>New</option>
                                    <option value="S" <?php echo (@$status_filter=='S')?'selected="selected"':'';?>>Subscribed</option>
                                    <option value="U" <?php echo (@$status_filter=='U')?'selected="selected"':'';?>>Unsubscribed</option>
                                </select></td>
              <td class="left"><input type="text" name="refsource_filter" value="<?php echo (@$refsource_filter);?>" size="30"></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
              <td class="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody>
           <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):		$start++;
		   ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="center"><?php echo $start;?></td>
              <td class="left"><?php echo $ar['email_id'];?></td>
              <td class="left"><?php echo $ar['el_optout_level'];?></td>
              <td class="left"><?php echo $ar['el_status'];?></td>
              <td class="left"><?php echo $ar['el_reference_source'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['el_created_date']);?></td>  
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/emailListForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ] <?php endif;?> </td>
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