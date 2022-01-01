
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/adminUserForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Admin User </legend>
			<table class="form">
              <tbody>
               <tr>
                  <td>&nbsp;&nbsp;Manufacturer:</td>
                  <td>
					<?php   
                    $sql = "SELECT manufacturer_id, manufacturer_name FROM manufacturer WHERE manufacturer_status=0 ";
                    $userArr = getDropDownAry( $sql,"manufacturer_id", "manufacturer_name", array('' => "-- Select Department --"), false);
                    $setval =(@$manufacturer_id)? $manufacturer_id:@$_POST['manufacturer_id'];
                    echo form_dropdown('manufacturer_id',$userArr,$setval);
                    ?>
                 </td>
                </tr>   
              	<tr>
                  <td><span class="required">*</span> First Name:</td>
                  <td><input type="text" name="admin_user_firstname" value="<?php echo (@$admin_user_firstname)?$admin_user_firstname:@$_POST['admin_user_firstname'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_firstname'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Last Name:</td>
                  <td><input type="text" name="admin_user_lastname" value="<?php echo (@$admin_user_lastname)?$admin_user_lastname:@$_POST['admin_user_lastname'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_lastname'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Email Id:</td>
                  <td><input type="text" size="40" name="admin_user_emailid" value="<?php echo (@$admin_user_emailid)?$admin_user_emailid:@$_POST['admin_user_emailid'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_emailid'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;XMPP ID:</td>
                  <td><input type="text" name="admin_xmpp_id" size="40" value="<?php echo (@$admin_xmpp_id)?$admin_xmpp_id:@$_POST['admin_xmpp_id'];?>" />
                  </td>
                </tr>
               <tr>
                  <td>&nbsp;&nbsp;Can Chat:</td>
                  <td>
                     <select name="admin_can_chat">
                         <option value="0" selected="selected">Yes</option>
                       	 <option value="1" <?php echo (@$admin_can_chat=='1' || @$_POST['admin_can_chat']=='1')?'selected="selected"':'';?>>No</option>
                     </select>
                  </td>
                </tr>
                 <tr>
                  <td>&nbsp;&nbsp;Chat Priority:</td>
                  <td><input type="text" name="admin_chat_priority"value="<?php echo (@$admin_chat_priority)?$admin_chat_priority:@$_POST['admin_chat_priority'];?>" />
                  </td>
                </tr>             
               <tr>
              <td><span class="required">*</span>Profile Image:</td>
              <td valign="top">
                 <div class="image" style="padding:5px;" align="center">
                 	 <?php
					 $url = (@$admin_profile_image) ? $admin_profile_image : ((@$_POST['admin_profile_image']) ? $_POST['admin_profile_image'] : asset_url('images/admin/no_image.jpg')); ?>
                     <img src="<?php echo asset_url($url);?>" width="35" height="35" id="proPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                     <input type="file" name="admin_profile_image" id="proImg_00" onchange="readURL(this,'00');" style="display: none;">
                     <input type="hidden" value="<?php echo (@$admin_profile_image) ? $admin_profile_image : @$_POST['admin_profile_image'];?>" name="admin_profile_image" id="hiddenProImg" />
                     <div align="center">
                        <small><a onclick="$('#proImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('proPrevImage_00')"; >Clear</a></small>
                     </div>
             	 </div>
                <span class="error_msg"><?php echo (@$error)?form_error('admin_profile_image'):''; ?> </span>
            </td>
            </tr>
                <tr>
                  <td><span class="required">*</span> Phone No:</td>
                  <td><input type="text" name="admin_user_phone_no" value="<?php echo (@$admin_user_phone_no)?$admin_user_phone_no:@$_POST['admin_user_phone_no'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_phone_no'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Password:</td>
                  <td><input type="password" name="admin_user_password" value="" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_password'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Confirm Password:</td>
                  <td><input type="password" name="admin_user_password_confirm" value="" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_password_confirm'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> User Group:</td>
                  <td>
                  		<?php   
						$sql = "SELECT admin_user_group_id, admin_user_group_name FROM admin_user_group";
					  	$userArr = getDropDownAry($sql,"admin_user_group_id", "admin_user_group_name", array('' => "-- Select Users Group --"), false);
						$setval =(@$admin_user_group_id)? $admin_user_group_id:@$_POST['admin_user_group_id'];
						echo form_dropdown('admin_user_group_id',$userArr,$setval);
					    ?>
                        <span class="error_msg"><?php echo (@$error)?form_error('admin_user_group_id'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp; Newsletter:</td>
                  <td>
                     <select name="admin_user_newslatter">
                         <option value="0" selected="selected">Yes</option>
                       	 <option value="1" <?php echo (@$admin_user_newslatter=='1' || @$_POST['admin_user_newslatter']=='1')?'selected="selected"':'';?>>No</option>
                     </select>
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="admin_user_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$admin_user_status=='1' || @$_POST['admin_user_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
