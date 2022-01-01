<div id="content">
  	
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1> <img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"><?php echo 'Products';//pgTitle($this->controller);?></h1>
      <div class="buttons">
      <?php 
	  	$para = (isset($_GET['custid'])?'custid='.$_GET['custid']:'');
	  	$para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'edit=true':'';
	  ?>
         <a class="button" onclick="" style="float:right;" href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?'.$para.'')?>">Back</a>
         <a class="button" href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?'.$para.'');?>" style="float:right;">Continue</a>
      </div>
    </div>
   
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/product_ajax_html_data'); ?>
    </div>
  </div>
  
</div>

