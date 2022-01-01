      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
      <input type="hidden" name="m_id"  value=""  />
      <table class="list">
          <thead>
			<?php
              $a = get_sort_order($this->input->get('s'),$this->input->get('f'),'ims_tab_label');
              $b = get_sort_order($this->input->get('s'),$this->input->get('f'),'ims_status');
            ?>
            <tr id="heading_tr" style="cursor:pointer;">
			  <td width="3%" style="text-align: center;"><!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"> --></td>
              <th class="left" f="ims_input_type" s="<?php echo $a; ?>" width="37%">Input Type</th>
			  <th class="left" f="ims_tab_label" s="<?php echo $a; ?>" width="37%">Name</th>
              <td class="right"  width="10%">Sort Order</td>
              <th class="right" f="ims_status" s="<?php echo $b; ?>" width="10%">Status</th>
              <td class="right" width="7%">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left"><input type="text" size="50" name="ims_input_type_filter" value="<?php echo (@$ims_input_type_filter);?>"></td>
              <td class="left"><input type="text" size="50" name="ims_name_filter" value="<?php echo (@$ims_name_filter);?>"></td>
              <td class="left"></td>
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
          <tbody class="ajaxdata">
          <?php 
		  	$extra = "";
			//pr($listArr);die;
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
          
		            <tr id="m<?php echo $ar[$this->cAutoIdM]?>">
		                <td style="text-align: center;"><!-- <input type="checkbox" value="m<?php echo $ar[$this->cAutoIdM]?>" name="selected[]" class="chkbox"> --></td>
		                <td class="center"><?php echo $ar['ims_input_type']?></td>
		                <td class="center"><?php echo $ar['ims_tab_label']?></td>
		                <td class="right sort_order" data-="<?php echo $ar[$this->cAutoIdM]?>" rel="<?php echo $ar['ims_sort_order']?>"><?php echo $ar['ims_sort_order']?></td>	
		                <td class="right">
		                <?php 
							if($ar['ims_status']=='0')
		                        echo '<a id="ajaxStatusEnabled" rel="1" data-="m'.$ar[$this->cAutoIdM].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
		                    else
		                        echo '<a id="ajaxStatusEnabled" rel="0" data-="m'.$ar[$this->cAutoIdM].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
		                ?>
		                </td>
		                <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/imsForm?edit=true&item_id='._en($ar['inventory_type_id']).'&m_id='._en($ar[$this->cAutoIdM]))?>">Edit</a> ]  <?php endif;?> </td>
		            </tr>
          
		  <?php 
		  		endforeach;
		  	else:
				echo "<tr><td class='center' colspan='6'>No results!</td></tr>";
	   	   	endif; 
		  ?>
          </tbody>
        </table>
      
	</form>
