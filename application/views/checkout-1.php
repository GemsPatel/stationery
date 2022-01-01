<!--- Only display if logged in --->
<div class="checkout_delivery clearfix" <?php echo ($is_logged_in) ? ' style="display:block;" ' : ' style="display:none;" ';?> id="log_out_" >
	<h3 class="checkout_title">User Information</h3> 
	<?php //$this->load->view('elements/login_fb'); ?>
	<?php
		$name = '';	
	    if($customer_group_type=='U')
		{
			$name = $customer_firstname.' '.$customer_lastname;
		}
		else if($customer_group_type=='G' && !empty($customer_emailid))
		{
			$name = 'Guest - '.$customer_emailid;
		}
	?>
	
	<div class="checkout_delivery_note green">
		<i class="fa fa-check-circle green"></i><?php echo getLangMsg("login_as", $name);?>
	</div>
	<br />

    <input id="ridio1" type="radio" name="radio" hidden />
    <a onclick="logOut()" class="cursor font-14"><i class="fa fa-smile-o green"></i>&nbsp; <?php echo getLangMsg("lo_as");?></a>
    <span id="login_loading_img_che" class="hide">
        <img style="padding:5px;" src="<?php echo asset_url('images/preloader-white.gif'); ?>" alt="loader">
    </span>
				
				
	<a class="btn active pull-right checkout_block_btn" href="<?php echo site_url('checkout?act=sinfo') ?>" ><?php echo getLangMsg("cont");?></a>
</div>


<!--- Only display if not logged in --->
<div <?php echo (!$is_logged_in) ? ' style="display:block;" ' : ' style="display:none;" ';?> id="log_in_">
<form class="checkout_form clearfix" id="login" action="javascript:void(0);" onsubmit="return false;" method="post">
	
	<div class="checkout_form_input sity">
		<label><?php echo getLangMsg("lid");?> <span class="color_red"> *</span></label>
		<input type="text" name="login_email" value="" />
		<span id="login-error" class="input-notification error png_bg" for="login_email" style="width:800px;"></span>
		<div><small><?php echo getLangMsg("lidmsg");?></small></div>
	</div>
	
    <div class="checkout_form_input adress" id="pass_field">
		<label><?php echo getLangMsg("pass");?> <span class="color_red"> *</span></label>
		<input type="password" id="pass_field" name="login_password" value="" />
        <span id="login-error" class="input-notification error png_bg" for="login_password"></span>
        <span id="login-error" class="input-notification error png_bg" for="login_not_match"></span>
        <a class="forgot_pass" data-toggle="modal" data-target="#CloudwebsModal"><label class="cursor"><?php echo getLangMsg("fyp");?></label></a>
	</div>
    
	<?php if($this->session->userdata('lType') == 'PC'):?>
    <div class="clear"></div>
    <?php endif; ?>
    
	<div class="checkout_form_input">
		<input type="radio" id="checkout_pass_w" name="checkout_pass" onchange="javascript:$('#pass_field').hide();"/>
		<label for="checkout_pass_w"><span></span><?php echo getLangMsg("cwp");?></label>
	</div>
			
	<div class="checkout_form_input last">
		<input type="radio" id="checkout_pass_p" name="checkout_pass" checked="checked" onchange="javascript:$('#pass_field').show();"/>
		<label for="checkout_pass_p"><span></span><?php echo getLangMsg("ihap");?></label>
	</div>
	
			
	<div class="checkout_form_input2 last adress">
		<a class="btn active pull-left" href="javascript:void(0);" onclick="logIn()"><?php echo getLangMsg("cont")?></a>
				
		<span id="login_loading_img_che_login" style="display:none;">
			<img src="<?php echo asset_url('images/preloader-white.gif'); ?>" alt="loader">
		</span>
	</div>
	
	<div class="checkout_form_input2 center"><hr class="mar-5" /><?php echo getLangMsg("or");?></div>

	<?php if($this->session->userdata('lType') == 'PC'):?>
    <div class="clear"></div>
    <?php endif; ?>
    
	<div class="checkout_form_input center martop15">
		<label><?php echo getLangMsg("sifb");?> </label>
	</div>
			
	<div class="checkout_form_input">
		<a class="register_here" href="javascript:void(0)" onclick="facebook_login()"><img src="<?php echo asset_url('images/icon-fb-login.png')?>" title="Login with facebook"/></a>
	</div>
	
</form>
</div>

<?php $this->load->view('elements/forgot-password')?>
<?php $this->load->view('elements/login_fb'); ?>
