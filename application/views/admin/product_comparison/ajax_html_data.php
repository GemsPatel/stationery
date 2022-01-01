      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      
      <table class="list">
          <thead>
          	<tr>
            	<td class="center" colspan="4"><h1>Stationery</h1></td>
                <td></td>
                <td class="center" colspan="4"><h1>AU (CCTLD)</h1></td>
            </tr>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sku');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_modified_date');
			  ?>
              <th width="20%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="5%" class="left" f="product_sku" s="<?php echo @$d;?>">SKU</th>
              <th width="15%" class="right" f="product_modified_date" s="<?php echo @$g;?>">Date</th>
              <th width="5%" class="right" f="product_status" s="<?php echo @$c;?>">Status</th>
              <td width="2%"></td>
              <th width="20%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="5%" class="left" f="product_sku" s="<?php echo @$d;?>">SKU</th>
              <th width="15%" class="right" f="product_modified_date" s="<?php echo @$g;?>">Date</th>
              <th width="5%" class="right" f="product_status" s="<?php echo @$c;?>">Status</th>
            </tr>
            
            <tr class="filter">
              <td class="left"><input type="text" size="40" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left"><input type="text" size="15" name="product_sku_filter" value="<?php echo (@$product_sku_filter);?>"></td>
              <td></td>
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>              
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar): 
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><?php echo $ar['product_name'];?></td>  
              <td class="left"><?php echo $ar['product_sku'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['product_modified_date']);?></td>
              <td class="right">
			  <?php if($ar['product_status']=='0')
			  			echo '<img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/>';
					else
				  		echo '<img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/>';
			  ?></td>
              <td class="left"></td>
              <td class="left"><?php echo $ar['product_name'];?></td>  
              <td class="left"><?php echo $ar['product_sku'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['product_modified_date']);?></td>
              <td class="right">
			  <?php if($ar['product_cctld_status']=='0')
			  			echo '<img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/>';
					else
				  		echo '<img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/>';
			  ?></td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='10'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>

      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>
