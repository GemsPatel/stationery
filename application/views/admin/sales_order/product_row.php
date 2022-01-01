          <?php
				if( !$is_new_row ):			
					if( isset( $ar["not_available"] ) ):
		  ?>
		  	            <tr id="pp_id_<?php echo $k; ?>">
	                      <td align="center" colspan="<?php echo ( isSupportsJewelleryInventory() ? 6 : 5 );?>"><?php echo $ar['not_available'];?></td>
	                      <td align="center">
	                      	<input class="hide" type="checkbox" checked="checked" value="<?php echo $k;?>" id="checkbox_<?php echo $k;?>" name="selected[]" class="chkbox" onchange="addRemProductAdmin(this, this.value, 0, <?php echo $k;?>)"/>
	                      	<a class="button" href="javascript:void(0);" onclick="remProductTr( $('#checkbox_<?php echo $k?>'), <?php echo $k?>, 0, <?php echo $k;?>,'pp_id_<?php echo $k;?>');" style="float:right;">Remove</a>
	                      </td>
	                    </tr>
		  <?php			
					else:
          ?>
	                    <tr id="pp_id_<?php echo $k; ?>">
	                      <td class="left" id="td_product_name_<?php echo $k;?>"><?php echo $ar['product_name'];?></td>
	                      <td class="left" id="td_product_sku_<?php echo $k;?>"><?php echo $ar['product_sku'];?></td>
	                      <td align="center">
	                         	<select name="product_type" id="product_type_<?php echo $k;?>" onchange="dynamicAddInput(this, <?php echo $k;?>)">
	                              	<?php echo hewr_productTypes( $ar["type"] );?>
	                            </select>
	                      	    <input type="text" size="40" id="product_generated_code_<?php echo $k;?>"  name="product_generated_code_<?php echo $k;?>" onblur="calcProdPrice(this,'<?php echo $k;?>');" placeholder="Product Generated Code" value="<?php echo $ar['product_generated_code_displayable']?>"/>
	                      </td>
	                      
	                      <?php
			              	if( isSupportsJewelleryInventory() ): 
			              ?>
	                      		<td align="center" id="ring_size_<?php echo $k;?>"><?php echo (isset($ar['ring_size_drop_down'])) ? $ar['ring_size_drop_down'] : 'Not applicable';?></td>
			              <?php
			              	endif;
			              ?>		
	                      
	                      <td align="center"><span id="span_prod_price_<?php echo $k;?>"><?php echo lp($ar['product_discounted_price'])?></span></td>
	                      <td align="center" >
	                      	 <?php
	                      	 	if( CLIENT == "Stationery" && hewr_isGroceryInventoryCheckWithId( $ar["inventory_type_id"] ) ):
	                      	 ?>
			                         <?php
										echo form_dropdown( 'quantity_'.$k,
												getProdQtyOptions( $ar["product_id"], $ar["product_generated_code_info"] ),
												$ar["qty"],' id="qty_'.$k.'" onchange="addRemProductAdmin($(\'#checkbox_'.$k.'\'), $(\'#checkbox_'.$k.'\').val(), this.value, '.$k.');" ');
			                         ?>
			                 <?php
			                 	else:
			                 ?>
			                 		 <select name="quantity_<?php echo  $k;?>" id="qty_<?php echo  $k;?>" onchange="addRemProductAdmin($('#checkbox_<?php echo $k?>'), $('#checkbox_<?php echo $k?>').val(), this.value, <?php echo $k;?>);">
			                         <?php
			                              for($j=1;$j<=10;$j++)
			                              {
			                                  if($j == $ar['qty'])
			                                  {
			                                      echo '<option value="'.$j.'" selected="selected">'.$j.'</option>';
			                                  }
			                                  else
			                                  {
			                                      echo '<option value="'.$j.'" >'.$j.'</option>';
			                                  }
			                              }
			                         ?>
			                         </select>
			                 <?php
			                 	endif;
			                 ?>        
	                      </td>
	                      <td align="center" >
	                      	<input class="hide" type="checkbox" checked="checked" value="<?php echo $k;?>" id="checkbox_<?php echo $k;?>" name="selected[]" class="chkbox" onchange="addRemProductAdmin(this, this.value, $('#qty_<?php echo $k?>').val(), <?php echo $k;?>)"/>
	                      	<a class="button" href="javascript:void(0);" onclick="remProductTr($('#checkbox_<?php echo $k?>'), $('#checkbox_<?php echo $k?>').val(), $('#qty_<?php echo $k?>').val(), <?php echo $k;?>,'pp_id_<?php echo $k;?>');" style="float:right;">Remove</a>
	                      </td>
	                    </tr>
          <?php
          			endif;
          		else:
          			$i = $k; 
          ?>
                      <tr id="pp_id_<?php echo $i; ?>">
                        <td class="left" id="td_product_name_<?php echo $i;?>"></td>
                        <td class="left" id="td_product_sku_<?php echo $i;?>"></td>
                        <td class="center">
                         	<select name="product_type" id="product_type_<?php echo $i;?>" onchange="dynamicAddInput(this, <?php echo $i;?>)">
                              	<?php echo hewr_productTypes();?>
                            </select>
                              
                            <span class="spanclass_<?php echo $i;?>" id="product_code_span_<?php echo $i;?>">&nbsp;&nbsp;
                            	<input type="text" size="30" id="product_generated_code_<?php echo $i;?>" name="product_generated_code_<?php echo $i;?>" onblur="calcProdPrice(this,'<?php echo $i;?>');" placeholder="Product Generated Code"/>
                            </span>
                            
                            <span class="spanclass_<?php echo $i;?>" id="solitaire_code_span_<?php echo $i;?>" style="display:none">&nbsp;&nbsp;
                            	<input type="text" size="20" id="solitaire_code_<?php echo $i;?>" name="solitaire_code_<?php echo $i;?>" onblur="calcProdPrice( this,'<?php echo $i;?>');" placeholder="Solitaire Design Code"/>&nbsp;
                                <input type="text" size="20" id="solitaire_diamond_code_<?php echo $i;?>" name="solitaire_diamond_code_<?php echo $i;?>" 
                                	onblur="calcProdPrice($('#solitaire_code_<?php echo $i;?>'),'<?php echo $i;?>');" placeholder="Diamond Code"/>
                            </span>
                           
                            <span class="spanclass_<?php echo $i;?>" id="diamond_code_span_<?php echo $i;?>" style="display:none">&nbsp;&nbsp;
                            	<input type="text" size="30" id="diamond_code_<?php echo $i;?>" name="diamond_code_<?php echo $i;?>" onblur="calcProdPrice(this,'<?php echo $i;?>');" placeholder="Diamond Code"/>
                            </span>
                            
                            <span class="spanclass_<?php echo $i;?>" id="cz_code_span_<?php echo $i;?>" style="display:none">&nbsp;&nbsp;
                            	<input type="text" size="30" id="CZ_code_<?php echo $i;?>" name="CZ_code_<?php echo $i;?>" onblur="calcProdPrice(this,'<?php echo $i;?>');" placeholder="CZ Code"/>
                            </span>
                        </td>
                        
						<?php
		              		if( isSupportsJewelleryInventory() ): 
		              	?>
                      			<td class="center" id="ring_size_<?php echo $i;?>">Not applicable</td>
		              	<?php
		              		endif;
		              	?>		
                        
                        <td class="center"><span id="span_prod_price_<?php echo $i;?>" ></span></td>
                        <td class="center" >
                           <select name="quantity_<?php echo  $i;?>" id="qty_<?php echo  $i;?>" onchange="addRemProductAdmin($('#checkbox_<?php echo $i?>'), $('#checkbox_<?php echo $i?>').val(), this.value, <?php echo $i;?>);">
                           <?php
                                for($j=1;$j<=10;$j++)
                                {
                                    echo '<option value="'.$j.'" >'.$j.'</option>';
                                }
                           ?>
                           </select>
                        </td>
                        <td align="center" >
                      		<input class="hide" type="checkbox" checked="checked" value="" id="checkbox_<?php echo $i;?>" name="selected[]" class="chkbox" onchange="addRemProductAdmin(this, this.value, $('#qty_<?php echo $i?>').val(), <?php echo $i;?>)"/>
                      		<a class="button" href="javascript:void(0);" onclick="remProductTr($('#checkbox_<?php echo $i?>'), $('#checkbox_<?php echo $i?>').val(), $('#qty_<?php echo $i?>').val(), <?php echo $i;?>,'pp_id_<?php echo $i; ?>');" style="float:right;">Remove</a>
                      	</td>
                      </tr>
          <?php		
		  		endif;		  
          ?>                      
