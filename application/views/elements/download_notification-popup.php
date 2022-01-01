<style type="text/css">
.download_app
{
	padding: 16px;
}
/* .modal-body
{
	float: inherit;
	background-image:url(images/logo__2.png);
	background-repeat:no-repeat;
	background-size:30% auto;
	-webkit-background-size:30% auto;
}
 */
 
</style>
<div class="modal fade forgot" id="CloudwebsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" <?php echo ( !isMobile() ? ' style="margin-top:47px;" ' : '' );?>>
    	<div class="modal-content">
    		<form method="post" id="download_app">
	      		<div class="modal-header show">
	        		<button type="button" id="close_download_popup" onclick="close_download_popup()" class="close" data-dismiss="modal" aria-label="Close">
	        			<span aria-hidden="true">&times;</span>
	        		</button>
	        		<h3 class="modal-title" id="exampleModalLabel">Download Mobile Apps</h3>
	      		</div>
	      		<div class="modal-body">
        			<div class="download_app">
	            		<a target="_blank" href="https://play.google.com/store/apps/details?id=com.Stationery.gj_android_3_10&hl=en" >
	                		<img title="Download with android app" src="<?php echo asset_url('images/android-app-icon_download.png')?>" width="200px">
	              		</a>
	              		<a target="_blank" href="https://itunes.apple.com/ao/app/Stationery/id1030671382?mt=8" >
	              		<img title="Download with apple app" src="<?php echo asset_url('images/apple-app-icon_download.png')?>" width="200px">
	              		</a>
	              		 <?php $this->session->set_userdata( array( "is_SID_c" => 0 ) );?>
					</div>
      			</div>
    		</div>
		</form>
  	</div>
</div>
