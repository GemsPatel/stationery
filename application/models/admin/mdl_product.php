<?php
class mdl_product extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';

	/**
	 * inventory type key
	 */
	var $IT_KEY = "";
	
	
	function getData($srchKey = '')
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$product_name = $this->input->get('product_name_filter');
			$product_code = $this->input->get('product_code_filter');
			$product_sku = $this->input->get('product_sku_filter');
			
			if(isset($product_name) && $product_name != "")
			{
				if( MANUFACTURER_ID == 7 )
				{
					$this->db->where('product.product_name LIKE \'%'.$product_name.'%\' ');
				}
				else 
				{
					$this->db->where('product_cctld.product_name LIKE \'%'.$product_name.'%\' ');
				}
			}
			
			if(isset($product_code) && $product_code != "")
			{
				$this->db->join('product_price', 'product_price.product_id=product.product_id', 'INNER');	
				$this->db->where('product_generated_code LIKE \'%'.trim($product_code).'%\' ');
			}

			if(isset($product_sku) && $product_sku != "")
				$this->db->where('product_sku LIKE \'%'.$product_sku.'%\' ');
				
			if(isset($product_status) && $product_status != "")
				$this->db->where('product_status LIKE \''.$product_status.'\' ');

			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by("product.".$this->cAutoId,'ASC');


			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " product.product_id, product.inventory_type_id, product.product_image, product_cctld.product_name, 
									 product.product_sku, product.product_sort_order, product.product_modified_date, product_cctld.product_status as product_status,
									 product.product_angle_in, product.product_alias, product.category_id " );
	 		    $this->db->join('product_cctld', 'product_cctld.product_id=product.product_id', 'INNER');
				//$this->db->where( 'product.product_manufacturer_id', MANUFACTURER_ID);
				$this->db->where( 'product_cctld.manufacturer_id', MANUFACTURER_ID);
			}
			$res = $this->db->get($this->cTableName);
			//echo $this->db->last_query();
			return $res;
		}
		else if($this->cPrimaryId != '')
		{
			$sql = '';
			
			if( MANUFACTURER_ID == 7 )
			{
				$sql = " SELECT DISTINCT p.*,v.*,cs.product_center_stone_weight,cs.product_center_stone_size,cs.product_center_stone_total,cs.pcs_diamond_shape_id,
								  ss1.product_side_stone1_weight,ss1.product_side_stone1_size,ss1.product_side_stone1_total,ss1.pss1_diamond_shape_id,
								  ss2.product_side_stone2_weight,ss2.product_side_stone2_size,ss2.product_side_stone2_total,ss2.pss2_diamond_shape_id, 
			
								  cs.inventory_master_specifier_id as cs_inventory_master_specifier_id, 
								  ss1.inventory_master_specifier_id as ss1_inventory_master_specifier_id, 
								  ss2.inventory_master_specifier_id as ss2_inventory_master_specifier_id
						
									";
				
				if( $this->IT_KEY == "JW" )
				{	$sql .=",mt.product_metal_weight, mt.inventory_master_specifier_id as mt_inventory_master_specifier_id ";		} 
				
// 				$sql .= " FROM product p LEFT JOIN product_value v
// 								  ON v.product_id=p.product_id 
// 								  LEFT JOIN product_center_stone cs 
// 								  ON (cs.product_id=p.product_id AND product_center_stone_status=0) 
// 								  LEFT JOIN inventory_master_specifier cs_ims 
// 								  ON (cs_ims.inventory_master_specifier_id=cs.inventory_master_specifier_id)
// 								  LEFT JOIN product_side_stone1 ss1
// 								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0)
// 								  LEFT JOIN inventory_master_specifier ss1_ims 
// 								  ON (ss1_ims.inventory_master_specifier_id=ss1.inventory_master_specifier_id)
// 								  LEFT JOIN product_side_stone2 ss2
// 								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0) 
// 								  LEFT JOIN inventory_master_specifier ss2_ims 
// 								  ON (ss2_ims.inventory_master_specifier_id=ss2.inventory_master_specifier_id) ";

				$sql .= " FROM product p LEFT JOIN product_value v
								  ON v.product_id=p.product_id
								  LEFT JOIN product_center_stone cs
								  ON (cs.product_id=p.product_id AND product_center_stone_status=0)

								  LEFT JOIN product_side_stone1 ss1
								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0)

								  LEFT JOIN product_side_stone2 ss2
								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0) ";
				
								  
				if( $this->IT_KEY == "JW" )
				{	$sql .="LEFT JOIN product_metal mt 
							ON mt.product_id=p.product_id ";	}
				
				$sql .= " WHERE p.product_id=".$this->cPrimaryId." ";
			}
			else
			{
				$sql = " SELECT DISTINCT p.*,
								  pc.product_name as product_name,
								  pc.product_short_description as product_short_description,
								  pc.product_description as product_description,
								  pc.custom_page_title as custom_page_title,
								  pc.meta_keyword as meta_keyword,
								  pc.meta_description as meta_description,
								  pc.robots as robots,
								  pc.author as author,
								  pc.content_rights as content_rights,
								  
								  pc.product_metal_priority_id as product_metal_priority_id, pc.product_cs_priority_id as product_cs_priority_id, 
								  pc.product_ss1_priority_id as product_ss1_priority_id, pc.product_ss2_priority_id as product_ss2_priority_id, 
								  pc.product_status as product_status, 
								  v.*,cs.product_center_stone_weight,csc.product_center_stone_size,cs.product_center_stone_total,cs.pcs_diamond_shape_id,
								  ss1.product_side_stone1_weight,ss1c.product_side_stone1_size,ss1.product_side_stone1_total,ss1.pss1_diamond_shape_id,
								  ss2.product_side_stone2_weight,ss2c.product_side_stone2_size,ss2.product_side_stone2_total,ss2.pss2_diamond_shape_id, 

								  cs.inventory_master_specifier_id as cs_inventory_master_specifier_id, 
								  ss1.inventory_master_specifier_id as ss1_inventory_master_specifier_id, 
								  ss2.inventory_master_specifier_id as ss2_inventory_master_specifier_id
												
									";
				
				if( $this->IT_KEY == "JW" )
				{	$sql .= ", mt.product_metal_weight, mt.inventory_master_specifier_id as mt_inventory_master_specifier_id ";		}
				
// 				$sql .= " FROM product p LEFT JOIN product_cctld pc 
// 								  ON ( pc.product_id=p.product_id AND pc.manufacturer_id=".MANUFACTURER_ID." ) 
// 								  LEFT JOIN product_value v
// 								  ON v.product_id=p.product_id 

// 								  LEFT JOIN product_center_stone cs 
// 								  ON (cs.product_id=p.product_id AND product_center_stone_status=0) 
// 								  LEFT JOIN product_center_stone_cctld csc
// 								  ON (csc.product_center_stone_id=cs.product_center_stone_id)		
// 								  LEFT JOIN inventory_master_specifier cs_ims 
// 								  ON (cs_ims.inventory_master_specifier_id=cs.inventory_master_specifier_id)
// 								  LEFT JOIN inventory_master_specifier_cctld cs_imsc 
// 								  ON (cs_imsc.inventory_master_specifier_id=cs_ims.inventory_master_specifier_id)
								  
// 								  LEFT JOIN product_side_stone1 ss1
// 								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0)
// 								  LEFT JOIN product_side_stone1_cctld ss1c
// 								  ON (ss1c.product_side_stone1_id=ss1.product_side_stone1_id)
// 								  LEFT JOIN inventory_master_specifier ss1_ims 
// 								  ON (ss1_ims.inventory_master_specifier_id=ss1.inventory_master_specifier_id)
// 								  LEFT JOIN inventory_master_specifier_cctld ss1_imsc 
// 								  ON (ss1_imsc.inventory_master_specifier_id=ss1_ims.inventory_master_specifier_id)
								  		
// 								  LEFT JOIN product_side_stone2 ss2
// 								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0) 
// 								  LEFT JOIN product_side_stone2_cctld ss2c
// 								  ON (ss2c.product_side_stone2_id=ss2.product_side_stone2_id) 
// 								  LEFT JOIN inventory_master_specifier ss2_ims 
// 								  ON (ss2_ims.inventory_master_specifier_id=ss2.inventory_master_specifier_id)
// 								  LEFT JOIN inventory_master_specifier_cctld ss2_imsc 
// 								  ON (ss2_imsc.inventory_master_specifier_id=ss2_ims.inventory_master_specifier_id)	"; 

				$sql .= " FROM product p LEFT JOIN product_cctld pc
								  ON ( pc.product_id=p.product_id AND pc.manufacturer_id=".MANUFACTURER_ID." )
								  LEFT JOIN product_value v
								  ON v.product_id=p.product_id
				
								  LEFT JOIN product_center_stone cs
								  ON (cs.product_id=p.product_id AND product_center_stone_status=0)
								  LEFT JOIN product_center_stone_cctld csc
								  ON (csc.product_center_stone_id=cs.product_center_stone_id AND csc.manufacturer_id=".MANUFACTURER_ID." )
				
								  LEFT JOIN product_side_stone1 ss1
								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0)
								  LEFT JOIN product_side_stone1_cctld ss1c
								  ON (ss1c.product_side_stone1_id=ss1.product_side_stone1_id AND ss1c.manufacturer_id=".MANUFACTURER_ID." )
				
								  LEFT JOIN product_side_stone2 ss2
								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0)
								  LEFT JOIN product_side_stone2_cctld ss2c
								  ON (ss2c.product_side_stone2_id=ss2.product_side_stone2_id AND ss2c.manufacturer_id=".MANUFACTURER_ID." ) ";
				
				
				if( $this->IT_KEY == "JW" )
				{	$sql .= " LEFT JOIN product_metal mt 
								  ON mt.product_id=p.product_id ";		}
				
				$sql .= " WHERE p.product_id=".$this->cPrimaryId." "; 
			} 
			
			$resP = $this->db->query( $sql );
			//echo $this->db->last_query();

            $sql = "SELECT category_id, category_id FROM product_center_stone WHERE product_id=".$this->cPrimaryId." AND product_center_stone_status=0";
            $resCS = getDropDownAry( $sql, "category_id", "category_id", '', false);

            $sql = "SELECT category_id, category_id FROM product_side_stone1 WHERE product_id=".$this->cPrimaryId." AND product_side_stone1_status=0";
            $resSS1 = getDropDownAry( $sql, "category_id", "category_id", '', false);

            $sql = "SELECT category_id, category_id FROM product_side_stone2 WHERE product_id=".$this->cPrimaryId." AND product_side_stone2_status=0";
            $resSS2 = getDropDownAry( $sql, "category_id", "category_id", '', false);

            $sql = "SELECT category_id, category_id FROM product_metal WHERE product_id=".$this->cPrimaryId." AND product_metal_status=0";
            $resM = getDropDownAry( $sql, "category_id", "category_id", '', false);

            /**
             * 
             */
            if( MANUFACTURER_ID == 7 )
            {
            	$sql = "SELECT psss.inventory_master_specifier_id, category_id, product_stone_number, product_side_stones_weight, psss_diamond_shape_id, 
            			product_side_stones_size, product_side_stones_total
	            		FROM product_side_stones psss
	            		WHERE product_id=".$this->cPrimaryId." AND
	            			  product_side_stones_status=0
	            		GROUP BY product_stone_number";
            }
            else 
            {
            	$sql = "SELECT psss.inventory_master_specifier_id, category_id, product_stone_number, product_side_stones_weight, 
	            		psss_diamond_shape_id, psssc.product_side_stones_size, product_side_stones_total 
	            		FROM product_side_stones psss
	            		LEFT JOIN product_side_stones_cctld psssc
	            		ON ( psssc.product_side_stones_id=psss.product_side_stones_id AND psssc.manufacturer_id=".MANUFACTURER_ID." )	
	            		WHERE product_id=".$this->cPrimaryId." AND
	            			  product_side_stones_status=0
	            		GROUP BY product_stone_number";
            }
			$product_side_stonesData = $this->db->query( $sql )->result_array();
			
			return array('resP'=>$resP,'resCS'=>$resCS,'resSS1'=>$resSS1,'resSS2'=>$resSS2,'resM'=>$resM,'product_side_stonesData'=>$product_side_stonesData);
		}
		
	}
	
	function saveData()
	{
		$logType = "";
		
		$last_id = $this->saveItem( false, $logType );
		
		saveAdminLog($this->router->class, $this->input->post('product_name'), $this->cTableName, $this->cAutoId, $last_id, $logType);
		setFlashMessage('success','Product has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		return $last_id;
	}
	
	function saveItem( $is_external, &$logType )
	{
		/**
		 *
		 */
		$compAttrArr = getcompAttrArr(false);
		
		//field are stored in product table
		
		/**
		 * change date 24-Apr-15
		*/
		$dt_prod['product_manufacturer_id'] = 7;
		
		/**
		 * inventory type
		 */
		if( $this->input->post('inventory_type_id') !== FALSE )
		{
			$dt_prod['inventory_type_id'] = $this->input->post('inventory_type_id');
		}
		
		/**
		 * Added on 01-01-2015
		 */
		$sites_id = $this->input->post('sites_id');
		if( !empty( $sites_id ) && $sites_id != "__SKIP" )
		{
			$dt_prod['sites_id'] = $sites_id;
		}
		
		/**
		 * Change on 13-08-2015
		 *
		 * Only save if passed in post data
		 */
		if( $this->input->post('seller_id') !== FALSE && $this->input->post('seller_id') != "__SKIP" )
		{
			$dt_prod['seller_id'] = $this->input->post('seller_id');
		}
		
		$dt_prod['product_name'] = $this->input->post('product_name');
		
		/**
		 * don't save alias in other language,
		 * [temp]: but what if some sellers are not allowed to use default language? fix it.
		*/
		if( $this->input->post('product_alias') !== FALSE && $this->input->post('product_alias') != "__SKIP" )
		{
			if( (empty($this->cPrimaryId) || MANUFACTURER_ID == 7 || getSysConfig("IS_UM")) && (empty($this->cPrimaryId) ) )
			{
				$dt_prod['product_alias'] = removeMultiDash( $this->input->post('product_alias') );
			}
		}
		 
		$dt_prod['product_sku'] = $this->input->post('product_sku');
		
		if( $this->input->post('category_id') != "__SKIP"  )
		{
			$dt_prod['category_id'] = implode("|",(array)$this->input->post('category_id'));
		}
		
		/**
		 * deprecated
		 */
		//$dt_prod['product_manufacturer_id'] = $this->input->post('product_manufacturer_id');
		
		if( $this->input->post('product_short_description') != "__SKIP"  )
		{
			$dt_prod['product_short_description'] = $this->input->post('product_short_description');
		}
		
		if( $this->input->post('product_description') != "__SKIP"  )
		{
			$dt_prod['product_description'] = $this->input->post('product_description');
		}
		
		if( !$is_external )
		{
			$dt_prod['product_angle_in'] = ( $this->input->post('product_angle_in') ) ? $this->input->post('product_angle_in') : 0;
			$dt_prod['product_offer_id'] = implode("|",(array)$this->input->post('product_offer_id'));
			$dt_prod['product_video'] = $this->input->post('product_video');
			$dt_prod['product_sort_order'] = ( $this->input->post('product_sort_order') ) ? $this->input->post('product_sort_order') : 0;
			$dt_prod['product_gender'] = $this->input->post('product_gender');
			$dt_prod['ring_size_region'] = $this->input->post('ring_size_region');
			$dt_prod['product_metal_priority_id'] = ( $this->input->post('product_metal_priority_id') ) ? $this->input->post('product_metal_priority_id') : 0;$this->input->post('');
			$dt_prod['product_cs_priority_id'] = ( $this->input->post('product_cs_priority_id') ) ? $this->input->post('product_cs_priority_id') : 0;
			$dt_prod['product_ss1_priority_id'] = ( $this->input->post('product_ss1_priority_id') ) ? $this->input->post('product_ss1_priority_id') : 0;
			$dt_prod['product_ss2_priority_id'] = ( $this->input->post('product_ss2_priority_id') ) ? $this->input->post('product_sort_order') : 0;
		}
			
		/**
		 * product approval status can only be controlled by "Admins" not "Sellers"
		 * added on 13-10-2015, so that if only required than item status is effected.
		 * So that if from import process if not desired item status could not be imported
		 */
		if( $this->input->post('product_status') !== FALSE && $this->input->post('product_status') != "__SKIP" )
		{
			$dt_prod['product_status'] = $this->input->post('product_status');
		}
			
		if( $this->input->post('p_seller_publish_status') !== FALSE && $this->input->post('p_seller_publish_status') != "__SKIP" )
		{
			$dt_prod['p_seller_publish_status'] = $this->input->post('p_seller_publish_status');
		}
		
		if( !$is_external )
		{
			$dt_prod['product_accessories'] = $this->input->post('product_accessories');
			$dt_prod['custom_page_title'] = $this->input->post('custom_page_title');
			$dt_prod['meta_description'] = $this->input->post('meta_description');
			$dt_prod['meta_keyword'] = $this->input->post('meta_keyword');
			$dt_prod['robots'] = $this->input->post('robots');
			$dt_prod['author'] = $this->input->post('author');
			$dt_prod['content_rights'] = $this->input->post('content_rights');
		}
		
		/**
		 * update price only if static price inventory
		 */
		if( !hewr_isPriceDynamic() )
		{
			$dt_prod['product_price'] = ( $this->input->post('product_price') ) ? $this->input->post('product_price') : 0;
		}
		else
		{
			$dt_prod['product_price'] = 0;
		}
		
		/**
		 * added if condition On 14-07-2015 to support versioning/cloning in product prices
		 */
		if( $this->input->post('product_discount') !== FALSE )
		{
			$dt_prod['product_discount'] = ( $this->input->post('product_discount') ) ? $this->input->post('product_discount')  : 0;
		}
		
		if( !$is_external )
		{
			$dt_prod['product_shipping_cost'] = $this->input->post('product_shipping_cost');
			$dt_prod['product_cod_cost'] = $this->input->post('product_cod_cost');
			$dt_prod['product_tax_id'] = implode("|",(array)$this->input->post('product_tax_id'));
			$dt_prod['author'] = $this->input->post('author');
			$dt_prod['content_rights'] = $this->input->post('content_rights');
			$dt_prod['product_internal_note'] = $this->input->post('product_internal_note');
			$dt_prod['product_tags'] = $this->input->post('product_tags');
			$dt_prod['product_related_keywords'] = $this->input->post('product_related_keywords');
			$dt_prod['product_related_products_id'] = implode("|",(array)$this->input->post('product_related_products_id'));
			$dt_prod['product_related_category_id'] = implode("|",(array)$this->input->post('product_related_category_id'));
		}
		
		if( $this->input->post('p_vat') !== FALSE )
		{
			$dt_prod['p_vat'] = (float) $this->input->post('p_vat');
		}
		
		/**
		 * below field are stored in product_value table
		 */
		if( hewr_isWarehouseManaged() )
		{
			$dt_prodval['pv_quantity_unit'] = $this->input->post('pv_quantity_unit');
		}
		
		$dt_prodval['product_value_weight'] = $this->input->post('product_value_weight');
		if( $this->input->post('product_value_quantity') != "__SKIP" )
		{
			$dt_prodval['product_value_quantity'] = ( $this->input->post('product_value_quantity') ) ?  $this->input->post('product_value_quantity') : 0;
		}
		
		if( !$is_external )
		{
			$dt_prodval['product_value_height'] = ( $this->input->post('product_value_height') ) ? $this->input->post('product_value_height') : 0;
			$dt_prodval['product_value_width'] = ( $this->input->post('product_value_width') ) ? $this->input->post('product_value_width') : 0;
			$dt_prodval['product_value_weight'] = ( $this->input->post('product_value_weight') ) ? $this->input->post('product_value_weight') : 0;
			$dt_prodval['product_value_quantity'] = ( $this->input->post('product_value_quantity') ) ? $this->input->post('product_value_quantity') : 0;
			$dt_prodval['product_value_notification_level'] = ( $this->input->post('product_value_notification_level') ) ? $this->input->post('product_value_notification_level') : 0;
			$dt_prodval['product_value_maximum_purchase'] = ( $this->input->post('product_value_maximum_purchase') ) ? $this->input->post('product_value_maximum_purchase') : 0;
			$dt_prodval['stock_status_id'] = ( $this->input->post('stock_status_id') ) ? $this->input->post('stock_status_id') : 0;
		}
		
		/**
		 * added on 18-12-2015, for import process
		 */
		if( $this->input->post('pv_qty_cart_increments') !== FALSE )
		{
			$dt_prodval['pv_qty_cart_increments'] = $this->input->post('pv_qty_cart_increments');
		}
		
		//field are stored in product_center_stone table
		// 		$dt_cs['pcs_diamond_shape_id'] = $this->input->post('pss0_diamond_shape_id');
		// 		$dt_cs['product_center_stone_size'] = $this->input->post('product_side_stone0_size');
		// 		$dt_cs['product_center_stone_weight'] = $this->input->post('product_side_stone0_weight');
		// 		$dt_cs['product_center_stone_total'] = $this->input->post('product_side_stone0_total');
		// 		$ss0_p = $this->input->post('ss0_p');
		
		//field are stored in product_side_stone1 table
		// 		$dt_ss1['pss1_diamond_shape_id'] = $this->input->post('pss1_diamond_shape_id');
		// 		$dt_ss1['product_side_stone1_size'] = $this->input->post('product_side_stone1_size');
		// 		$dt_ss1['product_side_stone1_weight'] = $this->input->post('product_side_stone1_weight');
		// 		$dt_ss1['product_side_stone1_total'] = $this->input->post('product_side_stone1_total');
		// 		$ss1_p = $this->input->post('ss1_p');
		
		//field are stored in product_side_stone2 table
		// 		$dt_ss2['pss2_diamond_shape_id'] = $this->input->post('pss2_diamond_shape_id');
		// 		$dt_ss2['product_side_stone2_size'] = $this->input->post('product_side_stone2_size');
		// 		$dt_ss2['product_side_stone2_weight'] = $this->input->post('product_side_stone2_weight');
		// 		$dt_ss2['product_side_stone2_total'] = $this->input->post('product_side_stone2_total');
		// 		$ss2_p = $this->input->post('ss2_p');
		
		//field are stored in product_metal table
		$mt_p = $this->input->post('mt_p');
		
		//$dt_prod['product_alias'] = strtolower(url_title($dt_prod['product_name']));
		
		//if primary id set then we have to make update query
		$product_id = 0;
		$this->db->set('product_modified_date', 'NOW()', FALSE);
		if( !empty($this->cPrimaryId) )
		{
		$product_id = $this->cPrimaryId;
			
			//product category map
			$this->db->query("DELETE FROM product_category_map WHERE product_id=".$product_id."");

			//product product-offer map
			$this->db->query("DELETE FROM product_offer_map WHERE product_id=".$product_id."");
			
			/**
			 * SINGLE image
			 */
			$check_sku = getField("product_sku",$this->cTableName,$this->cAutoId,$this->cPrimaryId);
			
			//check if sku name change
			if( $dt_prod['product_sku'] != $check_sku )
			{
				$source = getField("product_image",$this->cTableName,$this->cAutoId,$this->cPrimaryId);
				$expld = explode("/", $source);
				rename("assets/product/".$expld[2], "assets/product/".$dt_prod['product_sku']);
				$dt_prod['product_image'] = "assets/product/".$dt_prod['product_sku']."/".$expld[3];
			}
			
			if(@$_FILES['product_image_single']['name'] != "")
			{
				$path = getField("product_image",$this->cTableName,$this->cAutoId,$this->cPrimaryId);
				if($path != "")
				{
					$resArr = unlinkFile('./'.$path);
					if($resArr['success'] == false)
					{
						setFlashMessage( 'error', $resArr['error'] );
						redirect('admin/'.$this->router->class);
					}
				}
			
				$path = $this->uploadFolder('product_image_single', 'image', 'product/'.$dt_prod['product_sku']);
				
				$path = $this->resizeImage($path);
			
				$dt_prod['product_image'] = $path;
			}
			
			/**
			 * Image Folder
			 */
			if(@$_FILES['product_image']['name'] != "")
			{
				/**
				 * deleted SKU folder entirely on 14-04-2015
				 */
				$path = getField("product_sku","product","product_id",$this->cPrimaryId);
				$path = "assets/product/".$path;
				
				if( $path != "" )
				{
					$resArr = unlinkFile( './'.$path );
					if( $resArr['success'] == false )
					{
						setFlashMessage( 'error', $resArr['error'] );
						redirect( 'admin/'.$this->router->class );
					}
				}
			
				$path = $this->uploadFolder( 'product_image', 'zip', 'product' );
				$dt_prod['product_image'] = $path;
			}
			
			//UML: ccTLD -> specific feature
			$this->productCcTld( true, $this->cPrimaryId, $dt_prod );

			//update product
			$this->db->where( $this->cAutoId, $this->cPrimaryId )->update( $this->cTableName, $dt_prod );
			$last_id = $this->cPrimaryId;
			$logType = 'E';
			
			//update product_value
			$this->db->where( "product_id", $this->cPrimaryId )->update( "product_value", $dt_prodval );

// 			$update="";
// 			$this->db->where("product_id",$this->cPrimaryId)->update("product_center_stone",array('product_center_stone_status'=>1));
// 			$dt_cs['product_center_stone_status'] = 0;  			//enable only those category which are selected
// 			foreach($dt_cs as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
// 			{
// 				$val = ($val != '')?$val:0;
// 				$update .= $key."='".$val."', ";
// 			}
// 			$update .= "product_center_stone_modified_date=NOW()";
// 			$dt_cs['product_id'] = $this->cPrimaryId;
// 			if(is_array($ss0_p) && sizeof($ss0_p)>0)
// 			{
// 				foreach($ss0_p as $k=>$ar)
// 				{
// 					$dt_cs['category_id'] = $ar;
	
// 					$sql = $this->db->insert_string("product_center_stone",$dt_cs).' ON DUPLICATE KEY UPDATE '.$update;
// 					$this->db->query($sql);
// 				}
// 			}

// 			$update="";
// 			$this->db->where("product_id",$this->cPrimaryId)->update("product_side_stone1",array('product_side_stone1_status'=>1));
// 			$dt_ss1['product_side_stone1_status'] = 0;  			//enable only those category which are selected
// 			foreach($dt_ss1 as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
// 			{
// 				$val = ($val != '')?$val:0;
// 				$update .= $key."='".$val."', ";
// 			}
// 			$update .= "product_side_stone1_modified_date=NOW()";
// 			$dt_ss1['product_id'] = $this->cPrimaryId;
// 			if(is_array($ss1_p) && sizeof($ss1_p)>0)
// 			{
// 				foreach($ss1_p as $k=>$ar)
// 				{
// 					$dt_ss1['category_id'] = $ar;
	
// 					$sql = $this->db->insert_string("product_side_stone1",$dt_ss1).' ON DUPLICATE KEY UPDATE '.$update;
// 					$this->db->query($sql);
// 				}
// 			}

// 			$update="";
// 			$this->db->where("product_id",$this->cPrimaryId)->update("product_side_stone2",array('product_side_stone2_status'=>1));
// 			$dt_ss2['product_side_stone2_status'] = 0;  			//enable only those category which are selected
// 			foreach($dt_ss2 as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
// 			{
// 				$val = ($val != '')?$val:0;
// 				$update .= $key."='".$val."', ";
// 			}
// 			$update .= "product_side_stone2_modified_date=NOW()";
// 			$dt_ss2['product_id'] = $this->cPrimaryId;
// 			if(is_array($ss2_p) && sizeof($ss2_p)>0)
// 			{
// 				foreach($ss2_p as $k=>$ar)
// 				{
// 					$dt_ss2['category_id'] = $ar;
// 					$sql = $this->db->insert_string("product_side_stone2",$dt_ss2).' ON DUPLICATE KEY UPDATE '.$update;
// 					$this->db->query($sql);
// 				}
// 			}

// 			//add stone category if it is more then 3
// 			if(isset( $_REQUEST['stone_cat_3'] ))
// 			{
// 				$this->saveStoneRec( 3, $product_id);
// 			}
			
			/**
			 * Added on 13-04-2015 to delete all attributes at once
			 * delete old attributes
			 */
			deleteCompAttrArr( $product_id, 0, null );
			
				
			/**
			 * dynamic inventory added on 04-03-2015
			 */
			$sideStoneCnt = 0;
			foreach ( $compAttrArr as $compAttrKey => $compAttrVal )
			{
				$this->saveStoneRec( true, $sideStoneCnt, $compAttrVal, $product_id);
				$sideStoneCnt++;
			}
			
			$update="";
			$this->db->where( "product_id", $this->cPrimaryId )->update( "product_metal", array('product_metal_status' => 1 ) );
			$dt_mt['product_metal_status'] = 0;  			//enable only those category which are selected
			foreach($dt_mt as $key=>$val)					//creates updates string to be used in query if record already exist with unique index
			{
				$val = ($val != '')?$val:0;
				$update .= $key."='".$val."', ";
			}
			$update .= "product_metal_modified_date=NOW()";
			$dt_mt['product_id'] = $this->cPrimaryId;
			if( is_array( $mt_p ) && sizeof( $mt_p ) > 0 )
			{
				foreach($mt_p as $k=>$ar)
				{
					$dt_mt['category_id'] = $ar;
					$dt_mt['product_metal_weight'] = (float)$this->input->post('product_metal_weight_'.$ar);
					$sql = $this->db->insert_string("product_metal",$dt_mt).' ON DUPLICATE KEY UPDATE '.$update.', product_metal_weight='.$dt_mt['product_metal_weight'].' ';
					$this->db->query($sql);
				}
			}
		}
		else if( $this->input->post('SKIP_insert_in_import') === FALSE  )// insert new row
		{
			/**
			 * SINGLE image
			 */
			if( !empty( $_FILES['product_image_single']['name'] ) )
			{
				$path = $this->uploadFolder('product_image_single', 'image', 'product/'.$dt_prod['product_sku']);
				/**
				 * old resize deprecated
				*/
				//$path = $this->resizeImage($path);
		
				$dt_prod['product_image'] = $path;
			}
		
			/**
			 * Image Folder
			 */
			if( !empty( $_FILES['product_image']['name'] ) )
			{
				$path = $this->uploadFolder('product_image', 'zip', 'product', $dt_prod['product_sku']);
				$dt_prod['product_image'] = $path;
			}
				
			$this->db->insert($this->cTableName,$dt_prod);
			$last_id = $product_id = $this->db->insert_id();
			$logType = 'A';
		
			//UML: ccTLD -> specific feature
			$this->productCcTld( false, $product_id, $dt_prod );
		
			$dt_prodval['product_id'] = $product_id;
			$this->db->insert("product_value",$dt_prodval);
		
			// 			$dt_cs['product_id'] = $product_id;
			// 			if(is_array($ss0_p) && sizeof($ss0_p)>0)
			// 			{
			// 				foreach($ss0_p as $k=>$ar)
			// 				{
			// 					$dt_cs['category_id'] = $ar;
			// 					$this->db->insert("product_center_stone",$dt_cs);
			// 				}
			// 			}
		
			// 			$dt_ss1['product_id'] = $product_id;
			// 			if(is_array($ss1_p) && sizeof($ss1_p)>0)
			// 			{
			// 				foreach($ss1_p as $k=>$ar)
			// 				{
			// 					$dt_ss1['category_id'] = $ar;
			// 					$this->db->insert("product_side_stone1",$dt_ss1);
			// 				}
			// 			}
		
			// 			$dt_ss2['product_id'] = $product_id;
			// 			if(is_array($ss2_p) && sizeof($ss2_p)>0)
			// 			{
			// 				foreach($ss2_p as $k=>$ar)
			// 				{
			// 					$dt_ss2['category_id'] = $ar;
			// 					$this->db->insert("product_side_stone2",$dt_ss2);
			// 				}
			// 			}
		
			// 			//add stone category if it is more then 3
			// 			if(isset( $_REQUEST['stone_cat_3'] ))
			// 			{
			// 				$this->saveStoneRec( 3, $product_id);
			// 			}
		
			/**
			 * Added on 13-04-2015 to delete all attributes at once
			 * delete old attributes
			*/
			deleteCompAttrArr($product_id, 0, null);
		
			/**
			 * dynamic inventory added on 04-03-2015
			*/
			$sideStoneCnt = 0;
			foreach ($compAttrArr as $compAttrKey=>$compAttrVal)
			{
				$this->saveStoneRec( false, $sideStoneCnt, $compAttrVal, $product_id);
				$sideStoneCnt++;
			}
		
		
			$dt_mt['product_id'] = $product_id;
			if(is_array($mt_p) && sizeof($mt_p)>0)
			{
				foreach($mt_p as $k=>$ar)
				{
					$dt_mt['category_id'] = $ar;
					$dt_mt['product_metal_weight'] = (float)$this->input->post('product_metal_weight_'.$ar);
					$this->db->insert("product_metal",$dt_mt);
				}
			}
		
// 			$ms_sites_product_mapData = array();
// 			if( (int) $this->input->post("sites_id") != 0 )
// 			{
// 				$ms_sites_product_mapData["sites_id"] = (int) $this->input->post("sites_id");
// 			}
// 			else
// 			{
// 				$ms_sites_product_mapData["sites_id"] = SITES_ID;
// 			}
// 			$ms_sites_product_mapData["product_id"] = $last_id;
// 			$ms_sites_product_mapData["spm_status"] = 0;
// 			$this->db->insert("ms_sites_product_map", $ms_sites_product_mapData);
		}
		
		//save product_category_map table data
		if( $this->input->post('category_id') != "__SKIP" )
		{
			$catidArr = $this->input->post('category_id');
			if( !isEmptyArr( $catidArr ) )
			{
				foreach($catidArr as $k=>$ar)
				{
					$this->db->query("INSERT INTO product_category_map values(".$product_id.",".$ar.")");
				}
			}
		}
		
		if( !$is_external )
		{
			//save product_offer_map table data
			$prod_offidArr = $this->input->post('product_offer_id');
			if(isset($prod_offidArr) && is_array($prod_offidArr) && sizeof($prod_offidArr)>0)
			{
				foreach($prod_offidArr as $k=>$ar)
				{
					$this->db->query("INSERT INTO product_offer_map values(".$product_id.",".$ar.")");
				}
			}
		}
		
		
		$is_selection_updated = (int)$this->input->post('is_selection_updated');
		
		
		/**
		 * from 17-03-2015 now it is allowed to always update/insert product_price table,
		 * due to static, warehouse based inventories are introduced.
		 */
		if( ( TRUE || $is_selection_updated==1 ) && ( $this->input->post("product_discounted_price") != "__SKIP" ) )
		{
			if($this->cPrimaryId != '')
			{
				if( IS_LOG )
				{
					//fwrite($fp,'<mode>Edit</mode>');
				}
		
				/**
				 * In update mode change all price status to 1-disabled as some combinations may be deselected
				 */
				// 				if( MANUFACTURER_ID == 7 )
				// 				{
				// 					$this->db->query("update product_price SET product_price_status_temp=1,product_price_modified_date=NOW() WHERE product_id=".$this->cPrimaryId."");
				// 				}
				// 				else
				// 				{
				// 					$this->db->query("update product_price_cctld SET product_price_status_temp=1,product_price_cctld_modified_date=NOW()
				// 									  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
				// 									 ( SELECT product_price_id FROM product_price WHERE product_id=".$this->cPrimaryId." )");
				// 				}
				$this->db->query("update product_price SET product_price_status_temp=1 WHERE product_id=".$this->cPrimaryId."");
			}
			else
			{
				if( IS_LOG )
				{
					//fwrite($fp,'<mode>Insert</mode>');
				}
			}
		
		
			if( IS_LOG )
			{
				//fwrite($fp,'<iscalled>Yes</iscalled>');
			}
		
		
		
			/**
			 * added on 14-07-2015
			 * to support cloning system in product prices
			 */
			$is_process_main_price = true;
			if( $this->input->post("product_price_calculated_price") === FALSE )
			{
				$is_process_main_price = false;
			}
		
			/**
			 * ppi other prices added on 02-07-2015
			 */
			$product_price_identifier_id = $this->input->post("product_price_identifier_id");
			$ppiPrices = array();
			if( !isEmptyArr($product_price_identifier_id) )
			{
				foreach ($product_price_identifier_id as $k1=>$v1)
				{
					$ppiPrices[$v1]["product_price_identifier_id"] = $v1;
					$ppiPrices[$v1]["product_price_calculated_price"] = $this->input->post("product_price_calculated_price_".$v1);
					$ppiPrices[$v1]["product_discount"] = $this->input->post("product_discount_".$v1);
					$ppiPrices[$v1]["product_discounted_price"] = $this->input->post("product_discounted_price_".$v1);
				}
			}
		
			//update/insert product pricing
			update_insertProductPrice(  $product_id, 1, false, false, false,
			$this->input->post("product_price_calculated_price"), $this->input->post("product_discounted_price"), 0,
			$ppiPrices, $is_process_main_price, (int)$this->input->post("product_price_calculated_price_app_bkp"),
			(int)$this->input->post("product_discount_app_bkp"), (int)$this->input->post("product_discounted_price_app_bkp") );
				
		
			// 			if( MANUFACTURER_ID == 7 )
			// 			{
			// 				$this->db->query("update product_price SET product_price_status=product_price_status_temp WHERE product_id=".$product_id."");
			// 			}
			// 			else
			// 			{
			// 				$this->db->query("update product_price_cctld SET product_price_status=product_price_status_temp
			// 								  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
			// 								 ( SELECT product_price_id FROM product_price WHERE product_id=".$product_id." ) ");
			// 			}
			$this->db->query("update product_price SET product_price_status=product_price_status_temp WHERE product_id=".$product_id."");
		
			/**
			 * Send notification email to site admin when product is changed if product_approval_require is true,
			 * in mail send old info and new info in simple table.
			 * Applicable only in update mode. From 30-09-2015 mail will be sent in insert mode also if applicable
			*/
// 			if( getSiteConfig("IS_PAR") == 1 )	//&& !empty($this->cPrimaryId)
// 			{
// 				$is_email_send_require = false;
// 				$data_email = array();
		
// 				/*********************** email template *************************/
		
// 				/**
// 				 * product changed info template
// 				*/
// 				$data_email['es_message'] = "<b>Product Name</b>: ".$this->input->post('product_name')." " . ( !empty($this->cPrimaryId) ? "Updated" : "Added" )  . "<br><br>
// 											<table border='1'>
// 												<tr>
// 													<td style='padding: 10px;'><b>What</b></td>
// 													<td style='padding: 10px;'><b>Existing</b></td>
// 													<td style='padding: 10px;'><b>New Prices</b></td>
// 												</tr>";
		
// 				/**
// 				 * existing/cloned price information
// 				 */
		
// 				//main price
// 				$app_product_price = null;
// 				$cloned_product_price = null;
// 				$app_product_price = getFirstProductPrice( $product_id );
// 				if( !isEmptyArr($app_product_price) )
// 				{
// 					$cloned_product_price = getFirstProductPriceOther($app_product_price["product_price_id"], he_CONSTANT("MPCI"));
		
// 					if( !isEmptyArr($cloned_product_price) )
// 					{
// 						priceNotficationTemplate( "Product Price", $app_product_price, $cloned_product_price, $is_email_send_require, $data_email['es_message'], $this->cPrimaryId );
// 					}
// 				}
		
		
// 				//white label price
// 				if( isPriceApplicable("WP") )
// 				{
// 					$app_product_price = null;
// 					$cloned_product_price = null;
// 					$product_info = getProductCodeInfo( $product_id );
// 					if( !isEmptyArr($product_info) )
// 					{
// 						$app_product_price = getFirstProductPriceOther($product_info["product_price_id"], he_CONSTANT("WPI"));
// 						$cloned_product_price = getFirstProductPriceOther($product_info["product_price_id"], he_CONSTANT("WPCI"));
							
// 						if( !isEmptyArr($cloned_product_price) )
// 						{
// 							if( isEmptyArr($app_product_price) )
// 							{
// 								$app_product_price = array();
// 								$app_product_price["product_price_calculated_price"] = 0;
// 								$app_product_price["product_discount"] = 0;
// 								$app_product_price["product_discounted_price"] = 0;
// 							}
		
// 							priceNotficationTemplate( "White Label Price", $app_product_price, $cloned_product_price, $is_email_send_require, $data_email['es_message'], $this->cPrimaryId );
// 						}
// 					}
// 				}
		
		
// 				$data_email['es_message'] .= "</table>";
		
// 				$is_added = (empty($this->cPrimaryId) ? 1 : 0);
// 				/**
// 				 * approve link
// 				*/
// 				$data_email['es_message'] .= "<br><br><br>
// 											  <a href=\"".site_url( "admin/product/productCloneToOriginal?item_id="._en($product_id)."&is_added=".$is_added."&acc=".GetAdminToken( getSiteConfig("PA") ) )."\">Approve changes</a><br><br><br><br>
// 											  Below are the user details who had made changes<br>
// 											  <b>Seller/Winery ID</b>: ".$this->session->userdata('seller_id')."<br>
// 											  <b>Seller firstname</b>: ".$this->session->userdata('s_firstname')."<br>
// 											  <b>Seller company name</b>: ".$this->session->userdata('s_company_name')."<br>";
// 				/*********************** end email template *************************/
		
					
// 				if( $is_email_send_require )
// 				{
// 					//insert entry in email_send_history table
// 					$data_email['es_from_emails'] = "";
// 					$data_email['es_to_emails'] = getField( 'config_value', 'configuration', 'config_key', 'ADMIN_EMAIL' );
// 					$data_email['es_module_primary_id'] = $product_id;
// 					$data_email['es_module_name'] = "Product";
// 					$data_email['es_subject'] = "Product changed by seller ".$this->session->userdata("s_firstname");
		
// 					$data_email['es_status'] = '';	//$this->input->post('order_status_id');
		
// 					//send product changed notification email
// 					sendMail( $data_email['es_to_emails'], $data_email['es_subject'], $data_email['es_message']);
// 					$this->db->insert("email_send_history",$data_email);
// 				}
		
// 			}
		}
		
		if( IS_LOG )
		{
			//fwrite($fp,'</note>');
			//fclose($fp);
		}
		
		return $last_id;
	}
	
/**
 * @author Cloudwebs
 * @abstract function will save update stone upwards from 3 if it was specified in input
 */	
	function saveStoneRec($is_update, $product_stone_number, $compAttrVal, $product_id) 
	{
		//echo "COLOR ISSUE HERE 7.001<br>";
		
		
		//field are stored in product_side_stone tables
		
		//set defaults
		$is_data = true;
		$dt_sss = $sss_p = array(); 
		
		//prepare input values to save
		$dt_sss['product_id'] = $product_id;
		$dt_sss["inventory_type_id"] = $this->input->post('inventory_type_id');
		$inventory_master_specifier_id = $dt_sss["inventory_master_specifier_id"] = $compAttrVal["inventory_master_specifier_id"];
		if( $compAttrVal["ims_input_type"] == "JW_CS" || $compAttrVal["ims_input_type"] == "JW_SS1" || 
			$compAttrVal["ims_input_type"] == "JW_SS2" || $compAttrVal["ims_input_type"] == "JW_SSS" )
		{
			$dt_sss['product_stone_number'] = $product_stone_number;
			$dt_sss['psss_diamond_shape_id'] = $this->input->post('pss'.$inventory_master_specifier_id.'_diamond_shape_id');
			$dt_sss['product_side_stones_size'] = $this->input->post('product_side_stone'.$inventory_master_specifier_id.'_size');
			$dt_sss['product_side_stones_weight'] = $this->input->post('product_side_stone'.$inventory_master_specifier_id.'_weight');
			$dt_sss['product_side_stones_total'] = $this->input->post('product_side_stone'.$inventory_master_specifier_id.'_total');
			$sss_p = $this->input->post('ss'.$inventory_master_specifier_id.'_p');
			
			if( isEmptyArr($sss_p) )
			{
				$is_data = false;
			}
		}
		elseif( $compAttrVal["ims_input_type"] == "JW_MTL" )
		{
			return;
		}
		elseif( $compAttrVal["ims_input_type"] == "TXT" )
		{
			$dt_sss['product_stone_number'] = $product_stone_number;
			$dt_sss['product_side_stones_size'] = $this->input->post('product_side_stone'.$inventory_master_specifier_id.'_size');
			
			if( empty($dt_sss['product_side_stones_size']) )
			{
				$is_data = false;
			}
		}
		elseif( $compAttrVal["ims_input_type"] == "SEL" )
		{
			$dt_sss['product_stone_number'] = $product_stone_number;
			$dt_sss['psss_diamond_shape_id'] = $this->input->post('pss'.$inventory_master_specifier_id.'_diamond_shape_id');
			
			if( empty($dt_sss['psss_diamond_shape_id']) )
			{
				$is_data = false;
			}
			else 
			{
				$sss_p[] = $dt_sss['psss_diamond_shape_id']; 
			}
		}
		elseif( $compAttrVal["ims_input_type"] == "CHK" )
		{
			//echo "COLOR ISSUE HERE 7.002<br>";
			$dt_sss['product_stone_number'] = $product_stone_number;
			$sss_p = $this->input->post('pss'.$inventory_master_specifier_id.'_diamond_shape_id');
			
			if( isEmptyArr($sss_p) )
			{
				//echo "COLOR ISSUE HERE 7.002.1<br>";
				$is_data = false;
			}
		}
		elseif( $compAttrVal["ims_input_type"] == "RDO" )
		{
			$dt_sss['product_stone_number'] = $product_stone_number;
			$dt_sss['psss_diamond_shape_id'] = $this->input->post('pss'.$inventory_master_specifier_id.'_diamond_shape_id');
			
			if( empty($dt_sss['psss_diamond_shape_id']) )
			{
				$is_data = false;
			}
			else
			{
				$sss_p[] = $dt_sss['psss_diamond_shape_id'];
			}
		}
		
		
		/**
		 * @deprecated
		 * removed from here On 13-04-2015 and moved to delete all attribute at once in saveData
		 * delete old attributes
		 */
		//deleteCompAttrArr($product_id, $product_stone_number, $compAttrVal);
	
		/**
		 * here return if no data to save, but TXT is exceptional since input of type text needs to be updated to empty or inserted, <br>
		 * since they are not ccTld holder also.  
		 */
		if( !$is_data && $compAttrVal["ims_input_type"] !== "TXT" )
		{
			return;
		}
		
		/**
		 * save in main table
		 */
		if( $compAttrVal["ims_input_type"] != "TXT" )
		{
			//echo "COLOR ISSUE HERE 7.003<br>";
			$table_name = ""; $status_field = ""; $category_field = ""; 
			if( $product_stone_number == 0 )
			{
				//echo "COLOR ISSUE HERE 7.004<br>";
				$table_name = "product_center_stone";
				$status_field = "product_center_stone_status";
				$category_field = getCompAttrCategoryField($compAttrVal, "cs");

				/**
				 * set for center stone table
				 */				
				if( isset($dt_sss['psss_diamond_shape_id']) )
				{
					$dt_sss['pcs_diamond_shape_id'] = $dt_sss['psss_diamond_shape_id'];
					unset($dt_sss['psss_diamond_shape_id']);
				}
				
				if( isset($dt_sss['product_side_stones_size']) )
				{
					$dt_sss['product_center_stone_size'] = $dt_sss['product_side_stones_size'];
					unset($dt_sss['product_side_stones_size']);
				}
				
				if( isset($dt_sss['product_side_stones_weight']) )
				{
					$dt_sss['product_center_stone_weight'] = $dt_sss['product_side_stones_weight'];
					unset($dt_sss['product_side_stones_weight']);
				}
				
				if( isset($dt_sss['product_side_stones_total']) )
				{
					$dt_sss['product_center_stone_total'] = $dt_sss['product_side_stones_total'];
					unset($dt_sss['product_side_stones_total']);
				}
				
				
				unset($dt_sss['product_stone_number']);
			}
			else if( $product_stone_number <= 2 )
			{
				//echo "COLOR ISSUE HERE 7.005<br>";
				$table_name = "product_side_stone".$product_stone_number;
				$status_field = "product_side_stone".$product_stone_number."_status";
				$category_field = getCompAttrCategoryField($compAttrVal, "ss".$product_stone_number);
				
				/**
				 * set for side stone table
				 */
				if( isset($dt_sss['psss_diamond_shape_id']) )
				{
					$dt_sss['pss'.$product_stone_number.'_diamond_shape_id'] = $dt_sss['psss_diamond_shape_id'];
					unset($dt_sss['psss_diamond_shape_id']);
				}
				
				if( isset($dt_sss['product_side_stones_size']) )
				{
					$dt_sss['product_side_stone'.$product_stone_number.'_size'] = $dt_sss['product_side_stones_size'];
					unset($dt_sss['product_side_stones_size']);
				}
				
				if( isset($dt_sss['product_side_stones_weight']) )
				{
					$dt_sss['product_side_stone'.$product_stone_number.'_weight'] = $dt_sss['product_side_stones_weight'];
					unset($dt_sss['product_side_stones_weight']);
				}
				
				if( isset($dt_sss['product_side_stones_total']) )
				{
					$dt_sss['product_side_stone'.$product_stone_number.'_total'] = $dt_sss['product_side_stones_total'];
					unset($dt_sss['product_side_stones_total']);
				}
				
				unset($dt_sss['product_stone_number']);
			}
// 			else if( $product_stone_number !== FALSE )
// 			{
// 				$table_name = "product_side_stones";
// 				$status_field = "product_side_stones_status";
// 				$category_field = getCompAttrCategoryField($compAttrVal, "sss");
// 			}
			else 
			{
				//echo "COLOR ISSUE HERE 7.006<br>";
				$table_name = "product_side_stones";
				$status_field = "product_side_stones_status";
				$category_field = getCompAttrCategoryField($compAttrVal, "sss");
			}
			
			$dt_sss[$status_field] = 0;
			foreach($sss_p as $k=>$ar)
			{
				$dt_sss[$category_field] = $ar;
				
				if( $is_update ) 
				{
					//echo "COLOR ISSUE HERE 7.007<br>";
					$table_primary_id = exeQuery( " SELECT ".$table_name."_id FROM ".$table_name." 
													WHERE product_id=".$product_id." AND
													inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." AND 
													".$category_field."=".$ar." ", true, $table_name."_id" );

					/**
					 * since at runtime user may change inventory of product so attribute might need insert in case of product 
					 * update also, so check if empty then insert otherwise update, 
					 */
					if( !empty( $table_primary_id ) )
					{
						$this->db->where( $table_name."_id", $table_primary_id)
								 ->update( $table_name, $dt_sss );
					}
					else 
					{
						$this->db->insert($table_name, $dt_sss);
					}
				}
				else 
				{
					//echo "COLOR ISSUE HERE 7.008<br>";
					$this->db->insert($table_name, $dt_sss);
				}
			}
		}
		else //input type: TXT 
		{
			$table_name = ""; $status_field = ""; $category_field = "";
			if( $product_stone_number == 0 )
			{
				$table_name = "product_center_stone";
				$status_field = "product_center_stone_status";
				$category_field = "product_center_stone_size";
				$dt_sss['product_center_stone_size'] = $dt_sss['product_side_stones_size'];
				
				unset($dt_sss['product_side_stones_size']);
				unset($dt_sss['product_stone_number']);
			}
			else if( $product_stone_number <= 2 )
			{
				$table_name = "product_side_stone".$product_stone_number;
				$status_field = "product_side_stone".$product_stone_number."_status";
				$category_field = 'product_side_stone'.$product_stone_number.'_size';
				$dt_sss['product_side_stone'.$product_stone_number.'_size'] = $dt_sss['product_side_stones_size'];
			
				unset($dt_sss['product_side_stones_size']);
				unset($dt_sss['product_stone_number']);
			}
			else
			{
				$table_name = "product_side_stones";
				$status_field = "product_side_stones_status";
				$category_field = 'product_side_stones_size';
				//$dt_sss['product_side_stones_size'] = $dt_sss['product_side_stones_size'];
			}
			
			
			if( $is_update )
			{
				$table_primary_id = exeQuery( " SELECT ".$table_name."_id FROM ".$table_name." 
												WHERE product_id=".$product_id." AND
												inventory_master_specifier_id=".$compAttrVal["inventory_master_specifier_id"]." ", 
												true, $table_name."_id" );
				
				/**
				 * since at runtime user may change inventory of product so attribute might need insert in case of product
				 * update also, so check if empty then insert otherwise update,
				 */
				if( !empty( $table_primary_id ) )
				{
					/**
					 * ccTLD is only used for input type: TXT
					 */
					$this->stoneCcTld($is_update, $product_id, $dt_sss, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id);
					
					$dt_sss[$status_field] = 0;
					$this->db->where($table_name."_id", $table_primary_id)->update($table_name, $dt_sss);
				}
				else 
				{
					$dt_sss[$status_field] = 0;
					$this->db->insert($table_name, $dt_sss);
					
					$table_primary_id = $this->db->insert_id();
					
					/**
					 * ccTLD is only used for input type: TXT
					*/
					$this->stoneCcTld($is_update, $product_id, $dt_sss, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id);
				}
			}
			else 
			{
				$dt_sss[$status_field] = 0;
				$this->db->insert($table_name, $dt_sss);
				
				$table_primary_id = $this->db->insert_id();
				
				/**
				 * ccTLD is only used for input type: TXT
				 */
				$this->stoneCcTld($is_update, $product_id, $dt_sss, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id);
			}
			
		}

	}	
	
	/**
	 * function will return dia filter price and weight min and max
	 */
	function stoneCcTld( $is_update, $product_id, &$data, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id)
	{
		$ccTldData = array();
	
		//ccTLD data
		$ccTldData[$table_name."_id"] = $table_primary_id;
		
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
	
				if( isset($data[$category_field]) )
				{
					$ccTldData[$category_field] = $data[$category_field];
						
					unset( $data[$category_field] );
				}

				$this->saveupdStoneCcTld( $is_update, $product_id, $ccTldData, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id);
			}
		}
		else
		{
			$resManuf = getManufacturers();

			if( isset($data[$category_field]) )
			{
				$ccTldData[$category_field] = $data[$category_field];
				unset( $data[$category_field] );
			}
							
			foreach( $resManuf as $k=>$ar )
			{
				//$statusTemp = 1;
				if( $ar['manufacturer_id'] == 7 )	//primary language(EN_US)
				{
// 					/**
// 					 * if product is added in non language mode then update status of product in primary
// 					 * language as per $statusTemp.
// 					 */
// 					if( MANUFACTURER_ID != 7 )
// 					{
// // 						$this->db->where( 'product_id', $product_id)
// // 								 ->where( 'inventory_master_specifier_id', $compAttrVal["inventory_master_specifier_id"])
// // 								 ->update( $table_name, array( $status_field => $statusTemp ) );
// 					}
				}
				else 
				{
// 					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )
// 					{
// 						//$statusTemp = $data[$status_field];
// 					}
						
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					
					//no status field in ccTld table
					//$ccTldData[$status_field] = $statusTemp;

					$this->saveupdStoneCcTld( $is_update, $product_id, $ccTldData, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id);
				}
			}
	
			unset( $data[$status_field] );
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max
	 */
	function saveupdStoneCcTld($is_update, $product_id, $ccTldData, $table_name, $status_field, $category_field, $compAttrVal, $table_primary_id)
	{
		/**
		 * On 15-06-2015 entire flow had been changed, now weather it is insert or update 
		 * first it will look in cctld table if attribute entry is exist then it will update otherwise it will insertv enrty. 
		 */
		$_cctld_id = exeQuery( " SELECT ".$table_name."_cctld_id FROM ".$table_name."_cctld 
								 WHERE manufacturer_id=".$ccTldData["manufacturer_id"]." AND
								 ".$table_name."_id=".$table_primary_id." ", true, $table_name."_cctld_id" );
		
		if( !empty($_cctld_id) )
		{
			$this->db->where( $table_name."_cctld_id", $_cctld_id)
					 ->update( $table_name."_cctld", $ccTldData );
		}
		else 
		{
			$this->db->insert( $table_name."_cctld", $ccTldData );
		}
	}
	
/*
+----------------------------------------------------------+
	Deleting data. handle both request get and post.
	with single delete and multiple delete.
	@prams : $ids -> integer or array
+----------------------------------------------------------+
*/	
	function deleteData($ids)
	{
		if($ids)
		{
			foreach($ids as $id)
			{
// 				$tabNameArr = array('0'=>'order_details','1'=>'product_review');
// 				$fieldNameArr = array('0'=>'product_id','1'=>'product_id');
// 				$res=isImageIdExist($tabNameArr,$fieldNameArr,$id);// this function call for un delete field
// 				$delete_sku = getField('product_sku', $this->cTableName, $this->cAutoId, $id);
// 				$path = "assets/product/".$delete_sku;
// 				if(rmdir($path))
// 				{
// 					echo "Success";
// 				}
// 				else
// 				{
// 					echo "error";
// 				}
// 				echo $path;die;
				
				$res = checkIfForeignKeyExist( array( "order_details", "product_review", "warehouse_transactions" ), "product_id", $id );								
				
				if(sizeof($res)>0)
				{
					echo json_encode($res);	
					return;
				}
				else 
				{
					$getName = getField('product_name', $this->cTableName, $this->cAutoId, $id);
					saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
					
					/**
					 * check asset folder available or not
					 * getField("product_image","product","product_id",$id);
					 */
					$path = getField('product_sku', $this->cTableName, $this->cAutoId, $id);
					
					//delete from product_price
					deleteProductFromProductPrice($id);

					//delete from product attributes tables 
					$this->db->where_in($this->cAutoId,$id)->delete("product_metal");
					$this->db->where_in($this->cAutoId,$id)->delete("product_center_stone");
					$this->db->where_in($this->cAutoId,$id)->delete("product_side_stone1");
					$this->db->where_in($this->cAutoId,$id)->delete("product_side_stone2");
					$this->db->where_in($this->cAutoId,$id)->delete("product_side_stones");

					if( false )
					{
						$this->db->where_in($this->cAutoId,$id)->delete("order_details");
						$this->db->where_in($this->cAutoId,$id)->delete("product_review");
						$this->db->where_in($this->cAutoId,$id)->delete("warehouse_transactions");
					}
					
					
					//delete from product_value(stock/warehouse info)
					$this->db->where_in($this->cAutoId,$id)->delete("product_value");

					//delete from product table
					deleteProduct($id);
					
					/**
					 * delete assets uploads
					 */
					if($path != "")
					{
						$resArr = unlinkFile("assets/product/".$path);
					}
				}
			}
			$returnArr['type'] ='success';
			$returnArr['msg'] = count($ids)." records has been deleted successfully.";
		}
		else{
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
		
		$data['product_status'] = $status;
		if(  MANUFACTURER_ID == 7 )	
		{
			$this->db->where($this->cAutoId,$cat_id);
			$this->db->update($this->cTable,$data);
		}
		else	//ccTLDs
		{
			$this->productCcTld( true, $cat_id, $data );
		}
	}

/*
+-----------------------------------------+
 * @author Cloudwebs
	fetch caclulated diamond or metal price for product
	@params : post array of parameters
	@return calculated price
+-----------------------------------------+
*/	
	function getDiaMetPrice()
	{
		$type =  $this->input->post('type');
		$weight =  $this->input->post('weight');
		$idA =  explode("|",$this->input->post('id'));
		$priceArr = array();
		if($type == "dp")
		{
			foreach($idA as $k=>$ar)
			{
				$res = $this->db->query('SELECT dp_calculated_cost FROM diamond_price WHERE diamond_price_id='.$ar.'');
				$row = $res->row_array();

				$priceArr[] =  round($weight * $row['dp_calculated_cost'],2); 
			}
		}
		else if($type == "mp")
		{
			$weightArr = explode("|",$weight);
			foreach($idA as $k=>$ar)
			{
				$res = $this->db->query('SELECT mp_price_difference FROM metal_price WHERE metal_price_id='.$ar.'');
				$row = $res->row_array();
				
				$priceArr[] =  round($weightArr[$k] * $row['mp_price_difference'],2); 
			}
		}

		return json_encode($priceArr);
	}
	
/**
 * uploads product image folder
 * Changed on 24-03-2015 to suppport both single image file upload and zip file upload  
 */	
	function uploadFolder( $uploadFile, $filetype, $folder ) 
	{
		/**
		 * old concept
		 */
// 		$image = uploadFile( $uploadFile, $filetype, $folder ); //input file, type, folder
// 		if(@$image['error'])
// 		{
// 			setFlashMessage('error',$image['error']);
// 			$path = $image['path'];
// 			@unlink('./'.$path);
// 			redirect('admin/'.$this->router->class);	
// 		}
// 		$path = $image['path'];
		
// 		if( $filetype == "zip" )
// 		{
// 			@unlink('./'.$path); //delete zip file
// 			return substr($path,0,strrpos($path,".")) ;
// 		}
// 		else 
// 		{
// 			return $path;
// 		}

		/**
		 * updating use for winezon concept
		 * added on 23-03-2016
		 * Author Gautam Kakadiya
		 */

		/**
		 * load file helpers
		 */
		$this->load->helper( "Custom_file" );
		
		
		$image = uploadFile( $uploadFile, $filetype, $folder ); //input file, type, folder
		if(@$image['error'])
		{
			setFlashMessage('error',$image['error']);
			$path = $image['path'];
			@unlink('./'.$path);
				
			//[temp]; facing strange error in chrome, in spite of no issues there
			// 			echo $uploadFile . " | " . $filetype . " | "  . $folder;
			redirect('admin/'.$this->router->class);
		}

		$path = $image['path'];
		
		if( $filetype == "zip" )
		{
			@unlink('./'.$path); //delete zip file
		
			/**
			 *  image name cleaning
			*/
			$this->imageNameCleaning("", "assets/".$folder."/".$product_sku, "dir");
		
			/**
			 * create image versions
			*/
			$this->imageVersions("", "assets/".$folder."/".$product_sku, "dir", $product_sku);
				
			return substr($path,0,strrpos($path,".")) ;
		}
		else
		{
			/**
			 *  image name cleaning
			 */
			$this->imageNameCleaning($path, "assets/".$folder, "image");
		
			/**
			 * create image versions
			*/
			$this->imageVersions($path, "assets/".$folder, "image");
		
			return $path;
		}
		
	}
	
	/**
	 * function will replace white space characters in image name with -, and so on cleaning.
	 */
	function imageNameCleaning( $srcFile, $srcFolder, $type )
	{
		if( $type == "image" )
		{
			/**
			 * replace white spaces to dashes "-" only in directories
			 */
			$fileName = hefile_fileName( $srcFile );
				
			if( strpos($fileName, " ") !== FALSE )
			{
				hefile_rename($srcFile, $srcFolder."/". str_replace(" ", "-", $fileName));
			}
		}
		else if( $type == "dir" )	//folder
		{
			$dir = $srcFolder;
			if( hefile_isDir($dir) )
			{
				if ($dh = opendir($dir))
				{
					$imagePath = null;
					while (($file = readdir($dh)) !== false)
					{
						if ( !hefile_isDir($dir.'/'.$file) && substr($file,-3)!=".db" )
						{
							$srcFile = $dir.'/'.$file;
								
							/**
							 * replace white spaces to dashes "-" only in directories
							 */
							$fileName = hefile_fileName( $srcFile );
								
							if( strpos($fileName, " ") !== FALSE )
							{
								$fileName = str_replace(" ", "-", $fileName);
								hefile_rename($srcFile, str_replace( hefile_fileName( $srcFile ), $fileName, $srcFile) );
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * function will create thumbnail __T and medium __M version of images
	 */
	function imageVersions( $srcFile, $srcFolder, $type, $product_sku="",$maintain_ratio=false )
	{
		if( $type == "image" )
		{
			$fileName = hefile_fileName( $srcFile );
				
			/**
			 * __T thumbnail version
			*/
			if( !hefile_isDirExists( $srcFolder."/__T/" ) )
			{
				hefile_createDir($srcFolder."/__T/" );
			}
			resize_image($srcFile, $srcFolder."/__T/".$fileName, 70, 84, $maintain_ratio);
				
			/**
			 * __M medium version
			*/
			if( !hefile_isDirExists( $srcFolder."/__M/" ) )
			{
				hefile_createDir($srcFolder."/__M/" );
			}
			resize_image($srcFile, $srcFolder."/__M/".$fileName, 335, 375, $maintain_ratio);
	
			/**
			 * __L medium version
			*/
			if( !hefile_isDirExists( $srcFolder."/__L/" ) )
			{
				hefile_createDir($srcFolder."/__L/" );
			}
			resize_image($srcFile, $srcFolder."/__L/".$fileName, 804, 900, $maintain_ratio);
		}
		else if( $type == "dir" )	//folder
		{
			//create temp dir
			hefile_copyDir($srcFolder, "assets/tmp/".$product_sku);
				
			/**
			 * __T thumbnail version
			*/
			hefile_copyDir("assets/tmp/".$product_sku, $srcFolder."/__T");
			$dir = $srcFolder."/__T";
			if( hefile_isDir($dir) )
			{
				if ($dh = opendir($dir))
				{
					$imagePath = null;
					while (($file = readdir($dh)) !== false)
					{
						if ( !hefile_isDir($dir.'/'.$file) && substr($file,-3)!=".db" )
						{
							$srcFile = $dir.'/'.$file;
	
							/**
							 * resize to thumbnail version
							 */
							resize_image($srcFile, $srcFile, 70, 84, $maintain_ratio);
						}
					}
				}
			}
	
	
			/**
			 * __M medium version
			 */
			hefile_copyDir("assets/tmp/".$product_sku, $srcFolder."/__M");
			$dir = $srcFolder."/__M";
			if( hefile_isDir($dir) )
			{
				if ($dh = opendir($dir))
				{
					$imagePath = null;
					while (($file = readdir($dh)) !== false)
					{
						if ( !hefile_isDir($dir.'/'.$file) && substr($file,-3)!=".db" )
						{
							$srcFile = $dir.'/'.$file;
								
							/**
							 * resize to medium version
								*/
							$width = 0;
							$height = 0;
							list($width, $height) = getimagesize($srcFile);
							if( $width > 335 && $height > 375 )
							{
								resize_image($srcFile, $srcFile, 335, 375, $maintain_ratio);
							}
						}
					}
				}
			}
	
			/**
			 * __L Large version
			 */
			hefile_copyDir("assets/tmp/".$product_sku, $srcFolder."/__L");
			$dir = $srcFolder."/__L";
			if( hefile_isDir($dir) )
			{
				if ($dh = opendir($dir))
				{
					$imagePath = null;
					while (($file = readdir($dh)) !== false)
					{
						if ( !hefile_isDir($dir.'/'.$file) && substr($file,-3)!=".db" )
						{
							$srcFile = $dir.'/'.$file;
	
							/**
							 * resize to medium version
							 */
							$width = 0;
							$height = 0;
							list($width, $height) = getimagesize($srcFile);
							if( $width > 804 && $height > 900 )
							{
								resize_image($srcFile, $srcFile, 804, 900, $maintain_ratio);
							}
						}
					}
				}
			}
	
			//remove temp dir
			hefile_removeDirRecursive( "assets/tmp/".$product_sku );
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max 
	 
	 */
	function productCcTld( $is_update, $product_id, &$data )
	{
		$ccTldData = array();
		
		//ccTLD data
		$ccTldData['product_id'] = $product_id;

		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )	
			{
				//ccTLD data
				$ccTldData['manufacturer_id'] = MANUFACTURER_ID;
				$ccTldData['product_status'] = $data['product_status'];

				if( isset($data['product_metal_priority_id']) )
				{
					$ccTldData['product_metal_priority_id'] = $data['product_metal_priority_id'];
					$ccTldData['product_cs_priority_id'] = $data['product_cs_priority_id'];
					$ccTldData['product_ss1_priority_id'] = $data['product_ss1_priority_id'];
					$ccTldData['product_ss2_priority_id'] = $data['product_ss2_priority_id'];
					$ccTldData['product_discount'] = $data['product_discount'];
					//Gautam Change
					$ccTldData['product_name'] = $data['product_name'];
					//$ccTldData['product_alias'] = $data['product_alias'];
					$ccTldData['product_short_description'] = $data['product_short_description'];
					$ccTldData['product_description'] = $data['product_description'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = $data['meta_description'];
					$ccTldData['robots'] = $data['robots'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
					//--Gautam Kakadiya--
					
					unset( $data['product_metal_priority_id'] );
					unset( $data['product_cs_priority_id'] );
					unset( $data['product_ss1_priority_id'] );
					unset( $data['product_ss2_priority_id'] );
					unset( $data['product_discount'] );
					//Gautam Change
					unset( $data['product_name']);
					//unset( $data['product_alias']);
					unset( $data['product_short_description']);
					unset( $data['product_description']);
					unset( $data['custom_page_title']);
					unset( $data['meta_keyword']);
					unset( $data['meta_description']);
					unset( $data['robots']);
					unset( $data['author']);
					unset( $data['content_rights']);
					//--Gautam Kakadiya--
				}
				
				$this->saveupdProductCcTld( $ccTldData );
				unset( $data['product_status'] );
			}
		}
		else
		{
			$resManuf = getManufacturers();
			
			if( isset($data['product_metal_priority_id']) )
			{
				$ccTldData['product_metal_priority_id'] = $data['product_metal_priority_id'];
					$ccTldData['product_cs_priority_id'] = $data['product_cs_priority_id'];
					$ccTldData['product_ss1_priority_id'] = $data['product_ss1_priority_id'];
					$ccTldData['product_ss2_priority_id'] = $data['product_ss2_priority_id'];
					$ccTldData['product_discount'] = $data['product_discount'];
					//Gautam Change
					$ccTldData['product_name'] = $data['product_name'];
					//$ccTldData['product_alias'] = $data['product_alias'];
					$ccTldData['product_short_description'] = $data['product_short_description'];
					$ccTldData['product_description'] = $data['product_description'];
					$ccTldData['custom_page_title'] = $data['custom_page_title'];
					$ccTldData['meta_keyword'] = $data['meta_keyword'];
					$ccTldData['meta_description'] = $data['meta_description'];
					$ccTldData['robots'] = $data['robots'];
					$ccTldData['author'] = $data['author'];
					$ccTldData['content_rights'] = $data['content_rights'];
					//--Gautam Kakadiya--
					
					unset( $data['product_metal_priority_id'] );
					unset( $data['product_cs_priority_id'] );
					unset( $data['product_ss1_priority_id'] );
					unset( $data['product_ss2_priority_id'] );
					unset( $data['product_discount'] );
					//Gautam Change
					unset( $data['product_name']);
					//unset( $data['product_alias']);
					unset( $data['product_short_description']);
					unset( $data['product_description']);
					unset( $data['custom_page_title']);
					unset( $data['meta_keyword']);
					unset( $data['meta_description']);
					unset( $data['robots']);
					unset( $data['author']);
					unset( $data['content_rights']);
					//--Gautam Kakadiya--
			}
			
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 0; //to resolve bug 374 by default enable
				if( $ar['manufacturer_id'] == 7 )	//primary language(EN_US)
				{
					/**
					 * if product is added in non language mode then update status of product in primary 
					 * language as per $statusTemp.
					 */
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'product_id', $product_id)->update( "product", array( 'product_status' => $statusTemp ) );	
					}
				}
				else
				{
					if(  $ar['manufacturer_id'] == MANUFACTURER_ID )	
					{
						$statusTemp = $data['product_status'];
					}
					
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['product_status'] = $statusTemp;
					$this->saveupdProductCcTld( $ccTldData );
				}
			}

			unset( $data['product_status'] );
		}
	}

	/**
	 * function will return dia filter price and weight min and max 
	 */
	function saveupdProductCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= "`".$key."`='". addslashes( $val ) ."', ";
		}
		$update .= "product_cctld_modified_date=NOW()";

		$this->db->query( $this->db->insert_string( "product_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}
	
	function resizeImage($path)
	{
		$sizeArr = $this->db->where('image_size_id',$this->input->post('image_size_id'))->where('image_size_status','0')->get('image_size')->row_array();
		$dest = getResizeFileNameByPath($path,'m',''); //image path, type(s,m), folder
		$returnFlag = resize_image($path, $dest, @$sizeArr['image_size_width'], @$sizeArr['image_size_height']); //source, destination, width, height
		@unlink($path); //delete old image
		return $dest;
	}
	
	/*
	 * Function will get all sites language
	*/
	function getLanguagesForListing()
	{
		if($this->input->get('edit') == 'true')
		{
			$row = fetchRow("SELECT product_id, inventory_type_id, product_name  FROM ".$this->cTableName." WHERE product_id=".$this->cPrimaryId." ");
			if( !isEmptyArr($row) )
			{
				$sel_query = " '".$row["product_id"]."' as item_id, '".$row["product_name"]."' as item_name, '".$row["inventory_type_id"]."' as inventory_type_id ";
				return getLanguagesForItemListing( $sel_query );
			}
			else
			{
				return array();
			}
		}
		else if($this->input->get('insert') == 'true')
		{
			return getInventoryListing();
		}
	}
	
	/**
	 * Function will get all Inventory Type
	 */
	function getInventoryListing()
	{
		$CI =& get_instance();
		$res = $CI->db->query("SELECT inventory_type_id, it_name, it_key FROM inventory_type WHERE it_status=0 ");
		return $res->result_array();
	}
	
	function formValidation( $cons__this )
	{
		$is_seller_id_changed = (int) $this->input->post("is_seller_id_changed");
		if( $is_seller_id_changed == 1 && $this->input->post("seller_id") != "__SKIP" )
		{
			$cons__this->form_validation->set_rules( 'seller_id', 'Seller', 'trim|required|numeric' );
		}
	
		$cons__this->form_validation->set_rules('product_name','Product Name','trim|required');
	
		if(MANUFACTURER_ID == 7 && ( empty($this->cPrimaryId) ) && $this->input->post("product_alias") != "__SKIP" ):
			$cons__this->form_validation->set_rules('product_alias','Product Alias','trim|required|callback_checkAlias');
		endif;
	
		if( !$this->cPrimaryId )
			$cons__this->form_validation->set_rules('product_sku','Product SKU','trim|required|callback_productSKU');
	
		//validation removed on 17-03-2016
		// 		if( $this->input->post("main_category_id") != "__SKIP" )
			// 		{
			// 			$cons__this->form_validation->set_rules('main_category_id','Main Category ','trim|numeric|required');
			// 		}
		//validation removed on 17-03-2016
		// 		if( $this->input->post("category_id") != "__SKIP" )
			// 		{
			// 			$cons__this->form_validation->set_rules('category_id','Category ','required');
			// 		}
// 		$cons__this->form_validation->set_rules('product_image_single','Product Image','trim|callback_imageSize');	//required| removed on 14-04-2015
	
		/**
		 * the manufaturer is no longer required here. <br>
		 * for that instead branc provision is there in category and seller_id is also there for market place
		*/
		//$cons__this->form_validation->set_rules('product_manufacturer_id','Manufacturer ','trim|required');
	
		//$cons__this->form_validation->set_rules('product_short_description','Short Description','trim|required');
	
		/**
		 * validation turned off on 13-03-2015, to support simple product entry.
		 * Numeric validation added on 22-07-2015
		*/
		$cons__this->form_validation->set_rules('product_value_height','Product Height','trim|numeric');
		$cons__this->form_validation->set_rules('product_value_width','Product Width','trim|numeric');
		$cons__this->form_validation->set_rules('product_value_weight','Product Weight','trim|numeric');
	
	
		/*		$cons__this->form_validation->set_rules('custom_page_title','Custom Page Title','trim|required');
		 $cons__this->form_validation->set_rules('meta_description','Category meta Description','trim|required');
		$cons__this->form_validation->set_rules('meta_keyword','Category Meta keyword','trim|required');
		$cons__this->form_validation->set_rules('author','Category Author Name','trim|required');
		$cons__this->form_validation->set_rules('content_rights','Category Contents Rights','trim|required');
		*/
	
		/**
		 * validation turned off on 13-03-2015, to support simple product entry
		*/
		//$cons__this->form_validation->set_rules('product_price','Product Price','trim|required|numeric');
		//$cons__this->form_validation->set_rules('stock_status_id','Availability Status','trim|required');
	
		/**
		 * component_based_inventory inventory
		*/
		if( $cons__this->session->userdata("IT_KEY") == "JW" )
		{	$cons__this->form_validation->set_rules('mt_p','prd_scomp Category','required');		}
	
		/**
		 * @deprecated
		 * added validation of quantity unit on 17-03-2015 for warehouse managed inventory
		 */
		// 		if( hewr_isWarehouseManaged() )
		// 		{
		// 			$cons__this->form_validation->set_rules('pv_quantity_unit','Quanity Unit','required');
		// 		}
	
	}

	/********************************* Export-Import functions ************************************/
	
	/**
	 * export query
	 */
	function exportQuery( $compAttrArr )
	{
		$export_limit = $this->input->post("export_limit");
		if( !empty($export_limit) )
		{
			$export_limit = " LIMIT ".$export_limit;
		}
	
		$sql = "SELECT p.product_id, p.inventory_type_id, p.seller_id, p.product_name, p.product_alias, p.product_sku,
					 p.category_id as 'Other Category', p.product_short_description, p.product_status,
					 pp.product_price_calculated_price as 'Market Price', pp.product_discount as 'Discount',
					 pp.product_discounted_price as 'Our Price',
					 pv.product_value_quantity as 'Qunatity', pv.product_value_weight as 'Weight',
					 p.product_description as 'Long Description', p.product_image as 'Image',
					 '' as 'Reserve 1', '' as 'Reserve 2', '' as 'Reserve 3', '' as 'Reserve 5' ";
		
		if( !empty( $compAttrArr ) )
		{
			foreach ($compAttrArr as $k=>$ar)
			{
				$sql .= ", 'Attr-".$k."-".$ar["inventory_master_specifier_id"]."' as '".$ar["ims_input_label"]."-Attr-".$k."-".$ar["inventory_master_specifier_id"]."' ";
			}
		}
		
			
		$sql .= "FROM product p LEFT JOIN product_value pv
				  ON pv.product_id=p.product_id
				  LEFT JOIN product_price pp
				  ON pp.product_id=p.product_id
				  GROUP BY p.product_id
				   ".$export_limit;
	
		$res = $this->db->query( $sql );
		return $res->result_array();
	}
	
	/**
	 * This Function will export product information
	 * downloaded and create csv file.
	 */
	function exportData()
	{
		$compAttrArr = getcompAttrArr( false );
		$listArr = $this->exportQuery( $compAttrArr );
		
		$ext = $this->input->post($this->controller.'_export');
		if( empty($ext) )
		{
			$ext = "csv";
		}
		$col= array(array_keys($listArr[0]));
		$col= $col[0];
	
		$this->exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, $listArr, $ext, $compAttrArr);
	
		die;
	}
	
	/**
	 * This Function will export product information but only header or column information
	 * downloaded and create csv file.
	 */
	function exportDataSample( $is_header_row_only=false )
	{
		$compAttrArr = getcompAttrArr( false );
		$listArr = $this->exportQuery( $compAttrArr );
		
		$ext = $this->input->post($this->controller.'_export');
		if( empty($ext) )
		{
			$ext = "csv";
		}
		$col= array(array_keys($listArr[0]));
		$col= $col[0];
	
		if( $is_header_row_only )
		{
			$headerRowText = $this->exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, '', $ext, $compAttrArr, $is_header_row_only);
			return explode(",", $headerRowText);
		}
		else
		{
			$this->exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, '', $ext, $compAttrArr);
		}
		die;
	}
	
	/**
	 * @abstract insert data into pincode table in format
	 * format:: pincode,areaname,cityname,state_id
	 */
	function importData()
	{
		setTimeLimit();
		$this->load->helper("import_export");
		$this->load->helper("custom_file");
		$image = uploadFile('import_csv','All','importdata');
		if( empty( $image["path"] ) )
		{
			//[temp]
			if( isset( $image["error"] ) )
			{
				setFlashMessage("error", $image["error"]);
			}
			else
			{
				setFlashMessage("error", "Import file missing, some error occured in upload.");
			}
	
			redirect("admin/".$this->controller);
			//$image["path"] = "export_products_it_0_1000.xml";
		}
	
			
		$data["path"] = $image['path'];
		$data["start"] = 1;
		$data['pageName'] = 'admin/'.$this->controller.'/import_process';
	
		$this->load->view('admin/layout',$data);
		
		exit(1);
	}
	
	
	/**
	 * @abstract insert data into pincode table in format
	 * format:: pincode,areaname,cityname,state_id
	 */
	function importDataProcess( $path, $start )
	{
		setTimeLimit();
		$is_debug=false;
		$num_records = 1;	//number of records to process each time
		$this->load->helper("import_export");
		$this->load->helper("custom_file");
	
		// 		//temp
		// 		if( $start < 398 )
			// 		{
			// 			$start = 398;
			// 		}
	
		$compAttrArr = getcompAttrArr( false );
		if( !empty($path) )
		{
			$resArr = array();
			$EXT = strtoupper( file_extension($path) );
				
			if( $EXT == "CSV" )
			{
				$resArr = readCsvNew($path, ",", true);
			}
			else if( $EXT == "XML" )
			{
				//dead code: need to be implemented
				$custom = "mag_exp_ext_xml";
	
				if( $custom == "mag_exp_ext_xml" )
				{
					/**
					 * SKIP INSERT
					 */
					$_POST["SKIP_insert_in_import"] = 1;
						
	
					$resultXml = hefile_simpleXmlLoadFiles( $path );
					$rowArr = $resultXml->Worksheet->Table->Row;
						
					$resArr[ 0 ] = $this->exportDataSample( true );
						
					$size = sizeof( $rowArr );
					for( $j=1; $j<$size; $j++ )
					{
						if($j >= $start && $j < ($start + $num_records) )
						{
							$resArr[ $j ] = ieh_product_mag_exp_ext_xml_ToArr($rowArr, $j);
						}
						else
						{
							$resArr[ $j ] = array("EMPTY_ARRAY");
						}
					}
				}
			}
			else
			{
				ieh_errorMsg("File type: ".$EXT." does not supported, import terminated.");
			}
				
			$logType = "";
			$header = $resArr[0];
	
			$attrIndexStart = 20;
			$attrCache = array();
			foreach($resArr as $k=>$ar)
			{	
				if($k >= $start && $k < ($start + $num_records) )
				{
					if( sizeof($ar) < $attrIndexStart )
					{
						ieh_errorMsg("Row number ".$k." skipped due to row has not enough number of columns, total columns found are: ". sizeof( $ar ) );
						continue;
					}
						
					$product_image = "";
					$this->cPrimaryId = $this->cPrimaryId = $ar[0];
					if( empty($this->cPrimaryId) )
					{
						/**
						 * check if product already exist based on SKU
						 */
						$this->cPrimaryId = $this->cPrimaryId = getField("product_id", "product", "product_sku", $ar[4]);
	
						if( empty( $this->cPrimaryId ) )
						{
							if( $this->input->post('SKIP_insert_in_import') == 1 )
							{
								ieh_errorMsg("Row number ".$k." skipped due to product_id not available for specified SKU: ".$ar[4].". Skipped since specified to skip insert.");
								continue;
							}
						}
					}

					// save inventory type id
					if( $ar[1] != "__SKIP" )
					{
						if( is_numeric($ar[1]) )
						{
							$_POST['inventory_type_id'] = $ar[1];
						}
						else
						{
							$_POST['inventory_type_id'] = getField("inventory_type_id", "inventory_type", "it_name", $ar[1]);
						}
						if( empty($_POST['inventory_type_id']) )
						{
							ieh_errorMsg("Row number ".$k." skipped due to inventory id not available for specified value: ".$ar[1]);
							continue;
						}
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['inventory_type_id'] = "__SKIP";
						}
						else
						{
							$_POST['inventory_type_id'] = 1;
						}
					}
					//set invetory type id in session for saving purpose
					setInventorySession( inventory_typeKeyForId( $_POST['inventory_type_id'] ) );
					
					
					//save seller id
					if( $ar[2] != "__SKIP" )
					{
						if( is_numeric($ar[2]) )
						{
							$_POST['seller_id'] = $ar[2];
						}
						else
						{
							$_POST['seller_id'] = getField("seller_id", "mp_seller", "s_shop_name", $ar[2]);
						}
						if( empty($_POST['seller_id']) )
						{
							ieh_errorMsg("Row number ".$k." skipped due to seller_id not available for specified value: ".$ar[2]);
							continue;
						}
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['seller_id'] = "__SKIP";
						}
						else
						{
							$_POST['seller_id'] = 1;
						}
					}
	
					$_POST['product_name'] = $ar[3];
					
					if( $ar[4] != "__SKIP" )
					{
						if( empty($ar[4]) )
						{
							$_POST['product_alias'] = getUrlName($_POST['product_name'], true);
						}
						else
						{
							$_POST['product_alias'] = $ar[4];
						}
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['product_alias'] = "__SKIP";
						}
						else
						{
							$_POST['product_alias'] = getUrlName($_POST['product_name'], true);
						}
					}
						
					$_POST['product_sku'] = $ar[5];
						
					if( $ar[6] != "__SKIP" )
					{
						$category_idA = explode("|", $ar[6]);
						$_POST['category_id'] = array();
						foreach ($category_idA as $cat_idAK=>$cat_idAV)
						{
							if( is_numeric($cat_idAV) )
							{
								$_POST['category_id'][] = $cat_idAV;
							}
							else
							{
								$category_id = $this->importProcessFetchCategoryID( $cat_idAV );
								if( !empty($category_id) )
								{
									$_POST['category_id'][] = $category_id;
								}
								else
								{
									ieh_errorMsg("Row number ".$k." to category_id not found for specified value: ".$cat_idAV);
								}
							}
						}
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['category_id'] = "__SKIP";
						}
						else
						{
							$_POST['category_id'] = 1;
						}
					}
	
					$_POST['product_short_description'] = $ar[7];
						
					/**
					 * only update item status in main language mode
					 */
					if( MANUFACTURER_ID == 7 && $ar[8] != "__SKIP" )
					{
						if( is_numeric($ar[8]) )
						{
							$_POST['product_status'] = $ar[8];
						}
						else
						{
							if( stripos($ar[8], "enab") !== FALSE )
							{
								$_POST['product_status'] = 0;
							}
							else
							{
								$_POST['product_status'] = 1;
							}
						}
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['product_status'] = "__SKIP";
						}
						else
						{
							$_POST['product_status'] = 1;
						}
					}
					
					/**
					 * price
					 */
					$prodData = array();
					if( $ar[9] != "__KEEP" )
					{
						$_POST['product_price_calculated_price'] = $ar[9];
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$product_price_id = getPriorityPrPrID('',$this->cPrimaryId,' AND p.product_status=0 AND pp.product_price_status=0 ', true);
							$prodData = showProductsDetails($product_price_id, false, true, false);
	
							$_POST['product_price_calculated_price'] = (float) @$prodData["product_price_calculated_price"];
						}
						else
						{
							$_POST['product_price_calculated_price'] = 0;
						}
					}
						
					if( $ar[10] != "__KEEP" )
					{
						$_POST['product_discount'] = $ar[10];
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['product_discount'] = (float) @$prodData["product_discount"];
						}
						else
						{
							$_POST['product_discount'] = 0;
						}
					}
						
					if( $ar[11] != "__KEEP" )
					{
						$_POST['product_discounted_price'] = $ar[11];
					}
					else
					{
						if( !empty( $this->cPrimaryId ) )
						{
							$_POST['product_discounted_price'] = (float) @$prodData["product_discounted_price"];
						}
						else
						{
							$_POST['product_discounted_price'] = 0;
						}
					}
	
					$_POST['product_value_quantity'] = $ar[12];
						
					$_POST['product_value_weight'] = $ar[13];
	
// 					$_POST['p_seller_publish_status'] = $ar[14];
						
					$_POST['product_description'] = $ar[14];
					
					/**
					 * create on 05-08-2016 
                     * commented on 13-08-2017
					 * Gautam Kakadiya
					 * download image from another location like dropbox, etc....
					 * 
					 * after change multiple image upload
					 */
					
// 					$multipleImages = array();
// 					$multipleImages = explode("|", $ar[15] );
					
// 					if( !isEmptyArr( $multipleImages ) )
// 					{
// 						foreach ( $multipleImages as $img=>$expImage )
// 						{
// 							if( !empty( $expImage ) )
// 							{
// 								if( strpos($expImage, "assets/product/") === FALSE )
// 								{
// 									if( $is_debug )
// 									{
// 										echo "Image Link (".($img+1).") ".$expImage."<br>";
// 									}
// // 									getImageFromOtherLocation( $_POST['product_sku'], $ar[15] );
// 									$retry_limit = 3;
// 									for($iImg=0; $iImg<$retry_limit; $iImg++ )
// 									{
// 										if( getImageFromOtherLocation( $_POST['product_sku'], $expImage, $is_debug ) )
// 										{
// 											break;
// 										}
// 										else 
// 										{
// 											//let it retry
// 										}
// 									}
// 								}
// 							}
// 						}
// 					}
	
// 					$_POST['pv_min_purchase_qty'] = $ar[16];
						
// 					$_POST['pv_qty_cart_increments'] = $ar[17];
						
					/**
					 * attributes
					 */
					foreach ($ar as $arKey=>$arVal)
					{
						if( $arKey > ( $attrIndexStart - 1 ) )
						{
							$tmpA = explode("-", $header[$arKey]);
							$tmpA[3] = (int) $tmpA[3];
							
							//echo "COLOR ISSUE HERE 1 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
							
							$K1 = associative_array_search($compAttrArr, "inventory_master_specifier_id", $tmpA[3]);
	
							if( $compAttrArr[$K1]["ims_input_type"] == "TXT" )
							{
								if( $arVal != "__KEEP" )
								{
									//Cloudwebs: changed code on 21-12-2016
									//$_POST['product_center_stone'.$tmpA[3].'_size'] = $arVal;
									if( $tmpA[2] == 0 )
									{
										$_POST['product_center_stone'.$tmpA[3].'_size'] = $arVal;
									}
									else 
									{
										$_POST['product_side_stone'.$tmpA[3].'_size'] = $arVal;
									}
								}
								else
								{
									if( !empty( $this->cPrimaryId ) )
									{
										if( $arKey == 0 )
										{
											$_POST['product_center_stone'.$tmpA[3].'_size'] = @$prodData['product_center_stone_size'];
										}
										else
										{
											$_POST['product_center_stone'.$tmpA[3].'_size'] = @$prodData['product_center_stone'.$tmpA[2].'_size'];
										}
									}
									else
									{
										$_POST['product_center_stone'.$tmpA[3].'_size'] = "";
									}
								}
							}
							else
							{
								//echo "COLOR ISSUE HERE 2 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
								if( $compAttrArr[$K1]["ims_input_type"] == "CHK" )
								{
									//echo "COLOR ISSUE HERE 3 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
									$_POST['pss'.$tmpA[3].'_diamond_shape_id'] = array();
									$tmpAvA = explode("|", $arVal);
									foreach ($tmpAvA as $avk=>$avV)
									{
										//echo "COLOR ISSUE HERE 4 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
										
										if( empty($avV) ) { continue; }
	
										//echo "COLOR ISSUE HERE 5 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
										
										if( !isset($attrCache[ $tmpA[3] ."_". $avV ]) )
										{
											//echo "COLOR ISSUE HERE 6 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
											$attrCache[ $tmpA[3] ."_". $avV ] = $this->importProcessFetchAttributeID($tmpA[3], $avV, $k, $this->cPrimaryId);
										}

										//echo "COLOR ISSUE HERE 7 ArrVal: ".$arVal." TMP: ".$tmpA[3]."<br>";
										$_POST['pss'.$tmpA[3].'_diamond_shape_id'][] = $attrCache[ $tmpA[3] ."_". $avV ];
									}
									
									//print_r( $_POST['pss'.$tmpA[3].'_diamond_shape_id'] );
								}
								else
								{
									if( empty($arVal) ) { continue; }
									if( !isset($attrCache[ $tmpA[3] ."_". $arVal ]) )
									{
										$attrCache[ $tmpA[3] ."_". $arVal ] = $this->importProcessFetchAttributeID($tmpA[3], $arVal, $k, $this->cPrimaryId);
									}
									$_POST['pss'.$tmpA[3].'_diamond_shape_id'] = $attrCache[ $tmpA[3] ."_". $arVal ];
								}
							}
						}
					}
	
					$this->formValidation($this);
					
					if($this->form_validation->run() == FALSE )
					{
						$data['error'] = $this->form_validation->get_errors();
						ieh_errorMsg("Row number ".$k." skipped due to validation error");
						foreach ($data['error'] as $eK=>$eV)
						{
							echo $eV."<br>";
						}
						continue;
					}
	
					$last_id = $this->saveItem(true, $logType);
					
					/**
					 * images
					*/
					if( hefile_isDirExists( "assets/importdata/product/".$_POST['product_sku'] ) )
					{
						if( !empty($this->cPrimaryId) && hefile_isDirExists( "assets/product/".$_POST['product_sku'] ) )
						{
							unlinkFile( "./"."assets/product/".$_POST['product_sku'] );
						}
						
						hefile_copyDir("assets/importdata/product/".$_POST['product_sku'], "assets/product/".$_POST['product_sku']);
							
						/**
						 *  image name cleaning
						*/
						$this->imageNameCleaning("", "assets/product/".$_POST['product_sku'], "dir");
							
						/**
						 * create image versions
						*/
						$this->imageVersions("", "assets/product/".$_POST['product_sku'], "dir", $_POST['product_sku']);
							
						unlinkFile( "./"."assets/importdata/product/".$_POST['product_sku'] );
							
						$product_image = "assets/product/".$_POST['product_sku'];
						$this->db->query( "UPDATE product SET product_image='".$product_image."' WHERE product_id=".$last_id." " );
					}
					else
					{
						ieh_warningMsg("Important only if image processing required, No Image found for Product SKU: ".$_POST['product_sku']);
					}
						
					saveAdminLog($this->router->class, $_POST['product_name'], $this->cTableName, $this->cAutoId, $last_id, $logType);
	
					if( $logType == "A" )
					{
						ieh_successMsg("Row number ".$k." has been processed, record inserted with ID: ".$last_id);
					}
					else
					{
						ieh_successMsg("Row number ".$k." has been processed, record updated with ID: ".$last_id);
					}
						
				}
				else
				{
					if( $k >= ($start + $num_records) )
					{
						echo '<script type="text/javascript">
								setTimeout( function()
											{ importDataProcess( \''.$path.'\', '.($start + $num_records).'); }, 2000 );
							  </script>';
	
						exit(1);
					}
				}
			}
	
			ieh_successMsg("File has been imported.");
		}
		else
		{
			if( isset($image["error"]) )
			{
				ieh_errorMsg($image["error"]);
			}
			else
			{
				ieh_errorMsg("Import file missing.");
			}
		}
		
		ieh_hideLoader( $this->controller );
		
		exit(1);
	}
	
	
	/**
	 *
	 */
	function exportExcel($fileName,$columns,$listArr,$ext,$compAttrArr, $is_header_row_only=false)
	{
		$CI = & get_instance();
		$CI->load->helper('download');
		$sep = ($ext=='csv') ? "," : "\t"; //seperate
	
		$fileTextArray = array_values($columns);
		$fileText = "";
		foreach($fileTextArray as $ke=>$va)
		{
			if( strpos( $va, "Attr" ) === FALSE )
			{
				$fileText .= pgTitle($va).$sep;
			}
			else
			{
				$fileText .= $va.$sep;
			}
		}
	
		$fileText = substr($fileText,0,-1)."\n";
		//$fileText = implode(",",$fileTextArray)."\n"; //excel:\t
		if( $is_header_row_only )
		{
			return $fileText;
		}
	
		$handle1 = fopen($fileName,'w');
		fwrite($handle1, $fileText);
	
		/**
		 * added on 20-08-2015
		 * is not pass empty list Array
		*/
		if(!empty($listArr))
		{
			foreach($listArr as $list)
			{
				if( $ext == "csv" )
				{
					foreach( $list as $key=>$val )
					{
						if( strpos($val, "Attr") !== FALSE )
						{
							$tmpA = explode("-", $val);
							$K1 = associative_array_search($compAttrArr, "inventory_master_specifier_id", $tmpA[2]);
							$table_name = "";
							$status_field = "";
							$category_field = "";
							$category_field1 = "";
							if( $tmpA[1] == 0 )
							{
								$table_name = "product_center_stone";
								$status_field = "product_center_stone_status";
								$category_field = "product_center_stone_size";
								$category_field1 = "pcs_diamond_shape_id";
							}
							else if( $tmpA[1] <= 2 )
							{
								$table_name = "product_side_stone".$tmpA[1];
								$status_field = "product_side_stone".$tmpA[1]."_status";
								$category_field = 'product_side_stone'.$tmpA[1].'_size';
								$category_field1 = "pss".$tmpA[1]."_diamond_shape_id";
							}
							else
							{
								$table_name = "product_side_stones";
								$status_field = "product_side_stones_status";
								$category_field = 'product_side_stones_size';
								$category_field1 = "psss_diamond_shape_id";
							}
	
	
							if( $compAttrArr[$K1]["ims_input_type"] == "TXT" )
							{
								$val = exeQuery(" SELECT ".$category_field." FROM ".$table_name."
												  WHERE inventory_master_specifier_id=".$compAttrArr[$K1]["inventory_master_specifier_id"]."
														AND product_id=".$list["product_id"]."
														AND ".$status_field."=0 ", true, $category_field);
							}
							else
							{
								$val = "";
								$attrRes = executeQuery(" SELECT pa.pa_value
														  FROM ".$table_name." ss INNER JOIN product_attribute pa
														  ON pa.product_attribute_id=ss.".$category_field1."
												  	  	  WHERE ss.inventory_master_specifier_id=".$compAttrArr[$K1]["inventory_master_specifier_id"]."
																AND ss.product_id=".$list["product_id"]."
																AND ".$status_field."=0 ");
								if( $compAttrArr[$K1]["ims_input_type"] == "CHK" )
								{
									if( !isEmptyArr($attrRes) )
									{
										foreach ($attrRes as $arK=>$arV)
										{
											$val .= $arV["pa_value"]."|";
										}
									}
								}
								else
								{
									if( !isEmptyArr($attrRes) )
									{
										foreach ($attrRes as $arK=>$arV)
										{
											$val .= $arV["pa_value"];
											break;
										}
									}
								}
							}
						}
	
						$newtitle = str_replace(",",".",$val);
						$newtitle = str_replace("","'",unhtmlentities($newtitle));
						$list[ $key ] = ("\"".$newtitle."\"");
					}
				}
					
				$fileText = implode($sep,$list)."\n";  //excel:\t
				fwrite($handle1, $fileText);
			}
		}
		fclose($handle1);
	
		myForceDownload($fileName);
		unlink($fileName);
	}
	
	/**
	 *
	 */
	function importProcessFetchCategoryID( $category_name )
	{
		$category_id = 0;
		if( MANUFACTURER_ID != 7 )
		{
			$category_id = exeQuery("	SELECT pc.category_id FROM product_categories pc
												INNER JOIN product_categories_cctld pcc
												ON (pcc.category_id=pc.category_id AND pcc.manufacturer_id=".MANUFACTURER_ID." )
												WHERE pcc.category_name='". $category_name ."' ",
					true, "category_id");
		}
	
		/**
		 *
		 */
		if( empty($category_id) )
		{
			$category_id = getField("category_id", "product_categories", "category_name", $category_name);
		}
	
		return $category_id;
	}
	
	/**
	 *
	 */
	function importProcessFetchAttributeID( $inventory_master_specifier_id, $pa_value, $k, $product_id, $is_external=false )
	{
		$CI =& get_instance();
		$value = "";
		$product_attribute_id = 0;
		$pa_value = trim( $pa_value );
		if( empty($pa_value) )	{ return 0; }
	
		//
		if( MANUFACTURER_ID != 7 )	//if( MANUFACTURER_ID != 7 )
		{
			if( $is_external )
			{
				$value = "AND pa.pa_real_value=". mysql_real_escape_string( $pa_value );
			}
			else
			{
				$value = "AND pac.pa_value='". mysql_real_escape_string( $pa_value )."'";
			}
				
			$product_attribute_id = exeQuery("	SELECT pa.product_attribute_id FROM product_attribute pa
												INNER JOIN product_attribute_cctld pac
												ON (pac.product_attribute_id=pa.product_attribute_id AND pac.manufacturer_id=".MANUFACTURER_ID." )
												WHERE inventory_master_specifier_id=".$inventory_master_specifier_id." ".$value,
					true, "product_attribute_id");
			//WHERE inventory_master_specifier_id=".$inventory_master_specifier_id." AND pac.pa_value='". mysql_real_escape_string( $pa_value ) ."' ",
		}
	
		/**
		 *
		 */
		if( empty($product_attribute_id) )
		{
			if( $is_external )
			{
				$value = "AND pa_real_value=". mysql_real_escape_string( $pa_value );
			}
			else
			{
				$value = "AND pa_value='". mysql_real_escape_string( $pa_value )."'";
			}
				
			$product_attribute_id = exeQuery("	SELECT product_attribute_id FROM product_attribute
												WHERE inventory_master_specifier_id=".$inventory_master_specifier_id." ".$value,
					true, "product_attribute_id");
			//WHERE inventory_master_specifier_id=".$inventory_master_specifier_id."AND pa_value='". mysql_real_escape_string( $pa_value ) ."' ",
		}
	
	
		/**
		 * insert if not found, but only if insert mode to avoid lossing attributes inserted from other language in update mode.
		 */
		if( empty($product_attribute_id) && empty($product_id) )
		{
			$this->load->model('admin/mdl_product_attribute','pamdl');
			$this->pamdl->cTableName = "product_attribute";
			$this->pamdl->cAutoId = "product_attribute_id";
	
			$paData = array();
			$paData["inventory_master_specifier_id"] = $inventory_master_specifier_id;
			$paData["pa_value"] = $pa_value;
			if( $is_external )
			{
				$paData["pa_real_value"] = $pa_value;
			}
			$paData["pa_sort_order"] = 10000;
				
			$logType = "";
			$product_attribute_id = $this->pamdl->saveItem( $paData, $logType );
				
			//
			if( !$is_external )
			{
				ieh_warningMsg("Row number ".$k." attribute inserted with ID: ".$product_attribute_id." for Attribute Value: ".$pa_value);
			}
		}
	
		return $product_attribute_id;
	}
	
	/********************************* Export-Import functions end *********************************/
	
}