      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <td width="3%" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"></td>
              <th class="left" width="3%">Icon</th>
              <th class="left" width="37%">Name</th>
              <td class="left" width="30%">Class Name</td>
              <th class="right" width="10%">Sort Order</th>
              <th class="right" width="10%">Status</th>
              <td class="right" width="7%">Action</td>
            </tr>
            
            <tr class="filter">
              <td width="1" style="text-align: center;"></td>
              <td class="left"></td>
              <td class="left"><input type="text" size="50" name="menu_name_filter" value="<?php echo (@$menu_name_filter);?>"></td>
              <td class="left"></td>
              <td class="left"></td>
              <td class="right"><select name="status_filter" id="status_filter">
                                    <option value="" selected="selected"></option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                                </select></td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
          </thead>
          <tbody class="ajaxdata">
          <?php 
		  	$extra = "";
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
					menuListing($ar,$this->cAutoId,$this->controller,"<b>",$this->per_edit);
					if($ar['am_parent_id'] == 0)
					{
						recursiveSubMenu($ar['admin_menu_id'],$this->cAutoId,$this->controller,null,"&raquo; ",$this->per_edit);
					}
		  ?>
		  <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='7'>No results!</td></tr>";
	   	   endif; 
		   ?>
          </tbody>
        </table>
      
	</form>
    
<?php 
/*
	display one tr on each call
	@param : $ar table row from admin_menu
*/
function menuListing($ar,$cAutoId,$controller,$level,$per_edit)
{
?>
            <tr id="<?php echo $ar[$cAutoId]?>" <?php echo ($ar['am_parent_id'] == 0)?'class="clickable" style="cursor:pointer;" ':' class="clickable menu-'.$ar['am_parent_id'].'" style="cursor:pointer; display:none;"'; ?>>
                <td style="text-align: center;"><input type="checkbox" value="<?php echo $ar[$cAutoId]?>" class="menu-<?php echo $ar['am_parent_id']; ?>" name="selected[]" class="chkbox"></td>
                <td class="center"><img src="<?php echo load_image($ar['am_icon'])?>" height="16" width="16" alt="<?php echo $ar['am_name']?>"  /></td>
                <td class="left"><?php echo $level ." ". $ar['am_name']?></td>
                <td class="left"><?php echo $ar['am_class_name']?></td>
                <td class="right"><?php echo $ar['am_sort_order']?></td>	
                <td class="right">
                <?php if($ar['am_status']=='0')
                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
                    else
                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
                ?>
                </td>
                <td class="right"> <?php if($per_edit == 0): ?> [ <a href="<?php echo site_url('admin/'.$controller.'/adminMenuForm?edit=true&item_id='._en($ar[$cAutoId]))?>">Edit</a> ] <?php endif;?> </td>
            </tr>

<?php
}

function recursiveSubMenu($admin_menu_id,$cAutoId,$controller,$result,$level,$per_edit)
{
	if(isset($result) && sizeof($result)>0)
	{
		foreach($result as $key=>$row)
		{
			$cnt = getField("admin_menu_id","admin_menu","am_parent_id",$row['admin_menu_id']);
			menuListing($row,$cAutoId,$controller,((int)$cnt>0?'<b>':'').$level,$per_edit);

			if((int)$cnt>0)
			{
				recursiveSubMenu($row['admin_menu_id'],$cAutoId,$controller,null,$level."&raquo; ",$per_edit);
			}
		}
	}
	else
	{
		$result = executeQuery("SELECT * FROM admin_menu WHERE am_parent_id=".$admin_menu_id." ORDER BY am_sort_order");
		if(!empty($result))
			recursiveSubMenu($admin_menu_id,$cAutoId,$controller,$result,$level,$per_edit);
	}
}
?>
<script>
$(".clickable").click(function(e) {
	$(this).nextAll('.menu-'+$(this).attr('id')).toggle();
});

// Select child when parent selected
$("input[name=selected\\[\\]]").change(function()
{
	var id= $(this).parent().parent().attr('id');
   if($(this).is(":checked"))
   {
	   	$('.menu-'+id).prop('checked', true);
		$('.menu-'+id).parent().parent().each(function(){
			$('.menu-'+$(this).attr('id')).prop('checked', true);
		});
   }
   else
   {
	   	$('.menu-'+id).prop('checked', false);
		$('.menu-'+id).parent().parent().each(function(){
			$('.menu-'+$(this).attr('id')).prop('checked', false);
		});
   }
});
</script>
