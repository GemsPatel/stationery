   	  <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'currency_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'currency_sort_order');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'currency_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'currency_value');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'currency_code');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'price_filter_range');
			  ?>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="currency_name" s="<?php echo @$a;?>">Name</th>
              <th class="left" f="currency_code" s="<?php echo @$e;?>">Code</th>
              <th class="left" f="currency_value" s="<?php echo @$d;?>">Value</th>
              <th class="left" f="price_filter_range" s="<?php echo @$f;?>">Price Filter Range</th>
              <th class="right" f="currency_sort_order" s="<?php echo @$b;?>">Sort Order</th>
              <th class="right" f="currency_status" s="<?php echo @$c;?>">Status</th>
              <td class="right">Action</td>
            </tr>            
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar['currency_name'];?></td>
              <td class="left"><?php echo $ar['currency_code'];?></td>
              <td class="left"><?php echo $ar['currency_value'];?></td>
              <td class="left"><?php echo $ar['price_filter_range'];?></td>
              <td class="right sort_order" data-="<?php echo $ar[$this->cAutoId]?>" rel="<?php echo $ar['currency_sort_order']?>"><?php echo $ar['currency_sort_order']?>
                </td>
              <td class="right">
			  <?php if($ar['currency_status']=='0') 
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/CurrencyForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ] <?php endif;?> </td>
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