<div id="content">
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <?php 
		if(@$module_manager_table_name == ''  && @$_REQUEST['module_manager_primary_id'] == ''):
	?>
      <script type="text/javascript">
			$('#content').before(getNotificationHtml('error','Module not selected cancel form and select module first.'));
		</script>
      <?php
    	endif;
	?>
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/moduleManagerForm')?>">
        <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
        <input type="hidden" name="module_manager_table_name" value="<?php echo (isset($module_manager_table_name))? $module_manager_table_name:@$_REQUEST['module_manager_table_name'];?>" />
        <input type="hidden" name="module_manager_field_name" value="<?php echo (isset($module_manager_field_name))? $module_manager_field_name:@$_REQUEST['module_manager_field_name'];?>" />
        <input type="hidden" name="module_manager_primary_id" value="<?php echo (isset($module_manager_primary_id))? $module_manager_primary_id:@$_REQUEST['module_manager_primary_id'];?>" />
        <fieldset>
          <legend>General Information</legend>
          <table class="form">
            <tbody>
              <tr>
                <td>Module Title: <span class="required">*</span></td>
                <td><input type="text" size="40" name="module_manager_title" value="<?php echo (@$module_manager_title)?$module_manager_title:set_value('module_manager_title');?>" />
                  <span class="error_msg"><?php echo (@$error)?form_error('module_manager_title'):''; ?> </span></td>
              </tr>
              <tr>
                <td>Show Title:</td>
                <td><label>
					<?php 
						$checked = 1;
						if(isset($module_manager_title_show_hide))
							$checked = $module_manager_title_show_hide;
						else if(isset($_POST['module_manager_title_show_hide']))
							$checked = $_POST['module_manager_title_show_hide'];
					?>
                    <input type="radio" name="module_manager_title_show_hide" id="show_title" value="1" <?php echo (@$checked == 1)?'checked="checked"':''; ?> />
                    Show</label>
                  &nbsp;&nbsp;
                  <label>
                    <input type="radio" name="module_manager_title_show_hide" id="hide_title"  <?php echo (@$checked == 0)?'checked="checked"':''; ?> value="0" />
                    Hide</label></td>
              </tr>
              <tr>
                <td>Position: <span class="required">*</span></td>
                <td><?php 
					  $setPosition = (@$position_id) ? $position_id : @$_POST['position_id'];
					  $sql = "SELECT banner_position_id, banner_position_name FROM banner_position WHERE banner_position_status=0 ORDER BY banner_position_sort_order";
					  $positionArr = getDropDownAry($sql,"banner_position_id", "banner_position_name", array(), false);
					  echo form_dropdown('position_id',@$positionArr,@$setPosition,'class=""');
					 ?>
                  <span class="error_msg"><?php echo (@$error)?form_error('position_id'):''; ?> </span></td>
              </tr>
              <tr>
                <td>Description:</td>
                <td><textarea name="module_manager_description" rows="3" cols="43"></textarea></td>
              </tr>
              <tr>
                <td>Extra (css) :</td>
                <td><input name="module_manager_css" type="text" value="<?php echo (@$module_manager_css)?$module_manager_css:@$_POST['module_manager_css'];?>" /></td>
              </tr>
              <tr>
                <td>Sort Order:</td>
                <td><input name="module_manager_sort_order" type="text" size="3" value="<?php echo (@$module_manager_sort_order)?$module_manager_sort_order:@$_POST['module_manager_sort_order'];?>" /></td>
              </tr>
              <tr>
                <td>Status:</td>
                <td><select name="module_manager_status">
                    <option value="0" selected="selected">Enabled</option>
                    <option value="1" <?php echo (@$module_manager_status=='1' || @$_POST['module_manager_status']=='1')?'selected="selected"':'';?>>Disabled</option>
                  </select></td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <fieldset>
          <legend>Menu Assignment</legend>
          <table class="form">
            <tbody>
              <tr>
                <td>Module Assignment:</td>
                <td>
                  <select name="module_manager_serialize_menu" onchange="return selectMenuCheckBox(this.value);">
                    <option value="all" selected="selected">All pages</option>
                    <option value="no">No pages</option>
                    <option value="sel">Selected pages</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="2">Menu Selection:<br />
                  <br />
                  <?php
						$sql = "SELECT front_menu_type_id,front_menu_type_name FROM front_menu_type WHERE fmt_status=0";
						$manArr = getDropDownAry($sql,"front_menu_type_id", "front_menu_type_name", '', false);
					?>
                  <div class="htabs" id="tabs">
                    <?php
                    	if(is_array($manArr) && sizeof($manArr)>0):
							foreach($manArr as $k=>$ar):
					?>
                    <a href="#tab-menu<?php echo $k; ?>" style="display: inline;" class="selected"><?php echo $ar; ?></a>
                    <?php
							endforeach;
						endif;
                    ?>
                  </div>
                  
                  <!-- menu -->
                  
                  <?php
						$display = "block";
						$menu_assign = (@$module_manager_serialize_menu != '')?unserialize($module_manager_serialize_menu):'';
                    	if(is_array($manArr) && sizeof($manArr)>0):
							foreach($manArr as $k=>$ar):
				  ?>
                  <div id="tab-menu<?php echo $k; ?>" style="display: <?php echo $display; ?>;">
                    <table class="form">
                      <tbody>
                        <tr>
                          <td>
						  <?php
                    	  	renderMenu($k,(!empty($menu_assign) && isset($menu_assign[$k]))?$menu_assign[$k]:'');
						  ?>
                    	  </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <?php
								$display = "none";
								
							endforeach;
						endif;
                  ?></td>
              </tr>
            </tbody>
          </table>
        </fieldset>
      </form>
    </div>
  </div>
</div>
<?php

	function renderMenu($menu_type_id,$selArr)
	{
		$menuArr = getMultiLevelFrontMenuDropdown($menu_type_id,0,'');
		if(is_array($menuArr) && sizeof($menuArr)>0):
			foreach($menuArr as $k=>$ar):
?>
<label>
  <input type="checkbox" class="menu_assign" name="menu_assignment[]" value="<?php echo $menu_type_id; ?>|<?php echo $k; ?>" <?php echo (!empty($selArr))?(in_array($k,$selArr)?'checked="checked"':''):''; ?> />
  <?php echo str_replace("-","&raquo;",$ar); ?></label>
<br>
<?php
			endforeach;
		endif;
	}
	
?>
<script type="text/javascript">
/**
+-----------------------------------------------------+
	select or de-select menu check boxes as per option selected
+-----------------------------------------------------+
*/
	function selectMenuCheckBox(val)
	{
		if(val == 'all')
		{
			$('.menu_assign').prop('checked',true);
		}
		else if(val == 'no')
		{
			$('.menu_assign').prop('checked',false);
		}
	}
	
$(document).ready(function(){
	<?php
	if(@$this->cPrimaryId == ''):
	?>
		selectMenuCheckBox('all');
	<?php
	endif;	
	?>
});
</script> 
<script type="text/javascript">
<!--
$('#tabs a').tabs();
$('.htabs a').tabs();
//-->
</script>