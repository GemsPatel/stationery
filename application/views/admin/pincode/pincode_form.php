<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/pincodeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Pincode </legend>
			<table class="form">
              <tbody>
              <tr>
		<?php
			$countryid = (@$country_id)?$country_id:@$_POST['country_id'];
			$country = loadCountryDropdown($countryid, ' id="country_id" onchange="getState(this.value,\'state_id\')" '); 
		?>    
       			 <td><span class="required">*</span> Country:</td>
       			<td><?php 
			    echo str_replace('name="country"','name="country_id"', $country); 
            ?>
          		</td>
   			</tr>
             <tr>
       			<td><span class="required">*</span> Region / State:</td>
        <?php
			$state_id = (@$state_id)?$state_id:@$_POST['state_id'];
		?>
        <td><?php echo loadStateDropdown('state_id',$countryid,$state_id, '') ?>
        <span class="error_msg"><?php echo (@$error)?form_error('state_id'):''; ?></span>
       			 </td>
   				</tr>
              	<tr>
                  <td><span class="required">*</span> City:</td>
                  <td><input type="text" name="cityname" value="<?php echo (@$cityname)?$cityname:set_value('cityname');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('cityname'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                  <td><span class="required">*</span> Area Name:</td>
                  <td><input type="text" name="areaname" value="<?php echo (@$areaname)?$areaname:set_value('areaname');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('areaname'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Pincode:</td>
                  <td><input type="text" name="pincode" value="<?php echo (@$pincode)?$pincode:set_value('pincode');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('pincode'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="pincode_status">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$pincode_status=='1' || @$_POST['pincode_status']=='1')?'selected="selected"':'';?>>Disabled</option>
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
