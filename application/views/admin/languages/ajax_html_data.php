     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'l_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'l_key');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'l_sort_order');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'l_status');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="25%" class="left" f="l_name" s="<?php echo @$a;?>">Name</th>
              <th width="25%" class="left" f="l_key" s="<?php echo @$b;?>">Key</th>
              <th width="20%" class="right" f="l_sort_order" s="<?php echo @$c;?>">Sort order</th>
              <th width="20%" class="right" f="l_status" s="<?php echo @$d;?>">Status</th>
              <td width="7%" class="right">Action</td>
            </tr>            
          </thead>
          <tbody>
           <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		   ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar['l_name'];?></td>
              <td class="left"><?php echo $ar['l_key'];?></td>
              <td class="right sort_order" data-="<?php echo $ar[$this->cAutoId]?>" rel="<?php echo $ar['l_sort_order']?>"><?php echo $ar['l_sort_order']?></td>
              <td class="right">
			  <?php if($ar['l_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/languagesForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ] <?php endif;?> </td>
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