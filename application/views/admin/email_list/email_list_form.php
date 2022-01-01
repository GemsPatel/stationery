<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/emailListForm')?>">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Email List </legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td>Email <span class="required">*</span>:</td>
                  <td><input type="text" name="email_id" value="<?php echo (@$email_id)?$email_id:@$_POST['email_id'];?>" style="width:250px;" />
                      <span class="error_msg"> <?php echo (@$error)?form_error('email_id'):''; ?> </span>
                  </td>
                </tr>
                <tr>
                  <td>Optout Level:</td>
                  <td>
                     <select name="el_optout_level">
                         <option value="0" <?php echo (@$el_optout_level=='0' || @$_POST['el_optout_level']=='0')?'selected="selected"':'';?>>0 - No authority send mail</option>
                         <option value="1" <?php echo (@$el_optout_level=='1' || @$_POST['el_optout_level']=='1')?'selected="selected"':'';?>>1 - Email entered by user but still not confirmed by clicking on mail</option>
                         <option value="2" <?php echo (@$el_optout_level=='2' || @$_POST['el_optout_level']=='2')?'selected="selected"':'';?>>2 - Registered and confirmed mail allows mailing to end user</option>
                     </select>
                  </td>
                </tr>
              	<tr>
                  <td>Status:</td>
                  <td>
                     <select name="el_status">
                         <option value="N" <?php echo (@$el_status=='N' || @$_POST['el_status']=='N')?'selected="selected"':'';?>>New</option>
                         <option value="S" <?php echo (@$el_status=='S' || @$_POST['el_status']=='S')?'selected="selected"':'';?>>Subscribed</option>
                         <option value="U" <?php echo (@$el_status=='U' || @$_POST['el_status']=='U')?'selected="selected"':'';?>>Unsubscribed</option>
                     </select>
                  </td>
                </tr>
                <tr>
                  <td>Reference Source <span class="required">*</span>:</td>
                  <td><input type="text" name="el_reference_source" value="<?php echo (@$el_reference_source)?$el_reference_source:@$_POST['el_reference_source'];?>" style="text-transform:uppercase; width:250px;" />
                      <span class="error_msg"><?php echo (@$error)?form_error('el_reference_source'):''; ?> </span>
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
