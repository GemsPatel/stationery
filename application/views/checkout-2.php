     		
<div class="checkout_delivery clearfix">
	<?php
		$res;
		if(isset($customer_id) && (int)$customer_id!=0)
		{
			$res = getShippAddress( $customer_id );
		}
							
		$num_add =0;
		$curr_add_id = 0;
		if( !empty($res) && is_array($res) && sizeof($res)>0 )
		{
			$num_add = sizeof($res);
		}
	?>
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <h3 class="checkout_title"> <?php echo getLangMsg("prv_add");?>(<?php echo $num_add; ?>)</h3>
        
            <?php
            if($num_add>0):
                foreach($res as $k=>$ar):
            ?>
            <div class="billing_information f-none margbot20">
                <div class="billing_information_content margbot10">
                    <span class="font-18"><?php echo $ar['customer_address_firstname'] . " " . $ar["customer_address_lastname"];?></span>
                    <span><?php echo @$ar['customer_address_address'] ?></span>
                    <span><?php echo @$ar['cityname'] ?>, <?php echo @$ar['pincode'] ?></span>
                    <span><?php echo @$ar['state_name'] ?>, <?php echo @$ar['country_name'] ?></span>
                    <span><?php echo @$ar['customer_address_phone_no'] ?></span>
                </div>
                
                <label class="hideMobile">
                    <i class="fa fa-edit"></i>&nbsp;
                    <a class="cursor font-14" onclick="applyAddress('shipp',<?php echo $ar['customer_address_id'] ?>,'login_loading_img_ship','shipp_div',0,$('#shippingAddress_<?php echo $ar['customer_address_id'] ?>'),true)"><?php echo getLangMsg("edit");?></a>
                </label>
                
                <input id="shippingAddress_<?php echo $ar['customer_address_id'] ?>" type="checkbox" name="shippingAddress"
                       onclick="applyAddress('shipp',<?php echo $ar['customer_address_id'] ?>,'login_loading_img_ship','shipp_div',1,this,false)" 
                       <?php echo (($ar['customer_address_id']==$customer_shipping_address_id)?'checked="checked"':'');?> 
                       class="shipp_che" hidden />
                <label for="shippingAddress_<?php echo $ar['customer_address_id'] ?>"><?php echo getLangMsg("selshipadd");?></label>
    
                <input id="billingAddress_<?php echo $ar['customer_address_id'] ?>" type="checkbox" name="billingAddress"
                       onclick="applyAddress('bill',<?php echo $ar['customer_address_id'] ?>,'login_loading_img_bill','bill_div',1,this,false)" 
                       <?php echo (($ar['customer_address_id']==$customer_billing_address_id)?'checked="checked"':'');?> 
                       class="bill_che" hidden />
                <label for="billingAddress_<?php echo $ar['customer_address_id'] ?>"><?php echo getLangMsg("sel_bill_add");?></label>
                
            </div>        
                <?php
                endforeach;
            endif;
            ?>        
        </div>
        
        <div class="col-lg-9 col-md-9">
            <form name="add_form" id="add_form" class="checkout_form clearfix">
                <h3 class="checkout_title"><b><?php  echo getLangMsg("shipadd");?></b></h3>
                <span id="login_loading_img_ship" style="display:none;">
                    <img style="padding:5px;" src="<?php echo asset_url('images/preloader-white.gif'); ?>" alt="loader">
                </span>
                
                <div id="shipp_div">
                    <?php
                        $data['customer_address_id']=$customer_shipping_address_id;
                        $data['class']="shipp";
                        $data['is_read_only'] = ($customer_shipping_address_id!=0)?true:false;
                        $this->load->view('elements/customer_address',$data);
                    ?>
                </div>
                
                <div class="clear"></div>
                <div class="clear"></div>
                
                <h3 class="checkout_title"> <b><?php echo getLangMsg("billadd");?></b></h3>
                <input name="same_as_billing_address" id="same_as_billing_address" type="checkbox" onclick="showHideDiv(this)" checked="checked" value="1">
                <label for="same_as_billing_address"><?php echo getLangMsg("sam_ship_add");?></label>
                <span id="login_loading_img_bill" style="display:none;">
                    <img style="padding:5px;" src="<?php echo asset_url('images/preloader-white.gif'); ?>" alt="loader">
                </span>
           
                <div id="bill_div" style="display:none;">
                    <?php
                        $data['customer_address_id']=$customer_billing_address_id;
                        $data['class']="bill";
                        $data['is_read_only'] = ($customer_billing_address_id!=0)?true:false;
                        $this->load->view('elements/customer_address',$data);
                    ?>
                </div>
                
            </form>    
        </div>
	
        <a class="btn active pull-right checkout_block_btn marg-0" href="javascript:void(0);" onclick="applyShipInfo('login_loading_img_proceed_ship')"><?php echo getLangMsg("cont");?></a>
        <span id="login_loading_img_proceed_ship" class="fright" style="display:none;">
            <img style="padding:5px;" src="<?php echo asset_url('images/preloader-white.gif'); ?>" alt="loader">
        </span>
           
        <div style="clear:right;"></div>
        <a class="btn active pull-right checkout_block_btn" href="<?php echo site_url('checkout?act=uinfo') ?>" ><?php echo getLangMsg("back");?></a>
    
    </div>
	
</div>
