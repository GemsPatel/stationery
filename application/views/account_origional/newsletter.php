		
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
							<div class="comment_form_wrapper column_item">
								<form action="" method="post">
									
                                    <p>
                                    	<i class="fa fa-location-arrow"></i>Subscriber: &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" hidden="" name="customer_newsletter" id="yes" value="1" <?php echo (@$customer_newsletter=='1')?'checked="checked"':'';?>>
                                        <label for="yes">Yes</label>
                                        
                                        <input type="radio" hidden="" name="customer_newsletter" id="no" value="0" <?php echo (@$customer_newsletter=='0')?'checked="checked"':'';?>>
                                        <label for="no">No</label>
                                    </p>
                                    
                                                                        
									<div class="clear"></div>
									
                                    <input type="submit" value="Submit" />
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
        
