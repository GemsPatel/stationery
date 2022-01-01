      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_sort_order');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_key');
			  ?>
          	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left">Icon</th>
              <th class="left" f="manufacturer_name" s="<?php echo @$a;?>">Name</th>
              <th class="left" f="manufacturer_key" s="<?php echo @$d;?>">Key</th>
              <th class="right" f="manufacturer_status" s="<?php echo @$c;?>">Status</th>
              <td class="right">Action</td>
              </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td width="1" style="text-align: center;"></td>
              <td class="left"><input type="text" size="50" name="text_filter" value="<?php echo (@$text_filter);?>" id="text_filter"></td>
              <td></td>
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
                <td class="left"><img src="<?php echo load_image($ar['manufacturer_image']);?>" width="50" height="30"  class="image" style="margin-bottom:0px;padding:3px;" /></td>
                <td class="left"><?php echo $ar['manufacturer_name']?></td>
                <td class="left"><?php echo $ar['manufacturer_key']?></td>
                <td class="right">
                <?php if($ar['manufacturer_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/manufacturerForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ]  <?php endif;?> </td>
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