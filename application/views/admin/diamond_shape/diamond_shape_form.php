<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/diamondShapeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Diamond Shape </legend>
			<table class="form">
                <tbody>
              	<tr>
                  <td><span class="required">*</span> Diamond Shape Name :</td>
                  <td><input type="text" name="diamond_shape_name" value="<?php echo (@$diamond_shape_name)?$diamond_shape_name:set_value('diamond_shape_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('diamond_shape_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="diamond_shape_key" size="75" value="<?php echo (@$diamond_shape_key)?$diamond_shape_key:set_value('diamond_shape_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('diamond_shape_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
              <td><span class="required">*</span>Icon:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php $url = (@$diamond_shape_icon) ? $diamond_shape_icon : ((@$_POST['diamond_shape_icon']) ? $_POST['diamond_shape_icon'] : asset_url('images/admin/no_image.jpg')); ?> 
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="diamond_shape_icon" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$diamond_shape_icon) ? $diamond_shape_icon : @$_POST['diamond_shape_icon'];?>" name="diamond_shape_icon" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	</div>
                
                <span class="error_msg"><?php echo (@$error)?form_error('diamond_shape_icon'):''; ?> </span>
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
                  <td>&nbsp;&nbsp;  Sort Order :</td>
                  <td><input type="text" size="3" name="diamond_shape_sort_order" value="<?php echo (@$diamond_shape_sort_order)? $diamond_shape_sort_order :@$_POST['diamond_shape_sort_order'];?>" />
                      
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp; Status:</td>
                  <td>
                     <select name="diamond_shape_status">
                       <option value="0" selected="selected">Enable</option>
                       <option value="1" <?php echo (@$diamond_shape_status=='1' || @$_POST['diamond_shape_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
