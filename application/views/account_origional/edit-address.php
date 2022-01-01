		
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
							<div class="comment_form_wrapper edit_address">
								<form action="<?php echo site_url('account/save-address');?>" method="post" enctype="multipart/form-data">
								<?php
									if($mode=='add')
										$customer_address_id=0;
								?>
									<input type="hidden" name="customer_address_id" value="<?php echo (@$customer_address_id)?_en($customer_address_id):@$_POST['customer_address_id']; ?>" />
                                
									<input type="text" name="customer_address_firstname" placeholder="<?php echo getLangMsg("f_name")."*";?>" value="<?php echo (@$customer_address_firstname)?$customer_address_firstname:@$_POST['customer_address_firstname']; ?>">
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_firstname'):''; ?> </span>
                                    
                                    <input type="text" name="customer_address_lastname" value="<?php echo (@$customer_address_lastname)?$customer_address_lastname:@$_POST['customer_address_lastname']; ?>" placeholder="<?php echo getLangMsg("l_name");?>" />	
                                    
                                    <input type="text" name="customer_address_address" placeholder="<?php echo getLangMsg("address")."*";?>" class="input-address" value="<?php echo (@$customer_address_address)?$customer_address_address:@$_POST['customer_address_address']; ?>">
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_address'):''; ?> </span>
                                    
                                    
									<?php echo loadCountryDropdown(((@$country_id)?$country_id:@$_POST['country_id']),'onchange="getState(this.value,\'state_id\')" class="select custom" ', 'country_id');?>
									<span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('country_id'):''; ?> </span>
                                    
                                    <?php echo loadStateDropdown('state_id',((@$country_id)?$country_id:@$_POST['country_id']),((@$state_id)?$state_id:@$_POST['state_id']),' id="state_id" class="select custom" '); ?>
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('state_id'):''; ?> </span>
                                    
                                    <input type="text" name="address_city" value="<?php echo ((@$address_city)?$address_city:@$_POST['address_city']); ?>" placeholder="<?php echo getLangMsg("city")."*";?>" />
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('address_city'):''; ?> </span>
                                    
                                    <input type="text" name="customer_address_landmark_area" value="<?php echo ((@$customer_address_landmark_area)?$customer_address_landmark_area:@$_POST['customer_address_landmark_area']); ?>" placeholder="<?php echo getLangMsg("l_arear")?>" />
                                                                        
                                    <input type="text" name="pincode" value="<?php echo ((@$pincode)?$pincode:@$_POST['pincode']); ?>" placeholder="<?php echo  getLangMsg("pin")."*";?>" />
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('pincode'):''; ?> </span>
                                    
                                    <input type="text" name="customer_address_phone_no" value="<?php echo ((@$customer_address_phone_no)?$customer_address_phone_no:@$_POST['customer_address_phone_no']);?>" placeholder="<?php echo getLangMsg("phone")." ".getLangMsg("no")."*";?>" />
                                    <span class="error_msg" style="color:red;"><?php echo (@$error)?form_error('customer_address_phone_no'):''; ?> </span>
                                    
									<div class="clear"></div>
									
                                    <input type="submit" value="Submit" id="btn_address"/>
                                    <span id="address_loading_img" class="hide"><img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
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
        
        
