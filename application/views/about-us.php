		
		
		<!-- ABOUT US INFO -->
		<section class="about_us_info">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<!-- ROW -->
				<div class="row">
					<div class="col-lg-8 col-md-8 col-sm-8 padbot60 about_us_description" data-appear-top-offset='-100' data-animated='fadeInLeft'>
						<p>
							<?php 
								$mfg = MANUFACTURER_ID;
								
								if( MANUFACTURER_ID == 7 ):
								
									$artRow = fetchRow( "SELECT article_name,article_description, article_image FROM article WHERE article_key='ABOUT_US'  " );
									
								else: 
								
									$artRow = fetchRow( "SELECT article_name,article_description, article_image FROM article_cctld WHERE article_key='ABOUT_US' AND manufacturer_id = '$mfg'" );
									
								endif;
								
								echo $artRow['article_name'];
								
							?>
						</p>
						<div class="article_content">
							<?php 
								echo $artRow["article_description"];
							?>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-4 col-sm-4 padbot30" data-appear-top-offset='-100' data-animated='fadeInRight'>
						<img class="about_img1" src="<?php echo $artRow['article_image'];?>" alt="" />
					</div>
				</div><!-- //ROW -->
				
				
			</div><!-- //CONTAINER -->
		</section><!-- //ABOUT US INFO -->