      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_code');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'ep_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sku');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'category_name');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'ep_created_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th width="20%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="35%" class="left" f="product_code" s="<?php echo @$f;?>">Product Genereted Code</th>
              <th width="5%" class="left" f="product_sku" s="<?php echo @$d;?>">SKU</th>
              <td width="5%" class="left">Item Type</td>
              <td width="5%" class="left">Site</td>
              <th width="7%" class="right" f="ep_status" s="<?php echo @$c;?>">Status</th>
              <th width="10%" class="right" f="ep_created_date" s="<?php echo @$g;?>">Date</th>
              <td width="10%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left"><input type="text" size="40" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left"><input type="text" size="40" name="product_code_filter" value="<?php echo (@$product_code_filter);?>"></td>
              <td class="left"><input type="text" size="10" name="product_sku_filter" value="<?php echo (@$product_sku_filter);?>"></td>
              <td class="left"></td>
              <td class="left"></td>
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td></td>
              <td align="right">
              	<select name="ebay_country_id" onchange="showDeleteBtn(this)">
                    <option value="-1" <?php echo (@$ebay_country_id=='-1')?'selected="selected"':'';?>> </option>
                    <option value="0" <?php echo (@$ebay_country_id=='0')?'selected="selected"':'';?>>US</option>
                    <option value="3" <?php echo (@$ebay_country_id=='3')?'selected="selected"':'';?>>UK</option>
                    <option value="15" <?php echo (@$ebay_country_id=='15')?'selected="selected"':'';?>>AU</option>
                </select>
                &nbsp;&nbsp;<a class="button" id="searchFilter">Filter</a>
              </td>
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
					$prodUrl = getProductUrl($ar['product_id'],$ar['product_price_id']);
		  ?>
            <tr id="<?php echo $ar["ebay_products_id"]?>">
              <td style="text-align: center;"> <input type="checkbox" value="<?php echo $ar["ebay_products_id"]."|".$ar["product_id"]."|".$ar["product_price_id"]?>" name="selected[]"> </td>
              <td class="left">
			  	<?php echo $ar['product_name'];?>&nbsp;&nbsp;[<?php echo $ar['product_price_id'];?>]
              	<a href="<?php echo site_url( "admin/".$this->controller."/addEbayProduct?product_id=".$ar['product_id']."&product_price_id=".$ar['product_price_id']."&ebay_site_id=".$ar['ep_site_id'] ); ?>" target="_blank">Duplicate</a> 
              </td>  
              <td class="left">
                <?php
					if( !empty($ar['ebay_products_id']) ) :             		
              	?>
                  <input type="text" size="80" name="ebay_title" id="ebay_title_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_title'] ?>" maxlength="80" placeholder="Title"><br />
                  Price:<input type="text" size="1" name="ebay_price" id="ebay_price_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_product_price'] ?>" placeholder="Price">&nbsp;
                  Qty:<input type="text" size="1" name="ebay_qty" id="ebay_qty_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_qty'] ?>" placeholder="Qty">&nbsp;
                  Duration Days:<input type="text" size="1" name="ebay_duration" id="ebay_duration_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_listing_duration'] ?>" placeholder="Duration Days">&nbsp;
                  Allow Auto Listing:<input type="checkbox" name="ebay_is_auto_listing" id="ebay_is_auto_listing_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_is_auto_listing'] ?>" <?php echo ($ar['ep_is_auto_listing'] == 1) ? 'checked="checked"' : '' ?> >
                  <br />
                  <input type="hidden" name="ebay_site_id" id="ebay_site_id_<?php echo $ar['ebay_products_id'];?>" value="<?php echo $ar['ep_site_id'] ?>" />
                  <a onClick="ebayUpdateTitle(this, <?php echo $ar["ebay_products_id"]?>)" class="button" id="<?php echo $ar[$this->cAutoId]?>" data-id="<?php echo $ar['product_price_id'];?>">Update</a>&nbsp;
               <?php
              	endif;
               ?>
                  <a target="_blank" href="<?php echo site_url('admin/product/checkFoldeStructure?id='.$ar['product_id'])?>">Html Page</a>              
              </td>
              <td class="left"><?php echo $ar['product_sku'];?></td>
              <td class="left">
			  	<?php 
					if($ar['ep_mode'] == 10)
						echo 'Auction';
					else if($ar['ep_mode'] == 9)
						echo 'Both';
					else if($ar['ep_mode'] == 1)
						echo 'Fixed';
				?>
              </td>
              <td class="left"><?php echo getEbaySiteName($ar['ep_site_id']);?></td>
              <td class="right">
			  <?php if($ar['ep_status']=='0')
			  			echo '<img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/>';
					else
				  		echo '<img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/>';
			  ?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['ep_created_date']);?></td>
              <td class="right">
              <?php if( $ar['ep_item_id'] != "" && $ar['ep_status'] == "0" ):?>
			  
				  <?php if($this->per_edit == 0):?> [ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=2&ebay_products_id='.$ar["ebay_products_id"].'&product_price_id='.$ar['product_price_id'].'&add_mode='.$ar['ep_mode'] )?>">Update On eBay</a> ] <?php endif;?>
                  <br />
                  <?php if($this->per_delete == 0):?> [ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=3&ebay_products_id='.$ar["ebay_products_id"].'&product_price_id='.$ar['product_price_id'].'&add_mode='.$ar['ep_mode'] )?>">Delete From eBay</a> ] <?php endif;?>
                  <br /> 
                  <?php if($this->per_delete == 0):?> [ <a target="_blank" href="<?php echo site_url('admin/'.$this->controller.'/deleteImagesFromEbay?product_price_id='.$ar['product_price_id'].'&ebay_products_id='.$ar["ebay_products_id"].'&add_mode='.$ar['ep_mode'] )?>">Delete Images</a> ] <?php endif;?>
              	  <br />
              	  [ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=5&ebay_products_id='.$ar["ebay_products_id"].'&product_price_id='.$ar['product_price_id'].'&add_mode='.$ar['ep_mode'] )?>">Relist</a> ]
              
              <?php else:?>
              	
                <?php if($this->per_add == 0):?> 
                	[ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=1&product_price_id='.$ar['product_price_id'].'&ebay_products_id='.$ar["ebay_products_id"] )?>">Add as FixedPrice</a> ]
                    <br />
                	[ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=10&product_price_id='.$ar['product_price_id'].'&ebay_products_id='.$ar["ebay_products_id"] )?>">Add as Auction</a> ]
                    <br />
                    [ <a target="_blank" href="<?php echo site_url( 'site/ebayListing?mode=9&product_price_id='.$ar['product_price_id'].'&ebay_products_id='.$ar["ebay_products_id"] )?>">Add as Both</a> ]
                    <br />
                	<?php if($this->per_delete == 0):?> [ <a target="_blank" href="<?php echo site_url('admin/'.$this->controller.'/deleteImagesFromEbay?product_price_id='.$ar['product_price_id'].'&ebay_products_id='.$ar["ebay_products_id"].'&add_mode='.$ar['ep_mode'] )?>">Delete Images</a> ] <?php endif;?>
                <?php endif;?>
              
			  <?php endif;?>
              </td>
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
	  
<script type="text/javascript">

	function createHtmlPage()
	{
		var prcode = $("input[name='product_code_filter']").val();
		
		if( prcode == '' )
		{
			$('#content').before(getNotificationHtml('error','Specify product generated code.'));
		}
		else
		{
			url = (base_url+'admin/'+lcFirst(controller))+'/ebayHtmlPage?product_code='+prcode;
			window.open( url );
		}
	}
	
	function ebayUpdateTitle(obj, ebay_products_id)
	{
		var price_id = $(obj).attr('data-id');
		var etitle = $('#ebay_title_'+ebay_products_id).val();
		var pid = $(obj).attr('id');
		var eprice = $('#ebay_price_'+ebay_products_id).val();
		var eqty = $('#ebay_qty_'+ebay_products_id).val();
		var eDuration = $('#ebay_duration_'+ebay_products_id).val();
		var eSiteId = $('#ebay_site_id_'+ebay_products_id).val();
		var eAutoList = $('#ebay_is_auto_listing_'+ebay_products_id).is( ":checked" );
		var eIsAuto = (eAutoList ==1) ? '1' : '0';
		
		if(etitle == "")
		{
			//$('#content').before(getNotificationHtml('error','Please enter ebay title'));
			//return false;
		}
		showLoader();
		var loc = (base_url+'admin/'+lcFirst(controller))+'/saveEbayData';
		form_data = { ebay_products_id:ebay_products_id, ebay_title : etitle, product_id : pid, product_price_id : price_id, ebay_price : eprice, ebay_qty : eqty, ebay_duration : eDuration, is_auto_listing : eIsAuto, ebay_site_id : eSiteId };
		$.post(loc, form_data, function (data) {
			var arr = $.parseJSON(data);	
			if(arr['success'])
			{
				$('#content').before(getNotificationHtml('success',arr['success']));
				hideLoader();
				return false;
			}
			$("#searchFilter").trigger("click");
			hideLoader();
		});
		
	}

</script>
	  