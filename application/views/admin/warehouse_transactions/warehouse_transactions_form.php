<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form name="wt" id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/wtForm')?>">
      
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              
              <tr>
                <td><span class="required">*</span> Product:</td>
                <td>
                	<?php
                		if( !empty( $this->cPrimaryId ) )
                		{
                			$disabled = ''; 
                			$setval =(@$product_id)? $product_id:@$_POST['product_id'];
                			$manArr = $manArr = hewr_warehouseProductsDropDown();
                			echo form_dropdown('product_id_disabled',$manArr, $setval, ' style="width:300px;" disabled="disabled" ');
							echo '<input type="hidden" name="product_id" value="'.$setval.'">';		                			 
                		}
                		else 
                		{
                			$setval =(@$product_id)? $product_id:@$_POST['product_id'];
                			$manArr = $manArr = hewr_warehouseProductsDropDown();
                			echo form_dropdown('product_id',$manArr, $setval, ' style="width:300px;" ');
                		}
					?>
                	
				<span class="error_msg"><?php echo (@$error)?form_error('product_id'):''; ?></span>
                </td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> Quantity:</td>
                <td>
                	<input type="text" size="10" maxlength="10" name="wt_qty" id="wt_qty" value="<?php echo (@$_POST['wt_qty'])? $_POST['wt_qty']: @$wt_qty; ?>">
                	<!-- 
                	 - <?php echo (@$_POST['pv_quantity_unit'])? $_POST['pv_quantity_unit']: @$pv_quantity_unit; ?>
                	<input type="hidden" name="pv_quantity_unit" id="pv_quantity_unit" value="<?php echo (@$_POST['pv_quantity_unit'])? $_POST['pv_quantity_unit']: @$pv_quantity_unit; ?>" >
                	 --> 

                	<span class="error_msg"><?php echo (@$error)?form_error('wt_qty'):''; ?></span>
                </td>
              </tr>

              <tr>
                <td>Total Ammount:</td>
                <td>
                	<input type="text" size="10" maxlength="10" name="wt_total" id="wt_total" onchange="count_amount()">
                	<span class="error_msg"><?php echo (@$error)?form_error('wt_total'):''; ?></span>
                </td>
              </tr>
              
              <tr>
                <td><span class="required">*</span> Rate:</td>
                <td><input type="text" size="10" maxlength="10" name="wt_rate" id="wt_rate" value="<?php echo (@$_POST['wt_rate'])? $_POST['wt_rate']: @$wt_rate; ?>">
				<span class="error_msg"><?php echo (@$error)?form_error('wt_rate'):''; ?></span>
                </td>
              </tr>
              <tr>
	              <td>Status:</td>
	              <td>
	              	<select name="wt_status">
	                	<option value="0" selected="selected">Enable</option>
	                    <option value="1" <?php echo (@$pa_status=='1' || @$_POST['wt_status']=='1')?'selected="selected"':'';?>>Disable</option>
	                </select>
	              </td>
              </tr>
              <tr>
	              <td>Note:</td>
	              <td>
	              	<textarea name="wt_note" rows="4" cols="70"><?php echo (@$wt_note) ? $wt_note : @$_POST['wt_note'];?></textarea>
	              	<small class="small_text">Max 300 characters</small>
	              </td>
              </tr>
              
            </tbody></table>
            
        </fieldset>
        </div>
        
      </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
//-->
function count_amount()
{
	var qty = document.wt.wt_qty.value;
	var total_amt = document.wt.wt_total.value;

	var vrate = total_amt / qty;
	var rate = vrate.toFixed(2);

	if( isNaN( rate ) )
	{
		document.wt.wt_rate.value = "0";
	}
	else
	{
		document.wt.wt_rate.value = rate;
	}
}
</script>



