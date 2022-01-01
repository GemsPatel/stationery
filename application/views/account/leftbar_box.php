<style>
.block-account .block-content li.current strong { color: #f06292; }
</style>
<div id="ytech_left" class="col-lg-3 col-md-3 col-left sidebar">
	<div class="block block-account">
		<div class="block-title">
			<strong>
				<span>My Account</span>
			</strong>
		</div>
		
		<?php $current = $this->uri->segment(2); ?>
		
		<div class="block-content">
			<ul>
				<li class="<?php echo ( $current == "" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('account')?>">
						<?php echo ( $current == "" ) ? '<strong>' : ''; ?>
							Account Dashboard
						<?php echo ( $current == "" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="<?php echo ( $current == "edit-account" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('account/edit-account')?>">
						<?php echo ( $current == "edit-account" ) ? '<strong>' : ''; ?>
							Account Information
						<?php echo ( $current == "edit-account" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="<?php echo ( $current == "address-books" || $current == "edit-address" || $current == "save-address" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('account/address-books')?>">
						<?php echo ( $current == "address-books" || $current == "edit-address" || $current == "save-address" ) ? '<strong>' : ''; ?>
							Address Book
						<?php echo ( $current == "address-books" || $current == "edit-address" || $current == "save-address" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="<?php echo ( $current == "" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('sm/myOrder')?>">
						<?php echo ( $current == "myOrder" ) ? '<strong>' : ''; ?>
							My Orders
						<?php echo ( $current == "myOrder" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="<?php echo ( $current == "" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('sm/billAgre')?>">
						<?php echo ( $current == "billAgre" ) ? '<strong>' : ''; ?>
							Billing Agreements
						<?php echo ( $current == "billAgre" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="hide"><a href="<?php echo site_url('sm/recProfile')?>">Recurring Profiles</a></li>
				<li class="hide"><a href="<?php echo site_url('sm/productReview')?>">My Product Reviews</a></li>
				<li class="hide"><a href="<?php echo site_url('sm/tags')?>">My Tags</a></li>
				<li class="<?php echo ( $current == "" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('sm/wishllist')?>">
						<?php echo ( $current == "wishllist" ) ? '<strong>' : ''; ?>
							My Wishlist
						<?php echo ( $current == "wishllist" ) ? '</strong>' : ''; ?>
					</a>
				</li>
				<li class="hide"><a href="<?php echo site_url('sm/application')?>">My Applications</a></li>
				<li class="last <?php echo ( $current == "newsletter" ) ? 'current' : ''; ?>">
					<a href="<?php echo site_url('account/newsletter')?>">
						<?php echo ( $current == "newsletter" ) ? '<strong>' : ''; ?>
							Newsletter Subscriptions
						<?php echo ( $current == "newsletter" ) ? '</strong>' : ''; ?>
					</a>
				<li class="last hide"><a href="<?php echo site_url('sm/products')?>">My Downloadable Products</a></li>
			</ul>
		</div>
	</div>
</div>