<?php
class mdl_sales_order_return extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cCategory = '';
	var $prev_credited = 0;		//used in update mode
	var $prev_quantity = 0;		//used in update mode
	var $product_id = 0;

/**
 * 
 * @return unknown
 */
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$return_id_filter = $this->input->get('return_id_filter');
			$order_id_filter = $this->input->get('order_id_filter');
			$customer_name_filter = $this->input->get('customer_name_filter');
			$product_filter = $this->input->get('product_filter');
			$reason_filter = $this->input->get('reason_filter');
			$order_status_id = $this->input->get('order_status_id');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('customer_firstname LIKE \'%'.$customer_name_filter.'%\' OR customer_lastname LIKE \'%'.$customer_name_filter.'%\' ');
			
			if(isset($return_id_filter) && $return_id_filter != "")
				$this->db->where('order_return_id LIKE \''.$return_id_filter.'\' ');
				
			if(isset($order_id_filter) && $order_id_filter != "")
				$this->db->where('orders.order_id LIKE \''.$order_id_filter.'%\' ');
					
			if(isset($product_filter) && $product_filter != "")
				$this->db->where('product_name LIKE \'%'.$product_filter.'%\' ');
			
			if(isset($reason_filter) && $reason_filter != "")
				$this->db->where('order_return_reason_key LIKE \'%'.$reason_filter.'%\' ');
			
			if(isset($order_status_id) && $order_status_id != "")
				$this->db->where('order_status_id LIKE \''.$order_status_id.'\' ');
	
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.order_return_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoId,'DESC');
			
			if( MANUFACTURER_ID != 7 )
			{
				/**
				 * 
				 */
				if( IS_CS )
				{
					$this->db->where('orders.manufacturer_id', MANUFACTURER_ID );
				}
			}
				
			
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
			
		$this->db->select($this->cTableName.'.*, order_details.order_id,product_name, customer_firstname,customer_lastname,orders.customer_id');
		$this->db->join('order_details','order_details.order_details_id='.$this->cTableName.'.order_details_id','left');
		$this->db->join('product','product.product_id=order_details.product_id','left');
		$this->db->join('orders','orders.order_id=order_details.order_id','left');
		$this->db->join('customer','customer.customer_id=orders.customer_id','left');
		//$this->db->where();
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();
		return $res;
		
	}

/**
+----------------------------------------------------------+
	function will insert/update data
+----------------------------------------------------------+
*/
	function saveData()
	{
		$data = $this->input->post();
		unset( $data['item_id'] );
		$data_cust_acc = array();
		$amt = 0;

		/**
		 * On 04-05-2015 return qty 0 is allowed, DELETE action is turned off
		 */
		$data['order_return_quantity'] = (float) $data['order_return_quantity']; 
		
		//? seems unnecessary, no useful also!!!
		//update order details table
		$data_ord_det['order_details_is_returned'] = 1;
		$data_ord_det['order_details_return_quantity'] = $data['order_return_quantity'];
		$this->db->set('order_details_modified_date', 'NOW()', FALSE);
		$this->db->where("order_details_id",$data['order_details_id'])->update("order_details",$data_ord_det);

		/**
		 * 1:1 mapping between order_details to order_return see UML 73 for further details
		 */ 
		if( empty($this->cPrimaryId) )
		{
			$this->cPrimaryId = (int) getField("order_return_id", "order_return", "order_details_id", $data['order_details_id']);
		}
		

		//
		if( !empty( $this->cPrimaryId ) )
		{
			$this->db->set('order_return_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
		}

		//product information
		$f_product  = fetchRow("SELECT p.product_id, p.inventory_type_id, p.product_price, p.product_sku, 
								od.warehouse_transactions_id, od.customer_account_manage_id, od.order_details_amt, od.order_details_product_qty 
								FROM order_details od 
								INNER JOIN product p 
								ON p.product_id=od.product_id  
								WHERE od.order_details_id=".$data['order_details_id']." 
								LIMIT 1 " );
		
		/**
		 * Update warehouse as per return action
		 */
		cart_hlp_warehouseTransaction( false, $f_product["inventory_type_id"], $f_product["warehouse_transactions_id"], $f_product['product_id'], 
									   -( $f_product['order_details_product_qty'] - $data['order_return_quantity'] ), 0 ); 
		/****************************** END warehouse transaction **************************************/
		
		
		/**
		 * Since order_status_id is "SAVE" only can not be edited, 
		 * so if edit mode then fetch order status id from order return so that if BUCKs transaction applicable, 
		 * it can be applied. 
		 */
		if( !empty( $this->cPrimaryId ) )
		{
			$data['order_status_id'] = (int) getField("order_status_id", "order_return", "order_return_id", $this->cPrimaryId); 
		}
		
		/**
		 * If order returned with status "REFUND_BUCKS" then credit in user account BUCKS applicable
		 */
		if($data['order_status_id'] == 23)
		{
			//fetch order info
			$orderData = fetchRow( "SELECT customer_id, order_total_amt FROM orders WHERE order_id = ".$data['order_id']." LIMIT 1 " );
			
			//total credited back so far this order
			$total_credited = exeQuery( "SELECT SUM(customer_account_manage_credit) as 'Total' FROM customer_account_manage 
										 WHERE order_id = ".$data['order_id']." AND customer_account_manage_entry_type=3 ", 
										 true, "Total" ); 

			//amount to credit
			$customer_account_manage_credit = $f_product['order_details_amt'] * $data['order_return_quantity'];
			
			/**
			 * Resolution: BUG 391 POINT 9 
			 * Allow credit in return until it is less then or equal to order_total_amt
			 */
			if( ( $total_credited + $customer_account_manage_credit ) > $orderData["order_total_amt"] )
			{
				//is it new return entry or is it update?
				if( empty( $f_product["customer_account_manage_id"] ) )
				{
					//new entry so allow rest credit possible to user
					$customer_account_manage_credit = $orderData["order_total_amt"] - $total_credited; 
				}
				else 
				{
					/**
					 * update entry so allow user to credit up to
					 * 
					 * 
					 * $total_credited = $total_credited - $old_customer_account_manage_credit(of this entry)
					 * 
					 * if( is goes outer of total order amt ) then
					 * 		$customer_account_manage_credit = $orderData["order_total_amt"] - $total_credited;
					 * else 
					 * 		just let it be credited as it is 
					 */ 
					$old_customer_account_manage_credit = exeQuery( "SELECT customer_account_manage_credit FROM customer_account_manage 
										 							 WHERE customer_account_manage_id = ".$f_product["customer_account_manage_id"]." ", 
										 							 true, "customer_account_manage_credit" );
					$total_credited = $total_credited - $old_customer_account_manage_credit;
					
					/**
					 * 
					 */
					if( ( $total_credited + $customer_account_manage_credit ) > $orderData["order_total_amt"] )
					{
						$customer_account_manage_credit = $orderData["order_total_amt"] - $total_credited;
					}
					
				}
			}
			
			/**
			 * add or update the BUCKs transaction for this order_details_id
			 */
			$customer_account_manage_id = hecam_bucksTransaction( false, $f_product["customer_account_manage_id"], $orderData["customer_id"], 
																	$data['order_id'], 
																	$data['order_details_id'], $customer_account_manage_credit, 0, 3 );

			if( empty( $f_product["customer_account_manage_id"] ) )
			{
				query("UPDATE order_details SET customer_account_manage_id=".$customer_account_manage_id." 
					   WHERE order_details_id=".$data["order_details_id"]." ");
			}
		}	
		/****************************** END BUCKS transaction **************************************/
		
		setFlashMessage('success','Order Return has been '.(($this->cPrimaryId != '' && !isset($_GET['reorder'])) ? 'updated': 'inserted').' successfully.');
	}

/**
 * @deprecated
 * function held for removal
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
				$getName = getField('order_return_id', $this->cTableName, $this->cAutoId, $id);
				saveAdminLog($this->router->class, @$getName, $this->cTableName, $this->cAutoId, $id, 'D');

				//fetch customer balance
				$cust_acc_bal = 0;
				$res = executeQuery("SELECT o.order_id,d.product_id,r.order_details_id,r.order_return_quantity,r.order_return_action FROM order_return r INNER JOIN order_details d 
					   ON d.order_details_id=r.order_details_id INNER JOIN orders o 
					   ON o.order_id=d.order_id WHERE order_return_id=".$id." LIMIT 1");
										
				if(!empty($res))
				{	
					if($res[0]['order_return_action'] == "C")
					{
						$this->prev_quantity = $res[0]['order_return_quantity'];
						$data_cust_acc = $this->calcCreditValue($res[0]['order_id'], $res[0]['order_details_id'],$res[0]['order_return_quantity'],false);
						$data_cust_acc['customer_account_manage_entry_type'] = "D";
						$this->db->insert("customer_account_manage",$data_cust_acc);
					}
					
					updateProductQuantity($this->product_id, $this->prev_quantity);
				}
				$this->db->where_in($this->cAutoId,$id)->delete($this->cTableName);
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

/**
+-----------------------------------------+
	function will fetch products orderd in particular order to display in form select box
+-----------------------------------------+
*/		
	function fetchOrderDetails( $order_id=0, $order_details_id=0 )
	{	
		if( empty($order_id) )
		{
			$order_id = $this->input->post('order_id');
		}
		
		if( cart_hlp_isOrderCompleted($order_id) || cart_hlp_isOrderCancelled($order_id) )
		{
			return "Order status is completed or cancelled, you can not return in this order.";
		}
		else 
		{
			$sql = "SELECT order_details_id, CONCAT(product_name,' ',pp.product_generated_code_displayable) as 'product_name'
				FROM order_details d
				INNER JOIN product p
				ON p.product_id=d.product_id
				INNER JOIN product_price pp
				ON pp.product_price_id=d.product_price_id	
			    WHERE order_id=".$order_id."";
				
			$detArr = getDropDownAry($sql,"order_details_id", "product_name", array('' => "-Select Product-"), false);
			return form_dropdown('order_details_id',@$detArr, $order_details_id,'style="width:10%;" onchange="fetchQuantity(this.value)"');
		}
	}

/**
+-----------------------------------------+
	function will fetch products ordered quantity to display in select box
+-----------------------------------------+
*/		
	function fetchQuantity( $order_details_id=0, $order_return_quantity=0 )
	{	
		if( empty($order_details_id) )
		{
			$order_details_id = $this->input->post('order_details_id');
		}
		
		$sql = "SELECT od.order_details_product_qty, p.product_id, p.inventory_type_id, pp.product_price_id,
  					   pp.product_generated_code_info
  								FROM order_details od
  								INNER JOIN product p
  								ON p.product_id=od.product_id
  								INNER JOIN product_price pp
  								ON pp.product_price_id=od.product_price_id
  								WHERE order_details_id=".$order_details_id."";
		$res = executeQuery($sql);
		$qty = $res[0]['order_details_product_qty'];
		
		if( hewr_isGroceryInventoryCheckWithId( $res[0]["inventory_type_id"] ) )
		{
			$tmpArr = getProdQtyOptions( $res[0]["product_id"], $res[0]["product_generated_code_info"], array( ''=>"Quantity", 0=>"0" ) );
			$optArr = array(); 
			foreach ($tmpArr as $k=>$ar)
			{
				if( $k <= $qty )
				{
					$optArr[$k] = $ar; 
				}
			}
		 
			return form_dropdown( 'order_return_quantity', $optArr, $order_return_quantity,' id="order_return_quantity" style="width:8%;" ');
		}
		else 
		{
			$html = '<select name="order_return_quantity" style="width:8%;"><option value="">-Select Qty-</option>
					<option value="0">0</option>';
			
			for($i=1;$i<=$qty;$i++)
			{
				if($i == @$order_return_quantity)
					$html .= '<option value="'.$i.'" selected="selected">'.$i.'</option>';
				else
					$html .= '<option value="'.$i.'">'.$i.'</option>';
			}
			$html .= '</select>';
			
			return $html;
		}
	}

/**
 * @deprecated
 * function held for removal
+-----------------------------------------+
	function will calculate value to be credited to customer as per product
+-----------------------------------------+
*/		
	function calcCreditValue($order_id,$order_details_id,$order_return_quantity,$is_credit)
	{	
		$data_cust_acc['customer_id'] = getField("customer_id","orders","order_id",$order_id);							
		$data_cust_acc['order_id'] = $order_id;							
		$data_cust_acc['order_details_id'] = $order_details_id;			
		$res = executeQuery("SELECT * FROM order_details WHERE order_details_id=".$order_details_id."");
		
		$this->product_id = $res[0]['product_id'];
		$prod_price = (float)$res[0]['order_details_product_price'] + (float)$res[0]['order_details_product_shipping_cost'] + (float)$res[0]['order_details_product_cod_cost']; 
		$subTot = $amt = round($prod_price * (int)$order_return_quantity,2);		  //subtotal
		$dis = round($subTot * ((float)$res[0]['order_details_product_discount']/100),2);	 //deduct discount
		$tax = 0;
	
		if($res[0]['order_details_product_tax'] != "")	//apply product wise tax
		{
			$taxArr = explode("|",$res[0]['order_details_product_tax']);
			foreach($taxArr  as $k=>$ar)
			{
				$taxrateArr = explode(",",$ar);	
				if($taxrateArr[0] == "Fix")
				{
					$tax += round((float)$taxrateArr[1]*$order_return_quantity,2);
				}
				else
				{
					$tax += round($subTot * ((float)$taxrateArr[1]/100),2);
				}
			}
		}
		else	//apply general product tax if product wise not available
		{
			$taxRate = getField("order_tax_percent","orders","order_id",$order_id);
			$tax += round($subTot * ((float)$taxRate/100),2);
		}
		$amt = $subTot + $tax - $dis;							
		
		$coupon_id = getField("coupon_id","orders","order_id",$order_id); //apply coupon discount if available
		if((int)$coupon_id != 0)
		{
			$res = executeQuery("SELECT coupon_type, coupon_discount_amt FROM coupon WHERE coupon_id=".$coupon_id."");
			if(!empty($res))
			{
				if($res[0]['coupon_type'] = "Fix")
				{
					$res = executeQuery("SELECT COUNT(*) as 'Tot' FROM order_details WHERE order_id=".$order_id."");
					$amt -= round((float)$res[0]['coupon_discount_amt']/(int)$res[0]['Tot'],2);			// coupon discount amount will not be credited no ==> formula if fix amount: coup_disc_amt/ no_of_ordered_items
				}
				else
					$amt -= round($subTot * ((float)$res[0]['coupon_discount_amt']/100),2);
			}
		}
					
		$cust_acc_bal = 0;
		$res = executeQuery("SELECT customer_account_manage_balance FROM customer_account_manage WHERE customer_id=".$data_cust_acc['customer_id']." ORDER BY customer_account_manage_id DESC LIMIT 1");
		if(!empty($res))
			$cust_acc_bal = $res[0]['customer_account_manage_balance'];		
			
		if($is_credit)	
		{
			$data_cust_acc['customer_account_manage_credit'] = $amt;		
			$data_cust_acc['customer_account_manage_balance'] = $cust_acc_bal + $amt;
		}
		else	
		{
			$data_cust_acc['customer_account_manage_debit'] = $amt;		
			$data_cust_acc['customer_account_manage_balance'] = $cust_acc_bal - $amt;
		}
		return $data_cust_acc;
	}

	
}
