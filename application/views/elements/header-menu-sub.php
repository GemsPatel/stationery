<?php
$res = cmn_vw_mainMenu(); 
$segment = $this->uri->segments;

if(!empty($res["res"]))
{
	foreach($res["res"] as $k=>$ar)
	{
		$lowerMenuName = strtolower($ar['front_menu_name']);
		$activeClass='';
		if(!empty($segment))
			$activeClass = ($lowerMenuName == $segment[1]) ? 'active' : '';
		?>
		<li class="home-item-parent other-toggle sm_megamenu_lv1 sm_megamenu_drop parent <?php echo $activeClass ?>">
			<a class="sm_megamenu_head sm_megamenu_drop " href="javascript:void(0)"  id="sm_megamenu_<?php echo$ar['front_menu_id']?>">
				<span class="sm_megamenu_icon sm_megamenu_nodesc">		
					<span class="sm_megamenu_title"><?php echo $ar['front_menu_name'];?></span>
				</span>
			</a>
			<?php
			if( (int)$ar["cnt"] > 0 )
			{
			?>
				<!-- MEGA MENU -->
				<div class="sm_megamenu_dropdown_6columns" >
					<div class="sm_megamenu_content">
						<div class="home-menu-dropdown">
							<?php frontmenuListing( $ar['front_menu_id'], $res["fm_icon_is_display"], $ar["item"], $ar["res"] );  ?>						
						</div>
					</div>
				</div>
			<?php
			}
		echo "</li>";
	}
}
function frontmenuListing($front_menu_id,$fm_icon_is_display,$parent_item, $res, $extra='', $isUL = true )
{
	if(!empty($res))
	{
		foreach($res as $k=>$ar)
		{
			$icon = "";  $item = "";
			  
			if($ar['front_hook_alias'] == "products")
			{
				$item = getField("category_alias","product_categories","category_id",$ar['front_menu_primary_id']);			  
			}
			else if($ar['front_hook_alias'] == "articles")
			{
				$item = getField("article_alias","article","article_id",$ar['front_menu_primary_id']);
			}
					  
			$item = (($item != "")?'/'.$item:'');
			
			$icon = ($fm_icon_is_display)?'<img alt="'.$ar['front_menu_name'].'" src="'.load_image($ar['fm_icon']).'" class="small-inner">':'';
			
			$newResArr = executeQuery( "SELECT front_menu_id, front_menu_primary_id, front_menu_name, fm_icon, front_hook_alias FROM front_menu WHERE fm_parent_id = ".$ar['front_menu_id'] );
			if( !isEmptyArr( $newResArr ) )
			{
				?>
				<ul class="item-home-store detail-home">
					<li class="title-menu-home">
						<?php echo $ar['front_menu_name'];?>
					</li>
					<?php 
					foreach ( $newResArr as $nr=>$newRes )
					{
						frontmenuListing( $newRes['front_menu_id'], $fm_icon_is_display, $parent_item, array( $newRes ), '', false );
					}
					?>
				</ul>
				<?php 
			}
			else 
			{
				if( $isUL )
				{ echo '<ul class="item-home-store detail-home">';}
				?>					
					<li>
						<a href="<?php echo getListingUrl($parent_item, $item);?>" title="<?php echo $ar['front_menu_name'];?>"><?php echo $ar['front_menu_name'];?></a>
					</li>
				<?php
				if( $isUL )
				{ echo '</ul>';}
			}
		}
	}
}
?>