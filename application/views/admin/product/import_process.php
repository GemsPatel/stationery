<div id="content">
	<?php $this->load->view('admin/elements/breadcrumb'); ?>  
  	<div class="box">
    	<div class="heading">
      		<h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> Import process</h1>
      		<div class="buttons">
				
      		</div>
    	</div>
    	
    	<div class="content">
    		<span id="import_process_loader" class="fright">
            	<img class="login_priloaded" src="<?php echo asset_url("images/preloader-white.gif");?>" alt="loader">
            	Processing file.....
            </span>
    	</div>
  	</div> 
</div>
<script type="text/javascript">
	importDataProcess( '<?php echo $path;?>', <?php echo $start;?>);
</script>
<?php die;?>