<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
         // CKEDITOR.replace( 'payment_method_description' );
		  	CKEDITOR.replace( 'payment_method_description',
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
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/paymentMethodForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Name:</td>
                <td><input type="text" size="75" name="payment_method_name" value="<?php echo (@$payment_method_name)?$payment_method_name:set_value('payment_method_name');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('payment_method_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text"   name="payment_method_key" size="75" value="<?php echo (@$payment_method_key)?$payment_method_key:set_value('payment_method_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('payment_method_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              <tr>
                <td><span class="required">*</span>Description:</td>
                <td><textarea id="payment_method_description" name="payment_method_description"><?php echo (@$payment_method_description)?$payment_method_description:set_value('payment_method_description');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('payment_method_description'):''; ?> </span>
                </td>
              </tr>
               <tr>
              <td><span class="required">*</span>Icon:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php
					 $url = (@$payment_method_icon) ? $payment_method_icon : ((@$_POST['payment_method_icon']) ? $_POST['payment_method_icon'] : load_image('')); ?>
                     <img src="<?php echo load_image($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="payment_method_icon" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$payment_method_icon) ? $payment_method_icon : @$_POST['payment_method_icon'];?>" name="payment_method_icon" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	</div>
                
                <span class="error_msg"><?php echo (@$error)?form_error('payment_method_icon'):''; ?> </span>
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
              <td><input type="text" size="3" name="payment_method_sort_order" value="<?php echo (@$payment_method_sort_order)?$payment_method_sort_order:@$_POST['payment_method_sort_order'];?>"> 
              </td>
            </tr>	
              <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td><select name="payment_method_status">
                  <option value="0" selected="selected">Enable</option>
                  <option value="1" <?php echo (@$payment_method_status=='1' || @$_POST['payment_method_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



