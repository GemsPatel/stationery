<div class="main-container col1-layout">
	<div class="main">
		<div class="col-main">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="account-create">
							<div class="page-title">
								<h1><?php echo getLangMsg("newc");?></h1>
							</div>
							<form onsubmit="return false;" method="get" id="form-validate-register">
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="fieldset">
											<h2 class="legend">Personal Information</h2>
											<ul class="form-list">
												<li class="fields">
												
													<div class="customer-name-middlename row">
														<div class="field name-firstname col-md-6 col-sm-6 col-xs-6">
															<label for="firstname" class="required"><em>*</em>First Name</label>
															<div class="input-box">
																<input type="text" id="firstname" name="customer_firstname" value="" title="First Name" maxlength="255" class="input-text required-entry"  />
																<div for="customer_firstname" class="input-notification error" id="login-error"></div>
															</div>
														</div>

														<div class="field name-lastname col-md-6 col-sm-6 col-xs-6">
															<label for="lastname" class="required"><em>*</em>Last Name</label>
															<div class="input-box">
																<input type="text" id="lastname" name="customer_lastname" value="" title="Last Name" maxlength="255" class="input-text required-entry"  />
																<div for="customer_lastname" class="input-notification error" id="login-error"></div>
															</div>
														</div>
													</div>
													
												</li>
												<li class="fields">
													<label for="phone" class="required"><em>*</em><?php echo getLangMsg("phone");?></label>
													<div class="input-box">
														<input type="text" id="phone" name="customer_phoneno" value="" title="First Name" maxlength="255" class="input-text required-entry"  />
														<div for="customer_phoneno" class="input-notification error" id="login-error"></div>
													</div>
												</li>
												<li class="control">
													<div class="">
														<input type="checkbox" id="reg1" name="agree" value="1" >
                            							<div for="agree" class="input-notification error" id="login-error"></div>
													</div>
													<label for="reg1"><?php  echo getLangMsg("itc");?></label>
												</li>
												<li class="control">
													<div class="input-box">
														<input type="checkbox" name="is_subscribed" title="Sign Up for Newsletter" value="1" id="is_subscribed" class="checkbox" />
													</div>
													<label for="is_subscribed">Sign Up for Newsletter</label>
												</li>
											</ul>
										</div>
									</div>
									
									<div class="col-lg-6 col-md-6">
										<div class="fieldset">
											<h2 class="legend">Login Information</h2>
											<ul class="form-list">
												<li class="fields">
													<div class="field">
														<label for="email_address" class="required"><em>*</em><?php echo getLangMsg("email");?></label>
														<div class="input-box">
															<input type="text" name="customer_emailid" id="email_address" value="" title="Email Address" class="input-text validate-email required-entry" />
															<div for="customer_emailid" class="input-notification error" id="login-error"></div>
														</div>
													</div>
													
													<div class="field" style="margin-bottom:5px;">
														<label for="password" class="required"><em>*</em>Password</label>
														<div class="input-box">
															<input type="password" name="customer_password" id="password" title="Password" class="input-text required-entry validate-password" />
															<div for="customer_password" class="input-notification error" id="login-error"></div>
														</div>
													</div>
												</li>
											</ul>
											<div id="window-overlay" class="window-overlay" style="display:none;"></div>
											<div id="remember-me-popup" class="remember-me-popup" style="display:none;">
												<div class="remember-me-popup-head">
													<h3>What's this?</h3>
													<a href="#" class="remember-me-popup-close" title="Close">Close</a>
												</div>
												<div class="remember-me-popup-body">
													<p>Checking &quot;Remember Me&quot; will let you access your shopping cart on this computer when you are logged out</p>
													<div class="remember-me-popup-close-button a-right">
														<a href="#" class="remember-me-popup-close button" title="Close"><span>Close</span></a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="buttons-set">
									<p class="back-link"><a href="../login/index.html" class="back-link"><small>&laquo; </small>Back</a></p>
									<button id="signup" type="submit" title="Submit" class="button" name="create_account" >
										<span><span>Submit</span></span>
									</button>
									<span id="signup_loading_img" class="hide">
										<img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" style="padding:5px;" />
                                    </span>
								</div>
							</form>
						    <script type="text/javascript">
						        var dataForm = new VarienForm('form-validate-register', true);
						    </script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<br>