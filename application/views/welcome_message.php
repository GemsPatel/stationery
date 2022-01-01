<?
$lang = array();
if( $this->clang == 'en' )
{
    $lang['cu'] = "Contact Us";
    $lang['grp'] = "Group";
    $lang['cnl'] = "Channel";
    $lang['lt'] = "Link Title";
    $lang['ld'] = "Link Description";
    $lang['el'] = "Enter Link";
    $lang['t1'] = "Tags 1";
    $lang['t2'] = "Tags 2";
    $lang['t3'] = "Tags 3";
    $lang['sl'] = "Submit Link";
    
}
else
{
    $lang['cu'] = "聯繫我們";
    $lang['grp'] = "組";
    $lang['cnl'] = "渠道";
    $lang['lt'] = "鏈接標題";
    $lang['ld'] = "鏈接說明";
    $lang['el'] = "輸入鏈接";
    $lang['t1'] = "標籤1";
    $lang['t2'] = "標籤2";
    $lang['t3'] = "標籤23";
    $lang['sl'] = "提交鏈接";
    
}
?>
<div class="container-fluid text-center bg-theme submit">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6 col-12">
				<div class="submit-button py-3">
					<?
				    $email = getField( "site_email", "settings", "id", 1 );
				    ?>
					<a onClick="popupOpen( 'mailto:<?php echo $email; ?>' );" class="btn btn-theme1 w-100"><?php echo $lang['cu'];?></a>
				</div>
			</div>
		</div>

		<?php $this->load->view('elements/top-slider');?>

		<div class="row justify-content-center">
			<div class="col-md-6 col-12">
			    <form onsubmit="return false;" method="post" id="form-validate-link">
    				<div class="submit-input">
    					<input type="radio" name="sl_type" value="0" id="sl_type_g" checked="checked" class="mt-2 d-none" />
    					<label for="sl_type_g" class="text-theme1 float-left mt-2"><?php echo $lang['grp'];?></label>
    				</div>
    				<div class="submit-input">
    					<input type="radio" name="sl_type" value="1" id="sl_type_c" class="mt-2 d-none" />
    					<label for="sl_type_c" class="text-theme1 float-right mt-2 mr-4"><?php echo $lang['cnl'];?></label>
    				</div>
    				<div for="sl_type" class="input-notification error" ></div>
    				<div class="submit-form">
    					<div class="form-group mb-0">
    						<input type="text" name="sl_title" id="sl_title" class="form-control" placeholder="<?php echo $lang['lt'];?>">
    					</div>
						<div for="sl_title" class="input-notification mb-2 error" ></div>
    					<div class="form-group mb-0">
    						<textarea name="sl_description" id="sl_description" class="form-control c-form" rows="6" placeholder="<?php echo $lang['ld'];?>"></textarea>
    					</div>
						<div for="sl_description" class="input-notification mb-2 error" ></div>
    					<div class="input-group mb-0">
    						<input type="text" name="pre-fix" id="pre-fix" class="form-control" value="https://t.me/" disabled="disabled">
    						<input type="text" name="sl_link" id="sl_link" class="form-control c-form" placeholder="<?php echo $lang['el']; ?>">
    					</div>
						<div for="sl_link" class="mb-2 input-notification error" ></div>
    					<div class="form-group mb-0">
    						<input type="text" name="sl_tag_1" id="sl_tag_1" class="form-control c-form text-center" placeholder="<?php echo $lang['t1']; ?>">
    					</div>
						<div for="sl_tag_1" class="input-notification mb-2 error" ></div>
    					<div class="form-group mb-2 ">
    						<input type="text" name="sl_tag_2" id="sl_tag_2" class="form-control c-form text-center" placeholder="<?php echo $lang['t2']; ?>">
    					</div>
						<div for="sl_tag_2" class="input-notification mb-2 error" ></div>
    					<div class="form-group mb-2 ">
    						<input type="text" name="sl_tag_3" id="sl_tag_3" class="form-control c-form text-center" placeholder="<?php echo $lang['t3']; ?>">
    					</div>
						<div for="sl_tag_3" class="input-notification mb-2 error" ></div>
    					<div class="form-group">
    						<button class="btn btn-theme1 w-100" name="create_link" id="signup">
    						    <p class="mb-0 text"><?php echo $lang['sl']; ?></p>
        						<span id="signup_loading_img" class="d-none">
    						        <img class="login_priloaded" src="<?php echo base_url('img/preloader-white.gif') ?>" alt="loader" />
    					        </span>
						    </button>
    					</div>
    					<div class="input-notification mb-2 success" ></div>
    				</div>
				</form>
			</div>
		</div>
	</div>
</div>