
		<!-- ACCOUNT PAGE -->
		<section class="faq_page">
			
			<!-- CONTAINER -->
			<div class="container account order_history">
			
				<!-- ROW -->
				<div class="row">
					
					<!-- INNER BLOCK -->
					<div class="col-lg-9 col-md-9 col-sm-9 padbot30">
						
                        <!-- LEAVE A COMMENT -->
						<div id="comment_form" data-appear-top-offset='-100' data-animated='fadeInUp'>
							<h2><i class="fa fa-smile-o"></i>&nbsp; <?php echo 'Order History'; ?></h2>
                            <?php
                            	$is_order = false;
                                if(! isEmptyArr($listArr)):
                            ?>
                            <table class="shop_table type1">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail center"><?php echo getLangMsg("o_id");?></th>
                                        <th class="product-name"><?php echo getLangMsg("status");?></th>
                                        <th class="product-price"><?php echo getLangMsg("ttl_amt");?></th>
                                        <th class="product-remove center"><?php echo getLangMsg("dt");?></th>
                                        <th class="product-remove center"><?php echo getLangMsg("action");?></th>
                                    </tr>
                                </thead>
                                <?php			
                                	$is_order = true;
                                	//pr($listArr[1]); 
                                	//pr(${'order_details_78'}['data']); die; 
                                    foreach($listArr as $k=>$ar):
                                   		$res = executeQuery("SELECT order_status_name 
				    										 FROM order_tracking t INNER JOIN order_status s ON s.order_status_id=t.order_status_id
															 WHERE t.order_id=".$ar['order_id']." ORDER BY order_tracking_id DESC LIMIT 1");
										if(!empty($res)):					
								?>
                                <tbody>
                                	<tr class="cart_item cursor toggle-btn" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $ar['order_id'];?>" id="row_<?php echo $ar['order_id'];?>">
                                        <td class="product-thumbnail center"><?php echo $ar['order_id'];?></td>
                                        <td class="product-name"><?php echo $res[0]['order_status_name'];?></td>
                                        <td class="product-price"><?php echo lp($ar['order_total_amt']);?></td>
                                        <td class="product-remove"><?php echo formatDate("d-m-Y <b>h:i A</b>",$ar['order_created_date']);?></td>
                                        <td class="product-remove center">
                                        	<a onclick="redirect(this,'<?php echo site_url('account/order-tracking?oid='._en($ar['order_id']));?>');return false;" title="Track this order" alt="view" src="<?php echo asset_url('images/view_icon.png')?>"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>                                    
                                </tbody>                                
                                <tbody id="collapse_<?php echo $ar['order_id'];?>" class="collapse out">
                                	<tr class="cart_item">
                                    	<td colspan="5">
                                    		<?php $this->load->view('account/order_details',${'order_details_'.$ar['order_id']}['data']);?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php
										  endif;
									endforeach;
								?>
                            </table>
                            <?php		  
                            	else:
                            ?>
                            <table>
                            	<tr>
                            		<td class=""><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp;You haven't placed any order yet.</td>
                                </tr>
                            </table>	
                            <?php
                                endif;
							?>
                            <?php if($links){?>
                            	<div class="pagination">
                                	<div class="links"><?php echo $links;?></div>
                                </div>
                             <?php }?>
                        
						</div><!-- //LEAVE A COMMENT -->
                        					
					</div><!-- //INNER BLOCK -->
					
					
					<!-- SIDEBAR -->
					<?php $this->load->view('account/rightbar_box') ?>
                    <!-- //SIDEBAR -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //ACCOUNT PAGE -->

<script language="javascript">
function redirect(obj,url)
{
	window.location.href = url;
}
</script>