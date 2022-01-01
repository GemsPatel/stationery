		
		<!-- ACCOUNT PAGE -->
		<section class="faq_page"> 
			
			<!-- CONTAINER -->
			<div class="container account">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
						
                        <!-- LEAVE A COMMENT -->
                        <form action="" method="post" enctype="multipart/form-data">
							<div id="comment_form" data-appear-top-offset='-100' data-animated='fadeInUp'>
								<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo pgTitle(end($this->uri->segments)); ?></h2>
	                            <?php
	                            if(!empty($listArr)):
                                    if(is_array(@$listArr) && sizeof($listArr)>0):
                                ?>
	                            <table class="shop_table type1">
	                                <thead>
	                                    <tr>
	                                        <th class="product-thumbnail center"><?php echo getLangMsg("no").".";?></th>
	                                        <th class="product-price" colspan="2"><?php echo getLangMsg("nm");?></th>
	                                        <th class="product-name"><?php echo getLangMsg("address");?></th>
	                                        <th class="product-quantity"><?php echo getLangMsg("phone").getLangMsg("no").".";?></th>
	                                        <th class="product-remove center"><?php  echo getLangMsg("action");?></th>
	                                    </tr>
	                                </thead>
	                                <?php		
	                                	foreach($listArr as $k=>$ar):
	                                ?>
	                                <tbody>
	                                    <tr class="cart_item" id="row_<?php echo $ar['customer_address_id'];?>">
	                                        <td class="product-thumbnail center"><?php echo $k + 1;?></td>
	                                        <td class="product-price" colspan="2"><?php echo $ar['customer_address_firstname'];?></td>
	                                        <td class="product-name"><?php echo $ar['customer_address_address'];?></td>
	                                        <td class="product-quantity"><?php echo $ar['customer_address_phone_no'];?></td>
	                                        <td class="product-remove center">
	                                        	<a href="<?php echo site_url('account/edit-address?add_id='._en($ar['customer_address_id'])) ?>" title="Edit address"><i class="fa fa-edit"></i></a>
	                                            <a href="javascript:void(0);" onclick="deleteAddress(<?php echo $ar['customer_id'] ?>,<?php echo $ar['customer_address_id'] ?>)" title="Delete Address"><i class="fa fa-trash-o"></i></a>
	                                        </td>
	                                    </tr>
	                                </tbody>
	                                <?php
										endforeach;
									?>
	                            </table>
	                            <?php	
	                            	endif;
                                 else:
								?>
								<!-- <table>
									<tr class="cart_item">
                                    	<td class="product-thumbnail right"><a><i class="fa fa-thumbs-o-down"></i></a>
                                        <td class="product-price" colspan="3"><a>You haven't inserted any address yet.</a></td>
                                    </tr> 
                                </table>-->
                                <h3 class="product-price cart_item"><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp; <?php echo getLangMsg("cart_item");?></h3>	
                                <?php
                                 	endif;
								?>
	                            <div class="right">
									<a class="btn" href="<?php echo site_url('account/add-address') ?>"><i class="fa fa-plus-circle"></i><?php echo getLangMsg("new_add");?> </a>
	                            </div>
	                        
							</div>
						</form>	
						<!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->
        

