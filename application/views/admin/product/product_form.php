<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/chosen/chosen.jquery.js');?>"></script>
<link rel="stylesheet" href="<?php echo asset_url('css/admin/chosen/chosen.css');?>" />
<script type="text/javascript">
	$(document).ready(function(e) {
      // CKEDITOR.replace( 'product_description' );
	   	CKEDITOR.replace( 'product_description',
    	{
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
   		 });
	
	   //CKEDITOR.replace( 'product_email_message' );
	   	CKEDITOR.replace( 'product_email_message',
    	{
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
   		 });
		 //autocomplete pulgin chosen for custom field
		$(".select_chosen").chosen();
		
		//$(".select_chosen").data("placeholder","Select category").chosen();

    });
</script>

<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
 
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller;?>" src="<?php echo getMenuIcon($this->controller);?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons">
      	<a class="button" onclick="$('#form').submit();">Save</a>
      		
      	  <?php $get = $this->input->get();?>
      	
      	  <?php if( getSysConfig("IS_ML") ): ?>
	      	<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/itemLanguages?'.(!empty($get["edit"])?'edit':'insert').'=true&item_id='._en( @$this->cPrimaryId ) );//@$this->cPrimaryId?>">Cancel</a></div>
	      <?php else:?>
	      	<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/itemLanguages?edit=true&item_id=');?>">Cancel</a></div>
	      <?php endif;?>
      </div>
    </div>
  
  		<?php
			$compAttrArr = getcompAttrArr(); 
		?>
  
  
    <div class="content">
      <div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <?php
// 		  		$cnt = 5;
//           		if(isset($product_side_stonesData) && sizeof($product_side_stonesData) > 0):
// 					foreach( $product_side_stonesData as $k=>$ar ):
// 		  ?>
			        	<!-- <a href="#tab-data<?php echo $cnt?>" style="display: inline;" id="tabmetal">Side Stone <?php echo ($cnt-2)?> -->
          <?php
// 			  			$cnt++;
// 		  			endforeach;
//           		endif;
// 		  ?>

          <?php
          		if( !isEmptyArr($compAttrArr) ):
					foreach( $compAttrArr as $k=>$ar ):
		  ?>
			        	<a href="#tab-data<?php echo $ar["inventory_master_specifier_id"]?>" style="display: inline;" id="tabmetal"><?php echo $ar["ims_tab_label"];?></a>
          <?php
		  			endforeach;
          		endif;
		  ?>

		  <a style="display: inline; cursor:pointer" onclick=" alert('Not supported yet!'); return false; addStoneTab();" ><img src="<?php echo asset_url('images/admin/add.png')?>"/></a>
      </div>
      
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/product/productForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>" />
        <div id="tab-general" style="display: block;">
            <!--Product information-->
            <fieldset>
            <legend>General Information</legend>
                <table class="form">
                  <tbody>
                  
	              <?php
	              	if( INVENTORY_TYPE_ID == 0 ):
	              ?>
		              <input type="hidden" name="inventory_type_id" value="<?php echo inventory_typeIdForKey($this->session->userdata("IT_KEY"));?>"/>
		          <?php
		          	else:
		          ?>    
		          		<input type="hidden" name="inventory_type_id" value="<?php echo INVENTORY_TYPE_ID;?>"/>
		          <?php 
		          	endif;
		          ?>
                  
                  
                  <tr>
                    <td><span class="required">*</span> Product Name:</td>
                    <td><input type="text" size="96" maxlength="80" name="product_name" value="<?php echo (@$product_name) ? $product_name : set_value('product_name'); ?>" onkeyup="getUrlName(this.value)">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_name'):''; ?> </span>
                    </td>
                  </tr>
                  <?php if(MANUFACTURER_ID ==7):?>
                  <tr>
                    <td><span class="required">*</span> Product Alias:</td>
                    <td><input type="text" size="96" name="product_alias" id="display_alias" value="<?php if(MANUFACTURER_ID!=7) echo "DEFAULT";
                    																					  else echo (@$product_alias)? url_title($product_alias): set_value('product_alias'); ?>" readonly="readonly">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_alias'):''; ?> </span>
                    </td>
                  </tr>
                  <?php endif;?>
                  <tr>
                    <td><span class="required">*</span> Product SKU:</td>
                    <td><input type="text" size="96" name="product_sku" value="<?php echo (@$product_sku) ? $product_sku : set_value('product_sku'); ?>">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_sku'):''; ?> </span>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="required">*</span> Category:</td>
                    <td><?php
						$setVal = (@$category_id) ? explode("|",$category_id): @$_POST['category_id'];
						//$catArr = getMultiLevelWithOptGroup("category_id,category_name","category_sort_order","product_categories",0,"parent_id",'', false);
						//echo form_dropdownMultiDimensional(true,'category_id[]',$catArr,@$setVal,' style="width:30%;" class="select_chosen" multiple="true" '); 
						echo form_dropdown('category_id[]',getMultiLevelMenuDropdown(0,array()),@$setVal,' style="width:30%;" class="select_chosen" multiple="true" ');
						?>
                    	<span class="error_msg"><?php echo (@$error)?form_error('category_id'):''; ?></span>
                    </td>
                  </tr>
                  
                  <!-- seller-manufacturer
                  <tr>
                    <td><span class="required">*</span> Manufacturer:</td>
                    <td>
					<?php
// 						$setVal = (!empty($product_manufacturer_id)) ? @$product_manufacturer_id : ((!empty($_POST['product_manufacturer_id'])) ? @$_POST['product_manufacturer_id'] : MANUFACTURER_ID);
// 						$sql = "SELECT manufacturer_id, manufacturer_name FROM manufacturer WHERE manufacturer_status=0";
// 						$manArr = getDropDownAry($sql,"manufacturer_id", "manufacturer_name", array('' => "Select Manufacturer"), false);
// 						echo form_dropdown('product_manufacturer_id',$manArr,$setVal,'style="width:12%; " ');
					?>
                    	<span class="error_msg"><?php echo (@$error)?form_error('product_manufacturer_id'):''; ?></span>
                    </td>
                  </tr>
 				  -->

				<tr>	
 				     <td> Product price:</td>
                     <td>
                     	<?php
                     		/**
							 * price will be keep disabled from product module for, <br>
							 * component based and warehouse managed inventory.  
                     		 */
                     		$disabled = ""; 
                     		if( hewr_isPriceDynamic() )
                     		{
                     			$disabled = 'disabled="disabled"';
                     		}
                     	?>	
                     	<input type="text" value="<?php echo (@$product_price) ? $product_price : set_value('product_price'); ?>" size="10" name="product_price" <?php echo $disabled;?> >
                     	
                     	<span class="error_msg"><?php echo (@$error)?form_error('product_price'):''; ?> </span>
                     </td>
 				</tr>	
 					
 				<?php
 					$product_image_single = ""; 
 					if( isset($product_image) && hefile_isFile( $product_image ) )
 					{
 						$product_image_single = $product_image; 
 					}
 				?>
 				<tr>
                  <td>Image:</td>
                  <td valign="top">
	                 <div class="image" style="padding:5px;" align="center">
	                 	  <img src="<?php echo (@$product_image_single)?  load_image( $product_image_single ) : asset_url('/images/admin/no_image.jpg');?>" width="35" height="35" id="imageRemoveBtn_01" class="image" style="margin-bottom:0px;padding:3px;" /><br />
	                      <input type="file" name="product_image_single" id="productImage_01" onchange="readURL(this,'01');" style="display: none;">
	                      <input type="hidden" value="<?php echo (@$product_image_single) ? $product_image_single : @$_POST['product_image_single'];?>" name="product_image_single" id="hiddenProdImg" />
	                      <div align="center">
	                      	<small><a onclick="$('#productImage_01').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" id="imageRemoveBtn_01">Clear</a></small>
	                      </div>
	                 </div>
	                 <br>
		                 <span class="error_msg">
		                 <?php if(!@$error)
		                 {
		                 ?>
                 			<span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","PRODUCT_IMG_SIZE");
                 		            						$allowedRec = getField("config_value", "configuration", "config_key","PRODUCT_REC_IMAGE");
		                 	echo '(Maximum allowed size is '.$allowedSize.','.$allowedRec.')';
		                 ?></span>
		                 <?php }
		                 else
		                 {
		                 	echo (@$error)?form_error('product_image_single'):''; 
		                 }?></span>
                  </td>
                </tr>
 				<tr>
                <td>&nbsp;&nbsp;Size:</td>
                <td>
                <?php
                    $setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
                    echo getImageSizeDropdown($setval); 
                ?>
                </td>
              </tr>	
 					
 				<tr>
                      <td>Short Description:</td>
                      <td>
                      	  <textarea name="product_short_description" rows="4" cols="70"><?php echo (@$product_short_description) ? $product_short_description : set_value('product_short_description');?></textarea>
                          <span class="error_msg"><?php echo (@$error)?form_error('product_short_description'):''; ?> </span>
                      </td>
                </tr>
 					
 					
 				<tr>	
 				  <td>Status:</td>
                  <td><select name="product_status">
                      <option <?php echo (@$product_status) ? (($product_status == 0) ? 'selected="selected"': '' ) :((@$_POST['product_status'] == 0) ? 'selected="selected"': '' ) ;  ?> value="0">Enabled</option>
                      <option <?php echo (@$product_status) ? (($product_status == 1) ? 'selected="selected"': '' ) : ((@$_POST['product_status'] == 1) ? 'selected="selected"': '' ) ;  ?> value="1">Disabled</option>
                      </select>
                  </td>
                </tr>  

                <?php
                	/**
                	 * @deprecated
					 * Like Kg, Gm, Ml, pieces etc.
                     */
                    if( FALSE && hewr_isWarehouseManaged() ):
                ?>	
		                <tr>	
		 				     <td> Quanity Unit:</td>
		                     <td>
		                     	<input type="text" value="<?php echo (@$pv_quantity_unit) ? $pv_quantity_unit : @$_POST['pv_quantity_unit']; ?>" size="10" name="pv_quantity_unit" >
		                     	
		                     	<span class="error_msg"><?php echo (@$error)?form_error('pv_quantity_unit'):''; ?> </span>
		                     	<br>
		                     	<small class="small_text">Like Kg, Gm, Ml, pieces etc.</small>
		                     </td>
		 				</tr>
 				<?php
 					endif;
 				?>	
                  
                  
                </tbody></table>
            </fieldset>

            <!--Product desciption-->
            <div id="tab-general">
                <fieldset>
                <legend class="toggle minus">Product Long Description</legend>
                  <table class="form hide">
                    <tbody>
                    <tr>
                      <td>Description:</td>
                      <td><textarea name="product_description" class="product_description"><?php echo (@$product_description) ? $product_description : @$_POST['product_description']; ?></textarea></td>
                    </tr>
                  </tbody></table>
                </fieldset>
            </div>
        
        	<!--Product details-->
            <div id="tab-general" style="display: block;">
            	<fieldset>
                <legend class="toggle minus">Advanced Details</legend>
              <table class="form hide">
                <tbody>
                
                <?php
 					if( isset($product_image) && !hefile_isDir( $product_image ) )
 					{
 						$product_image = ""; 
 					}
 				?>
                <tr>
                  <td>Image Folder:</td>
                  <td valign="top">
	                 <div class="image" style="padding:5px;" align="center">
	                 	  <img src="<?php echo (@$product_image)?  asset_url(image_src_common($product_image.".zip")) : asset_url('/images/admin/no_image.jpg');?>" width="35" height="35" id="imageRemoveBtn_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
	                      <input type="file" name="product_image" id="productImage_00" onchange="readURL(this,'00');" style="display: none;">
	                      <input type="hidden" value="<?php echo (@$product_image) ? $product_image : @$_POST['product_image'];?>" name="product_image" id="hiddenProdImg" />
	                      <div align="center">
	                      	<small><a onclick="$('#productImage_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" id="imageRemoveBtn_00">Clear</a></small>
	                      </div>
	                 </div>
	                 <br />
	                 <small class="small_text">(Upload multiple images with ZIPPED file of FOLDER, FOLDER name and ZIP file name should be same as SKU. )<?php echo (@$product_image)? "<br>Existing folder : ".$product_image:""; ?></small>
                  </td>
                  <td colspan="5"></td>
                </tr>
                
                
                <tr>
                    <td>Video Url:</td>
                    <td><input type="text" value="<?php echo (@$product_video) ? $product_video : @$_POST['product_video']; ?>" size="40" name="product_video"></td>
                    <td width="15%"> Image Angle Index:</td>
                    <td width="15%"><input type="text" value="<?php echo (isset( $product_angle_in ) ) ? $product_angle_in : set_value('product_angle_in'); ?>" size="10" name="product_angle_in">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_angle_in'):''; ?> </span>
                    </td>
                    <td colspan="3"></td>
                </tr>
                
                
                <tr>
                    <td width="15%"> Height:</td>
                    <?php
                    	if( empty( $product_value_height ) && empty( $_POST['product_value_height'] ) )
                    	{
                    		$product_value_height = "0 mm";
                    	}
                    ?>
                    <td width="15%"><input type="text" value="<?php echo (@$product_value_height) ? $product_value_height : @$_POST['product_value_height']; ?>" size="10" name="product_value_height">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_value_height'):''; ?> </span>
                    </td>
                    <td width="5%"> Width:</td>
                    <?php
                    	if( empty( $product_value_width ) && empty( $_POST['product_value_width'] ) )
                    	{
                    		$product_value_width = "0 mm";
                    	}
                    ?>
                    <td width="15%"><input type="text" value="<?php echo (@$product_value_width) ? $product_value_width : @$_POST['product_value_width']; ?>" size="10" name="product_value_width">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_value_width'):''; ?> </span>
                    </td>
                    <td width="5%"> Weight:</td>
                    <?php
                    	if( empty( $product_value_weight ) && empty( $_POST['product_value_weight'] ) )
                    	{
                    		$product_value_weight = "0";
                    	}
                    ?>
                    <td width="15%"><input type="text" value="<?php echo (@$product_value_weight) ? $product_value_weight : @$_POST['product_value_weight']; ?>" size="10" name="product_value_weight">
                        <span class="error_msg"><?php echo (@$error)?form_error('product_value_weight'):''; ?> </span>
                    </td>
                    <td width="30%"></td>
                </tr>
               
               
                <tr>
                  <td>Gender:</td>
                  <td>
                  	<?php $product_gender = (@$product_gender) ? $product_gender:@$_POST['product_gender'];?>
                	<label>
                    <input type="radio" name="product_gender" value="M" <?php echo (@$product_gender) ? (($product_gender == 'M') ? 'checked="checked"': '' ) : '' ;  ?> /> Male </label>
                    <label>
                    <input type="radio" name="product_gender" value="F" <?php echo (@$product_gender) ? (($product_gender == 'F') ? 'checked="checked"': '' ) : 'checked="checked"' ;  ?> /> Female </label>                <label>
                    <input type="radio" name="product_gender" value="O" <?php echo (@$product_gender) ? (($product_gender == 'O') ? 'checked="checked"': '' ) : '' ;  ?> /> Others </label>                
                  </td>
                  
                  <td>Sort Order:</td>
                  <td><input type="text" size="10" value="<?php echo (@$product_sort_order) ? $product_sort_order : @$_POST['product_sort_order']; ?>" name="product_sort_order"></td>
	              
                <td colspan="3"></td>
              </tr>
              
              
              <tr>
              	<td> Product Occasion:</td>
                <td>              
                     <?php 
						$setVal = (@$product_offer_id) ? explode("|",$product_offer_id) : @$_POST['product_offer_id'];
						$sql = "SELECT product_offer_id, product_offer_name FROM product_offer WHERE product_offer_status=0";
						$manArr = getDropDownAry($sql,"product_offer_id", "product_offer_name", '', false);
						echo form_dropdown('product_offer_id[]',@$manArr,@$setVal,' class="select_chosen" multiple="true"  style="width: 85%;"');
					 ?>
                </td>
                <td colspan="5"></td>
              </tr>
              

              <tr height="100">
                  <td colspan="7"></td>
              </tr>
              
              
              <tr>
                 <td>Product Accessories:</td>
                  <td><select name="product_accessories">
                      <option value="" >Others</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'CHN') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'CHN') ? 'selected="selected"': '' ) ;  ?> value="CHN">Chain</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'BAN') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'BAN') ? 'selected="selected"': '' ) ;  ?> value="BAN">Bangles</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'BRA') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'BRA') ? 'selected="selected"': '' ) ;  ?> value="BRA">Bracelet</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'RIN') ? 'selected="selected"': 'selected="selected"' ) : ((@$_POST['product_accessories'] == 'RIN') ? 'selected="selected"': 'selected="selected"' ) ;  ?> value="RIN">Ring</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'COU') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'COU') ? 'selected="selected"': '' ) ;  ?> value="COU">Couple Bands</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'SOL') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'SOL') ? 'selected="selected"': '' ) ;  ?> value="SOL">Solitaire Jewellery</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'EAR') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'EAR') ? 'selected="selected"': '' ) ;  ?> value="EAR">Earrings</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'ONP') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'ONP') ? 'selected="selected"': '' ) ;  ?> value="ONP">Onyx Pendant</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'NEC') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'NEC') ? 'selected="selected"': '' ) ;  ?> value="NEC">Necklace</option>
                      <option <?php echo (@$product_accessories) ? (($product_accessories == 'PEC') ? 'selected="selected"': '' ) : ((@$_POST['product_accessories'] == 'PEC') ? 'selected="selected"': '' ) ;  ?> value="PEC">Pendant Set</option>
                      </select>
                  </td>
                  <td colspan="5"></td>
              </tr>
              
                <tr>
                
                  <td>Metal :</td>
                    <td><?php
						$setVal = (@$product_metal_priority_id) ? $product_metal_priority_id : ((@$_POST['product_metal_priority_id']) ? $_POST['product_metal_priority_id'] : 16);
						$sql = "SELECT metal_price_id, CONCAT(metal_purity_name,' ',metal_color_name,' ',metal_type_name) as 'metal_price_name' FROM metal_price m 
                          INNER JOIN metal_color c
						  ON c.metal_color_id=m.metal_color_id INNER JOIN metal_type t
						  ON t.metal_type_id=m.metal_type_id INNER JOIN metal_purity u
						  ON u.metal_purity_id=m.metal_purity_id 
						  WHERE metal_price_status=0";
						$manArr = getDropDownAry($sql,"metal_price_id", "metal_price_name", array('' => "Select Metal"), false);
						echo form_dropdown('product_metal_priority_id',$manArr,@$setVal,'style="width:124px; " '); ?>
                    </td>

                  <td>Center Stone:</td>
                    <td><?php 
						$setVal = (@$product_cs_priority_id) ? $product_cs_priority_id: @$_POST['product_cs_priority_id'];
						$sql = "SELECT  diamond_price_id, CONCAT(diamond_price_name,': ', diamond_price_key) as diamond_price_key FROM diamond_price WHERE dp_manufacturer_id=".MANUFACTURER_ID." AND dp_rapnet_lot_no=0 AND  dp_status=0";
						$manArr = getDropDownAry($sql,"diamond_price_id", "diamond_price_key", array('' => "Select Diamond"), false);
						echo form_dropdown('product_cs_priority_id',$manArr,@$setVal,'style="width:114px; " '); ?>
                    </td>
                    
                    
                    <td colspan="3"></td>
                </tr>
                
                
                <tr>
            	  <td>Side Stone 1:</td>
                    <td><?php 
						$setVal = (@$product_ss1_priority_id) ? $product_ss1_priority_id: @$_POST['product_ss1_priority_id'];
						$sql = "SELECT  diamond_price_id, CONCAT(diamond_price_name,': ', diamond_price_key) as diamond_price_key FROM diamond_price WHERE dp_manufacturer_id=".MANUFACTURER_ID." AND dp_rapnet_lot_no=0 AND  dp_status=0";
						$manArr = getDropDownAry($sql,"diamond_price_id", "diamond_price_key", array('' => "Select Diamond"), false);
						echo form_dropdown('product_ss1_priority_id',$manArr,@$setVal,'style="width:114px; " '); ?>
                    </td>
                 <td>Side Stone 2:</td>
                    <td><?php 
						$setVal = (@$product_ss2_priority_id) ? $product_ss2_priority_id: @$_POST['product_ss2_priority_id'];
						$sql = "SELECT  diamond_price_id, CONCAT(diamond_price_name,': ', diamond_price_key) as diamond_price_key FROM diamond_price WHERE dp_manufacturer_id=".MANUFACTURER_ID." AND dp_rapnet_lot_no=0 AND  dp_status=0";
						$manArr = getDropDownAry($sql,"diamond_price_id", "diamond_price_key", array('' => "Select Diamond"), false);
						echo form_dropdown('product_ss2_priority_id',$manArr,@$setVal,'style="width:114px; " '); ?>
                    </td>
                <td colspan="3"></td>
              </tr>
              
                  <?php
                  	if( $this->session->userdata("IT_KEY") === "JW" ):
                  ?>
                  	<tr>
	            	  <td>Ring Size:</td>
	                  <td>
	             	  <?php $ring_size_region = (@$ring_size_region) ? $ring_size_region:@$_POST['ring_size_region'];?>
	                    <label>
	                    <input type="radio" name="ring_size_region" value="Y" <?php echo (@$ring_size_region) ? (($ring_size_region == 'Y') ? 'checked="checked"': '' ) : 'checked="checked"' ;  ?> />Yes</label>
	                    <label>
	                    <input type="radio" name="ring_size_region" value="N" <?php  echo (@$ring_size_region) ? (($ring_size_region == 'N') ? 'checked="checked"': '' ) : '' ;  ?> /> No</label>    
	                  </td>
	                  <td colspan="5"></td>
	                </tr>  
	              <?php
	              	endif;
	              ?>    
              
               </tbody></table>
            </fieldset>
            </div>
            
            <!--SEO-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">SEO</legend>
                    <?php $this->load->view('admin/elements/seo_form', array('class'=>'hide'));?>
                </fieldset>
            </div>
            
            <!--Product pricing-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">Product dynamic pricing</legend>
                  <table class="form hide">
                    <tbody>
                    
                    <tr>
                        <td width="5%">Discount:</td>
                        <td width="15%"><input type="text" onchange="updateFlag();" value="<?php echo (@$product_discount) ? $product_discount : @$_POST['product_discount']; ?>" size="10" name="product_discount"></td>
                        <td colspan="5"></td>
                    </tr>
                    
                    <tr>
                      	<td> COD cost:</td>
                      	<td><input type="text" value="<?php echo (@$product_cod_cost) ? $product_cod_cost : @$_POST['product_cod_cost']; ?>" size="10" name="product_cod_cost"></td>
                      	<td colspan="5"></td>
                    </tr>  
                      
                    <tr>  
                      	<td> Tax and VAT:</td>
                    	<td>
							<?php 
								$setVal = (@$product_tax_id) ? explode("|",$product_tax_id): @$_POST['product_tax_id'];
								$sql = "SELECT tax_rate_id, tax_rate_name FROM tax_rate WHERE tax_rate_status=0";
								$taxArr = getDropDownAry($sql,"tax_rate_id", "tax_rate_name", '', false);
								echo form_dropdown('product_tax_id[]',$taxArr,@$setVal,'style="width:30%; height:80px;" class="select_chosen" multiple="true" placeholder="Select Taxes"'); 
							?>
	                    	<span class="error_msg"><?php echo (@$error)?form_error('tax_rate_id'):''; ?></span>
                    	</td>
                    	<td colspan="5"></td>
                    </tr>
                    
                  <!--<tr>
                   
                    <td colspan="3"></td>
                  </tr>-->
                  </tbody></table>
                </fieldset>
            </div>

            <!--Product status-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">Product warehouse status</legend>
                  <table class="form hide">
                    <tbody>

                    <tr>
                    	<?php
                     		/**
							 * Qty will be keep disabled from product module for, <br>
							 * warehouse managed inventory.  
                     		 */
                     		$disabled = ""; 
                     		if( $this->session->userdata("IT_KEY") === "JW" || $this->session->userdata("IT_KEY") === "GC" )
                     		{
                     			$disabled = 'readonly="readonly"';
                     		}
                     	?>	
                        <td width="12%"> Quantity:</td>
                        <td width="13%"><input type="text" value="<?php echo (@$product_value_quantity) ? $product_value_quantity : @$_POST['product_value_quantity']; ?>" size="10" name="product_value_quantity" <?php echo $disabled;?>></td>
                        <td colspan="5"></td>
                    </tr>    
                    
                    <tr>
 
                        <td> Availability:</td>
                        <td>
                          <?php 
                          $setStockStatus = (@$stock_status_id) ? $stock_status_id : @$_POST['stock_status_id'];
                          $sql = "SELECT stock_status_id, status_name FROM stock_status WHERE stock_status=0";
                          $stock_statusArr = getDropDownAry($sql,"stock_status_id", "status_name", array('' => "Select availability"), false);
                          echo form_dropdown('stock_status_id',@$stock_statusArr,@$setStockStatus,'class=""');
                          ?>
                          <span class="error_msg"><?php echo (@$error)?form_error('stock_status_id'):''; ?> </span>
                        </td>
                        <td colspan="5"></td>
                         
                    </tr>
                    
                    <tr>
                        <td> Maximum purchase quantity:</td>
                        <td><input type="text" value="<?php echo (@$product_value_maximum_purchase) ? $product_value_maximum_purchase : @$_POST['product_value_maximum_purchase']; ?>" size="10" name="product_value_maximum_purchase"></td>
                        <td colspan="5"></td>
                    </tr>    
                        
                    <tr>    
                        <td width="10%"> Low stock notification:</td>
                        <td width="15%"><input type="text" value="<?php echo (@$product_value_notification_level) ? $product_value_notification_level : @$_POST['product_value_notification_level'];?>" size="10" name="product_value_notification_level"></td>
                        <td colspan="5"></td>
                    </tr>
                    
                  </tbody>
                </table>
                </fieldset>
            </div>

            <!--Product internal note-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">Internal note</legend>
                  <table class="form hide">
                    <tbody>
                    <tr>
                         <td><textarea name="product_internal_note" rows="4" cols="124"><?php echo (@$product_internal_note) ? $product_internal_note : @$_POST['product_internal_note']; ?></textarea></td>
                    </tr>
                  </tbody></table>
                </fieldset>
            </div>

            <!--Related searches-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">Related searches</legend>
                  <table class="form hide">
                    <tbody>
                    <tr>
                        <td width="12%"> Product tags:</td>
                        <td width="18%"><textarea name="product_tags" rows="4" cols="35"><?php echo (@$product_tags) ? $product_tags : @$_POST['product_tags']; ?></textarea></td>
                        <td width="5%"> Related keywords:</td>
                        <td width="15%"><textarea name="product_related_keywords" rows="4" cols="35"><?php echo (@$product_related_keywords) ? $product_related_keywords : @$_POST['product_related_keywords'];?></textarea></td>
                        <td width="50%"></td>
                    </tr>
                  </tbody></table>
                </fieldset>
            </div>

            <!--Related Tags-->
            <div id="tab-general" style="display: block;">
                <fieldset>
                <legend class="toggle minus">Related Tags</legend>
                  <table class="form hide">
                    <tbody>
                    <tr>
                        <td> Related products:</td>
                        <td>
						<?php
						$setRelProd = (@$product_related_products_id) ? explode("|",$product_related_products_id) : @$_POST['product_related_products_id'];
                        $sql = "SELECT product_id, product_sku FROM product WHERE product_status=0";
                        $productArr = getDropDownAry($sql,"product_id", "product_sku", '', false);
						echo form_dropdown('product_related_products_id[]',@$productArr,@$setRelProd,' class="select_chosen" multiple="true"  style="width: 85%;"');
                        ?>
                        </td>
                    </tr>
                    <tr>
                        <td> Related categories:</td>
                        <td>
						<?php 
						$setRelCat = (@$product_related_category_id) ? explode("|",$product_related_category_id) : @$_POST['product_related_category_id'];
                        //$sql = "SELECT category_id, category_name FROM product_categories WHERE category_status=0 AND parent_id<>0";
                        //$product_categoryArr = getDropDownAry($sql,"category_id", "category_name", '', false);
						//echo form_dropdown('product_related_category_id[]',@$product_categoryArr,@$setRelCat,' class="select_chosen" multiple="true"  style="width: 85%;"');
						echo form_dropdown('product_related_category_id[]',getMultiLevelMenuDropdown(0,array()),@$setRelCat,' class="select_chosen" multiple="true"  style="width: 85%;" ');
						?>
                        </td>
                    </tr>
                  </tbody></table>
                </fieldset>
            </div>

            <!--Send product emails-->
            <div id="tab-general" style="display: block; float:left; width:100%">
                <fieldset>
                <legend class="toggle minus">Send product emails</legend>
                  <table class="form hide">
                    <tbody>
                    <tr>
                        <td>To:</td>
                        <td><input type="text" value="" size="80" name="product_email_toemails">
                            <!--<input type="file" name="import_file" id="import_file_00" style="display: none;" accept="application/msexcel" >
	                        <a onclick="$('#import_file_00').trigger('click');" ><img alt="import file" src="<?php echo asset_url('images/admin/excel_file.png'); ?>" style="vertical-align: middle; margin-left: 1%;" /></a>-->
                        </td>
                    </tr>
                    <tr>
                        <td>Subject:</td>
                        <td><input type="text" value="" size="80" name="product_email_subject"></td>
                    </tr>
                    <tr>
                      <td>Message:</td>
                      <td><textarea id="product_email_message" name="product_email_message"></textarea></td>
                    </tr>
                    <tr>
                    	<td></td>
                        <td><a class="button" onclick="productSendNewsletter()">Send email(s)</a></td>
                    </tr>
                  </tbody>
                  </table>
                </fieldset>
            </div>
        </div>
	
		<!-- components and attributes start -->
		
		<?php
			$sideStoneCnt = 0;
			if( !isEmptyArr($compAttrArr) ):
			foreach ($compAttrArr as $compAttrKey=>$compAttrVal):
				$attrData;
			
				if( !isPost() && !empty($this->cPrimaryId) )
				{
					$attrData = getAttributeDataOfimsID( $sideStoneCnt, $compAttrVal["inventory_master_specifier_id"], $resP, $product_side_stonesData, 
														 $center_stone_idArr, $side_stone1_idArr, $side_stone2_idArr, $metal_price_idArr, $compAttrVal );
				}
				else 
					$attrData = FALSE; 
				
				if( $attrData !== FALSE )
				{
					$compAttrVal = array_merge($compAttrVal, $attrData);					
				}
				
				$inventory_master_specifier_id = $compAttrVal["inventory_master_specifier_id"]; 
		?>
		
		<?php
			if( $compAttrVal["ims_input_type"] === "JW_CS" || $compAttrVal["ims_input_type"] === "JW_SS1" || $compAttrVal["ims_input_type"] === "JW_SS2" || $compAttrVal["ims_input_type"] === "JW_SSS" ):
		?>
        <!-- Jewelry stones -->  
        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
       		<fieldset>
            <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
            <table class="form">
              <tbody>
              <tr>
                <td> Diamond Shape:</td>
                <td>
				<?php 
				$setCSshape = (@$compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"]) ? $compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"] : @$_POST["pss".$inventory_master_specifier_id."_diamond_shape_id"];
				$sql = "SELECT diamond_shape_id, diamond_shape_name FROM diamond_shape WHERE diamond_shape_status=0";
				$diamond_shapeArr = getDropDownAry($sql,"diamond_shape_id", "diamond_shape_name", '', false);
				
				echo form_dropdown( "pss".$inventory_master_specifier_id."_diamond_shape_id",@$diamond_shapeArr,@$setCSshape,'style="width: 10%;" ');
				?>
                </td>
              </tr>
              <tr>
                <td> Size:</td>
                <td><input type="text" value="<?php echo (@$compAttrVal["product_side_stone".$inventory_master_specifier_id."_size"]) ? $compAttrVal["product_side_stone".$inventory_master_specifier_id."_size"] : @$_POST["product_side_stone".$inventory_master_specifier_id."_size"]; ?>" name="product_side_stone<?php echo $inventory_master_specifier_id;?>_size"></td>
              </tr>
              <tr>
                <td> Weight(Carat):</td>
                <td><input type="text"  onkeyup="return calcProdPrice(null,'ss<?php echo $inventory_master_specifier_id?>_p',false,false, <?php echo $inventory_master_specifier_id;?>);" value="<?php echo (@$compAttrVal["product_side_stone".$inventory_master_specifier_id."_weight"]) ? $compAttrVal["product_side_stone".$inventory_master_specifier_id."_weight"] : @$_POST["product_side_stone".$inventory_master_specifier_id."_weight"]; ?>" name="product_side_stone<?php echo $inventory_master_specifier_id;?>_weight"></td>
              </tr>
              <tr>
                <td> Total Number of Diamonds:</td>
                <td><input type="text" onkeyup="return setProductPrice();" value="<?php echo (@$compAttrVal["product_side_stone".$inventory_master_specifier_id."_total"]) ? $compAttrVal["product_side_stone".$inventory_master_specifier_id."_total"] : @$_POST["product_side_stone".$inventory_master_specifier_id."_total"]; ?>" name="product_side_stone<?php echo $inventory_master_specifier_id;?>_total"></td>
              </tr>
            </tbody>
            </table>
           </fieldset>
           
           <!-- Diamond types-->
           <?php
		   $center_stone_idArr = (@$compAttrVal["side_stone".$inventory_master_specifier_id."_idArr"])? $compAttrVal["side_stone".$inventory_master_specifier_id."_idArr"]:@$_POST['ss'.$inventory_master_specifier_id.'_p'];
		   $sql = "SELECT diamond_type_id, diamond_type_name FROM diamond_type WHERE diamond_type_status=0";
		   $diamond_typeArr = getDropDownAry($sql, "diamond_type_id", "diamond_type_name", null, null);
		   $sql = "SELECT diamond_price_id, CONCAT(diamond_price_name,': ', diamond_price_key) as diamond_price_key FROM diamond_price WHERE dp_manufacturer_id=".MANUFACTURER_ID." AND diamond_type_id=";
		   
		   echo renderCategorywithCheckbox("ss".$inventory_master_specifier_id."_p[]",$sql, array('0' => "diamond_price_id", '1' => "diamond_price_key"), array('0' => "diamond_type_id", '1' => "diamond_type_name"), 
		   @$diamond_typeArr, @$center_stone_idArr, "", 'style="display: block; width:50%; float:left;"', @$this->cPrimaryId, @$this->is_post, $inventory_master_specifier_id );
		   ?>
              
        </div>
        
        <?php
			elseif( $compAttrVal["ims_input_type"] === "JW_MTL" ):
		?>
        
        
        <!-- metal -->
        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
        <input type="hidden" name="ss<?php echo $inventory_master_specifier_id;?>_imsID" value="<?php echo $compAttrVal["inventory_master_specifier_id"];?>">
           <fieldset>
           <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
           <table class="form">
              <tbody>
              <tr>
                <td colspan="2"><span class="required">*</span> Note: Must select one metal category below</td>
              </tr>
              <tr>
                <td colspan="2"><span class="error_msg"><?php echo (@$error)?form_error('mt_p'):''; ?> </span></td>          
              </tr>
            </tbody>
            </table>
           </fieldset>
           
           <!-- Metal types-->  
           <?php
		   $metal_price_idArr = (@$metal_price_idArr)? $metal_price_idArr:@$_POST['mt_p'];
		   
 		   $sql = "SELECT metal_type_id, metal_type_name FROM metal_type WHERE metal_type_status=0";
		   $diamond_typeArr = getDropDownAry($sql, "metal_type_id", "metal_type_name", null, null);
		   
		   $sql = "SELECT metal_price_id, CONCAT(p.metal_purity_name, CONCAT(' ' , c.metal_color_name)) as 'metal_category_name'  
				   FROM metal_price m INNER JOIN metal_purity p 
				   ON p.metal_purity_id=m.metal_purity_id 
				   INNER JOIN metal_color c 
				   ON c.metal_color_id=m.metal_color_id
				   WHERE metal_purity_status=0 AND metal_color_status=0 AND metal_type_id=";
				   
		   echo renderCategorywithCheckbox("mt_p[]",$sql, array('0' => "metal_price_id", '1' => "metal_category_name"), array('0' => "metal_type_id", '1' => "metal_type_name"), 
		   @$diamond_typeArr, @$metal_price_idArr, "", 'style="display: block; width:50%; float:left;"', @$this->cPrimaryId, @$this->is_post, $inventory_master_specifier_id );
		   ?>

        </div>
        
        <?php
			elseif( $compAttrVal["ims_input_type"] === "TXT" ):
		?>
        
                <!-- text -->
		        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
		        <input type="hidden" name="ss<?php echo $inventory_master_specifier_id;?>_imsID" value="<?php echo $compAttrVal["inventory_master_specifier_id"];?>">
		           <fieldset>
		           <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
		           <table class="form">
		              <tbody>

			              <tr>
			                <td> <?php echo $compAttrVal["ims_input_label"];?>:</td>
			                <td><input type="text" value="<?php echo (@$compAttrVal["product_side_stone".$inventory_master_specifier_id."_size"]) ? $compAttrVal["product_side_stone".$inventory_master_specifier_id."_size"] : @$_POST["product_side_stone".$inventory_master_specifier_id."_size"]; ?>" name="product_side_stone<?php echo $inventory_master_specifier_id;?>_size"></td>
			              </tr>
		              
		              </tbody>
		            </table>
		           </fieldset>
		
		        </div>
		        
		<?php
			elseif( $compAttrVal["ims_input_type"] === "SEL" ):
		?>
        
                <!-- select -->
		        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
		        <input type="hidden" name="ss<?php echo $inventory_master_specifier_id;?>_imsID" value="<?php echo $compAttrVal["inventory_master_specifier_id"];?>">
		           <fieldset>
		           <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
		           <table class="form">
		              <tbody>

			              <tr>
			                <td> <?php echo $compAttrVal["ims_input_label"];?>:</td>
			                <td>
							<?php 
							$product_attribute_id = (@$compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"]) ? $compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"] : @$_POST["pss".$inventory_master_specifier_id."_diamond_shape_id"];

							$sql = ""; 
							if( MANUFACTURER_ID === 7 )
							{
								$sql = "SELECT pa.product_attribute_id, pa.pa_value FROM product_attribute pa 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
							else 
							{
								$sql = "SELECT pa.product_attribute_id, pac.pa_value 
										FROM product_attribute pa 
										INNER JOIN product_attribute_cctld pac
										ON ( pac.product_attribute_id=pa.product_attribute_id AND manufacturer_id=".MANUFACTURER_ID." ) 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
								
							$product_attributeArr = getDropDownAry($sql,"product_attribute_id", "pa_value", array('' => "Select ".$compAttrVal["ims_input_label"]), false);
							
							echo form_dropdown( "pss".$inventory_master_specifier_id."_diamond_shape_id",@$product_attributeArr,@$product_attribute_id,'style="width: 10%;" ');
							?>
			                </td>
			              </tr>
		              		              
		              </tbody>
		            </table>
		           </fieldset>
		
		        </div>

		<?php
			elseif( $compAttrVal["ims_input_type"] === "CHK" ):
		?>
        
                <!-- select -->
		        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
		        <input type="hidden" name="ss<?php echo $inventory_master_specifier_id;?>_imsID" value="<?php echo $compAttrVal["inventory_master_specifier_id"];?>">
		           <fieldset>
		           <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
		           <table class="form">
		              <tbody>

			              <tr>
			                <td> <?php echo $compAttrVal["ims_input_label"];?>:</td>
			                <td>
							<?php 
							$product_attribute_id = (@$compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"]) ? $compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"] : @$_POST["pss".$inventory_master_specifier_id."_diamond_shape_id"];

							$sql = ""; 
							if( MANUFACTURER_ID === 7 )
							{
								$sql = "SELECT pa.product_attribute_id, pa.pa_value FROM product_attribute pa 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
							else 
							{
								$sql = "SELECT pa.product_attribute_id, pac.pa_value 
										FROM product_attribute pa 
										INNER JOIN product_attribute_cctld pac
										ON ( pac.product_attribute_id=pa.product_attribute_id AND pac.manufacturer_id=".MANUFACTURER_ID." ) 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
								
							$product_attributeArr = getDropDownAry($sql,"product_attribute_id", "pa_value", '', false);
							
							echo form_checkboxArry( "pss".$inventory_master_specifier_id."_diamond_shape_id[]",@$product_attributeArr,@$product_attribute_id,'style="width: 10%;" ');
							?>
			                </td>
			              </tr>
		              		              
		              </tbody>
		            </table>
		           </fieldset>
		
		        </div>

		<?php
			elseif( $compAttrVal["ims_input_type"] === "RDO" ):
		?>
        
                <!-- select -->
		        <div id="tab-data<?php echo $inventory_master_specifier_id?>" style="display: none;">
		        <input type="hidden" name="ss<?php echo $inventory_master_specifier_id;?>_imsID" value="<?php echo $compAttrVal["inventory_master_specifier_id"];?>">
		           <fieldset>
		           <legend><?php echo $compAttrVal["ims_fieldset_label"];?></legend>
		           <table class="form">
		              <tbody>

			              <tr>
			                <td> <?php echo $compAttrVal["ims_input_label"];?>:</td>
			                <td>
							<?php 
							$product_attribute_id = (@$compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"]) ? $compAttrVal["pss".$inventory_master_specifier_id."_diamond_shape_id"] : @$_POST["pss".$inventory_master_specifier_id."_diamond_shape_id"];

							$sql = ""; 
							if( MANUFACTURER_ID === 7 )
							{
								$sql = "SELECT pa.product_attribute_id, pa.pa_value FROM product_attribute pa 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
							else 
							{
								$sql = "SELECT pa.product_attribute_id, pac.pa_value 
										FROM product_attribute pa 
										INNER JOIN product_attribute_cctld pac
										ON ( pac.product_attribute_id=pa.product_attribute_id AND manufacturer_id=".MANUFACTURER_ID." ) 
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." 
	   									ORDER BY pa.pa_sort_order "; 
							}
								
							$product_attributeArr = getDropDownAry($sql,"product_attribute_id", "pa_value", '', false);
							
							echo form_radioArry( "pss".$inventory_master_specifier_id."_diamond_shape_id",@$product_attributeArr,@$product_attribute_id,'style="width: 10%;" ');
							?>
			                </td>
			              </tr>
		              		              
		              </tbody>
		            </table>
		           </fieldset>
		
		        </div>
		        
		        
        <?php
			endif;
		?>
        
        
        <?php
        		$sideStoneCnt++;
        	endforeach;
        	endif;
        ?>
        
      <input type="hidden" value="0" name="is_selection_updated" /> 
      </form>
   
  	</div>
  </div>
</div>

<script type="text/javascript">
var company_labour = '<?php echo getField("config_value","configuration","config_key","LABOUR_CHARGE"); ?>';
var company_profit = '<?php echo getField("config_value","configuration","config_key","COMPANY_PROFIT"); ?>';

//incase user adds more stone then it will be added from number 3
var product_stone_number = 3;
<!--
/*------------------------------------------
	@author Cloudwebs
	@abstract ajax fuunction call to calclulate product price
	 Change Note: Change is made on 28-9-2013 when there is metal_weight requirement changed and now metal weight is specified for each metal_price category
	@param obj checkbox object
	@param type center stone,side stone or metal type
	@param is_single specifies if calculation to be done for single checkbox or check array
------------------------------------------*/

	$(document).ready(function(e)
	{
		calcProdPrice(null,'ss0_p',false,false,0);
		calcProdPrice(null,'ss1_p',false,false,0);
		calcProdPrice(null,'ss2_p',false,false,0);
		calcProdPrice(null,'mt_p',false,false,0);
		
		<?php
            if(isset($product_side_stonesData) && sizeof($product_side_stonesData) > 0):
            	foreach( $product_side_stonesData as $k=>$ar ):
		?>
					calcProdPrice(null,'ss<?php echo $ar['inventory_master_specifier_id']?>_p',false,false, <?php echo $ar['inventory_master_specifier_id']?>);
		<?php
        		endforeach;
			endif;	
		?>			
	});

	$(window).load(function()
	{
		//update hidden field at load time to zero
		$('input[name=is_selection_updated]').val(0);
	});
	
/**
 * @abstract function update price calc flag
 */
 	function updateFlag()
	{
		//alert('test');
		$('input[name=is_selection_updated]').val(1);
	}
	
	function calcProdPrice(obj,type,is_single,is_metal_weight_box, product_stone_number)
	{
		var weight ="";
		var no ="";
		var id ="";
		
		if(type=="ss0_p")
		{
			weight = $('input[name=product_side_stone0_weight]').val();
		}
		else if(type == "ss1_p")
		{
			weight = $('input[name=product_side_stone1_weight]').val();
		}
		else if(type == "ss2_p")
		{
			weight = $('input[name=product_side_stone2_weight]').val();
		}
		else if(type != "mt_p")
		{
			weight = $('input[name=product_side_stone'+product_stone_number+'_weight]').val();
		}
		else if(type == "mt_p")
		{
			if(is_metal_weight_box)
			{
				weight = $(obj).val();
			}
			else if(is_single)
			{
				id = $(obj).val();
				weight = $('#pmw_'+id).val();
			}
			else 	//IF MUPLTIPLE	
			{
				weight = 1; //dummy weight will not be used in calc actually added to add abstraction when change made to prevent other changes
			}
		}

		if(weight == '' || typeof weight === "undefined" || !isNumber(weight))
		{
			if(is_single)
			{
				if(is_metal_weight_box)
					id = $(obj).attr('data-');	
				else
					id = $(obj).val();	

				$('#span_'+type+id).text('Specify weight first.');
			}
			else
			{
				$('input[name='+type+'\\[\\]]:checked').each(function()
				{    
					id = $(this).val();	
					$('#span_'+type+id).text('Specify weight first.');
				});
			}
			setProductPrice();
			return false;
		}
		
		var is_checked = false;
		if(is_single)
		{
			if(is_metal_weight_box)
			{
				id = $(obj).attr('data-');	
				is_checked = $('#chk_'+type+'_'+id).is(':checked');				
			}
			else
			{
				id = $(obj).val();	
				is_checked = $(obj).is(':checked');				
			}
			
			if(is_checked)
			{
				form_data = {id : id, weight : weight, type : (type != "mt_p") ? "dp" : "mp"};
				var loc = (base_url+'admin/'+lcFirst(controller)+'/getDiaMetPrice');
				$.post(loc, form_data, function(data) {
					data = $.parseJSON(data);
					$('#span_'+type+id).text(data[0]);
					setProductPrice();
					return false;
				});
			}
			else
			{
				$('#span_'+type+id).text(0);
				setProductPrice();
				return false;
			}
		}
		else
		{
			if(type=="mt_p")
				weight ='';	//here dummy weight is initialized to empty
			
			var id_temp =0;
			$('input[name='+type+'\\[\\]]:checked').each(function()
			{    
				is_checked = true;
				id_temp = $(this).val();
				id += id_temp + "|";
				
				if(type=="mt_p")	//only for metal category
					weight +=$('#pmw_'+id_temp).val() + "|";
			});
			
			if(is_checked)
			{
				form_data = {id : id.substring(0,id.length -1), weight : weight, type : (type == "ss0_p" || type == "ss1_p" || type == "ss2_p" || product_stone_number != 0) ? "dp" : "mp"};
				var loc = (base_url+'admin/'+lcFirst(controller)+'/getDiaMetPrice');
				$.post(loc, form_data, function(data) {
					
					data = $.parseJSON(data);
					var cnt = 0;
					$('input[name='+type+'\\[\\]]:checked').each(function()
					{    
						id = $(this).val();
						$('#span_'+type+id).text(data[cnt]);
						cnt++;
					});
					setProductPrice();
					return false;
				});
			}
		}
		
	}

/**
 * @abstract function will check if string is number
 */	
	function isNumber(n)
	{
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}

// set price in product price text box
	function setProductPrice()
	{
		//alert('test');
		//update hidden field
		 $('input[name=is_selection_updated]').val(1);
		
		var ss0_p =0;
		var ss1_p = 0;
		var ss2_p = 0;
		var mt_p =0;
		$('input[name=ss0_p\\[\\]]:checked').each(function()
		{    
			id = $(this).val();
			ss0_p = (isNaN($('#span_ss0_p'+id).text()))?0 : $('#span_ss0_p'+id).text();
			return false;
		});
	
		$('input[name=ss1_p\\[\\]]:checked').each(function()
		{    
			id = $(this).val();
			ss1_p = (isNaN($('#span_ss1_p'+id).text()))?0 : $('#span_ss1_p'+id).text();
			return false;
		});
	
		$('input[name=ss2_p\\[\\]]:checked').each(function()
		{    
			id = $(this).val();
			ss2_p = (isNaN($('#span_ss2_p'+id).text()))?0 : $('#span_ss2_p'+id).text();
			return false;
		});
	
		$('input[name=mt_p\\[\\]]:checked').each(function()
		{    
			id = $(this).val();
			mt_p = (isNaN($('#span_mt_p'+id).text()))?0 : $('#span_mt_p'+id).text();
			return false;
		});
		
		var pro_price =  Math.round(ss0_p*1+ss1_p*1+ss2_p*1+ mt_p*1);
		pro_price = Math.round(pro_price + (pro_price * (company_labour/100)));
		pro_price = Math.round(pro_price + (pro_price * (company_profit/100)));
		$('input[name=product_price]').val(pro_price);
	}
	
/**
 * @author Cloudwebs
 * @abstract function will add new tabs for adding more diamond category 
 */
	function addStoneTab()
	{
		showLoader();
		form_data = {product_stone_number : product_stone_number};
		var loc = (base_url+'admin/'+lcFirst(controller)+'/addStoneTab');
		$.post(loc, form_data, function(data) {
			
			var arr = $.parseJSON(data);
			if(arr['type'] == 'success')
			{
				product_stone_number++;	
				$('#tabmetal').before( arr['tab'] );
				$('#form').append( arr['tab_content'] );

				//initialize new tabs
				$('#tabs a').tabs();
				$('.htabs a').tabs();
			}
			hideLoader();
		});
	}
	
//-->
</script>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
$('.htabs a').tabs();
$('.vtabs a').tabs();
//-->
</script>