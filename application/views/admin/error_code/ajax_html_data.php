      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
				$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'error_code');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'error_message');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'modified_date');
			  ?>
              <th class="left" f="error_code" s="<?php echo @$a;?>">Error Code</th>
              <th class="left" f="error_message" s="<?php echo @$b;?>">Message</th>
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
              <td class="left"><?php echo $ar['error_code'];?></td>
              <td class="left"><?php echo $ar['error_message'];?></td>
              <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['modified_date']);?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/errorCodeForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ] <?php endif;?> </td>
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