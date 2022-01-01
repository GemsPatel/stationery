<link href="<?php echo asset_url('css/ebay.css')?>" rel="stylesheet" type="text/css">

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



<!-- Start Footer -->
<footer class="container copyrightbottom fontsize14">
    <div class="tcenter">
      Copyright &copy; <?php echo date('Y') ?>. <a><?php echo baseDomain() ?></a>. All Rights Reserved. Developed by <a href="http://www.Cloudwebstechnology.com" target="_blank">Cloudwebs</a>
    </div>      
</footer>
<!-- End Footer -->