<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/inventoryTypeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Inventory Type </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Name :</td>
                  <td><input type="text" name="it_name" value="<?php echo @$it_name;?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('it_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Key:</td>
                  <td><input type="text" name="it_key" size="75" value="<?php echo (@$it_key)?$it_key:set_value('it_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('it_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>  
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="it_status">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$it_status=='1')?'selected="selected"':'';?>>Disabled</option>
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
