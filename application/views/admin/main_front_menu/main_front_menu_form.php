<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/frontMenuForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Front Menu </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td><span class="required">*</span> Menu Name :</td>
                  <td><input type="text" name="front_menu_type_name" value="<?php echo @$front_menu_type_name;?>" />
                      <span class="error_msg"><?php echo (@$error)?form_error('front_menu_type_name'):''; ?> </span>
                  </td>
                </tr>
              	<tr>
                  <td><span class="required">*</span> Short Description :</td>
                  <td><textarea name="fmt_desc"  ><?php echo @$fmt_desc;?></textarea>
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Display Icon:</td>
                  <td>
                     <select name="fm_icon_is_display">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$fm_icon_is_display=='1')?'selected="selected"':'';?>>Disabled</option>
                     </select>
                  </td>
                </tr>
              	<tr>
                  <td>&nbsp;&nbsp;Status:</td>
                  <td>
                     <select name="fmt_status">
                         <option value="0" selected="selected">Enabled</option>
                         <option value="1" <?php echo (@$fmt_status=='1')?'selected="selected"':'';?>>Disabled</option>
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
