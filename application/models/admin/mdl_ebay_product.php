<?php
class mdl_ebay_product extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	
	
	function getData($srchKey = '')
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$product_name = $this->input->get('product_name_filter');
			$product_code = $this->input->get('product_code_filter');
			$product_sku = $this->input->get('product_sku_filter');
			$product_status = $this->input->get('status_filter');
			$ebay_country_id = $this->input->get('ebay_country_id');
			$this->session->set_userdata( array('ebay_country_id' => $ebay_country_id) );
			
			if(isset($product_name) && $product_name != "")
				$this->db->where('product_name LIKE \'%'.$product_name.'%\' ');
			
			if(isset($product_code) && $product_code != "")
				$this->db->where('product_generated_code LIKE \'%'.trim($product_code).'%\' ');
			
			if(isset($product_sku) && $product_sku != "")
				$this->db->where('product_sku LIKE \'%'.$product_sku.'%\' ');
				
			if(isset($product_status) && $product_status != "")
				$this->db->where('ep_status LIKE \''.$product_status.'\' ');
			
			if(isset($ebay_country_id) && $ebay_country_id != "" && $ebay_country_id != -1 )
				$this->db->where('ep_site_id = '.$ebay_country_id);

			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by("ebay_product.ebay_products_id",'ASC');

			
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->select( " product.product_id, product.product_name, product.product_sku, product.product_modified_date, product_cctld.product_status as product_status, product_price.product_price_id as product_price_id, product_price.product_generated_code, ebay_product.ebay_products_id, ebay_product.product_id as ebay_product_id, ebay_product.product_price_id as ebay_product_price_id, ebay_product.ep_site_id, ebay_product.ep_item_id, ebay_product.ep_title, ebay_product.ep_product_price, ebay_product.ep_qty, ebay_product.ep_listing_duration, ebay_product.ep_is_auto_listing, ebay_product.ep_status,ebay_product.ep_mode, ebay_product.ep_created_date " );
	 		    $this->db->join('product_cctld', 'product_cctld.product_id=product.product_id', 'INNER');
				$this->db->where( 'product.product_manufacturer_id', MANUFACTURER_ID);
				$this->db->where( 'product_cctld.manufacturer_id', MANUFACTURER_ID);
			}
			else
			{
				$this->db->select( " product.product_id, product.product_name, product.product_sku, product.product_modified_date, product.product_status, product_price.product_price_id as product_price_id, product_price.product_generated_code, ebay_product.ebay_products_id, ebay_product.product_id as ebay_product_id, ebay_product.product_price_id as ebay_product_price_id, ebay_product.ep_site_id, ebay_product.ep_item_id, ebay_product.ep_title, ebay_product.ep_product_price, ebay_product.ep_qty, ebay_product.ep_listing_duration, ebay_product.ep_is_auto_listing, ebay_product.ep_status,ebay_product.ep_mode, ebay_product.ep_created_date " );
			}
					
			$this->db->join('product_price','product_price.product_id='.$this->cTableName.'.product_id','LEFT');
			$this->db->join('product_price_cctld','product_price_cctld.product_price_id=product_price.product_price_id','LEFT');
			$this->db->join('ebay_product','ebay_product.product_price_id=product_price.product_price_id','LEFT');
			//$this->db->group_by('product_price_id');
					
			//$this->db->where( "product_price_cctld.product_price_status", 0 ); 
			
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
								  mt.product_metal_weight
								  FROM product p LEFT JOIN product_value v
								  ON v.product_id=p.product_id LEFT JOIN product_center_stone cs 
								  ON (cs.product_id=p.product_id AND product_center_stone_status=0) LEFT JOIN product_side_stone1 ss1
								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0) LEFT JOIN product_side_stone2 ss2
								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0) LEFT JOIN product_metal mt 
								  ON mt.product_id=p.product_id 
								  WHERE p.product_id=".$this->cPrimaryId." ";
			}
			else
			{
				$sql = " SELECT DISTINCT p.*, pc.product_metal_priority_id as product_metal_priority_id, pc.product_cs_priority_id as product_cs_priority_id, 
								  pc.product_ss1_priority_id as product_ss1_priority_id, pc.product_ss2_priority_id as product_ss2_priority_id, 
								  pc.product_status as product_status, 
								  v.*,cs.product_center_stone_weight,cs.product_center_stone_size,cs.product_center_stone_total,cs.pcs_diamond_shape_id,
								  ss1.product_side_stone1_weight,ss1.product_side_stone1_size,ss1.product_side_stone1_total,ss1.pss1_diamond_shape_id,
								  ss2.product_side_stone2_weight,ss2.product_side_stone2_size,ss2.product_side_stone2_total,ss2.pss2_diamond_shape_id,
								  mt.product_metal_weight
								  FROM product p LEFT JOIN product_cctld pc 
								  ON ( pc.product_id=p.product_id AND pc.manufacturer_id=".MANUFACTURER_ID." ) 
								  LEFT JOIN product_value v
								  ON v.product_id=p.product_id LEFT JOIN product_center_stone cs 
								  ON (cs.product_id=p.product_id AND product_center_stone_status=0) LEFT JOIN product_side_stone1 ss1
								  ON (ss1.product_id=p.product_id AND product_side_stone1_status=0) LEFT JOIN product_side_stone2 ss2
								  ON (ss2.product_id=p.product_id AND product_side_stone2_status=0) LEFT JOIN product_metal mt 
								  ON mt.product_id=p.product_id 
								  WHERE p.product_id=".$this->cPrimaryId." ";
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
	
            $sql = "SELECT category_id, product_stone_number, product_side_stones_weight, psss_diamond_shape_id, product_side_stones_size,  
					product_side_stones_total FROM product_side_stones WHERE product_id=".$this->cPrimaryId." AND product_side_stones_status=0 GROUP BY product_stone_number";
			$product_side_stonesData = $this->db->query( $sql )->result_array();
			
			return array('resP'=>$resP,'resCS'=>$resCS,'resSS1'=>$resSS1,'resSS2'=>$resSS2,'resM'=>$resM,'product_side_stonesData'=>$product_side_stonesData);
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
				$strArr = explode('|',$id);
				$id = $strArr[0];
				
				$this->cTableName = 'ebay_product';
				$this->cAutoId = 'ebay_products_id';
				$getName = getField('ep_title', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');
				
				$this->db->where($this->cAutoId,$id)->delete($this->cTableName);
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
	
	
	//Function will update ebay title
	function saveEbayData()
	{
		$data = $this->input->post();
		
		if(!empty($data['ebay_site_id']))
			$this->db->where('ep_site_id',@$data['ebay_site_id']);
		
		if(!empty($data['ebay_products_id']))
		{
			//$mode = getField("ep_mode","ebay_product","ebay_products_id",@$data['ebay_products_id']);
			//$this->db->where('ep_mode',$mode);
			$this->db->where('ebay_products_id',@$data['ebay_products_id']);
		}
		$ebayArr = $this->db->where('product_id',@$data['product_id'])->where('product_price_id',@$data['product_price_id'])->get('ebay_product')->row_array();
		
		if(!empty($ebayArr['ebay_products_id']))
		{
			$ebayData['ep_title'] = $data['ebay_title'];
			$ebayData['ep_product_price'] = $data['ebay_price'];
			$ebayData['ep_qty'] = $data['ebay_qty'];
			$ebayData['ep_listing_duration'] = $data['ebay_duration'];
			$ebayData['ep_is_auto_listing'] = $data['is_auto_listing'];
			$ebayData['ep_site_id'] = $data['ebay_site_id'];
			$this->db->where('ebay_products_id', $ebayArr['ebay_products_id']);
			$this->db->update('ebay_product',$ebayData);
		}
		else
		{
			$ebayData = array(
				'product_id' => @$data['product_id'],
				'product_price_id' => @$data['product_price_id'],
				'ep_title' => @$data['ebay_title'],
				'ep_product_price' => @$data['ebay_price'],
				'ep_qty' => @$data['ebay_qty'],
				'ep_listing_duration' => @$data['ebay_duration'],
				'ep_is_auto_listing' => @$data['is_auto_listing'],
				'ep_site_id' => @$data['ebay_site_id'],
				'ep_item_id' => (!empty($data['ebay_item_id'])) ? $data['ebay_item_id'] : '',
				'ep_status' => 1
			);
			
			$this->db->insert('ebay_product', $ebayData);
		}
		//echo $this->db->last_query();
		$returnArr['success'] = 'Ebay product has been '.(($data['product_price_id'] != '') ? 'updated': 'inserted').' successfully.';
		echo json_encode($returnArr);
	}
	
/*
 * Function will saved product to ebay products listing 
 */
	function ajaxAddEbayListing()
	{
		$ids = $this->input->post('selected');
		if($ids)
		{
			foreach($ids as $id)
			{
				$strArr = explode('|',$id);
				$ebay_products_id = $strArr[0];
				$product_id = $strArr[1];
				$product_price_id = $strArr[2];
				
				if(!empty($ebay_products_id))
					$ebayArr = exeQuery('SELECT * FROM ebay_product WHERE ebay_products_id='.$ebay_products_id );
				else
					$ebayArr = exeQuery('SELECT product_id, product_name as ep_title FROM product WHERE product_id='.$product_id );
				
				$_POST['product_id'] = $ebayArr['product_id'];
				$_POST['product_price_id'] = (!empty($ebayArr['product_price_id'])) ? $ebayArr['product_price_id'] : $product_price_id;
				$_POST['ebay_title'] = $ebayArr['ep_title'];
				$_POST['ebay_price'] = @$ebayArr['ep_product_price'];
				$_POST['ebay_qty'] = (!empty($ebayArr['ep_qty'])) ? $ebayArr['ep_qty'] : 1;
				$_POST['ebay_duration'] = (!empty($ebayArr['ep_listing_duration'])) ? $ebayArr['ep_listing_duration'] : 3;
				$_POST['is_auto_listing'] = @$ebayArr['ep_is_auto_listing'];
				$this->saveEbayData();
			}
		}
		else{
			$returnArr['error'] = "Please select at least 1 item.";
			echo json_encode($returnArr);
		}
	}
	
}