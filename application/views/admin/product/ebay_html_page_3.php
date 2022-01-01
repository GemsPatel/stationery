<link href="<?php echo asset_url('css/ebay.css')?>" rel="stylesheet" type="text/css">
<?php
//pr($data);
?>

<!-- Begin Header -->
<div class="header tcenter">
	<span class="header_logo left"></span>
    <span class="logo florence"></span>
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
<section id="section2" class="container custom">
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
  		<p>&raquo;&nbsp; All our jewelry are made from genuine marked 925 Sterling Silver only. </p>            
        <p>&raquo;&nbsp; Metal shade or gemstone colour may slightly differ as the images displayed here are computer generated and not of real products. </p>
        
		<?php if($diamond_price_key_cs == "WHITE_DIAMOND" || $diamond_price_key_ss1 == "WHITE_DIAMOND"): ?>
            <p>&raquo;&nbsp; We use Swarovski Crystals instead of regular Cubic Zirconia which gives more lustre & radiance to your jewelry. </p>
            <p align="center"><img src="<?php echo load_image('images/florence-jewel-product.jpg')?>" height="300"></p>
        <?php endif; ?>
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
  <h2 align="center" class="sixteen columns f-style">Custom Orders</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We also make custom orders on request. If you have any particular personalised design in mind which you want to make and can't find anywhere else, let us know we will create a CAD design for you and will get back to you with the quote of that particular design.</p>
        <p>&raquo;&nbsp; We can also particularly customise our existing products on Ebay as per your request like change in gemstone, diamond or metal color.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Why Florence Jewels?</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We thrive to provide 100% customer service. Customer satisfaction is our ultimate goal.</p>
        <p>&raquo;&nbsp; We are in this industry since 20 years. With core facilities of state of the art manufacturing factory and 150 artisans our quality is unmatched.</p>
        <p>&raquo;&nbsp; Each piece pass through strict quality control so you can enjoy the highest quality and craftsmanship of finest quality.</p>
        <p>&raquo;&nbsp; Our products are 100% genuine are manufactured by us only that's why you can get the product at factory price without additional middlemen charges.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Shipping Policy</h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We offer free shipping worldwide. We try to ship your products at earliest.</p>
        <p>&raquo;&nbsp; All our shipments are fully insured till it reaches to your doorstep.</p>
        <p>&raquo;&nbsp; As all our designs are 'Made to Order', It takes about 10 days to manufacture the product and about 12-15 working days as a shipping period to deliver it your door step.</p>
        <p>&raquo;&nbsp; Local charges such as import duty, sales tax should be bearded by customer if applied.</p>
  </div>
  
  <br /><br />
  <h2 align="center" class="sixteen columns f-style">Refund and Returns </h2>
  <div class="u-line"></div>
  <div class="inner_container">
  		<p>&raquo;&nbsp; We offer 100% money back guarantee on every purchase.</p>
  		<p>&raquo;&nbsp; We offer 14 days 'No question asked' return policy, so in case you didn't like the product just return back to us and we will refund your full amount back to your respective account.</p>
        <p>&raquo;&nbsp; Return shipping charges are paid by the customer.</p>
        <p>&raquo;&nbsp; Refund will be initiated within 3-4 days of receiving the product.</p>
        <p>&raquo;&nbsp; In case of any query kindly contact us (drop a message) & we will get back to you within 24 hrs.</p>
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
      Copyright &copy; <?php echo date('Y') ?>. <a>Florence Jewels</a>. All Rights Reserved. Developed by <a href="http://www.Cloudwebstechnology.com" target="_blank">Cloudwebs</a>
    </div>      
</footer>
<!-- End Footer -->