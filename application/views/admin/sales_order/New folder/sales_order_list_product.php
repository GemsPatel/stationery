<div id="content">
  	
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1> <img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"><?php echo 'Products';//pgTitle($this->controller);?></h1>
      <div class="buttons">
      <?php $para = (isset($_GET['custid'])?'custid='.$_GET['custid']:'');
	  		$para .= (isset($_GET['edit']) && $_GET['edit']=="true")?(($para!="")?'&':'').'edit=true':'';
			$para .= (isset($_GET['item_id']))?(($para!="")?'&':'').'item_id='.$_GET['item_id']:''; ?>
         <a class="button" onclick="" style="float:right;" href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?'.$para.'')?>">Back</a><a class="button" onclick="$('#form').submit();" style="float:right;">Add Selected Products To Order</a>
      </div>
    </div>
   
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/product_ajax_html_data'); ?>
    </div>
  </div>
  
</div>

