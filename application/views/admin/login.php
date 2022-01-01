<?php $controller = $this->router->class; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="X-UA-Compatible" content="IE=9" />
<script type="text/javascript" src="<?php echo asset_url(); ?>/js/jquery.min.js"></script>
<script type="text/javascript">
	var base_url = "<?php echo base_url(); ?>";
	var controller = "<?php echo ucfirst(@$controller); ?>";
</script>
<title>Administrator Login</title>
<link href="<?php echo asset_url('css/admin/login.css'); ?>" rel="stylesheet" type="text/css" />
</head>
<body>
<!-- Box Start-->
<div id="box_bg">
<div id="content">
 <div style="position:relative; height:30px;">
	<h1 style="border:none">Sign In</h1>
    <img id="logo" src="<?php echo asset_url('images/admin/login_logo.png') ?>" alt="Cloudwebs Technology" 
                		style="position:absolute; right:0px; top:-17px;" class="login_logo" height="60" width="75" />
       </div>                 
	<!-- Social Buttons -->
	<div class="social" style="height:auto; padding:10px 0px 10px 0px;">
	</div>
	
    <form method="post">
	<!-- Login Fields -->
	<div id="login">
     <?php $this->load->view('elements/notifications'); ?>
    Sign in using your Admin account:<br/>
	<input type="text" value="<?php echo set_value('admin_user_emailid'); ?>" class="login user" name="admin_user_emailid"/>
	<input type="password" name="admin_user_password" value="" class="login password" />
	</div>
	
    <a href="<?php echo site_url('admin/lgs/forgotPassword'); ?>" class="forgot_pass">Forgot Your Password?</a>
    
	<!-- Green Button -->
    <input type="submit" value="Sign In" name="admin_login" class="button green" />
 
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
