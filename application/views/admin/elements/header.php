<?php
$custom_page_title = (!empty($custom_page_title) ? $custom_page_title : $this->router->class);
$controller = $this->router->class;
?>
<!DOCTYPE html>
<html dir="ltr" lang="en"><head>
<meta charset="UTF-8" />
<title><?php echo ($custom_page_title) ? pgTitle($custom_page_title) : 'Admin';?></title>
<base href="<?php echo site_url();?>" />

<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/admin/stylesheet.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/admin/jquery.css')?>" />
<script language="javascript" type="text/javascript">
	var root_dir = 'admin';
	var router_dir = '<?php echo $this->router->directory?>';
	var base_url = "<?php echo base_url(); ?>";
	var asset_url = "<?php echo asset_url(); ?>";
	var controller = "<?php echo ucfirst($controller); ?>";
	var controller_org = "<?php echo $controller; ?>";
	var baseDomain = '<?php echo base_domain(); ?>';	//base domain for XMPP service
	var IT_KEY = '<?php echo $this->session->userdata('IT_KEY'); ?>';
	var su_type = '<?php echo $this->session->userdata('su_type'); ?>';
</script>

<script type="text/javascript" src="<?php echo asset_url('js/admin/jquery/jquery-1.7.1.min.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/jquery-ui.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/jquery/tabs.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/facebox.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/jquery/superfish/js/superfish.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/admin.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/common.js')?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/qtip.js')?>"></script>

<noscript> <!-- Show a notification if the user has disabled javascript -->
<div class="notification error png_bg">
    <div>
        Javascript is disabled or is not supported by your browser. Please <a href="http://browsehappy.com/" title="Upgrade to a better browser">upgrade</a> your browser or <a href="http://www.google.com/support/bin/answer.py?answer=23852" title="Enable Javascript in your browser">enable</a> Javascript to navigate the interface properly.
    </div>
</div>
</noscript>

</head>
<body ondragstart="return false">
<!-- oncontextmenu="return false"  ondragstart="return false" onselectstart="return false"-->
<div id="container" class="admin">
  <div id="header">
  <div class="div1">
    <div class="div2">
    	<a id="header_shopname" href="<?php echo site_url('admin/lgs')?>">Admin Panel </a>
        <?php
			$admin_user_id = $this->session->userdata('admin_id');
        	$resAdm = executeQuery("SELECT admin_user_order_noti_status, admin_user_customer_noti_status, admin_user_message_noti_status FROM admin_user WHERE admin_user_id=".$admin_user_id." ");
		?>
    	<div id="notifs_icon_wrapper">
        <?php
        	if( $resAdm[0]['admin_user_order_noti_status']==0 ):
		?>
            <div class="notifs" id="orders_notif">
                <span class="number_wrapper" id="orders_notif_number_wrapper" style="display: none;">
                    <span id="orders_notif_value">0</span>
                </span>
                <div class="notifs_wrapper" id="orders_notif_wrapper" style="display:none">
                </div>
            </div>
        <?php
        	endif;
        	if($resAdm[0]['admin_user_customer_noti_status']==0):
		?>    
            <div class="notifs notifs_alternate" id="customers_notif">
                    <span class="number_wrapper" id="customers_notif_number_wrapper" style="display: none;">
                        <span id="customers_notif_value">0</span>
                    </span>
                <div class="notifs_wrapper" id="customers_notif_wrapper" style="display:none">
                </div>
            </div>
        <?php
        	endif;
        	if($resAdm[0]['admin_user_message_noti_status']==0):
		?>    
            <div class="notifs" id="customer_messages_notif">
                    <span class="number_wrapper" id="customer_messages_notif_number_wrapper" style="display: none;">
                        <span id="customer_messages_notif_value">0</span>
                    </span>
                <div class="notifs_wrapper" id="customer_messages_notif_wrapper" style="display:none"></div>
            </div>
        <?php
        	endif;
		?>    
        </div>
    </div>
        <div class="div3">
        	
        	<img src="<?php echo asset_url('images/admin/lock.png')?>" alt="" style="position: relative; top: 3px;" />&nbsp;You are logged in as 
            <span><a rel="modal" href="<?php echo site_url('admin/lgs/accountSettings'); ?>"><?php echo getField("admin_user_firstname","admin_user","admin_user_id",$this->session->userdata('admin_id')); ?></a></span>
            &nbsp;&nbsp;|&nbsp; <a href="<?php echo base_url()?>" target="_blank">View Site</a> &nbsp;|&nbsp; <a href="<?php echo site_url('admin/lgs/logout')?>">Logout</a>
            
        </div>
      </div>

	<?php
		if( IS_CACHE ) 
		{
			//moved below code to CI_Controller class constructor
// 			//cache driver
// 			$CI =& get_instance();
// 			$CI->load->driver( 'cache', cacheConfig());
			
			$header_menu;
			$cache_key = cacheKey( 'admin_header_menu_'.$admin_user_id );
			
			if ( ! $header_menu = $CI->cache->get( $cache_key ) )
			{
				$header_menu = $CI->load->view('admin/elements/header_menu', '', TRUE);
			
				saveCacheKey( $cache_key, 'admin_header');
			
				// Save into the cache for infinite time
				$CI->cache->save( $cache_key, $header_menu, 0);
			}
			
			echo $header_menu;
		}
		else 
		{
			$this->load->view('admin/elements/header_menu');
		}
	?>
	
  </div>
  
  <?php $this->load->view('elements/notifications'); ?>
