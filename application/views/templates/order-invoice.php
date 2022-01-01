<script type="text/javascript" src="<?php echo asset_url('js/admin/jquery/jquery-1.7.1.min.js');?>"></script>

<div class="print">
<tr>
  <td colspan="2">
    <table style="border-collapse: collapse; width: 640px; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;font-family:Verdana, Geneva, sans-serif;">
      <thead>
      	<tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px; "><img alt="<?php echo baseDomain() ?>" src="<?php echo asset_url('images/logo.png'); ?>" style="width: 200px;"/> <br /><br />
          	<?php echo getLangMsg("s/g/i")?><br />
          </td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; vertical-align:top; padding: 11px;"><b><?php echo getLangMsg("dt")?>: </b> <?php echo formatDate("d-m-Y <b>h:i A</b>",$order_created_date);?><br /><br />
            <b><?php echo getLangMsg("ino")?>: </b> <?php echo $invoice_number;?><br />
            <b><?php echo getLangMsg("mop")?>: </b> <?php echo $payment_mode;?><br />
            <br />
            <!--<b>VAT/TIN: </b> 24221502930<br />
            <b>CST: </b> 24721502930<br />
            <b>PAN: </b> AAHCP1484F<br />-->
          </td>
        </tr>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 3px;"><b><?php echo getLangMsg("billadd")?></b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 3px;"><b><?php echo getLangMsg("shipadd")?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
		  
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
          <?php
          	if(isset($shipp_add) && is_array($shipp_add) && sizeof($shipp_add)>0):
		  ?>	
            <b><?php echo getLangMsg("nm")?>: </b> <?php echo $shipp_add['customer_name'] ?><br />
            <b><?php echo getLangMsg("email")?>: </b> <?php echo $customer_emailid;?><br />
            <b><?php echo getLangMsg("phone")?>: </b> <?php echo $shipp_add['customer_address_phone_no'] ?><br />
            <b><?php echo getLangMsg("address")?>: </b> <?php echo $shipp_add['customer_address_address'] ?><br />
            <?php echo $shipp_add['cityname'] ?>, <?php echo $shipp_add['country_name'] ?> <br />
            <b><?php echo getLangMsg("pin")?>: </b> <?php echo $shipp_add['pincode'] ?><br />
          <?php
          	else:
		  		echo getLangMsg("anau");
          	endif;
		  ?>  
          </td>
          
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
          <?php
          	if(isset($bill_add) && is_array($bill_add) && sizeof($bill_add)>0):
		  ?>	
            <b><?php echo getLangMsg("nm")?>: </b> <?php echo $bill_add['customer_name'] ?><br />
            <b><?php echo getLangMsg("email")?>: </b> <?php echo $customer_emailid;?><br />
            <b><?php echo getLangMsg("phone")?>: </b> <?php echo $bill_add['customer_address_phone_no'] ?><br />
            <b><?php echo getLangMsg("address")?>: </b> <?php echo $bill_add['customer_address_address'] ?><br />
            <?php echo $bill_add['cityname'] ?>, <?php echo $bill_add['country_name'] ?><br />
            <b><?php echo getLangMsg("pin")?>e: </b> <?php echo $bill_add['pincode'] ?><br />
          	<?php
          	else:
		  		echo getLangMsg("anau");
          	endif;
		  ?>  
          </td>
        </tr>
      </tbody>
    </table>
    <table style="border-collapse: collapse; width: 640px; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; border-right: 1px solid #DDDDDD; margin-bottom: 20px;font-family:Verdana, Geneva, sans-serif">
      <tbody>
        <tr>
          <td colspan="2">
          <table style="border-collapse: collapse;">
              <thead>
                <tr>
                  <td width="350" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 3px; color: #222222;"><?php echo getLangMsg("desc")?></td>
                  <td width="150" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: left; padding: 3px; color: #222222;"><?php echo getLangMsg("up")?></td>
                  <td width="40" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: center; padding: 3px; color: #222222;"><?php echo getLangMsg("q")?></td>
                  <td width="100" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #efefef; font-weight: bold; text-align: right; padding: 3px; color: #222222;"><?php echo getLangMsg("price")?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="4">
                  <table style="border-bottom: 1px dotted #DDDDDD; border-collapse: collapse; font-size:14px;">
                      <tbody>
                      <?php 
                      if(isset($resOrdDet) && is_array($resOrdDet) && sizeof($resOrdDet)>0):
							foreach($resOrdDet as $k=>$ar):
// 						pr($ar);
						if( empty($ar['product_type']) || $ar['product_type'] == 'prod' )
						{
							$angle_in = ANGLE_IN;
							if($ar['product_accessories']=='BAN' || $ar['product_accessories']=='BRA')
							{
								$angle_in = 0;	
							}

// 							$prodUrl = getProductUrl($ar['product_id'],$ar['product_price_id'],$ar['product_alias'],$ar['category_id']);
							
// 							$imagefolder = getProdImageFolder($ar['product_generate_code'],$ar['product_price_id'],$ar['product_image']);
// 							$product_images = fetchProductImages($imagefolder);	//images for particular selection

							$product_images = front_end_hlp_getProductImages($ar['product_generate_code'], $ar['product_price_id'], $ar["product_sku"], $ar['product_generated_code_info']);
					  ?>
                        <tr>
                          <td width="350" style="border-bottom: 1px solid #DDDDDD;">
						  <span style="float:left; margin-right:5px;"><img width="50px" height="50px" src="<?php echo load_image( $product_images[ $ar["product_angle_in"] ] )?>" alt="<?php echo $ar["product_name"];?>" title="<?php echo $ar["product_name"];?>" /></span>
                          <span>
						    <?php echo $ar['product_name']; ?><br>
                            <small><?php echo getLangMsg("ic")?>: <?php echo $ar['product_generated_code_displayable'];?></small><br>
                            <?php
								if( $ar['product_final_weight'] != '0 (gm)' && $ar['product_final_weight'] != '0.0 (gm)' ):
							?>
	                            <small><?php echo getLangMsg("pw")?>: <?php echo $ar['product_final_weight'];?></small><br> 
                            <?php
								endif;
							?>
                          </span>
                          </td>
                          <td width="150" style="border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['order_details_amt'])?></td>
                          <td width="40" style="text-align:center; border-bottom: 1px solid #DDDDDD;"><?php echo $ar['order_details_product_qty']?></td>
                          <td width="100" class="chan_curr" style="font-size: 12px; text-align: right; border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['order_details_amt'] * $ar['order_details_product_qty'])?></td>
                        </tr>
                	    <?php
						}
						else if( $ar['product_type'] == 'sol' )
						{
							$angle_in = ANGLE_IN;
							if($ar['product_accessories']=='BAN' || $ar['product_accessories']=='BRA')
							{
								$angle_in = 0;	
							}
							$imagefolder = getProdImageFolder($ar['product_generate_code'],$ar['product_price_id']);
							$product_images = fetchProductImages($imagefolder);			//images for particular selection
						?>
                        <tr>
                          <td width="350">
						  <span style="float:left; margin-right:5px;"><img src="<?php echo load_image(@$ar['diamond_shape_icon']);?>" height="50" width="50" alt="<?php echo $ar['diamond_shape_name']; ?>" /></span>
                          <span>
						    <?php echo $ar['diamond_shape_name']; ?><br>
                            <small>Item Code: <?php echo $ar['dp_rapnet_lot_no'];?></small><br>
                          </span>
                          </td>
                          <td width="150"><?php echo lp($ar['dp_price'])?></td>
                          <td width="40" style="text-align:center;"><?php echo $ar['order_details_product_qty']?></td>
                          <td width="100" class="chan_curr" style="font-size: 12px; text-align: right;"><?php echo lp($ar['dp_price'] * $ar['order_details_product_qty'])?></td>
                        </tr>
                        <tr>
                          <td width="350" style="border-bottom: 1px solid #DDDDDD;">
						  <span style="float:left; margin-right:5px;"><img src="<?php echo load_image(@$product_images[$angle_in]);?>" height="50" width="50" alt="<?php echo $ar['product_name']; ?>" /></span>
                          <span>
						    <?php echo $ar['product_name']; ?><br>
                            <small><?php echo getLangMsg("ic")?>: <?php echo $ar['product_generate_code'];?></small><br>
                            <?php
								if( $ar['product_final_weight'] != '0 (gm)' && $ar['product_final_weight'] != '0.0 (gm)' ):
							?>
	                            <small><?php echo getLangMsg("pw")?>: <?php echo $ar['product_final_weight'];?></small><br> 
                            <?php
								endif;
							?>
                          </span>
                          </td>
                          <td width="150" style="border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['order_details_amt'])?></td>
                          <td width="40" style="text-align:center; border-bottom: 1px solid #DDDDDD;"><?php echo $ar['order_details_product_qty']?></td>
                          <td width="100" class="chan_curr" style="font-size: 12px; text-align: right; border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['order_details_amt'] * $ar['order_details_product_qty'])?></td>
                        </tr>
									
						<?php 
						}
						else if( $ar['product_type'] == 'dia' )
						{
						?>
                        <tr>
                          <td width="350" style="border-bottom: 1px solid #DDDDDD;">
						  <span style="float:left; margin-right:5px;"><img src="<?php echo load_image(@$ar['diamond_shape_icon']);?>" height="50" width="50" alt="<?php echo $ar['diamond_shape_name']; ?>" /></span>
                          <span>
						    <?php echo $ar['diamond_shape_name']; ?><br>
                            <small><?php echo getLangMsg("ic")?>: <?php echo $ar['dp_rapnet_lot_no'];?></small><br>
                          </span>
                          </td>
                          <td width="150" style="border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['dp_price'])?></td>
                          <td width="40" style="text-align:center;border-bottom: 1px solid #DDDDDD;"><?php echo $ar['order_details_product_qty']?></td>
                          <td width="100" class="chan_curr" style="font-size: 12px; text-align: right; border-bottom: 1px solid #DDDDDD;"><?php echo lp($ar['dp_price'] * $ar['order_details_product_qty'])?></td>
                        </tr>		
						<?php 
						}
								
							endforeach;
						else:
					  ?>
					    <tr>
	                      <td colspan="4"><?php echo getLangMsg("nia")?></td>
                        </tr> 
					  <?php		
                      		endif;
					  ?>
                      </tbody>
                    </table></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td width="77%" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><b><?php echo getLangMsg("sttl")?>:</b></td>
          <td width="23%" style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo lp($order_subtotal_amt)?></td>
        </tr>
        <?php
        	if($order_discount_amount>0):
		?>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><b><?php echo getLangMsg("disc")?>:</b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo lp($order_discount_amount)?></td>
        </tr>
        <?php
        	endif;
		?>
        <tr>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">
          
          <?php echo getLangMsg("vat",VAT_CHARGE)?>          
		  	
          &nbsp;&nbsp;<b><?php echo getLangMsg("ttl")?>:</b></td>
          <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"><?php echo lp($order_total_amt)?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top:20px; font-size:12px; text-align:center"><?php echo getLangMsg("que")?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-top:10px; padding-bottom:10px; font-size:12px; text-align:center"><b><?php echo getLangMsg("thnk")?></b></td>
        </tr>
      </tfoot>
    </table>
    </td>
</tr>
</div>
<input type="button" name="Print" value="Print" onclick="printInvoice();" />
<script type="text/javascript">
	function printInvoice() 
    {
		var html = $('.print').html();
        var mywindow = window.open('', '', '');
        /*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write(html);

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>
