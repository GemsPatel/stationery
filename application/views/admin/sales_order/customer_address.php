	<?php
    	$res = getAddress($customer_address_id);executeQuery("SELECT c.*, p.pincode FROM customer_address c INNER JOIN pincode p
							 ON p.pincode_id=c.customer_address_zipcode WHERE customer_address_id=".$customer_address_id."");
		if(!empty($res)):
	//pr($res); die;
	?>
	<!--Customerr address -->
            <tr>
                <td><span class="required">*</span> First Name:</td>
                <td><input type="text" id="customer_address_firstname_<?php echo $type; ?>" name="customer_address_firstname_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['customer_address_firstname']:@$_POST['customer_address_firstname_'.$type]; ?>" />
                <span class="error_msg"><?php  echo (@$error)?form_error('customer_address_firstname_'.$type):''; ?></span>
                </td>
            <tr> 
            <tr>  
               <td><span class="required">*</span> Last Name:</td>
               <td><input type="text" id="customer_address_lastname_<?php echo $type; ?>" name="customer_address_lastname_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['customer_address_lastname']:@$_POST['customer_address_lastname_'.$type]; ?>" />
               <span class="error_msg"><?php echo (@$error)?form_error('customer_address_lastname_'.$type):''; ?></span>
               </td>
            </tr>
            <tr>
                <td>Phone No:</td>
                <td><input type="text" id="customer_address_phone_no_<?php echo $type; ?>" name="customer_address_phone_no_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['customer_address_phone_no']:@$_POST['customer_address_phone_no_'.$type]; ?>" /></td>
            </tr>
            <tr>
                <td><span class="required">*</span> Address:</td><br />
                <td><textarea id="customer_address_address_<?php echo $type; ?>" name="customer_address_address_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> rows="3" cols="45" ><?php echo (!$this->is_post)?$res['customer_address_address']:@$_POST['customer_address_address_'.$type]; ?></textarea>
                <span class="error_msg"><?php echo (@$error)?form_error('customer_address_address_'.$type):''; ?></span>
                </td>
            </tr>
             <tr>
                <?php
                    $countryid = (!$this->is_post)?$res['country_id']:@$_POST['country_id_'.$type];
                    $country = loadCountryDropdown($countryid, ' id="country_id_'.$type.'" onchange="getState(this.value,\'customer_address_state_id_'.$type.'\')" '.(($type=='bill')?'onblur="copyAddress(this)"':'')); 
                ?>    
                <td><span class="required">*</span> Country:</td>
                <td><?php 
                        	echo str_replace('name="country"','name="country_id_'.$type.'"', $country); 
                    ?>
                    <span class="error_msg"><?php echo (@$error)?form_error('country_id_'.$type):''; ?></span>
                </td>
            </tr>
            <tr>
                <td><span class="required">*</span> Region / State:</td>
                <?php
                    $state_id = (!$this->is_post)?$res['state_id']:@$_POST['customer_address_state_id_'.$type];
                ?>
                <td><?php $state=loadStateDropdown('customer_address_state_id_'.$type,$countryid,$state_id, ' id="customer_address_state_id_'.$type.'" onchange="loadCity(this.value,\'customer_address_city_'.$type.'\',\'admin/customer/loadCityAjax\')" '.(($type=='bill')?'onblur="copyAddress(this)" ':''));
					 echo $state;
				 	?>
                <span class="error_msg"><?php echo (@$error)?form_error('customer_address_state_id_'.$type):''; ?></span>
                </td>
            </tr>
            <tr>
                <td><span class="required">*</span> City:</td>
               	<td>
                	<input type="text" id="customer_address_city_<?php echo $type; ?>" name="customer_address_city_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['cityname']:@$_POST['customer_address_city_'.$type]; ?>" />
            	  	<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_city_'.$type):''; ?> </span>
             </td>
             </tr>
            <tr>
                <td><span class="required">*</span> Landmark Area:</td>
                <td><input type="text" id="customer_address_landmark_area_<?php echo $type; ?>" name="customer_address_landmark_area_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['customer_address_landmark_area']:@$_POST['customer_address_landmark_area'.$type]; ?>" />
                <span class="error_msg"><?php echo (@$error)?form_error('customer_address_landmark_area_'.$type):''; ?></span>
                </td>
            </tr>
            <tr>
                <td><span class="required">*</span> Postcode:</td>
                <td><input type="text" id="pincode_<?php echo $type; ?>" name="pincode_<?php echo $type; ?>" <?php echo ($type=='bill')?'onblur="copyAddress(this)"':''; ?> value="<?php echo (!$this->is_post)?$res['pincode']:@$_POST['pincode_'.$type]; ?>" />
                <span class="error_msg"><?php echo (@$error)?form_error('pincode_'.$type):''; ?></span>
                </td>
            </tr>
           
	<?php
    	endif;
	?>
