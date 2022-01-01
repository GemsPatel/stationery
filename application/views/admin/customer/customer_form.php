<div id="content">

  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
  		 <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
   <div class="content">
      <!--<div class="htabs" id="htabs">
      	<a href="#tab-general" style="display: inline;" class="selected">General</a>
    	<a href="#tab-ip" style="display: inline;">IP Addresses</a>
      </div>-->
      <?php $par = (isset($_GET['mode']) && $_GET['mode']=='order')?'?mode=order':'';?>
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/customerForm'.$par)?>">
	  <?php
	  	$addFieldArr = (isset($_POST['customer_address_firstname'])) ? $_POST['customer_address_firstname']:(isset($cust_add) ? $cust_add:''); 
	  ?>		
      	<input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
      	<div id="tab-general" style="display: block;">
        	<div class="vtabs" id="vtabs"><a href="#tab-customer" class="selected">General</a>
        	<?php
				if(is_array($addFieldArr) && sizeof($addFieldArr)>0):
					foreach($addFieldArr as $k=>$ar):
						$key = $k + 1;
			?>
                <a href="#tab-address-<?php echo $key ?>" id="address-<?php echo $key ?>">Address <?php echo $key ?> 
                    &nbsp;<img src="images/admin/delete.png" alt="" 
                    onclick="$('#vtabs a:first').trigger('click');
                    $('#address-<?php echo $key ?>').remove();
                    $('#tab-address-<?php echo $key ?>').remove();
					address_row = address_row - 1;
                    return false;" /></a>
			<?php
					endforeach;
				endif;
			?>
	          	<span id="address-add">Add Address&nbsp;<img onclick="add_address();" alt="" src="<?php echo asset_url('images/admin/add.png')?>"></span>
            </div>

          <div class="vtabs-content" id="tab-customer" style="display: block;">
            <table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> First Name:</td>
                <td><input type="text"  name="customer_firstname" value="<?php echo (@$customer_firstname)?$customer_firstname:@$_POST['customer_firstname']; ?>">
                <span class="error_msg"><?php echo (@$error)?form_error('customer_firstname'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Last Name:</td>
                <td><input type="text"  name="customer_lastname" value="<?php echo (@$customer_lastname)?$customer_lastname:@$_POST['customer_lastname']; ?>">
                <span class="error_msg"><?php echo (@$error)?form_error('customer_lastname'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td><span class="required">*</span> E-Mail:</td>
               <td><input type="text"  name="customer_emailid" value="<?php echo (@$customer_emailid)?$customer_emailid:@$_POST['customer_emailid']; ?>">
                <span class="error_msg"><?php echo (@$error)?form_error('customer_emailid'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Telephone:</td>
                <td><input type="text"  name="customer_phoneno" value="<?php echo (@$customer_phoneno)?$customer_phoneno:@$_POST['customer_phoneno']; ?>">
                <span class="error_msg"><?php echo (@$error)?form_error('customer_phoneno'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td>Fax:</td>
                <td><input type="text"  name="customer_fax" value="<?php echo (@$customer_fax)?$customer_fax:@$_POST['customer_fax']; ?>"></td>
              </tr>
              <tr>
                <td>Password:</td>
                <td><input type="password"  name="customer_password"/>
                <span class="error_msg"><?php echo (@$error)?form_error('customer_password'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td>Confirm:</td>
                <td><input type="password"  name="customer_confirm_password" />
                <span class="error_msg"><?php echo (@$error)?form_error('customer_confirm_password'):''; ?></span>
                  </td>
              </tr>
              <tr>
                <td>Newsletter:</td>
                <td>
                     <select name="customer_newsletter">
                         <option value="1" selected="selected">Disable</option>
                       	 <option value="0" <?php echo (@$customer_newsletter=='0' || @$_POST['customer_newsletter']=='0')?'selected="selected"':'';?>>Enable</option>
                     </select>
                  </td>
              </tr>
              <tr>
                <td>Customer Group:</td>
                <td>
                <?php 
				$customer_group_id = (@$customer_group_id) ? $customer_group_id : @$_POST['customer_group_id'];
				$sql = "SELECT customer_group_id, customer_group_name FROM customer_group WHERE customer_group_status=0";
				$diamond_shapeArr = getDropDownAry($sql,"customer_group_id", "customer_group_name", '', false);
				
				echo form_dropdown('customer_group_id',@$diamond_shapeArr,@$customer_group_id,'style="width: 82px;" ');
				?>
                </td>
              </tr>
              <tr>
                <td>Status:</td>
                <td>
                     <select name="customer_status">
                         <option value="0" selected="selected">Enabled</option>
                       	 <option value="1" <?php echo (@$customer_status=='1' || @$_POST['customer_status']=='1')?'selected="customer_status"':'';?>>Disabled</option>
                     </select>
                  </td>
              </tr>
              <tr>
                <td>Approve:</td>
                <td>
                     <select name="customer_approved">
                         <option value="0" selected="selected">New Registration</option>
                       	 <option value="1" <?php echo (@$customer_approved=='1' || @$_POST['customer_approved']=='1')?'selected="selected"':'';?>>Approved</option>
                     </select>
                  </td>
              </tr>
            </tbody></table>
          </div>
          
          <!--address tab generation-->
		  	<?php
				$country = "";
		  		if(is_array($addFieldArr) && sizeof($addFieldArr)>0):
		  			foreach($addFieldArr as $k=>$ar):
		 	?> 
           		<div id="tab-address-<?php echo $k+1;?>" class="vtabs-content" style="display: none;">
		      		<input type="hidden" name="item_idA[]" id="item_idA_<?php echo $k; ?>" value="<?php echo (!$this->is_post) ? _en(@$ar['customer_address_id']) : _en(@$this->cPrimaryIdA[$k]); ?>"  />
		      		<input type="hidden" name="radio_A[]" class="radioA" id="radio_A_<?php echo $k; ?>" value="<?php echo (!$this->is_post)?@$ar['customer_address_is_default']:@$_POST['radio_A'][$k]; ?>"  />
                    <table class="form">
                   		<tr>
                        	<td><span class="required">*</span> First Name:</td>
                            <td><input type="text" name="customer_address_firstname[]" value="<?php echo ((!$this->is_post)?@$ar['customer_address_firstname']:@$_POST['customer_address_firstname'][0]); ?>" />
                            <span class="error_msg"><?php  echo (@$error)?form_error('customer_address_firstname[]'):''; ?></span>
                            </td>
                        <tr> 
                        <tr>  
                           <td><span class="required">*</span> Last Name:</td>
                           <td><input type="text" name="customer_address_lastname[]" value="<?php echo (!$this->is_post)?@$ar['customer_address_lastname']:@$_POST['customer_address_lastname'][$k]; ?>" />
                           <span class="error_msg"><?php echo (@$error)?form_error('customer_address_lastname[]'):''; ?></span>
                           </td>
                        </tr>
                        <tr>
                        	<td>Company:</td>
                        	<td><input type="text" name="customer_address_company[]" value="<?php echo (!$this->is_post)?@$ar['customer_address_company']:@$_POST['customer_address_company'][$k]; ?>" /></td>
                        </tr>
                        <tr>
                        	<td><span class="required">*</span> Address:</td><br />
							<td><textarea name="customer_address_address[]" rows="5" cols="45" ><?php echo (!$this->is_post)?@$ar['customer_address_address']:@$_POST['customer_address_address'][$k]; ?></textarea>
                            <span class="error_msg"><?php echo (@$error)?form_error('customer_address_address[]'):''; ?></span>
                            </td>
                        </tr>
                       
                        <tr>
                        	<td>Country:</td>
                        	<td>
							<?php $country=loadCountryDropdown(((@$ar['country_id'])?$ar['country_id']:@$_POST['country_id_'.$k]),'onchange="getState(this.value,\'state_id_'.$k.'\')"  ', 'country_id_'.$k);
								echo $country;
							?>
                            </td>
                        </tr>
                        <tr>
                        	<td><span class="required">*</span> Region / State:</td>
                            <td><?php $state=loadStateDropdown('customer_address_state_id_'.$k,((@$ar['country_id'])?$ar['country_id']:@$_POST['country_id_'.$k]),((@$ar['state_id'])?$ar['state_id']:@$_POST['customer_address_state_id'.$k]),' onchange="loadCity(this.value,\'customer_address_city'.$k.'\',\'admin/customer/loadCityAjax\')" id="customer_address_state_id_'.$k.'"');
								echo $state;
							 ?>                                      
                            </td>            
                        </tr>
                        <tr>
                        	<td><span class="required">*</span> City:</td>
                            <td> <input type="text" name="customer_address_city[]" value="<?php echo (!$this->is_post)?@$ar['customer_address_city']:@$_POST['customer_address_city'][$k]; ?>" />
                            	<span class="error_msg"><?php echo (@$error)?form_error('customer_address_city[]'):''; ?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td><span class="required">*</span> Landmark:</td>
                            <td><input type="text" name="customer_address_landmark_area[]" value="<?php echo (!$this->is_post)?@$ar['customer_address_landmark_area']:@$_POST['customer_address_landmark_area'][$k]; ?>" />
                            <span class="error_msg"><?php echo (@$error)?form_error('customer_address_landmark_area[]'):''; ?></span>
                            </td>
                        </tr>
                        <tr>
                        	<td><span class="required">*</span> Postcode:</td>
                            <td><input type="text" name="pincode[]" value="<?php echo (!$this->is_post)?@$ar['pincode']:@$_POST['pincode'][$k]; ?>" />
                            <span class="error_msg"><?php echo (@$error)?form_error('pincode[]'):''; ?></span>
                            </td>
                        </tr>                        
                        
                        <tr>
                        	<td>Default Address:</td>
                        	<td><input type="radio" class="customer_address_is_default" name="customer_address_is_default" value="<?php echo $k; ?>" <?php echo (!$this->is_post)?(@$ar['customer_address_is_default']==0?'checked="checked"':''):(@$_POST['customer_address_is_default']==$k?'checked="checked"':''); ?> onchange="return checkRadio(this);"/></td>
                        </tr>
                	</table>
            	</div>
					
			<?php				
					endforeach;
				else:
								
				endif;
				
		   	?>
         </div>
         
         <!--<div id="tab-ip" style="display: none;">
          <table class="list">
            <thead>
              <tr>
                <td class="left">IP</td>
                <td class="right">Total Accounts</td>
                <td class="left">Date Added</td>
                <td class="right">Action</td>
              </tr>
            </thead>
            <tbody>
             <tr>
                <td colspan="4" class="center">No results!</td>
             </tr>
            </tbody>
          </table>
         </div>-->
        
      </form>
    </div>
    
    </div>
  
  
</div>

<script type="text/javascript">
<!--
	$('#tabs a').tabs();
	$('.htabs a').tabs();
	$('.vtabs a').tabs();
//-->
</script>

<script type="text/javascript"><!--
<!--
var address_row = <?php echo json_encode((is_array($addFieldArr) && sizeof($addFieldArr)>0)? (sizeof($addFieldArr)+1):'1'); ?>;

function checkRadio(obj)
{
	$('.radioA').each(function(){
		$(this).val('');
	});
	$('#radio_A_'+$(obj).val()).val('0');
}

/**
 * @author Cloudwebs Kahar
 * @abstract this function will add address in customer address 
*/
function add_address()
{
	var loc = (base_url+'admin/'+lcFirst(controller))+'/addAddress';
	form_data = {address_row : address_row};
	$.post(loc, form_data, function (data) {

		$('#tab-general').append(data);
		$('#address-add').before('<a href="#tab-address-row-' + address_row + '" id="address-' + address_row + '">Address ' + address_row + '&nbsp;<img src="images/admin/delete.png" alt="" onclick="$(\'#vtabs a:first\').trigger(\'click\'); $(\'#address-' + address_row + '\').remove(); $(\'#tab-address-' + address_row + '\').remove(); address_row = address_row - 1; return false;" /></a>');
		$('.vtabs a').tabs();
		$('#address-'+address_row).trigger('click');
		address_row++;
	});
	
}
//-->
</script>