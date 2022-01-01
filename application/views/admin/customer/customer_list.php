<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
      <form method="post" id="export_form" action="<?php echo site_url('admin/'.$this->controller.'/exportData') ?>">
      
        Export to:
        <select name="<?php echo $this->controller.'_export'?>">
            <option value="csv">CSV</option>
            <option value="xls">XLS</option>
        </select>
        <a class="button" href="javascript:void(0)" onclick="$('#export_form').submit();">Export</a>
      	
         <?php if($this->per_add == 0):?>
        	<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/customerForm')?>">Insert</a>
         <?php endif;?>
       	 <?php if($this->per_delete == 0):?>
       		<a class="button" title="delete" onclick="return deleteAjaxData()">Delete</a>
         <?php endif;?>
      </form>
      </div>
    </div>
    
    <!--<div class="pre_loader"><div class="listingPreloader"></div></div>-->
    
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>
    </div>
  </div>
  
</div>

