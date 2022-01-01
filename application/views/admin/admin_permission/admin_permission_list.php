<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"></div>
    </div>
        
    <div class="content">
	<?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>    
    </div>
  </div>
  
</div>