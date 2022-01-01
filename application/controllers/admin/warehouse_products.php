<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class warehouse_products extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'product_id';
	var $cPrimaryId = '';
	var $cTable = 'product';
	var $controller = 'warehouse_products';
	var $per_add = 1;
	var $per_edit = 1; 
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function warehouse_products()
	{
		parent::__construct();
		$this->load->model('admin/mdl_warehouse_products','cat');
		$this->cat->cTableName = $this->cTable;
		$this->cat->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->cat->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
		$logType = 'V';
		saveAdminLog($this->router->class, 'Warehouse Transactions', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cat->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['product_filter'] = $this->input->get('product_filter'); // filter by category name

		if($this->is_ajax)
		{
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
		}
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
	function wpForm()
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
		$this->form_validation->set_rules('product_id','Product','trim|required|numeric');
		$this->form_validation->set_rules('product_price_calculated_price','Market Price','trim|required|numeric');
		$this->form_validation->set_rules('product_discounted_price','Our Price','trim|required|numeric');

		if($this->form_validation->run() == FALSE )
		{
			$data['error'] = $this->form_validation->get_errors();
			$data["type"] = "error"; 
			$data["msg"] = getErrorMessageFromCode('01005');
		}
		else // saving data to database
		{
		 	$data = $this->cat->saveData();
		}
		
		echo json_encode($data);
	}
	
}