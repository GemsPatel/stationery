<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/metalTypeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Metal Type </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Type:</td>
                  <td><input type="text" name="metal_type_name" value="<?php echo (@$metal_type_name)?$metal_type_name:set_value('metal_type_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_type_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text"  name="metal_type_key"  size="75" value="<?php echo (@$metal_type_key)?$metal_type_key:set_value('metal_type_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_type_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>

              	<tr>
                  <td><span class="required">*</span> Price:</td>
                  <td><input type="text" name="metal_type_price" value="<?php echo (@$metal_type_price)?$metal_type_price:set_value('metal_type_price');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_type_price'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="metal_type_status">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$metal_type_status=='1' || @$_POST['metal_type_status']=='1')?'selected="selected"':'';?>>Disabled</option>
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
