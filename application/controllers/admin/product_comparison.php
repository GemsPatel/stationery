<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class product_comparison extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'product_id';
	var $cPrimaryId = '';
	var $cTable = 'product';
	var $controller = 'product_comparison';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	var $is_post = false;
	
	//parent constructor will load model inside it
	function product_comparison()
	{
		parent::__construct();
		$this->load->model('admin/mdl_product_comparison','prodc');
		$this->prodc->cTableName = $this->cTable;
		$this->prodc->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->prodc->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
			
		$this->chk_permission();	
		
		$this->is_post = ($_SERVER['REQUEST_METHOD']=='POST')?true:false;
		
//		error_reporting(E_ALL);
//		ini_set("display_errors", 1);
//		$this->db->db_debug = TRUE;
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
		saveAdminLog($this->router->class, 'Product Comparison', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$num = $this->prodc->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); 	// sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['product_name_filter'] = $this->input->get('product_name_filter'); 
		$data['product_sku_filter'] = $this->input->get('product_sku_filter');
		$data['status_filter'] = ($this->input->get('status_filter') != '') ? $this->input->get('status_filter') : '-1'; // filter by status
		$data['status_cctld_filter'] = ($this->input->get('status_cctld_filter') != '') ? $this->input->get('status_cctld_filter') : '-1'; // filter by cctld status

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

}
