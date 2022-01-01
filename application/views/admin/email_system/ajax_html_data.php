      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'es_from_emails');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'es_module_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'es_subject');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'es_status');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'es_sent_date');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'admin_user_id');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="15%" class="left" f="es_from_emails" s="<?php echo @$a;?>">From Email</th>
              <th width="15%" class="left" f="es_module_name" s="<?php echo @$b;?>">Module Name</th>
              <th width="35%" class="left" f="es_subject" s="<?php echo @$c;?>">Subject</th>
              <th width="10%" class="right" f="admin_user_id" s="<?php echo @$f;?>">Username</th>
              <th width="10%" class="left" f="es_sent_date" s="<?php echo @$e;?>">Date</th>
              <th width="5%" class="right" f="es_status" s="<?php echo @$d;?>">Status</th>
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
              <td class="left"><?php echo $ar['es_from_emails'];?></td>
              <td class="left"><?php echo $ar['es_module_name'];?></td>
              <td class="left"><?php echo $ar['es_subject'];?></td>
              <td class="right"><?php echo $ar['admin_user_firstname'].' '.$ar['admin_user_lastname'];?></td>
              <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['es_sent_date']);?></td>
              <td class="right">
			  <?php if($ar['es_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/sendEmailForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="View">View</a> ]  <?php endif;?> </td>
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