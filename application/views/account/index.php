<div class="main-container col2-left-layout">
	<div class="main">
		<div class="container">
			<div class="row">					
				<?php 
				$this->load->view('account/leftbar_box');
				
				?>						
				<div class="col-lg-9 col-md-9 col-main">
					<div class="my-account">
						<div class="dashboard">
							<div class="page-title">
								<h1>My Dashboard</h1>
							</div>
							<div class="welcome-msg">
								<p class="hello">
									<strong>Hello, Gautam Patel!</strong>
								</p>
								<p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
							</div>
							<div class="box-account box-info">
								<div class="box-head">
									<h2>Account Information</h2>
								</div>
								<div class="row">
									<div class="col-lg-6 col-md-6">
										<div class="box">
											<div class="box-title">
												<h3>Contact Information</h3>
												<a href="<?php echo site_url('account/edit-account')?>">Edit</a>
											</div>
											<?php $cutomer = exeQuery( "SELECT CONCAT( customer_firstname, ' ', customer_lastname ) as name, customer_emailid FROM customer WHERE customer_id = ".(int)$this->session->userdata( 'customer_id' ) );?>
											<div class="box-content">
												<p><?php echo $cutomer['name']."<br>".$cutomer['customer_emailid'];?><br> 
													<a href="">Change Password</a>
												</p>
											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-6">
										<div class="box">
											<div class="box-title">
												<h3>Newsletters</h3>
												<a href="<?php echo site_url('account/newsletter');?>">Edit</a>
											</div>
											<div class="box-content">
												<p>You are currently not subscribed to any newsletter.                                    </p>
											</div>
										</div>
									</div>
								</div>
								<div class="col2-set">
									<div class="box">
										<div class="box-title title-add">
											<h3>Address Book</h3>
											<a href="http://demo.flytheme.net/themes/sm_stationery/customer/address/">Manage Addresses</a>
										</div>
										<div class="box-content">
											<div class="row">
												<div class="col-lg-6 col-md-6">
													<h4>Default Billing Address</h4>
													<address>You have not set a default billing address.<br>
														<a href="http://demo.flytheme.net/themes/sm_stationery/customer/address/edit/">Edit Address</a>
													</address>
												</div>
												<div class="col-lg-6 col-md-6">
													<h4>Default Shipping Address</h4>
													<address>You have not set a default shipping address.<br>
														<a href="http://demo.flytheme.net/themes/sm_stationery/customer/address/edit/">Edit Address</a>
													</address>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>						
				</div>						
			</div>
		</div>
	</div>
</div>