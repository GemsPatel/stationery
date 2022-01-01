<div id="content">
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> Inventory Attribute</h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller).'?item_id='._en(@$this->cPrimaryId).'&m_id=';?>">Cancel</a></div>
    </div>
    <div class="content">
      <?php 
	  		$item_id = (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : '';
	  		$m_id = (@$this->cPrimaryIdM != '') ? _en(@$this->cPrimaryIdM) : '';
	  ?>
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/imsForm')?>">
      <input type="hidden" name="item_id" value="<?php echo @$item_id; ?>"  />
      <input type="hidden" name="m_id" value="<?php echo  @$m_id?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Inventory Attribute</legend>
			<table class="form">
              <tbody>
              
              <tr>
                <td><span class="required">*</span> Input Type:</td>
                <td>
                	<select name="ims_input_type">
	                    <option value="TXT" selected="selected">Textbox</option>
	                    <option value="RDO" <?php echo (@$ims_input_type=='RDO' || @$_POST['ims_input_type']=='RDO')?'selected="selected"':'';?>>Radio</option>
	                    <option value="CHK" <?php echo (@$ims_input_type=='CHK' || @$_POST['ims_input_type']=='CHK')?'selected="selected"':'';?>>Checkbox</option>
	                    <option value="SEL" <?php echo (@$ims_input_type=='SEL' || @$_POST['ims_input_type']=='SEL')?'selected="selected"':'';?>>Drop Down</option>
						<option value="">---Inventory Components---</option>
	                    <option value="JW_CS" <?php echo (@$ims_input_type=='JW_CS' || @$_POST['ims_input_type']=='JW_CS')?'selected="selected"':'';?>>Jewelry ==> Center Stone</option>
	                    <option value="JW_SS1" <?php echo (@$ims_input_type=='JW_SS1' || @$_POST['ims_input_type']=='JW_SS1')?'selected="selected"':'';?>>Jewelry ==> Side Stone1</option>
	                    <option value="JW_SS2" <?php echo (@$ims_input_type=='JW_SS2' || @$_POST['ims_input_type']=='JW_SS2')?'selected="selected"':'';?>>Jewelry ==> Side Stone2</option>
	                    <option value="JW_SSS" <?php echo (@$ims_input_type=='JW_SSS' || @$_POST['ims_input_type']=='JW_SSS')?'selected="selected"':'';?>>Jewelry ==> Side Stones</option>
	                    <option value="JW_MTL" <?php echo (@$ims_input_type=='JW_MTL' || @$_POST['ims_input_type']=='JW_MTL')?'selected="selected"':'';?>>Jewelry ==> Metal</option>
                    </select>
                
                <span class="error_msg"><?php echo (@$error)?form_error('ims_input_type'):''; ?></span>
                </td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> Name</td>
                <td><input type="text" size="50" maxlength="50" name="ims_tab_label" value="<?php echo (@$ims_tab_label)?$ims_tab_label:@$_POST['ims_tab_label'];?>"> 
              	</td>
              </tr>

              <tr>
                <td><span class="required">*</span> Heading Title</td>
                <td><input type="text" size="50" maxlength="50" name="ims_fieldset_label" value="<?php echo (@$ims_fieldset_label)?$ims_fieldset_label:@$_POST['ims_fieldset_label'];?>"> 
              	</td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> Input Label</td>
                <td><input type="text" size="50" maxlength="50" name="ims_input_label" value="<?php echo (@$ims_input_label)?$ims_input_label:@$_POST['ims_input_label'];?>"> 
              	</td>
              </tr>
              
              <tr>
                <td>Default Value</td>
                <td><input type="text" size="50" maxlength="50" name="ims_default_value" value="<?php echo (@$ims_default_value)?$ims_default_value:@$_POST['ims_default_value'];?>"> 
              	</td>
              </tr>
              
              <tr>
                <td> Input Validation</td>
                <td>
	                <select name="ims_input_validation">
						<option selected="selected" value="">None</option>
						<option value="DBL" <?php echo (@$ims_input_validation=='DBL' || @$_POST['ims_input_validation']=='DBL')?'selected="selected"':'';?>>Decimal Number</option>
						<option value="INT" <?php echo (@$ims_input_validation=='INT' || @$_POST['ims_input_validation']=='INT')?'selected="selected"':'';?>>Integer Number</option>
						<option value="EML" <?php echo (@$ims_input_validation=='EML' || @$_POST['ims_input_validation']=='EML')?'selected="selected"':'';?>>Email</option>
						<option value="URL" <?php echo (@$ims_input_validation=='URL' || @$_POST['ims_input_validation']=='URL')?'selected="selected"':'';?>>URL</option>
						<option value="ALP" <?php echo (@$ims_input_validation=='ALP' || @$_POST['ims_input_validation']=='ALP')?'selected="selected"':'';?>>Letters</option>
						<option value="ALN" <?php echo (@$ims_input_validation=='ALN' || @$_POST['ims_input_validation']=='ALN')?'selected="selected"':'';?>>Letters (a-z, A-Z) or Numbers (0-9)</option>
					</select>
					
	                <span class="error_msg"><?php echo (@$error)?form_error('ims_input_type'):''; ?></span>
                </td>
              </tr>
              
              <tr>
                <td>Is Use In Search Filter</td>
                <td>
                	<select name="ims_is_use_in_search_filter">
	                    <option value="1" selected="selected">Enable</option>
	                    <option value="0" <?php echo (@$ims_is_use_in_search_filter=='0' || @$_POST['ims_is_use_in_search_filter']=='0')?'selected="selected"':'';?>>Disable</option>
                    </select>
                </td>
              </tr>

              <tr>
                <td>Is Use In Compare</td>
                <td>
                	<select name="ims_is_use_in_compare">
	                    <option value="1" selected="selected">Enable</option>
	                    <option value="0" <?php echo (@$ims_is_use_in_compare=='0' || @$_POST['ims_is_use_in_compare']=='0')?'selected="selected"':'';?>>Disable</option>
                    </select>
                </td>
              </tr>
              
              <tr>
                <td>Sort Order</td>
                <td><input type="text" size="3" maxlength="11" name="ims_sort_order" value="<?php echo (@$ims_sort_order)?$ims_sort_order:@$_POST['ims_sort_order'];?>"> 
              	</td>
              </tr>

              <tr>
                <td>Status:</td>
                <td>
                	<select name="ims_status">
	                    <option value="0" selected="selected">Enable</option>
	                    <option value="1" <?php echo (@$ims_status=='1' || @$_POST['ims_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



