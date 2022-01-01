		
		<!-- TOVAR DETAILS -->
		<section class="tovar_details padbot70">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<!-- ROW -->
				<div class="row">
					
					<?php
					if($this->session->userdata('lType') == 'PC')
					{
					  	$data['related_productsArr'] = @$related_productsArr;
						$data['related_links'] = @$related_links;
						$data['category_id'] = $category_id;
						$data['product_id'] = $product_id;
						$data['product_discounted_price'] = $product_discounted_price;
						$data['angle_in'] = $product_angle_in;
					  	$this->load->view('elements/related_products',$data);
					}
					?>
					
					
					<!-- TOVAR DETAILS WRAPPER -->
					<div itemscope itemtype="http://schema.org/Product" itemref="product_discounted_price_up" class="col-lg-9 col-md-9 tovar_details_wrapper clearfix">
						<div class="tovar_details_header clearfix">
							<h3 class="pull-left" itemprop="name"><b><?php echo $product_name;?></b></h3>
						</div>
						
						<?php
				        	if( isEmptyArr( $product_images ) )
							{
								$product_images = array('');
							}
							//__L/
							$largeImg = load_image( injectDirInImagePath($product_images[$product_angle_in], "", "assets/product/".$product_sku."/") );
						?>
                        
						<!-- CLEARFIX -->
						<div class="clearfix padbot40 martop15">
							<div class="tovar_view_fotos clearfix">
								<div id="slider2" class="flexslider f-none">
									<ul class="slides" id="slides_main">
										<?php
											foreach ($product_images as $k=>$ar):												
											
												if( $k == $product_angle_in ): 
										?>
													<li class="Cloudwebs_zoom" index="<?php echo $k;?>" data-zoom-image="<?php echo load_image( $product_images[$k] );?>">
                                                    	<a href="javascript:void(0);" class="hs_main_img">
                                                    		<?php //echo load_image( $product_images[$k] );?>
															<img itemprop="image" src="<?php echo load_image( $product_images[$k] );?>" alt="<?php echo ucfirst($largeImg)?>" title="<?php echo ucfirst($largeImg);?>"/>
														</a>
													</li>
										<?php
												else:
										?>			
													<li class="Cloudwebs_zoom" index="<?php echo $k;?>"  data-zoom-image="<?php echo load_image( $product_images[$k] );?>">
														<a href="javascript:void(0);" class="hs_main_img" >
															<?php //echo load_image( $product_images[$k] );?>
															<img itemprop="image" src="<?php echo load_image( $product_images[$k] );?>" alt="<?php echo ucfirst($largeImg)?>" title="<?php echo ucfirst($largeImg);?>"/>
														</a>
													</li>
										<?php
												endif;
											endforeach;	
										?>
									</ul>
								</div>
								<div id="carousel2" class="flexslider">
									<ul class="slides" id="slider_images">
										<?php
											if( sizeof( $product_images ) > 1 ):
												foreach ($product_images as $k=>$ar):
										?>
													<li>
														<a href="javascript:void(0);" class="product_detail_slider_image">
															<img src="<?php echo load_image($ar);?>" index="<?php echo $k;?>" alt="<?php echo ucfirst($product_name);?>" title="<?php echo ucfirst($product_name);?>" class="h100" />
														</a>
													</li>
										<?php
												endforeach;
											endif;
										?>		
									</ul>
								</div>
								<script src="<?php echo asset_url('js/jquery.elevatezoom.js')?>" type="text/javascript"></script>
								<script type="text/javascript">
									var img_len = <?php echo sizeof($product_images);?>;
									var up_edge = 0;
									var botoom_edge = 3;

									$(".Cloudwebs_zoom").elevateZoom({easing : true});
								</script>
                                
                                
							</div>
							
							<div class="tovar_view_description">
								<div class="tovar_view_title"><?php echo char_limit($product_short_description, 30)?></div>
								<div class="tovar_article" id="product_generated_code_displayable_up"><?php echo $product_generated_code_displayable;?></div>
								
								<?php
									$oldPrice = ($product_discount != 0) ? 'Market Price: <span class="chan_curr" id="product_price_calculated_price">'.lp($product_price_calculated_price).'</span>' : '';
									if( $product_discount != 0 ):
								?>

										<div class="clearfix tovar_brend_price">
											
											<div class="pull-right tovar_view_price price chan_curr" id="product_price_calculated_price">
												<?php echo lp($product_price_calculated_price)?>
											</div>
											
											<div class="tovar_brend"><?php echo getLangMsg("mr_pr");?>: </div>
											
										</div>
										
										<div class="clearfix tovar_brend_price">
									
											<div class="pull-right tovar_view_price price chan_curr"
												itemprop="offers" itemscope itemtype="http://schema.org/Offer" id="product_discounted_price_up">
												<?php echo lp($product_discounted_price,2,true)?>
											</div>
											
											<div class="tovar_brend"><?php echo getLangMsg("or_pr");?>: </div>
											
										</div>
										
								<?php
									else:
								?>

										<div class="clearfix tovar_brend_price">
									
											<div class="pull-right tovar_view_price price chan_curr"
												itemprop="offers" itemscope itemtype="http://schema.org/Offer" id="product_discounted_price_up">
												<?php echo lp($product_discounted_price,2,true)?>
											</div>
											
											<div class="tovar_brend"><?php echo getLangMsg("pr");?>: </div>
											
										</div>
									
								<?php	
									endif;
								?>
								

								<?php
									if( hewr_isQtyInAttributeInventoryCheckWithId( $inventory_type_id ) ):
								?>
										<div class="clearfix tovar_brend_price">
											
											<div class="pull-right tovar_view_price price chan_curr" id="product_discounted_price_tot">
												<?php echo lp($product_discounted_price_tot)?>
											</div>
											
											<div class="tovar_brend"><?php echo getLangMsg("tt");?>: </div>
											
										</div>
								<?php
									
									endif;
								?>		
								
								
								<?php
									/**
									 *
									 */
									foreach ($codeArr as $k=>$ar):
										if( $k >= 2 ):
											$tempA = explode(":", $ar);
											
										
											/**
											 * here $k stands for product_stone_number,
											 * minus it by 2 to reflect stone number in sequence.
											 */
											$k -= 2;
										
											if( $tempA[1] === "JW_CS" || $tempA[1] === "JW_SS1" || $tempA[1] === "JW_SS2" || $tempA[1] === "JW_SSS" ):
												$type = detailDiamondType( $k ); 
												$res = detailDiamonds( $product_id, $type, $k, "C" ); 
												if( !isEmptyArr($res) && sizeof($res) > 1 ):
												
								?>
													<div class="tovar_size_select">
														<div class="clearfix">
															<p class="pull-left"><?php echo $tempA[2];?></p>
															<span></span>
														</div>
								<?php
														foreach($res as $k=>$ar):
															$img_class = "";
															if($ar['diamond_price_id']==$tempA[3])
																$img_class = 'class="active '.$ar['diamond_type_name'].' prod_det '.$type.' diamond" ';
															else
																$img_class = 'class="'.$ar['diamond_type_name'].' prod_det '.$type.' diamond"';
								
								?>							
															
															<a <?php echo $img_class;?> title="<?php echo $ar['diamond_price_name']?>" 
																pid-="<?php echo $ar['diamond_price_id'];?>" type-="<?php echo $type;?>" 
																name="diamond_radio_<?php echo $type;?>" value="<?php echo $ar['diamond_price_id'];?>" 
																href="javascript:void(0);" ><?php echo $ar['diamond_price_name']?></a> 
								<?php
														endforeach;
								?>
													</div>
								<?php 					
												endif;
											elseif( $tempA[1] === "SEL" || $tempA[1] === "CHK" || $tempA[1] === "RDO" ):
												$type = detailDiamondType( $k );
												$res = detailDiamonds( $product_id, $type, $k, "A" );
												if( !isEmptyArr($res) && sizeof($res) > 1 ):
								?>
													<div class="tovar_size_select">
														<div class="clearfix">
															<p class="pull-left"><?php echo $tempA[2];?></p>	
                                                            <span></span>														
														</div>
								<?php
														foreach($res as $k=>$ar):
															$img_class = "";
															if($ar["p".$type."_diamond_shape_id"]==$tempA[3])
																$img_class = 'class="active prod_det '.$type.' attribute" ';
															else
																$img_class = 'class="prod_det '.$type.'" attribute';
								
								?>							
															
															<a <?php echo $img_class;?> title="<?php echo $ar['pa_value']?>" 
																pid-="<?php echo $ar["p".$type."_diamond_shape_id"];?>" type-="<?php echo $type;?>" 
																name="diamond_radio_<?php echo $type;?>" value="<?php echo $ar["p".$type."_diamond_shape_id"];?>" 
																href="javascript:void(0);" ><?php echo $ar['pa_value']?></a> 
								<?php
														endforeach;
								?>
													</div>
								<?php 					
												endif;
											elseif( $tempA[1] === "JW_MTL" ):
												$type = detailDiamondType( $k );
												if( $type === "dyn" )
												{
													$type = "ss".$k;
												}
												
												$res = detailMetals( $product_id );
												/**
												 * JW_MTL: metal Component yet need to be developed, as per design selected by client  
												 */
											//elseif( $tempA[1] === "TXT" ):
												//nothing to do
											endif;
										endif;
									endforeach; 
								?>		

								<div class="tovar_view_btn">
                                	<div class="clearfix">
										<?php
											if(isProductOutOfStock( $product_id, $inventory_type_id )):
												$sold_out = 1;
                                        ?>
                                            	<span class="pull-right in-stock"><i class="fa fa-check-circle red"></i>&nbsp;<?php echo getLangMsg("soldout");?></span>
                                        <?php
                                             else: 
                                             	$sold_out = 0;
                                        ?>
                                                <span class="pull-right in-stock"><i class="fa fa-check-circle green"></i>&nbsp;<?php echo getLangMsg("instock");?></span>
                                        <?php    
                                            endif;
                                        ?>
                                    </div>
                                	
                                	<input type="hidden" name="qty" id="qty" value="<?php echo $qty?>">
									<a href="javascript:void(0);" onclick="addProduct(0,true,pid);" class="add_bag<?php echo (($sold_out)? ' hide' : '')?>"><i class="fa fa-shopping-cart"></i><?php echo getLangMsg("atb");?></a>
									<a href="javascript:void(0);" class="add_lovelist" onclick="addWishList(0, pid)" title="Add Wishlist"><i class="fa fa-heart"></i></a>
								</div>
								
								<div class="tovar_shared pull-right clearfix">
									<p><?php echo getLangMsg("siwf");?></p>
									<ul>
										<li><a class="facebook" href="<?php echo getFbPageUrl()?>" title="<?php echo getLangMsg("fb");?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
										<li><a class="google-plus" href="<?php echo getGooglePageUrl()?>" title="<?php echo getLangMsg("gplus");?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
									</ul>
								</div>
								
							</div>
						</div><!-- //CLEARFIX -->
						
						<!-- TOVAR INFORMATION -->
						<?php 
							$res = executeQuery( " SELECT * FROM product_review where product_id=".$product_id." AND product_review_status = 0 " );
							$count = count($res);
							
						?>
						<div class="tovar_information">
							<ul class="tabs clearfix">
								<li class="current"><?php echo getLangMsg("info")?></li>
								<li><?php echo getLangMsg("dtail")?></li>
								<li><?php echo getLangMsg("rvu");?> (<?php if(isEmptyArr($res)):
														echo "0";
													else:
														echo $count;
													endif;?>)
								</li>
							</ul>

							<div class="box visible clearfix">
                            
                            	<div class="col-lg-8 col-md-8 col-sm-8 padbot30">
                                    <div class="text_iframe">
                                        <div class="widget_categories product_details">
                                        	<h3><?php echo getLangMsg("pdtail");?></h3>
                                            <ul>
                                              <li><span><b><?php echo getLangMsg("pcode");?>: </b> <span id="product_generated_code_displayable"><?php echo $product_generated_code_displayable;?></span></span></li>
                                              <?php
                                              	/**
                                              	 * product specification attributes. 
                                              	 * On 04-06-2015 empty check condition added to show only those attributes which have values.  
                                              	 */
												foreach ($codeArr as $k=>$ar):
													if( $k >= 2 ):
														$tempA = explode(":", $ar);
													
														/**
														 * here $k stands for product_stone_number,
														 * minus it by 2 to reflect stone number in sequence.
														 */
														$k -= 2;
													
														$type = detailDiamondType( $k );
														if( $type === "dyn" )
														{
															$type = "ss".$k;
														}
														
														if( $tempA[1] === "JW_CS" || $tempA[1] === "JW_SS1" || $tempA[1] === "JW_SS2" || $tempA[1] === "JW_SSS" ):
														
															if( !empty( ${"diamond_type_name_".$type."_alias"} ) || !empty( ${"diamond_type_name_".$type} ) ):
											  ?>
                                              					<li><span><b><?php echo $tempA[2]." ".getLangMsg("type");?>: </b> <span id="diamond_type_name_cs_alias"><?php echo ( isset( ${"diamond_type_name_".$type."_alias"} ) ? ${"diamond_type_name_".$type."_alias"} : ${"diamond_type_name_".$type} ); //isset condition added on 04-06-2015, will need to be removed when product folder made dynamic in showProductsDetails and system.?></span> </span></li>
                                              <?php
                                              				endif;
                                              				
                                              				if( !empty( ${"diamond_shape_name_".$type} ) ):
                                              ?>					
                                              					<li><span><b><?php echo $tempA[2]." ".getLangMsg("shape");?>: </b> <span id="diamond_shape_name_cs"><?php echo ${"diamond_shape_name_".$type}; ?></span> </span></li>
                                              					
                                              <?php
                                              				endif;

                                              				
															if($diamond_type_key_cs=='DIAMOND'):
															
																if( !empty( ${"diamond_purity_name_".$type} ) ):
											  ?>
											  						<li><span><b><?php echo $tempA[2]." ".getLangMsg("purity");?>: </b> <span id="diamond_purity_name_cs"><?php echo ${"diamond_purity_name_".$type}; ?></span> </span></li>
											  <?php
											  					endif;
											  					
											  					if( !empty( ${"diamond_color_name_".$type} ) ):
											  ?>						
                                              						<li><span><b><?php echo $tempA[2]." ".getLangMsg("clr");?>: </b> <span id="diamond_color_name_cs"><?php echo ${"diamond_color_name_".$type}; ?></span> </span></li>
                                              						
                                              <?php
                                              					endif;
                                              					
															endif;
		
															$no_of_pcs = ( $type === "cs" ? "product_center_stone_total" : "product_side_stone".$k."_total" );
															$tot_weight = ( $type === "cs" ? "product_center_stone_weight" : "product_side_stone".$k."_weight" );
															if( !empty( ${$no_of_pcs} ) ):
											  ?>
                                              					<li><span><b><?php echo $tempA[2]." No of pcs";?>: </b> <span id="<?php echo $no_of_pcs;?>"><?php echo ${$no_of_pcs}; ?></span> </span></li>
                                              <?php
                                              				endif;
                                              				
                                              				if( !empty( ${$tot_weight} ) ):
                                              ?>					
                                              					<li><span><b><?php echo $tempA[2]." Total Weight";?>: </b> <span id="<?php echo $tot_weight;?>"><?php echo ${$tot_weight}; ?></span> </span></li>
											  <?php 			
											  				endif;

											  				
														elseif( $tempA[1] === "SEL" || $tempA[1] === "CHK" || $tempA[1] === "RDO" ):
															if( !empty( ${"pa_value_".$type} ) ):
											  ?>
                                              					<li><span><b><?php echo $tempA[2];?>: </b> <span id="pa_value_<?php echo $type;?>"><?php echo ${"pa_value_".$type}; ?></span> </span></li>
											  <?php 	
											  				endif;				
														elseif( $tempA[1] === "JW_MTL" ):
											  ?>
                                              				<li><span><b><?php echo $tempA[2]." ".getLangMsg("type");?>: </b> <span id="metal_name"><?php echo $metal_type_name; ?></span> </span></li>
                                              				<li><span><b><?php echo $tempA[2]." ".getLangMsg("purity");?>: </b> <span id="metal_purity_name"><?php echo $metal_purity_name; ?></span> </span></li>
                                              				<li><span><b><?php echo $tempA[2]." ".getLangMsg("clr");?>: </b> <span id="metal_color_name"><?php echo $metal_color_name; ?></span> </span></li>
                                              				<li><span><b><?php echo $tempA[2]." ".getLangMsg("weight");?>: </b> <span id="product_metal_weight"><?php echo $product_metal_weight; ?></span> </span></li>
											  <?php 			
												    	elseif( $tempA[1] === "TXT" ):
// 												    		if( isset($_GET["is_test"]) )
// 												    			print_r($tempA);
												    		
															$txt = ( $type == "cs" ? "product_center_stone_size" : "product_side_stone".$k."_size" );
												   			if( !empty( ${$txt} ) ):
											  ?>
                                              					<li><span><b><?php echo $tempA[2];?>: </b> <span id="<?php echo $txt;?>"><?php echo ${$txt}; ?></span> </span></li>
                                   		<?php
															endif; 			
                                                    	endif;
                                                    
                                                endif;
                                            endforeach; 
                                        ?>
                                              
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
							
							<div class="box">
								<p> <?php echo $product_short_description;?></p>
                                
                                <div class="widget_categories product_details">
                                	<?php echo $product_description;?>
                                </div>
							</div>
							<!-- Gautam Change Code-->
							<div class="box">
								<?php
									$dt["product_id"] = $product_id;
									$dt["res"] = $res;
									$this->load->view("elements/product_review", $dt);
								?>                                
                                						
							</div>
							<!-- //Gautam Change Code -->
						</div><!-- //TOVAR INFORMATION -->
					</div><!-- //TOVAR DETAILS WRAPPER -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section>        
        <!-- //TOVAR DETAILS -->
        
        <!-- BANNER SECTION -->
		<section class="banner_section">
			
			<!-- CONTAINER -->
			<div class="container">
				
				<!-- ROW -->
				<div class="row">
					
					<!-- BANNER WRAPPER -->
					<div class="banner_wrapper">
						<!-- BANNER -->
						<div class="col-lg-12 col-md-12">
							<?php 
								$artImg = fetchRow( "SELECT banner_name,banner_image,banner_link FROM banner WHERE banner_key='FREE_SHIPPING' LIMIT 1 " ); 
							?>
							<a class="banner type4 margbot40" href="<?php echo site_url($artImg['banner_link'])?>" >
								<img src="<?php echo asset_url($artImg['banner_image'])?>" alt="<?php echo $artImg['banner_name'];?>" title="<?php echo $artImg['banner_name'];?>"/>
							</a>
						</div><!-- //BANNER -->
						
					</div><!-- //BANNER WRAPPER -->
				</div><!-- //ROW -->
			</div><!-- //CONTAINER -->
		</section><!-- //BANNER SECTION -->
        
       
        <!-- NEW ARRIVALS -->
        <section class="new_arrivals padbot50">
			<?php 
				$this->load->view('elements/new-arrivals'); 
			?>
        </section>
        
        <!-- //NEW ARRIVALS -->
        
<script type="text/javascript">
var is_component_besed_inv = <?php echo ( hewr_isComponentBasedCheckWithId($inventory_type_id) ? 1 : 0 );?>; 								
var selected_index = <?php echo $product_angle_in;?>; //specifies image index currently active
var pid = '<?php echo $pageToken;?>';		 		  //page token
var is_dynamic_images = <?php echo $is_dynamic_images;?>;
function include_chain(obj)
{
	if( $( obj ).is(":checked") )
	{
		$("#include_chain_span").hide();
		$("#include_chain_price").show();
	}
	else
	{
		$("#include_chain_span").show();
		$("#include_chain_price").hide();
	}
}

</script>

<script type="text/javascript" src="<?php echo asset_url('js/products_details.js?ver=1.1')?>"></script>