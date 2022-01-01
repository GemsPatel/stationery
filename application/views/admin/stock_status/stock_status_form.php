<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/StockStatusForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Stock Status </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Status Name :</td>
                  <td><input type="text" name="status_name" value="<?php echo (@$status_name)?$status_name:@$_POST['status_name'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('status_name'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="stock_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$stock_status=='1' || @$_POST['stock_status']=='1')?'selected="selected"':'';?>>Disable</option>
                     </select>
                  </td>
                </tr>
           	  </tbody>
            </table>
            
        </fieldset>
        </div>
                
      </form>
    </div>
  </div>
  
</div>
