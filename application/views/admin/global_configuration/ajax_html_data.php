	<form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/globalConfigurationForm')?>">
    <input type="hidden" name="item_id" value="<?php echo @$site_config_id; ?>"  />
        <fieldset>
            <legend>Global Configuration</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td class="left" width="15%">Site Offline</td>
                  <td class="left" width="5%"><label><input type="radio" name="site_offline" value="1" <?php echo (@$site_offline=='1') ? 'checked="checked"' : ''?> /> Yes </label></td>
                  <td class="left" width="5%"><label><input type="radio" name="site_offline" value="0" <?php echo (@$site_offline=='0') ? 'checked="checked"' : ''?> /> No </label></td>
                  <td class="left" width="75%"></td>
                </tr>
              	<tr>
                  <td class="left">Offline Message</td>
                  <td class="left"><label><input type="radio" name="offline_msg" value="0" <?php echo (@$offline_msg=='0') ? 'checked="checked"' : ''?> /> Hide </label></td>
                  <td class="left" colspan="2"><label><input type="radio" name="offline_msg" value="1" <?php echo (@$offline_msg=='1') ? 'checked="checked"' : ''?> /> Custom Message </label></td>
                </tr>
              	<tr>
                  <td class="left">Custom Message</td>
                  <td class="left" colspan="3"><textarea name="custom_message" rows="4" cols="50"><?php echo @$custom_message?></textarea></td>
                </tr>
              	<tr>
                  <td class="left">Offline Image</td>
                  <td class="left" colspan="3">
                    <div class="image" style="padding:5px;" align="center">
                         <?php
                         $url = (@$offline_image) ? $offline_image : ((@$_POST['offline_image']) ? $_POST['offline_image'] : asset_url('images/admin/no_image.jpg')); ?>
                         <img src="<?php echo asset_url($url);?>" width="100" height="100" id="offPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                         <input type="file" name="offline_image" id="offImg_00" onchange="readURL(this,'00');" style="display: none;">
                         <input type="hidden" value="<?php echo (@$offline_image) ? $offline_image : @$_POST['offline_image'];?>" name="offline_image" id="hiddenCatImg" />
                         <div align="center">
                            <small><a onclick="$('#offImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('offPrevImage_00')"; >Clear</a></small>
                         </div>
                    </div>
                  </td>
                </tr>
           	  </tbody>
            </table>            
        </fieldset>
        
        <fieldset>
            <legend>Notifications</legend>
            <table class="form">
              <tbody>
              	<tr>
                  <td class="left" width="15%">Show Notification for New Orders</td>
                  <td class="left" width="5%"><label><input type="radio" name="admin_user_order_noti_status" value="0" <?php echo (@$admin_user_order_noti_status==0) ? 'checked="checked"' : ''?> /> Yes </label></td>
                  <td class="left" width="5%"><label><input type="radio" name="admin_user_order_noti_status" value="1" <?php echo (@$admin_user_order_noti_status==1) ? 'checked="checked"' : ''?> /> No </label></td>
                  <td class="left" width="75%"></td>
                </tr>
              	<tr>
                  <td class="left">Show Notification for New Customers</td>
                  <td class="left"><label><input type="radio" name="admin_user_customer_noti_status" value="0" <?php echo (@$admin_user_customer_noti_status==0) ? 'checked="checked"' : ''?> /> Yes </label></td>
                  <td class="left"><label><input type="radio" name="admin_user_customer_noti_status" value="1" <?php echo (@$admin_user_customer_noti_status==1) ? 'checked="checked"' : ''?>/> No </label></td>
                </tr>
              	<tr>
                  <td class="left">Show Notification for New Messages</td>
                  <td class="left"><label><input type="radio" name="admin_user_message_noti_status" value="0" <?php echo (@$admin_user_message_noti_status==0) ? 'checked="checked"' : ''?> /> Yes </label></td>
                  <td class="left"><label><input type="radio" name="admin_user_message_noti_status" value="1" <?php echo (@$admin_user_message_noti_status==1) ? 'checked="checked"' : ''?> /> No </label></td>
                </tr>
           	  </tbody>
            </table>            
        </fieldset>
        
        <fieldset>
            <legend>Default Metadata Settings</legend>
			<?php $this->load->view('admin/elements/seo_form');?>       
        </fieldset>

		<?php
			$is_allow = false;
            if( checkIsSuperAdmin( true ) && $is_allow ):
        ?>
                <fieldset>
                    <legend>ccTLD Settings</legend>
                    <input type="hidden" name="setccTLD" id="setccTLD" value="true"/>	
                    <table class="form">
                      <tbody>
                        <tr>
                          <td class="left" width="15%">flip ccTLD:</td>
                          <td colspan="3">
							<?php   
                            $sql = "SELECT manufacturer_id, manufacturer_name FROM manufacturer WHERE manufacturer_status=0 ";
                            $userArr = getDropDownAry( $sql,"manufacturer_id", "manufacturer_name", array('' => "-- Select ccTLD --"), false);
                            echo form_dropdown('ccTLD',$userArr, MANUFACTURER_ID);
                            ?>
                          </td>
                        </tr>
					  </tbody>
                    </table>  
                </fieldset>
        <?php
            endif;
        ?>
        
    </form>
