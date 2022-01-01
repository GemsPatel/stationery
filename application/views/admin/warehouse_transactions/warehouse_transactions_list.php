<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
      	<form method="post" id="import_form" enctype="multipart/form-data" action="<?php echo site_url('admin/'.$this->controller.'/importData') ?>">
          <input type="file" name="import_csv" />
           <a class="button" title="format:: productcode,product,weight,srate" href="javascript:void(0)" onclick="$('#import_form').submit();">Import</a>
      	<?php if($this->per_add == 0):?>
      		<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/wtForm')?>">Insert</a>
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

