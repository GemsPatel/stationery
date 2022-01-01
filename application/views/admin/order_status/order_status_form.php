<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/orderStatusForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Order Status</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Name:</td>
                  <td><input type="text" name="order_status_name" value="<?php echo (@$order_status_name) ? $order_status_name : set_value('order_status_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('order_status_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Key:</td>
                  <td><input type="text"   name="order_status_key" size="75" value="<?php echo (@$order_status_key)?$order_status_key:set_value('order_status_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('order_status_key'):''; ?> </span>
                      <small class="small_text">(Start order key with ORDER_ then write your words in upper case later)</small>
                  </td>
                </tr>
                <tr>
              <td><span class="required">*</span>Icon:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php  $url = (@$order_status_icon) ? $order_status_icon : ((@$_POST['order_status_icon']) ? $_POST['order_status_icon'] : asset_url('images/admin/no_image.jpg')); ?> 
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="odrPreImage_00"  class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="order_status_icon" id="odrImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$order_status_icon) ? $order_status_icon : @$_POST['order_status_icon'];?>" name="order_status_icon" id="hiddenCatImg" />
                     <div align="center">                                                                                                                
                        <small><a onclick="$('#odrImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('odrPreImage_00')"; >Clear</a></small>
                     </div>
             	</div>
                
                <span class="error_msg"><?php echo (@$error) ? form_error('order_status_icon'):'' ?></span>
            </td> 
            </tr>
            <tr>
                  <td>&nbsp;&nbsp; Message :</td>
                  <td><textarea id="order_status_msg" name="order_status_msg" cols="40" rows="5"><?php echo (@$order_status_msg)?$order_status_msg:set_value('order_status_msg');?></textarea></td>
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
              <td><input type="text" size="3" name="order_status_sort_order" value="<?php echo (@$order_status_sort_order)?$order_status_sort_order:@$_POST['order_status_sort_order'];?>"></td>
            </tr>
            </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="order_status_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$order_status_status=='1' || @$_POST['order_status_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
