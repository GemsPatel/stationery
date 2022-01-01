
		<!-- CONTACTS BLOCK -->
		<section class="contacts_block">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<!-- ROW -->
				<div class="row padbot30">
					<div class="col-lg-6 col-md-6 padbot30">
						<div id="map"><iframe height="490" src="http://maps.google.co.in/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Surat+Gujarat&amp;sll=21.159207,72.8223832&amp;output=embed"></iframe></div>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-6 padbot30">
						<ul class="contact_info_block">
							<li>
								<h3><i class="fa fa-map-marker"></i><b><?php echo getLangMsg("stloc");?></b></h3>
								<p><?php echo getLangMsg("gsst");?></p>
								<span><?php echo getLangMsg("add");?></span>
							</li>
							<li>
								<h3><i class="fa fa-phone"></i><b><?php echo getLangMsg("phone");?></b></h3>
								<p class="phone"><?php echo getField('config_value','configuration','config_key','TOLL_FREE_NO') ?></p>
							</li>
							<li>
								<h3><i class="fa fa-envelope"></i><b><?php echo getLangMsg("email");?></b></h3>
                                
                                <?php $supportEmail = getField('config_value','configuration','config_key','SUPPORT_EMAIL') ?>
								<p><?php echo getLangMsg("sup");?></p>
								<a href="mailto:<?php echo $supportEmail ?>"><?php echo $supportEmail ?></a>

								<?php $partnerEmail = getField('config_value','configuration','config_key','PARTNER_EMAIL') ?>
								<p><?php  echo getLangMsg("pn");?></p>
								<a href="mailto:<?php echo $partnerEmail ?>"><?php echo $partnerEmail ?></a>
                                
                                <?php $salesEmail = getField('config_value','configuration','config_key','SALES_EMAIL') ?>
								<p><?php echo getLangMsg("rnr");?></p>
								<a href="mailto:<?php echo $salesEmail ?>"><?php echo $salesEmail ?></a>
							</li>
						</ul>
					</div>
					
					<div class="col-lg-3 col-md-3 col-sm-6 padbot30">
						<!-- CONTACT FORM -->
                        <div class="contact_form">
							<h3><b><?php  echo getLangMsg("cform");?></b></h3>
							
							<div id="fields">
								<form id="ajax-contact-form" enctype="multipart/form-data" method="post" onsubmit="return saveFeedbackForm(this);">
									<label><?php echo getLangMsg("nm");?></label><input type="text" name="pm_name" value="" placeholder="<?php echo getLangMsg("nm");?>" />
                                    <div class="input-notification error" for="pm_name"></div>
                                    
                                    <label><?php echo getLangMsg("email")?></label><input type="text" name="pm_email" value="" placeholder="<?php echo getLangMsg("email");?>" />
                                    <div class="input-notification error" for="pm_email"></div>
									
                                    <label><?php echo getLangMsg("phone");?></label><input type="text" name="pm_phone" value="" placeholder="<?php echo getLangMsg("phone");?>" />
                                    <div class="input-notification error" for="pm_phone"></div>
									
                                    <label><?php echo getLangMsg("msg");?></label><textarea name="pm_message" placeholder="<?php echo getLangMsg("msg");?>" ></textarea><br>
                                    <div class="input-notification error" for="pm_message"></div>
									
                                    <input id="btn_load" class="btn active" type="submit" value="Send Message" placeholder="Send Message" />
                                    <div id="note"></div>
								</form>
							</div>
						</div>
                        
						<!--<form id="form" enctype="multipart/form-data" method="post" onsubmit="return saveFeedbackForm(this);">
							<div id="ajax-contact-form" class="contact_form">
	                            <div class="box-category" style="padding:7px;">
	                                 <h3><b>Contacts form</b></h3>
	                                 <div class="tradus-address-row">
	                                 	<div class="split left">
	                                        <input name="pm_name" type="text" placeholder="Name" value="">
	                                        <span class="input-notification error png_bg" id="input_notification_error" for="pm_name"></span>
	                                    </div>                               
	                                  </div>
	                                  <div class="tradus-address-row">
	                                     <div class="split left">
	                                         <input name="pm_email" type="text" placeholder="Email" value="">
	                                         <span class="input-notification error png_bg" id="login-error" for="pm_email"></span>
	                                     </div>                                
	                                  </div>
                                   <div class="tradus-address-row">
                                        <div class="split left">
                                            <input name="pm_phone" type="text" placeholder="Phone Number" value="">
                                            <span class="input-notification error png_bg" id="login-error" for="pm_phone"></span>
                                        </div>                                
                                    </div>
                                    <div class="tradus-address-row">
                                        <div>
                                            <textarea name="pm_message" rows="2" placeholder="Message Containt" ></textarea>
                                            <span class="input-notification error png_bg" id="login-error" for="pm_message"></span>
                                        </div>                                
                                    </div>
                                    <input type="submit" value="Continue" class="button1" />
                               </div>  
                                <div class="notification_area_feedback"></div>
                              </div>
                              <div class="clear"></div>
                   
                         </form>  -->
						<!-- //CONTACT FORM -->
					</div>
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //CONTACTS BLOCK -->
