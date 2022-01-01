      <input type="hidden" id="hidden_srt" value="<?php echo $srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo $field; ?>" />
      <?php $para = (isset($_GET['custid'])?'custid='.$_GET['custid']:'');
	  		$para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'edit=true':'';
			$para .= (isset($_GET['item_id']))?(($para!="")?'&':'').'item_id='.$_GET['item_id']:''; ?>
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?prod=add&'.$para)?>" >
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
            <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_name');
            	$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'product_sku');
            ?>
              <th width="15%" class="left" f="product_name" s="<?php echo $a; ?>" >Product Name</th>
              <th width="3%" class="left" f="product_sku" s="<?php echo $c; ?>" >SKU</th>
              <td width="8%" align="center" >Engraving Text</td>
              <td width="8%" align="center" >Engraving Font</td>
              <td width="8%" align="center" >Product Generated Code</td>
              <td width="8%" align="center" >Price</td>
              <td width="8%" align="center" >Gift</td>
              <td width="5%" align="center" >Select</td>
              <td width="5%" align="center" >Qty To Add</td>
            </tr>
            <tr class="filter">
              <td class="left" valign="top"><input type="text" name="product_name_filter" value="<?php echo (@$product_name_filter);?>"></td>
              <td class="left" valign="top"><input type="text" name="product_sku_filter" value="<?php echo (@$product_sku_filter);?>"></td>
              <td class="left" valign="top"></td>
              <td class="left" valign="top"></td>
              <td class="left" valign="top"></td>
              <td class="left" valign="top"></td>
              <td class="left" valign="top"></td>
              <td class="left" valign="top"></td>
              <td align="right" valign="top"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody>
          <?php
			  $sql = "SELECT config_key,config_key FROM configuration WHERE config_display_name='ENGRAVING_FONT' ";
			  $fontArr = getDropDownAry($sql,"config_key", "config_key", array(''=>'Select Font'), false);
			
              if(count($listArr)):
                  foreach($listArr as $k=>$ar):
          ?>
            <tr id="<?php echo $ar['product_id']; ?>">
              <td class="left"><?php echo $ar['product_name'];?></td>
              <td class="left" ><?php echo $ar['product_sku'];?></td>
              <td  align="center" ><input type="text" size="14" maxlength="20" name="product_engraving_text_<?php echo $ar['product_id'] ?>"  /></td>
              <td  align="center" ><?php echo form_dropdown('product_engraving_font_'.$ar['product_id'].'',$fontArr,'',' style="width:85%;" ');
 ?></td>
              <td  align="center"><input type="text" size="14" maxlength="20" name="product_generated_code_<?php echo $ar['product_id'] ?>"  /></td>
              <td align="center">
              <?php
                  $sql = "SELECT CONCAT(product_side_stone1_id,'|',category_id,'|',product_side_stone1_weight,'|',product_side_stone1_total) as 'product_side_stone1_id', diamond_price_name FROM product_side_stone1 p INNER JOIN diamond_price d 
                          ON d.diamond_price_id=p.category_id WHERE dp_status=0 AND product_side_stone1_status=0 AND product_id=".$ar['product_id']." ";
                  $ss1Arr = getDropDownAry($sql,"product_side_stone1_id", "diamond_price_name", '', false);
                  echo form_dropdown('product_side_stone1_id_'.$ar['product_id'].'',$ss1Arr,'',' style="width:70%;" id="product_side_stone1_id_'.$ar['product_id'].'" onchange="return calcProdPrice('.$ar['product_id'].')"');
              ?>
              </td>
              <td  align="center">
              <?php
                  $sql = "SELECT CONCAT(product_side_stone2_id,'|',category_id,'|',product_side_stone2_weight,'|',product_side_stone2_total) as 'product_side_stone2_id', diamond_price_name FROM product_side_stone2 p INNER JOIN diamond_price d 
                          ON d.diamond_price_id=p.category_id WHERE dp_status=0 AND product_side_stone2_status=0 AND product_id=".$ar['product_id']." ";
                  $ss2Arr = getDropDownAry($sql,"product_side_stone2_id", "diamond_price_name", '', false);
                  echo form_dropdown('product_side_stone2_id_'.$ar['product_id'].'',$ss2Arr,'',' style="width:70%;" id="product_side_stone2_id_'.$ar['product_id'].'" onchange="return calcProdPrice('.$ar['product_id'].')" ');
              ?>
              </td>
              <td  align="center">
              <?php
                  $sql = " SELECT CONCAT(product_metal_id,'|',category_id,'|',product_metal_weight,'|',0) as 'product_metal_id', CONCAT(metal_color_name,' ',metal_type_name,' ',metal_purity_name) as 'metal_price_name' FROM product_metal p INNER JOIN metal_price m 
                          ON m.metal_price_id=p.category_id INNER JOIN metal_color c
						  ON c.metal_color_id=m.metal_color_id INNER JOIN metal_type t
						  ON t.metal_type_id=m.metal_type_id INNER JOIN metal_purity u
						  ON u.metal_purity_id=m.metal_purity_id 
						  WHERE metal_price_status=0 AND product_metal_status=0 AND product_id=".$ar['product_id']." ";
				  $res = executeQuery($sql);
				  $metArr = array();
				  if(!empty($res))
					  foreach($res as $key=>$val)
						$metArr[$val['product_metal_id']] = str_replace("None","",$val['metal_price_name']);				  		

                  echo form_dropdown('product_metal_id_'.$ar['product_id'].'',$metArr,'',' style="width:70%;" id="product_metal_id_'.$ar['product_id'].'" onchange="return calcProdPrice('.$ar['product_id'].')" ');
				  
				  $where = "";
				  if($ar['product_tax_id'] != '')
				  {
					  $taxidArr = explode("|",$ar['product_tax_id']);
					  $where = " WHERE ";
					  foreach($taxidArr as $key=>$val)
							$where .= "tax_rate_id=".$val." OR ";
							
					  $where = substr($where,0,-3);		
				  }
					
                  $sql = "SELECT tax_rate_id, CONCAT(tax_rate_type,',',tax_rate_rate) as 'tax_rate_rate' FROM tax_rate ".$where." ";
                  $taxrateArr = getDropDownAry($sql,"tax_rate_id", "tax_rate_rate", '', false);
				  
				  $order_details_product_tax = "";
				  foreach($taxrateArr as $key=>$val)
				  		$order_details_product_tax .= $val."|";
						
				  $order_details_product_tax = substr($order_details_product_tax,0,-1);
				  
              ?>
              </td>
              <td  align="center" ><span id="span_prod_price_<?php echo $ar['product_id'];  ?>" ><?php echo $ar['product_price'];?></span>
              <input type="hidden" value="<?php echo $ar['product_price'];?>" name="hid_product_price_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $ar['product_discount'];?>" name="hid_product_discount_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $order_details_product_tax;?>" name="order_details_product_tax_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $ar['product_shipping_cost'];?>" name="hid_product_shipping_cost_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $ar['product_cod_cost'];?>" name="hid_product_cod_cost_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $ar['product_name'];?>" name="hid_product_name_<?php echo $ar['product_id']; ?>" />
			  <input type="hidden" value="<?php echo $ar['product_sku'];?>" name="hid_product_sku_<?php echo $ar['product_id']; ?>" />
              </td>
              <td  align="center">
              <?php
				  $catArr = explode("|",$ar['category_id']);  //category id array used in gift also

				  $where = "";
				  foreach($catArr as $key=>$val)
				  		$where .= "category_id LIKE '".$val."' OR category_id LIKE '".$val."|%' OR category_id LIKE '%|".$val."|%' OR category_id LIKE '%|".$val."' OR ";

                  $sql = "SELECT gift_id, gift_name FROM gift WHERE gift_status=0 AND (".substr($where,0,-3).") ";
                  $giftArr = getDropDownAry($sql,"gift_id", "gift_name", array('' => "Select Gift"), false);
                  echo form_dropdown('gift_id_'.$ar['product_id'].'',$giftArr,'',' ');
              ?>
              </td>
              <td  align="center" ><input type="checkbox" value="<?php echo $ar['product_id']; ?>" name="selected[]" class="chkbox" /></td>
              <td  align="center" ><select name="quantity_<?php echo $ar['product_id'] ?>" >
                    			   <?php
										for($i=1;$i<=10;$i++)
										{
											echo '<option value="'.$i.'" >'.$i.'</option>';
								   		}
								   ?>
                                   </select></td>
            </tr>
          <?php
                  endforeach;
              endif;
          ?>                      
          </tbody>
      </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
      
      </form>
      
<script type="text/javascript">
var company_labour = <?php echo getField("config_value","configuration","config_key","LABOUR_CHARGE"); ?>;
var company_profit = <?php echo getField("config_value","configuration","config_key","COMPANY_PROFIT"); ?>;

/*
+------------------------------------------------+
	function will caclulate product price whenever option changed
+------------------------------------------------+
*/
function calcProdPrice(prod_id)
{
	var weight = "";
	var id = "";
	var stone_total = "";

	var mt_weight = "";
	var mt_id = "";

	var val = "";
	var valArr = new Array();
	
	val = $('#product_center_stone_id_'+prod_id).val();
	if(val != '' && val != null && typeof val !== "undefined")
	{
		valArr = val.split('|');
		weight += valArr[2]+'|';
		stone_total += valArr[3]+'|';
		id += valArr[1]+'|';
	}

	val = $('#product_side_stone1_id_'+prod_id).val();
	if(val != '' && val != null && typeof val !== "undefined")
	{
		valArr = val.split('|');
		weight += valArr[2]+'|';
		stone_total += valArr[3]+'|';
		id += valArr[1]+'|';
	}

	val = $('#product_side_stone2_id_'+prod_id).val();
	if(val != '' && val != null && typeof val !== "undefined")
	{
		valArr = val.split('|');
		weight += valArr[2]+'|';
		stone_total += valArr[3]+'|';
		id += valArr[1]+'|';
	}

	val = $('#product_metal_id_'+prod_id).val();
	if(val != '' && val != null && typeof val !== "undefined")
	{
		valArr = val.split('|');
		mt_weight = valArr[2];
		mt_id += valArr[1];
	}

	form_data = {id : id.substring(0,id.length -1), weight : weight.substring(0,weight.length -1), stone_total :  stone_total.substring(0,stone_total.length -1), mt_id : mt_id, mt_weight : mt_weight};
	console.log(form_data);
	var loc = (base_url+'admin/'+lcFirst(controller)+'/getDiaMetPrice');
	$.post(loc, form_data, function(data) {
		data = $.parseJSON(data);
		console.log(data);
		setProductPrice(prod_id,data);
		return false;
	});
}

// set price in product price text box
function setProductPrice(prod_id,pro_price)
{
	pro_price = Math.round(pro_price + (pro_price * (company_labour/100)));
	pro_price = Math.round(pro_price + (pro_price * (company_profit/100)));
	$('input[name=hid_product_price_'+prod_id+']').val(pro_price);
	$('#span_prod_price_'+prod_id).text(pro_price);
}

</script>