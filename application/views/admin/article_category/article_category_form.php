<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
       // CKEDITOR.replace( 'article_category_description' );
			CKEDITOR.replace( 'article_category_description',
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
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/articleCategoryForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Category Name:</td>
                <td><input type="text" size="100" name="article_category_name" value="<?php echo (@$article_category_name)?$article_category_name:set_value('article_category_name');?>" onkeyup="getUrlName(this.value)">
				<span class="error_msg"><?php echo (@$error)?form_error('article_category_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp;Category Alias:</td>
                <td><input type="text" size="100" name="article_category_alias" id="display_alias" value="<?php echo (@$_POST['article_category_alias'])? $_POST['article_category_alias']: (@$article_category_alias); ?>" readonly="readonly"></td>
              </tr>
              <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="article_category_key" size="75" value="<?php echo (@$article_category_key)?$article_category_key:set_value('article_category_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('article_category_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              <tr>
                <td>&nbsp;&nbsp;Parent Category Name:</td>
                <td>
				<?php 
				 
				$setval =(@$article_category_parent_id)? $article_category_parent_id:@$_POST['article_category_parent_id'];
				echo form_dropdown('article_category_parent_id',getMultiLevelMenuDropdownArticle(0,array(''=>'Select Article Category')),$setval,'style="width:200px;"'); 
				?>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span>Description:</td>
                <td><textarea class="article_category_description" name="article_category_description"><?php echo (@$article_category_description)?$article_category_description:set_value('article_category_description');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('article_category_description'):''; ?> </span>
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
              <td><span class="required">*</span>Icon:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php $url = (@$article_category_image) ? $article_category_image : ((@$_POST['article_category_image']) ? $_POST['article_category_image'] : asset_url('images/admin/no_image.jpg')); ?> 
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="article_category_image" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$article_category_image) ? $article_category_image : @$_POST['article_category_image'];?>" name="article_category_image" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	</div><br>
                <span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","ARTICAL_CATEGORIES_IMG_UPLOAD_SIZE");
                 								$allowedRec = getField("config_value", "configuration", "config_key","ARTICAL_CAT_REC_ICON");
                 	echo '(Maximum allowed size is '.$allowedSize.'KB, '.$allowedRec.')';
                 ?></span>
                <span class="error_msg"><?php echo (@$error)?form_error('article_category_image'):''; ?> </span>
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
              <td>&nbsp;&nbsp;Sort Order:</td>
              <td><input type="text" size="3" name="article_category_sort_order" value="<?php echo (@$article_category_sort_order)?$article_category_sort_order:@$_POST['article_category_sort_order'];?>"></td>
            </tr>
            <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td><select name="article_category_status">
                  <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$article_category_status=='1' || @$_POST['article_category_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



