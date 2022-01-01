<style>
<!--
.fleft{ float:left;}

.clear-un{ clear:none;}
-->
</style>

<div id="content">
	<?php $this->load->view('admin/elements/breadcrumb');?>
	<div class="box">
		<div class="heading">
			<h1>
				<img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?>
			</h1>
			<div class="buttons">
				<!-- Added on 28-07-2016
					 Gautam Kakadiya -->
					 
				<!-- Import -->
				<?php if($this->per_add == 0 && $this->per_edit == 0):?>   
					<form method="post" id="import_form" enctype="multipart/form-data" action="<?php echo site_url('admin/'.$this->controller.'/importData') ?>" class="fleft">
	          			<input type="file" name="import_csv" style="margin-right : -60px;"/>
	           			<a class="button" href="javascript:void(0)" onclick="$('#import_form').submit();">Import</a>
	           		</form> 

	           	<!-- Export -->
		      		<form method="post" name="export_form" id="export_form" action="<?php echo site_url('admin/'.$this->controller.'/exportData') ?>" class="fleft clear-un">
		      			&nbsp;&nbsp;<input type="text" name="export_limit" value="" placeholder="Export Limit e.g 0,10">
		      			<input type="hidden" name="<?php echo $this->controller.'_export'?>" value="csv">
		      				<a class="button " onclick="$('#export_form').submit();">Export</a>
					</form>
					
				<!-- Export Sample -->
					<a class="button " href="<?php echo site_url('admin/'.$this->controller.'/exportDataSample') ?>">Export Sample</a>
		      	
		      	<?php endif;?>
		      	
		      	<?php if($this->per_add == 0):?>
					<?php if( INVENTORY_TYPE_ID == 0 ): ?>
						<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/inventoryType?insert=true&item_id='._en(@$this->cPrimaryId) );?>">Insert</a>
					<?php else:?>
						<a class="button" href="<?php echo site_url('admin/'.$this->controller.'/productForm'); ?>">Insert</a>
					<?php endif;?>
				<?php endif;?>
				<?php if($this->per_delete == 0):?>
					<a class="button" onclick="return deleteAjaxData()">Delete</a>
				<?php endif;?>
			</div>
		</div>
		<div class="content">
			<?php $this->load->view('admin/'.$this->controller.'/ajax_html_data'); ?>
		</div>
	</div>
</div>