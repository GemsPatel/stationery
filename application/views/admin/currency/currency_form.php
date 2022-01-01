<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/CurrencyForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Currency</legend>
			<table class="form">
              <tbody>
                <tr>
                  <td>&nbsp;&nbsp;Country:</td>
                  <td>
					<?php   
						$sql = "SELECT country_id, country_name FROM country WHERE country_status=0 ";
						$userArr = getDropDownAry( $sql,"country_id", "country_name", array('' => "-- Select Country --"), false);
						$setval =(@$country_id)? $country_id:@$_POST['country_id'];
						echo form_dropdown('country_id',$userArr,$setval);
                    ?>
                 </td>
                </tr>   
              	<tr>
                  <td><span class="required">*</span>  Name :</td>
                  <td><input type="text" name="currency_name" value="<?php echo (@$currency_name)?$currency_name:@$_POST['currency_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('currency_name'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span>  Code :</td>
                  <td><input type="text" name="currency_code"   value="<?php echo (@$currency_code)?$currency_code:set_value('currency_code');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('currency_code'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span>  Symbol :</td>
                  <td><input type="text" name="currency_symbol" value="<?php echo (@$currency_symbol)?$currency_symbol:set_value('currency_symbol');?>"  />
                      <span class="error_msg"><?php echo (@$error)?form_error('currency_symbol'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span>  Value :</td>
                  <td><input type="text" name="currency_value" value="<?php echo (@$currency_value)?$currency_value:@$_POST['currency_value'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('currency_value'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span>  Price Filter Range :</td>
                  <td><input type="text" name="price_filter_range" value="<?php echo (@$price_filter_range)?$price_filter_range:@$_POST['price_filter_range'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('price_filter_range'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp; Sort Order :</td>
                  <td><input type="text" name="currency_sort_order" value="<?php echo (@$currency_sort_order)?$currency_sort_order:@$_POST['currency_sort_order'];?>" />
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp; Status:</td>
                  <td>
                     <select name="currency_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$currency_status=='1' || @$_POST['currency_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
