<div class="main-container col2-left-layout">
	<div class="main">
		<div class="container">
			<div class="row">					
				<?php $this->load->view('account/leftbar_box');?>						
				<div class="col-lg-9 col-md-9 col-main">
					<div class="my-account">
						<div class="page-title">
							<h1>Add New Address</h1>
						</div>
						<form action="<?php echo site_url('account/save-address');?>" method="post" enctype="multipart/form-data" id="form-validate">
							<input type="hidden" name="customer_address_id" value="<?php echo (@$customer_address_id)?_en($customer_address_id):@$_POST['customer_address_id']; ?>" />
							
							<?php
							if($mode=='add')
								$customer_address_id=0;
							?>
								
							<div class="fieldset">
								<h2 class="legend">Contact Information</h2>
									<ul class="form-list">
										<li class="fields">
											<div class="customer-name-middlename row">
												<div class="field name-firstname col-md-6 col-sm-6 col-xs-6">
													<label for="firstname" class="required"><em>*</em>First Name</label>
													<div class="input-box">
														<input type="text" id="firstname" name="customer_address_firstname" value="<?php echo (@$customer_address_firstname)?$customer_address_firstname:@$_POST['customer_address_firstname']; ?>" title="First Name" class="input-text required-entry">
														 <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_firstname'):''; ?> </span>
													</div>
												</div>
												<div class="field name-lastname col-md-6 col-sm-6 col-xs-6">
													<label for="lastname" class="required"><em>*</em>Last Name</label>
													<div class="input-box">
														<input type="text" id="lastname" name="customer_address_lastname" value="<?php echo (@$customer_address_lastname)?$customer_address_lastname:@$_POST['customer_address_lastname']; ?>" title="Last Name" class="input-text required-entry">
													</div>
												</div>
											</div>
										</li>
										<li class="fields">
											<div class="row">
												<div class="col-lg-6 col-md-6 field">
													<label for="company">Company</label>
													<div class="input-box">
														<input type="text" name="customer_address_company" id="company" title="Company" value="<?php echo (@$customer_address_company)?$customer_address_company:@$_POST['customer_address_company']; ?>" class="input-text ">
													</div>
												</div>
												<div class="col-lg-6 col-md-6 field">
													<label for="telephone" class="required"><em>*</em>Telephone</label>
													<div class="input-box">
														<input type="text" name="customer_address_phone_no" class="input-text required-entry" id="telephone" value="<?php echo ((@$customer_address_phone_no)?$customer_address_phone_no:@$_POST['customer_address_phone_no']);?>" placeholder="<?php echo getLangMsg("phone")." ".getLangMsg("no")."*";?>" />
                                    					<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_phone_no'):''; ?> </span>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
								<div class="fieldset">
									<h2 class="legend">Address</h2>
									<ul class="form-list">
										<li class="wide">
											<label for="street_1" class="required"><em>*</em>Street Address</label>
											<div class="input-box">
												<input type="text" name="customer_address_address" value="<?php echo ( (@$customer_address_address ) ?  $customer_address_address : @$_POST['customer_address_address']);?>" title="Street Address" id="street_1" class="input-text  required-entry">
												<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_address'):''; ?> </span>
											</div>
										</li>
										<li class="fields">
											<div class="row">
												<div class="col-lg-6 col-md-6 field">
													<label for="country_id" class="required"><em>*</em>Country</label>
													<div class="input-box">
														<?php echo loadCountryDropdown(((@$country_id)?$country_id:@$_POST['country_id']),'onchange="getState(this.value,\'state_id\')" class="validate-select" ', 'country_id');?>
														<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('country_id'):''; ?> </span>					
													</div>
													
												</div>
												<div class="col-lg-6 col-md-6 field">
													<label for="state_id" class="required"><em>*</em>State/Province</label>
													<div class="input-box">
														<?php echo loadStateDropdown('state_id',((@$country_id)?$country_id:@$_POST['country_id']),((@$state_id)?$state_id:@$_POST['state_id']),' id="state_id" class="validate-select required-entry" '); ?>
                                    					<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('state_id'):''; ?> </span>
													</div>
												</div>
											</div>
										</li>
										<li class="fields">
											<div class="row">
												<div class="col-lg-6 col-md-6 field">
													<label for="city" class="required"><em>*</em>City</label>
													<div class="input-box">
														<input type="text" name="address_city" id="city" class="input-text  required-entry" value="<?php echo ((@$address_city)?$address_city:@$_POST['address_city']); ?>" placeholder="<?php echo getLangMsg("city")."*";?>" />
                                    					<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('address_city'):''; ?> </span>
													</div>
												</div>
												<div class="col-lg-6 col-md-6 field">
													<label for="zip" class="required"><em>*</em>Zip/Postal Code</label>
													<div class="input-box">
														<input type="text" name="pincode" id="zip" class="input-text validate-zip-international  required-entry" value="<?php echo ((@$pincode)?$pincode:@$_POST['pincode']); ?>" placeholder="<?php echo  getLangMsg("pin")."*";?>" />
                                    					<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('pincode'):''; ?> </span>
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
								<div class="buttons-set">
									<p class="back-link"><a href=""><small>« </small>Back</a></p>
									<button id="btn_address" type="submit" title="Save Address" class="button"><span><span>Save Address</span></span></button>
									<span id="address_loading_img" class="hide"><img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
								</div>
							</form>
							<br>
					</div>						
				</div>					
			</div>
		</div>
	</div>
</div>