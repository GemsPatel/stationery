<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
	<?php 
		//$_SESSION['KCFINDER']['uploadURL'] = 'http://192.168.1.14/MyOwn/assets';
		//$_SESSION['KCFINDER']['uploadDir'] = 'http://192.168.1.14/MyOwn/assets';
	 ?>
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
      	 
      </div>
    </div>
    
    <div class="content">
	
    <iframe width="100%" height="400" frameborder="0" style="border-radius: 7px 7px 0px 0px;" src="kcfinder/browse.php"> </iframe>
   </div>
    
  </div>
  
</div>

