<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/metalColorForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Metal Color </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Color :</td>
                  <td><input type="text" name="metal_color_name" value="<?php echo (@$metal_color_name)?$metal_color_name:@$_POST['metal_color_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_color_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="metal_color_key" size="75" value="<?php echo (@$metal_color_key)?$metal_color_key:set_value('metal_color_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_color_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
                <td><span class="required">*</span>Icon:</td>
                <td valign="top">
                   <div class="image">
                    <?php  $url = (@$metal_color_icon) ? $metal_color_icon : ((@$_POST['metal_color_icon']) ? $_POST['metal_color_icon'] : asset_url('images/admin/no_image.jpg')); ?> 
                   <img src="<?php echo asset_url($url);?>" width="100" height="100" id="metalPrevImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                   <input type="file" name="metal_color_icon" id="metalImg_00" onchange="readURL(this,'00');" style="display: none;">
                   <input type="hidden" value="<?php echo (@$metal_color_icon) ? $metal_color_icon : @$_POST['metal_color_icon'];?>" name="metal_color_icon" id="hiddenMetalImg" />
                   <div align="center">
                   	<a onclick="$('#metalImg_00').trigger('click');">Browse</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a style="clear:both;" onclick="javascript:clear_image('metalPrevImage_00')"; >Clear</a>
                   </div>
               	   </div>
              
                <span class="error_msg"><?php echo (@$error)?form_error('metal_color_icon'):''; ?></span>
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
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="metal_color_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$metal_color_status=='1' || @$_POST['metal_color_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
