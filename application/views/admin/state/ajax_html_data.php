     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
        	<tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'state_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'state_key');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'country_name');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'state_status');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="15%" class="left" f="state_name" s="<?php echo @$a;?>">State Name</th>
               <th width="15%" class="left" f="state_key" s="<?php echo @$b;?>">State key</th>
              <th width="30%" class="left" f="country_name" s="<?php echo @$c;?>">Country Name</th>
              <th width="20%" class="right" f="state_status" s="<?php echo @$d;?>">Status</th>
              <td width="17%" class="right">Action</td>
            </tr> 
             <tr class="filter">
              	<td class="right"></td>	
                 <td class="left"><input type="text" size="50" name="text_state" value="<?php echo (@$text_state);?>" id="text_state"></td>
                  <td class="left"><input type="text" size="50" name="text_key" value="<?php echo (@$text_key);?>" id="text_key"></td>
                <td class="left"><input type="text" size="50" name="text_country" value="<?php echo (@$text_country);?>" id="text_country"></td>
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
              <td class="left"><?php echo $ar['state_name'];?></td>
              <td class="left"><?php echo $ar['state_key'];?></td>
              <td class="left"><?php echo  getField('country_name','country','country_id',$ar['country_id']);?></td>
              <td class="right">
			  <?php if($ar['state_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/stateForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?>  </td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='5'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>