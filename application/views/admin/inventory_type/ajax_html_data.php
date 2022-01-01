      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'it_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'it_key');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'it_status');
			  ?>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="5.2%" class="left">Inventory ID</th>
              <th class="left" f="it_name" s="<?php echo @$a;?>">Inventory Name</th>
              <th class="left" f="it_key" s="<?php echo @$d;?>" width="15%">Key</th>
              <th class="right" f="it_status" s="<?php echo @$c;?>" width="15%">Status</th>
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
              <td class="left"><?php echo $ar['inventory_type_id'];?></td>
              <td class="left"><a href="<?php echo site_url('admin/inventory_type?item_id='._en($ar[$this->cAutoId]).'&m_id='); ?>" ><?php echo $ar['it_name'];?></a></td>
              <td class="left"><?php echo $ar['it_key'];?></td>
              <td class="right">
			  <?php if($ar['it_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right">  <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/inventoryTypeForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>
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