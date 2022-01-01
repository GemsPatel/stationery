
<input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
<input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
<form id="form" enctype="multipart/form-data" method="post" action="">
	<input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>" />
	<input type="hidden" name="m_id" value="" />
	<table class="list">
		<thead>
			<?php
            $a = get_sort_order($this->input->get('s'), $this->input->get('f'), 'front_menu_name');
            $b = get_sort_order($this->input->get('s'), $this->input->get('f'), 'fm_status');
            ?>
            <tr id="heading_tr" style="cursor: pointer;">
				<td style="text-align: center;">
					<input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);">
				</td>
				<th align="center">Icon</th>
				<th class="left" f="front_menu_name" s="<?php echo $a; ?>">Name</th>
				<td class="right">Sort Order</td>
				<th class="right" f="fm_status" s="<?php echo $b; ?>">Status</th>
				<td class="right">Action</td>
			</tr>
		</thead>
		<tbody class="ajaxdata">
          <?php
        $extra = "";
        if (count($listArr)) :
            foreach ($listArr as $k => $ar) :
                menuListing($ar, $this->cAutoIdM, $this->controller, "<b>", $this->per_edit);
                if ($ar['fm_parent_id'] == 0)
                    recursiveSubMenu($ar['front_menu_id'], $this->cAutoIdM, $this->controller, null, "&raquo; ", $this->per_edit);
            endforeach ;
        else :
            echo "<tr><td class='center' colspan='7'>No results!</td></tr>";
        endif;
        ?>
		</tbody>
	</table>
</form>

<?php

/*
 * display one tr on each call
 * @param : $ar table row from admin_menu
 */
function menuListing($ar, $cAutoIdM, $controller, $level, $per_edit)
{
    ?>
    <tr id="m<?php echo $ar[$cAutoIdM]?>"
    	<?php echo ($ar['fm_parent_id'] == 0)?'class="clickable" style="cursor:pointer;" ':' class="clickable menu-m'.$ar['fm_parent_id'].'" style="cursor:pointer; display:none;"'; ?>>
    	<td style="text-align: center;"><input type="checkbox"
    		value="m<?php echo $ar[$cAutoIdM]?>"
    		class="menu-<?php echo $ar['fm_parent_id']; ?>" name="selected[]"
    		class="chkbox"></td>
    	<td class="center"><img src="<?php echo load_image($ar['fm_icon'])?>"
    		height="16" width="16" alt="<?php echo $ar['front_menu_name']?>" /></td>
    	<td class="left"><?php echo $level ." ". $ar['front_menu_name']?></td>
    	<!--<td class="right"><?php //echo $ar['fm_sort_order']?></td>-->
    	<td class="right sort_order" data-="<?php echo $ar[$cAutoIdM]?>"
    		rel="<?php echo $ar['fm_sort_order']?>"><?php echo $ar['fm_sort_order']?>
                    </td>
    	<td class="right">
                    <?php
        if ($ar['fm_status'] == '0')
            echo '<a id="ajaxStatusEnabled" rel="1" data-="m' . $ar[$cAutoIdM] . '" title="Enabled"><img src="' . asset_url('images/admin/enabled.gif') . '" alt="enabled"/></a>';
        else
            echo '<a id="ajaxStatusEnabled" rel="0" data-="m' . $ar[$cAutoIdM] . '" title="Disabled"><img src="' . asset_url('images/admin/disabled.gif') . '" alt="disabled"/></a>';
        ?>
                    </td>
    	<td class="right"> <?php if($per_edit == 0):?> [ <a
    		href="<?php echo site_url('admin/'.$controller.'/menuItemForm?edit=true&item_id='._en($ar['front_menu_type_id']).'&m_id='._en($ar[$cAutoIdM]))?>">Edit</a> ]  <?php endif;?> </td>
    </tr>

<?php
}

function recursiveSubMenu($front_menu_id, $cAutoIdM, $controller, $result, $level, $per_edit)
{
    if (isset($result) && sizeof($result) > 0) {
        foreach ($result as $key => $row) {
            $cnt = getField("front_menu_id", "front_menu", "fm_parent_id", $row['front_menu_id']);
            menuListing($row, $cAutoIdM, $controller, ((int) $cnt > 0 ? '<b>' : '') . $level, $per_edit);

            if ((int) $cnt > 0) {
                recursiveSubMenu($row['front_menu_id'], $cAutoIdM, $controller, null, $level . "&raquo; ", $per_edit);
            }
        }
    } else {
        if (MANUFACTURER_ID != 7)
            $result = executeQuery("SELECT * FROM front_menu INNER JOIN front_menu_cctld ON front_menu_cctld.front_menu_id=front_menu.front_menu_id WHERE fm_parent_id=" . $front_menu_id . " AND manufacturer_id=" . MANUFACTURER_ID);
        else
            $result = executeQuery("SELECT * FROM front_menu WHERE fm_parent_id=" . $front_menu_id . "");
        if (! empty($result))
            recursiveSubMenu($front_menu_id, $cAutoIdM, $controller, $result, $level, $per_edit);
    }
}
?>
<script>
$(".clickable").click(function(e)
{
	$(this).nextAll('.menu-'+$(this).attr('id')).toggle();
});

// Select child when parent selected
$("input[name=selected\\[\\]]").change(function()
{
   var id= $(this).parent().parent().attr('id');
   id = id.substring(1);
   if($(this).is(":checked"))
   {
	   	$('.menu-'+id).prop('checked', true);
		$('.menu-'+id).parent().parent().each(function()
		{
			$('.menu-'+$(this).attr('id').substring(1)).prop('checked', true);
		});
   }
   else
   {
	   	$('.menu-'+id).prop('checked', false);
		$('.menu-'+id).parent().parent().each(function()
		{
			$('.menu-'+$(this).attr('id').substring(1)).prop('checked', false);
		});
   }
});
</script>