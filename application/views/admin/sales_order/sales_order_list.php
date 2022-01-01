<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons">
          Export to:
          <select name="<?php echo $this->controller.'_export'?>">
              <option value="exportCsv">CSV</option>
          </select>
          <a class="button" href="javascript:void(0);" onclick="javascript:exportData();">Export</a>
          <?php if($this->per_add == 0):?>  
        	  <a class="button" href="<?php echo site_url('admin/'.$this->controller.'?cust=list')?>">Insert</a>
          <?php endif;?>
       	 <?php if($this->per_delete == 0):?>  
        	  <a class="button" title="delete" onclick="return deleteAjaxData()">Delete</a> 
          <?php endif;?> 
      </div>
    </div>
    
    <div class="content">
    <?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>
    </div>
  </div>
  
</div>

<script type="text/javascript">

/**
 * function edit order confirmation
 */
function confirmEditOrder(url, msg)
{
	if( confirm( msg ) )
	{
		window.location.href = url; 
		return true;
	}	
	else 
	{
		return false; 
	}
}

</script>

