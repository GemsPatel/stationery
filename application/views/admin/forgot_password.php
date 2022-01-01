<?php $controller = $this->router->class; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<title>Administrator Forgot Password</title>

<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var controller = "<?php echo ucfirst(@$controller); ?>";
</script>

<link href="<?php echo asset_url('css/admin/login.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo asset_url(); ?>/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/common.js')?>"></script>

</head>
<body>
<!-- Box Start-->
<div id="box_bg">
<div id="content">
 <div style="position:relative; height:30px;">
	<h1 style="border:none">Forgot Your Password?</h1>
    <img id="logo" src="<?php echo asset_url('images/admin/login_logo.png') ?>" alt="Cloudwebs Technology" 
                		style="position:absolute; right:0px; top:-17px;" class="login_logo" height="60" width="60" />
       </div>                 
	<!-- Social Buttons -->
	<div class="social" style="height:auto; padding:10px 0px 10px 0px;">
	</div>
	
    <form method="post" action="<?php echo site_url('admin/lgs/forgotPassword')?>">
	<!-- Login Fields -->
	<div id="login">
     <?php $this->load->view('elements/notifications'); ?>
     Please enter your email id registered on admin panel. Password email link will be sent on this email id.<br/>
	 <input type="text" value="<?php echo set_value('forgot_email'); ?>" class="login user" name="forgot_email" placeholder="Enter Your Email Id"/>
	</div>
    
    <input type="button" value="Sign In" name="admin_sign" class="button blue fleft" onclick="javascript:window.location='<?php echo site_url('admin/lgs')?>'" />
    
	<!-- Green Button -->
    <input type="submit" value="Submit" name="admin_forgot_pass" class="button green" />
 
	<!-- Checkbox -->
	<div class="checkbox">
	<li>
	
	</li>
	</div>
    </form>
</div>
</div>
</body>
</html>
