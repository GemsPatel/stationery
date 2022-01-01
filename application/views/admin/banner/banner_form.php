<script type="text/javascript" src="<?php echo asset_url('js/admin/chosen/chosen.jquery.js');?>"></script>
<link rel="stylesheet" href="<?php echo asset_url('css/admin/chosen/chosen.css');?>" />
<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
       // CKEDITOR.replace( 'banner_description' );
			CKEDITOR.replace( 'banner_description',
   			 {
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
   			 });
			 
		//autocomplete pulgin chosen for custom field
		$(".select_chosen").chosen();
	
    });
</script>
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/bannerForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
      
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Banner Name:</td>
                <td><input type="text" size="100" name="banner_name" value="<?php echo (@$banner_name)?$banner_name:set_value('banner_name');?>" onkeyup="getUrlName(this.value)">
				<span class="error_msg"><?php echo (@$error)?form_error('banner_name'):''; ?></span>
                </td>
              </tr>
                <tr>
                    <td> Product categories:</td>
                    <td>
                    <?php 
                    if(@$this->cPrimaryId != '' && !isset($_POST['category_id']))
                    {
                        //fetch banner_category mapping
                        $res = executeQuery("SELECT category_id FROM banner_category_map WHERE banner_id=".$this->cPrimaryId."");
                        $category_idArr = array();
                        if(!empty($res))
                        {
                            foreach($res as $k=>$ar)
                            {
                                $category_idArr[] = $ar['category_id'];
                            }
                        }
                    }

					$setRelCat = (@$category_idArr) ? $category_idArr : @$_POST['category_id'];
					//$tableName = (MANUFACTURER_ID !=7) ? 'product_categories_cctld' : 'product_categories';
                    //$sql = "SELECT category_id, category_name FROM ".$tableName." WHERE category_status=0";
                    //$product_categoryArr = getDropDownAry($sql,"category_id", "category_name", '', false);
                    //echo form_dropdown('category_id[]',@$product_categoryArr,@$setRelCat,' class="select_chosen" multiple="true"  style="width: 70%;"');
					echo form_dropdown('category_id[]',getMultiLevelMenuDropdown(0,array()),@$setRelCat,' style="width:30%;" class="select_chosen" multiple="true" ');
                    ?>
                    </td>
                </tr>
              <tr>
                  <td><span class="required">*</span> Banner Key:</td>
                  <td><input type="text" name="banner_key" size="75" value="<?php echo (@$banner_key)?$banner_key:set_value('banner_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('banner_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              <tr>
                <td><span class="required">*</span> Description:</td> 
                <td><textarea id="banner_description" name="banner_description"><?php echo (@$banner_description)?$banner_description:set_value('banner_description');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('banner_description'):''; ?> </span>
                </td>
              </tr>
              <tr>
              <td><span class="required">*</span>Image:</td>
              <td valign="top">
                 <div class="image">
          		  <?php  $url = (@$banner_image) ? $banner_image : ((@$_POST['banner_image']) ? $_POST['banner_image'] : asset_url('images/admin/no_image.jpg')); ?> 
                 <img src="<?php echo asset_url($url);?>" width="100" height="100" id="bannerPrevImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                 <input type="file" name="banner_image" id="bannerImg_00" onchange="readURL(this,'00');" style="display: none;">
                 <input type="hidden" value="<?php echo (@$banner_image) ? $banner_image : @$_POST['banner_image'];?>" name="banner_image" id="hiddenBannerImg" />
                 <div align="center">
                  	<a onclick="$('#bannerImg_00').trigger('click');">Browse</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="clear:both;" onclick="javascript:clear_image('bannerPrevImage_00')"; >Clear</a>
                 </div>
             </div><br>
              <span class="error_msg">
              <?php if(!@$error){?>
             <span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","BANNER_UPL_SIZE");
             								$allowedRec = getField("config_value", "configuration", "config_key","BANNER_REC_IMG");
                 	echo '(Maximum allowed size is '.$allowedSize.'KB, '.$allowedRec.')';
             ?></span><?php }else{
             	echo (@$error)?form_error('banner_image'):''; 
              }?></span>
            </td>
            </tr>
            <tr>
              	<td>Size:</td>
                    
               	<td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); ?>
                       
                </td>
            </tr>	
            <tr>
              <td> Image Alt Text:</td>
              <td><input type="text" size="25" name="banner_image_alt_text" value="<?php echo (@$banner_image_alt_text)?$banner_image_alt_text:@$_POST['banner_image_alt_text'];?>"></td>
            </tr>
            <tr>
              <td> Sort Order:</td>
              <td><input type="text" size="3" name="banner_sort_order" value="<?php echo (@$banner_sort_order)?$banner_sort_order:@$_POST['banner_sort_order'];?>"></td>
            </tr>
            <tr>
                <td><span class="required"></span> Link:</td>
                <td><input type="text" size="50" name="banner_link" value="<?php echo (@$banner_link)?$banner_link:set_value('banner_link');?>" onkeyup="getUrlName(this.value)">
				<span class="error_msg"><?php echo (@$error)?form_error('banner_link'):''; ?></span>
                </td>
              </tr>
            <tr>
              	<td> Status:</td>
              	<td>
                   		<select name="banner_status">
                     	 <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$banner_status=='1' || @$_POST['banner_status']=='1')?'selected="selected"':'';?>>Disable</option>
                     	</select>
              	</td>
            </tr>
            </tbody></table>
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



