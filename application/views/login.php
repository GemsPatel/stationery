<div class="main-container col1-layout">
	<div class="main">
		<div class="col-main">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="account-login">
							<div class="page-title">
								<h1>Login or Create an Account</h1>
							</div>
							<form onsubmit="return false;" method="get" id="login-form">
								<div class="row">
									<div class="col-lg-6 col-md-6 new-users">
										<div class="content">
											<h2>New Customers</h2>
											<p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
											<div class="buttons-set">
												<button type="button" title="Create an Account" class="button" onclick="window.location='<?php echo site_url('register')?>';"><span><span>Create an Account</span></span></button>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 registered-users">
										<div class="content">
											<h2><?php echo getLangMsg("iar");?></h2>
											<p><?php echo getLangMsg("iarl");?>If you have an account with us, please log in.</p>
											<ul class="form-list">
												<li>
													<label for="email" class="required"><em>*</em><?php echo getLangMsg("email");?></label>
													<div class="input-box">
														<input type="text" name="login_email" value="" id="email" class="input-text required-entry validate-email" title="<?php echo getLangMsg("email");?>" />
														<div for="login_email" class="input-notification error" id="login-error-msg"></div>
													</div>
												</li>
												<li>
													<label for="pass" class="required"><em>*</em><?php echo getLangMsg("pass");?></label>
													<div class="input-box">
														<input type="password" name="login_password" class="input-text required-entry validate-password" id="pass" title="<?php echo getLangMsg("pass");?>" />
														<div for="login_password" class="input-notification error" id="login-error"></div>
														<div for="login_not_match" class="input-notification error" id="login-error"></div>
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
											<div class="buttons-set">
												<a href="<?php echo site_url('sm/forgotPassword');?>" class="f-left">Forgot Your Password?</a>
												<button type="submit" class="button" title="Login" name="sign_in" id="login">
													<span><span>Login</span></span>
												</button>
												<span id="login_loading_img" class="hide"><img class="login_priloaded" src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
											</div>
											<a href="javascript:void(0)" onclick="facebook_login()">
                                            	<img title="Login with facebook" src="http://www.gujcart.com/images/icon-fb-login.png">
                                            </a>
										</div>
									</div>
								</div>
							</form>
							<script type="text/javascript">
								var dataForm = new VarienForm('login-form', true);
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('elements/login_fb')?>