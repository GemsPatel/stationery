      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'banner_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'category_id');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'banner_sort_order');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'banner_status');
			  ?>
          	  <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <td class="left">Image</td>
              <th class="left" f="banner_name" s="<?php echo @$a;?>">Name</th>
              <th class="left" f="category_id" s="<?php echo @$b;?>">Category</th>
              <th class="right" f="banner_sort_order" s="<?php echo @$c;?>">Sort Order</th>
              <th class="right" f="banner_status" s="<?php echo @$d;?>">Status</th>
              <td class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="right"></td>
               <td class="left"><input type="text" size="30" name="text_name" value="<?php echo (@$text_name);?>" id="text_name"></td>
               <td class="left"><?php echo form_dropdown('cat_filter',getMultiLevelMenuDropdown(0,array(''=>'Select Category')),@$cat_filter,'id="cat_filter" style="width:200px;"');?></td>
              <td class="right"></td>
              
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)): //pr($listArr);die;
				foreach($listArr as $k=>$ar):
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
                <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"></td>
                <td class="left"><img class="image" src="<?php echo  load_image($ar['banner_image']);?>" width="50" height="35"  style="margin-bottom:0px;padding:3px;" /><br />
                 </td>
                <td class="left"><?php echo $ar['banner_name']?></td>
                 <?php
				 if( MANUFACTURER_ID != 7)
				 {
					$res = executeQuery("SELECT category_name FROM banner_cctld b INNER JOIN banner_category_map m ON m.banner_id = b.banner_id  INNER JOIN product_categories_cctld c ON c.category_id = m.category_id  WHERE b.banner_id= ".$ar['banner_id']." "); 
				 }
				 else
				 {
				 	$res = executeQuery("SELECT category_name FROM banner b INNER JOIN banner_category_map m ON m.banner_id = b.banner_id  INNER JOIN product_categories c ON c.category_id = m.category_id  WHERE b.banner_id= ".$ar['banner_id']." ");
				 }
				 
				 $names = "";
				 	if( !isEmptyArr($res) )
				 	{
				 		foreach($res as $k=>$v)
				 		{
				 			$names .= $v['category_name'].",";
				 		}
				 	}
	
					$names = substr($names,0,-1);
				?>
                  
                <td class="left" title="<?php echo  $names; ?>"><?php echo char_limit($names,80);?></td>
               <td class="right sort_order" data-="<?php echo $ar[$this->cAutoId]?>" rel="<?php echo $ar['banner_sort_order']?>"><?php echo $ar['banner_sort_order']?>
                </td>
                <td class="right">
                <?php if($ar['banner_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"> <?php if($this->per_edit == 0):?> [ <a href="<?php echo site_url('admin/'.$this->controller.'/bannerForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ]<?php endif;?> </td>
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