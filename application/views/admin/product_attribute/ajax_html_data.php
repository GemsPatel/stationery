<span id="loading_img_adm" style="display:none;">
<img style="padding:0;" src="<?php echo asset_url('images/preloader.gif'); ?>" alt="loader">
</span>
      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'inventory_master_specifier_id');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'inventory_type_id');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'pa_value');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'pa_sort_order');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'pa_status');
			  ?>
          	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="inventory_master_specifier_id" s="<?php echo @$a;?>">Inventory Attribute</th>
              <th class="left" f="inventory_type_id" s="<?php echo @$b;?>">Inventory Type</th>
              <th class="left" f="pa_value" s="<?php echo @$c;?>">Attribute Value</th>
              <th class="right" f="pa_sort_order" s="<?php echo @$d;?>">Sort Order</th>
              <th class="right" f="pa_status" s="<?php echo @$e;?>">Status</th>
              <td class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left">
              	    <?php
						$manArr = getDropDownAry( inventroyAttributeQuery(),"inventory_master_specifier_id", "ims_tab_label", array('' => "Select Attributes"), false);
						echo form_dropdown('ims_filter',$manArr, @$ims_filter, ' id="ims_filter" style="width:300px;" ');
					?>
              </td>
              <td class="left">
                    <?php
						$sql = "SELECT inventory_type_id, it_name FROM inventory_type it WHERE it_status=0";
						$manArr = getDropDownAry($sql,"inventory_type_id", "it_name", array('' => "Select Inventory"), false);
						echo form_dropdown('it_filter',$manArr, @$it_filter, ' id="it_filter" style="width:300px;" ');
					?>
              </td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right">
              	<select name="status_filter" id="status_filter">
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
		                <td class="left"><?php echo $ar['ims_tab_label']?></td>
		                <td class="left"><?php echo $ar['it_name']?></td>
		                <td class="left"><?php echo $ar['pa_value']?></td>
		                <td class="right sort_order" data-="<?php echo $ar[$this->cAutoId]?>" rel="<?php echo $ar['pa_sort_order']?>"><?php echo $ar['pa_sort_order']?></td>	
		                <td class="right">
		                <?php if($ar['pa_status']=='0')
		                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
		                    else
		                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
		                ?>
		                </td>
		                <td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/productAttributeForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ] <?php endif;?> </td>
		            </tr>
		  <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='7'>No results!</td></tr>";
	   	   endif; 
		  ?>
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
	</form>