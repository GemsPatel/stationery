<?php
class mdl_product_attribute extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$ims_filter = $this->input->get('ims_filter');
			$it_filter = $this->input->get('it_filter');
			$status_filter = $this->input->get('status_filter');
			
			if(isset($ims_filter) && $ims_filter != "")
			{
				$this->db->where('('.$this->cTableName.'.inventory_master_specifier_id LIKE \''.$ims_filter.'\' )');
			}

			if(isset($it_filter) && $it_filter != "")
			{
				$this->db->where('('.$this->cTableName.'.inventory_type_id LIKE \''.$it_filter.'\' )');
			}
			
			if(isset($status_filter) && $status_filter != "")
			{
				$this->db->where( $this->cTableName.'.pa_status LIKE \''.$status_filter.'\' ');
			}
			
			
			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cTableName.".".$this->cAutoId,'ASC');
				
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " product_attribute.product_attribute_id, 
									 product_attribute.inventory_master_specifier_id, 
									 product_attribute.inventory_type_id,
									 product_attribute.pa_status, 
									 product_attribute.pa_sort_order, 
									 product_attribute_cctld.pa_value,
									 product_attribute_cctld.pa_icon,  
									 product_attribute_cctld.image_size_id, 
									 inventory_master_specifier.ims_tab_label, inventory_type.it_name " );
	 		    
				$this->db->join('product_attribute_cctld', 'product_attribute_cctld.product_attribute_id = product_attribute.product_attribute_id', 'INNER');	
				
				$this->db->where( 'product_attribute_cctld.manufacturer_id', MANUFACTURER_ID);
			}
			else
			{
				$this->db->select( " product_attribute.*, inventory_master_specifier.ims_tab_label, inventory_type.it_name " ); 
			}
			
		}
		else if($this->cPrimaryId != '')
		{
			if( MANUFACTURER_ID != 7 )
			{
				
				$this->db->select( " product_attribute.product_attribute_id, 
									 product_attribute.inventory_master_specifier_id, 
									 product_attribute.inventory_type_id,
									 product_attribute.pa_status, 
									 product_attribute.pa_sort_order, 
									 product_attribute_cctld.pa_value, 
									 product_attribute_cctld.pa_icon,  
									 product_attribute_cctld.image_size_id, 
									 inventory_master_specifier.ims_tab_label, 
									 inventory_type.it_name " );
								
				$this->db->join('product_attribute_cctld', 'product_attribute_cctld.product_attribute_id=product_attribute.product_attribute_id', 'INNER');	
				
				$this->db->where( 'product_attribute_cctld.manufacturer_id', MANUFACTURER_ID);
				$this->db->where( "product_attribute_cctld.".$this->cAutoId, $this->cPrimaryId);
			}
			else
			{
				$this->db->select( " product_attribute.*, inventory_master_specifier.ims_tab_label, inventory_type.it_name " );
				$this->db->where( $this->cTableName.".".$this->cAutoId, $this->cPrimaryId);
			}			
		}

		$this->db->join('inventory_master_specifier', 'inventory_master_specifier.inventory_master_specifier_id = product_attribute.inventory_master_specifier_id', 'INNER');
		$this->db->join('inventory_type', 'inventory_type.inventory_type_id = product_attribute.inventory_type_id', 'INNER');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		$data["inventory_type_id"] = getField("inventory_type_id", "inventory_master_specifier", "inventory_master_specifier_id", $data["inventory_master_specifier_id"]); 
		
		unset($data['item_id']);
		
		
		$paName = @$data['pa_value'];
		$this->db->set('pa_modified_date', 'NOW()', FALSE);
		if($this->cPrimaryId != '')
		{
			//UML: ccTLD -> specific feature
			$this->product_atrributeCcTld( true, $this->cPrimaryId, $data );
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
			$last_id = $this->db->insert_id();
			$logType = 'A';			
			
			//UML: ccTLD -> specific feature
			$this->product_atrributeCcTld( false, $last_id, $data );
		}
		
		//class name, item name, tablename, fieldname, primary id, type A/E/D
		saveAdminLog($this->router->class, @$paName, $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Product Attribute has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		return $last_id;
				
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
		if($ids)
		{			
			
			foreach($ids as $id) //delete auto id
			{
// 				$res = checkIfForeignKeyExistMultiple( array( "product_center_stone", "product_side_stone1", "product_side_stone2", 
// 													  		  "product_side_stones" ), 
// 													   array( "inventory_master_specifier_id"=> getField("inventory_master_specifier_id", $this->cTableName, $this->cAutoId, $id), 
// 													   		  "pcs_diamond_shape_id"=>$id ) );

// 				if(sizeof($res)>0)
// 				{
// 					echo json_encode($res);	
// 					return;
// 				}
// 				else
// 				{
					$getName = getField('pa_value', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					
					//ccTLD
					$this->db->where_in( $this->cAutoId, $id)->delete( $this->cTableName."_cctld" );

					
					$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
				//}
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
		
		$data['pa_status'] = $status;
		
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			//$this->product_atrributeCcTld( true, $cat_id, $data );
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max 	 
	 */
	function product_atrributeCcTld( $is_update, $product_attribute_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['product_attribute_id'] = $product_attribute_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID; 
				
				if( isset($data['pa_value']) )
				{
					$ccTldData['pa_value'] = $data['pa_value'];
			
					unset( $data['pa_value'] );
				}
				
				$this->saveupdproduct_atrributeCcTld( $ccTldData );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			
			if( isset($data['pa_value']) )
			{
				$ccTldData['pa_value'] = $data['pa_value'];
		
				unset( $data['pa_value'] );
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						//$this->db->where( 'product_attribute_id', $product_attribute_id)->update( $this->cTableName, array( 'pa_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$this->saveupdproduct_atrributeCcTld( $ccTldData );
				}
			}
		}
	}

	/**
	 *  
	 */
	function saveupdproduct_atrributeCcTld( $data )
	{
		if( checkIfRowExist( "SELECT 1 FROM product_attribute_cctld 
							  WHERE product_attribute_id=".$data["product_attribute_id"]." AND manufacturer_id=".$data["manufacturer_id"]." " ) )
		{
			$update="";
			foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
			{
				$val = ( $val != '' ) ? $val : '';
				$update .= $key."='".$val."', ";
			}
			$update .= "pac_modified_date=NOW()";
			
			$this->db->query( " UPDATE ".$this->cTableName."_cctld SET ".$update." 
								WHERE product_attribute_id=".$data["product_attribute_id"]." AND manufacturer_id=".$data["manufacturer_id"]." " );
		}
		else 
		{
			$this->db->insert( $this->cTableName."_cctld", $data );
		}
	}

}