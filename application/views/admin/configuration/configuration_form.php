<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/configurationForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Configuration</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="config_key" size="75" value="<?php echo (@$config_key)?$config_key:set_value('config_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('config_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Name:</td>
                  <td>
                     <input class="text" type="text" name="config_display_name" maxlength="200" size="75" value="<?php echo (@$config_display_name) ? $config_display_name : set_value('config_display_name');?>">
                     <span class="error_msg"><?php echo (@$error)?form_error('config_display_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Value:</td>
                  <td>
                     <textarea class="textarea" name="config_value" rows="6" cols="72"><?php echo (@$config_value) ? $config_value : set_value('config_value');?></textarea>
                     <span class="error_msg"><?php echo (@$error)?form_error('config_value'):''; ?> </span>
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
