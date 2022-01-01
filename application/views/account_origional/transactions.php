		
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
							<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?> </h2>
                            <div class="fright"><?php echo getLangMsg("cur_bal").":";?> <b class="chan_curr"><?php echo lp($customer_account_manage_balance); ?></b></div>
                            <table class="shop_table type1">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail center"><?php echo getLangMsg("dt");?></th>
                                        <th class="product-price"><?php echo getLangMsg("crdt");?></th>
                                        <th class="product-price"><?php echo getLangMsg("dbt");?></th>
                                        <th class="product-name"><?php echo getLangMsg("disc");?></th>
                                        <!-- <th class="product-subtotal"><?php //echo getLangMsg("bal");?></th>-->
                                    </tr>
                                </thead>
                               <tbody>
                                <?php
                                	if(is_array(@$listArr) && sizeof($listArr)>0):
                                		foreach($listArr as $k=>$ar):
                                ?>
	                                    <tr class="cart_item">
	                                        <td class="product-thumbnail center product-name"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['customer_account_manage_created_date']);?></td>
	                                        <td class="product-price"><?php echo lp($ar['customer_account_manage_credit'])?></td>
	                                        <td class="product-price"><?php echo lp($ar['customer_account_manage_debit'])?></td>
	                                        <td class="product-name"><?php echo hecam_transactionTypeDesc( $ar["customer_account_manage_entry_type"] ) . ( !empty($ar['cam_note']) ? " : ".$ar['cam_note'] : "" )?></td>
	                                        <!-- <td class="product-subtotal"><?php //echo $ar['customer_account_manage_balance'];?></td> -->
	                                    </tr>
	                                    <?php
                                    	endforeach;
                                    else:	
									?>  
                                     	<tr class="cart_item">
                                     		<td class="product-thumbnail right"><a><i class="fa fa-thumbs-o-down"></i></a></td>
                                     		<td class="product-name"><a><?php echo getLangMsg("n_tran");?></a></td>
                                     	</tr>               
                                    <?php 
                                    endif;
                                ?>
                                </tbody>
                            </table>

                        
						</div><!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        

