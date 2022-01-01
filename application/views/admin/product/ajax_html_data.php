      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
				$f = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_code');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sort_order');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_status');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sku');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'category_name');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_modified_date');
			  ?>
              <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <td width="3%">ID</td>
              <td width="5%">Image</td>
              <th width="25%" class="left" f="product_name" s="<?php echo @$a;?>">Product Name</th>
              <th width="20%" class="left hide" f="product_code" s="<?php echo @$f;?>">Product Genereted Code</th>
              <th width="5%" class="left" f="product_sku" s="<?php echo @$d;?>">SKU</th>
              <td width="10%" class="center" title="<?php echo @$f?>" f="product_code" s="<?php echo @$f;?>">View On Front</td>
              <th width="10%" class="right" f="product_sort_order" s="<?php echo @$b;?>">Sort Order</th>
              <th width="15%" class="right" f="product_modified_date" s="<?php echo @$g;?>">Date</th>
              <th width="10%" class="right" f="product_status" s="<?php echo @$c;?>">Status</th>
              <td width="7%" class="right">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left"></td>
              <td class="left"></td>
              <td class="left"><input type="text" size="40" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left hide"><input type="text" size="20" name="product_code_filter" value="<?php echo (@$product_code_filter);?>">&nbsp;<a onclick="createHtmlPage()" id="ebayHtmlBtn" class="button">Html Page</a></td>
              <td class="left"><input type="text" size="20" name="product_sku_filter" value="<?php echo (@$product_sku_filter);?>"></td>
              <td class="left">
              	<input class="hide" type="text" size="10" name="product_code_filter" value="<?php echo (@$product_code_filter);?>">&nbsp;
              	<a onclick="createHtmlPage()" id="ebayHtmlBtn" class="button hide" style="display: none !important;"></a>
              </td>
              <td class="right">
              		<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/randomSortOrder'); ?>" title="Product sort order will be randomized.">Randomize</a>
              </td>
              <td></td>
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
              <td style="text-align: center;"> <input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> </td>
              <td class="left"><?php echo $ar[$this->cAutoId];?></td>
              <td class="left">
              	<?php
					$product_images = array();
              		$prodUrl = "#";
              		
              		$findCode = getProductCodeInfo( $ar['product_id'] );
              		if(!empty($findCode))
					{
// 						$imagefolder = getProdImageFolder( $findCode['product_generated_code'], $findCode['product_price_id'], $ar["product_sku"] );
// 						$product_images = fetchProductImages( $imagefolder );
						$product_images = front_end_hlp_getProductImages($findCode['product_generated_code'], $findCode['product_price_id'],
																		 $ar["product_sku"], $findCode['product_generated_code_info']);
						
						
 						$prodUrl = getProductUrl($ar['product_id'],$findCode['product_price_id'],$ar['product_alias'],$ar['category_id']);
					}					
              	?>
                <img class="image" src="<?php echo load_image(@$product_images[ $ar["product_angle_in"] ]);?>" width="50" height="45"  style="margin-bottom:0px;padding:3px;" /><br />
              </td>
              <td class="left ">
              	<?php if($this->per_edit == 0):?> 
					<?php if( getSysConfig("IS_ML") ): ?>
						<a href="<?php echo site_url('admin/'.$this->controller.'/itemLanguages?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit"><?php echo $ar['product_name'];?></a>  
					<?php else:?>
						<?php if( INVENTORY_TYPE_ID != 0 ):?>
							<a href="<?php echo site_url('admin/'.$this->controller.'/productForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit"><?php echo $ar['product_name'];?></a>
						<?php else:?>
							<a href="javascript:void(0);" onclick="switchInventorySessionIfRequired( '<?php echo inventory_typeKeyForId($ar["inventory_type_id"]);?>', '<?php echo site_url('admin/'.$this->controller.'/productForm?edit=true&item_id='._en($ar[$this->cAutoId]));?>');"><?php echo $ar['product_name'];?></a>
						<?php endif;?>
					<?php endif;?>
				<?php endif;?> 
              </td>  
              <td class="left hide"><a target="_blank" href="<?php echo site_url('admin/'.$this->controller.'/checkFoldeStructure?id='.$ar[$this->cAutoId])?>">Check Html Page</a></td>
              <td class="left"><?php echo $ar['product_sku'];?></td>
              <?php
                $prevURL = urlAppendParameter($prodUrl, "is_preview=1");
              ?>
              <td class="center"><a class="button" href="<?php echo $prevURL;?>" title="<?php echo $prevURL;?>" target="_blank">Preview</a></td>
              <td class="right sort_order" data-="<?php echo $ar[$this->cAutoId]?>" rel="<?php echo $ar['product_sort_order']?>"><?php echo $ar['product_sort_order']?></td>
              <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['product_modified_date']);?></td>
              <td class="right">
			  <?php if($ar['product_status']=='0')
			  			echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
					else
				  		echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'"  title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
			  ?></td>
              <td class="right"> 
				<?php if($this->per_edit == 0):?> 
					<?php if( getSysConfig("IS_ML") ): ?>
						[ <a href="<?php echo site_url('admin/'.$this->controller.'/itemLanguages?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]  
					<?php else:?>
						<?php if( INVENTORY_TYPE_ID != 0 ):?>
							[ <a href="<?php echo site_url('admin/'.$this->controller.'/productForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>" title="Edit">Edit</a> ]
						<?php else:?>
							[ <a href="javascript:void(0);" onclick="switchInventorySessionIfRequired( '<?php echo inventory_typeKeyForId($ar["inventory_type_id"]);?>', '<?php echo site_url('admin/'.$this->controller.'/productForm?edit=true&item_id='._en($ar[$this->cAutoId]));?>');">Edit</a> ]
						<?php endif;?>
					<?php endif;?>
				<?php endif;?> 
			  </td>
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

</script>
	  