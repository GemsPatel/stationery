<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/countryForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Country </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Country Name:</td>
                  <td><input type="text" name="country_name" value="<?php echo (@$country_name)?$country_name:@$_POST['country_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('country_name'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                 <td><span class="required">*</span>Country Key:</td>
                  <td><input type="text" name="country_key" value="<?php echo (@$country_key)?$country_key:@$_POST['country_key'];?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('country_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              	<tr>
                  <td><span class="required">*</span> Country Code:</td>
                  <td><input type="text" name="country_code" value="<?php echo (@$country_code)?$country_code:@$_POST['country_code'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('country_code'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                  <td> Female Default Ring Size:</td>
                  <td><input type="text" name="female_default_ring_size" value="<?php echo (@$female_default_ring_size)?$female_default_ring_size:@$_POST['female_default_ring_size'];?>" /></td>
                </tr>
              	<tr>
                  <td> Male Default Ring Size:</td>
                  <td><input type="text" name="male_default_ring_size" value="<?php echo (@$male_default_ring_size)?$male_default_ring_size:@$_POST['male_default_ring_size'];?>" /></td>
                </tr>
              	<tr>
                  <td> Other Default Ring Size:</td>
                  <td><input type="text" name="other_default_ring_size" value="<?php echo (@$other_default_ring_size)?$other_default_ring_size:@$_POST['other_default_ring_size'];?>" /></td>
                </tr>
                <tr>
                  <td> Import Duty Description:</td>
                  <td><input type="text" name="country_import_duty_desc" value="<?php echo (@$country_import_duty_desc)?$country_import_duty_desc:@$_POST['country_import_duty_desc'];?>" /></td>
                </tr>                
              	<tr>
                  <td> Import Duty:</td>
                  <td><input type="text" name="country_import_duty" value="<?php echo (@$country_import_duty)?$country_import_duty:@$_POST['country_import_duty'];?>" /></td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="country_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$country_status=='1' || @$_POST['country_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
