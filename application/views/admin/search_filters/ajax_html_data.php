	<form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/searchFilterForm')?>">
     <fieldset>
      <legend>Price Filter</legend>
      <table class="list">
      <tbody>
		  <?php
			if( MANUFACTURER_ID == 7 )
			{
				$result = executeQuery("SELECT * FROM filters 
										WHERE inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
										filters_table_name='Price_Filter'");
			}
			else
			{
				$result = executeQuery("SELECT fc.* 
										FROM filters f 
										INNER JOIN filters_cctld fc 
										ON ( fc.manufacturer_id = ".MANUFACTURER_ID." AND fc.filters_id=f.filters_id ) 
										WHERE f.inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND  
										fc.filters_table_name='Price_Filter' ");
			}
		  
			$idA = array();
			if(!empty($result))
			{
			  $idA = explode("|",$result[0]['filters_table_id']);
			}
          ?>
          
          <tr>
            <td class="left hide" width="80%">Min Price:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="min_price" value="<?php echo (isset($idA))?@$idA[0]:''; ?>" />
            </td>
            <td class="left hide" width="20%">Sort Order: &nbsp;&nbsp;&nbsp;
                <input type="text" name="price_filters_sort_order" value="<?php echo (isset($result[0]['filters_sort_order']))?@$result[0]['filters_sort_order']:''; ?>" />
            </td>
          </tr>
          <tr>
            <td class="left hide" width="80%">Difference:&nbsp;&nbsp;&nbsp;
                <input type="text" name="diff_price" value="<?php echo (isset($idA))?@$idA[1]:''; ?>" />
            </td>
            <td class="left" width="20%">Price Filter:&nbsp;&nbsp;&nbsp;
                 <select name="price_filter_status">
                     <option value="1" <?php echo (isset($result[0]['filters_status']))?((@$result[0]['filters_status'] == 1)?'selected="selected"':''):''; ?>>Disabled</option>
                     <option value="0" <?php echo (isset($result[0]['filters_status']))?((@$result[0]['filters_status'] == 0)?'selected="selected"':''):''; ?>>Enabled</option>
                 </select>
            </td>
          </tr>
          <tr>
            <td class="left hide" width="100%" colspan="2">Max Price:&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="max_price" value="<?php echo (isset($idA))?@$idA[2]:''; ?>" />
            </td>
            
            <td class="left" width="100%" colspan="2">
            	To update <b>Price Filter Range</b> got to Menu >> Localisation >> Currencies module.  
            </td>
          </tr>
          
          
          <tr>
          </tr>
          <tr>
          </tr>
      </tbody>
      </table>
     </fieldset>
     
     
		<?php
		/**
		 * 
		 */	
		if( hewr_isGenderOriented() ):	
		
			if( MANUFACTURER_ID == 7 )
			{
		 	  	$res = executeQuery("SELECT * FROM filters 
									 WHERE inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
									 filters_table_name='Gender_Filter'");
			}
			else
			{
			 	  	$res = executeQuery("SELECT fc.* 
										FROM filters f 
										INNER JOIN filters_cctld fc 
										ON ( fc.manufacturer_id = ".MANUFACTURER_ID." AND fc.filters_id=f.filters_id ) 
										WHERE f.inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
										fc.filters_table_name='Gender_Filter'");
			}


		?>  
	        <fieldset>
	            <legend>Gender</legend>
				<table class="list">
	              <tbody>
	              	<tr>
	                  <td>Filter Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                      <input type="text" name="gender_filters_name" value="<?php echo (isset($res[0]['filters_name']) && @$res[0]['filters_name'] != "")?@$res[0]['filters_name']:@$_POST['gender_filters_name']; ?>" />
	                  </td>
	                  <td class="left" width="50%">Sort Order:&nbsp;&nbsp;&nbsp;
	                      <input type="text" name="gender_filters_sort_order" value="<?php echo (isset($res[0]['filters_sort_order']))?@$res[0]['filters_sort_order']:''; ?>" />
	                  </td>
	                </tr>
	              	<tr>
	                  <td class="left" width="80%"></td>
	                  <td class="left" width="20%">Status:&nbsp;&nbsp;&nbsp;
	                     <select name="gender_filter_status">
	                         <option value="1"  <?php echo (isset($res[0]['filters_status']))?((@$res[0]['filters_status'] == 1)?'selected="selected"':''):''; ?>>Disabled</option>
	                         <option value="0"  <?php echo (isset($res[0]['filters_status']))?((@$res[0]['filters_status'] == 0)?'selected="selected"':''):''; ?>>Enabled</option>
	                     </select>
	                  </td>
	                </tr>
	              </tbody>
	          	</table>
	        </fieldset>

		<?php
		
		endif;
		
		/**
		 * CZ only for jewelllery
		 */
		if( $this->session->userdata("IT_KEY") === "JW" ):
		
			if( MANUFACTURER_ID == 7 )
			{
		 	  	$res = executeQuery("SELECT * 
									 FROM filters 
									 WHERE inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
									 filters_table_name='CZ'");
			}
			else
			{
		 	  	$res = executeQuery("SELECT fc.* 
									 FROM filters f 
									 INNER JOIN filters_cctld fc 
									 ON ( fc.manufacturer_id = ".MANUFACTURER_ID." AND fc.filters_id=f.filters_id ) 
									 WHERE f.inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
									 fc.filters_table_name='CZ'");
			}
		
		?>	
	        <fieldset>
	            <legend>CZ</legend>
				<table class="list">
	              <tbody>
	              	<tr>
	                  <td>Filter Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	                      <input type="text" name="cz_filters_name" value="<?php echo (isset($res[0]['filters_name']) && @$res[0]['filters_name'] != "")?@$res[0]['filters_name']:@$_POST['gender_filters_name']; ?>" />
	                  </td>
	                  <td class="left" width="50%">Sort Order:&nbsp;&nbsp;&nbsp;
	                      <input type="text" name="cz_filters_sort_order" value="<?php echo (isset($res[0]['filters_sort_order']))?@$res[0]['filters_sort_order']:''; ?>" />
	                  </td>
	                </tr>
	              	<tr>
	                  <td class="left" width="80%"></td>
	                  <td class="left" width="20%">Status:&nbsp;&nbsp;&nbsp;
	                     <select name="cz_filter_status">
	                         <option value="1"  <?php echo (isset($res[0]['filters_status']))?((@$res[0]['filters_status'] == 1)?'selected="selected"':''):''; ?>>Disabled</option>
	                         <option value="0"  <?php echo (isset($res[0]['filters_status']))?((@$res[0]['filters_status'] == 0)?'selected="selected"':''):''; ?>>Enabled</option>
	                     </select>
	                  </td>
	                </tr>
	              </tbody>
	          	</table>
	        </fieldset>
        
        <?php
			endif;        
        ?>
        
        <?php

        	/**
        	 * component and attribute derived filters
        	 */
	  		if(is_array($resCompAttrFilter) && sizeof($resCompAttrFilter)>0):
				foreach($resCompAttrFilter as $k=>$ar):
					  if( MANUFACTURER_ID == 7 )
					  {
						$result = executeQuery("SELECT * 
												FROM filters 
												WHERE inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
												filters_table_name='".$ar['table']."' AND 
												filters_table_field_name='".$ar['key']."' ");
					  }
					  else
					  {
				 	  	$result = executeQuery("SELECT fc.* 
												FROM filters f 
												INNER JOIN filters_cctld fc 
												ON ( fc.manufacturer_id = ".MANUFACTURER_ID." AND fc.filters_id=f.filters_id ) 
												WHERE f.inventory_type_id=".inventory_typeIdForKey($this->session->userdata("IT_KEY"))." AND 
												fc.filters_table_name='".$ar['table']."' AND 
												fc.filters_table_field_name='".$ar['key']."' ");
					  }
				
					$idA = array();
					if(!empty($result))
					{
						$idA = explode("|",$result[0]['filters_table_id']);
					}
	  ?>
      <div id="tab-<?php echo strtolower(str_replace(" ","_",$ar['name'])); ?>" style="display: block;">
        <fieldset>
            <legend><?php echo $ar['name']; ?></legend>
			<table class="list">
              <tbody>
              	<tr>
                  <td>Filter Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      <input type="text" name="filters_name[]" value="<?php echo (isset($result[0]['filters_name']) && @$result[0]['filters_name'] != "")?@$result[0]['filters_name']:@$ar['name']; ?>" />
                  </td>
                  <td class="left" width="50%">Sort Order:&nbsp;&nbsp;&nbsp;
                      <input type="text" name="filters_sort_order[]" value="<?php echo (isset($result[0]['filters_sort_order']))?@$result[0]['filters_sort_order']:''; ?>" />
                  </td>
                </tr>
                <tr>  
                  <td width="80%">
                  	<?php
						if(is_array($ar['data']) && sizeof($ar['data'])>0):
							foreach($ar['data'] as $key=>$val):
								if((int)$key > 0):
					?>
                      <label><input type="checkbox" name="filters_table_id[<?php echo $k; ?>][]" value="<?php echo $key ?>" <?php echo ((isset($idA))?(in_array($key,@$idA)?'checked="checked"':''):''); ?> /><?php echo $val; ?></label>&nbsp;&nbsp;
                    <?php
								endif;
                    		endforeach;
						endif;
					?>
                  </td>
                  <td class="left" width="20%">Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  	<select name="filters_status[]">
                         <option value="<?php echo $ar['table']."|".$ar['key']."|"; ?>1" <?php echo (isset($result[0]['filters_status']))?((@$result[0]['filters_status'] == 1)?'selected="selected"':''):''; ?> >Disabled</option>
                         <option value="<?php echo $ar['table']."|".$ar['key']."|"; ?>0" 
						 <?php echo (isset($result[0]['filters_status']))?((@$result[0]['filters_status'] == 0)?'selected="selected"':''):''; ?> >Enabled</option>
                    </select>
                  </td>
                </tr>
           	  </tbody>
            </table>            
        </fieldset>
      </div>
      <?php
	  			endforeach;
			endif;
      ?>
        
    </form>
