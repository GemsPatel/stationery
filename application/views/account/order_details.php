<?php
	$grand_total = 0.0;
	$is_order = false;
	
	if(is_array($data_order) && sizeof($data_order)>0):
	
?>
	<table class="shop_table type1 inner">
		<thead>
			<tr>
				<th class="product-thumbnail"></th>
				<th class="product-name"><?php echo getLangMsg("item");?></th>
				<th class="product-quantity"><?php echo getLangMsg("qty");?></th>
				<th class="product-subtotal"><?php echo getLangMsg("sttl");?></th>
			</tr>	
		</thead>
		<?php			
		$is_prod = true;
		foreach($data_order as $k=>$ar):
		//pr($ar);die;
			if(isset($ar['not_available'])):
			  
			?>
				<tr>
					<td colspan="4"><?php echo $ar['not_available'];?></td>
				</tr>
			<?php
			elseif( $ar['type'] == '' || $ar['type'] == 'prod' ):
				$prod_url = getProductUrl($ar['product_id'],$k);
			?>
		<tbody>
			<tr class="cart_item inner">
				<td class="product-thumbnail center">
					<a href="<?php echo $prod_url; ?>">
						<img title="<?php echo $ar['product_name']; ?>" alt="<?php echo $ar['product_name']; ?>" 
						src="<?php echo load_image(@$ar['product_images'][$ar['product_angle_in']]);?>" width="80"/>
					</a>
				</td>
				<td class="product-name"><a href="<?php echo $prod_url; ?>"><?php echo $ar['product_name']; ?></a>
					<br><?php
						if(  $ar["inventory_type_id"]  == "3"):
							if( hewr_isGroceryInventoryCheckWithId( $ar["inventory_type_id"] ) ):
						?>
								<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
						<?php
							endif;
						else:
						?>
								<li class="variation-Size">Code: <span><?php echo $ar['product_generated_code_displayable'];?></span></li>
						<?php
						endif;
						
						$grand_total += round($ar['order_details_amt']*$ar['qty']);
					?>
				</td>
				<td class="product-quantity"><?php echo $ar['qty']; ?></td>
				<td class="product-subtotal"><?php echo lp($ar['order_details_amt']*$ar['qty']);?></td>
			</tr>
		</tbody>
		<?php 
			endif;
		endforeach;
		?>
		<tr class="cart_item">
			<td colspan="2"></td>
			<td class="product-subtotal"><?php echo ucwords( strtolower( getLangMsg("sttl") ) ).":";?></td>
			<td class="product-subtotal"><?php echo lp($order_subtotal_amt); ?></td>
		</tr>
		<tr class="cart_item">
			<td colspan="2"></td>
			<td class="product-subtotal"><?php echo getLangMsg("dics").":";?></td>
			<td class="product-subtotal"><?php echo lp($order_discount_amount); ?></td>
		</tr>
		<tr class="cart_item">
			<td colspan="2"></td>
			<td class="product-subtotal"><?php echo getLangMsg("ttl_amt").":";?></td>
			<td class="product-subtotal"><?php echo lp($order_total_amt); ?></td>
		</tr>
		<?php
	else:
	?>
		<tr class="cart_item">
			<td class="product-thumbnail right"><a><i class="fa fa-thumbs-o-down"></i></a></td>
			<td class="product-name"><a>Sorry no orders found.</a></td>
		</tr>
	<?php 
	endif;
	?>
</table>