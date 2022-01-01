<script type="text/javascript" src="<?php echo asset_url('js/admin/ckeditor/ckeditor.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(e) {
         // CKEDITOR.replace( 'payment_method_description' );
		  	CKEDITOR.replace( 'template_content',
   			 {
				filebrowserBrowseUrl : 'kcfinder/browse.php',
				filebrowserImageBrowseUrl : 'kcfinder/browse.php?type=Images',
				filebrowserUploadUrl : 'kcfinder/upload.php',
				filebrowserImageUploadUrl : 'kcfinder/upload.php?type=Images'
    		});
	
    });
</script>
<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <!--<div class="htabs" id="tabs">
          <a href="#tab-general" style="display: inline;" class="selected">General</a>
          <a href="#tab-data" style="display: inline;">Data</a>
          <a href="#tab-data1" style="display: inline;">Seo</a>
      </div>-->
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/mailTemplatesForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>General Information</legend>
			<table class="form">
              <tbody>
              <tr>
                  <td><span class="required">*</span> Config Key:</td>
                  <td><input type="text" name="template_key" size="100" value="<?php echo (@$template_key)?$template_key:set_value('template_key');?>" style="text-transform:uppercase" <?php echo (@$this->cPrimaryId) ? 'disabled="disabled"': ''; ?> />
                      <span class="error_msg"><?php echo (@$error)?form_error('template_key'):''; ?> </span>
                      <small class="small_text">For developer reference, do not edit if not required.</small>
                  </td>
                </tr>
              <tr>
                <td><span class="required">*</span> Name:</td>
                <td><input type="text" size="100" name="template_name" value="<?php echo (@$template_name)?$template_name:set_value('template_name');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('template_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span>Subject:</td>
                <td><input type="text" size="100" name="template_subject" value="<?php echo (@$template_subject)?$template_subject:set_value('template_subject');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('template_subject'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span>Description:</td>
                <td><textarea id="template_content" name="template_content"><?php echo (@$template_content)?$template_content:set_value('template_content');?></textarea>
                	<span class="error_msg"><?php echo (@$error)?form_error('template_content'):''; ?> </span>
                </td>
              </tr>
              
              <tr>
              <td>&nbsp;&nbsp;Status:</td>
              <td><select name="template_status">
                  <option value="0" selected="selected">Enable</option>
                  <option value="1" <?php echo (@$template_status=='1' || @$_POST['template_status']=='1')?'selected="selected"':'';?>>Disable</option>
               </select>
               </td>   
            </tr>
            </tbody></table>
            
        </fieldset>
        </div>
       </form>
    </div>
  </div>
  
</div>

<script type="text/javascript">
<!--
$('#tabs a').tabs();
//-->
</script>



