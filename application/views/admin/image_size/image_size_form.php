<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/imageSizeForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Image Size </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Image Width:</td>
                  <td><input type="text" size="7" name="image_size_width" value="<?php echo (@$image_size_width)?$image_size_width:@$_POST['image_size_width'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('image_size_width'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Image Height:</td>
                  <td><input type="text" size="7" name="image_size_height" value="<?php echo (@$image_size_height)?$image_size_height:@$_POST['image_size_height'];?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('image_size_height'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;&nbsp; Sort Order:</td>
                  <td><input type="text" size="7" name="image_size_sort_order" value="<?php echo (@$image_size_sort_order)?$image_size_sort_order:@$_POST['image_size_sort_order'];?>" />
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="image_size_status">
                         <option value="0" selected="selected">Enable</option>
                       	 <option value="1" <?php echo (@$image_size_status=='1' || @$_POST['image_size_status']=='1')?'selected="selected"':'';?>>Disable</option>
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
