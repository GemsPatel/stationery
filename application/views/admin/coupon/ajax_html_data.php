      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'coupon_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'coupon_code');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'category_id');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'coupon_status');
			  ?>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="coupon_name" s="<?php echo @$a;?>">Name</th>
              <th class="left" f="coupon_code" s="<?php echo @$b;?>">Code</th>
              <th class="left" f="category_id" s="<?php echo @$c;?>">Category</th>
              <th class="right" f="coupon_status" s="<?php echo @$d;?>">Status</th>
              <td class="right">Action</td>
            </tr>
            <tr class="filter">
              	<td class="right"></td>	
                 <td class="left"><input type="text" size="50" name="text_name" value="<?php echo (@$text_name);?>" id="text_name"></td>
                <td class="left"><input type="text" size="50" name="text_code" value="<?php echo (@$text_code);?>" id="text_code"></td>
                <td class="left"><?php echo form_dropdown('cat_filter',getMultiLevelMenuDropdown(0,array(''=>'Select Category')),@$cat_filter,'id="cat_filter" style="width:200px;"');?></td>
              	<td class="right">
                	<select name="status_filter" id="status_filter">
                         <option value="" selected="selected"></option>
                         <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                         <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                    </select>
               	</td>
              	<td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>            
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId];?>">
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar['coupon_name'];?></td>
               <td class="left"><?php echo $ar['coupon_code'];?></td>
                <?php
					 $res = executeQuery("SELECT category_name FROM coupon b INNER JOIN coupon_category_map m ON m.coupon_id =b. coupon_id  INNER JOIN product_categories c ON c.category_id = m.category_id  WHERE b.coupon_id= ".$ar['coupon_id']." ");
				 
				 $names = "";
				 if(!empty($res)):
					foreach($res as $k=>$v)
					{
						$names .= $v['category_name'].",";
					}	
					$names = substr($names,0,-1);
				  endif;
				?>
                <td class="left" title="<?php echo  $names; ?>"><?php echo char_limit($names,80);?></td>
              <td class="right">
			  <?php if($ar['coupon_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/couponForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  <?php endif;?> </td>
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