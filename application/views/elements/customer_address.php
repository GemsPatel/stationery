<?php	
	$is_add_available = false; 
	$res;
	if(isset($customer_address_id) && (int)$customer_address_id!=0)
	{
		$res = executeQuery("SELECT c.customer_address_firstname,c.customer_address_lastname,c.customer_address_address,c.customer_address_phone_no,c.customer_address_zipcode, 
							 c.customer_address_landmark_area, p.pincode,p.state_id,p.cityname,p.areaname,s.country_id 
							 FROM customer_address c 
							 INNER JOIN pincode p ON p.pincode_id=c.customer_address_zipcode 
							 INNER JOIN state s ON s.state_id=p.state_id  
							 WHERE customer_address_id=".$customer_address_id." ");
							 
		if(!empty($res))
			$is_add_available = true;
	}
?>

<input type="hidden" name="customer_address_id_<?php echo $class ?>" value="<?php echo ($is_add_available)?$customer_address_id:''; ?>" />
<input type="hidden" name="edit_<?php echo $class ?>" id="edit_<?php echo $class ?>" value="<?php echo (($is_read_only==true)?0:1) ?>" />

<div class="checkout_form_input">
	<label><?php echo getLangMsg("nm");?><span class="color_red">*</span></label>
	<input name="customer_address_firstname_<?php echo $class; ?>" type="text" <?php echo (($is_read_only==true)?'disabled="disabled"':'');?> placeholder="<?php echo getLangMsg("f_name");?>" class="<?php echo $class ?>" value="<?php echo ($is_add_available)?$res[0]['customer_address_firstname']:@$_POST['customer_address_firstname_'.$class]; ?>">
    <span class="input-notification error png_bg" for="customer_address_firstname_<?php echo $class; ?>" id="login-error"></span>
</div>

<div class="checkout_form_input">
	<label><?php echo getLangMsg("l_name");?></label>
	<input name="customer_address_lastname_<?php echo $class; ?>" type="text" placeholder="<?php echo getLangMsg("l_name");?>" <?php echo (($is_read_only==true)?'disabled="disabled"':'') ?> class="<?php echo $class ?>" value="<?php echo ($is_add_available)?$res[0]['customer_address_lastname']:@$_POST['customer_address_lastname_'.$class]; ?>">                            
	<span class="input-notification error png_bg" for="customer_address_lastname_<?php echo $class; ?>" id="login-error"></span>
</div>

<div class="checkout_form_input checkout_form_textarea">
	<label><?php echo getLangMsg("s_add");?><span class="color_red">*</span></label>
    <input name="customer_address_address_<?php echo $class; ?>" type="text" class="<?php echo $class ?>" <?php echo (($is_read_only==true)?'disabled="disabled"':'') ?> value="<?php echo ($is_add_available)?$res[0]['customer_address_address']:@$_POST['customer_address_address_'.$class]; ?>">
	<span class="input-notification error png_bg" for="customer_address_address_<?php echo $class; ?>" id="login-error"></span>
</div>

<div class="checkout_form_input hide">
	<label><?php echo getLangMsg("country");?> <span class="color_red">*</span></label>
	<!-- Chage CountryDropdown list getDefaultCountryID() from ($is_add_available)?$res[0]['country_id']:@$_POST['country_'.$class] -->
	<?php echo loadCountryDropdown( getDefaultCountryID(),'onchange="getState(this.value,\'state_id_'.$class.'\')" class="'.$class.' dd custom wd100" '.(($is_read_only==true)?'disabled="disabled"':'') .'', 'country_'.$class.'');?>
	<span class="input-notification error png_bg" for="country_<?php echo $class; ?>" id="login-error"></span>
</div>

<div class="checkout_form_input hide">
	<label><?php echo getLangMsg("state");?> </label>
	<!-- Chage StateDropdown list getDefaultStateID() from 'state_id_'.$class -->
	<?php echo loadStateDropdown( 'state_id_'.$class, getDefaultCountryID(), getDefaultStateID(),' class="'.$class.' dd custom wd100" '.(($is_read_only==true)?'disabled="disabled"':'') .' onchange="loadCity(this.value,\'address_city_'.$class.'\',\'checkout/loadCityAjax\')" id="state_id_'.$class.'" '); ?>
	<span class="input-notification error png_bg" for="state_id_<?php echo $class; ?>" id="login-error"></span>
</div>

<div class="checkout_form_input">
	<label><?php echo getLangMsg("city");?><span class="color_red">*</span></label>
	<input name="address_city_<?php echo $class;?>" type="text" class="" value="<?php echo ($is_add_available)?$res[0]['cityname']:@$_POST['address_city_'.$class];?>" <?php echo (($is_read_only==true)?'disabled="disabled"':'') ?>/><!-- Surat -->
	<span class="input-notification error png_bg" for="address_city_<?php echo $class ?>" id="login-error"></span>
</div>

<div class="checkout_form_input">
	<label><?php echo getLangMsg("l_area");?> <span class="color_red">*</span></label>
	<input name="customer_address_landmark_area_<?php echo $class;?>" type="text" class="" placeholder="<?php echo getLangMsg("l_area");?>" value="<?php echo ($is_add_available)?$res[0]['customer_address_landmark_area']:@$_POST['customer_address_landmark_area_'.$class];?>" <?php echo (($is_read_only==true)?'disabled="disabled"':'') ?>/>
	<span class="input-notification error png_bg" for="customer_address_landmark_area_<?php echo $class ?>" id="login-error"></span>
</div>

<div class="checkout_form_input">
	<label><?php echo getLangMsg("pin");?> <span class="color_red">*</span></label>
	<input name="pincode_<?php echo $class;?>" type="text" class="" value="<?php echo ($is_add_available)?$res[0]['pincode']:@$_POST['pincode_'.$class];?>"  <?php echo (($is_read_only==true)?'disabled="disabled"':'') ?> />
	<span class="input-notification error png_bg" for="pincode_<?php echo $class ?>" id="login-error"></span>
</div>

<div class="checkout_form_input">
	<label><?php echo getLangMsg("phone")." ".getLangMsg("no").".";?><span class="color_red">*</span></label>
	<input name="customer_address_phone_no_<?php echo $class ?>" type="text" <?php echo (($is_read_only==true)?'disabled="disabled"':'');?> class="<?php echo $class ?>" value="<?php echo (($is_add_available)?$res[0]['customer_address_phone_no']:@$_POST['customer_address_phone_no_'.$class]);?>"/>
	<span class="input-notification error png_bg" for="customer_address_phone_no_<?php echo $class ?>" id="login-error"></span>
</div>


<?php
	if(!$is_read_only && $class=='shipp' && isset($customer_address_id) && (int)$customer_address_id!=0):
?>
		<div class="clear"></div>
		<a class="btn active pull-right checkout_block_btn" href="javascript:void(0);" 
		   onclick="editAddress('<?php echo $class;?>','checkout_editadd',this)"><?php echo getLangMsg("save");?></a>
<?php
	endif;
?>

<div class="clear"></div>

<div class="checkout_form_note"><?php echo getLangMsg("a_fill");?>(<span class="color_red">*</span>)<?php echo getLangMsg("req");?></div>