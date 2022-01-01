<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
          <a class="button" href="<?php echo site_url('admin/'.$this->controller)?>">Back</a>
          <a class="button" href="<?php echo site_url('admin/'.$this->controller.'/salesOrderForm?edit=true&item_id='.$_GET['item_id'])?>">Edit</a>
          <a class="button" href="<?php echo site_url('admin/'.$this->controller.'/sendMail?item_id='._en(@$this->cPrimaryId))?>">Send Email</a>
      </div>
    </div>
    
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/'.$this->controller.'_invoice_view'); ?>
    </div>
  </div>
  
</div>

