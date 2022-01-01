<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/metalPurityForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Metal Purity </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Purity:</td>
                  <td><input type="text" name="metal_purity_name" value="<?php echo (@$metal_purity_name)?$metal_purity_name:@$_POST['metal_purity_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_purity_name'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text"  name="metal_purity_key"  size="75" value="<?php echo (@$metal_purity_key)?$metal_purity_key:set_value('metal_purity_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('metal_purity_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
                <tr>
              <td>&nbsp;&nbsp;Sort Order:</td>
              <td><input type="text" size="3" name="metal_purity_sort_order" value="<?php echo (@$metal_purity_sort_order)?$metal_purity_sort_order:@$_POST['metal_purity_sort_order'];?>"> 
              </td>
            </tr>
                <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="metal_purity_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$metal_purity_status=='1' || @$_POST['metal_purity_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
