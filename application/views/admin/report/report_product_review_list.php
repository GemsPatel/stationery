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
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_price');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_review_rating');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_review_ipaddress');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_review_created_date');
				
				
			  ?>
              <th width="55%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="10%" class="right" f="product_price" s="<?php echo @$b;?>">Product Price</th>
              <th width="10%" class="left" f="product_review_rating" s="<?php echo @$c;?>" >Rating</th>
              <th width="10%" class="left" f="product_review_ipaddress" s="<?php echo @$d;?>" >IP address</th>
              <th width="15%" class="right" f="product_review_created_date" s="<?php echo @$e;?>" >Date</th>
           </tr>
            
            <tr class="filter">
              <td class="left" valign="top"><input type="text" size="50" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="right">
                <span>From: </span><input type="text" size="10" name="from_range"  value="<?php echo (@$from_range);?>" /><br />
                <span style="padding-left:15px;">To: </span><input type="text" size="10" name="to_range"  value="<?php echo (@$to_range);?>" />
              </td>
              <td class="right"></td>
              <td class="left" valign="top"><input type="text" size="20" name="ipaddress_filter" value="<?php echo (@$ipaddress_filter);?>"></td>
               <td class="right">
                <span>From: </span><input type="text" class="datepicker"  name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
				
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td class="left"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemProduct?item_id='._en($ar['product_id'])); ?>"><?php  echo $ar['product_name'];?> </a></td>
              <td class="right"><?php echo $ar['product_price'];?></td>
              <td class="left"><img src="<?php echo load_image('images/admin/stars-'.$ar['product_review_rating'].'.png'); ?>" /></td>
              <td class="left"><?php echo $ar['product_review_ipaddress'];?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['product_review_created_date']);?></td>
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


