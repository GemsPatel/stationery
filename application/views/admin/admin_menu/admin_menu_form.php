<div id="content" data-test="7"> 
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/adminMenuForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Admin Menus</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Menu Name:</td>
                <td><input type="text" name="am_name" value="<?php echo (@$am_name)?$am_name:set_value('am_name');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('am_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td> Class Key:</td>
                <td><input type="text" name="am_class_name" value="<?php echo (@$am_class_name)?$am_class_name:set_value('am_class_name');?>"  <?php echo (@$this->cPrimaryId) ? 'readonly="readonly"': ''; ?>>
				<span class="error_msg"><?php echo (@$error)?form_error('am_class_name'):''; ?></span>
                <small class="small_text">For developer reference, do not edit if not required.</small>
                </td>
              </tr>
              <tr>
                <td>Parent Name:</td>
                <td>
				<?php 
				$setval =(@$am_parent_id)? $am_parent_id:@$_POST['am_parent_id'];
				echo form_dropdown('am_parent_id',getMultiLevelAdminMenuDropdown(),@$setval,'style="width:140px;"');
				?>
                </td>
              </tr>
              <tr>
                  <td><span class="required">*</span> Icon:</td>
                  <td valign="top">
                     <div class="image" style="padding:5px;" align="center">
                         <?php
                         $url = (@$am_icon) ? $am_icon : ((@$_POST['am_icon']) ? $_POST['am_icon'] : asset_url('images/admin/no_image.jpg')); ?>
                         <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                         <input type="file" name="am_icon_file" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                         <input type="hidden" value="<?php echo (@$am_icon) ? $am_icon : @$_POST['am_icon'];?>" name="am_icon" id="hiddenCatImg" />
                         <div align="center">
                            <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                         </div>
                    </div>
                    
                    <span class="error_msg"><?php echo (@$error)?form_error('am_icon'):''; ?> </span>
            	</td>
              </tr>
              <tr>
                <td>Size:</td>
                <td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); 
					?>
                  
               </td>
              </tr>
              <tr>
                  <td>Sort Order:</td>
                  <td><input type="text" size="3" name="am_sort_order" value="<?php echo (@$am_sort_order)?$am_sort_order:@$_POST['am_sort_order'];?>"> 
              	  </td>
             </tr>
              <tr>
                  <td>Status:</td>
                  <td><select name="am_status">
                      <option value="0" selected="selected">Enable</option>
                      <option value="1" <?php echo (@$am_status=='1' || @$_POST['am_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



