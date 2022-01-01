
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
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/shippingMethodForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Name:</td>
                <td><input type="text" size="48" name="shipping_method_name" value="<?php echo (@$shipping_method_name)?$shipping_method_name:set_value('shipping_method_name');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('shipping_method_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="shipping_method_key" size="75" value="<?php echo (@$shipping_method_key)?$shipping_method_key:set_value('shipping_method_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('shipping_method_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
                <td>&nbsp;&nbsp; Tracking URL:</td>
                <td>
                	<textarea name="shipping_method_url" cols="45" role="3"><?php echo (@$shipping_method_url)?$shipping_method_url:set_value('shipping_method_url');?></textarea>
				</td>
              </tr>
              <tr>
                <td><span class="required">*</span>Description:</td>
                <td><textarea id="shipping_method_description" rows="3" cols="45" name="shipping_method_description"><?php echo (@$shipping_method_description)?$shipping_method_description:set_value('shipping_method_description');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('shipping_method_description'):''; ?> </span>
                </td>
              </tr>
               <tr>
              <td><span class="required">*</span>Icon:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php
					 $url = (@$shipping_method_icon) ? $shipping_method_icon : ((@$_POST['shipping_method_icon']) ? $_POST['shipping_method_icon'] : load_image('')); ?>
                     <img src="<?php echo load_image($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="shipping_method_icon" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$shipping_method_icon) ? $shipping_method_icon : @$_POST['shipping_method_icon'];?>" name="shipping_method_icon" id="hiddenCatImg" />
                     <div align="center">
                        <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                     </div>
             	</div>
                
                <span class="error_msg"><?php echo (@$error)?form_error('shipping_method_icon'):''; ?> </span>
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
                <td><span class="required">*</span> Free Shipping:</td>
                <td><input type="text" size="10" name="shipping_method_free_shipping" value="<?php echo (@$shipping_method_free_shipping)?$shipping_method_free_shipping:set_value('shipping_method_free_shipping');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('shipping_method_free_shipping'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Handling Charges:</td>
                <td><input type="text" size="10" name="shipping_method_handling_charges" value="<?php echo (@$shipping_method_handling_charges)?$shipping_method_handling_charges:set_value('shipping_method_handling_charges');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('shipping_method_handling_charges'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Zip Code:</td>
                <td><input type="text" size="10" name="shipping_method_zip_code" value="<?php echo (@$shipping_method_zip_code)?$shipping_method_zip_code:set_value('shipping_method_zip_code');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('shipping_method_zip_code'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp; Maximium package height:</td>
                <td><input type="text" size="10" name="shipping_method_max_pack_height" value="<?php echo (@$shipping_method_max_pack_height)?$shipping_method_max_pack_height:@$_POST['shipping_method_max_pack_height'];?>">
				</td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp; Maximium package width:</td>
                <td><input type="text" size="10" name="shipping_method_max_pack_width" value="<?php echo (@$shipping_method_max_pack_width)?$shipping_method_max_pack_width:@$_POST['shipping_method_max_pack_width'];?>">
				</td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp; Maximium package depth:</td>
                <td><input type="text" size="10" name="shipping_method_max_pack_depth" value="<?php echo (@$shipping_method_max_pack_depth)?$shipping_method_max_pack_depth:@$_POST['shipping_method_max_pack_depth'];?>">
				 </td>
              </tr>
              <tr>
                <td>&nbsp;&nbsp; Maximium package weight:</td>
                <td><input type="text" size="10" name="shipping_method_max_pack_weight" value="<?php echo (@$shipping_method_max_pack_weight)?$shipping_method_max_pack_weight:@$_POST['shipping_method_max_pack_weight'];?>">
				</td>
              </tr>
              <tr>
              <td>&nbsp;&nbsp;Sort Order:</td>
              <td><input type="text" size="10" name="shipping_method_sort_order" value="<?php echo (@$shipping_method_sort_order)?$shipping_method_sort_order:@$_POST['shipping_method_sort_order'];?>"> 
              </td>
            </tr>	
              <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td><select name="shipping_method_status">
                  <option value="0" selected="selected">Enable</option>
                  <option value="1" <?php echo (@$shipping_method_status=='1' || @$_POST['shipping_method_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



