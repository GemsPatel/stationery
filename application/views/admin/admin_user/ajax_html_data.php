     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php			    
               	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_firstname');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_lastname');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_emailid');								
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_group_name');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_status');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_created_date');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_id');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_xmpp_id');
				$i = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_can_chat');
				$j = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_chat_priority');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="15%" class="left" f="admin_user_firstname" s="<?php echo @$a;?>">First Name</th>
              <th width="15%" class="left" f="admin_user_lastname" s="<?php echo @$b;?>">Last Name</th>
              <th width="15%" class="left" f="admin_user_emailid" s="<?php echo @$c;?>">Email Id</th>
              <th width="15%" class="left" f="admin_user_group_name" s="<?php echo @$d;?>">User Group</th>
              <th width="15%" class="right" f="admin_user_status" s="<?php echo @$e;?>">Date Added</th>
              <th width="15%" class="right" f="admin_user_created_date" s="<?php echo @$f;?>">Status</th>
              
              <td width="17%" class="right">Action</td>
            </tr> 
             <tr class="filter">        
                <td class="right"></td>
                <td class="left"><input type="text" size="25" name="text_firstname" value="<?php echo (@$text_firstname);?>" id="text_firstname"></td>
              	<td class="left"><input type="text" size="25" name="text_lastname" value="<?php echo (@$text_lastname);?>" id="text_lastname"></td>
                <td class="left"><input type="text" size="25" name="text_emailid" value="<?php echo (@$text_emailid);?>" id="text_emailid"></td>
                <td class="right"></td>
                <td class="right"></td>
                <td class="right">
                	<select name="status_filter" id="status_filter">
                         <option value="" selected="selected"></option>
                         <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                         <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                    </select>
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
              <td class="left"><?php echo $ar['admin_user_firstname'];?></td>
              <td class="left"><?php echo $ar['admin_user_lastname'];?></td>
              <td class="left"><?php echo $ar['admin_user_emailid'];?></td>
              <td class="left"><?php echo getField('admin_user_group_name','admin_user_group','admin_user_group_id',$ar['admin_user_group_id']);?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['admin_user_created_date']);?></td>
              <td class="right">
                <?php if($ar['admin_user_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/adminUserForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]<?php endif;?> </td>              
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