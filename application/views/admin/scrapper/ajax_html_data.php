     <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php			    
               	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_year');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_us_release_date');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_status');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_rating');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_director');
				$h = get_sort_order($this->input->get('s'),$this->input->get('f'),'m_site_key');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <td width="5%" class="center">Image</td>
              <th width="20%" class="left" f="m_name" s="<?php echo @$a;?>">Name</th>
              <th width="5%" class="left" f="m_year" s="<?php echo @$b;?>">Year</th>
              <th width="5%" class="left" f="m_site_key" s="<?php echo @$h;?>">Site</th>
              <th width="5%" class="left" f="m_rating" s="<?php echo @$e;?>">Rating</th>
              <td width="5%" class="left">Description</td>
              <td width="15%" class="left">Genre</td>
              <th width="10%" class="left" f="m_director" s="<?php echo @$g;?>">Director</th>
              <th width="7%" class="right" f="m_us_release_date" s="<?php echo @$c;?>">Release Date</th>
              <th width="5%" class="right" f="m_status" s="<?php echo @$d;?>">Status</th>
              <td width="5%" class="right">Action</td>
            </tr> 
             <tr class="filter">        
                <td class="right"></td>
                <td></td>
                <td class="left"><input type="text" size="30" name="name_filter" value="<?php echo (@$name_filter);?>"></td>
              	<td class="left"><input type="text" size="6" name="year_filter" value="<?php echo (@$year_filter);?>"></td>
                <td class="left"><input type="text" size="10" name="site_filter" value="<?php echo (@$site_filter);?>"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="right"><select name="status_filter" id="status_filter">
                         <option value="" selected="selected"></option>
                         <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Enabled</option>
                         <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Disabled</option>
                    </select></td>
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
              <td class="center"><img src="<?php echo load_image($ar['m_image']);?>" height="50" width="50" /></td>
              <td class="left"><?php echo $ar['m_name'];?></td>
              <td class="left"><?php echo $ar['m_year'];?></td>
              <td class="left"><?php echo $ar['m_site_key'];?></td>
              <td class="left"><?php echo $ar['m_certificate'];?></td>
              <td class="left"><?php echo $ar['m_desc'];?></td>
              <td class="left"><?php echo $ar['m_genre'];?></td>
              <td class="left"><?php echo $ar['m_director'];?></td>
              <td class="right"><?php echo ($ar['m_us_release_date'] != '0000-00-00') ? formatDate('d m, Y',$ar['m_us_release_date']) : '';?></td>
              <td class="right"><?php if($ar['m_status']=='1')
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?></td>           
                <td></td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='12'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>