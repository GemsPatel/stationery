      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
				$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'config_display_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'config_key');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'modified_date');
			  ?>
              <th class="left" f="config_display_name" s="<?php echo @$a;?>">Config Name</th>
              <th class="left" f="config_key" s="<?php echo @$b;?>">Key</th>
              <th class="left">Value</th>
              <th class="left" f="modified_date" s="<?php echo @$c;?>">Modified Date</th>
              <td class="right">Action</td>
            </tr>            
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td class="left"><?php echo $ar['config_display_name'];?></td>
              <td class="left"><?php echo $ar['config_key'];?></td>
              <td class="left"><?php echo $ar['config_value'];?></td>
              <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['modified_date']);?></td>
              <td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/configurationForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>
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