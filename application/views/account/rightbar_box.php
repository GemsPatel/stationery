<?php $customerArr = fetchRow( "SELECT customer_firstname,customer_lastname FROM customer WHERE customer_id=".$this->session->userdata('customer_id') ); ?>
				<div id="sidebar" class="col-lg-3 col-md-3 col-sm-3 padbot50">
						
						<!-- MENU -->
						<div class="sidepanel widget_categories">
							<h3>ACCOUNT [<?php echo $customerArr['customer_firstname']; ?>]</h3>
							<ul>
							  <li><a href="<?php echo site_url('account');?>"><?php echo getLangMsg("ma");?></a></li>
                              <li><a href="<?php echo site_url('account/invite-friends')?>"><?php echo getLangMsg("invfr");?></a></li>
                              <li><a href="<?php echo site_url('account/order-history')?>"><?php echo getLangMsg("o_h");?></a></li>
                              <li><a href="<?php echo site_url('account/address-books');?>"><?php echo getLangMsg("a_bok");?></a></li>
                              <li><a href="<?php echo site_url('account/edit-account');?>"><?php echo getLangMsg("e_acc");?></a></li>
                              <li><a href="<?php echo site_url('account/change-password')?>"><?php echo getLangMsg("cng_pass");?></a></li>
                              <li><a href="<?php echo site_url('account/wishlist')?>"><?php echo getLangMsg("w_l");?></a></li>
                              <li><a href="<?php echo site_url('account/transactions')?>"><?php echo getLangMsg("mybal");?></a></li>
                              <li><a href="<?php echo site_url('account/newsletter')?>"><?php echo getLangMsg("nl");?></a></li>
                              <li><a href="<?php echo site_url('login/logout')?>"><?php echo getLangMsg("l_out");?></a></li>
							</ul>
						</div><!-- //MENU -->
						
				</div>
               