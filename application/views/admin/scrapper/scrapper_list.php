<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
         <form id="import_form" action="<?php echo site_url('admin/'.$this->controller.'/importData') ?>" enctype="multipart/form-data" method="post" style="float: left; margin-right: 25px;">
            <input type="file" name="import_csv">
            <a class="button" onclick="$('#import_form').submit();" href="javascript:void(0)">Import</a>
	     </form>
        
        <form method="post" id="export_form" action="<?php echo site_url('admin/'.$this->controller.'/exportData') ?>" style="float:left;">
      	Export to:
        <select name="<?php echo $this->controller.'_export'?>">
            <option value="csv">CSV</option>
        </select>
        <a class="button" href="javascript:void(0)" onclick="$('#export_form').submit();">Export</a>
        </form>
        
        <?php if($this->per_delete == 0):?>
        	<a class="button" onclick="return deleteAjaxData()">Delete</a>
        <?php endif;?>
        
        <a class="button" rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/statusPopupData')?>">Status</a>
        
      </div>
    </div>
    
    <!--<div class="pre_loader"><div class="listingPreloader"></div></div>-->
    
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>
    </div>
  </div>
  
</div>

