<form name="paymentf" id="paymentf" action="<?php echo site_url('checkout/payment');?>" method="post">

	<div class="checkout_payment clearfix">
		<div class="payment_method padbot70">
			<h3 class="checkout_title"><?php echo getLangMsg("pay_meth");?></h3>
	
			<?php 
				if( isImportDuty() )
				{
					$addressArr = getAddress($customer_address_id);
					if(!empty($addressArr['country_id']))
					{
						$importDutyArr = exeQuery(" SELECT country_import_duty, country_import_duty_desc FROM country WHERE country_id = '".$addressArr['country_id']."' ");
					}
				}
				
				$default_payment_method_id = 4;
			?>
			
			
				<ul class="clearfix">
				
					<?php if( isIntranetIp() ):?>
					<li>
						<input id="payment_method_id_4" type="radio" name="payment_method_id" value="4" <?php echo ((@$_POST['payment_method_id']=='4')? 'checked' : '')?> />
						<label for="payment_method_id_4"><?php echo getLangMsg("c_on_del");?></label><br><img src="images/icon-cash-on-delivery.png" alt="COD" title="COD" />
					</li>
					<?php endif;?>
					<li>
						<input id="payment_method_id_5" type="radio" name="payment_method_id" value="5" <?php echo ((@$_POST['payment_method_id']=='5')? 'checked' : '')?>/>
						<label for="payment_method_id_5"><?php echo getLangMsg("payu");?></label><br><img src="images/icon-payu.jpg" alt="payU" title="payU" />
					</li>
					<?php
						if( $resArr['order_total_amt'] <=  getCustBalance( $resArr['customer_id'] ))
							$balance = 1;
						else 
							$balance = 0;
					?>
					<li <?php echo (($balance == '1')? '' : '')?>>
						<input <?php echo (($balance == '1')? '' : 'disabled="disabled"')?> id="payment_method_id_6" type="radio" name="payment_method_id" value="6" <?php echo ((@$_POST['payment_method_id']=='6')? 'checked' : '')?>/>
						<label for="payment_method_id_6"><?php echo getLangMsg("bal");?>: <span class="chan_curr"><?php echo lp($customer_account_manage_balance);?> <?php echo (($balance == '1')? '' : '(Insufficient Balance)')?></span></label>
                        <br>
						<img src="images/foot_logo.png" alt="Your Balance" title="Your Balance" />
					</li>
					
				</ul>
				<div class="checkout_title error_msg red martop15 pos-a"><?php echo (@$error)?form_error('payment_method_id'):''; ?> </div>
				
			<?php if( isImportDuty() && !empty($importDutyArr) && $importDutyArr['country_import_duty'] !=0 && ( MANUFACTURER_ID == 7 ) ): ?>
	        	<p class="checkout_title"><?php echo getLangMsg("obl_pay");?> <?php echo $importDutyArr['country_import_duty']; ?>% <?php echo $importDutyArr['country_import_duty_desc']; ?><?php echo getLangMsg("regulation");?><?php echo $addressArr['country_name'] ?>.</p>
	       	<?php endif; ?>
			
		</div>
		
		
		<div class="credit_card_number padbot80">
			<h3 class="checkout_title"><?php echo getLangMsg("o_info");?></h3>
            <table class="bag_total">
                 <tbody>
                 	
                 	<?php if(!empty($resArr['coupon_id'])): ?>
                    <tr class="cart-subtotal clearfix">
                        <th><?php echo getLangMsg("sttl");?></th>
                        <td><?php echo lp($resArr['order_subtotal_amt']);?></td>
                    </tr>                    
                    <tr class="coupon shipping clearfix">
                        <th><?php echo getLangMsg("disc_amt");?></th>
                        <td><?php echo lp($resArr['order_discount_amount']);?></td>
                    </tr>
                    <?php endif; ?>   
                 	
                 	<?php
	        		if( isset( $resArr["other_charges"] ) ):
	        		?>
                    <tr class="cart-subtotal clearfix">
                        <th><?php echo getLangMsg("sttl");?></th>
                        <td><?php echo lp($grand_total);?></td>
                    </tr>
                    <?php
						foreach ( $resArr["other_charges"] as $k=>$ar ):
							$grand_total += $ar["value"];	
					?>
                    <tr class="shipping clearfix">
                        <th><?php echo pgTitle( $ar["name"] )?></th>
                        <td><?php echo lp($ar["value"]);?></td>
                    </tr>
                    <?php 		
						endforeach;
					endif;
	        		?>
                    
                    <tr class="total clearfix">
                        <th><?php echo getLangMsg("ttl");?></th>
                        <td><?php echo lp($grand_total);?></td>
                    </tr>
                    
                    <tr class="total clearfix">
	                	<td class="pull-left">
							<?php 
                                $tableName = ( MANUFACTURER_ID != 7 ) ? 'article_cctld' : 'article';
                                $articleAlias1 = getField('article_alias', $tableName, 'article_key', 'TERMS_CONDITION'); 
                            ?>
		                	<input type="checkbox" id="agree" class="shipp_che" name="agree" value="1" <?php echo ((@$_POST['agree']=='1')? 'checked' : '')?>/>
							<label for="agree" class="font-12 b-none">I agree to the&nbsp;<a target="_blank" class="terms_condition" href="<?php echo site_url('terms-conditions') ?>" ><?php echo getLangMsg("tc");?></a></label>
							<div class="error_msg margtop5 red"><?php echo (@$error)?form_error('agree'):''; ?> </div>
	                	</td>
	                </tr>
                    
            	</tbody>
            </table>
			
		</div>
	        
		
		<div class="clear"></div>
		<input type="submit" value="Proceed" name="proceed" id="proceed" class="hide">
		<a class="btn active pull-right checkout_block_btn" href="javascript:void(0);" onclick="$('#proceed').click(); $('#proceed').prop('disabled',true); "><?php echo getLangMsg("cont");?></a>
		   
		<div class="clear"></div>
		<a class="btn active pull-right checkout_block_btn" href="<?php echo site_url('checkout?act=sinfo') ?>" ><?php echo getLangMsg("back");?></a>
	
	</div>

</form>