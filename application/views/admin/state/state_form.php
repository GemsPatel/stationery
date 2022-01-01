<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/stateForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>State </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> State Name:</td>
                  <td><input type="text" name="state_name" value="<?php echo (@$state_name)?$state_name:@$_POST['state_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('state_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text"  name="state_key"  size="75" value="<?php echo (@$state_key)?$state_key:set_value('state_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('state_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              	<tr>
               
                      <td><span class="required">*</span> Country:</td>
                      <td>
                      <?php 
					// echo $country_id;
					 	$sql = "SELECT country_id, country_name FROM country WHERE country_status=0";
					  	$country_statusArr = getDropDownAry($sql,"country_id", "country_name", array('' => "Select Country"), false);
					 	$country_ids =(@$country_id)? $country_id: @$_POST['country_id']; 
						echo form_dropdown('country_id',@$country_statusArr,$country_ids,'class=""');
					  ?>
                      <span class="error_msg"><?php echo (@$error)?form_error('country_id'):''; ?></span>
                      </td>
               </tr>
               <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="state_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$state_status=='1' || @$_POST['state_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
