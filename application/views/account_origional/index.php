
		
		<!-- ACCOUNT PAGE -->
		<section class="faq_page">
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
                    
                    	<div class="text_iframe column_item">
							<p><i class="fa fa-info-circle"></i><?php echo getLangMsg("m_ordr");?></p>
							<ul class="list2">
                                <li><a href="<?php echo site_url('account/order-history');?>"><?php echo getLangMsg("vmohis");?></a></li>
                                <li><a href="<?php echo site_url('account/transactions');?>"><?php echo getLangMsg("m_t");?></a></li>
                            </ul>
						</div>
                        <br />
                        <div class="text_iframe column_item">
							<p><i class="fa fa-info-circle"></i><?php echo getLangMsg("m_a");?> <span class="fright"><?php echo "(".getLangMsg("cur_bal") ;?> <b class="chan_curr"><?php echo lp($customer_account_manage_balance); ?></b>)</span></p>
							<ul class="list2">
                                <li><a href="<?php echo site_url('account/edit-account');?>"><?php echo getLangMsg("emainfo")?></a></li>
                                <li><a href="<?php echo site_url('account/change-password');?>"><?php echo getLangMsg("cng_pass")?></a></li>
                                <li><a href="<?php echo site_url('account/address-books');?>"><?php echo getLangMsg("m_a_b");?></a></li>
                                <li><a href="<?php echo site_url('account/wishlist');?>"><?php echo getLangMsg("m_w_l");?></a></li>
                            </ul>
						</div>
                        <br />
                        <div class="text_iframe column_item">
							<p><i class="fa fa-info-circle"></i><?php echo getLangMsg("nl");?></p>
							<ul class="list2">
                                <li><a href="<?php echo site_url('account/newsletter');?>"><?php echo getLangMsg("s_u_n");?></a></li>
                            </ul>
						</div>
                        
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        
        