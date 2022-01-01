<div class="htabs" id="tabs">
    <a href="#tab-general" style="display: inline;" class="selected">Diamond Price</a>
    <a href="#tab-data1" style="display: inline;">Metal Price</a>
    <a href="#tab-data2" style="display: inline;">Labour Charge</a>
    <a href="#tab-data3" style="display: inline;">Company Profit</a>
</div>

<form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/diamondPriceForm')?>">
<?php 
  $metal_type_price = (isset($_POST['metal_type_price'])) ? $_POST['metal_type_price']:(isset($metal_type_price) ? $metal_type_price:'');

  $diamondArr = (isset($_POST['diamond_price_name'])) ? $_POST['diamond_price_name']:(isset($diamond_price) ? $diamond_price:'');
  if(is_array($diamondArr) && sizeof($diamondArr)>0)
  {
      foreach($diamondArr as $k=>$ar)
      {
	      echo '<input type="hidden" name="item_idD[]" id="item_idD_'.$k.'" value="'.((!$this->is_post)? _en($ar['diamond_price_id']) : _en(@$this->cPrimaryIdD[$k])).'"  />';				
	  }
	  
	
  }
  
?>

<?php 
  $metalArr = (isset($_POST['metal_purity_id'])) ? $_POST['metal_purity_id']:(isset($metal_price) ? $metal_price:'');
  if(is_array($metalArr) && sizeof($metalArr)>0)
  {
      foreach($metalArr as $k=>$ar)
      {
          echo '<input type="hidden" name="item_idM[]" id="item_idM_'.$k.'" value="'.((!$this->is_post)? _en($ar['metal_price_id']) : _en(@$this->cPrimaryIdM[$k])).'"  />';				
      }
  }
?>
  <div id="tab-general" style="display: block;">
  <fieldset>
      <legend>Diamond Prices (per carat)</legend>
      <table class="list">
        <thead>
        <tr>
          <td class="left"> Name &nbsp;<span class="required">*</span></td>
           <td class="left"> Key &nbsp;<span class="required">*</span></td>
          <td class="left"> Type &nbsp;<span class="required">*</span>&nbsp;<a href="<?php echo site_url('admin/diamond_type');?>"><img src="<?php echo asset_url('images/admin/add.png')?>" alt="add diamond type" style="margin-bottom: -4px;" /></a></td>
          <td class="left"> Price &nbsp;<span class="required">*</span></td>
          <td class="left"> Clarity </td>
          <td class="left"> Color &nbsp;<span class="required">*</span></td>
          <td class="left"> Labour Charge </td>
          <td class="left"> Weight Diff &nbsp;<span class="required">*</span></td>
          <td class="left"> Icon &nbsp;<span class="required">*</span></td>
          <td class="left"> Description</td>
          <td class="left"> <a class="button" onclick="addDiamondPrice();">Add Diamond Price</a></td>
        </tr>
        </thead>
        <tbody id="diamond_body">
        <?php 
        	$sql_dtype = "SELECT diamond_type_id, diamond_type_name FROM diamond_type WHERE diamond_type_status=0";
        	$dTypeArr = getDropDownAry($sql_dtype,"diamond_type_id", "diamond_type_name", '', false);
          
		  $sql_dpurity = "SELECT diamond_purity_id, diamond_purity_name FROM diamond_purity";
        	$dPurityArr = getDropDownAry($sql_dpurity,"diamond_purity_id", "diamond_purity_name", '', false);
          
		  $sql_dcolor = "SELECT diamond_color_id, diamond_color_name FROM diamond_color";
        	$dColorArr = getDropDownAry($sql_dcolor,"diamond_color_id", "diamond_color_name", '', false);
          
        	if(is_array($diamondArr) && sizeof($diamondArr)>0):
            	foreach($diamondArr as $k=>$ar):
						
        ?>
        <tr id="dp-row<?php echo $k;?>">
   		<td class="left top"><input type="text" size="25" name="diamond_price_name[]" value="<?php echo (!$this->is_post)?@$ar['diamond_price_name']:set_value('diamond_price_name[]');?>"></td>
        <td class="left top"><input type="text" size="25"  name="diamond_price_key[]" value="<?php echo (!$this->is_post)?@$ar['diamond_price_key']:@$_POST['diamond_price_key'][$k];?>" style="text-transform:uppercase" <?php echo (!$this->is_post) ? 'readonly="readonly"': ((isset($this->cPrimaryIdD[$k]))? 'readonly="readonly"':''); ?> ></td>
          <td class="left top">
          <?php 
          	$dropdownDt = form_dropdown('diamond_type_id[]',@$dTypeArr,(!$this->is_post)?@$ar['diamond_type_id']:set_value('diamond_type_id[]'),'class=""');
            echo $dropdownDt;
          ?>
          </td>
          <td class="left top"><input type="text" size="10" name="dp_price[]" value="<?php echo (!$this->is_post)?@$ar['dp_price']:set_value('dp_price[]');?>"></td>
          <!--<td class="left top"><input type="text" size="3" name="dp_clarity[]" value="<?php //echo (!$this->is_post)?@$ar['dp_clarity']:@$_POST['dp_clarity'][$k];?>"></td>-->
          <td class="left top">
          <?php 
		  	
          	$dropdownDp = form_dropdown('diamond_purity_id[]',@$dPurityArr,(!$this->is_post)?@$ar['diamond_purity_id']:@$_POST['diamond_purity_id'][$k],'class=""');
            echo $dropdownDp;
          ?>
          </td>
          <!--<td class="left top"><input type="text" size="1" name="dp_color[]" value="<?php //echo (!$this->is_post)?@$ar['dp_color']:set_value('dp_color[]');?>"></td>-->
          <td class="left top">
          <?php 
          	$dropdownDc = form_dropdown('diamond_color_id[]',@$dColorArr,(!$this->is_post)?@$ar['diamond_color_id']:set_value('diamond_color_id[]'),'class=""');
            echo $dropdownDc;
          ?>
          </td>
          <td class="left top"><input type="text" size="2" name="diamond_price_labour_charge[]" value="<?php echo (!$this->is_post)?@$ar['diamond_price_labour_charge']:@$_POST['diamond_price_labour_charge'][$k];?>"><span>%</span></td>
          <td class="left top"><input type="text" size="5" name="dp_weight_diff[]" value="<?php echo (!$this->is_post)?@$ar['dp_weight_diff']:set_value('dp_weight_diff[]');?>"></td>
          <td class="left top">
          	<div class="image" style="padding:2px;" align="center">
				<?php $url = ((!$this->is_post) ? @$ar['dp_icon'] : set_value('dp_icon[]'));?>
                <img src="<?php echo asset_url($url);?>" width="45" height="30" id="dpPrevImage_<?php echo $k; ?>" style="padding:1px; margin-bottom: -5px;" class="image"/><br />
                <input type="file" name="dp_iconfile[]" id="dpImg_<?php echo $k; ?>" onchange="readURL(this,'<?php echo $k; ?>');" style="display: none;">
                <input type="hidden" value="<?php echo $url;?>" name="dp_icon[]" id="hiddenDPImg" />
                <small class="small_text"><a onclick="$('#dpImg_<?php echo $k; ?>').trigger('click');">Browse</a>|<a style="clear:both;" onclick="clear_image('dpPrevImage_<?php echo $k; ?>')">Clear</a></small>             		 		  	
          	</div>
          </td>
          <td class="left top"><textarea name="dp_desc[]" rows="2" cols="45"><?php echo (!$this->is_post)?@$ar['dp_desc']:@$_POST['dp_desc'][$k];?></textarea></td>
          <?php
		  	$remove = '';
          	if(isset($this->cPrimaryIdD[$k]) && $this->cPrimaryIdD[$k]!='')
			{
				$remove = 'return deleteDiamondMetal('.$k.',1)';
			}
			else
			{
				$remove = '$(\'#dp-row'.$k.'\').remove();dp_row = dp_row -1;';
			}
		  ?>
          <td class="left top"><a onclick="<?php echo $remove;?>" class="button">Remove</a></td>
        </tr>
        <?php 
        		endforeach;
        	else:
            	$dropdownDt = form_dropdown('diamond_type_id[]',@$dTypeArr,'','class=""');
				$dropdownDp = form_dropdown('diamond_purity_id[]',@$dTypeArr,'','class=""');
				$dropdownDc = form_dropdown('diamond_color_id[]',@$dTypeArr,'','class=""');
        ?>
		<tr id="nores_d"><td class='center' colspan='10'>No results!</td></tr>
        <?php			 	
        	endif; 
        ?>
        </tbody>
      </table>
      
  </fieldset>
  </div>
  
  <div id="tab-data1" style="display: none;">
  <fieldset>
      <legend>Metal Type &nbsp;<a href="<?php echo site_url('admin/metal_type');?>"><img src="<?php echo asset_url('images/admin/add.png')?>" alt="add metal type" style="margin-bottom: -4px;" /></a></legend>
      <table class="form">
        <tbody>
        <form id="metal_type_priceF" name="metal_type_priceF">
        <?php 
		  	$cnt=0;
          	foreach($metal_type_price as $k=>$ar):
          	$type = explode('|',$ar);
			$k = ((!$this->is_post)?$k:@$_REQUEST['metal_type_idhid'][$cnt]);
			$price = ((!$this->is_post)?$type[1]:@$_POST['metal_type_price'][$cnt]);
        ?>
		<script type="text/javascript">var mt_price<?php echo $k; ?> = <?php echo json_encode($price); ?>;</script>
        <tr>
          <td><span class="required">*</span> <?php echo $type[0]; ?></td>
          <td><input type="text" size="10" name="metal_type_price[]" class="metal_type_price metal_price_update" id=""  value="<?php echo $price; ?>" >
              <input type="hidden" value="<?php echo $k; ?>" name="metal_type_idhid[]"  class="metal_type_idhid metal_price_update" />
          </td>    
          <span class="error_msg"><?php echo (@$error)?form_error('metal_type_price[]'):''; ?></span>
        </tr>
        <?php 
			$cnt++;
        	endforeach;
        ?>	
        </form> 
        <tr>
        <td></td><td><a class="button" onclick="return updateMetalPrice()">Update</a></td>
        <tr>
        </tbody>
      </table>
  </fieldset>
  <fieldset>
      <legend>Metal Prices (per gm)</legend>
      <table class="list">
        <thead>
        <tr>
          <td class="left"> Metal &nbsp;<span class="required">*</span>&nbsp;<a href="<?php echo site_url('admin/metal_type');?>"><img src="<?php echo asset_url('images/admin/add.png')?>" alt="add metal carat" style="margin-bottom: -4px;" /></a></td>
          <td class="left"> Purity &nbsp;<span class="required">*</span>&nbsp;<a href="<?php echo site_url('admin/metal_purity');?>"><img src="<?php echo asset_url('images/admin/add.png')?>" alt="add purity carat" style="margin-bottom: -4px;" /></a></td>
          <td class="left"> Color &nbsp;<span class="required">*</span>&nbsp;<a href="<?php echo site_url('admin/metal_color');?>"><img src="<?php echo asset_url('images/admin/add.png')?>" alt="add color carat" style="margin-bottom: -4px;" /></a></td>
          <td class="left"> Labour Charge </td>
          <td class="left"> Price Difference &nbsp;<span class="required">*</span></td>
          <td class="left"> Icon &nbsp;<span class="required">*</span></td>
          <td class="left"> Description</td>
          <td class="left"> <a class="button" onclick="addMetalPrice();">Add Metal Price</a></td>
        </tr>
        </thead>
        <tbody id="metal_body">
        <?php 
        	$sql_purity = "SELECT metal_purity_id, metal_purity_name FROM metal_purity WHERE metal_purity_status=0";
        	$mPurityArr = getDropDownAry($sql_purity,"metal_purity_id", "metal_purity_name", '', false);

        	$sql_color = "SELECT metal_color_id, metal_color_name FROM metal_color WHERE metal_color_status=0";
        	$mColorArr = getDropDownAry($sql_color,"metal_color_id", "metal_color_name", '', false);

        	$sql_mtype = "SELECT metal_type_id, metal_type_name FROM metal_type WHERE metal_type_status=0";
        	$mTypeArr = getDropDownAry($sql_mtype,"metal_type_id", "metal_type_name", '', false);

        	if(is_array($metalArr) && sizeof($metalArr)>0):
           		foreach($metalArr as $k=>$ar):
        ?>
        <tr id="mp-row<?php echo $k;?>">
          <td class="left top">
          <?php 
            $dropdownMt = form_dropdown('metal_type_id[]',@$mTypeArr,((!$this->is_post)?@$ar['metal_type_id']:set_value('metal_type_id[]')),'onchange="return countMetalPrDiff(\''.$k.'\')" id="metal_type_id_'.$k.'"');
            echo $dropdownMt;
          ?>
          </td>
          <td class="left top">
          <?php 
          	$dropdownMp = form_dropdown('metal_purity_id[]',@$mPurityArr,((!$this->is_post)?@$ar['metal_purity_id']:set_value('metal_purity_id[]')),'onchange="return countMetalPrDiff(\''.$k.'\')" id="metal_purity_id_'.$k.'"');
            echo $dropdownMp;
          ?>
          </td>
          <td class="left top">
          <?php 
            $dropdownMc = form_dropdown('metal_color_id[]',@$mColorArr,((!$this->is_post)?@$ar['metal_color_id']:set_value('metal_color_id[]')),'class=""');
            echo $dropdownMc;
          ?>
          </td>
          <td class="left top"><input type="text" size="5" name="metal_price_labour_charge[]" value="<?php echo ((!$this->is_post)?@$ar['metal_price_labour_charge']:@$_POST['metal_price_labour_charge'][$k]);?>" id="mp_lbrtext_<?php echo $k; ?>"  onkeyup="countMetalPrDiff('<?php echo $k; ?>')"><span>%</span></td>
          <td class="left top"><?php $mp_diff = ((!$this->is_post)?@$ar['mp_price_difference']:set_value('mp_price_difference[]'));?><input type="hidden" value="<?php echo $mp_diff; ?>" name="mp_price_difference[]" id="hiddenMpricedif" /><span id="mp_diffspan_<?php echo $k; ?>"><?php echo $mp_diff; ?></span></td>
          <td class="left top">
          	<div class="image" style="padding:2px;" align="center">
				<?php $url = ((!$this->is_post) ? @$ar['mp_icon'] : set_value('mp_icon[]')); ?>
                <img src="<?php echo asset_url($url);?>" width="45" height="30" id="mpPrevImage_<?php echo $k; ?>" style="padding:1px; margin-bottom: -5px;" class="image"/><br />
                <input type="file" name="mp_iconfile[]" id="mpImg_<?php echo $k; ?>" onchange="readURL(this,'<?php echo $k; ?>');" style="display: none;">
                <input type="hidden" value="<?php echo $url;?>" name="mp_icon[]" id="hiddenMPImg" />
                <small class="small_text"><a onclick="$('#mpImg_<?php echo $k; ?>').trigger('click');">Browse</a>|<a style="clear:both;" 
                onclick="clear_image('mpPrevImage_<?php echo $k; ?>')" >Clear</a></small>             		 		  	
          	</div>
          </td>
          <td class="left top"><textarea name="mp_desc[]" rows="2" cols="50"><?php echo (!$this->is_post)?@$ar['mp_desc']:@$_POST['mp_desc'][$k];?></textarea></td>
          <?php
		  	$remove = '';
          	if(isset($this->cPrimaryIdM[$k]) && $this->cPrimaryIdM[$k]!='')
			{
				$remove = 'return deleteDiamondMetal('.$k.',2)';
			}
			else
			{
				$remove = '$(\'#mp-row'.$k.'\').remove();mp_row = mp_row -1;';
			}
		  ?>
          <td class="left top"><a onclick="<?php echo $remove;?>" class="button">Remove</a></td>
        </tr>
        <?php 
        		endforeach;
        	else:
            	$dropdownMp = form_dropdown('metal_purity_id[]',@$mPurityArr,'','onchange="return countMetalPrDiff(\'0\')" id="metal_purity_id_0"');
            	$dropdownMc = form_dropdown('metal_color_id[]',@$mColorArr,'','class=""');
            	$dropdownMt = form_dropdown('metal_type_id[]',@$mTypeArr,'','onchange="return countMetalPrDiff(\'0\')" id="metal_type_id_0"');
        ?>
		<tr id="nores_m"><td class='center' colspan='8'>No results!</td></tr>
        <?php			 	
        	endif; 
        ?>
        </tbody>
      </table>
      
  </fieldset>
  </div>
  
  <div id="tab-data2" style="display: none;">
  <fieldset>
      <legend>Labour Charge</legend>
      <table class="form">
        <tbody>
        <tr>
          <td><span class="required">*</span> Labour Charge:</td>
          <td><input type="text" size="10" name="LABOUR_CHARGE" value="<?php echo getField('config_value','configuration','config_key','LABOUR_CHARGE'); ?>">% 
              &nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="return updateChargeProfit('LABOUR_CHARGE')">Update</a></td>
          <span class="error_msg"><?php echo (@$error)?form_error('LABOUR_CHARGE'):''; ?></span>
        </tr>
        </tbody>
        
      </table>
      
  </fieldset>
  </div>

  <div id="tab-data3" style="display: none;">
  <fieldset>
      <legend>Company Profit</legend>
      <table class="form">
        <tbody>
        <tr>
          <td><span class="required">*</span> Company Profit:</td>
          <td><input type="text" size="10" name="COMPANY_PROFIT" value="<?php echo getField('config_value','configuration','config_key','COMPANY_PROFIT'); ?>">% 
              &nbsp;&nbsp;&nbsp;&nbsp;<a class="button" onclick="return updateChargeProfit('COMPANY_PROFIT')">Update</a></td>
          <span class="error_msg"><?php echo (@$error)?form_error('COMPANY_PROFIT'):''; ?></span>                
        </tr>
        </tbody>
        
      </table>
      
  </fieldset>
  </div>
</form>

<script type="text/javascript">
<!--
	var dp_row=<?php echo json_encode(is_array($diamondArr)?sizeof($diamondArr):'0'); ?>;
	var mp_row=<?php echo json_encode(is_array($metalArr)?sizeof($metalArr):'0'); ?>;
	var G_18K = <?php echo json_encode(G_18K); ?>;
	var G_14K = <?php echo json_encode(G_14K); ?>;
	var G_10K = <?php echo json_encode(G_10K); ?>;
	var dropdownDt = <?php echo json_encode(str_replace('selected="selected"','', $dropdownDt)); ?>;
	var dropdownDp = <?php echo json_encode(str_replace('selected="selected"','', $dropdownDp)); ?>;
	var dropdownDc = <?php echo json_encode(str_replace('selected="selected"','', $dropdownDc)); ?>;
	var dropdownMp = <?php echo json_encode(substr($dropdownMp,strpos($dropdownMp,'>')+1)); ?>;
	var dropdownMt = <?php echo json_encode(substr($dropdownMt,strpos($dropdownMt,'>')+1)); ?>;
	var dropdownMc = <?php echo json_encode(str_replace('selected="selected"','', $dropdownMc)); ?>;
-->


/*
+-------------------------------------------+
	function will disable input element on focus and enable on blur
+-------------------------------------------+
*/
function disableInput(obj,isdisable)
{
	if(isdisable)
	{
		$(obj).attr('disabled','disabled');
	}
	else
	{
		$(obj).removeAttr("disabled");
	}
}
</script>