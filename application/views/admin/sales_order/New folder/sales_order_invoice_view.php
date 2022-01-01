<style>
.box-left {
    float: left;
}
.box-right {
    float: right;
}
.box-left, .box-right {
    width: 48.5%;
}
dl.accordion dt, .entry-edit .entry-edit-head {
    background: none repeat scroll 0 0 #6F8992;
    padding: 2px 10px;
}
.entry-edit .entry-edit-head h4 {
    background: none repeat scroll 0 0 transparent;
    color: #FFFFFF;
    float: left;
    font-size: 1em;
    line-height: 18px;
    margin: 0;
    min-height: 0;
    padding: 0;
}
.box, .entry-edit fieldset, .entry-edit .fieldset {
    background: none repeat scroll 0 0 #FAFAFA;
    border: 1px solid #D6D6D6;
}
.box, .entry-edit fieldset, .entry-edit .fieldset {
    margin-bottom: 15px;
    padding: 10px 15px;
}
.form-list td.label {
    width: 200px;
}
.form-list td.label label {
    display: block;
    padding-right: 15px;
    padding-top: 1px;
    width: 185px;
	cursor:auto;
}
.entry-edit .entry-edit-head:after, fieldset li:after, .clear:after {
    clear: both;
    content: ".";
    display: block;
    font-size: 0;
    height: 0;
    line-height: 0;
    overflow: hidden;
}
.list .left{
	padding:3px !important;
}
.order-totals {
    background: none repeat scroll 0 0 #FCFAC9;
    border: 1px solid #D7C699 !important;
    margin-left: auto;
    padding: 12px 0;
    text-align: right;
}
.order-totals td{
	padding: 3px 20px 3px 10px;
}
</style>
<!-- Order Information-->
<div class="box-left">
    <!--Order Information-->
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Order # <?php echo @$order_id; ?></h4>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
            <tr>
                <td class="label"><label>Order Date</label></td>
                <td class="value"><strong><?php echo formatDate('d m, Y <b>h:i a</b>',@$order_created_date);?></strong></td>
            </tr>
            <tr>
                <td class="label"><label>Order Status</label></td>
                <td class="value"><strong><span id="order_status"><?php echo @$order_status_name;?></span></strong></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Account Information-->
<div class="box-right">
    <!--Account Information-->
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Account Information</h4>
            <div class="tools"></div>
        </div>
        <div class="fieldset">            
            <table cellspacing="0" class="form-list">
            <tbody>
            <tr>
                <td class="label"><label>Customer Name</label></td>
                <td class="value"><strong><?php echo @$customer_firstname." ".@$customer_lastname;?></strong></td>
            </tr>
            <tr>
                <td class="label"><label>Email</label></td>
                <td class="value"><a href="mailto:<?php echo @$customer_emailid; ?>"><strong><?php echo $customer_emailid;?></strong></a></td>
            </tr>
            <tr>
                <td class="label"><label>Customer Group</label></td>
                <td class="value"><strong><?php echo @$customer_group_name;?></strong></td>
            </tr>
            </tbody>
            </table>           
        </div>
    </div>
</div>

<div class="clear"></div>
<!-- Billing Address-->
<div class="box-left">
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Billing Address</h4>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
			<?php
                $res = executeQuery("SELECT a.*,s.state_name,c.country_name FROM customer_address a LEFT JOIN state s 
				ON s.state_id=a.customer_address_state_id LEFT JOIN country c
				ON c.country_id=a.country_id WHERE customer_address_id=".$customer_billing_address_id."");
                if(!empty($res)):
            ?>        
            <tr>
                <td class="label">
                <?php echo $res[0]['customer_address_firstname']." ".$res[0]['customer_address_lastname']; ?><br />
                <?php echo $res[0]['customer_address_address']; ?><br />
                <?php echo $res[0]['customer_address_company']; ?><br />
                <?php echo $res[0]['customer_address_city']."-".$res[0]['customer_address_zipcode']; ?><br />
				<?php echo $res[0]['state_name'].", ".$res[0]['country_name']; ?>                
                </td>
            </tr>
			<?php
				endif;
			?>
            </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Shipping Address-->
<div class="box-right">
    <!--Account Information-->
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Shipping Address</h4>
            <div class="tools"></div>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
			<?php
                $res = executeQuery("SELECT a.*,s.state_name,c.country_name FROM customer_address a LEFT JOIN state s 
				ON s.state_id=a.customer_address_state_id LEFT JOIN country c
				ON c.country_id=a.country_id WHERE customer_address_id=".$customer_shipping_address_id."");
                if(!empty($res)):
            ?>        
            <tr>
                <td class="label">
                <?php echo $res[0]['customer_address_firstname']." ".$res[0]['customer_address_lastname']; ?><br />
                <?php echo $res[0]['customer_address_address']; ?><br />
                <?php echo $res[0]['customer_address_company']; ?><br />
                <?php echo $res[0]['customer_address_city']."-".$res[0]['customer_address_zipcode']; ?><br />
				<?php echo $res[0]['state_name'].", ".$res[0]['country_name']; ?>                
                </td>
            </tr>
			<?php
				endif;
			?>
            </tbody>
            </table>
        </div>
    </div>
</div>

<div class="clear"></div>
<!-- Payment Information-->
<div class="box-left">
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Payment Information</h4>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
            <tr>
                <td class="label">
                <?php echo @$payment_method_name; ?><br />
				Order was placed using USD
                </td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Shipping Information-->
<div class="box-right">
    <!--Account Information-->
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Shipping Information</h4>
            <div class="tools"></div>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
			<?php
				$order_total_amt = 0;
                $res = executeQuery("SELECT * FROM shipping_method WHERE shipping_method_id=".$shipping_method_id."");
				$order_total_amt += (int)@$res[0]['shipping_method_free_shipping'] + (int)@$res[0]['shipping_method_handling_charges'];
            ?>        
            <tr>
                <td class="label" style="width:300px">
               	<b><?php echo @$res[0]['shipping_method_name']; ?></b><br>Shipping Charge: <?php echo lp(@$res[0]['shipping_method_free_shipping']); ?>&nbsp;&nbsp;&nbsp;&nbsp;Handling Charge:<?php echo lp(@$res[0]['shipping_method_handling_charges']); ?>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>

<div class="clear"></div>
<!-- Product Ordered-->
<div class="entry-edit">
    <div class="entry-edit-head">
        <h4 class="icon-head head-products">Items Ordered</h4>
    </div>
</div>
<div class="grid">
	<table class="list">
      <thead>
      <tr>
      	<td width="8%" class="left">Product</td>
      	<td width="4%" class="left">SKU</td>
        <td width="10%" class="left">Code</td>
        <td width="5%" class="left">Gift</td>
        <td width="5%" class="left">Metal</td>
        <td width="5%" class="left">Center Stone</td>
      	<td width="5%" class="left">Side Stone 1</td>
      	<td width="5%" class="left">Side Stone 2</td>
        <td width="4%" class="left">Engraving</td>
        <td width="6%" class="right">Price</td>
        <td width="3%" class="right">Qty</td>
        <td width="7%" class="right">Subtotal</td>
        <td width="7%" class="right">Discount</td>
        <td width="7%" class="right">Tax</td>
        <td width="7%" class="right">Total</td>
      </tr>
      </thead>
      <tbody>
	  <?php
			$subTot = 0.0;
			$disTot = 0.0;
			$taxTot = 0.0;
			$rowTot = 0.0;
			$prodTot = 0.0;
      		if(is_array($prodArr) && sizeof($prodArr)>0):
				foreach($prodArr as $k=>$ar):
	  ?>      
      <tr>
      	<td class="left"><?php echo $ar['hid_product_name']; ?></td>
        <td class="left"><?php echo $ar['hid_product_sku']; ?></td>
      	<td class="left"><?php echo $ar['product_generate_code']; ?></td>
        <td class="left"><?php echo getField("gift_name","gift","gift_id",@$ar['gift_id']); ?></td>
      	<td class="left"><?php 
		if(!empty($ar['product_metal_id']))
		{
			$res = $this->db->query("SELECT CONCAT(metal_type_name,' ',metal_purity_name,' ',metal_color_name) as 'metal_price_name' FROM metal_price p INNER JOIN product_metal m 
									 ON m.category_id=p.metal_price_id INNER JOIN metal_type t 
									 ON t.metal_type_id=p.metal_type_id INNER JOIN metal_purity u 
									 ON u.metal_purity_id=p.metal_purity_id INNER JOIN metal_color c 
									 ON c.metal_color_id=p.metal_color_id WHERE category_id=".$ar['product_metal_id']." ");
			$res = $res->row_array();						 
			echo @$res['metal_price_name'];
		}
		 ?></td>
        <td class="left"><?php 
		if(!empty($ar['product_center_stone_id']))
		{
			$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_center_stone c 
									 ON c.category_id=p.diamond_price_id WHERE category_id=".$ar['product_center_stone_id']."  ");
			
			$res = $res->row_array();						 
			echo @$res['diamond_price_name'];
		}
		?></td>
      	<td class="left"><?php 
		if(!empty($ar['product_side_stone1_id']))
		{
			$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_side_stone1 c 
									 ON c.category_id=p.diamond_price_id WHERE category_id=".$ar['product_side_stone1_id']." ");
			
			$res = $res->row_array();						 
			echo @$res['diamond_price_name'];
		}
		?></td>
      	<td class="left">
		<?php 
			if(!empty($ar['product_side_stone2_id']))
			{
				$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_side_stone2 c 
										 ON c.category_id=p.diamond_price_id WHERE category_id=".$ar['product_side_stone2_id']." ");
				
				$res = $res->row_array();						 
				echo @$res['diamond_price_name'];
			}
		?></td>
        <td class="left"><?php echo $ar['product_engraving_text']; ?></td>
        <?php
			$prod_price = $ar['hid_product_price'] + (int)$ar['hid_product_shipping_cost'] + (int)$ar['hid_product_cod_cost'];
			$subTot += $temp_sub = round($prod_price * (int)$ar['quantity'],2);
			$disTot += $temp_dis = round((int)$ar['quantity'] * $prod_price * ((int)$ar['hid_product_discount']/100),2);
			$taxAmt = 0.0;
			$taxPer = 0.0;
							
			if($ar['order_details_product_tax'] == "")
			{
				$this->proArr[] = $temp_sub;
			}
			else
			{
				$taxArr = explode("|",$ar['order_details_product_tax']);
				foreach($taxArr as $key=>$val)
				{
					$valArr = explode(",",$val); 
					if($valArr[0] == "Fix")
						$taxAmt += (int)@$valArr[1];	
					else
						$taxAmt += round($temp_sub * ((float)@$valArr[1]/100),2);	
				}
			}
			$taxTot += $taxAmt;
			//$this->taxTot += $taxAmt;
																							
			$prodTot = $rowTot += $temp_row = ($temp_sub + $taxAmt - $temp_dis);
		?>
        <td class="right"><?php echo lp($prod_price); ?></td>
        <td class="right"><?php echo $ar['quantity']; ?></td>
        <td class="right"><?php echo lp($temp_sub); ?></td>
        <td class="right"><?php echo lp($temp_dis); ?></td>
        <td class="right"><?php echo lp($taxAmt); ?></td>
        <td class="right"><?php echo lp($temp_row); ?></td>
      </tr>
	  <?php
	  			endforeach;
      		endif;
	  ?>      
      <tfoot id="product_footer">
      <tr>
          <td class="left" >Total <?php echo sizeof($prodArr) ?> Products</td>
          <td class="right" colspan="10"  >Subtotal: </td>
          <td class="right"><?php echo lp($subTot); ?></td>
          <td class="right"><?php echo lp($disTot); ?></td>
          <td class="right"><?php echo lp($taxTot); ?></td>
          <td class="right"><?php echo lp($rowTot); ?></td>
      </tr>
      <?php
          $coupDiscAmt =0.0;
          $res = executeQuery("SELECT coupon_discount_amt, coupon_type FROM coupon WHERE coupon_id=".(int)$coupon_id);
		  if(!empty($res)):
              if($res[0]['coupon_type'] == 'Fix')
                  $coupDiscAmt = $res[0]['coupon_discount_amt'];	
              else
                  $coupDiscAmt = round($subTot * ($res[0]['coupon_discount_amt']/100),2);	
      ?>
      <tr id="tr_coupon">
      <td class="right" colspan="12">Coupon Discount: </td>
      <td class="right"><?php echo ($res[0]['coupon_type'] == 'Fix')?'Fix':$res[0]['coupon_discount_amt'].'%'; ?></td>
      <td class="right"><?php echo lp($coupDiscAmt); ?></td>
      <td class="right"><?php $prodTot = $prodTot - $coupDiscAmt; echo lp($prodTot); ?></td>
      </tr>                
      <?php
          endif;
      ?>
      </tfoot>
      </tbody>
    </table>
</div>

<div class="clear"></div>
<!-- Comment History-->
<div class="box-left">
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Comments History</h4>
        </div>
        <div class="fieldset">
            <table cellspacing="0" class="form-list">
            <tbody>
            <?php
				$resArr = executeQuery("SELECT order_tracking_comment,order_tracking_created_date,order_status_name FROM order_tracking t INNER JOIN order_status s 
				ON s.order_status_id=t.order_status_id WHERE order_id=".$order_id." ORDER BY order_tracking_id DESC ");
				if(!empty($resArr)):
					foreach($resArr as $k=>$ar):
			?>
  
            <tr>
                <td class="label" style="width:400px;">
                <?php echo formatDate('d m, Y <b>h:i a</b>',@$ar['order_tracking_created_date']);?>&nbsp;|&nbsp; <b><?php echo $ar['order_status_name']; ?></b><br>
                <?php echo $ar['order_tracking_comment']; ?>
                </td>
            </tr>
            <?php
            		endforeach;
				endif;
			?>
            </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Order Totals-->
<div class="box-right">
    <div class="entry-edit">
        <div class="entry-edit-head">
            <h4 class="icon-head head-account">Order Totals</h4>
            <div class="tools"></div>
        </div>
        <div class="order-totals">
        <table cellspacing="0" width="100%">
        <colgroup>
            <col>
            <col width="1">
        </colgroup>
        <tbody>
		<tr>
            <td class="label"><b>Tax Rate:</b></td>
            <td style="width:160px;">
			<?php 
			  $order_total_amt += $prodTot;
			  $taxGenTot = 0;
			  $taxRate = 0;

			  if(sizeof($this->proArr) > 0 )
			  {
				  $taxRate = getField("config_value","configuration","config_key","TAX_RATE");
				  if(!empty($taxRate))
				  {
					  foreach($this->proArr as $k=>$price)
						  $taxGenTot += round($price * ($taxRate/100),2);	
				  }
				  echo $taxRate."%";
			  }
			  else
				  echo "Product Wise Tax Applied"; 
				  
			  $order_total_amt += $taxGenTot;
			?>
	        </td>
        </tr>
        <tr>
            <td class="label"><b>Tax Amount:</b></td>
            <td style="width:160px;"><?php echo lp($taxGenTot);?></td>
        </tr>
        <tr>
            <td class="label"><b>Grand Total:</b></td>
            <td style="width:160px;"><?php echo lp($order_total_amt);?></td>
        </tr>
        </tbody>
    	</table>
</div>
    </div>
</div>

<div class="clear"></div>