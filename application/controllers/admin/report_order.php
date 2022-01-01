  <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report_order extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'order_id';
	var $cTable = 'orders';
	var $controller = 'report_order';
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function report_order()
	{
		parent::__construct();
		$this->load->model('admin/report/mdl_report_order','ro');
		$this->ro->cTableName = $this->cTable;
		$this->ro->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
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
		$num = $this->ro->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['order_id_filter'] = $this->input->get('order_id_filter');
		$data['gender_filter'] = $this->input->get('gender_filter');
		$data['invoice_number_filter'] = $this->input->get('invoice_number_filter');
		$data['customer_name_filter'] = $this->input->get('customer_name_filter');
		$data['payment_method_id'] = $this->input->get('payment_method_id');
		$data['shipping_method_id'] = $this->input->get('shipping_method_id');
		$data['from_range_pr'] = $this->input->get('from_range_pr');
		$data['to_range_pr'] = $this->input->get('to_range_pr');
		$data['fromDate'] = $this->input->get('fromDate');
		$data['toDate'] = $this->input->get('toDate');
		
		if($this->is_ajax)
			$this->load->view('admin/report/'.$this->controller.'_list',$data);
		else
		{
			$data['pageName'] = 'admin/report/report_box_content';
			$this->load->view('admin/layout',$data);
		}
	}
	function viewItemCustomer()
	{
		$data['detail'] = $this->ro->getCustomerDetails();
		
		if(isset($data['detail']['error']))
		{
			$data['error'] = $data['detail']['error'];
		}
		else
		{
			$data['detail']['customer_group_id'] = getField('customer_group_name','customer_group','customer_group_id',$data['detail']['customer_group_id']);
			
			unset($data['detail']['customer_group_status']);
			unset($data['detail']['customer_group_created_date']);
			unset($data['detail']['customer_group_modified_date']);
			
		}
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
		
		
	}
	
	
	
	function viewFullDetails()
	{
		$data['detail'] = $this->ro->getFullDetails();
		
		if(isset($data['detail']['error']))
		{
			$data['error'] = $data['detail']['error'];
		}

		$data['temp']['customer_firstname'] = $data['detail'][0]['customer_firstname'];
		$data['temp']['customer_lastname'] = $data['detail'][0]['customer_lastname'];
		$data['temp']['customer_gender'] = $data['detail'][0]['customer_gender'];
		$data['temp']['order_id'] = $data['detail'][0]['order_id'];
		$data['temp']['invoice_number'] = $data['detail'][0]['invoice_number'];
		
		if (isset($data['detail'][0]['coupon_name'])):
			$data['temp']['coupon_name'] = $data['detail'][0]['coupon_name'];
		endif;
		
		if (isset($data['temp'][0]['gift_name'])):
			$data['temp']['gift_name'] = $data['detail'][0]['gift_name'];
		endif;
		
		$data['temp']['No_Of_Product'] = sizeof($data['detail']);
		
		
		foreach($data['detail'] as $k=>$ar)
		{
			$data['temp']['Product '.($k+1).' Name'] = $data['detail'][$k]['product_name'];
			$data['temp']['Product '.($k+1).' Price'] = $data['detail'][$k]['order_details_product_price'];
			$data['temp']['Product '.($k+1).' Quantity'] = $data['detail'][$k]['order_details_product_qty'];
		}
		$data['temp']['payment_method_name'] = $data['detail'][0]['payment_method_name'];
		$data['temp']['shipping_method_name'] = $data['detail'][0]['shipping_method_name'];
		$data['temp']['order_created_date'] = $data['detail'][0]['order_created_date'];
		$data['temp']['order_modified_date'] = $data['detail'][0]['order_modified_date'];

		unset($data['detail']);
		$data['detail'] = $data['temp'];
		unset($data['temp']);
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
	}
	

}