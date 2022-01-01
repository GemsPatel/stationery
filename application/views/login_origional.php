		<?php //$this->load->view('elements/login_fb'); ?>
       
        <!-- MY ACCOUNT PAGE -->
		<section class="my_account parallax">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<div class="my_account_block clearfix">
					<div class="login clearfix">
						<h2><?php echo getLangMsg("iar");?></h2>
						<form class="login_form" id="login" onsubmit="return false;" method="get">
							
							<input type="text" name="login_email" placeholder="<?php echo getLangMsg("email")." *";?>"  />
                            <div for="login_email" class="input-notification error" id="login-error-msg"></div>
							
							<input class="last" type="password" name="login_password" placeholder="<?php echo getLangMsg("pass")." *";?>"/>
                            <div for="login_password" class="input-notification error" id="login-error"></div>
                            <div for="login_not_match" class="input-notification error" id="login-error"></div>
                            <div id="Error_login" class="font"></div>
							
							<div class="clearfix">
								<div class="pull-left"></div>
								<div class="pull-right"><a class="forgot_pass cursor" data-toggle="modal" data-target="#CloudwebsModal"><?php echo getLangMsg("fyp");?></a></div>
							</div>
                            
							<div class="submit-but center">
								<input type="submit" value="Login" name="sign_in" id="login">
								<span id="login_loading_img" class="hide"><img class="login_priloaded" src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
							</div>
							
						</form>
                        
                        <div class="checkout_form_input2 center martop20"><b>OR</b></div>
                        <div class="checkout_form_input2 center">
                            <a href="javascript:void(0)" onclick="facebook_login()">
                            	<img title="Login with facebook" src="<?php echo asset_url('images/icon-fb-login.png')?>">
                            </a>
                        </div>
                        
					</div>
					<div class="new_customers">
						<h2><?php echo getLangMsg("newc");?></h2>
						<form class="register_form" id="register" onsubmit="return false;" method="get">
							
							<input type="text" name="customer_firstname" placeholder="<?php echo getLangMsg("nm")." *";?>"/>
                            <div for="customer_firstname" class="input-notification error" id="login-error"></div>
                            
                            <input type="text" name="customer_emailid" placeholder="<?php echo getLangMsg("email")." *";?>"/>
                            <div for="customer_emailid" class="input-notification error" id="login-error"></div>
                            
                            <input type="text" name="customer_phoneno" placeholder="<?php echo getLangMsg("phone")." *";?>"/>
                            <div for="customer_phoneno" class="input-notification error" id="login-error"></div>
							
							<input class="last" type="password" name="customer_password" placeholder="<?php echo getLangMsg("pass")." *";?>"/>
							<div for="customer_password" class="input-notification error" id="login-error"></div>
							
							<div class="clearfix">
                            
                            	<input type="checkbox" id="reg1" name="agree"><label for="reg1"><?php  echo getLangMsg("itc")." *";?></label>
                            	<div for="agree" class="input-notification error" id="login-error"></div>
							
								<input type="checkbox" id="reg2" name="customer_newsletter"><label for="reg2"><?php echo getLangMsg("nl");?></label>
							</div>
							
							<div class="center submit-but">
							
								<input id="signup" type="submit" value="Create Account" name="create_account" class="f-none">
									<span id="signup_loading_img" class="hide">
										<img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" style="padding:5px;" />
                                    </span>
							</div>
							
							<div id="success_register" class="font"></div>
						</form>
					</div>
				</div>
				
				<div class="my_account_note center"><?php echo getLangMsg("haq");?> <b><?php echo getField('config_value','configuration','config_key','TOLL_FREE_NO') ?></b></div>
			</div><!-- //CONTAINER -->
		</section>
        
        <?php $this->load->view('elements/forgot-password')?>
        <?php $this->load->view('elements/login_fb'); ?>