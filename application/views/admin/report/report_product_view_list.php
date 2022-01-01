<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=modal]').facebox()
})
</script>
	  <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sku');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'manufacturer_name');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_view_buy');
				
				
				
			  ?>
              <th width="50%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="30%" class="left" f="product_sku" s="<?php echo @$b;?>">Product SKU</th>
              <th width="10%" class="left" f="product_view_buy" s="<?php echo @$d;?>">Viewed</th>
              
           </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="50" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" size="50" name="product_sku_filter" value="<?php echo (@$product_sku_filter);?>"></td>
              <td class="right"></td>         
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
	
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
               <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemProduct?item_id='._en($ar[$this->cAutoId])); ?>"><?php echo $ar['product_name'];?></a></td>
              <td class="left"><?php echo $ar['product_sku'];?></td>
              <td class="left"><?php echo $ar['product_view_buy'];?></td>
              
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


