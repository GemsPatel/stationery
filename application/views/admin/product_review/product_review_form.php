<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
         // CKEDITOR.replace( 'product_review_description' );
		 	CKEDITOR.replace( 'product_review_description',
   			 {
					filebrowserBrowseUrl : 'kcfinder/browse.php',
					filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
					filebrowserUploadUrl : 'kcfinder/upload.php',
					filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
    		});
	
   

    });
	
	
</script>
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
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/productReviewForm')?>">
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
						$sql = "SELECT product_id, product_name FROM product";
					  	$userArr = getDropDownAry($sql,"product_id", "product_name", array('' => "-- Select Product --"), false);
						$setval =(@$product_id)? $product_id:@$_POST['product_id'];
						echo form_dropdown('product_id',$userArr,$setval);
					    ?>
                        <span class="error_msg"><?php echo (@$error)?form_error('product_id'):''; ?> </span>
                  </td>
                </tr>
                
             <tr>
                <td><span class="required">*</span>  Rating:</td>
               	<td>
                
                <?php $rate=array('1','2','3','4','5');
						
					echo "<b>Bad</b> &nbsp;"; 
				    $setval=(@$product_review_rating)?$product_review_rating:@$_POST['product_review_rating'];
					foreach($rate as $row)
					{
						echo "&nbsp; &nbsp; ";
						
						?>
                        	<input type="radio" name="product_review_rating"  <?php echo ($setval == $row)?'checked="checked"':''; ?>  id="rating" value="<?php echo $row ?>" />
                        <?php
					}
					
					echo "&nbsp;<b>Good</b>"; 
				
				?>
                <span class="error_msg"><?php echo (@$error)?form_error('product_review_rating'):''; ?> </span>
                </td>
				
             </tr>
             <tr>
                <td><span class="required">*</span>  Description:</td>              	   
                <td><textarea id="product_review_description" name="product_review_description"><?php echo (@$product_review_description)?$product_review_description:@$_POST['product_review_description'];?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('product_review_description'):''; ?> </span>
                </td>
             </tr>
             <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td><select name="product_review_status">
                  <option value="1" selected="selected">Disable</option>
                  <option value="0" <?php echo (@$product_review_status=='0' || @$_POST['product_review_status']=='0')?'selected="selected"':'';?>>Enable</option>
               </select>
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
</script>



