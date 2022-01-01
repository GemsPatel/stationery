<?php $product_stone_number=$product_stone_number?>
<!-- side stone <?php echo $product_stone_number?>-->
<input type="hidden" name="stone_cat_<?php echo $product_stone_number ?>" />
<div id="tab-data<?php echo ($product_stone_number+2)?>" style="display: none;">
   <fieldset>
   <legend>Side Stone<?php echo $product_stone_number?> Information</legend>
   <table class="form">
      <tbody>
      <tr>
        <td> Diamond Shape:</td>
        <td>
        <?php 
        $setSSSshape = (@${'pss'.$product_stone_number.'_diamond_shape_id'}) ? ${'pss'.$product_stone_number.'_diamond_shape_id'} : @$_POST['pss'.$product_stone_number.'_diamond_shape_id'];
        $sql = "SELECT diamond_shape_id, diamond_shape_name FROM diamond_shape WHERE diamond_shape_status=0";
        $diamond_shapeArr = getDropDownAry($sql,"diamond_shape_id", "diamond_shape_name", '', false);
        
        echo form_dropdown('pss'.$product_stone_number.'_diamond_shape_id',@$diamond_shapeArr,@$setSSSshape,'style="width: 10%;" ');
        ?>
        </td>
      </tr>
       <tr>
        <td> Size:</td>
        <td><input type="text" value="<?php echo (@${'product_side_stone'.$product_stone_number.'_size'}) ? ${'product_side_stone'.$product_stone_number.'_size'}: @$_POST['product_side_stone'.$product_stone_number.'_size'];?>" name="product_side_stone<?php echo $product_stone_number?>_size"></td>
      </tr>
      <tr>
        <td> Weight(Carat):</td>
        <td><input type="text" onkeyup="return calcProdPrice(null,'ss<?php echo $product_stone_number?>_p',false,false, <?php echo $product_stone_number ?>);" value="<?php echo (@${'product_side_stone'.$product_stone_number.'_weight'}) ? ${'product_side_stone'.$product_stone_number.'_weight'}: @$_POST['product_side_stone'.$product_stone_number.'_weight'];?>" name="product_side_stone<?php echo $product_stone_number?>_weight"></td>
      </tr>
      <tr>
        <td> Total Number of Diamonds:</td>
        <td><input type="text" onkeyup="return setProductPrice();" value="<?php echo (@${'product_side_stone'.$product_stone_number.'_total'}) ? ${'product_side_stone'.$product_stone_number.'_total'}: @$_POST['product_side_stone'.$product_stone_number.'_total'];?>" name="product_side_stone<?php echo $product_stone_number?>_total"></td>
      </tr>
    </tbody>
    </table>
   </fieldset>
   
   <!-- Diamond types-->  
   <?php
	   $side_stones_idArr = (@${'side_stone'.$product_stone_number.'_idArr'})? ${'side_stone'.$product_stone_number.'_idArr'}:@$_POST['ss'.$product_stone_number.'_p'];
	   $sql = "SELECT diamond_type_id, diamond_type_name FROM diamond_type WHERE diamond_type_status=0";
	   $diamond_typeArr = getDropDownAry($sql, "diamond_type_id", "diamond_type_name", null, null);
	   $sql = "SELECT diamond_price_id, CONCAT(diamond_price_name,': ', diamond_price_key) as diamond_price_key FROM diamond_price WHERE diamond_type_id=";
	   
	   echo renderCategorywithCheckbox("ss".$product_stone_number."_p[]",$sql, array('0' => "diamond_price_id", '1' => "diamond_price_key"), array('0' => "diamond_type_id", '1' => "diamond_type_name"), 
	   @$diamond_typeArr, @$side_stones_idArr, "", 'style="display: block; width:50%; float:left;"', 0, false, $product_stone_number);
   ?>
      
</div>
