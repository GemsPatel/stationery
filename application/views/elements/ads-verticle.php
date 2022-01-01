<!-- CAMPAIGING WIDGET --> 
<?php


/**
 * On 27-06-2015 changed this view to support dynamic banners based on
 * category in concern right now.
 */
$category_idS = "";
$res = null;
if( !empty($category_id) )
{
	if( is_array($category_id) )
	{
		$category_idS = implode(',', $category_id);
	}
	else 
	{
		$category_idS = str_replace("|", ",", $category_id);
	}
}

/**
 * 
 */
if( !empty($category_idS) )
{
	if(MANUFACTURER_ID == 7)
	{
		$res = executeQuery( "SELECT banner_image,banner_link,banner_image_alt_text
						  FROM banner b INNER JOIN banner_category_map bcm
						  ON ( bcm.category_id IN ( ".$category_idS." ) AND b.banner_id=bcm.banner_id )
						  WHERE banner_status=0
						  ORDER BY banner_sort_order" );
	}
	else
	{
		$res = executeQuery( "SELECT banner_image,banner_link,banner_image_alt_text
						  FROM banner_cctld b INNER JOIN banner_category_map bcm
						  ON ( bcm.category_id IN ( ".$category_idS." ) AND b.banner_id=bcm.banner_id )
						  WHERE banner_status=0
						  ORDER BY banner_sort_order" );
	}
}

if( !isEmptyArr($res) ):
?>

	<div class="widget_banners">
	
		<?php
			foreach ($res as $k=>$ar):
		?>
				<a class="banner nobord margbot10" href="<?php echo site_url($ar['banner_link'])?>" title="<?php echo @$ar['banner_image_alt_text'];?>">
					<img src="<?php echo @$ar['banner_image']?>" alt="<?php echo @$ar['banner_image_alt_text'];?>" title="<?php echo @$ar['banner_image_alt_text'];?>"/>
				</a>
		<?php
			endforeach;
		?>            
		
	</div>

<?php
else:

	if(MANUFACTURER_ID == 7)
	{
		$res = executeQuery( "SELECT banner_image,banner_link,banner_image_alt_text 
							  FROM banner 
							  WHERE banner_key IN ('BANNER_EIGHT', 'BANNER_NINE', 'BANNER_TEN') AND banner_status=0 
							  ORDER BY banner_sort_order" );
	}
	else
	{
		$res = executeQuery( "SELECT banner_image,banner_link,banner_image_alt_text 
							  FROM banner_cctld 
							  WHERE banner_key IN ('BANNER_EIGHT', 'BANNER_NINE', 'BANNER_TEN') AND banner_status=0 
							  ORDER BY banner_sort_order" );
	}


?>

	<div class="widget_banners">


		<?php
			foreach ($res as $k=>$ar):
		?>
				<a class="banner nobord margbot10" href="<?php echo site_url($ar['banner_link'])?>" title="<?php echo @$ar['banner_image_alt_text'];?>">
					<img src="<?php echo @$ar['banner_image']?>" alt="<?php echo @$ar['banner_image_alt_text'];?>" title="<?php echo @$ar['banner_image_alt_text'];?>"/>
				</a>
		<?php
			endforeach;
		?>            
		
	</div>

<?php
endif;
?>
<!-- //CAMPAIGING WIDGET -->
