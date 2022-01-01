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
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/manufacturerForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span>  Name:</td>
                <td><input type="text" size="50" name="manufacturer_name" value="<?php echo (@$manufacturer_name)?$manufacturer_name:@$_POST['manufacturer_name'];?>" />
				<span class="error_msg"><?php echo (@$error)?form_error('manufacturer_name'):''; ?></span>
                </td>
             </tr>              
             <tr>
              <td><span class="required">*</span> Key:</td>
              <td><input type="text" size="50" name="manufacturer_key" value="<?php echo (@$manufacturer_key)?$manufacturer_key:@$_POST['manufacturer_key'];?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId && $manufacturer_key!="") ? 'readonly="readonly"': ''; ?> />
                  <span class="error_msg"><?php echo (@$error)?form_error('manufacturer_key'):''; ?> </span>
                  <small class="small_text">For developer reference, do not edit if not required.</small>
              </td>
             </tr>
             <tr>
                <td><span class="required">*</span>  Email Id:</td>
               	<td><input type="text" size="50" name="manufacturer_email_id" value="<?php echo (@$manufacturer_email_id)?$manufacturer_email_id:set_value(		'manufacturer_emailid');?>" />
				<span class="error_msg"><?php echo (@$error)?form_error('manufacturer_email_id'):''; ?></span>
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
                 	 <?php
					 $url = (@$manufacturer_image) ? $manufacturer_image : ((@$_POST['manufacturer_image']) ? $_POST['manufacturer_image'] : asset_url('images/admin/no_image.jpg')); ?>
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="manufacturer_image" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$manufacturer_image) ? $manufacturer_image : @$_POST['manufacturer_image'];?>" name="manufacturer_image" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	</div>
               
                <span class="error_msg"><?php echo (@$error)?form_error('manufacturer_image'):''; ?> </span>
            </td>
            </tr>
            <tr>
                <td>Size:</td>
                    
                <td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); ?>
                        <span class="error_msg"><?php echo (@$error)?form_error('image_size_id'):''; ?> </span>
                </td>
              </tr>	
            <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td>
              	<select name="manufacturer_status">
                     	 <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$manufacturer_status=='1' || @$_POST['manufacturer_status']=='1')?'selected="selected"':'';?>>Disable</option>
            	 </select>
              </td>  
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



