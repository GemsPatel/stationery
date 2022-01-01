<?php
class mdl_warehouse_products extends CI_Model
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
			$product_filter = $this->input->get('product_filter');
			
			if(isset($product_filter) && $product_filter != "")
			{
				$this->db->where( $this->cTableName.'.product_id', $product_filter ); 
			}
			
			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cTableName.".".$this->cAutoId,'ASC');
				
			$select  = "";
			
			/**
			 * either to use NET RATE or REFLECTIVE RATE 
			 */
			if( CLIENT === "Stationery" )
			{
				$select  = "product.product_price as product_price, "; 
			}
			else 
			{
				$select  = "product.pv_reflective_price as product_price, ";
			}

			if( MANUFACTURER_ID == 7 )
			{
				$this->db->select( " product.product_id, product.product_name, ".$select." product_value.product_value_quantity, 
									 product_price.product_price_calculated_price, product_price.product_discounted_price " );
				$this->db->join('product_value', 'product_value.product_id = product.product_id', 'INNER');
				$this->db->join('product_price', 'product_price.product_id = product.product_id', 'LEFT');
				$this->db->where('product_price.product_price_status', 0);
			}
			else 
			{
				$this->db->select( " product.product_id, product.product_name, ".$select." product_value.product_value_quantity, 
									 product_price_cctld.product_price_calculated_price, product_price_cctld.product_discounted_price " );
				$this->db->join('product_value', 'product_value.product_id = product.product_id', 'INNER');
				$this->db->join('product_price', 'product_price.product_id = product.product_id', 'LEFT');
				$this->db->join('product_price_cctld', 'product_price_cctld.product_price_id = product_price.product_price_id', 'LEFT');
				$this->db->where("product_price_cctld.manufacturer_id", MANUFACTURER_ID);
				$this->db->where('product_price_cctld.product_price_status', 0);
			}
			
			$this->db->group_by($this->cTableName.".product_id");
		}

		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		
		//
		if( IS_LOG )
		{
			/*$datetime = date('d-m-Y-H:i:s');
			 $admin_user_id = $this->session->userdata('admin_id');
			$admin_user = getField('admin_user_firstname','admin_user','admin_user_id',$admin_user_id);
			$fp = fopen(BASE_DIR."assets/product/log/product-log-".$admin_user_id."-".time().".xml","w");
			fwrite($fp,'<?xml version="1.0" encoding="ISO-8859-1"?><note><Datetime>'.$datetime.'</Datetime><updateby>'.$admin_user.'</updateby><ProductID>'.$data["product_id"].'</ProductID><starttime>'.$datetime.'</starttime>
					<isselectionupdated>'.$is_selection_updated.'</isselectionupdated>');
			//log
			fwrite($fp,'<mode>Edit From Warehouse Product RATE SET</mode>');
			
			fwrite($fp,'<mode>Edit</mode>');*/
		}

		//In update mode change all price status to 1-disabled as some combinations may be deselected
		if( MANUFACTURER_ID == 7 )
		{
			$this->db->query("update product_price SET product_price_status_temp=1,product_price_modified_date=NOW() WHERE product_id=".$data["product_id"]."");
		}
		else
		{
			$this->db->query("update product_price_cctld SET product_price_status_temp=1,product_price_cctld_modified_date=NOW()
							  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
							 ( SELECT product_price_id FROM product_price WHERE product_id=".$data["product_id"]." )");
		}

			
		if( IS_LOG )
		{
			//fwrite($fp,'<iscalled>Yes</iscalled>');
		}
	
		//update/insert product pricing
		update_insertProductPrice( $data["product_id"], 1, false, false, false, 
								   $data["product_price_calculated_price"], $data["product_discounted_price"], 
								   0); 
	
		if( MANUFACTURER_ID == 7 )
		{
			$this->db->query("update product_price SET product_price_status=product_price_status_temp WHERE product_id=".$data["product_id"]."");
		}
		else
		{
			$this->db->query("update product_price_cctld SET product_price_status=product_price_status_temp
							  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
							 ( SELECT product_price_id FROM product_price WHERE product_id=".$data["product_id"]." ) ");
		}
		
		if( IS_LOG )
		{
			/*fwrite($fp,'</note>');
			fclose($fp);*/
		}
		
		
		//class name, item name, tablename, fieldname, primary id, type A/E/D
		$paName = "product_id-".$data["product_id"];
		saveAdminLog($this->router->class, @$paName, $this->cTableName, $this->cAutoId, $data["product_id"], "E"); 
		
		return array("type"=>"success", "msg"=>"Product Price updated successfully");
	}

}