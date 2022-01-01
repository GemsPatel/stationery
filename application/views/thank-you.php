

		
		<!-- ACCOUNT PAGE -->
		<section class="faq_page thankyou">
			
			<!-- CONTAINER -->
			<div class="container">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-12 col-md-12 col-sm-12 padbot30">
                    
                    	<div class="text_iframe column_item clearfix">
							<h2><i class="fa fa-thumbs-up"></i> &nbsp;<?php echo getLangMsg("order_place");?></h2>

                            <p><?php echo getLangMsg("order_success");?></p>
                        
                            <p><?php echo getLangMsg("order_no");?>: <strong><?php echo (int)@$order_id;?></strong></p>
                        
                            <p><?php echo getLangMsg("order_history");?> <a href="<?php echo site_url('account')?>"><?php echo getLangMsg("ma");?></a><?php echo getLangMsg("show_page");?> <a href="<?php echo site_url('account/order-history')?>"><?php echo getLangMsg("o_h");?></a>.</p>
                        
                            <p><?php echo getLangMsg("d_question");?> <a href="mailto:sales@Stationery.com">sales@Stationery.com</a>.</p>
                        
                            <p><?php echo getLangMsg("thnks");?></p> 
                            
                            <input type="button" value="Continue Shopping" onclick="window.location.href='<?php echo site_url()?>'">

						</div>
                        <br />
                        
					</div><!-- //INNER BLOCK -->
					
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        
