<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons">
      	<select name="ebay_site_id" onchange="changeAddListBtnText(this)">
        	<option value=""> </option>
            <option value="0">US</option>
            <option value="3">UK</option>
            <option value="15">AU</option>
        </select>
        <a class="button" onclick="return ajaxAddEbayListing()" id="addListingBtn">Transfer to listing inventory</a> &nbsp;&nbsp;
        
        <select name="ebay_site_id">
        	<option value="0">US</option>
        </select>
        <a class="button" href="<?php echo site_url('site/fetchEbayProduct') ?>" target="_blank">Fetch Listing</a> 
        
		<?php if($this->per_delete == 0):?>
        	<a class="button" onclick="return deleteAjaxData()" id="deleteBtn" style="display:none">Delete</a>
        <?php endif;?>
      </div>
    </div>

    <div class="content">
      <?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>
    </div>
  </div>
  
</div>

