<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/errorCodeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Error Code</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Error Code:</td>
                  <td><input type="text" name="error_code" size="20" value="<?php echo (@$error_code)?$error_code:set_value('error_code');?>" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('error_code'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Message:</td>
                  <td>
                     <textarea class="textarea" name="error_message" rows="2" cols="72"><?php echo (@$error_message) ? $error_message : set_value('error_message');?></textarea>
                     <span class="error_msg"><?php echo (@$error)?form_error('error_message'):''; ?> </span>
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
