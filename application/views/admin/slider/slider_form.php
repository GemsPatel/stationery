
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/sliderForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Slider </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Slider Name:</td>
                  <td><input type="text" size="70" name="slider_name" value="<?php echo (@$slider_name)?$slider_name:set_value('slider_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('slider_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
            	  <td><span class="required">*</span>Image:</td>
              	  <td valign="top">
                  
                 	<div class="image">
                 <?php $url = (@$slider_image) ? $slider_image : ((@$_POST['slider_image']) ? $_POST['slider_image'] : 'images/admin/no_image.jpg');?>
                 <img src="<?php echo asset_url($url);?>" width="100" height="100" id="giftPrevImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                 		<input type="file" name="slider_image" id="sliderImg_00" onchange="readURL(this,'00');" style="display: none;">
                 		<input type="hidden" value="<?php echo (@$slider_image) ? $slider_image : @$_POST['slider_image'];?>" name="slider_image" id="hiddensliderImg" />
                 		<div align="center">
                  			<a onclick="$('#sliderImg_00').trigger('click');">Browse</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="clear:both;" onclick="javascript:clear_image('giftPrevImage_00')"; >Clear</a>
                 		</div>
             		</div><br>
             		<span class="small_text"><?php $allowedSize = getField("config_value", "configuration", "config_key","SLIDER_UPL_SIZE");
             										$allowedRec = getField("config_value", "configuration", "config_key","SLIDER_REC_IMG");
	                 	echo '(Maximum allowed size is '.$allowedSize.'KB, '.$allowedRec.')';
                 ?></span>
                    <span class="error_msg"><?php echo (@$error)?form_error('slider_image'):''; ?> </span>
            	 </td>
            	</tr>
                
                <tr>
                <td><span class="required">*</span>Link:</td>
                <td><input type="text" name="slider_url" size="70" value="<?php echo (@$slider_url)?$slider_url:set_value('slider_url');?>" />
                	<span class="error_msg"><?php echo (@$error)?form_error('slider_url'):''; ?> </span>
                </td>
                <tr class="hide">
                	<td>Size:</td>
                    
                	<td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); ?>
                        
                    </td>
              </tr>
              </tr>
                <tr>
                  <td>&nbsp;&nbsp; Sort Order:</td>
      	  		  <td>
                  <input type="text" size="3" name="slider_sort_order" value="<?php echo (@$slider_sort_order)?$slider_sort_order:@$_POST['slider_sort_order'];?>" />
                 </td>
                </tr>
                
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="slider_status">
                       <option value="0" selected="selected">Enabled</option>
                       <option value="1" <?php echo (@$slider_status=='1' || @$_POST['slider_status']=='1')?'selected="selected"':'';?>>Disabled</option>  
                     </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;Is Display:</td>
                  <td>
                     <select name="slider_display">
                       <option value="0" selected="selected">Top</option>
                       <option value="1" <?php echo (@$slider_display=='1' || @$_POST['slider_display']=='1')?'selected="selected"':'';?>>Bottom</option>  
                     </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;Layout:</td>
                  <td>
                     <select name="slider_layout">
                       <option value="D" selected="selected">Desktop</option>
                       <option value="M" <?php echo (@$slider_layout=='M' || @$_POST['slider_layout']=='M')?'selected="selected"':'';?>>Mobile</option>  
                     </select>
                  </td>
                </tr>
           	  </tbody>
            </table>
            
        </fieldset>
        </div>
                
      </form>
    </div>
  </div>
  
</div>
