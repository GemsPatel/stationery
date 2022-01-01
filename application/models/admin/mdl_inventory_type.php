<?php
class mdl_inventory_type extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cTableNameM = '';
	var $cAutoIdM = '';
	var $cPrimaryIdM = '';
	var $cCategory = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			
			if($f !='' && $s != '' && check_db_column($this->cTableName,$f))
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'ASC');
				
		}
		else if($this->cPrimaryId != '')
		{
			$this->db->where( $this->cAutoId, $this->cPrimaryId);
		}
					
		//$this->db->where('category_status','0');
		
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}

	function getDataInventoryTypeItem()
	{ 
		if($this->cPrimaryIdM == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$ims_input_type_filter = $this->input->get('ims_input_type_filter');
			$ims_name_filter = $this->input->get('ims_name_filter');
			$status_filter = $this->input->get('status_filter');

			if(isset($ims_input_type_filter) && $ims_input_type_filter != "")
			{
				/**
				 * Bug:1288 deprecated multilanguage on 04-05-2016
				 */
// 				if( MANUFACTURER_ID != 7 )
// 					$this->db->where('('.$this->cTableNameM.'_cctld.ims_input_type LIKE \'%'.$ims_input_type_filter.'%\' )');
// 				else
					$this->db->where('('.$this->cTableNameM.'.ims_input_type LIKE \'%'.$ims_input_type_filter.'%\' )');
			}
				
			if(isset($ims_name_filter) && $ims_name_filter != "")
			{
				/**
				 * Bug:1288 deprecated multilanguage on 04-05-2016
				 */
// 				if( MANUFACTURER_ID != 7 )
// 					$this->db->where('('.$this->cTableNameM.'_cctld.ims_tab_label LIKE \'%'.$ims_name_filter.'%\' )');
// 				else
					$this->db->where('('.$this->cTableNameM.'.ims_tab_label LIKE \'%'.$ims_name_filter.'%\' )');
			}
			
			if(isset($status_filter) && $status_filter != "")
			{
				/**
				 * Bug:1288 deprecated multilanguage on 04-05-2016
				 */
// 				if( MANUFACTURER_ID != 7 )
// 					$this->db->where($this->cTableNameM.'_cctld.ims_status LIKE \''.$status_filter.'\' ');
// 				else
					$this->db->where($this->cTableNameM.'.ims_status LIKE \''.$status_filter.'\' ');
			}
			
			$this->db->where('inventory_type_id',$this->cPrimaryId);			
			if( $f !='' && $s != '' && check_db_column($this->cTableNameM,$f) ) 
			{
				$this->db->order_by($f,$s); 
			}
			else
			{
				$this->db->order_by($this->cAutoIdM,'ASC');
			} 
			
			/**
			 * Bug:1288 deprecated multilanguage on 04-05-2016
			 */
// 			if( MANUFACTURER_ID != 7 )
// 			{
// 				$this->db->select( " inventory_master_specifier.inventory_master_specifier_id, inventory_master_specifier.inventory_type_id, 
// 									 inventory_master_specifier.ims_input_type, inventory_master_specifier.ims_tab_label, 
// 									 inventory_master_specifier.ims_fieldset_label, inventory_master_specifier.ims_input_validation, 
// 									 inventory_master_specifier.ims_is_use_in_search_filter, inventory_master_specifier.ims_is_use_in_compare,
// 								 	 inventory_master_specifier_cctld.ims_tab_label, inventory_master_specifier_cctld.ims_fieldset_label, 
// 									 inventory_master_specifier_cctld.ims_input_label, inventory_master_specifier_cctld.ims_default_value, 
// 									 inventory_master_specifier_cctld.ims_sort_order, inventory_master_specifier_cctld.ims_status " ); 
// 	 		    $this->db->join('inventory_master_specifier_cctld', 'inventory_master_specifier_cctld.inventory_master_specifier_id=inventory_master_specifier.inventory_master_specifier_id', 'INNER');	
// 				$this->db->where( 'inventory_master_specifier_cctld.manufacturer_id', MANUFACTURER_ID );
// 			}
			
		}
		else if($this->cPrimaryIdM != '')
		{
			/**
			 * Bug:1288 deprecated multilanguage on 04-05-2016
			 */
// 			if( MANUFACTURER_ID != 7 )
// 			{
// 				$this->db->select( " inventory_master_specifier.inventory_master_specifier_id, inventory_master_specifier.inventory_type_id, 
// 									 inventory_master_specifier.ims_input_type, inventory_master_specifier.ims_tab_label, 
// 									 inventory_master_specifier.ims_fieldset_label, inventory_master_specifier.ims_input_validation, 
// 									 inventory_master_specifier.ims_is_use_in_search_filter, inventory_master_specifier.ims_is_use_in_compare,
// 									 inventory_master_specifier_cctld.ims_tab_label, inventory_master_specifier_cctld.ims_fieldset_label, 
// 									 inventory_master_specifier_cctld.ims_input_label, inventory_master_specifier_cctld.ims_default_value, 
// 									 inventory_master_specifier_cctld.ims_sort_order, inventory_master_specifier_cctld.ims_status " ); 
// 				$this->db->join('inventory_master_specifier_cctld', 'inventory_master_specifier_cctld.inventory_master_specifier_id=inventory_master_specifier.inventory_master_specifier_id', 'INNER');	
// 				$this->db->where( 'inventory_master_specifier_cctld.manufacturer_id', MANUFACTURER_ID );
// 			}
			
			$this->db->where( "inventory_master_specifier.".$this->cAutoIdM,$this->cPrimaryIdM);
			
		}
		
		$res = $this->db->get($this->cTableNameM);
		//echo $this->db->last_query();
		return $res;
	}

	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		
		// unset item id 
		unset($data['item_id']);
		
		if($this->cPrimaryId != '')
		{
			
			$this->db->set('it_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
		}
		
		
		saveAdminLog($this->router->class, @$data['it_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Inventory type has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
	}

	function saveDataInventoryTypeItem()
	{
		$last_id = 0;
		
		/**
		 * post data for insert and edit
		 */ 
		$data = $this->input->post();
		$data['inventory_type_id'] = $this->cPrimaryId;
		
		unset($data['item_id']);
		unset($data['m_id']);
		
		if($this->cPrimaryIdM != '')
		{
			/**
			 * UML: ccTLD -> specific feature
			 * Bug:1288 deprecated multilanguage on 04-05-2016
			 */
// 			$this->inventory_master_specifier_CcTld( true, $this->cPrimaryIdM, $data );
			
			$this->db->set('ims_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoIdM,$this->cPrimaryIdM)->update($this->cTableNameM,$data);
			$last_id = $this->cPrimaryIdM;
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableNameM,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';
			
			/**
			 * UML: ccTLD -> specific feature
			 * Bug:1288 deprecated multilanguage on 04-05-2016
			 */
// 			$this->inventory_master_specifier_CcTld( false, $last_id, $data );
		}
		
		saveAdminLog($this->router->class, $this->input->post('ims_tab_label'), $this->cTableNameM, $this->cAutoIdM, $last_id, $logType);
		setFlashMessage('success','Inventory attribute has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
	}

/*
+----------------------------------------------------------+
	Deleting category. hadle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		$returnArr = array();
		if($ids)
		{		
			$ids = array_reverse($ids);
			
			foreach($ids as $id)
			{
				
				if(strpos(" ".$id,"m") != false)
				{
					$id = substr($id,1);
					
					
					$res = checkIfForeignKeyExist( array( "product_attribute", "product_center_stone", "product_side_stone1", "product_side_stone2",
														  "product_side_stones" ), "inventory_master_specifier_id", $id ); 										
					if(sizeof($res)>0)
					{
						echo json_encode($res);	
						return;
					}
					else
					{
						//records delete log
						$getName = getField('ims_tab_label', $this->cTableNameM, $this->cAutoIdM, $id);
						saveAdminLog($this->router->class, @$getName, $this->cTableNameM, $this->cAutoIdM, $id, 'D');

						$this->db->where_in($this->cAutoIdM,$id)->delete($this->cTableNameM."_cctld");
						$this->db->where_in($this->cAutoIdM,$id)->delete($this->cTableNameM);
					}
				}
				else
				{	
					$res = checkIfForeignKeyExist( array( "inventory_master_specifier", "product_attribute", "product_categories", "product",
								  						  "product_price", "metal_price", "diamond_price" ), "inventory_type_id", $id );
					if(sizeof($res)>0)
					{
						echo json_encode($res);	
						return;
					}
					else
					{
						//records delete log
						$getName = getField('it_name', $this->cTableName, $this->cAutoId, $id);
						saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
						
					   	$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
					}
				}
				
			}
			
			$returnArr['type'] ='success';
			$returnArr['msg'] = count($ids)." records has been deleted successfully.";
		}
		else
		{
			$returnArr['type'] ='error';
			$returnArr['msg'] = "Please select at least 1 item.";
		}
		echo json_encode($returnArr);
	}

/*
+-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids, status
+-----------------------------------------+
*/	
	function updateStatus()
	{
		$status = $this->input->post('status');
		$cat_id = $this->input->post('cat_id');
		if(strpos(" ".$cat_id,"m") != false)
		{
			$data['ims_status'] = $status;
			if(  MANUFACTURER_ID == 7 )	
			{	
				$this->db->where($this->cAutoIdM,substr($cat_id,1));
				$this->db->update($this->cTableM,$data);
			}
			else	//ccTLDs
			{	
				$this->inventory_master_specifier_CcTld( true, substr($cat_id,1), $data );
			}
		}
		else
		{
			$data['it_status'] = $status;
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
	}
	
	
	/**
	 * function will return dia filter price and weight min and max 
	 */
	function inventory_master_specifier_CcTld( $is_update, $inventory_master_specifier_id, &$data )
	{
		$ccTldData = array();

		//ccTLD data
		$ccTldData['inventory_master_specifier_id'] = $inventory_master_specifier_id;
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
				$ccTldData['ims_status'] = $data['ims_status'];
				
				if( isset($data['ims_tab_label']) )
				{
					$ccTldData['ims_tab_label'] = $data['ims_tab_label'];
					$ccTldData['ims_fieldset_label'] = $data['ims_fieldset_label'];
					$ccTldData['ims_input_label'] = $data['ims_input_label'];
					$ccTldData['ims_default_value'] = $data['ims_default_value'];
					$ccTldData['ims_sort_order'] = $data['ims_sort_order'];
					
					unset( $data['ims_tab_label'] );
					unset( $data['ims_fieldset_label'] );
					unset( $data['ims_input_label'] );
					unset( $data['ims_default_value'] );
					unset( $data['ims_sort_order'] );
				}

				$this->saveupdinventory_master_specifierCcTld( $ccTldData );
				unset( $data['ims_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();

			if( isset($data['ims_tab_label']) )
			{
				$ccTldData['ims_tab_label'] = $data['ims_tab_label'];
				$ccTldData['ims_fieldset_label'] = $data['ims_fieldset_label'];
				$ccTldData['ims_input_label'] = $data['ims_input_label'];
				$ccTldData['ims_default_value'] = $data['ims_default_value'];
				$ccTldData['ims_sort_order'] = $data['ims_sort_order'];
				
				unset( $data['ims_tab_label'] );
				unset( $data['ims_fieldset_label'] );
				unset( $data['ims_input_label'] );
				unset( $data['ims_default_value'] );
				unset( $data['ims_sort_order'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'inventory_master_specifier_id', $inventory_master_specifier_id)->update( $this->cTableNameM, array( 'ims_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['ims_status'];
					}
	
					//ccTLD data
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['ims_status'] = $statusTemp;				
	
					$this->saveupdinventory_master_specifierCcTld( $ccTldData );
				}
			}
			
			unset( $data['ims_status'] );
		}
		
		if( IS_CACHE )
		{
			removeCacheKey( '', 'header' );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdinventory_master_specifierCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "imsc_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( $this->cTableNameM."_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}
	
}