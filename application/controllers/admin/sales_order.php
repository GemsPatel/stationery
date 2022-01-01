<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sales_order extends CI_Controller 
{
	var $is_ajax = false;
	var $cAutoId = 'order_id';
	var $cPrimaryId = '';
	var $cTable = 'orders';
	var $cAutoIdC = 'customer_id';
	var $cPrimaryIdC = '';
	var $cTableC = 'customer';
	var $controller = 'sales_order';
	var $is_post = false;
	var $prodAmt = 0;						// to be set in items_ordered view and used in sales_order_form view
	var $proArr = array();				// prod price is stored if tax rule not specified for product in items_ordered view then general tax rule applied in sales_order_form view
	var $taxTot = 0.0;
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	var $adm_cartArr = '';
	var $cust_order_id = ''; 				//Note*(cust_order_id): in admin cart customer id is used for mapping for new order while order id is used for order in edit mode

	//parent constructor will load model inside it
	function sales_order()
	{
		parent::__construct(); 
		
		$this->load->model( 'admin/mdl_sales_order', 'sao' ); 
		$this->sao->cTableName = $this->cTable; 
		$this->sao->cAutoId = $this->cAutoId; 
		$this->sao->cTableNameC = $this->cTableC; 
		$this->sao->cAutoIdC = $this->cAutoIdC; 
		$this->is_ajax = $this->input->is_ajax_request(); 
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '') 
			$this->cPrimaryId  = $this->sao->cPrimaryId = _de( $this->security->xss_clean( $_REQUEST['item_id'] ) ); 

		if($this->input->get('custid') != '' || $this->input->post('custid') != '') 
			$this->cPrimaryIdC  = $this->sao->cPrimaryIdC = _de($this->security->xss_clean($_REQUEST['custid'])); 
			 
		$this->chk_permission();	

		//set adm_cartArr from session
		if($this->session->userdata('adm_cartArr') !== FALSE)
		{
			$this->adm_cartArr = $this->sao->adm_cartArr = $this->session->userdata('adm_cartArr');
		}
		
		/**
		 * in admin cart customer id is used for mapping for new order while order id is used for order in edit mode
		 */
		$this->cust_order_id = cart_hlp_getCustOrdId( false );
	}
	
/**
+----------------------------------------------------+
	check permission for user
+----------------------------------------------------+
*/
	function chk_permission()
	{
		$per =  fetchPermission($this->controller);
		if(!empty($per))
		{
			$this->per_add = @$per['permission_add'];		
			$this->per_edit = @$per['permission_edit'];		
			$this->per_delete = @$per['permission_delete'];		
			$this->per_view = @$per['permission_view'];		
		}
		else 
		{
			showPermissionDenied();
		}
	}
	
/*
+-----------------------------------------+
	This function will remap url for admin,
	and remove unnecesary name from url.
	For example : if we don't want index
	strgin in url while listin item, we can 
	remove it using this function
+-----------------------------------------+
*/	
	function _remap($method,$params)
	{
		if(method_exists($this,$method))
			return call_user_func_array(array($this, $method), $params);
		else
		{
			$para[0] = $method;
			
			if(count($params) > 0)
				$para = array_merge($para,$params);
			
			//here we are going to call out custom function for load specific menu.
			call_user_func_array(array($this,'index'),$para);
		}
	}

	/**
	 * Admin_CMS_checkout, refer UML::he_admin_cms_checkout_flow for more information
	 */
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Sales Order', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		/**
		 * Admin_CMS_checkout Step 1 
		 */
		if((isset($_GET['cust']) && $_GET['cust']=='list') || isset($_GET['email_filter']))
		{
			/**
			 * UML::he_order_flow->flushAdmCartSession as per stated in flushAdmCartSession
			 */
			cart_hlp_flushAdmCartSession();
				
			$num = $this->sao->getCustomerData();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			
			$data['start'] = $start;
			$data['total_records'] = $num->num_rows();
			$data['per_page_drop'] = per_page_drop();
			$data['srt'] = $this->input->get('s'); // sort order
			$data['field'] = $this->input->get('f'); // sort field name
			$data['customer_name_filter'] = $this->input->get('customer_name_filter'); // field name of customer_name
			$data['customer_email_filter'] = $this->input->get('customer_email_filter'); // field name of customer_emailid
			$data['customer_group_name_filter'] = $this->input->get('customer_group_name_filter'); 
			$data['email_filter'] = $this->input->get('email_filter'); 
			$data['phone_filter'] = $this->input->get('phone_filter'); 
			$data['gender_filter'] = $this->input->get('gender_filter'); 
			$data['fax_filter'] = $this->input->get('fax_filter'); 
			
			if($this->is_ajax)
				$this->load->view('admin/'.$this->controller.'/customer_ajax_html_data',$data); // this view loaded on ajax call
			else
			{
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_customer';
				$this->load->view('admin/layout',$data);
			}
		}
		/**
		 * Admin_CMS_checkout Step 2
		 */
		else if((isset($_GET['prod']) && $_GET['prod']=='list') || isset($_GET['product_name_filter']))
		{
			if($this->is_ajax)
				$this->load->view('admin/'.$this->controller.'/product_ajax_html_data',$data); // this view loaded on ajax call
			else
			{
				$data = $this->getSelectedProd( $this->cPrimaryIdC );
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_product';
				$this->load->view('admin/layout',$data);
			}
		}
		else
		{
			$num = $this->sao->getData();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			
			$data['start'] = $start;
			$data['total_records'] = $num->num_rows();
			$data['per_page_drop'] = per_page_drop();
			$data['srt'] = $this->input->get('s'); // sort order
			$data['field'] = $this->input->get('f'); // sort field name
			$data['invoice_number_filter'] = $this->input->get('invoice_number_filter'); 
			$data['customer_name_filter'] = $this->input->get('customer_name_filter'); // field name of customer_name
			$data['customer_email_filter'] = $this->input->get('customer_email_filter'); // field name of customer_emailid
			$data['payment_method_filter'] = $this->input->get('payment_method_filter'); 
			$data['status_filter'] = $this->input->get('order_status_id'); 
			$data['fromamt_filter'] = $this->input->get('fromamt_filter'); 
			$data['toamt_filter'] = $this->input->get('toamt_filter'); 
			$data['fromDate'] = $this->input->get('fromDate'); 
			$data['toDate'] = $this->input->get('toDate'); 
			
			if($this->is_ajax)
				$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
			else
			{
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
				$this->load->view('admin/layout',$data);
			}
		}
	}

	/**
	 * Function will save data, all parameters will be in post method.
	 */
	function salesOrderForm()
	{
		if($this->cPrimaryId != '')
		{
			if($this->per_edit != 0)
			{
				setFlashMessage('error',getErrorMessageFromCode('01008'));
				showPermissionDenied();
			}
		}
		else if($this->per_add != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01007'));
			showPermissionDenied();
		}
	
		$data = $this->getSelectedProd( $this->cust_order_id );
		if($this->input->get('edit') == 'true')
		{
			if( $this->input->get('act') == 'view' )
			{
				$dt =  array();
				if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
				{
					$dtArr = $this->sao->getData();
					$dt = $dtArr['res']->row_array();
					$dt['cart_prod'] = $dtArr['prodRes']['data_order'];
				}
				
				$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$dt);
			}
			else if( $this->input->get('act') == 'upd_sta' )
			{
				$dt =  array();
				if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
				{
					$dtArr = $this->sao->getData();
					$dt = $dtArr['res']->row_array();
					$dt['cart_prod'] = $dtArr['prodRes']['data_order'];
				}
					
				$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$dt);
			}
			else if( $this->input->get('act') == 'ship' )
			{
				$this->shipOrder();
			}
			else if( $this->input->get('act') == 'edit' )
			{
				$this->editOrder(); 
			}
			else if( $this->input->get('act') == 'cancel' )
			{
				$this->cancelOrder();
			}
			else if( $this->input->get('act') == 'send_mail' )
			{
				$this->sendMail();
			}
			else if( $this->input->get('act') == 'rel_ref_bns' )
			{
				$this->sao->releaseAffiliateReferrelBonus( $this->cPrimaryId ); 
			}
				
		}
		else
		{
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
			$this->load->view('admin/layout',$data);
		}
	}
	
/********************************** Admin_CMS_checkout Step 2 *************************************/	
	
/**
 * adds product tr row 
 */
	function addProductTr() 
	{
		$res = array();
		$res["type"] = "success";
		$res["msg"] = "";
		
		$dt['k'] = (int)$this->input->post("prod_tr_cnt");
		$dt["is_new_row"] = TRUE;
		$res["html"] = $this->load->view('admin/'.$this->controller.'/product_row', $dt, TRUE);
		
		echo json_encode($res);
	}	
	
/**
 * @abstract add product in cart Admin session
 */
	function add()
	{
		if($this->is_ajax)
		{
			$product_price_id = $this->input->post('pid');
			$order_details_product_qty = $this->input->post('qty');
			$ring_size = (int)$this->input->post('ring_size');	//ring size applicable to only ring products
			$type = $this->input->post('type');

			if( (int)$product_price_id != 0 )
			{
				updCartDatabase($product_price_id, $order_details_product_qty, false, false, $this->adm_cartArr, $this->cust_order_id, $ring_size, 'adm_', $type);
				echo json_encode(array('type'=>'success','msg'=>'Success'));			
			}
		}
		else
		{
			redirect( site_url('admin/'.$this->controller) );	
		}
	}
	
/**
 * @author Cloudwebs
 * @abstract remove product from cart
 */
	function removeProduct()
	{
		$product_price_id = $this->input->post('pid');
		if(isset($this->adm_cartArr[$this->cust_order_id][$product_price_id]))
		{
			unset($this->adm_cartArr[$this->cust_order_id][$product_price_id]);
			$this->session->set_userdata(array('adm_cartArr'=>$this->adm_cartArr));
	
			echo json_encode(array('type'=>'success', 'msg'=>'Product removed from admin cart.'));
		}
		else
		{
			echo json_encode(array('type'=>'warning', 'msg'=>'Product not found.'));
		}
	}
	
	/**
	 * @author Cloudwebs
	 * @abstract fetch calculated price for diamonds and metal
	 * @return $price value
	 */
	function getDiaMetPrice()
	{
		echo $this->sao->getDiaMetPrice();
	}
	
	/**
	 * @author Cloudwebs
	 * @abstract fetch calculated price from product price table
	 * @return $price value
	 */
	function getProdPrice()
	{
		echo json_encode($this->sao->getProdPrice());
	}
	
	/**
	 * @author Cloudwebs
	 * @abstract fetch products selected by user to add in order
	 *
	 */
	function getSelectedProd($cust_order_id)
	{
		return $this->sao->getSelectedProd($cust_order_id);
	}
	
/********************************** Admin_CMS_checkout Step 2 end *************************************/	
	
	/**
	 * Admin_CMS_checkout Step 3
	 * 
	 * function will apply coupon dixount to grand total only if coupon available and valid
	 */
	function applyCoupon()
	{
		$resArr = array();
		
		//unset coupon session first
		$this->session->unset_userdata('adm_coupon_id');
	
		$coupon_code = $this->input->post('coupon_code');
	
		$resArr = getCartData($this->adm_cartArr, $this->cust_order_id, false, false, true, true);
	
		if($resArr['type']=='success')
		{
			$resArr = applyCouponCode($resArr['order_subtotal_amt'], $resArr['cartArr'], $this->cust_order_id, 0, $coupon_code, true, 'adm_');
			if($resArr['type']=='success')
			{
				$resArr['grand_total'] = lp($resArr['order_total_amt']);	//append prefix of currency
			}
		}
	
		echo json_encode($resArr);
	}
	
	/**
	 * Admin_CMS_checkout Step 4
	 *
	 * @author Cloudwebs
	 * function will apply billing or shipping address to order
	 */
	function applyAddresses()
	{
		$resArr = array();
		$data = $this->input->post();
		$customer_billing_address_id = 0;
		$customer_shipping_address_id = 0;
	
		//billing address
		if( isset($data['save_in_address_book_bill']) && (int)$data['save_in_address_book_bill']==1 )
		{
			$customer_billing_address_id = $this->sao->saveUpdCustomerAddress( _de($data['customer_id']) , 'bill');
		}
		else
		{
			$customer_billing_address_id = $data['customer_billing_address_id'];
		}
	
		//shipping address
		if( isset($data['same_as_address']) && (int)$data['same_as_address']==1 )
		{
			$customer_shipping_address_id = $customer_billing_address_id;
		}
		else if( isset($data['save_in_address_book_shipp']) && (int)$data['save_in_address_book_shipp']==1 )
		{
			$customer_shipping_address_id = $this->sao->saveUpdCustomerAddress( _de($data['customer_id']) , 'shipp');
		}
		else
		{
			$customer_shipping_address_id = $data['customer_shipping_address_id'];
		}
	
		//
		if((int)$customer_billing_address_id==0 || (int)$customer_shipping_address_id==0)
		{
			$resArr['type'] = 'error';
			if((int)$customer_billing_address_id==0)
			{
				$resArr['msg'] = 'Please specify billing address.';
			}
			else if((int)$customer_shipping_address_id==0)
			{
				$resArr['msg'] = 'Please specify shipping address.';
			}
		}
		else
		{
			//set session variables
			$arr =  array('adm_customer_shipping_address_id'=>$customer_shipping_address_id,'adm_customer_billing_address_id'=>$customer_billing_address_id);
			$this->session->set_userdata($arr);
				
			$resArr = $this->checkShipAvail();
				
			//set session that shipping is okay
			if($resArr['type']=='success')
			{
				$this->session->set_userdata(array('adm_is_shipping_valid'=>true));
			}
			else
			{
				$this->session->set_userdata(array('adm_is_shipping_valid'=>false));
			}
		}
	
		echo json_encode($resArr);
	}
	
	/**
	 * Admin_CMS_checkout Step 5
	 * 
	 * @author   Cloudwebs
	 * functoin will check shipp availablity as per shipping code
	 */
	function checkShipAvail()
	{
		return cart_hlp_checkShipAvail( false );
	}
	
	
//Order transactios:  insert order - payment processing 

	/********************************** Admin_CMS_checkout Step  6, 7  *************************************/
	
	
/**
 * @author Cloudwebs
 * 
 * Admin_CMS_checkout Step 7
 * functoin will complete all process related to making of payment and creating new order
 */
	function payment() 
	{
		cart_hlp_payment( false ); 
	}
	
	/********************************** Admin_CMS_checkout Step  6, 7 end  *************************************/
	
/**
 * Admin_CMS_checkout Step 8
 * User will be redirect here after successfull payment
 * another url called from payment gateway.
*/	
	function orderSuccess()
	{
		cart_hlp_orderSuccess( false ); 
	}
	
/**
 * Admin_CMS_checkout Step 8
 * User will be redirect here after failed payment
 * another url called from payment gateway.
*/	
	function orderFailed()
	{
		cart_hlp_orderFailed( false ); 
	}	
	

//Order transactios end *******************************************************************//


/************************************* Post Order processing *******************************/
	
	/**
	 * @author Cloudwebs
	 * update order statuses after order placed however updating of statuses is done product wise
	 */
	function updateOrderStatus()
	{
		$is_all = $this->input->post('is_all');
		$is_email = $this->input->post('is_email');
		if($this->per_edit == 0)
		{
			$res = $this->sao->updateOrderStatus( $is_all, $is_email );
			echo json_encode($res);  
		}
		else
		{
			echo json_encode( array( "type"=>"error", "msg"=>getErrorMessageFromCode('01008') ) );
				
		}
	}
	
	/**
	 * @author Cloudwebs
	 * ships order
	 * It basically updates order status to shipped and send shipping mail
	 */
	function shipOrder()
	{
		$is_all = 1;
		$is_email = 1;
		$data['order_id'] = $this->cPrimaryId;
		$data['order_details_id'] = 0;
		$data['order_status_id'] = getField('order_status_id','order_status','order_status_key','YET_TO_SHIP');
		$data['order_tracking_number'] = "";
		$data['order_tracking_comment'] = "";
		
		if($this->per_edit == 0)
		{
			$res = $this->sao->updateOrderStatus( $is_all, $is_email, $data );
			setFlashMessage($res["type"], $res["msg"]);
			redirect( 'admin/'.$this->controller );
		}
		else
		{
			setFlashMessage( "error", getErrorMessageFromCode('01008'));
			redirect( 'admin/'.$this->controller );
		}
	}

	/**
	 * @author Cloudwebs
	 * ships order
	 * It basically updates order status to shipped and send shipping mail
	 */
	function editOrder()
	{
		if($this->per_edit == 0)
		{
			$res = $this->cancelOrder(true, 0);
			
			/**
			 * proceed to place new order if old one is cancelled
			 */
			if( $res["type"] == "success" ) 
			{
				/**
				 * UML::he_order_flow as per stated in flushAdmCartSession
				 */
				cart_hlp_flushAdmCartSession();
				$data = cart_hlp_adminRecreateSession($this->cPrimaryId);
				
				redirect( 'admin/'.$this->controller.'/salesOrderForm?custid='. _en( $data["data"]['customer_id'] ) ); 
			}
			else 
			{
				setFlashMessage( "error", "Process had faced error while canceling order.");
				redirect( 'admin/'.$this->controller );
			}
		}
		else
		{
			setFlashMessage( "error", getErrorMessageFromCode('01008'));
			redirect( 'admin/'.$this->controller );
		}
	}
	
	/**
	 * @author Cloudwebs
	 * ships order
	 * It basically updates order status to shipped and send shipping mail
	 */
	function cancelOrder($is_return=false, $is_email=1)
	{
		$is_all = 1;
		$data['order_id'] = $this->cPrimaryId;
		$data['order_details_id'] = 0;
		$data['order_status_id'] = getField('order_status_id','order_status','order_status_key','ORDER_CANCELED');
		$data['order_tracking_number'] = "";
		$data['order_tracking_comment'] = "Order has been cancelled by admin.";
	
		if($this->per_edit == 0)
		{
			/**
			 * update BUCKS transaction if applicable
			 */
			$customer_id = exeQuery("SELECT customer_id FROM orders WHERE order_id=".$data['order_id']." ", true, "customer_id"); 
			if( checkIfRowExist("SELECT 1 FROM customer_account_manage WHERE customer_id=".$customer_id." AND order_id = ".$data['order_id']." AND customer_account_manage_entry_type = 2 ") )
			{
				//flush the transaction with 0 Debit value
				hecam_bucksTransaction(false, 0, $customer_id, $data['order_id'], 0, 0, 0, 2); 
			}

			/**
			 * update warehouse if applicable
			 */
			$resDet = $this->db->query('SELECT p.product_id, p.inventory_type_id, order_details_id, warehouse_transactions_id FROM order_details od 
					 					INNER JOIN product p 
										ON p.product_id=od.product_id 
										WHERE order_id='.$data['order_id'].'  ')	//AND order_details_is_returned=0; On 02-05-2015 condition removed
							   ->result_array();
			if(isset($resDet) && is_array($resDet) && sizeof($resDet)>0)
			{
				foreach($resDet as $k=>$ar)
				{
					cart_hlp_warehouseTransaction(false, $ar["inventory_type_id"], $ar["warehouse_transactions_id"], $ar["product_id"], 0, 0); 
				}
			}

			/**
			 * Implementation of BUG 246 STEP=>10 
			 *  If for cancelled order, "affiliate_campaign_id" is applicable then adjust affected customer BUCKS as applicable
			 */
			$affiliate_campaign_id = exeQuery("SELECT affiliate_campaign_id FROM orders WHERE order_id=".$data['order_id']." ", true, "affiliate_campaign_id");
			if( !empty($affiliate_campaign_id) )
			{	
				//flush the affiliate discount 	
				$customer_partner_id = exeQuery("SELECT customer_partner_id FROM affiliate_campaign WHERE affiliate_campaign_id=".$affiliate_campaign_id." ", true, "customer_partner_id");
				hecam_bucksTransaction(true, 0, $customer_partner_id, $data['order_id'], 0, 0, 0, 1);
			}
			
			//
			$res = $this->sao->updateOrderStatus( $is_all, $is_email, $data );
			
			if( !$is_return )
			{
				setFlashMessage($res["type"], $res["msg"]);
				redirect( 'admin/'.$this->controller );
			}
			else 
			{
				return $res;
			}
		}
		else
		{
			if( !$is_return )
			{
				setFlashMessage( "error", getErrorMessageFromCode('01008'));
				redirect( 'admin/'.$this->controller );
			}
			else
			{
				return array('type'=>'error','msg'=>getErrorMessageFromCode('01008')); 
			}
		}
	}
	
/*
+-----------------------------------------+
	Function will send mail containing detailed information to the user
+-----------------------------------------+
*/
	function sendMail()
	{
		if($this->per_edit == 0)
		{
			$resSta = $this->db->query('SELECT o.order_id, order_status_key, order_status_msg, ot.order_tracking_number FROM orders o 
										INNER JOIN order_details od 
										ON od.order_id=o.order_id
										INNER JOIN order_tracking ot 
										ON ot.order_details_id=od.order_details_id
										INNER JOIN order_status os
										ON os.order_status_id=ot.order_status_id 
										WHERE o.order_id='.$this->cPrimaryId.' 
										ORDER BY ot.order_tracking_id DESC
										LIMIT 1 ')
							   ->row_array();
			$data = orderEmail($resSta['order_id'], $resSta['order_status_key'], $resSta['order_status_msg'] , $resSta['order_tracking_number']);
			
			if( $data["type"] == "success" )
			{
				setFlashMessage($data["type"], "Order email sent successfully.");
			}
			else 
			{
				setFlashMessage($data["type"], "Something wrong happen contact system support.");
			}	
			redirect( 'admin/'.$this->controller );
		}
		else
		{
			setFlashMessage( "error", getErrorMessageFromCode('01008'));
		}
		redirect( 'admin/'.$this->controller );
	}
	
/*
+-----------------------------------------+
	Function will display invoice of particular order 
+-----------------------------------------+
*/
	function invoice()
	{
		//verify if order id is valid and exist
		$cnt = getField('order_id','orders','order_id', $this->cPrimaryId);
		if((int)$cnt==0)
		{
			redirect(base_url('admin/'.$this->controller));	
		}

		$dtArr = $this->sao->getData();
		$dt = $dtArr['res']->row_array();	
		$dt['prodArr'] = $dtArr['prodRes']->result_array();
																										
		$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_invoice';
		$this->load->view('admin/layout',$dt);
	}
	
/*
+-----------------------------------------+
	Function will display invoice of particular order 
+-----------------------------------------+
*/
	function printInvoice()
	{
		if((int)$this->cPrimaryId!=0)
		{
			$res = $this->sao->getPrintInvoceData();
			$this->load->view('templates/order-invoice', $res);
		}
		else
		{
			setFlashMessage('error', "Order not found.");
			redirect('admin/sales_order');
		}
	}

/*
+-----------------------------------------+
	Function will allow user to place the same order again
+-----------------------------------------+
*/
	function reOrder()
	{
		
	}
	


/*
 * @abstract fetch shipping cost
 * 
*/
	function fetchShippingCost()
	{
		$this->sao->fetchShippingCost();
	}


/*
 * @abstract removes order detail entry
 * 
*/
	function deleteOrderDetail()
	{
		$this->sao->deleteOrderDetail();
	}
	
/*
 *  @abstract function will display detailed product selection for particular order  
 */
	function popupProductDetail()
	{
		$data['detail'] = $this->sao->popupProductDetail();
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
	}

/**	
 * function will update order item of specific order: it is post order administraion to update orders
 */
	function updateOrderItemAdmin()
	{
		echo json_encode( $this->sao->updateOrderItemAdmin() );
	}

/**
 * @author Cloudwebs
 * @abstract function will update final weight of ordered products i.e. the weight of product when it was actually manufactured
 * 
*/
	function updateFinalWeight()
	{
		if($this->per_edit == 0)
			$this->sao->updateFinalWeight();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));		
	}

	/************************************* Post Order processing end *****************************/	
	
	/*
	 +-----------------------------------------+
	Delete Category, single category and multiple
	category from single function call.
	@params : Item id. OR post array of ids
	+-----------------------------------------+
	*/
	function deleteData()
	{
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->sao->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
	
	
	/*
	 * @abstract fetch state as per country id passed
	*
	*/
	function getState()
	{
		return $this->sao->getState();
	}
	
	/*
	 * @abstract fetch address as per address id passed
	*
	*/
	function getAddress()
	{
		$data['customer_address_id'] = $this->input->post('add_id');
		$data['type'] = $this->input->post('type');
		echo $this->load->view('admin/'.$this->controller.'/customer_address',$data);
	}
	
}