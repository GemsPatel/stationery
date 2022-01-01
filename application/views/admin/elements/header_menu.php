    <div id="menu">
    
    <ul class="left" style="display: none;">
    <?php
		$res = executeQuery("SELECT  m.admin_menu_id,m.am_class_name,m.am_parent_id,m.am_name from admin_menu m WHERE m.am_parent_id=0 AND m.am_status=0 ORDER BY am_sort_order");
		$sql = "SELECT admin_menu_id, permission_view FROM permission WHERE admin_user_id=".$this->session->userdata('admin_id')." ";
		$per = getDropDownAry($sql,"admin_menu_id", "permission_view", '', false);
		
		if(is_array($res) && sizeof($res)>0):
			foreach($res as $k=>$ar):
				$href = "";
				if(@$ar['am_class_name'] != '')
				{
					if(array_key_exists($ar['admin_menu_id'],$per))
						$href = ($per[$ar['admin_menu_id']] == 0)? ' href="'.site_url('admin/'.@$ar['am_class_name'].'').'" ':' onClick="return  permissionDenied(\'View\');" ';
					else
						$href = ' onClick="return  permissionDenied(\'View\');" ';
				}
	?>
    	<li id="<?php echo strtolower($ar['am_name']); ?>"><a class="top" <?php echo $href; ?>><?php echo $ar['am_name']; ?></a>
	      	<ul>
			    <?php
	        		adminmenuListing($ar['admin_menu_id'],$per);
			   	?>
	        </ul>
    	</li>
    <?php
			endforeach;
		endif;	
	?>
      
    </ul>
   
    <ul class="left" style="display: none;">
    
      <li>  
	    <?php
			$sql = "SELECT it_key, it_name FROM inventory_type WHERE it_status=0";
			$manArr = getDropDownAry($sql,"it_key", "it_name", array('' => "Select Inventory"), false);
			echo form_dropdown('it_key',$manArr,$this->session->userdata("IT_KEY"),'style=" margin-top:6px; margin-right:16px; " onchange="updateInventorySession(this.value, \'\');" ');
		?>
      </li>
    
    
	  <li>  
	    <?php
			$sql = "SELECT manufacturer_key, manufacturer_name FROM manufacturer WHERE manufacturer_status=0";
			$manArr = getDropDownAry($sql,"manufacturer_key", "manufacturer_name", array('' => "Select Language"), false);
			echo form_dropdown('lang',$manArr,$this->session->userdata("LANG"),'style=" margin-top:6px; margin-right:16px; " onchange="updateLang(this);" ');
		?>
      </li>
      <li id="store"><a href="<?php echo base_url()?>" target="_blank" class="top">View Site</a></li>
      <li><a class="top" href="<?php echo site_url('admin/random/logout')?>">Logout</a></li>
    </ul>
  </div>
  
	<?php
    
        function adminmenuListing($menu_primary_id,$per)
        {
            $res = executeQuery("SELECT  m.admin_menu_id,m.am_class_name,m.am_parent_id,m.am_name from admin_menu m WHERE m.am_parent_id=".$menu_primary_id." AND m.am_status=0 ORDER BY am_sort_order");
            if(!empty($res)):
                foreach($res as $k=>$ar):
    
                $href = "";
                if(@$ar['am_class_name'] != '')
                {
                    if(array_key_exists($ar['admin_menu_id'],$per))
                        $href = ($per[$ar['admin_menu_id']] == 0)? ' href="'.site_url('admin/'.@$ar['am_class_name'].'').'" ':' onClick="return  permissionDenied(\'View\');" ';
                    else
                        $href = ' onClick="return  permissionDenied(\'View\');" ';
                }
                
                $cnt = getField("admin_menu_id","admin_menu","am_parent_id",$ar['admin_menu_id']);
                    if((int)$cnt>0):
    ?>
                    <li><a class="parent" <?php echo $href; ?>><?php echo $ar['am_name']; ?></a>
                      <ul>
    <?php
                    adminmenuListing($ar['admin_menu_id'],$per);
    ?>
                      </ul>
                    </li>
    <?php
                    else:
    ?>
                    <li><a <?php echo $href; ?>><?php echo $ar['am_name']; ?></a></li>
    <?php				
                    endif;				
                endforeach;
            endif;	
        }
    
    ?>