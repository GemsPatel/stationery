<?php
class mdl_search_filters extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	
	function getData()
	{
		$res = array();
		$cnt = 0; 
		
		$res[$cnt]['name'] = "Product Category";
		$res[$cnt]['key']  = "category_id";
		$res[$cnt]['val']  = "category_name";
		$res[$cnt]['table']  = "product_categories";
		$sql = "SELECT category_id,category_name FROM product_categories WHERE category_status=0 AND parent_id=0";
		$res[$cnt]['data']  = getDropDownAry($sql,"category_id", "category_name", '', false);
		$cnt++;
		
		$res[$cnt]['name'] = "Occassion";
		$res[$cnt]['key']  = "product_offer_id";
		$res[$cnt]['val']  = "product_offer_name";
		$res[$cnt]['table']  = "product_offer";
		$sql = "SELECT product_offer_id,product_offer_name FROM product_offer WHERE product_offer_status=0";
		$res[$cnt]['data']  = getDropDownAry($sql,"product_offer_id", "product_offer_name", '', false);
		
		
		/**
		 *
		 */
		$compAttrArr = getcompAttrArr(false);
		
		$stnCntI = 0;
		$is_stone_component_taken = false; 
		foreach ($compAttrArr as $compAttrKey=>$compAttrVal)
		{
			if( !$is_stone_component_taken &&  
				( 
					$compAttrVal["ims_input_type"] == "JW_CS" || $compAttrVal["ims_input_type"] == "JW_SS1" ||
					$compAttrVal["ims_input_type"] == "JW_SS2" || $compAttrVal["ims_input_type"] == "JW_SSS" 
				)
			  )	
			{
				$is_stone_component_taken = true; 
				
				$stnCntI = $cnt;
				$res[$cnt]['name'] = "Stone Type";		//filter category name
				$res[$cnt]['key']  = "diamond_type_id";
				$res[$cnt]['val']  = "diamond_type_name";
				$res[$cnt]['table']  = "diamond_type";
				$sql = "SELECT diamond_type_id, diamond_type_name FROM diamond_type WHERE diamond_type_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"diamond_type_id", "diamond_type_name", '', false);
				$cnt++;
				
				$res[$cnt]['name'] = "Stone Color";
				$res[$cnt]['key']  = "diamond_color_id";
				$res[$cnt]['val']  = "diamond_color_name";
				$res[$cnt]['table']  = "diamond_color";
				$sql = "SELECT diamond_color_id,diamond_color_name FROM diamond_color WHERE diamond_color_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"diamond_color_id", "diamond_color_name", '', false);
				$cnt++;
				
				$res[$cnt]['name'] = "Stone Purity";
				$res[$cnt]['key']  = "diamond_purity_id";
				$res[$cnt]['val']  = "diamond_purity_name";
				$res[$cnt]['table']  = "diamond_purity";
				$sql = "SELECT diamond_purity_id,diamond_purity_name FROM diamond_purity WHERE diamond_purity_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"diamond_purity_id", "diamond_purity_name", '', false);
				$cnt++;
				
				$res[$cnt]['name'] = "Stone Shape";
				$res[$cnt]['key']  = "diamond_shape_id";
				$res[$cnt]['val']  = "diamond_shape_name";
				$res[$cnt]['table']  = "diamond_shape";
				$sql = "SELECT diamond_shape_id,diamond_shape_name FROM diamond_shape WHERE diamond_shape_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"diamond_shape_id", "diamond_shape_name", '', false);
				$cnt++;
				
				foreach($res[$stnCntI]['data'] as $k=>$ar)
				{
					$res[$cnt]['name'] = $ar." Category";
					$res[$cnt]['key']  = "diamond_price_id";
					$res[$cnt]['val']  = "diamond_price_name";
					$res[$cnt]['table']  = "diamond_price-".$k."";
					$sql = "SELECT diamond_price_id,diamond_price_name FROM diamond_price WHERE dp_status=0 AND diamond_type_id=".$k."";
					$res[$cnt]['data']  = getDropDownAry($sql,"diamond_price_id", "diamond_price_name", '', false);
					$cnt++;
				}
			}
			elseif( $compAttrVal["ims_input_type"] == "JW_MTL" )
			{
				$res[$cnt]['name'] = "Metal Type";
				$res[$cnt]['key']  = "metal_type_id";
				$res[$cnt]['val']  = "metal_type_name";
				$res[$cnt]['table']  = "metal_type";
				$sql = "SELECT metal_type_id,metal_type_name FROM metal_type WHERE metal_type_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"metal_type_id", "metal_type_name", '', false);
				$cnt++;
				
				$res[$cnt]['name'] = "Metal Color";
				$res[$cnt]['key']  = "metal_color_id";
				$res[$cnt]['val']  = "metal_color_name";
				$res[$cnt]['table']  = "metal_color";
				$sql = "SELECT metal_color_id,metal_color_name FROM metal_color WHERE metal_color_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"metal_color_id", "metal_color_name", '', false);
				$cnt++;
				
				$res[$cnt]['name'] = "Metal Purity";
				$res[$cnt]['key']  = "metal_purity_id";
				$res[$cnt]['val']  = "metal_purity_name";
				$res[$cnt]['table']  = "metal_purity";
				$sql = "SELECT metal_purity_id,metal_purity_name FROM metal_purity WHERE metal_purity_status=0";
				$res[$cnt]['data']  = getDropDownAry($sql,"metal_purity_id", "metal_purity_name", '', false);
				$cnt++;
			}
			elseif( $compAttrVal["ims_input_type"] == "TXT" )
			{
				continue; 
			}
			elseif( $compAttrVal["ims_input_type"] == "SEL" || $compAttrVal["ims_input_type"] == "CHK" || $compAttrVal["ims_input_type"] == "RDO" )
			{
				$res[$cnt]['name'] = $compAttrVal["ims_input_label"];
				$res[$cnt]['key']  = "product_attribute_id";
				$res[$cnt]['val']  = "pa_value";
				$res[$cnt]['table']  = "product_attribute-".$compAttrVal["inventory_master_specifier_id"];
				
				$sql = "";
				if( MANUFACTURER_ID == 7 )
				{
					$sql = "SELECT pa.product_attribute_id, pa.pa_value FROM product_attribute pa
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]."
	   									ORDER BY pa.pa_sort_order ";
				}
				else
				{
					$sql = "SELECT pa.product_attribute_id, pac.pa_value
										FROM product_attribute pa
										INNER JOIN product_attribute_cctld pac
										ON ( pac.product_attribute_id=pa.product_attribute_id AND manufacturer_id=".MANUFACTURER_ID." )
										WHERE pa.pa_status=0 AND pa.inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]."
	   									ORDER BY pa.pa_sort_order ";
				}
				$res[$cnt]['data']  = getDropDownAry($sql,"product_attribute_id", "pa_value", '', false);
				$cnt++;
			}
		}
		
		return $res;
	}

	function saveData()
	{
		$data = $this->input->post();

		/**
		 * @deprecated
		 * default filter name array for filter to be used if not specified by user
		 */
// 		$nameArr = array('Stone Type','Stone Color','Stone Purity','Stone Shape');
// 		$sql = "SELECT diamond_type_id, diamond_type_name FROM diamond_type WHERE diamond_type_status=0";
// 		$resArr  = getDropDownAry($sql,"diamond_type_id", "diamond_type_name", '', false);
// 		foreach($resArr as $k=>$ar)
// 			$nameArr[] = $ar." Category";
		
// 		$nameArr[]="Metal Type";
// 		$nameArr[]="Metal Color";
// 		$nameArr[]="Metal Purity";
// 		$nameArr[]="Product Category";
// 		$nameArr[]="Product Offer";

		$module_item_name = ""; 
		/**
		 * filter data
		 */
		$data_filter = array(); 
		if( INVENTORY_TYPE_ID == 0 )
		{
			$data_filter["inventory_type_id"] = inventory_typeIdForKey($this->session->userdata("IT_KEY")); 
		}
		else 
		{
			$data_filter["inventory_type_id"] = INVENTORY_TYPE_ID;
		}
		
		//price filter
		$module_item_name = $data_filter['filters_name'] = "Price Filter";
		$data_filter['filters_table_name'] = "Price_Filter";
		$data_filter['filters_table_field_name'] = "";
		$data_filter['filters_status'] = $data['price_filter_status'];
		$data_filter['filters_sort_order'] = $data['price_filters_sort_order'];
		//if diff price not specified then fetch default diff from configuration
		$diff_price = ((int)$data['diff_price']==0)? getField("config_value","configuration","config_key","FILTER_DIFF_PRICE"):$data['diff_price'];
		$data_filter['filters_table_id'] = $data['min_price']."|".$diff_price."|".$data['max_price'];
		$key = exeQuery("SELECT filters_id FROM filters 
						 WHERE inventory_type_id=".$data_filter["inventory_type_id"]." AND 
						 filters_table_name='Price_Filter' ", true, "filters_id");

		//if primary key available then update else insert
		if((int)$key > 0)
		{
			//UML: ccTLD -> specific feature
			$this->filterCcTld( true, $key, $data_filter);
			
			$this->db->set('filters_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$key)->update($this->cTableName,$data_filter);
			$last_id = $key;
			$logType = 'E';
		}
		else // insert new row
		{
				$this->db->insert($this->cTableName,$data_filter);
				$last_id = $this->db->insert_id();
				$logType = 'A';
				
			//UML: ccTLD -> specific feature
			$this->filterCcTld( false, $last_id, $data_filter );
		}

		//price filter log
		saveAdminLog($this->router->class, $module_item_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
		
		//other filters
		foreach($data['filters_sort_order'] as $k=>$ar)
		{
			$module_item_name = $data_filter['filters_name'] = ($data['filters_name'][$k] != "")?$data['filters_name'][$k]:"Filter";	//$nameArr[$k]
			$val = $data['filters_status'][$k];
			$statusArr = explode("|",$val);
			$data_filter['filters_table_name'] = $statusArr[0];
			$data_filter['filters_table_field_name'] = $statusArr[1];
			$valArr = (isset($data['filters_table_id'][$k]))?@$data['filters_table_id'][$k]:'';
			$filters_table_id = (!empty($valArr))?implode("|",$valArr):0;
			$data_filter['filters_table_id'] = $filters_table_id;
			$data_filter['filters_status'] = $statusArr[2];
			$data_filter['filters_sort_order'] = $data['filters_sort_order'][$k];
			$key = exeQuery("SELECT filters_id FROM filters
						 	 WHERE inventory_type_id=".$data_filter["inventory_type_id"]." AND 
								   filters_table_name='".$statusArr[0]."' AND 
								   filters_table_field_name='".$statusArr[1]."' ", true, "filters_id");
				
			//if primary key available then update else insert
			if($key > 0)
			{
				//UML: ccTLD -> specific feature
				$this->filterCcTld( true, $key, $data_filter );
				
				$this->db->set('filters_modified_date', 'NOW()', FALSE);
				$this->db->where($this->cAutoId,$key)->update($this->cTableName,$data_filter);
				$last_id = $key;
			}
			else // insert new row
			{
				$this->db->insert($this->cTableName,$data_filter);
				$last_id = $this->db->insert_id();
					
				//UML: ccTLD -> specific feature
				$this->filterCcTld( false, $last_id, $data_filter );
			}
			
			//log for all other filters
			saveAdminLog($this->router->class, $module_item_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
			
		}

		//gender filter
		if( hewr_isGenderOriented() )
		{
			$module_item_name = $data_gen_filter['filters_name'] = ($data['gender_filters_name'] != "")?$data['gender_filters_name']:"Gender";
			$data_gen_filter['filters_table_name'] = "Gender_Filter";
			$data_gen_filter['filters_status'] = $data['gender_filter_status'];
			$data_gen_filter['filters_sort_order'] = $data['gender_filters_sort_order'];
			$key = exeQuery("SELECT filters_id FROM filters
						 	 WHERE inventory_type_id=".$data_filter["inventory_type_id"]." AND
						 	 filters_table_name='Gender_Filter' ", true, "filters_id");
			//if primary key available then update else insert
			if((int)$key > 0)
			{
				//UML: ccTLD -> specific feature
				$this->filterCcTld( true, $key, $data_gen_filter );
					
				$this->db->set('filters_modified_date', 'NOW()', FALSE);
				$this->db->where($this->cAutoId,$key)->update($this->cTableName,$data_gen_filter);
				$last_id = $key;
				$logType = 'E';
			}
			else // insert new row
			{
				$this->db->insert($this->cTableName,$data_gen_filter);
				$last_id = $this->db->insert_id();
				$logType = 'A';
			
				//UML: ccTLD -> specific feature
				$this->filterCcTld( false, $last_id, $data_gen_filter );
			}
			//log for all gender filter
			saveAdminLog($this->router->class, $module_item_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
		}
		
		/**
		 * cz filter
		 * only for jewelllery
		 */
		if( $this->session->userdata("IT_KEY") == "JW" )
		{
			$module_item_name = $data_cz_filter['filters_name'] = ($data['cz_filters_name'] != "")?$data['cz_filters_name']:"CZ Category";
			$data_cz_filter['filters_table_name'] = "cz";
			$data_cz_filter['filters_status'] = $data['cz_filter_status'];
			$data_cz_filter['filters_sort_order'] = $data['cz_filters_sort_order'];
			$key = exeQuery("SELECT filters_id FROM filters
						 	 WHERE inventory_type_id=".$data_filter["inventory_type_id"]." AND
						 	 filters_table_name='cz' ", true, "filters_id");
			//if primary key available then update else insert
			if((int)$key > 0)
			{
				//UML: ccTLD -> specific feature
				$this->filterCcTld( true, $key, $data_cz_filter );
					
				$this->db->set('filters_modified_date', 'NOW()', FALSE);
				$this->db->where($this->cAutoId,$key)->update($this->cTableName,$data_cz_filter);
				$last_id = $key;
				$logType = 'E';
			}
			else // insert new row
			{
				$this->db->insert($this->cTableName,$data_cz_filter);
				$last_id = $this->db->insert_id();
				$logType = 'A';
			
				//UML: ccTLD -> specific feature
				$this->filterCcTld( false, $last_id, $data_cz_filter );
			}
			//log for all gender filter
			saveAdminLog($this->router->class, $module_item_name, $this->cTableName, $this->cAutoId, $last_id, $logType);
		}

		setFlashMessage('success','Records has been updated successfully.');
	}
	
	/**
	 * function will return dia filter price and weight min and max 
	 */
	function filterCcTld( $is_update, $filters_id, &$data )
	{
		$ccTldData = array();

		//ccTLD data
		$ccTldData['filters_id'] = $filters_id;
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
				$ccTldData['filters_status'] = $data['filters_status'];
				
				if( isset($data['filters_sort_order']) )
				{
					$ccTldData['filters_sort_order'] = $data['filters_sort_order'];
					$ccTldData['filters_name'] = $data['filters_name'];
					$ccTldData['filters_table_name'] = $data['filters_table_name'];
					$ccTldData['filters_table_field_name'] = $data['filters_table_field_name'];

					unset( $data['filters_sort_order'] );
					unset( $data['filters_name'] );
					unset( $data['filters_table_name'] );
					unset( $data['filters_table_field_name'] );

					if( isset($data['filters_table_id']) )
					{
						$ccTldData['filters_table_id'] = $data['filters_table_id'];
						unset( $data['filters_table_id'] );
					}
				}

				$this->saveupdfilter( $ccTldData );
				unset( $data['filters_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();

			if( isset($data['filters_sort_order']) )
			{
				$ccTldData['filters_sort_order'] = $data['filters_sort_order'];
				$ccTldData['filters_name'] = $data['filters_name'];
				$ccTldData['filters_table_name'] = $data['filters_table_name'];
				
				//[temp]: until Timeline 0.3 is not resolved
				$ccTldData['filters_table_field_name'] = ( isset( $data['filters_table_field_name'] ) ? $data['filters_table_field_name'] : "" );

				unset( $data['filters_sort_order'] );
				unset( $data['filters_name'] );
				unset( $data['filters_table_name'] );
				unset( $data['filters_table_field_name'] );

				if( isset($data['filters_table_id']) )
				{
					$ccTldData['filters_table_id'] = $data['filters_table_id'];
					unset( $data['filters_table_id'] );
				}
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary perrian.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'filters_id', $filters_id)->update( $this->cTableName, array( 'filters_status' => $statusTemp ) );	
					}
				}
				else
				{
					if( $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['filters_status'];
					}
	
					//ccTLD data
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['filters_status'] = $statusTemp;
					
					$this->saveupdfilter( $ccTldData );
				}
			}
			
			unset( $data['filters_status'] );

		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdfilter( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "filters_cctld_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( $this->cTableName."_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}

	/*
	 * Function will get all sites language
	*/
	function getLanguagesForListing()
	{
		$row = fetchRow("SELECT inventory_type_id, it_name FROM inventory_type WHERE inventory_type_id=".$this->input->get('item_id')." ");
		if( !isEmptyArr($row) )
		{
			$sel_query = " '".$row["inventory_type_id"]."' as item_id, '".$row["it_name"]."' as item_name ";
			return getLanguagesForItemListing( $sel_query );
		}
		else
		{
			return array();
		}
// 		}
// 		else if($this->input->get('insert') == 'true')
// 		{
// 			return getInventoryListing();
// 		}
	}
}