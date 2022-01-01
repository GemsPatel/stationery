<form id="form" enctype="multipart/form-data" method="post" >
<div id="tab-option" style="display: block;">
         
    <div id="vtab-option" class="vtabs">
    	<?php 
			$admin_user_id = 0;
			if(count($res)>0):
				foreach($res as $k=>$ar):
					if($admin_user_id == $ar['admin_user_id']) continue;
		?>
          <a href="#tab-option-<?php echo $k; ?>" style="display: inline;" class="selected"><?php echo $ar['admin_user_firstname']; ?></a>
        <?php
					$admin_user_id =  $ar['admin_user_id'];
				endforeach;
			endif;
        ?>
    </div>

<?php
	    displayMenu($res);
/*
+------------------------------------------------------------------------+
	display menu for particular user
	@author Cloudwebs
	@param $res database data 
	@param $admin_user_id
	@key key of array just to use for store in div id
+------------------------------------------------------------------------+
*/
function  displayMenu($res)
{
$admin_user_id = 0;
foreach($res as $key=>$val):
	if($admin_user_id == $val['admin_user_id']) continue;
?>
    <div id="tab-option-<?php echo $key; ?>" class="vtabs-content" style="display: block;">
        <table class="list" style="width:50%">
          <thead>
            <tr>
              <th class="left" width="25%">Menus</th>
              <th class="left" width="5%"><input type="checkbox" name="viewall" class="user-<?php echo $val['admin_user_id']; ?>" data-="v" style="vertical-align: middle;" />View</th>
              <th class="left" width="5%"><input type="checkbox" name="addall"  class="user-<?php echo $val['admin_user_id']; ?>" data-="a" style="vertical-align: middle;" />Add</th>
              <th class="left" width="5%"><input type="checkbox" name="editall" class="user-<?php echo $val['admin_user_id']; ?>" data-="e" style="vertical-align: middle;" />Edit</th>
              <th class="left" width="5%"><input type="checkbox" name="deleteall" class="user-<?php echo $val['admin_user_id']; ?>" data-="d" style="vertical-align: middle;" />Delete</th>
              <th class="left" width="5%"><input type="checkbox" name="allall" class="user-<?php echo $val['admin_user_id']; ?>" style="vertical-align: middle;" />All</th>
            </tr>
          </thead>
<?php 
    foreach($res as $k=>$ar):
		if($ar['admin_user_id'] != $val['admin_user_id']) continue;
?>

          <tbody>
            <tr>
              <td class="left" colspan="6"><b><?php echo $ar['am_name']; ?></b></td>
            </tr>

<?php
        displaySubMenu($ar['admin_menu_id'],$ar['admin_user_id'],null,"&raquo ");
?>

          </tbody>

<?php	
    endforeach;
?>
        </table>
    </div>
<?php
	$admin_user_id =  $val['admin_user_id'];
endforeach;
}

/*
+------------------------------------------------------------------------+
	recursively display sub menu for particular menu
	@author Cloudwebs
	@param $res database data 
	@param $admin_user_id
	@key key of array just to use for store in div id
+------------------------------------------------------------------------+
*/
function  displaySubMenu($admin_menu_id,$admin_user_id,$result,$level)
{
	if(isset($result) && sizeof($result)>0)
	{
		foreach($result as $key=>$row)
		{
			$cnt = getField("admin_menu_id","admin_menu","am_parent_id",$row['admin_menu_id']);
			displayPermission($row,$row['admin_menu_id'],$admin_user_id,((int)$cnt>0?'<b>':'').$level);
			if((int)$cnt>0)
			{
				displaySubMenu($row['admin_menu_id'],$admin_user_id,null,$level."&raquo; ");
			}
		}
	}
	else
	{
		$result = executeQuery("SELECT * FROM admin_menu WHERE am_parent_id=".$admin_menu_id."");
		if(!empty($result))
			displaySubMenu($admin_menu_id,$admin_user_id,$result,$level);
	}
}

/*
+------------------------------------------------------------------------+
	recursively display menu permission for particular user
	@author Cloudwebs
	@param $res database data 
	@param $admin_user_id
	@key key of array just to use for store in div id
+------------------------------------------------------------------------+
*/
function  displayPermission($row,$admin_menu_id,$admin_user_id,$level)
{
	$result = executeQuery("SELECT * FROM permission WHERE admin_menu_id=".$row['admin_menu_id']." AND admin_user_id=".$admin_user_id." ");
	if(!empty($result)):
?>
            <tr class="user-<?php echo $admin_user_id; ?>-tr" data-="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>">
              <td class="left"><?php echo $level.$row['am_name']; ?></td>
              <td class="left" ><input type="checkbox" name="view" value="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> v menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]['permission_view'] == 0)? ' checked="checked" ':'';?>></td>
              <td class="left"><input type="checkbox" name="add" value="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> a menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]['permission_add'] == 0)? ' checked="checked" ':'';?>></td>
              <td class="left"><input type="checkbox" name="edit" value="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> e menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]['permission_edit'] == 0)? ' checked="checked" ':'';?>></td>
              <td class="left"><input type="checkbox" name="delete" value="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> d menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]['permission_delete'] == 0)? ' checked="checked" ':'';?>></td>
              <td class="left"><input type="checkbox" name="all" value="<?php echo $result[0]['permission_id']."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> menu-<?php echo $admin_menu_id; ?>" <?php echo ($result[0]['permission_view'] == 0 && $result[0]['permission_add'] == 0 && $result[0]['permission_edit'] == 0 && $result[0]['permission_delete'] == 0)? ' checked="checked" ':'';?>></td>
            </tr>
<?php
	else:
?>
            <tr class="user-<?php echo $admin_user_id; ?>-tr" data-="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" >
              <td class="left"><?php echo $level.$row['am_name']; ?></td>
              <td class="left"  ><input type="checkbox" name="view" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> v menu-<?php echo $admin_menu_id; ?>"></td>
              <td class="left"><input type="checkbox" name="add" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> a menu-<?php echo $admin_menu_id; ?>"></td>
              <td class="left"><input type="checkbox" name="edit" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> e menu-<?php echo $admin_menu_id; ?>"></td>
              <td class="left"><input type="checkbox" name="delete" value="<?php echo "0"."|".$admin_user_id."|".$admin_menu_id;?>" class="user-<?php echo $admin_user_id; ?> d menu-<?php echo $admin_menu_id; ?>"></td>
              <td class="left"><input type="checkbox" name="all" class="user-<?php echo $admin_user_id; ?> menu-<?php echo $admin_menu_id; ?>" ></td>
            </tr>
<?php
	endif;
}
?>      
      
</div>
</form>   
<script>
/* 
+--------------------------------------------------+
	detect cehckbox change event and call ajax function
	@author Cloudwebs
	
+--------------------------------------------------+
*/
$("input[type=checkbox]").change(function()
{
   showLoader();
   var name = $(this).attr('name');
   var checked = ($(this).is(":checked"))? 1:0;
   var val = '';

   if(name == "viewall" || name == "addall" || name == "editall" || name == "deleteall" )
   {
	   $('.'+$(this).attr('class')+'.'+$(this).attr('data-')).each(function(){
			$(this).prop('checked', checked);
			val += $(this).val()+'|'+checked+'||';
		});
		updatePermission(val.slice(0, -2),name,$(this).attr('class'));
   }
   else if(name == "allall")
   {
	   $('.'+$(this).attr('class')).prop('checked', checked);
	   $('.'+$(this).attr('class')+'-tr').each(function(){
			val += $(this).attr('data-')+'|'+checked+'||';
		});
		updatePermission(val.slice(0, -2),name,$(this).attr('class'));
   }
   else if(name == "all")
   {
	   var obj = $(this).parent().parent();
	   $(obj).find('td').each(function(){
		   $(this).find('input:checkbox').prop('checked', checked);
		});
		val = $(obj).attr('data-')+'|'+checked;
	    var cls = $(this).attr('class');
		updatePermission(val,name,cls.substring(0,cls.indexOf(" ",0)));
   }
   else
   {
	    var cls = $(this).attr('class');
		updatePermission($(this).val()+'|'+checked,name,cls.substring(0,cls.indexOf(" ",0)));
   }
   hideLoader();
	return false;
});

/* 
+--------------------------------------------------+
	ajax function to update user permissions
	@author Cloudwebs
	
+--------------------------------------------------+
*/
function updatePermission(val,type,cls)
{
   var loc = (base_url+'admin/'+lcFirst(controller)+'/update_insertPermission');
   form_data = {val : val, type : type};
   $.post(loc,form_data,function(data){
		var arr = $.parseJSON(data);
		hideNotification();
		$('#content').before(getNotificationHtml(arr['type'],arr['msg']));
		(arr['type'] == "success")?parentCheck(cls):'';
   });
}

/* 
+--------------------------------------------------+
	function checks if all check box of parents are checked
	@author Cloudwebs
	return true if all checked 
+--------------------------------------------------+
*/
function parentCheck(cls)
{
	var is_view_checked = true;
	var is_add_checked = true;
	var is_edit_checked = true;
	var is_delete_checked = true;
	var is_row_checked = true;
   
   $('.'+cls+'-tr').each(function(){
	   is_row_checked = true;
	   $(this).find('input:checkbox').each(function(){
		   var name =  $(this).attr('name');
		   if(name == "all")
		   {
			  (is_row_checked)?$(this).prop('checked',true):$(this).prop('checked',false);					
		   }
		   else if(!$(this).is(":checked"))
		   {
				is_row_checked = false;
				if(name == "view")
					  is_view_checked = false;
				else if(name == "add")
					  is_add_checked = false;			  
				else if(name == "edit")
					  is_edit_checked = false;			  
				else if(name == "delete")
					  is_delete_checked = false;			  
		   }
		});
   });
   
   (is_view_checked)?$($('.'+cls+'[name="viewall"]')).prop('checked',true):$($('.'+cls+'[name="viewall"]')).prop('checked',false);
   (is_add_checked)?$($('.'+cls+'[name="addall"]')).prop('checked',true):$($('.'+cls+'[name="addall"]')).prop('checked',false);
   (is_edit_checked)?$($('.'+cls+'[name="editall"]')).prop('checked',true):$($('.'+cls+'[name="editall"]')).prop('checked',false);
   (is_delete_checked)?$($('.'+cls+'[name="deleteall"]')).prop('checked',true):$($('.'+cls+'[name="deleteall"]')).prop('checked',false);
	
	if(is_view_checked == true && is_add_checked == true && is_edit_checked == true && is_delete_checked == true )
		$($('.'+cls+'[name="allall"]')).prop('checked', true);
	else
		$($('.'+cls+'[name="allall"]')).prop('checked', false);
}
</script>

<script type="text/javascript">
<!--
$('#vtab-option a').tabs();
//-->
</script>