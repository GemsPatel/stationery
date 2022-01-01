<!-- Messages are shown when a link with these attributes are clicked: href="#messages" rel="modal"  -->
  <div class="pre_loader"><div class="listingPreloader"></div></div>
  <form id="form" enctype="multipart/form-data" method="post" onsubmit="return saveAccountSettings(this);">
  <div class="notification_area"></div>
  <fieldset>
  <legend>Change Password</legend>
  <table class="form">
    <tbody>
        <tr>
          <td>Old Password:</td>
          <td><input type="password" name="old_pass" class="password_fields" value="<?php echo (@$old_pass)?$old_pass:@$_POST['old_pass'];?>">
          <span class="input-notification error png_bg" id="popup-error" for="old_pass"></span></td>
        </tr>
        <tr>
          <td>New Password:</td>
          <td><input type="password" name="new_pass" class="password_fields" value="<?php echo (@$new_pass)?$new_pass:@$_POST['new_pass'];?>">
          <span class="input-notification error png_bg" id="popup-error" for="new_pass"></span></td>
        </tr>
        <tr>
          <td>Confirm Password:</td>
          <td><input type="password" name="confirm_pass" class="password_fields" value="<?php echo (@$confirm_pass)?$confirm_pass:@$_POST['confirm_pass'];?>">
          <span class="input-notification error png_bg" id="popup-error" for="confirm_pass"></span></td>
        </tr>
        <tr>
          <td></td>
          <td><div class="buttons"><input class="button" type="submit" value="Save Changes"></div></td>
        </tr>
    </tbody>
  </table>
  </fieldset>
  </form>
