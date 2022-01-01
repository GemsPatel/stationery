

		
        <!-- BLOG BLOCK -->
		<section class="blog">
			
			<!-- CONTAINER -->
			<div class="container">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- BLOG LIST -->
					<div class="col-lg-12 col-md-12 col-sm-12">
						
						<article class="post blog_post clearfix margbot20" data-appear-top-offset='-100' data-animated='fadeInUp'>
							<h2><?php
								$mfg = MANUFACTURER_ID;
								
								if( MANUFACTURER_ID == 7 ):
									$artRow = fetchRow( "SELECT article_name,article_description, article_image FROM article WHERE article_key='TERMS_CONDITION'  " );
								else: 
									$artRow = fetchRow( "SELECT article_name,article_description, article_image FROM article_cctld WHERE article_key='TERMS_CONDITION' AND manufacturer_id = '$mfg'" );
								endif;
								echo $artRow['article_name'];								
							?></h2>
							
							<div class="article_content">
								<?php 									
									echo $artRow['article_description'];
								?>
							</div>
							
						</article>
						
					</div><!-- //BLOG LIST -->
					
					
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section>
        <!-- //BLOG BLOCK -->
        
        