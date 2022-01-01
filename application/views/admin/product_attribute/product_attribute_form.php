<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/productAttributeForm')?>">
      
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              
              <tr>
                <td><span class="required">*</span> Inventory Attribute:</td>
                <td>
                	<?php
                		$setval =(@$inventory_master_specifier_id)? $inventory_master_specifier_id:@$_POST['inventory_master_specifier_id'];
						$manArr = getDropDownAry( inventroyAttributeQuery(),"inventory_master_specifier_id", "ims_tab_label", array('' => "Select Attributes"), false);
						echo form_dropdown('inventory_master_specifier_id',$manArr, $setval, ' style="width:300px;" ');
					?>
                	
				<span class="error_msg"><?php echo (@$error)?form_error('inventory_master_specifier_id'):''; ?></span>
                </td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> Attribute Value:</td>
                <td><input type="text" size="70" maxlength="200" name="pa_value" id="pa_value" value="<?php echo (@$_POST['pa_value'])? $_POST['pa_value']: @$pa_value; ?>" >
				<span class="error_msg"><?php echo (@$error)?form_error('pa_value'):''; ?></span>
                </td>
              </tr>
              
              <?php
              		if( MANUFACTURER_ID == 7 ):
              ?>
		              <tr>
		                <td>Applicable Value (Optional):</td>
		                <td><input type="text" size="70" maxlength="200" name=pa_real_value id="pa_real_value" value="<?php echo (@$_POST['pa_real_value'])? $_POST['pa_real_value']: @$pa_real_value; ?>" ><?php echo"(Only Accept Numeric Value.)"?>
						<span class="error_msg"><?php echo (@$error)?form_error('pa_real_value'):''; ?></span>
		                </td>
		              </tr>
			  <?php
			  		endif;
			  ?>	
		              
		              
              <tr>
	              <td>Sort Order:</td>
	              <td><input type="text" size="3" name="pa_sort_order" value="<?php echo (@$pa_sort_order)?$pa_sort_order:@$_POST['pa_sort_order'];?>"> 
	              </td>
              </tr>
              
              <tr>
	              <td>Status:</td>
	              <td>
	              	<select name="pa_status">
	                	<option value="0" selected="selected">Enable</option>
	                    <option value="1" <?php echo (@$pa_status=='1' || @$_POST['pa_status']=='1')?'selected="selected"':'';?>>Disable</option>
	                </select>
	              </td>
              </tr>
              
              
            </tbody></table>
            
        </fieldset>
        </div>
        
      </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
//-->
</script>



