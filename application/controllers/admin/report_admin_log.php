<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class report_admin_log extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'admin_log_id';
	var $cTable = 'admin_log';
	var $controller = 'report_admin_log';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function report_admin_log()
	{
		parent::__construct();
		$this->load->model('admin/report/mdl_report_admin_log','ral');
		$this->ral->cTableName = $this->cTable;
		$this->ral->cAutoId = $this->cAutoId;
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
		saveAdminLog($this->router->class, 'Admin Log', $this->cTable, $this->cAutoId, 0, $logType);
		$num = $this->ral->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['username_filter'] = $this->input->get('username_filter'); // field name of username
		$data['module_filter'] = $this->input->get('module_filter'); // module name
		$data['item_filter'] = $this->input->get('item_filter'); // item name
		$data['log_type_filter'] = $this->input->get('log_type_filter'); // log type filter
		$data['ip_filter'] = $this->input->get('ip_filter'); // field name of ip address
		$data['fromDate'] = $this->input->get('fromDate'); // field name of ip address
		$data['toDate'] = $this->input->get('toDate'); // field name of ip address
		
		if($this->is_ajax)
			$this->load->view('admin/report/'.$this->controller.'_list',$data);
		else
		{
			$data['pageName'] = 'admin/report/report_box_content';
			$this->load->view('admin/layout',$data);
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
		$ids = $this->input->post('selected');
		$this->ral->deleteData($ids);
	}
/*
+--------------------------------------------------+
	Will Display whole request which we get from 
	item detail report.
+--------------------------------------------------+
*/	
	function viewItemDetails()
	{
		$data['detail'] = $this->ral->getItemDetails();

		if(empty($data['detail']) || isset($data['detail']['error']))
		{
			$data['detail']['error'] = getErrorMessageFromCode('01006');
		}
		else
		{
			$data['detail']['robots'] = getField('robots_name','seo_robots','robots_id',@$data['detail']['robots']);
			
			unset($data['detail']['image_size_id']);
			unset($data['detail']['category_sort_order']);
			unset($data['detail']['module_manager_serialize_menu']);
		}
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
		
	}
/*
+--------------------------------------------------+
	Will Display whole request which we get from 
	user detail report.
+--------------------------------------------------+
*/	
	function viewUserDetails()
	{
		$data['detail'] = $this->ral->getUserDetails();
		
		if(isset($data['detail']['error']))
		{
			$data['error'] = $data['detail']['error'];
		}
		else
		{
			$data['detail']['admin_user_group_id'] = getField('admin_user_group_name','admin_user_group','admin_user_group_id',$data['detail']['admin_user_group_id']);
			
			unset($data['detail']['admin_user_password']);
			unset($data['detail']['admin_user_salt']);			
		}
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
		
	}
	
/*
+-----------------------------------------+
	This Function will product information 
	downloaded and create csv/xls file.
+-----------------------------------------+
*/	
	function exportData()
	{
		$ext = $this->input->post($this->controller.'_export');
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$res = $this->db->get($this->cTable);
		$listArr = $res->result_array();
		
		/*$this->load->dbutil();
		$delimiter = ",";
        $newline = "\r\n";
 
        $this->output->set_header('Content-Type: application/force-download');
        $this->output->set_header('Content-Disposition: attachment; filename="registered_users.csv"');
        $this->output->set_content_type('text/csv')
                ->set_output($this->dbutil->csv_from_result($listArr, $delimiter, $newline));*/
		
		$col= array(array_keys($listArr[0]));
		$col= $col[0];
		exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, $listArr, $ext);
		die;
	}


}