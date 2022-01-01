<div class="container">
	<div class="row">					
		<?php $this->load->view('elements/leftbar-filter');?>					
		<div class="col-lg-9 col-md-9 col-main">
			<form name="comparef" id="comparef" method="get" action="#">
		        <div id="loading" style="margin-bottom: 10px; text-align: center; display: none;">
		            <img class="v-middle" alt="" src="http://demo.flytheme.net/themes/sm_stationery/skin/frontend/sm-stationery/default/images/loading-qv.gif">
		        </div>
		        <?php 
					$records = (int)$total_records;
					$per_page = PER_PAGE_FRONT;
					if($total_records>PER_PAGE_FRONT):
				?>
					<script type="text/javascript">
						var is_records = true;
					</script>
				<?php
					else:
				?>
					<script type="text/javascript">
						var is_records = false;
					</script>
			    <?php	
					endif;
				?>
				<div id="catalog-listing">
					<div class="category-products">
						<div class="toolbar">
							<div class="toolbar-inner">
								<div class="sorter">
									<div class="limiter" style="margin-right: 25px;">
										<label>Total Record</label>
										<div class="selector" style="width: 68px;">
											<span style="width: 66px; user-select: none;"><?php echo $records?></span>
										</div> 
									</div>
									<div class="sort-by">
										<label>Sort By</label>
										<div class="selector" style="width: 184px;">
											<select name="sort_by" id="sort_by" onchange="applySort(this)" title="Select sort by">
												<option value=""><?php echo getLangMsg("select");?></option>
								                <option value="most_viewed_asc" <?php echo (@$_GET['sort_by']=='most_viewed_asc')?'selected="selected"':'';?> ><?php echo getLangMsg("pop");?></option>
								                <option value="latest_products_asc" <?php echo (@$_GET['sort_by']=='latest_products_asc')?'selected="selected"':'';?> ><?php echo getLangMsg("new");?></option>
								                <option value="price_asc" <?php echo (@$_GET['sort_by']=='price_asc')?'selected="selected"':'';?> ><?php echo getLangMsg("plth");?></option>
								                <option value="price_desc" <?php echo (@$_GET['sort_by']=='price_desc')?'selected="selected"':'';?> ><?php echo getLangMsg("phtl");?></option>
											</select>
										</div>
									</div>
									<div class="limiter hide">
										<label>Show</label>
										<div class="selector" style="width: 68px;">
											<span style="width: 66px; user-select: none;"> 10 </span>
											<select onchange="">
												<option value="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?limit=5&amp;mode=list"> 5	                </option>
												<option value="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?limit=10&amp;mode=list" selected="selected"> 10	                </option>
												<option value="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?limit=15&amp;mode=list"> 15	                </option>
												<option value="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?limit=20&amp;mode=list"> 20	                </option>
												<option value="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?limit=25&amp;mode=list"> 25	                </option>
											</select>
										</div> 
									</div>
									<p class="view-mode">
										<a href="http://demo.flytheme.net/themes/sm_stationery/fashion-accesories/fresh-suede.html?mode=grid" title="Grid" class="grid">
											<span>Grid</span>
										</a>
										<strong title="List" class="list"><span>List</span></strong>&nbsp;
									</p>
								</div>
								<div class="pager"> </div>
							</div>  
						</div>
						<ul class="products-grid row first last odd">
							<?php 
							if(isset($listArr) && sizeof($listArr)>0)
							{
					    		foreach($listArr as $key=>$val)
					    		{
									$catidArr = explode("|",$val['category_id']);
									if(is_array($catidArr) && sizeof($catidArr)>0)
									{
										$val['category_id'] = $catidArr[0];
									}
									
									$prodUrl = getProductUrl($val['product_id'],$val['product_price_id'],$val['product_alias'],$val['category_id']);
						
// 									$price = "";
// 									if($val['product_discount'] != 0)
// 										$price = '<span class="tovar_price chan_curr tovar_view_price_old fleft">'.lp($val['product_price_calculated_price'],2,true).'</span>&nbsp;
// 												  <span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
// 									else
// 										$price = '<span class="tovar_price chan_curr">'.lp($val['product_discounted_price'],2,true).'</span>';
	
									$product_images = front_end_hlp_getProductImages($val['product_generated_code'], $val['product_price_id'], $val["product_sku"], $val['product_generated_code_info']);
									
									$is_out_of_stock = isProductOutOfStock( $val["product_id"], $val["inventory_type_id"] ); 
									
									?>
									<li class="item">
										<div class="item-inner">
											<div class="box-image">
												<div class="effect-default">
													<a href="<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>" class="product-image">
														<img id="product-collection-image-949" src="<?php echo load_image( $product_images[ $val["product_angle_in"] ] )?>" alt="<?php echo $val["product_name"];?>" style="width: 270px; height: 255px;">
													</a>
												</div>
											</div>
											<div class="box-info">
												<h2 class="product-name">
													<a href="<?php echo $prodUrl ?>" title="<?php echo $val["product_name"];?>"><?php echo char_limit( $val["product_name"], 25 );?></a>
												</h2>
												<p class="no-rating"><a href="<?php echo $prodUrl ?>"></a></p>
												<div class="bs-price">
													<div class="price-box">
														<span class="regular-price">
															<span class="price"><?php echo lp($val['product_price_calculated_price'],2,true);?></span>                                    
														</span>
													</div>
												</div>
												<div class="actions">
													<button type="button" title="Add to Cart" class="button btn-cart <?php echo (($is_out_of_stock)? ' hide' : '')?>" onclick="addProduct(<?php echo $val['product_price_id'];?>,false, '', '<?php echo @$this->cz?>')">
														<i class="fa fa-shopping-cart"></i>
													</button>
													<ul class="add-to-links">
														<li>
															<a title="Add to Wishlist" href="javascript:void(0);" onclick="addWishList(<?php echo $val['product_price_id'];?>)"><i class="fa fa-heart"></i></a>
														</li>
														<li class="hide">
															<a title="Add to Compare" href="" class="link-compare">Compare</a>
														</li>
													</ul>
												</div>
												<a class="sm_quickview_handler hide" title="Quick View" href="<?php echo $prodUrl ?>"></a>
											</div>
										</div>
									</li>
									<?php
					    		}
							}
							?>
						</ul>
						<div class="toolbar-bottom">
							<div class="toolbar">
								<div class="toolbar-inner">
									<div class="pager">
										<div class="pages">
											<ol>
												<?php if( $records > $per_page)
												{
													echo productListPagination($start, $per_page, $total_records, $this->input->server('REQUEST_URI'));
												}?>
											
											</ol>
										</div>
									</div>
								</div>  
							</div>
							<script>
								jQuery(document).ready(function($) {
									$('.sort-by select').uniform();
									$('.limiter select').uniform();
								});
							</script>        
						</div>
					</div>
				</div>
			</form>
			<script type="text/javascript">decorateGeneric($$('ul.products-grid'), ['odd', 'even', 'first', 'last'])</script>
		</div>						
	</div>
</div>