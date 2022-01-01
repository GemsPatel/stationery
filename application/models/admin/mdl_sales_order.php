<?php
class mdl_sales_order extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cPrimaryId = '';
	var $cTableNameC = '';
	var $cAutoIdC = '';
	var $cPrimaryIdC = '';
	var $cCategory = '';
	var $adm_cartArr = '';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$invoice_number_filter = $this->input->get('invoice_number_filter');
			$customer_name_filter = $this->input->get('customer_name_filter');
			$customer_email_filter = $this->input->get('customer_email_filter');
			$payment_method_filter = $this->input->get('payment_method_filter');
			$order_status_id = $this->input->get('order_status_id');
			$ip_address_filter = $this->input->get('ip_address_filter');
			$status_filter = $this->input->get('order_status_id');
			$fromamt_filter = $this->input->get('fromamt_filter');
			$toamt_filter = $this->input->get('toamt_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			$this->db->select( 'orders.order_id, orders.invoice_number, orders.customer_id, orders.coupon_id, orders.order_total_qty, 
								orders.order_subtotal_amt, orders.order_discount_amount, orders.order_total_amt, orders.order_created_date, 
								orders.ip_address, 
								customer.customer_firstname, customer.customer_lastname, customer.customer_emailid, g.customer_group_name, 
								p.payment_method_name ' );

			if(isset($invoice_number_filter) && $invoice_number_filter != "")
				$this->db->where('invoice_number LIKE \''.$invoice_number_filter.'%\' ');

			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('(customer_firstname LIKE \'%'.$customer_name_filter.'%\' OR customer_lastname LIKE \'%'.$customer_name_filter.'%\' )');
			
			if(isset($customer_email_filter) && $customer_email_filter != "")
				$this->db->where('customer_emailid LIKE \'%'.$customer_email_filter.'%\' ');
				
			if(isset($payment_method_filter) && $payment_method_filter != "")
				$this->db->where('payment_method_name LIKE \'%'.$payment_method_filter.'%\' ');
			
			if(isset($order_status_id) && $order_status_id != "")
				$this->db->where('order_status_id LIKE \'%'.$order_status_id.'%\' ');
			
			if(isset($ip_address_filter) && $ip_address_filter != "")
				$this->db->where('ip_address LIKE \''.$ip_address_filter.'\' ');
		
			if($fromamt_filter && $toamt_filter)
				$this->db->where('(order_total_amt*1) BETWEEN '.$fromamt_filter.' and '.$toamt_filter.' ');
		
			if($fromDate && $toDate)
				$this->db->where('DATE_FORMAT('.$this->cTableName.'.order_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			if($f !='' && $s != '')
			{
				if($f=='order_total_amt')
					$this->db->order_by("(order_total_amt*1)",$s);
				else
					$this->db->order_by($f,$s);
			}
			else
				$this->db->order_by($this->cAutoId,'DESC');

			//if( MANUFACTURER_ID != 7 )
					
			/**
			 * added on 14-04-2015
			 * separate according to country wise store 
			 */
			
			$this->db->join('customer','customer.customer_id='.$this->cTableName.'.customer_id','INNER');
			$this->db->join('customer_group g','g.customer_group_id=customer.customer_group_id','INNER');
			$this->db->join('order_tracking od','od.order_id=orders.order_id','INNER');
			$this->db->join('order_transaction ot','ot.order_id=orders.order_id','LEFT');
			$this->db->join('payment_method p','p.payment_method_id=orders.payment_method_id','LEFT');
			
			/**
			 * added on 13-04-2015
			 */
			$this->db->where("orders.del_in","0");
			
			$this->db->group_by("orders.order_id");
			$res = $this->db->get($this->cTableName);
			
// 			echo $this->db->last_query();
		}
		else if($this->cPrimaryId != '')
		{
			$this->db->where("orders.".$this->cAutoId,$this->cPrimaryId);
	
			$this->db->select( 'orders.customer_id, orders.coupon_id, orders.order_total_qty, orders.order_subtotal_amt, orders.order_discount_amount, orders.order_total_amt, 
								orders.customer_shipping_address_id, orders.customer_billing_address_id, orders.shipping_method_id, orders.customer_note, 
								order_transaction.payment_method_id, 
								customer.customer_firstname, customer.customer_lastname, customer.customer_emailid, g.customer_group_name ' );
			$this->db->join('order_transaction','order_transaction.order_id='.$this->cTableName.'.order_id','LEFT');
			$this->db->join('customer','customer.customer_id='.$this->cTableName.'.customer_id','INNER');
			$this->db->join('customer_group g','g.customer_group_id=customer.customer_group_id','INNER');
			$this->db->where("orders.del_in","0");
			$this->db->group_by("orders.".$this->cAutoId);
			$res = $this->db->get($this->cTableName);
		}
		//echo $this->db->last_query();

		if($this->cPrimaryId != '')
		{
			$data = fetchOrdDetFromDatabase($this->cPrimaryId);
			
			return array('res'=>$res,'prodRes'=> $data);
		}
		else
		{
			return $res;
		}
	}

	function getCustomerData()
	{
		if($this->cPrimaryIdC == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$customer_name_filter = $this->input->get('customer_name_filter');
			$customer_email_filter = $this->input->get('customer_email_filter');
			$customer_group_name_filter = $this->input->get('customer_group_name_filter');
			$email_filter = $this->input->get('email_filter');
			$phone_filter = $this->input->get('phone_filter');
			$gender_filter = $this->input->get('gender_filter');
			$fax_filter = $this->input->get('fax_filter');

			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('(c.customer_firstname LIKE \'%'.$customer_name_filter.'%\' OR c.customer_lastname LIKE \'%'.$customer_name_filter.'%\') ');

			if(isset($customer_email_filter) && $customer_email_filter != "")
				$this->db->where('customer_emailid LIKE \'%'.$customer_email_filter.'%\' ');
				
			if(isset($customer_group_name_filter) && $customer_group_name_filter != "")
				$this->db->where('g.customer_group_name LIKE \'%'.$customer_group_name_filter.'%\' ');
				
			if(isset($email_filter) && $email_filter != "")
				$this->db->where('c.customer_emailid LIKE \'%'.$email_filter.'%\' ');
				
			if(isset($phone_filter) && $phone_filter != "")
				$this->db->where('c.customer_phoneno LIKE \''.$phone_filter.'%\' ');
				
			if(isset($gender_filter) && $gender_filter != "")
				$this->db->where('c.customer_gender LIKE \''.$gender_filter.'%\' ');
					
			if(isset($fax_filter) && $fax_filter != "")
				$this->db->where('c.customer_fax LIKE \'%'.$fax_filter.'%\' ');

			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by($this->cAutoIdC,'DESC');
				
			if( MANUFACTURER_ID != 7 )
				$this->db->where('c.manufacturer_id', MANUFACTURER_ID );

			$this->db->where('c.customer_status',0);
			
			/**
			 * Cloudwebs On 11-04-2015
			 * No more customer email approval is considered in login, checkout etc.. so commenetd. 
			 */
			//$this->db->where('c.customer_approved',1);
			
			$this->db->join('customer_group g','g.customer_group_id=c.customer_group_id','INNER');
			$res = $this->db->get("customer c");
			
			//echo "<br>".$this->db->last_query();
			return $res;
		}
		else if($this->cPrimaryIdC != '')
		{
			$this->db->where($this->cAutoIdC,$this->cPrimaryIdC);
		}
	}

	function getProductData()
	{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$product_name_filter = $this->input->get('product_name_filter');
			$product_sku_filter = $this->input->get('product_sku_filter');
			
			$this->db->select("product_id,product_name,product_sku,product_price,category_id,product_discount,product_shipping_cost,product_cod_cost,product_tax_id ");
			if(isset($product_name_filter) && $product_name_filter != "")
				$this->db->where('product_name LIKE \'%'.$product_name_filter.'%\' OR product_name LIKE \'%'.$product_name_filter.'%\' ');
				
			if(isset($product_sku_filter) && $product_sku_filter != "")
				$this->db->where('product_sku LIKE \'%'.$product_sku_filter.'%\' ');
				
			$this->db->where('product_status',0);
					
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);
			else
				$this->db->order_by("product_id",'ASC');
			
			if( MANUFACTURER_ID != 7 )
			{
				$this->db->join('product_cctld', 'product_cctld.product_id=product.product_id', 'INNER');	
				$this->db->where( 'product_cctld.manufacturer_id', MANUFACTURER_ID);
			}
				
			$res = $this->db->get("product");
			//echo "<br>".$this->db->last_query();
			return $res;
	}
			
	function saveData()
	{
		$data['customer_id'] = _de($this->input->get('custid'));
		$coupon_code = $this->input->post('coupon_code');
		if(!empty($coupon_code))
		{
			$data['coupon_id'] = getField("coupon_id","coupon","coupon_code",$coupon_code);
			if(empty($data['coupon_id']))
				unset($data['coupon_id']);
		}
		
		if($this->cPrimaryId == '' || isset($_GET['reorder']))
		{
			$res = executeQuery("SELECT MAX(order_id) as 'Max' FROM orders");
			if(is_null($res[0]['Max']) == true)
				$data['invoice_number'] = 10001;
			else
				$data['invoice_number'] = (int)$res[0]['Max']+1;
		}
		
		$data['order_total_qty'] = 0;
	
		$data_order_det['order_details_id'] = $this->input->post('order_details_id');
		$data_order_det['product_id'] = $this->input->post('hid_product_id');
		foreach($data_order_det['order_details_id'] as $k=>$ar)
		{
			$data_order_det[$k]['product_id'] = $data_order_det['product_id'][$k];
			$data_order_det[$k]['product_generate_code'] = $this->input->post('product_generate_code_'.$ar);
			$data_order_det[$k]['gift_id'] = $this->input->post('hid_gift_id_'.$ar);

			$tempArr = explode("|",$this->input->post('product_center_stone_id_'.$ar));
			$data_order_det[$k]['product_center_stone_id'] = $tempArr[0];
			$tempArr = explode("|",$this->input->post('product_side_stone1_id_'.$ar));
			$data_order_det[$k]['product_side_stone1_id'] = $tempArr[0];
			$tempArr = explode("|",$this->input->post('product_side_stone2_id_'.$ar));
			$data_order_det[$k]['product_side_stone2_id'] = $tempArr[0];
			$tempArr = explode("|",$this->input->post('product_metal_id_'.$ar));
			$data_order_det[$k]['product_metal_id'] = $tempArr[0];
			
			$data_order_det[$k]['product_engraving_text'] = $this->input->post('product_engraving_text_'.$ar);
			$data_order_det[$k]['product_engraving_font'] = $this->input->post('product_engraving_font_'.$ar);
	
			$data['order_total_qty'] += $data_order_det[$k]['order_details_product_qty'] = $this->input->post('quantity_'.$ar);
			$data_order_det[$k]['order_details_product_price'] = $this->input->post('hid_product_price_'.$ar);
			$data_order_det[$k]['order_details_product_discount'] = $this->input->post('hid_product_discount_'.$ar);
			$data_order_det[$k]['order_details_product_tax'] = $this->input->post('order_details_product_tax_'.$ar);
			$data_order_det[$k]['order_details_product_shipping_cost'] = $this->input->post('order_details_product_shipping_cost'.$ar);
			$data_order_det[$k]['order_details_product_cod_cost'] = $this->input->post('order_details_product_cod_cost_'.$ar);
		}
				
		$data['order_subtotal_amt'] = $this->input->post('order_subtotal_amt');
		$data['order_discount_amount'] = $this->input->post('order_discount_amount');
		$data['order_tax_percent'] = $this->input->post('order_tax_percent');
		$data['order_tax_amt'] = $this->input->post('order_tax_amt');
		$data['shipping_method_shipping_charge'] = $this->input->post('shipping_method_shipping_charge');
		$data['shipping_method_handling_charge'] = $this->input->post('shipping_method_handling_charge');
		$data['order_total_amt'] = $this->input->post('order_total_amt');

		$save_in_address_book_bill = $this->input->post('save_in_address_book_bill');
		if($save_in_address_book_bill == 1)
		{
			$data['customer_billing_address_id'] = $this->saveCustomerAddress($data['customer_id'],"bill");
		}
		else
		{
			$data['customer_billing_address_id'] = $this->input->post('customer_billing_address_id');
		}
		
		$save_in_address_book_shipp = $this->input->post('save_in_address_book_shipp');
		if($save_in_address_book_shipp == 1)
		{
			$data['customer_shipping_address_id'] = $this->saveCustomerAddress($data['customer_id'],"shipp");
		}
		else
		{
			$data['customer_shipping_address_id'] = $this->input->post('customer_shipping_address_id');
		}

		$data['shipping_method_id'] = $this->input->post('shipping_method_id');
		$data['payment_method_id'] = $this->input->post('payment_method_id');
		$data['customer_note'] = $this->input->post('customer_note');
		$data['ip_address'] = $this->input->ip_address();
		$data['manufacturer_id'] = MANUFACTURER_ID;
		
		$order_id =0;							
		//if primary id set then we have to make update query -->Insert/update entry in order master table
		if($this->cPrimaryId != '' && !isset($_GET['reorder']))
		{
			$this->db->set('order_modified_date', 'NOW()', FALSE);
			$this->db->where($this->cAutoId,$this->cPrimaryId)->update($this->cTableName,$data);
			$order_id = $this->cPrimaryId;			
		}
		else // insert new row
		{
			$this->db->insert($this->cTableName,$data);
			$order_id = $this->db->insert_id();
		}

		//Insert/update entry in order details table one entry for each product
		foreach($data_order_det['order_details_id'] as $k=>$ar)
		{
			$data_order_det[$k]['order_id'] = $order_id;
			if($this->cPrimaryId != '' && !isset($_GET['reorder']))
			{
				if(strpos($ar,"New") != false)		//if order_details_id New then insert else update in edit mode just id created for reference purpose
				{
					updateProductQuantity($data_order_det[$k]['product_id'],$data_order_det[$k]['order_details_product_qty']);
					$this->db->insert("order_details",$data_order_det[$k]);
				}						
				else
				{
					$pre_quantity = getField("order_details_product_qty","order_details","order_details_id",$ar);
					updateProductQuantity($data_order_det[$k]['product_id'],$data_order_det[$k]['order_details_product_qty'] - $pre_quantity);	//formula: current_quantity - previous_quantity 
					$this->db->set('order_details_modified_date', 'NOW()', FALSE);
					$this->db->where("order_details_id",$ar)->update("order_details",$data_order_det[$k]);
				}
			}
			else // insert new row
			{
				updateProductQuantity($data_order_det[$k]['product_id'],$data_order_det[$k]['order_details_product_qty']);
				$this->db->insert("order_details",$data_order_det[$k]);
			}
		}

		//Insert entry in order tracking table 
		$data_order_tracking['order_id'] = $order_id;
		$data_order_tracking['order_status_id'] =$this->input->post('order_status_id');
		$data_order_tracking['order_tracking_comment'] =  ($data['customer_note']=="")?$this->input->post('order_tracking_comment'):$data['customer_note'];
		cart_hlp_orderTracking($data_order_tracking);
												
		//send mail if specified													
		if($this->input->post('email_confirm') == "1")
		{
			$comment = "";
			if($this->input->post('append_com') == "1")
			{
				$comment = $data['customer_note'];
			}
			else if($this->cPrimaryId != '')
			{
				$comment = $data['order_tracking_comment'];
			}
			
			//insert entry in email_send_history table						
			$data_email['es_from_emails'] = getField('config_value','configuration','config_key','ADMIN_EMAIL');
			$data_email['es_to_emails'] = $this->input->post('customer_emailid');			
			$data_email['es_module_primary_id'] = $order_id;
			$data_email['es_module_name'] = "Sales Order";
			$data_email['es_subject'] = "Your order is placed";
			$data_email['es_message'] = "Your order is placed: <br><br>".$comment;
			$data_email['es_status'] = $this->input->post('order_status_id');
			sendMail($data_email['es_to_emails'],"Your order is placed","Your order is placed: <br><br>".$comment);	
			$this->db->insert("email_send_history",$data_email);
		}

		setFlashMessage('success','Order has been '.(($this->cPrimaryId != '' && !isset($_GET['reorder'])) ? 'updated': 'inserted').' successfully.');
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
			foreach($ids as $id)
			{
				$order_detailsArr = executeQuery("SELECT order_details_product_qty,product_id FROM order_details WHERE order_id=".$id."");
				if(!empty($order_detailsArr))
				{
					foreach($order_detailsArr as $k=>$ar)
					{
						updateProductQuantity($ar['product_id'],-$ar['order_details_product_qty']); //formula: pass minus(-) previous_quantity value so it will be actually added in product quantity value
					}
				}
				$this->db->where_in($this->cAutoId,$id)->update($this->cTableName,array('del_in'=>1));
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
 * @author Cloudwebs
 * @abstract fetch calculated price from product price table
 * @return $price value
*/
	function getProdPrice()
	{
		$prod_code = trim($this->input->post('prod_code'));
		$type = $this->input->post('type');
		$id = trim($this->input->post('id'));
		$ring_size_id = $this->input->post('ring_size');
		$solitaire_diamond_code = $this->input->post('solitaire_diamond_code');
		
		if(!empty($prod_code))
		{
			if($type == 'dia')
			{
				$res = fetchDiamondDetail( $prod_code );
				$res['view_var']['product_price_id'] = $res['diamond_price_id'];
				$res['view_var']['product_discounted_price'] = lp($res['dp_price']);
				$res['view_var']['product_name'] = $res['diamond_shape_name'];
				$res['view_var']['product_sku'] = $res['dp_rapnet_lot_no'];
				
				if($res)
					return array_merge( array( 'type'=>'success', 'msg'=>''), $res);
				else
					return array( 'type'=>'error', 'msg'=>'No product available with provided code.');
				
			}
			else if($type == 'prod')
			{
				$res = $this->db->query("SELECT product_price_id 
									FROM product_price 
									WHERE product_generated_code_displayable='".$prod_code."' AND product_price_status=0 ")->row_array();				
				
				if(isset($res) && is_array($res) && sizeof($res)>0)
				{
					$res = showProductsDetails($res['product_price_id'], true, false, true, '', $ring_size_id);
					if($res)
					{
						if( $res['view_var']['product_accessories'] == 'COU' || ( $res['view_var']['ring_size_region'] == 'Y' && $res['view_var']['product_accessories'] == 'SOL_COU' ) ) 
						{
							if( isset( $res['view_var']['ring_size_id_f'] ) )
							{
								$res['view_var']['ring_size_id_f'] = str_replace('onchange="ajaxCustomize(this)"', 'onchange="calcProdPrice($(\'#product_generated_code_'.$id.'\'), \''.$id.'\')"', $res['view_var']['ring_size_id_f']);
								$res['view_var']['ring_size_id_f'] = str_replace('id="ring_size_id_f"', 'id="ring_size_id_f_'.$id.'"', $res['view_var']['ring_size_id_f']);
								$res['view_var']['ring_size_id_f'] = str_replace('name="ring_size_id_f"', 'name="ring_size_id_f_'.$id.'"', $res['view_var']['ring_size_id_f']);
							}
							if( isset( $res['view_var']['ring_size_id_m'] ) )
							{
								$res['view_var']['ring_size_id_m'] = str_replace('onchange="ajaxCustomize(this)"', 'onchange="calcProdPrice($(\'#product_generated_code_'.$id.'\'), \''.$id.'\')"', $res['view_var']['ring_size_id_m']);
								$res['view_var']['ring_size_id_m'] = str_replace('id="ring_size_id_m"', 'id="ring_size_id_m_'.$id.'"', $res['view_var']['ring_size_id_m']);
								$res['view_var']['ring_size_id_m'] = str_replace('name="ring_size_id_m"', 'name="ring_size_id_m_'.$id.'"', $res['view_var']['ring_size_id_m']);
							}
							$res['view_var']['ring_size_drop_down'] =  $res['view_var']['ring_size_id_f']."&nbsp;".$res['view_var']['ring_size_id_m'] ;
						}
						
						if( $res['view_var']['product_accessories'] == 'RIN' || ( $res['view_var']['ring_size_region'] == 'Y' && $res['view_var']['product_accessories'] == 'SOL' ) )
						{
							if( isset( $res['view_var']['ring_size_drop_down'] ) )
							{
								$res['view_var']['ring_size_drop_down'] = str_replace('onchange="ajaxCustomize(this)"', 'onchange="calcProdPrice($(\'#product_generated_code_'.$id.'\'), \''.$id.'\')"', $res['view_var']['ring_size_drop_down']);
		
								$res['view_var']['ring_size_drop_down'] = str_replace('id="ring_size_id"', 'id="ring_size_id_'.$id.'"', $res['view_var']['ring_size_drop_down']);
								$res['view_var']['ring_size_drop_down'] = str_replace('name="ring_size_id"', 'name="ring_size_id_'.$id.'"', $res['view_var']['ring_size_drop_down']);
							}
						}
						
						/**
						 * qty added On 13-04-2015 applicable to some products only
						 */
						if( CLIENT == "Stationery" && hewr_isQtyInAttributeInventoryCheckWithId( $res["inventory_type_id"] ) ) //hewr_isGroceryInventoryCheckWithId( $res["inventory_type_id"] )
						{
							$res["view_var"]["qty_sel"] = form_dropdown( 'quantity_'.$res['product_price_id'],
															getProdQtyOptions( $res["product_id"], $res["product_generated_code_info"] ),
															"",' id="qty_'.$res['product_price_id'].'" onchange="addRemProductAdmin($(\'#checkbox_'.$res['product_price_id'].'\'), $(\'#checkbox_'.$res['product_price_id'].'\').val(), this.value, '.$res['product_price_id'].');" ');
						}
						
						return array_merge(array('type'=>'success', 'msg'=>''), $res);
					}
					else
					{
						return array('type'=>'error', 'msg'=>'No product available with provided code.');
					}
				}
			}
			else if($type == 'sol')
			{
				$res = $this->db->query("SELECT product_price_id 
									FROM product_price 
									WHERE product_generated_code='".$prod_code."' AND product_price_status=0 ")->row_array();				
				
				if(isset($res) && is_array($res) && sizeof($res)>0)
				{
					
					$res = prodSolInfo( $res['product_price_id'], true, false, true, '', $ring_size_id, '_mount', $solitaire_diamond_code, $id);
					if( !empty($res) )
					{
						return array_merge(array('type'=>'success', 'msg'=>''), $res);
					}
					else
					{
						return array('type'=>'error', 'msg'=>'No product available with provided code.');
					}
				}
			}
			else if($type == 'cz')
			{ return array('type'=>'error', 'msg'=>'CZ not supported yet.'); }
			else
			{
				return array('type'=>'error', 'msg'=>'No product available with provided code.');
			}
		}
		else
		{
			return array('type'=>'error', 'msg'=>'Specify product code properly.');
		}
		
		return array('type'=>'error', 'msg'=>'No product available with provided code.');
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
		$stone_totalA =  explode("|",$this->input->post('stone_total'));
		$weightA =  explode("|",$this->input->post('weight'));
		$idA =  explode("|",$this->input->post('id'));
		$mt_weight = $this->input->post('mt_weight');
		$mt_id =  $this->input->post('mt_id');
		$price = 0.0;

		foreach($idA as $k=>$ar)
		{
			$res = $this->db->query('SELECT dp_calculated_cost FROM diamond_price WHERE diamond_price_id='.(int)$ar.'');
			$row = $res->row_array();
	
			if(is_array($row) && sizeof($row))
				$price +=  round(((float)$weightA[$k] * $row['dp_calculated_cost']) * (int)$stone_totalA[$k],2); 
		}

		if(!empty($mt_id))
		{
			$res = $this->db->query('SELECT mp_price_difference FROM metal_price WHERE metal_price_id='.$mt_id.'');
			$row = $res->row_array();
			$price +=  round($mt_weight * $row['mp_price_difference'],2); 
		}

		return json_encode($price);
	}

/**
 * @author Cloudwebs
 * fetch products selected by user to add in order
 */
	function getSelectedProd($cust_order_id)
	{
		return cart_hlp_getCheckOutData( false );	
	}

/*
 * @author   Cloudwebs
 * @abstract functoin will insert or update order
 */
	function placeOrder($coupon_id, 
						$order_total_qty,  
						$order_subtotal_amt, $order_discount_amount, $order_total_amt, 
						$customer_shipping_address_id, $customer_billing_address_id, 
						$shipping_method_id, $transaction_id, $payment_method_id, 
						$account_number, $payment_response, $customer_note, $order_is_gift_wrap)
	{
		$invoice_number = getInvoiceNum();
		$ip_address = $this->input->ip_address();

		return placeOrder(0, $this->cPrimaryIdC, $coupon_id, $invoice_number, $order_total_qty, $order_subtotal_amt, $order_discount_amount, 0, 0, 
					  0, 0, $order_total_amt, $customer_shipping_address_id, $customer_billing_address_id, $shipping_method_id, 
					 $transaction_id, $payment_method_id, $account_number, $payment_response, $customer_note, $order_is_gift_wrap, $ip_address, 0);
	}

/*
 * @abstract fetch state as per country id passed
 * 
 */	
	function getState()
	{
		$countryid = $this->input->post('country_id');
		$name = $this->input->post('name');
		echo loadStateDropdown($name,$countryid);
	}

/*
 * @abstract fetch shipping cost
 * 
 */
	function fetchShippingCost()
	{
		$resArr = array();
		$shipp_id = $this->input->post('shipp_id');
		$res = executeQuery("SELECT shipping_method_free_shipping,shipping_method_handling_charges FROM shipping_method WHERE shipping_method_id=".$shipp_id."");
		if(!empty($res))
		{
			$resArr['type'] = "success";
			$resArr['shipp_charge'] = (int)$res[0]['shipping_method_free_shipping'];
			$resArr['handling_charge'] = (int)$res[0]['shipping_method_handling_charges'];
		}
		else
		{
			$resArr['type'] = "error";
			$resArr['msg'] = "Sorry! Specified shipping method not available";
		}
		echo json_encode($resArr);
	}

/*
 * @abstract save/update customer address
 * 
 */
	function saveUpdCustomerAddress($cust_id, $type)
	{
		$data = array();
		$data['customer_id'] = $cust_id;
		$data['customer_address_firstname'] = $this->input->post('customer_address_firstname_'.$type);
		$data['customer_address_lastname'] = $this->input->post('customer_address_lastname_'.$type);
		$data['customer_address_address'] = $this->input->post('customer_address_address_'.$type);
		$data['customer_address_company'] = $this->input->post('customer_address_company_'.$type);
		$data['customer_address_zipcode'] = getField('pincode_id', 'pincode', 'pincode', $this->input->post('pincode_'.$type));
		
		$this->db->insert("customer_address",$data);
		
		return $this->db->insert_id();
	}

/*
 * @abstract update order tracking status
 * 
 */
	function updateOrderStatus( $is_all, $is_email, $data="" )
	{
		$resArr = array();
		if( empty($data) )
		{
			$data['order_id'] = _de($this->input->post('order_id'));
			$data['order_details_id'] = $this->input->post('order_details_id');
			$data['order_status_id'] = $this->input->post('sta_id');
			$data['order_tracking_number'] = $this->input->post('track_no');
			$data['order_tracking_comment'] = $this->input->post('track_com');
		}
		
		if( (int) $is_all == 1 )
		{
			//AND order_details_is_returned=0; On 02-05-2015 condition removed
			$resDet = $this->db->query('SELECT order_details_id, order_details_product_qty, order_details_return_quantity FROM order_details WHERE order_id='.$data['order_id'].'  ')
							   ->result_array();	
			if(isset($resDet) && is_array($resDet) && sizeof($resDet)>0)
			{
				foreach($resDet as $k=>$ar)
				{
					/**
					 * added On 02-05-2015.
					 * 
					 * Allow status update only if order product has valid qty so is in processing, or 
					 * otherwise if action is to cancel order.
					 */
					if( ( $ar["order_details_product_qty"] - $ar["order_details_return_quantity"] ) > 0 || $data['order_status_id'] == 3 )
					{
						$data['order_details_id'] = $ar['order_details_id'];
						cart_hlp_orderTracking($data); 
					}
				}
			}
		}
		else
		{
			cart_hlp_orderTracking($data);
		}
		
		if((int)$is_email == 1)
		{
			$resSta = $this->db->query('SELECT order_status_key, order_status_msg FROM order_status WHERE order_status_id='.$data['order_status_id'].' ')
							   ->row_array();
			$data = orderEmail( $data['order_id'], @$resSta['order_status_key'], @$resSta['order_status_msg'] , $data['order_tracking_number']);
		}

		return array('type'=>'success', 'msg'=>'Status updated successfully.');
	}
	
/*
 * @abstract removes order detail entry
 * 
 */
	function deleteOrderDetail()
	{
		$product_idArr = explode("|",$this->input->post('product_id'));
		$order_details_idArr = explode("|",$this->input->post('order_details_id'));

		foreach($order_details_idArr as $k=>$ar)
		{
			$pre_quantity = getField("order_details_product_qty","order_details","order_details_id",$ar);
			updateProductQuantity($product_idArr[$k], - $pre_quantity);	//formula: pass minus(-) previous_quantity value so it will be actually added in product quantity value
			$this->db->where("order_details_id",$order_details_idArr[$k])->delete("order_details");
		}

		echo json_encode(array('type'=>'success','msg'=>sizeof($order_details_idArr).' Product removed successfully'));
	}
	
/*
 *  @abstract function will display detailed product selection for particular order  
 */
	function popupProductDetail()
	{
		$id = _de($this->input->get('id'));
		$res = $this->db->query("SELECT d.*,p.product_name,p.product_sku,g.gift_name FROM order_details d INNER JOIN product p 
								 ON p.product_id=d.product_id LEFT JOIN gift g ON g.gift_id=d.gift_id WHERE order_details_id=".$id."");

		$detail = $res->row_array();		
		unset($detail['gift_id']);

		if(!empty($detail['product_center_stone_id']))
		{
			$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_center_stone c 
									 ON c.category_id=p.diamond_price_id WHERE category_id=".$detail['product_center_stone_id']."  ");
			
			$res = $res->row_array();						 
			$detail['center_stone_name'] = @$res['diamond_price_name'];
		}
		unset($detail['product_center_stone_id']);

		if(!empty($detail['product_side_stone1_id']))
		{
			$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_side_stone1 c 
									 ON c.category_id=p.diamond_price_id WHERE category_id=".$detail['product_side_stone1_id']." ");
			
			$res = $res->row_array();						 
			$detail['side_stone_1_name'] = @$res['diamond_price_name'];
		}
		unset($detail['product_side_stone1_id']);

		if(!empty($detail['product_side_stone2_id']))
		{
			$res = $this->db->query("SELECT diamond_price_name FROM diamond_price p INNER JOIN product_side_stone2 c 
									 ON c.category_id=p.diamond_price_id WHERE category_id=".$detail['product_side_stone2_id']." ");
			
			$res = $res->row_array();						 
			$detail['side_stone_2_name'] = @$res['diamond_price_name'];
		}
		unset($detail['product_side_stone2_id']);

		if(!empty($detail['product_metal_id']))
		{
			$res = $this->db->query("SELECT CONCAT(metal_type_name,' ',metal_purity_name,' ',metal_color_name) as 'metal_price_name' FROM metal_price p INNER JOIN product_metal m 
									 ON m.category_id=p.metal_price_id INNER JOIN metal_type t 
									 ON t.metal_type_id=p.metal_type_id INNER JOIN metal_purity u 
									 ON u.metal_purity_id=p.metal_purity_id INNER JOIN metal_color c 
									 ON c.metal_color_id=p.metal_color_id WHERE category_id=".$detail['product_metal_id']." ");
			$res = $res->row_array();						 
			$detail['metal_name'] = @$res['metal_price_name'];
		}
		unset($detail['product_metal_id']);
		unset($detail['product_id']);
		
		return $detail;
	}

/*
 * @author Cloudwebs
 * @abstract function will fetch data to display in print invoice
 */
	function getPrintInvoceData()
	{
		$res = $this->db->query("SELECT * FROM orders o LEFT JOIN order_transaction ot
		 						ON ot.order_id=o.order_id WHERE o.order_id=".$this->cPrimaryId." ORDER BY order_transaction_id LIMIT 0,1")->row_array();
		
		if( $res['payment_method_id']==5 )
		{
			if($res['payment_mode']=='CC')
			{
				$res['payment_mode'] = 'Credit Card';
			}
			else if($res['payment_mode']=='DC')
			{
				$res['payment_mode'] = 'Debit Card';
			}
			else if($res['payment_mode']=='NB')
			{
				$res['payment_mode'] = 'Net Banking';
			}
		}
		else if( $res['payment_method_id']==4 )
		{
			$res['payment_mode'] = 'Cash On Delivery';
		}
		else if( $res['payment_method_id']==1 )
		{
			$res['payment_mode'] = 'PayPal';
		}
		
		$res['customer_emailid'] = getField("customer_emailid", "customer", "customer_id", $res['customer_id']);	
		$res['shipp_add'] = getAddress($res['customer_shipping_address_id']);
		$res['bill_add'] = getAddress($res['customer_billing_address_id']);
		
		//$res['resOrdDet'] = fetchOrdDetFromDatabase($res['order_id']);
		$res['resOrdDet'] = $this->db->query("SELECT od.product_id, p.product_sku, pp.product_generated_code_info, pp.product_generated_code_displayable, od.product_generate_code, od.order_details_id, od.order_details_ring_size, od.product_type, 
											order_details_product_qty, order_details_amt, CONCAT(product_final_weight, ' ', '(gm)') 
											as product_final_weight, pp.product_price_id, p.product_accessories, p.product_name, p.product_alias, p.category_id, p.product_image, p.product_angle_in, pp.product_generated_code_displayable,
											dp.dp_rapnet_lot_no, dp.dp_final_price as dp_price, ds.diamond_shape_name, ds.diamond_shape_icon
											FROM order_details od 
											INNER JOIN product_price pp ON pp.product_generated_code=od.product_generate_code 
											INNER JOIN product p ON p.product_id=pp.product_id
											LEFT JOIN diamond_price dp ON dp.diamond_price_id = od.diamond_price_id 
											LEFT JOIN diamond_shape ds ON ds.diamond_shape_id = dp.diamond_shape_id
											WHERE od.order_id=".$this->cPrimaryId." ")->result_array();
		return $res;
	}

/**
 * @author Cloudwebs
 * @abstract function will update final weight of ordered products i.e. the weight of product when it was actually manufactured
 * 
 */
	function updateFinalWeight()
	{
		$resArr = array();

		$order_details_id = $this->input->post('order_details_id');
		$data['product_final_weight'] = $this->input->post('final_weight');

		$this->db->where('order_details_id', $order_details_id)->update("order_details",$data);

		echo json_encode( array('type'=>'success', 'msg'=>'Status updated successfully.') );
	}
	
/*	
 * function will update order item of specific order: it is post order administraion to update orders
 */
	function updateOrderItemAdmin()
	{
		$res = array();
		$data = $this->input->post();
		
		

	}

/**
 * function added On 18-05-2015
 */	
	function releaseAffiliateReferrelBonus($order_id)
	{
		aff_hlp_signupAffiliateCreditPostOrder($order_id); 
		
		setFlashMessage("success", "Referrel bonus credited to affiliate member's account.");
		redirect( 'admin/sales_order' );
	}	
	
}
