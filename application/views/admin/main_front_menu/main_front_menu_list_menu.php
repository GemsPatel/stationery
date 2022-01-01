<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
      	<?php if($this->per_add == 0):?>
      		<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/menuItemForm?item_id='._en(@$this->cPrimaryId))?>">Insert</a>
            <?php endif;?>
       	<?php if($this->per_delete == 0):?>
      		<a class="button" onclick="return deleteAjaxData()">Delete</a>
 		<?php endif;?>           
     	 	<a class="button" href="<?php echo site_url('admin/'.$this->controller)?>" >Back</a>
      </div>
    </div>
    
    <div class="content">
	<?php $this->load->view('admin/'.$this->controller.'/menu_ajax_html_data'); ?>    
    </div>
  </div>
  
</div>

