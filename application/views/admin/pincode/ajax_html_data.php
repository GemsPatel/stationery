      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'state_id');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'cityname');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'areaname');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'pincode');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'pincode_status');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="20%" class="left" f="state_id" s="<?php echo @$a;?>">State</th>
              <th width="20%" class="left" f="cityname" s="<?php echo @$b;?>">City Name</th>
              <th width="20%" class="left" f="areaname" s="<?php echo @$c;?>">Area Name</th>
              <th width="20%" class="right" f="pincode" s="<?php echo @$d;?>">Pincode</th>
              <th width="10%" class="right" f="pincode_status" s="<?php echo @$e;?>">Status</th>
              <td width="7%" class="right">Action</td>
            </tr> 
            <tr class="filter">
              	<td class="right"></td>	
                 <td class="left"><input type="text" size="20" name="text_state" value="<?php echo (@$text_state);?>" id="text_state"></td>
                <td class="left"><input type="text" size="20" name="text_city" value="<?php echo (@$text_city);?>" id="text_city"></td>
                 <td class="left"><input type="text" size="20" name="text_area" value="<?php echo (@$text_area);?>" id="text_area"></td>
                  <td class="right"><input type="text" size="20" name="text_pincode" value="<?php echo (@$text_pincode);?>" id="text_pincode"></td>
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
              <td class="left"><?php echo  getField('state_name','state','state_id',$ar['state_id']);?></td>
              <td class="left"><?php echo $ar['cityname'];?></td>
              <td class="left"><?php echo $ar['areaname'];?></td>
              <td class="right"><?php echo $ar['pincode'];?></td>
              <td class="right">
			  <?php if($ar['pincode_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/pincodeForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>
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