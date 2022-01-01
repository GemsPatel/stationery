<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript" src="<?php echo asset_url('js/admin/chosen/chosen.jquery.js');?>"></script>
<link rel="stylesheet" href="<?php echo asset_url('css/admin/chosen/chosen.css');?>" />
<script type="text/javascript">
	$(document).ready(function(e) {
       //	CKEDITOR.replace( 'es_message' );
			CKEDITOR.replace( 'es_message',
   			 {
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
    		});
		$(".select_chosen").chosen();
    });
</script>

<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><?php echo (@$edit == true)? '':'<a class="button" onclick="$(\'#form\').submit();">Send</a>'; ?><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/sendEmailForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
      <?php
      		if(isset($_GET['private_email'])):
	  ?>
		    	<input type="hidden" name="private_email" value="<?php echo @$_GET['private_email']; ?>"  />
	  <?php
	  		endif;
      ?>
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Email System</legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> From:</td>
                  <td>
                  <?php 
					  $setVal = (@$es_from_emails) ? $es_from_emails: @$_POST['es_from_emails'];
					  $sql = "SELECT config_value, config_value FROM configuration WHERE config_display_name='EMAIL_ACCOUNT'";
					  $manArr = getDropDownAry($sql,"config_value", "config_value", array('' => "Select Email Account"), false);
					  echo form_dropdown('es_from_emails',$manArr,@$setVal,'style="width:12%; " ');
				  ?>
                  <span class="error_msg"><?php echo (@$error)?form_error('es_from_emails'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td> To:</td>
                  <td>
                  	  <?php 
					  	$setVal = (@$es_to_emails) ? $es_to_emails: @$_POST['es_to_emails']; 
	                    $setProd = (@$es_product_id) ? explode("|",$es_product_id) : @$_POST['es_product_id']; 
					  ?>
                  	  <select name="es_to_emails">
                        <option value="" >-- Select --</option>
                        <option value="newsletter" <?php echo (@$setVal == "newsletter" )? "selected":""; ?>>All Newsletter Subscribers</option>
                        <option value="customer_all" <?php echo (@$setVal == "customer_all" )? "selected":""; ?>>All Customers</option>
                        
                        <?php
                        	if( IS_MP() ):
                        ?>
                        		<option value="manufacturer_all" <?php echo (@$setVal == "manufacturer_all" )? "selected":""; ?>>All Salers</option>
                        <?php
                        	endif;
                        ?>		
                        		
                        <option value="customer_group" <?php echo (@$setVal == "customer_group" )? "selected":""; ?>>Customer Group</option>
                        <option value="customer" <?php echo (@$setVal == "customer" )? "selected":""; ?> >Customers</option>
                        
                        <?php
                        	if( IS_MP() ):
                        ?>
                        		<option value="manufacturer" <?php echo (@$setVal == "manufacturer" )? "selected":""; ?>>Saler</option>
                        <?php
                        	endif;
                        ?>		
                                                
                        
                      </select>
                      <input type="file" name="email_listfile" id="email_listfile_00" style="display: none;" accept="application/msexcel" >
	                  <a onclick="$('#email_listfile_00').trigger('click');" ><img alt="Import Excel File" src="<?php echo asset_url('images/admin/excel_file.png'); ?>" style="vertical-align: middle; margin-left: 1%;" /></a>
					  <label style="vertical-align: bottom;"><input type="checkbox" <?php echo (isset($setProd) && sizeof($setProd)>0)? 'checked="checked"':''; ?> name="es_product_idC" style="margin-left: 50px;vertical-align: bottom;" />Include Products</label>
                  </td>
                </tr>
                <tr class="to" id="to-customer-group" style="display: none;">
                  <td>Customer Group:</td>
                  <td><?php
						$setCustomerG = (@$es_module_primary_id) ? explode("|",$es_module_primary_id) : @$_POST['es_module_primary_id'];
                        $sqlCG = "SELECT customer_group_id, customer_group_name FROM customer_group WHERE customer_group_status=0";
                        $customerGArr = getDropDownAry($sqlCG,"customer_group_id", "customer_group_name", '', false);
						echo form_dropdown('es_module_primary_id[]',@$customerGArr,@$setCustomerG,' class="select_chosen" multiple="true" ');
                  	  ?>
                  </td>
                </tr>
                <tr class="to" id="to-customer" style="display: none;">
                  <td>Customer:</td>
                  <td><?php
						$setCustomer = (@$es_module_primary_id) ? explode("|",$es_module_primary_id) : @$_POST['es_module_primary_id'];
                        $sqlC = "SELECT customer_id, customer_firstname FROM customer WHERE customer_status=0";
                        $customerArr = getDropDownAry($sqlC,"customer_id", "customer_firstname", '', false);
						echo form_dropdown('es_module_primary_id[]',@$customerArr,@$setCustomer,' class="select_chosen" multiple="true" ');
                  	  ?>
                  </td>
                </tr>
                <tr class="to" id="to-manufacturer" style="display: none;">
                  <td>Manufacturer:</td>
                  <td><?php
						$setManufacturer = (@$es_module_primary_id) ? explode("|",$es_module_primary_id) : @$_POST['es_module_primary_id'];
                        $sqlM = "SELECT manufacturer_id, manufacturer_name FROM manufacturer WHERE manufacturer_status=0";
                        $mfArr = getDropDownAry($sqlM,"manufacturer_id", "manufacturer_name", '', false);
						echo form_dropdown('es_module_primary_id[]',@$mfArr,@$setManufacturer,' class="select_chosen" multiple="true" ');
                  	  ?>
                  </td>
                </tr>
                <tr id="to-product" style=" <?php echo (isset($setProd) && sizeof($setProd)>0)? '':'display:none'; ?>;">
                  <td>Products:</td>
                  <td><?php
						$setProd = (@$es_product_id) ? explode("|",$es_product_id) : @$_POST['es_product_id'];
                        $sqlP = "SELECT product_id, product_name FROM product WHERE product_status=0";
                        $productArr = getDropDownAry($sqlP,"product_id", "product_name", '', false);
						echo form_dropdown('es_product_id[]',@$productArr,@$setProd,' class="select_chosen" multiple="true" ');
                  	  ?>
                  </td>
                </tr>

				<tr >
                  <td>Custom Email Address:</td>
                  <td><input type="text" name="custom_email_address" value="<?php echo (@$es_to_emails) ? $es_to_emails: ( ($this->input->get('private_email')) ? _de($this->input->get('private_email')) : @$_POST['custom_email_address']); ?>" size="70" />
                  </td>
                </tr>                    

                <tr>
                  <td><span class="required">*</span> Subject:</td>
                  <td><input type="text" name="es_subject" value="<?php echo (@$es_subject)?$es_subject:set_value('es_subject');?>" size="70" />
                      <span class="error_msg"><?php echo (@$error)?form_error('es_subject'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td> Message:</td>
                  <td><textarea id="es_message" name="es_message"><?php echo (@$es_message)?$es_message:@$_POST['es_message'];?></textarea>
                      <span class="error_msg"><?php echo (@$error)?form_error('es_message'):''; ?> </span>
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
<script type="text/javascript">
<!--
$('select[name=\'es_to_emails\']').bind('change', function() {
	$('.form .to').hide();
	$('.form #to-' + $(this).attr('value').replace('_', '-')).show();
});
$('select[name=\'es_to_emails\']').trigger('change');

// display or hide product
$("input[name=es_product_idC]").change(function()
{
   if($(this).is(":checked"))
   {
		$('.form #to-product').show();
   }
   else
   {
		$('.form #to-product').hide();
   }
});
//-->
</script>