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
			$status_filter = $this->input->get('order_status_id');
			$fromamt_filter = $this->input->get('fromamt_filter');
			$toamt_filter = $this->input->get('toamt_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate = $this->input->get('toDate');
			
			if(isset($invoice_number_filter) && $invoice_number_filter != "")
				$this->db->where('invoice_number LIKE \''.$invoice_number_filter.'%\' ');

			if(isset($customer_name_filter) && $customer_name_filter != "")
				$this->db->where('(customer_firstname LIKE \'%'.$customer_name_filter.'%\' OR customer_lastname LIKE \'%'.$customer_name_filter.'%\' )');
			
			if(isset($customer_email_filter) && $customer_email_filter != "")
				$this->db->where('customer_emailid LIKE \'%'.$customer_email_filter.'%\' ');
				
			if(isset($payment_method_filter) && $payment_method_filter != "")
				$this->db->where('payment_method_name LIKE \'%'.$payment_method_filter.'%\' ');
					
			if(isset($status_filter) && $status_filter != "")
				$this->db->where('order_tracking.order_status_id',$status_filter);
		
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
		}
		else if($this->cPrimaryId != '')
			$this->db->where("orders.".$this->cAutoId,$this->cPrimaryId);
	

		$this->db->select($this->cTableName.'.*, customer.customer_firstname,customer.customer_lastname, customer.customer_emailid, g.customer_group_name, payment_method_name, order_tracking.order_status_id, order_status.order_status_name');
		$this->db->join('customer','customer.customer_id='.$this->cTableName.'.customer_id','INNER');
		$this->db->join('customer_group g','g.customer_group_id=customer.customer_group_id','INNER');
		$this->db->join('payment_method','payment_method.payment_method_id='.$this->cTableName.'.payment_method_id','INNER');
		$this->db->join('order_tracking','order_tracking.order_id='.$this->cTableName.'.order_id','INNER');
		$this->db->join('order_status','order_status.order_status_id=order_tracking.order_status_id','INNER');
		$this->db->where("del_in","0");
		$this->db->group_by("orders.order_id");
		$this->db->order_by("order_tracking.order_tracking_id",'DESC');
		$res = $this->db->get($this->cTableName);
		//echo $this->db->last_query();

		if($this->cPrimaryId != '')
		{
			$this->db->select("order_details_id, d.order_id, d.product_id,product_generate_code , product_name as 'hid_product_name', product_sku as 'hid_product_sku', gift_id, 
							   product_engraving_text, product_engraving_font, order_details_product_qty as 'quantity', order_details_product_price as 'hid_product_price', 
							   order_details_product_discount as 'hid_product_discount',order_details_product_tax, order_details_product_shipping_cost as 'hid_product_shipping_cost',order_details_product_cod_cost as 'hid_product_cod_cost' ");
			$this->db->join('product p','p.product_id=d.product_id','INNER');
			$this->db->where("d.order_id",$this->cPrimaryId);
			$prodRes = $this->db->get("order_details d");
			return array('res'=>$res,'prodRes'=>$prodRes);
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

			$this->db->where('c.customer_status',0);
			$this->db->where('c.customer_approved',1);
			$this->db->join('customer_group g','g.customer_group_id=c.customer_group_id','INNER');
			$res = $this->db->get("customer c");
			//echo $this->db->last_query();
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

			$res = $this->db->get("product");
			//echo $this->db->last_query();
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
		$this->db->insert("order_tracking",$data_order_tracking);
												
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
		$prod_id = trim($this->input->post('prod_id'));
		if(!empty($prod_code))
		{
			$res = $this->db->query("SELECT product_discounted_price FROM product_price WHERE product_id=".(int)$prod_id." AND product_generated_code='".$prod_code."'")->row_array();				
			
			if(isset($res) && is_array($res) && sizeof($res)>0)
			{
				return array('type'=>'success', 'msg'=>'', 'product_discounted_price' => $res['product_discounted_price']);	
			}
			else
			{
				return array('type'=>'error', 'msg'=>'No product available with provided code.');
			}
		}
		else
		{
			return array('type'=>'error', 'msg'=>'Specify product code properly.');
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

/*
 * @author Cloudwebs
 * @abstract fetch products selected by user to add in order
 * 
*/
	function getSelectedProd()
	{
		$prod_array = array();
		$SelA = $this->input->post('selected');
		$res = executeQuery("SELECT MAX(order_details_id) as 'Max' from order_details");
		if(is_null($res[0]['Max']) == true)
			$order_details_id = 0;
		else
			$order_details_id = $res[0]['Max'];
		
		if(is_array($SelA) && sizeof($SelA)>0)
			foreach($SelA as $k=>$ar)
			{
				$order_details_id++;
				$prod_array[$ar]['order_details_id'] = $order_details_id."-New";
				$prod_array[$ar]['product_id'] = $ar;
				$prod_array[$ar]['gift_id'] = $this->input->post('gift_id_'.$ar);
				$prod_array[$ar]['product_center_stone_id'] = $this->input->post('product_center_stone_id_'.$ar);
				$prod_array[$ar]['product_side_stone1_id'] = $this->input->post('product_side_stone1_id_'.$ar);
				$prod_array[$ar]['product_side_stone2_id'] = $this->input->post('product_side_stone2_id_'.$ar);
				$prod_array[$ar]['product_metal_id'] = $this->input->post('product_metal_id_'.$ar);
				
				$prod_array[$ar]['product_generate_code'] = generateProductCode($prod_array[$ar]['product_id'],
				substr($prod_array[$ar]['product_metal_id'],0, strpos($prod_array[$ar]['product_metal_id'],"|")),
				substr($prod_array[$ar]['product_center_stone_id'],0, strpos($prod_array[$ar]['product_center_stone_id'],"|")),
				substr($prod_array[$ar]['product_side_stone1_id'],0, strpos($prod_array[$ar]['product_side_stone1_id'],"|")),
				substr($prod_array[$ar]['product_side_stone2_id'],0, strpos($prod_array[$ar]['product_side_stone2_id'],"|")));
				
				$prod_array[$ar]['product_engraving_text'] = $this->input->post('product_engraving_text_'.$ar);
				$prod_array[$ar]['product_engraving_font'] = $this->input->post('product_engraving_font_'.$ar);
				$prod_array[$ar]['quantity'] = $this->input->post('quantity_'.$ar);
				$prod_array[$ar]['hid_product_price'] = $this->input->post('hid_product_price_'.$ar);
				$prod_array[$ar]['hid_product_discount'] = $this->input->post('hid_product_discount_'.$ar);
				$prod_array[$ar]['order_details_product_tax'] = $this->input->post('order_details_product_tax_'.$ar);
				$prod_array[$ar]['hid_product_shipping_cost'] = $this->input->post('hid_product_shipping_cost_'.$ar);
				$prod_array[$ar]['hid_product_cod_cost'] = $this->input->post('hid_product_cod_cost_'.$ar);
				$prod_array[$ar]['hid_product_name'] = $this->input->post('hid_product_name_'.$ar);
				$prod_array[$ar]['hid_product_sku'] = $this->input->post('hid_product_sku_'.$ar);
			}
		return $prod_array;
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
 * @abstract fetch coupon discount
 * 
*/
	function getCouponDiscount()
	{
		$resArr = array();
		$coup_code = $this->input->post('coup_code');
		$res = executeQuery("SELECT coupon_id,coupon_discount_amt, coupon_type FROM coupon WHERE coupon_code='".$coup_code."'");
		if(!empty($res))
		{
			$resArr['type'] = "success";
			$resArr['coup_disc'] = $res[0]['coupon_discount_amt'];
			$resArr['disc_type'] = $res[0]['coupon_type'];
			$resArr['coupon_id'] = $res[0]['coupon_id'];
		}
		else
		{
			$resArr['type'] = "error";
			$resArr['msg'] = "Sorry! Specified coupon code not available";
		}
		echo json_encode($resArr);
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
 * @abstract save customer address
 * 
*/
	function saveCustomerAddress($cust_id,$type)
	{
		$data = array();
		$data['customer_id'] = $cust_id;
		$data['customer_address_firstname'] = $this->input->post('customer_address_firstname_'.$type);
		$data['customer_address_lastname'] = $this->input->post('customer_address_lastname_'.$type);
		$data['customer_address_address'] = $this->input->post('customer_address_address_'.$type);
		$data['customer_address_company'] = $this->input->post('customer_address_company_'.$type);
		$data['customer_address_city'] = $this->input->post('customer_address_city_'.$type);
		$data['customer_address_zipcode'] = $this->input->post('customer_address_zipcode_'.$type);
		$data['country_id'] = $this->input->post('country_id_'.$type);
		$data['customer_address_state_id'] = $this->input->post('customer_address_state_id_'.$type);
		
		$this->db->insert("customer_address",$data);
		
		return $this->db->insert_id();
	}

/*
 * @abstract update order tracking status
 * 
*/
	function updateOrderStatus()
	{
		$resArr = array();

		$data['order_id'] = _de($this->input->post('item_id'));
		$data['order_status_id'] = $this->input->post('order_status_id');
		$data['order_tracking_comment'] = $this->input->post('order_tracking_comment');
		$this->db->insert("order_tracking",$data);

		$customer_id = _de($this->input->post('customer_id'));
		$email_confirm = $this->input->post('email_confirm');
		if((int)$email_confirm==1)
		{																
			$data_email['es_from_emails'] = getField('config_value','configuration','config_key','ADMIN_EMAIL');
			$data_email['es_to_emails'] = getField('customer_emailid','customer','customer_id',$customer_id);
			$data_email['es_module_primary_id'] = $data['order_id'];
			$data_email['es_module_name'] = "Sales Order";
			$data_email['es_subject'] = "Dear Customer this email contains information about order placed at".baseDomain()."";
			$data_email['es_message'] = "Dear Customer: <br><br>".$data['order_tracking_comment'];
			$data_email['es_status'] = $data['order_status_id'];
			sendMail($data_email['es_to_emails'],"Your order is placed","Your order is placed: <br><br>".$data['order_tracking_comment']);	
			$this->db->insert("email_send_history",$data_email);
		}

		$this->db->select("order_tracking_comment,order_tracking_created_date,order_status_name");
		$this->db->join("order_status s","s.order_status_id=t.order_status_id","INNER");
		$this->db->where("order_id",$data['order_id']);
		$this->db->order_by("order_tracking_id",'DESC');
		$resArr = $this->db->get("order_tracking t");
		$resArr = $resArr->result_array();
		
		$html="<tbody>";
		if(!empty($resArr))
			foreach($resArr as $k=>$ar)
			{
				$html .='<tr>
          				<td>'.$ar['order_tracking_created_date'].'&nbsp;&nbsp;|&nbsp;&nbsp;'.$ar['order_status_name'].'</td>
						</tr>';				
			}
		
		$html .="</tbody>";
		echo json_encode($html);
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
		$res = $this->db->query("SELECT * FROM orders o INNER JOIN order_transaction ot
		 						ON ot.order_id=o.order_id WHERE o.order_id=".$this->cPrimaryId." ORDER BY order_transaction_id LIMIT 0,1")->row_array();
		
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
		else if($res['payment_mode']=='COD')
		{
			$res['payment_mode'] = 'Cash On Delivery';
		}
		
		$res['customer_emailid'] = getField("customer_emailid", "customer", "customer_id", $res['customer_id']);	
		$res['shipp_add'] = getAddress($res['customer_shipping_address_id']);
		$res['bill_add'] = getAddress($res['customer_billing_address_id']);
		
		$res['resOrdDet'] = $this->db->query("SELECT product_id, product_generate_code, order_details_product_qty, order_details_amt, product_final_weight 
										FROM order_details WHERE order_id=".$this->cPrimaryId."")->result_array();
										
		return $res;
	}

}