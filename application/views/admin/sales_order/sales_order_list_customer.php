<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo 'Order: Please Select a Customer';//pgTitle($this->controller);?></h1>
      <div class="buttons">
          <a class="button" href="<?php echo site_url('admin/customer/customerForm?mode=order')?>">Create New Customer</a>
          <a class="button" href="<?php echo site_url('admin/sales_order')?>">Back</a>
      </div>
    </div>
    
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/customer_ajax_html_data'); ?>
    </div>
  </div>
  
</div>

