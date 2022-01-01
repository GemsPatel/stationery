<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sales_order extends CI_Controller {
	
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

	//parent constructor will load model inside it
	function sales_order()
	{
		parent::__construct();
		$this->load->model('admin/mdl_sales_order','sao');
		$this->sao->cTableName = $this->cTable;
		$this->sao->cAutoId = $this->cAutoId;
		$this->sao->cTableNameC = $this->cTableC;
		$this->sao->cAutoIdC = $this->cAutoIdC;
		$this->is_ajax = $this->input->is_ajax_request();		
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->sao->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));

		if($this->input->get('custid') != '' || $this->input->post('custid') != '')
			$this->cPrimaryIdC  = $this->sao->cPrimaryIdC = _de($this->security->xss_clean($_REQUEST['custid']));
		$this->chk_permission();	
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
		
	function index($start = 0)
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		if((isset($_GET['cust']) && $_GET['cust']='list') || isset($_GET['email_filter']))
		{
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
		else if((isset($_GET['prod']) && $_GET['prod']='list') || isset($_GET['product_name_filter']))
		{
			$num = $this->sao->getProductData();
			$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
			
			$data['start'] = $start;
			$data['total_records'] = $num->num_rows();
			$data['per_page_drop'] = per_page_drop();
			$data['srt'] = $this->input->get('s'); // sort order
			$data['field'] = $this->input->get('f'); // sort field name
			$data['product_name_filter'] = $this->input->get('product_name_filter'); 
			$data['customer_email_filter'] = $this->input->get('customer_email_filter'); // field name of customer_emailid
			$data['category_name_filter'] = $this->input->get('category_name_filter'); 
			$data['product_sku_filter'] = $this->input->get('product_sku_filter'); 
			
			if($this->is_ajax)
				$this->load->view('admin/'.$this->controller.'/product_ajax_html_data',$data); // this view loaded on ajax call
			else
			{
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
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
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
		
		$prodArr = array();
		$data = array();
		if($_SERVER['REQUEST_METHOD'] == 'POST'  && @$_GET['prod']!='add')
		{
			$this->is_post = true;
			$this->form_validation->set_rules('hid_product_id','Product','required');
			$this->form_validation->set_rules('customer_address_firstname_bill','First Name','trim|required');
			$this->form_validation->set_rules('customer_address_lastname_bill','Last Name','trim|required');
			$this->form_validation->set_rules('customer_address_address_bill','Address','trim|required');
			$this->form_validation->set_rules('customer_address_city_bill','City','trim|required');
			$this->form_validation->set_rules('customer_address_zipcode_bill','Zipcode','trim|required');
			$this->form_validation->set_rules('country_id_bill','Country','trim|required');
			$this->form_validation->set_rules('customer_address_state_id_bill','State','trim|required');
			$this->form_validation->set_rules('customer_address_firstname_shipp','First Name','trim|required');
			$this->form_validation->set_rules('customer_address_lastname_shipp','Last Name','trim|required');
			$this->form_validation->set_rules('customer_address_address_shipp','Address','trim|required');
			$this->form_validation->set_rules('customer_address_city_shipp','City','trim|required');
			$this->form_validation->set_rules('customer_address_zipcode_shipp','Zipcode','trim|required');
			$this->form_validation->set_rules('country_id_shipp','Country','trim|required');
			$this->form_validation->set_rules('customer_address_state_id_shipp','State','trim|required');
			$this->form_validation->set_rules('payment_method_id','Payment Method','trim|required');
			$this->form_validation->set_rules('shipping_method_id','Shipping Method','trim|required');
			$this->form_validation->set_rules('order_status_id','Order Status','trim|required');
		}

		if(isset($_GET['prod']) && $_GET['prod']=='add')
		{
			$prodArr = $this->getSelectedProd();
		}
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->sao->getData();
				$dt = $dtArr['res']->row_array();
				$dt['prodArr'] = $dtArr['prodRes']->result_array();
				//pr($dtArr);die;
			}

			if(sizeof($prodArr)>0)
			{
				$dt['prodArr'] = array_merge($prodArr,$dt['prodArr']);
			}
				
			$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				
				$data['prodArr'] = $prodArr;
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->sao->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->sao->saveData();
				redirect('admin/'.$this->controller);
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
 * @author Cloudwebs
 * @abstract fetch calculated price for diamonds and metal
 * @return $price value
*/
	function getDiaMetPrice()
	{
		echo $this->sao->getDiaMetPrice();
	}

/*
 * @author Cloudwebs
 * @abstract fetch calculated price from product price table
 * @return $price value
*/
	function getProdPrice()
	{
		echo json_encode($this->sao->getProdPrice());
	}

/*
 * @author Cloudwebs
 * @abstract fetch products selected by user to add in order
 * 
*/
	function getSelectedProd()
	{
		return $this->sao->getSelectedProd();
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

/*
 * @abstract fetch coupon discount
 * 
*/
	function getCouponDiscount()
	{
		$this->sao->getCouponDiscount();
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
 * @abstract fetch shipping cost
 * 
*/
	function updateOrderStatus()
	{
		if($this->per_edit == 0)
			$this->sao->updateOrderStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));		
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

}