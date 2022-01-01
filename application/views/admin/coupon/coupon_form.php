<script type="text/javascript" src="<?php echo asset_url('js/admin/chosen/chosen.jquery.js');?>"></script>
<link rel="stylesheet" href="<?php echo asset_url('css/admin/chosen/chosen.css');?>" />
<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>


<script type="text/javascript">
	$(document).ready(function(e) {
    	
		//CKEDITOR.replace( 'coupon_desc' );
			CKEDITOR.replace( 'coupon_desc',
   			 {
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
   			 });
	
		admin.bind_datepicker();
		$(".select_chosen").chosen();
	});
	
</script>

<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/couponForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Coupons </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span>  Name :</td>
                  <td><input type="text" name="coupon_name" value="<?php echo (@$coupon_name) ? $coupon_name : set_value('coupon_name');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Code:</td>
                  <td><input type="text" name="coupon_code" value="<?php echo (@$coupon_code) ? $coupon_code : set_value('coupon_code');?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_code'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                        <td> Product categories:</td>
                        <td>
						<?php 
						if(@$this->cPrimaryId != '' && !isset($_POST['category_id']))
						{
							//fetch banner_category mapping
							$res = executeQuery("SELECT category_id FROM coupon_category_map WHERE coupon_id=".$this->cPrimaryId."");
							$category_idArr = array();
							if(!empty($res))
							{
								foreach($res as $k=>$ar)
								{
									$category_idArr[] = $ar['category_id'];
								}
							}
						}

						$setRelCat = (@$category_idArr) ? $category_idArr : @$_POST['category_id'];
                        $sql = "SELECT category_id, category_name FROM product_categories WHERE category_status=0";
                        $product_categoryArr = getDropDownAry($sql,"category_id", "category_name", '', false);
						echo form_dropdown('category_id[]',@$product_categoryArr,@$setRelCat,' class="select_chosen" multiple="true"  style="width: 70%;"');
                        ?>
                        </td>
                    </tr>
                <tr>
                  <td><span class="required">*</span> Maximum Use:</td>
                  <td><input type="text" name="coupon_maximum_use" value="<?php echo (@$coupon_maximum_use) ? $coupon_maximum_use : @$_POST['coupon_maximum_use'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_maximum_use'):''; ?> </span>
                  </td>
                </tr>
                <!-- Gautam Change -->
                <tr>
                  <td><span class="required">*</span> Above Ammount:</td>
                  <td><input type="text" name="coupon_above_amount" value="<?php echo (@$coupon_above_amount) ? $coupon_above_amount : @$_POST['coupon_above_amount'];?>" />
                  Corrency wise:&nbsp;<input type="checkbox" name="coupon_is_above_amount_currencywise" value=<?php echo (@$coupon_is_above_amount_currencywise=='0' || @$_POST['coupon_is_above_amount_currencywise']=='1')?'checked="checked"':'';?>>
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_above_amount'):''; ?> </span>
                  </td>
                </tr>
                <!-- //Gautam Change -->
                <tr>
                  <td>&nbsp;&nbsp; Type :</td>
                  <td>
                     <select name="coupon_type">
                         <option value="Fix" selected="selected">Fix Amount</option>
                         <option value="Percent" <?php echo (@$coupon_type=='Percent' || @$_POST['coupon_type']=='Percent')?'selected="selected"':'';?>>Percentage</option>
                         <option value="FixCW" <?php echo (@$coupon_type=='FixCW' || @$_POST['coupon_type']=='FixCW')?'selected="selected"':'';?>>Fixed CouponWise</option>
                     </select>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;<span class="required">*</span> Discount Amount:</td>
                  <td><input type="text" name="coupon_discount_amt" value="<?php echo (@$coupon_discount_amt) ? $coupon_discount_amt : @$_POST['coupon_discount_amt'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_discount_amt'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Expiry Date:</td>
                  <td><input type="text" class="datepicker" name="coupon_expiry_date" value="<?php echo (@$coupon_expiry_date) ? $coupon_expiry_date : @$_POST['coupon_expiry_date'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('coupon_expiry_date'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Description:</td>
                  <td><textarea id="coupon_desc" name="coupon_desc"><?php echo (@$coupon_desc)?$coupon_desc:@$_POST['coupon_desc'];?></textarea>
                   <span class="error_msg"><?php echo (@$error)?form_error('coupon_desc'):''; ?> </span>
                </td>
                
                </tr>
                

                <tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="coupon_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$coupon_status=='1' || @$_POST['coupon_status']=='1')?'selected="selected"':'';?>>Disable</option>
                     </select>
                  </td>
                </tr>
           	  </tbody>
            </table>
            
        </fieldset>
        </div>
                
      </form>
    </div>
  </div>
  
</div>
