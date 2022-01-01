<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report_order_return extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'order_return_id';
	var $cTable = 'order_return';
	var $controller = 'report_order_return';
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function report_order_return()
	{
		parent::__construct();
		$this->load->model('admin/report/mdl_report_order_return','ro');
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
		$data['invoice_number_filter'] = $this->input->get('invoice_number_filter');
		$data['customer_name_filter'] = $this->input->get('customer_name_filter');
		$data['product_name_filter'] = $this->input->get('product_name_filter');
		$data['from_range_pr'] = $this->input->get('from_range_pr');
		$data['to_range_pr'] = $this->input->get('to_range_pr');
		$data['from_range_tq'] = $this->input->get('from_range_tq');
		$data['to_range_tq'] = $this->input->get('to_range_tq');
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
	function viewItemProduct()
	{
		$data['detail'] = $this->ro->getproductDetails();
		
		if(isset($data['detail']['error']))
		{
			$data['error'] = $data['detail']['error'];
		}
		else
		{
			$data['detail']['category_id'] = getField('category_name','product_categories','category_id',$data['detail']['category_id']);
			
		}
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
		
	}
	

}