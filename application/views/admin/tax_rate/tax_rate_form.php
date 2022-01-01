<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/taxRateForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Tax Rate </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Tax Name :</td>
                  <td><input type="text" name="tax_rate_name" value="<?php echo (@$tax_rate_name)?$tax_rate_name:set_value('tax_rate_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('tax_rate_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td> &nbsp;&nbsp; Type :</td>
                  <td>
                     <select name="tax_rate_type">
                         <option value="Fix" selected="selected">Fix Amount</option>
                         <option value="Percent" <?php echo (@$tax_rate_type=='Percent' || @$_POST['tax_rate_type']=='Percent')?'selected="selected"':'';?>>Percentage</option>
                     </select>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Tax Rate :</td>
                  <td><input type="text" name="tax_rate_rate" value="<?php echo (@$tax_rate_rate)?$tax_rate_rate:set_value('tax_rate_rate');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('tax_rate_rate'):''; ?> </span>
                  </td>
                </tr>
                
                <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="tax_rate_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$tax_rate_status=='1' || @$_POST['tax_rate_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
