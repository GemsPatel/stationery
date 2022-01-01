				<!-- ROW -->
				<div class="row">
					
					<!-- BANNER WRAPPER -->
					<div class="banner_wrapper" data-appear-top-offset='-100' data-animated='fadeInUp'>
						<!-- BANNER -->
						<div class="col-lg-12 col-md-12">
							<?php 
								$artImg = fetchRow( "SELECT banner_name,banner_image,banner_link FROM banner WHERE banner_key='FREE_SHIPPING' LIMIT 1 " ); 
							?>
							<a class="banner type4 margbot40" href="<?php echo site_url($artImg['banner_link'])?>" >
								<img src="<?php echo asset_url($artImg['banner_image'])?>" alt="<?php echo $artImg['banner_name'];?>" title="<?php echo $artImg['banner_name'];?>"/>
							</a>
<!-- 							<a class="banner type4 margbot40" href="javascript:void(0);" ><img src="images/tovar/banner4.jpg" alt="" /></a> -->
						</div><!-- //BANNER -->
						
					</div>
                    <!-- //BANNER WRAPPER -->
				</div><!-- //ROW -->
				<?php //$this->load->view('elements/login_fb'); ?>
                <!-- <a class="banner nobord margbot40" href="javascript:void(0)" onclick="facebook_login()"><img src="<?php //echo asset_url('images/tovar/banner5.jpg')?>" alt="" /></a> -->