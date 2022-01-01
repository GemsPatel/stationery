<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="" src="<?php echo asset_url('images/admin/category.png')?>"> <?php echo ucwords(str_replace('_',' ',$this->controller))?></h1>
      <div class="buttons">
       <?php if($this->per_add == 0):?>
          <a class="button" href="<?php echo site_url('admin/'.$this->controller.'/diamondPriceForm')?>">Insert</a>
        <?php endif;?>
       <?php if($this->per_delete == 0):?>   
          <a class="button" onclick="$('#form').submit();">Delete</a>
       <?php endif;?>    
      </div>
    </div>
    
    <!--<div class="pre_loader"><div class="listingPreloader"></div></div>-->
    
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/deleteCategory')?>">
      <input type="hidden" id="filter_field" value="<?php echo $this->input->get('f')?>" />
      <input type="hidden" id="filter_sort" value="<?php echo $this->input->get('s')?>"  />
        <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'diamond_price_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'dp_sort_order');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'dp_status');
			  ?>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" f="diamond_price_name" s="<?php echo @$a;?>">Category Name</th>
              <th class="right" f="dp_sort_order" s="<?php echo @$b;?>">Sort Order</th>
              <th class="right" f="dp_status" s="<?php echo @$c;?>">Status</th>
              <td class="right">Action</td>
            </tr>
            
          </thead>
          <tbody>
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr>
              <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]"> </td>
              <td class="left"><?php echo $ar['diamond_price_name'];?></td>
              <td class="right"><?php echo $ar['dp_sort_order']?></td>
              <td class="right">
			  <?php if($ar['dp_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> [ <a href="<?php echo site_url('admin/'.$this->controller.'/diamondPriceForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ] </td>
            </tr>
          <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='5'>No results!</td></tr>";
	   	   endif; 
		   ?>
            
          </tbody>
        </table>
      </form>
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
    </div>
  </div>
  
</div>

