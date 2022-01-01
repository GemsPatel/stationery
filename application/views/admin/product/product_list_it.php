<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> Select Inventory</h1>
      <div class="buttons">
       	<a class="button" href="<?php echo site_url('admin/'.$this->controller)?>" >Back</a>
      </div>
    </div>
    
    <div class="content">
    	<?php $this->load->view('admin/'.$this->controller.'/ajax_html_data_it'); ?>
    </div>
  </div> 
  
</div>

