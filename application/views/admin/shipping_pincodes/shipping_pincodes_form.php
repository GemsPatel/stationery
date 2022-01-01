
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
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/shippingPincodesForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Shipping Method:</td>
                <td>
                 <?php 
					
					 	$sql = "SELECT shipping_method_id ,shipping_method_name FROM shipping_method WHERE shipping_method_status=0";
					  	$ship_pinArr = getDropDownAry($sql,"shipping_method_id", "shipping_method_name", array('' => "Select Shipping Method"), false);
					 	$ship_pin_ids =(@$shipping_method_id)? $shipping_method_id: @$_POST['shipping_method_id']; 
						echo form_dropdown('shipping_method_id',@$ship_pinArr,$ship_pin_ids,'class=""');
					  ?>
                     <span class="error_msg"><?php echo (@$error)?form_error('shipping_method_id'):''; ?> </span> 
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Pincode:</td>
                <td>
                 <?php 
					
					 	$sql = "SELECT pincode_id ,pincode FROM pincode WHERE pincode_status=0";
					  	$pinArr = getDropDownAry($sql,"pincode_id", "pincode", array('' => "Select Pincode"), false);
					 	$pincode_ids =(@$pincode_id)? $pincode_id: @$_POST['pincode_id']; 
						echo form_dropdown('pincode_id',@$pinArr,$pincode_ids,'class=""');
					  ?>
                     <span class="error_msg"><?php echo (@$error)?form_error('pincode_id'):''; ?> </span> 
                </td>
              </tr>
              <tr>
                <td>&nbsp; &nbsp; City Name:</td>
                <td><input type="text" size="17" name="city_name" value="<?php echo (@$city_name)?$city_name:@$_POST['city_name'];?>">
				</td>
              </tr>
              <tr>
                <td>&nbsp; &nbsp; Service Type:</td>
                <td><input type="text" size="17" name="service_type" value="<?php echo (@$service_type)?$service_type:@$_POST['service_type'];?>">
				</td>
              </tr>
              <tr>
                <td>&nbsp; &nbsp; Service Type Code:</td>
                <td><input type="text" size="17" name="service_type_code" value="<?php echo (@$service_type_code)?$service_type_code:@$_POST['service_type_code'];?>">
				</td>
              </tr>
              <tr>
                <td>&nbsp; &nbsp; Cod Limit:</td>
                <td><input type="text" size="17" name="cod_limit" value="<?php echo (@$cod_limit)?$cod_limit:@$_POST['cod_limit'];?>">
                <span class="error_msg"><?php echo (@$error)?form_error('cod_limit'):''; ?> </span> 
				</td>
              </tr>
              <tr>
                <td>&nbsp; &nbsp; Prepaid Limit:</td>
                <td><input type="text" size="17" name="prepaid_limit" value="<?php echo (@$prepaid_limit)?$prepaid_limit:@$_POST['prepaid_limit'];?>">
                <span class="error_msg"><?php echo (@$error)?form_error('prepaid_limit'):''; ?> </span> 
				</td>
              </tr>	
              <tr>
              <td>&nbsp;&nbsp;  Status:</td>
              <td><select name="shipping_pincodes_status">
                  <option value="0" selected="selected">Enable</option>
                  <option value="1" <?php echo (@$shipping_pincodes_status=='1' || @$_POST['shipping_pincodes_status']=='1')?'selected="selected"':'';?>>Disable</option>
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



