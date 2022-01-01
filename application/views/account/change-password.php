		
		<!-- ACCOUNT PAGE -->
		<section class="faq_page">
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
						
                        <!-- LEAVE A COMMENT -->
						<div id="comment_form" data-appear-top-offset='-100' data-animated='fadeInUp'>
							<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?></h2>
							<div class="comment_form_wrapper">
								<form onsubmit="" method="post">
									<input type="password" name="current_password" value="<?php echo set_value('current_password')?>" placeholder="<?php echo getLangMsg("c_pass")."*";?>" />
                                    <div class="input-notification error" for="current_password"></div><br />
                                    
                                    <input type="password" name="new_password" value="<?php echo set_value('new_password')?>" placeholder="<?php echo getLangMsg("n_pass")."*";?>" />	
                                    <div class="input-notification error" for="new_password"></div><br />
                                    
                                    <input type="password" name="confirm_password" value="<?php echo set_value('confirm_password')?>" placeholder="<?php echo getLangMsg("cf_pass")."*";?>" />
                                    <div class="input-notification error" for="confirm_password"></div><br />
                                                             
									<div class="clear"></div>
									
                                    <input type="submit" value="Submit"/>
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
        
        
