      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'front_menu_type_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'fm_icon_is_display');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'fmt_status');
			  ?>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="front_menu_type_name" s="<?php echo @$a;?>">Menu Name</th>
              <th class="left" f="fm_icon_is_display" s="<?php echo @$d;?>" width="15%">Display Icon</th>
              <th class="right" f="fmt_status" s="<?php echo @$c;?>" width="15%">Status</th>
              <td class="right" width="15%">Action</td>
            </tr>            
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><a href="<?php echo site_url('admin/main_front_menu?item_id='._en($ar[$this->cAutoId]).'&m_id='); ?>" ><?php echo $ar['front_menu_type_name'];?></a></td>
              <td class="left"><?php echo ($ar['fm_icon_is_display']==0)?'On':'Off';?></td>
              <td class="right">
			  <?php if($ar['fmt_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right">  <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/frontMenuForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>
            </tr>
          <?php 
		  		endforeach;
		  	else:
			 	echo "<tr><td class='text-center' colspan='5'>No results!</td></tr>";
	   	  	endif;
		  ?>
            
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>