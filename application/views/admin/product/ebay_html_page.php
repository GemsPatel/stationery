<link href="<?php echo asset_url('css/ebay.css')?>" rel="stylesheet" type="text/css">
<?php
//pr($data);
?>

<!-- Begin Header -->
<div class="header tcenter">
    <span class="header_logo left"></span>
    <span class="logo"></span>
    <span class="header_logo right"></span>
</div> 
<!-- End Header --> 

<!-- Start Section1 -->
<section id="section1" class="container">  
    <div align="center" class="big-img-div">
    	<?php
		$cnt = 0;
		if(!empty($product_images)):
			foreach( $product_images as $k=>$ar):
				//if( $k < $angle_in ) { continue; }
				$cnt++;
				
				//if( $cnt > 3 ) { break; }
			?>
				<img width="500" src="<?php echo load_image(@$product_images[ $k ])?>">
			<?php 
			  endforeach;
		endif;
		?>	
    </div>
</section>
<!-- End Section1 -->


<!-- Start Section2 -->
<section id="section2" class="container">
  <h2 align="center" class="sixteen columns f-style">Item Description</h2>
  <div class="u-line"></div>
  
  <div class="one-third column"> <img alt="" src="<?php echo asset_url('images/diamond.png')?>">
    <h3 class="sub_header">MAIN STONE DETAIL</h3>
    
    <table align="center">
      <tbody>
        <tr>
          <td class="first-td">Type:</td>
          <td class="second-td"><?php echo (!empty($dp_desc_cs)) ? $dp_desc_cs : 'N/A'; ?></td>
        </tr>
        <tr>
          <td class="first-td">Carat:</td>
          <td class="second-td"><?php echo (!empty($product_center_stone_weight)) ? $product_center_stone_weight : 'N/A'; ?></td>
        </tr>
        <tr>
          <td class="first-td">Cut:</td>
          <td class="second-td">Excellent</td>
        </tr>
      </tbody>
    </table>    
  </div>
  
  <div class="one-third column"> <img alt="" src="<?php echo asset_url('images/round.png')?>">
    <h3 class="sub_header">SIDE STONE DETAIL</h3>
    
    <table align="center">
      <tbody>
        <tr>
          <td class="first-td">Type:</td>
          <td class="second-td"><?php echo (!empty($dp_desc_ss1)) ? $dp_desc_ss1 : 'N/A'; ?></td>
        </tr>
        <tr>
          <td class="first-td">Carat:</td>
          <td class="second-td"><?php echo (!empty($product_side_stone1_weight)) ? $product_side_stone1_weight : 'N/A'; ?></td>
        </tr>
        <tr>
          <td class="first-td">Cut:</td>
          <td class="second-td"><?php echo (!empty($product_side_stone1_weight)) ? "Excellent" : 'N/A'; ?></td>
        </tr>
      </tbody>
    </table>
    
  </div>
  
  <div class="one-third column"> <img alt="" src="<?php echo asset_url('images/silver.png')?>"> &nbsp;
    <h3 class="sub_header">METAL DETAIL</h3>
    <table align="center">
      <tbody>
        <tr>
          <td class="first-td">Metal Type:</td>
          <td class="second-td"><?php echo $metal_type_name; ?> (<?php echo $metal_color_name;?> Plated)</td>
        </tr>
        <tr>
          <td class="first-td">Total&nbsp;Silver&nbsp;Weight:</td>
          <td class="second-td"><?php echo $product_metal_weight; ?></td>
        </tr>

        <?php
        	if( isRing( $product_accessories, $ring_size_region) ):
				if(!empty($ep_mode) == 10)
					$ringSize = "11";
				else
					$ringSize = "7 US (FREE RESIZING)";
        ?>
		        <tr>
		          <td class="first-td">Ring Size:</td>
		          <td class="second-td"><?php echo $ringSize ?></td>
		        </tr>
        <?php
        	endif;
        ?>
        
      </tbody>
    </table>
    <br>
  </div>
  
  <br /><div style="clear:both"></div><br />
  <h2 align="center" class="sixteen columns f-style">Regarding Product</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; 
        	Gemstone color or metal shade may be differ as shown to picture as the picture are CAD generated images and not of the ready products. </p>
            
        <p>&raquo;&nbsp; All our diamonds & gemstones except Black are lab created and are not natural. Black Moissanite diamonds are used instead of CZ diamonds. </p>
  </div>
  
  <div style="clear:both"></div>
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Note To Buyer</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p align="center">&raquo;&nbsp; How to mention the ring size while making the payment?</p>
        <p>&raquo;&nbsp; While making the payment customer needs to mention the ring size on the "Note to Seller" tab or else customer can send a separate message to us after making the payment. </p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Customisation</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We offer full customization on our each product & we can also make products of your choice. If you like to make any changes to be made on a particular product of ours for ex. Studding different Gemstone, changing metal color, change in design etc. </p>
        <p>&raquo;&nbsp; If you have a particular design in mind, we can manufacture customised products as well. All you need to do is just sent us a message on Ebay along with the image you want to make & we will get back to you within 48 hrs with the quotation & details of the product.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Why Meera Jewels?</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We thrive to provide 100% customer service. Customer satisfaction is our ultimate goal.</p>
        <p>&raquo;&nbsp; As we are the direct manufacturers you can get your jewellery at the best prices as it does'nt include middlemen costs & retail stores margins.</p>
        <p>&raquo;&nbsp; We are in this industry since 20 years. With core facilities of state of the art manufacturing factory and 150 artisans our quality is unmatched.</p>
        <p>&raquo;&nbsp; Each piece pass through strict quality control so you can enjoy the highest quality and finest craftsmanship.</p>
        <p>&raquo;&nbsp; Our products are 100% genuine are manufactured by us only that's why you can get the product at factory price without additional middlemen charges.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Shipping Policy</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We offer free shipping worldwide. We try to ship your products at earliest.</p>
        <p>&raquo;&nbsp; All our products are 'Made to order' so it takes about 10 days in processing the order as we do not carry any physical inventory.</p>
        <p>&raquo;&nbsp; It takes about 15-18 working days to deliver your product to your door step.</p>
        <p>&raquo;&nbsp; Local charges such as import duty, sales tax should be bearded by customer if applied.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Refund and Returns </h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We offer 14 days 'No question asked' return policy, So in case you didn't like the product just return back to us and we will refund your full amount back to your respective account.</p>
        <p>&raquo;&nbsp; Return shipping charges are paid by the customer.</p>
        <p>&raquo;&nbsp; Refund will be initiated within 3-4 days of receiving the product.</p>
        <p>&raquo;&nbsp; In case of any query kindly contact us (drop a message) and we will get back to you within 24 hrs.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Feedback</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; Please contact us to resolve the issue before giving negative or neutral feedback.</p>
  </div>
  
  
</section>
<!-- End Section2 -->


<!-- Start Footer -->
<footer class="container copyrightbottom fontsize14">
    <div class="tcenter">
      Copyright &copy; <?php echo date('Y') ?>. <a>Meera Jewels</a>. All Rights Reserved. Developed by <a href="http://www.Cloudwebstechnology.com" target="_blank">Cloudwebs</a>
    </div>      
</footer>
<!-- End Footer -->