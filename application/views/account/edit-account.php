<div class="main-container col2-left-layout">
	<div class="main">
		<div class="container">
			<div class="row">					
				<?php $this->load->view('account/leftbar_box');?>						
				<div class="col-lg-9 col-md-9 col-main">
					<div class="my-account">
						<div class="page-title">
							<h1>Edit Account Information</h1>
						</div>
						<form enctype="multipart/form-data" method="post" action="" id="form-validate" autocomplete="off">
						<div class="row">
							<div class="col-lg-6 col-md-6">
								<div class="fieldset fix-min-height">
									<h2 class="legend">Account Information</h2>
									<ul class="form-list">
										<li class="fields">
											<div class="customer-name-middlename row">
												<div class="field name-firstname col-md-6 col-sm-6 col-xs-6">
													<label for="firstname" class="required"><em>*</em>First Name</label>
													<div class="input-box">
														<input type="text" id="firstname" name="customer_firstname" value="<?php echo $customer_firstname;?>" title="First Name" maxlength="255" class="input-text required-entry">
													</div>
												</div>

												<div class="field name-lastname col-md-6 col-sm-6 col-xs-6">
													<label for="lastname" class="required"><em>*</em>Last Name</label>
													<div class="input-box">
														<input type="text" id="lastname" name="customer_lastname" value="<?php echo $customer_lastname;?>" title="Last Name" maxlength="255" class="input-text required-entry">
													</div>
												</div>
											</div>
										</li>
										
										<li class="fields">
											<div class="customer-name-middlename row">
												<div class="field name-lastname col-md-6 col-sm-6 col-xs-6">
													<label for="phoneno" class="required"><em>*</em>Phone Number</label>
													<div class="input-box">
														<input type="text" id="phoneno" name="customer_phoneno" value="<?php echo $customer_phoneno;?>" title="Phone no" maxlength="255" class="input-text required-entry">
													</div>
												</div>
												
												<div class="field name-firstname col-md-6 col-sm-6 col-xs-6">
													<label for="gender" class="required"><em>*</em>Gender	</label>
													<div class="input-box">
														<select name="customer_gender" class="select custom">
					                                        <option value=""><?php echo getLangMsg("s_g");?></option>
					                                        <option value="M" <?php echo ($customer_gender=='M')?'selected="selected"':((@$_POST['customer_gender']=='M')?'selected="selected"':'')?> >Male</option>
					                                        <option value="F" <?php echo ($customer_gender=='F')?'selected="selected"':((@$_POST['customer_gender']=='F')?'selected="selected"':'')?> >FeMale</option>
					                                    </select>
													</div>
												</div>
											</div>
										</li>
										
										<li class="control">
											<input type="checkbox" name="change_password" id="change_password" value="1" onclick="setPasswordForm(this.checked)" title="Change Password" class="checkbox">
											<label for="change_password">Change Password</label>
										</li>
									</ul>
								</div>
							</div>
							<div class="col-lg-6 col-md-6">
								<div class="fieldset fix-min-height" style="display: none;">
									<h2 class="legend">Change Password</h2>
									<ul class="form-list">
									
										<li>
											Email: <?php echo $customer_emailid;?>
										</li>
										
										<li>
											<label for="current_password" class="required"><em>*</em>Current Password</label>
											<div class="input-box">
												<input type="password" title="Current Password" class="input-text" name="current_password" id="current_password">
											</div>
										</li>
										
										<li class="fields">
											<div class="row">
												<div class="col-lg-6 col-md-6 field">
													<label for="password" class="required"><em>*</em>New Password</label>
													<div class="input-box">
														<input type="password" title="New Password" class="input-text validate-password" name="password" id="password">
													</div>
												</div>
												<div class="col-lg-6 col-md-6 field">
													<label for="confirmation" class="required"><em>*</em>Confirm Password</label>
													<div class="input-box">
														<input type="password" title="Confirm New Password" class="input-text validate-cpassword" name="confirmation" id="confirmation">
													</div>
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="buttons-set">
							<p class="back-link hide"><a href="http://demo.flytheme.net/themes/sm_stationery/customer/account/"><small>« </small>Back</a></p>
							<button type="submit" title="Save" class="button"><span><span>Save</span></span></button>
						</div>
					</form>
					<script type="text/javascript">
				    var dataForm = new VarienForm('form-validate', true);
				    function setPasswordForm(arg)
				    {
				        if(arg){
				            $('current_password').up(3).show();
				            $('current_password').addClassName('required-entry');
				            $('password').addClassName('required-entry');
				            $('confirmation').addClassName('required-entry');
				
				        }else{
				            $('current_password').up(3).hide();
				            $('current_password').removeClassName('required-entry');
				            $('password').removeClassName('required-entry');
				            $('confirmation').removeClassName('required-entry');
				        }
				    }
					</script>
				</div>						
			</div>					
		</div>
	</div>
</div>
</div>