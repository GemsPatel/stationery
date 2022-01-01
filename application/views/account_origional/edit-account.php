
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
								<form enctype="multipart/form-data" method="post" action="">
									<input type="text" name="customer_firstname" value="<?php echo set_value('customer_firstname',$customer_firstname); ?>" placeholder="<?php echo getLangMsg("f_name")."*";?>" />
                                    
                                    <input type="text" name="customer_lastname" value="<?php echo set_value('customer_lastname',$customer_lastname); ?>" placeholder="<?php echo getLangMsg("l_name")."*";?>" />	
                                   								
                                    <input type="text" name="customer_emailid" disabled="disabled" value="<?php echo set_value('customer_emailid',$customer_emailid); ?>" placeholder="<?php echo getLangMsg("email");?>" />
                                    
                                    <select name="customer_gender" class="select custom">
                                        <option value=""><?php echo getLangMsg("s_g");?></option>
                                        <option value="M" <?php echo ($customer_gender=='M')?'selected="selected"':((@$_POST['customer_gender']=='M')?'selected="selected"':'')?> ><?php echo getLangMsg("m");?></option>
                                        <option value="F" <?php echo ($customer_gender=='F')?'selected="selected"':((@$_POST['customer_gender']=='F')?'selected="selected"':'')?> ><?php echo getLangMsg("f");?></option>
                                    </select>
                                    
                                    <input type="text" name="customer_phoneno" value="<?php echo set_value('customer_phoneno',$customer_phoneno); ?>" placeholder="<?php echo getLangMsg("phone").getLangMsg("no").".*";?>" />
                                    
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
        
        