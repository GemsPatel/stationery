<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/diamondTypeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Diamond Type</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Type:</td>
                  <td><input type="text" name="diamond_type_name" value="<?php echo (@$diamond_type_name)?$diamond_type_name:set_value('diamond_type_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('diamond_type_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="diamond_type_key" size="75" value="<?php echo (@$diamond_type_key)?$diamond_type_key:set_value('diamond_type_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('diamond_type_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
                  <td>Sort Order:</td>
                  <td><input type="text" name="dimaond_type_sort_order" value="<?php echo (@$dimaond_type_sort_order)?$dimaond_type_sort_order:set_value('dimaond_type_sort_order');?>" />
                  </td>
                </tr>
                <tr>
                  <td>Status:</td>
                  <td>
                     <select name="diamond_type_status">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$diamond_type_status=='1' || @$_POST['diamond_type_status']=='1')?'selected="selected"':'';?>>Disabled</option>
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
