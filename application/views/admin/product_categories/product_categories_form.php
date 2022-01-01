<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
      
		CKEDITOR.replace( 'category_description',
    	{
       	 	filebrowserBrowseUrl : 'kcfinder/browse.php',
        	filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
        	filebrowserUploadUrl : 'kcfinder/upload.php',
        	filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
    	});
	
    });
</script>
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
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
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/categoryForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>

              <?php
              	if( INVENTORY_TYPE_ID === 0 ):
              ?>
	              <tr>
	                <td><span class="required">*</span> Inventory Type:</td>
	                <td>
	                <?php
						$setVal = (!empty($inventory_type_id)) ? @$inventory_type_id : ((!empty($_POST['inventory_type_id'])) ? @$_POST['inventory_type_id'] : inventory_typeIdForKey($this->session->userdata("IT_KEY")) );
						$sql = "SELECT inventory_type_id, it_name FROM inventory_type WHERE it_status=0";
						$manArr = getDropDownAry($sql,"inventory_type_id", "it_name", array('' => "Select Inventory Type"), false);
						echo form_dropdown('inventory_type_id',$manArr,$setVal,'style="width:12%; " ');
					?>
	                	<span class="error_msg"><?php echo (@$error)?form_error('inventory_type_id'):''; ?></span>
	                </td>
	              </tr>
	          <?php
	          	else:
	          ?>    
	          		<input type="hidden" name="inventory_type_id" value="<?php echo INVENTORY_TYPE_ID;?>"/>
	          <?php 
	          	endif;
	          ?>
              
              <tr>
                <td><span class="required">*</span> Category Name:</td>
                <td><input type="text" size="70" name="category_name" value="<?php echo (@$category_name)?$category_name:set_value('category_name');?>" onkeyup="getUrlName(this.value)">
				<span class="error_msg"><?php echo (@$error)?form_error('category_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>Category Alias:</td>
                <td><input type="text" size="70" name="category_alias" id="display_alias" value="<?php echo (@$_POST['category_alias'])? $_POST['category_alias']: @$category_alias; ?>" >
				<span class="error_msg"><?php echo (@$error)?form_error('category_alias'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>Category Meta Name:</td>
                <td><input type="text" size="70" name="category_meta_name" id="display_alias" value="<?php echo (@$_POST['category_meta_name'])? $_POST['category_meta_name']: @$category_meta_name; ?>" >
				<span class="error_msg"><?php echo (@$error)?form_error('category_meta_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>Parent Category Name:</td>
                <td>
				<?php 
				$setval =(@$parent_id)? $parent_id:@$_POST['parent_id'];
				echo form_dropdown('parent_id',getMultiLevelMenuDropdown(0,array(0=>'Select Category')),$setval,'style="width:200px;"');
				?>
                </td>
              </tr>
              <tr>
                <td>Brand Code:</td>
                <td><input type="text" name="category_brand_code" disabled="disabled" size="35" value="<?php echo (@$category_brand_code)?$category_brand_code:set_value('category_brand_code');?>" style="text-transform:uppercase"  />
				</td>
              </tr>
              <tr>
                <td> Royalty:</td>
                <td><input type="text" size="35" name="category_royalty" value="<?php echo (@$category_royalty)?$category_royalty:set_value('category_royalty');?>">
                <span class="error_msg"><?php echo (@$error)?form_error('category_royalty'):''; ?> </span>
				</td>
              </tr>
              <tr>
                <td><span class="required">*</span> Description:</td>
                <td><textarea id="category_description"  name="category_description"><?php echo (@$category_description)?$category_description:set_value('category_description');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('category_description'):''; ?> </span>
                </td>
              </tr>
            </tbody></table>
            
        </fieldset>
        </div>
        
        <div id="tab-data" style="display: block; width:50%; float:left;">
        <fieldset>
            <legend>Details</legend>
          <table class="form">
            <tbody>
            <tr>
              <td>Image:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php
					 $url = (@$category_image) ? $category_image : ((@$_POST['category_image']) ? $_POST['category_image'] : asset_url('images/admin/no_image.jpg')); ?>
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="category_image" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$category_image) ? $category_image : @$_POST['category_image'];?>" name="category_image" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	 </div>                
                <span class="error_msg"><?php echo (@$error)?form_error('category_image'):''; ?> </span>
                <span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","PRODUCT_CATEGORIES_IMG_UPLOAD_SIZE");
                 								$allowedRec = getField("config_value", "configuration", "config_key","PRODUCT_CAT_REC_IMG");
                 	echo '(Maximum allowed size is '.$allowedSize.'KB, '.$allowedRec.')';
                 ?></span>
            </td>
            </tr>
            <tr>
                <td>Image Size:</td>
                <td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); 
					  ?>                  
               </td>
              </tr>
              
              <tr>
              <td> Banner:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php
					 $url = (@$category_banner) ? $category_banner : ((@$_POST['category_banner']) ? $_POST['category_banner'] : asset_url('images/admin/no_image.jpg')); ?>
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="banPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="category_banner" id="catBan_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$category_banner) ? $category_banner : @$_POST['category_banner'];?>" name="category_banner" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catBan_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('banPrevImage_00')"; >Clear</a></small>
                     </div>
             	 </div><br>
             	 <span class="error_msg"><?php echo (@$error)?form_error('category_banner'):''; ?> </span>
             	 <span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","PRODUCT_CATEGORIES_BANNER_UPLOAD_SIZE");
                 								$allowedRec = getField("config_value", "configuration", "config_key","PRODUCT_REC_BANNER");
                 	echo '(Maximum allowed size is '.$allowedSize.'KB, '.$allowedRec.')';
                 ?></span>
                 
            </td>
            <tr>
                <td>Banner Size:</td>
                <td><?php
						$setval =(@$banner_size_id)? $banner_size_id:@$_POST['banner_size_id'];
					  	echo getBannerSizeDropdown($setval); 
					  ?>
               </td>
              </tr>
            </tr>
            <tr>
              <td>Sort Order:</td>
              <td><input type="text" size="3" name="category_sort_order" value="<?php echo (@$category_sort_order)?$category_sort_order:@$_POST['category_sort_order'];?>"> 
              </td>
            </tr>
            <tr>
              <td>Status:</td>
              <td><select name="category_status">
                  <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$category_status=='1' || @$_POST['category_status']=='1')?'selected="selected"':'';?>>Disable</option>
                  </select></td>
            </tr>
          </tbody></table>
          
        </fieldset>
        </div>
        <div id="tab-data1" style="display: block; width:49%; padding-left:12px; float:left;">
        <fieldset>
            <legend>SEO</legend>
          	<?php $this->load->view('admin/elements/seo_form');?>
            
        </fieldset>
        </div>
        
        
      </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
//-->
</script>



