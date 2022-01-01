<?php $k=$address_row;?>
<div id="tab-address-row-<?php echo $k;?>" class="vtabs-content" style="display: none;">

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
                        	<td>
                            	<input type="radio" name="customer_address_is_default" value="<?php echo $k?>" 
								<?php echo (!$this->is_post)?( isset($ar['customer_address_is_default']) && $ar['customer_address_is_default']==0?'checked="checked"': (($k==1)? 'checked="checked"':'' )):(@$_POST['customer_address_is_default']==$k?'checked="checked"':''); ?> onchange="return checkRadio(this);"/>
                            </td>
                        </tr>
                	</table>
            	</div>