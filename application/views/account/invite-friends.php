		<?php
			$cust_id = $this->session->userdata("customer_id");
			$c_url = getCampaignUrl($cust_id);
        ?>
        <script language="javascript">
			function fb_share_referral()
			{
				var fb_url = "http://www.facebook.com/sharer.php?u="+ encodeURIComponent("<?php echo $c_url?>");
				window.open(fb_url,"","width=500, height=300");
			}
			function google_share_referral()
			{
				var fb_url = "https://plus.google.com/share?url=" + encodeURIComponent("<?php echo $c_url?>");
				window.open(fb_url, "", "width=500, height=300");
			}
		</script>
		<!-- ACCOUNT PAGE -->
		<section class="faq_page" data-test="2">
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
						
                        <!-- LEAVE A COMMENT -->
						<div id="comment_form" data-appear-top-offset='-100' data-animated='fadeInUp'>
							<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?></h2>
							<div class="comment_form_wrapper column_item">
								<form id="invite_form" enctype="multipart/form-data" method="post" onsubmit="return inviteFriends(this);">
									<div class="title">
                                    	<a href=""><img src="" alt="" title="" /></a>
                                        <ul class="list3">
                                            <li><p>For every friend that register and purchase first time you will get discount of <?php echo lp(getField('config_value','configuration','config_key','COMPAIGN_AMT'))?>.</p></li>
                                        </ul>
                                    </div>
                                    <div class="title center">
                                    	<p>Invite your friends on Social Media.</p>
                                        
                                    	<a href="javascript:fb_share_referral();" title="Share On Facebook">
                                        	<img src="<?php echo asset_url('images/icon_facebook.png') ?>" alt="Share On Facebook" />
                                        </a>&nbsp;&nbsp;
                                        <a href="javascript:google_share_referral();" title="Share On Google+">
                                        	<img src="<?php echo asset_url('images/icon_google.png') ?>" alt="Share On Google+" />
                                        </a>
                                        <br /><br />
                                        <h2>--- OR ---</h2>
                                        
                                        <p>Just send the link via email to earn money</p>
                                    </div>
                                                      
                                    <input type="text" name="customer_partner_id" value="" placeholder="<?php echo getLangMsg("wfmsg")." *";?>" />
                                    <div class="input-notification error" for="customer_partner_id"></div>
                                    
                                    <textarea name="customer_note" placeholder="<?php echo getLangMsg("tyf")." *";?>" onkeyup="getCustomerNote(this.value)"></textarea>
                                    <div class="input-notification error" for="customer_note"></div>
                                    
                                    
                                    <h3>Below message will be sent to your friend:</h3>
                                    <div class="email_structure_box">
										<?php 
                                        $inviteFrnd = fetchRow( "SELECT article_name,article_image,article_description FROM article WHERE article_key='INVITE_FRIEND_MAIL' " );
                                        echo "From: ".$this->session->userdata("customer_emailid"). "<br><br><div id='display_cnote'></div><br>". $inviteFrnd['article_description'];
                                        ?><a href="<?php echo $c_url?>">Click to join</a><br /><br />
                                        Regards,<br /><?php echo getField('customer_firstname','customer','customer_id',$cust_id); ?>
                                    </div>
						
									<input type="submit" class="dark-blue big" value="submit" name="invite_friends" id="btn_invitefriends">
									<span id="invite_loading_img" class="hide fright"><img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
									<div id="input-notification" for="invite_friends"></div>
									<div class="clear"></div>
									
								</form>
							</div>
						</div><!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        <script language="javascript">
		function getCustomerNote(val)
		{
			$('#display_cnote').html(val);
		}
		</script>
