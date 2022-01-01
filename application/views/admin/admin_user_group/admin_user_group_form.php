<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/adminUserGroupForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Admin User Group </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Name :</td>
                  <td><input type="text" name="admin_user_group_name" value="<?php echo (@$admin_user_group_name) ? $admin_user_group_name : set_value('admin_user_group_name')?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_group_name'):''; ?> </span>
                  </td>
                </tr>                
              	<tr>
                  <td><span class="required">*</span> Key:</td>
                  <td><input type="text" name="admin_user_group_key" value="<?php echo (@$admin_user_group_key)?$admin_user_group_key:set_value('admin_user_group_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId && $admin_user_group_key!="") ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('admin_user_group_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
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
