<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sales_order_return extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'order_return_id';
	var $cPrimaryId = '';
	var $cTable = 'order_return';
	var $controller = 'sales_order_return';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function sales_order_return()
	{
		parent::__construct();
		$this->load->model('admin/mdl_sales_order_return','sor');
		$this->sor->cTableName = $this->cTable;
		$this->sor->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->sor->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
			
		//initialize variables
		if($this->cPrimaryId != '')
			$this->sor->prev_quantity = getField('order_return_quantity',"order_return","order_return_id",$this->cPrimaryId);

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
		else if(empty($per) || ($this->per_add == 1 && $this->per_edit == 1 && $this->per_delete == 1 && $this->per_view == 1))
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
		$num = $this->sor->getData(false);
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['return_id_filter'] = $this->input->get('return_id_filter'); // order return id
		$data['order_id_filter'] = $this->input->get('order_id_filter'); // order id
		$data['customer_name_filter'] = $this->input->get('customer_name_filter'); // field name of customer name
		$data['product_filter'] = $this->input->get('product_filter'); // field name of product name
		$data['reason_filter'] = $this->input->get('reason_filter'); // field name of order reason
		$data['order_status_id'] = ($this->input->get('order_status_id') != '')?$this->input->get('order_status_id'):'-1'; // filter by order status
		$data['fromDate'] = $this->input->get('fromDate'); // field name of ip address
		$data['toDate'] = $this->input->get('toDate'); // field name of ip address
		
		if($this->is_ajax)
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
		else
		{
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
			$this->load->view('admin/layout',$data);
		}
	}
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function orderReturnForm()
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
		
		$data = array();
		$this->form_validation->set_rules('order_id','Order ID','trim|required');
		$this->form_validation->set_rules('order_details_id','Product','trim|required|numeric');
		$this->form_validation->set_rules('order_return_quantity','Quantity','trim|numeric');
		$this->form_validation->set_rules('order_return_reason_key','Order Return Reason','trim|required');
		//$this->form_validation->set_rules('order_return_action','Order Return Action','trim|required');
		
		if( empty($this->cPrimaryId) )
		{
			$this->form_validation->set_rules('order_status_id','Return Status','trim|required|numeric');
		}
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->sor->getData();
				$dt = $dtArr->row_array();
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
				
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				$this->sor->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->sor->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
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
			$this->sor->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
	
/*
+-----------------------------------------+
	function will fetch products orderd in particular order
+-----------------------------------------+
*/		
	function fetchOrderDetails()
	{	
		echo json_encode($this->sor->fetchOrderDetails());
	}

/*
+-----------------------------------------+
	function will fetch products ordered quantity
+-----------------------------------------+
*/		
	function fetchQuantity()
	{	
		echo json_encode($this->sor->fetchQuantity());
	}

}