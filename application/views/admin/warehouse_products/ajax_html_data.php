<span id="loading_img_adm" style="display:none;">
<img style="padding:0;" src="<?php echo asset_url('images/preloader.gif'); ?>" alt="loader">
</span>
      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            
            <tr id="heading_tr" style="cursor:pointer;">
          	  <td width="1" style="text-align: center;"><!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"> --></td>
              <th class="left">Product Name</th>
              <th class="left">Stock</th>
              <th class="left">Investment</th>
              <th class="left">Avg. Rate</th>
              <th class="left">Market Price</th>
              <th class="center">Our price</th>
              <th class="center">You Save</th>
              <td class="right">Action</td>
            </tr>
            
            <tr class="filter"> 
              <td width="1" style="text-align: center;"></td>
              <td class="left">
              	    <?php
						$manArr = getDropDownAry( "SELECT product_id, product_name FROM product","product_id", "product_name",
												  array('' => "Select Product"), false);
						echo form_dropdown('product_filter',$manArr, @$product_filter, ' id="product_filter" style="width:150px;" ');
					?>
              </td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right"></td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
            
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
		            <tr id="<?php echo $ar[$this->cAutoId]?>">
		                <td style="text-align: center;"><!-- <input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> --></td>
		                <td class="left"><?php echo $ar['product_name']?></td>
		                <td class="left"><?php echo $ar['product_value_quantity'];//." ".$ar["pv_quantity_unit"]?></td>
		                <td class="left"><?php echo lp( round( $ar['product_price'] * $ar["product_value_quantity"] ) );?></td>
		                <td class="left"><?php echo $ar['product_price'];?></td>
		                <td class="center"><input type="text" onkeyup="youSave(<?php echo $ar[$this->cAutoId]?>)" id="product_price_calculated_price_<?php echo $ar[$this->cAutoId]?>" name="product_price_calculated_price" value="<?php echo $ar['product_price_calculated_price'];?>" size="10"> </td>
		                <td class="center"><input type="text" onkeyup="youSave(<?php echo $ar[$this->cAutoId]?>)" id="product_discounted_price_<?php echo $ar[$this->cAutoId]?>" name="product_discounted_price" value="<?php echo $ar['product_discounted_price'];?>" size="10"> </td>
		                <td class="center" id="you_save_<?php echo $ar[$this->cAutoId]?>"><?php echo lp( $ar["product_price_calculated_price"] - $ar["product_discounted_price"] );?></td>
                		<td class="right"> <?php if($this->per_edit == 0):?> <a class="button" onclick="wpForm(<?php echo $ar[$this->cAutoId]?>);">Set</a> <?php endif;?> </td>
		            </tr>
		  <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='9'>No results!</td></tr>";
	   	   endif; 
		  ?>
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
	</form>
	
	<script type="text/javascript">
		/**
		 *
		 */
		function wpForm(product_id)
		{
			showLoader();
			var loc = (base_url+'admin/'+lcFirst(controller)+'/wpForm');
			var form_data = { product_id:product_id,
							  product_price_calculated_price: $("#product_price_calculated_price_"+product_id).val(), 
							  product_discounted_price: $("#product_discounted_price_"+product_id).val() 
							}; 
			$.post(loc, form_data, function (data) 
			{
				data = $.parseJSON(data);
				$('#content').before(getNotificationHtml(data['type'],data['msg']));
				hideLoader();
			});	
		}

		/**
		 *
		 */
		function youSave(product_id)
		{
			var product_price_calculated_price = $("#product_price_calculated_price_"+product_id).val(); 
			var product_discounted_price = $("#product_discounted_price_"+product_id).val();
			if( !isNaN( product_price_calculated_price ) )
			{
				//$('#content').before(getNotificationHtml("error","Price should be numeric only"));		
			}

			if( !isNaN( product_discounted_price ) )
			{
				//$('#content').before(getNotificationHtml("error","Price should be numeric only"));		
			}

			$("#you_save_"+product_id).html( lp( product_price_calculated_price - product_discounted_price ) ); 
		}		
				
	</script>