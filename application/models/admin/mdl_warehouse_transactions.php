<?php
class mdl_warehouse_transactions extends CI_Model
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
			$wt_type = $this->input->get('wt_type');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			$status_filter = $this->input->get('status_filter');
			
			if(isset($product_filter) && $product_filter != "")
			{
				$this->db->where( $this->cTableName.'.product_id', $product_filter ); 
			}
			
			if(isset($wt_type) && $wt_type != "")
			{
				$this->db->where( $this->cTableName.'.wt_type', $wt_type );
			}
			
			if( isset($status_filter) && $status_filter != "" )
			{
				$this->db->where( $this->cTableName.'.wt_status LIKE \''.$status_filter.'\' ');
			}
			
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.wt_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);

			if($f !='' && $s != '' )
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cTableName.".".$this->cAutoId,'ASC');
				
			$this->db->select( " warehouse_transactions.*, product.product_name, product_value.pv_quantity_unit " );
			$this->db->join('product', 'product.product_id = warehouse_transactions.product_id', 'INNER');
			$this->db->join('product_value', 'product_value.product_id = product.product_id', 'INNER');
			
		}
		else if($this->cPrimaryId != '')
		{
			$this->db->select( " warehouse_transactions.*, product_value.pv_quantity_unit " );
			$this->db->join('product_value', 'product_value.product_id = warehouse_transactions.product_id', 'INNER');
			$this->db->where( $this->cTableName.".".$this->cAutoId, $this->cPrimaryId);
			
		}

		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
	}
	
	function saveData()
	{
		// post data for insert and edit
		$data = $this->input->post();
		
		unset($data['item_id']);
		unset($data['wt_total']);
		//unset($data['pv_quantity_unit']);
		$data["wt_type"] = 1; //Purchase (Offline), adds into warehouse quntity
		$data["wt_rateReflective"] = $data["wt_rate"]; 
		
		
		$this->db->set('wt_modified_date', 'NOW()', FALSE);
		if($this->cPrimaryId != '')
		{
			//
			hewr_editWarehouseTransactions($data['product_id'], $this->cPrimaryId, $data['wt_qty'], $data["wt_rate"], $data["wt_rateReflective"], $data);
				
			$paName = $this->cPrimaryId."-0-".$data["wt_type"];
			//$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$last_id = $this->cPrimaryId;
			$logType = 'E';
		}
		else // insert new row
		{
			
			$paName = $this->cPrimaryId."-".$data['product_id']."-".$data["wt_type"];
			//$this->db->insert($this->cTableName,$data);
			$last_id = hewr_addWarehouseTransactions($data['product_id'], $data['wt_qty'], $data["wt_rate"], $data["wt_rateReflective"], $data);
			$logType = 'A';			
			
			//
			
		}
		
		//class name, item name, tablename, fieldname, primary id, type A/E/D
		saveAdminLog($this->router->class, @$paName, $this->cTableName, $this->cAutoId, $last_id, $logType); 
		setFlashMessage('success','Warehouse Transactions has been '.(($this->cPrimaryId != '') ? 'updated': 'inserted').' successfully.');
		
		return $last_id;
				
	}
	
	function importProductcode()
	{
		$image = uploadFile('import_csv','All','importdata');
		$type = "success";
		$msg = "Successfully imported in warehouse transactions.<br>";
		
		if(isset($_FILES['import_csv']['name']))
		{
			$extArr = explode(".",$_FILES['import_csv']['name']);
			$path = $image['path'];
			$resArr = readCsvNew($path);
			
			foreach($resArr as $k=>$ar)
			{	
				if($k != 0)
				{
					$f_product = fetchRow( "SELECT product_id,product_price FROM product WHERE product_sku = '".trim($ar[0])."' ");
					
					if( !isEmptyArr( $f_product ) )
					{
						$data = array();
						
						/**
						 * Cloudwebs On 04-05-2015 since sale expects qty in minus(-) value
						 */
						$ar[2] = - (float) trim($ar[2]);  
						
						$data['product_id'] = $f_product['product_id'];
						$data['purchase_sales_order_id'] = cmn_hlp_strtotime( $ar[5] );
						$data['wt_qty'] = $ar[2];
						$data["wt_rateReflective"] = trim($ar[3]);
						$data["wt_type"] = 3;	//Sale (Offline)
						
						$wtData = exeQuery( "SELECT warehouse_transactions_id, wt_rate FROM ".$this->cTableName."
											  					WHERE product_id=".$f_product["product_id"]."
											  					AND purchase_sales_order_id=".$data["purchase_sales_order_id"]." 
																LIMIT 1 " ); 
						if( empty( $wtData["warehouse_transactions_id"] ) )
						{
							$data["wt_rate"] = $f_product['product_price'];
							hewr_addWarehouseTransactions($f_product['product_id'], $ar[2], $f_product['product_price'], trim($ar[3]), $data);
						}
						else 
						{
							/**
							 * read net as it is from old transaction, as it is done in cart_hlp_warehouseTransaction.
							 */
							$data["wt_rate"] = $wtData['wt_rate'];
							hewr_editWarehouseTransactions($f_product['product_id'], $wtData["warehouse_transactions_id"], $ar[2], $wtData['wt_rate'], trim($ar[3]), $data);
						}
					}
					else 
					{
						$msg .= "Product SKU: ".trim($ar[0])." not found.<br>";  
					}
				}
			}
		}
		
		/**
		 * remove file
		 */
		$this->load->helper("custom_file"); 
		hefile_imfile_remove($path); 
		
		return array("type"=>$type, "msg"=>$msg); 
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
	
		$data['wt_status'] = $status;
	
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
}