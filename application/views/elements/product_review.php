<ul class="comments">
	<?php 
	    if(!isEmptyArr($res)):
			foreach ($res as $k=>$ar): 
					$type = $ar['user_type'];
					$c_id = $ar['customer_id'];
					if($type == "A"):
						$res_cust = executeQuery( " SELECT admin_user_firstname FROM admin_user where admin_user_id=".$c_id." " );
					else:
						$res_cust = executeQuery( " SELECT customer_firstname FROM customer where customer_id=".$c_id." " );
					endif;
				?>
					<li>
						<div class="clearfix">
							<?php 
							if(!empty($res_cust)):
								foreach ($res_cust as $j=>$arr): 
									$res_status = executeQuery( " SELECT product_review_status FROM product_review where customer_id=".$c_id." AND product_review_status = 0 " );
							?>
								<p class="pull-left">
									<strong>
										<a class="cursor">
											<?php 
												if($res_status == true):
													if($type == "A"):
														echo $arr['admin_user_firstname'];
													 else:
													 	echo $arr['customer_firstname'];
													 endif;
												endif;
											?>
										</a>
									</strong>
								</p>
							<?php 
								endforeach;
							endif;
							?>
							<span class="date">
								<?php 
									if($res_status == true):
										echo $ar['product_review_created_date'];
									endif;
								?>
							</span>
							<div class="pull-right rating-box clearfix">
							<?php 
								if($res_status == true):
									$star = 5;
									for( $i=1; $i<=5; $i++ ):
										if( $i <= $ar['product_review_rating'] ): 
								?>
											<i class="fa fa-star"></i>
								<?php
										else:
								?>
										<i class="fa fa-star-o"></i>
								<?php
										endif;
									endfor;
								endif;
							?>
							</div>
						</div>
						<p><?php 
							if($res_status == true):
								echo $ar['product_review_description'];
							endif;
							?>
                        </p>
					</li>	
				<?php
			endforeach;
		else:
		?>
		<li><?php echo getLangMsg("nry");?></li>
		<?php 
		endif;
	?>
</ul>

<?php 
	//change code if(!isLoggedIn()) to if(isLoggeedIn) after success save review data.
	if( isLoggedIn() ):
		?><h3><?php echo getLangMsg("war");?></h3>
		<p><?php echo getLangMsg("war_desc");?></p>
		<form id="review_form" enctype="multipart/form-data" method="post" onsubmit="return saveReview(this);">
			<div class="clearfix">
				
				<textarea id="product_review_description" name="product_review_description" class="marg-0 frm"></textarea>
				<span class="input-notification error" for="product_review_description"></span><br /><br />
				
				<div class="clear"></div>
				<label class="pull-left rating-box-label"><?php echo getLangMsg("yr");?></label>
				<div class="pull-left rating-box clearfix">
					<i class="fa fa-star-o star rstar" id="rat1" data-rat="1"></i>
					<i class="fa fa-star-o star rstar" id="rat2" data-rat="2"></i>
					<i class="fa fa-star-o star rstar" id="rat3" data-rat="3"></i>
					<i class="fa fa-star-o star rstar" id="rat4" data-rat="4"></i>
					<i class="fa fa-star-o star rstar" id="rat5" data-rat="5"></i>
				</div>
				
				<div class="clear"></div>
				<input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id;?>">
				<input type="hidden" name="product_review_rating" id="product_review_rating" value=""> 
				<span class="input-notification error" for="product_review_rating"></span><br />
				
				<input type="submit" class="dark-blue big" value="Submit a review" name="write_review" id="btn_review">
				<span id="review_loading_img" class="hide fright"><img src="<?php echo asset_url('images/preloader-white.gif') ?>" alt="loader" /></span>
				<div id="note" class="fleft"></div>
				
			</div>
		</form>
<?php 
	else:
?>
	<style>
		.link-write-review
		{
			border-color: #f06292;
		    background: #f06292;
		    display: block;
		    color: #fffbfb;
		    font-size: 18px;
		    width: 135px;
		    height: 40px;
		    overflow: hidden;
		    border: 1px solid #898989;
		    padding: 5px;
		}
	
		.link-write-review : hover {
	    color: #fffbfb !important;
	    }
	</style>
		<a class="link-write-review" href="<?php echo site_url('login') ?>" >Write a review</a>
<?php 
	endif;
?>
								