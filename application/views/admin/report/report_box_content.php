<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <?php
  $actions = array('report_admin_log'); 
  $reset   = array('report_admin_log');
  $filter  = array('');
  $delete   = array('report_admin_log');
  ?>
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo pgTitle($this->controller);?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
      <form method="post" id="export_form" action="<?php echo site_url('admin/'.$this->controller.'/exportData') ?>">
	  <?php if(in_array($this->router->class,$actions)):?>        
        Export to:
        <select name="<?php echo $this->controller.'_export'?>">
        	<option value="csv">CSV</option>
            <option value="xls">XLS</option>
        </select>
        <a class="button" href="javascript:void(0)" onclick="$('#export_form').submit();">Export</a>
        <?php endif;?>
        
        <?php if(!in_array($this->router->class,$reset)):?>
        <a class="button" href="<?php echo site_url('admin/'.$this->controller)?>">Reset</a> 
        <?php endif;?>
        
        <a class="button" id="searchFilter">Filter</a>
        
        <?php if(in_array($this->router->class,$delete)):?>
        <a class="button" title="delete" onclick="return deleteAjaxData()">Delete</a>
        <?php endif; ?>
        
        </form>
      </div>
    </div>
    
    <div class="content">
    
    <?php $this->load->view('admin/report/'.$this->controller.'_list'); ?>

    </div>
  </div>
  
</div>

    