<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_permission extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'permission_id';
	var $cPrimaryId = '';
	var $cTable = 'permission';
	var $controller = 'admin_permission';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/mdl_admin_permission','per');
		$this->per->cTableName = $this->cTable;
		$this->per->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if((int)$this->session->userdata('admin_id')!=0)
		{
			$res = checkIsSuperAdmin();
			if(!$res)
			{
				setFlashMessage('error',getErrorMessageFromCode('01023'));
				adminRedirect('admin/dashboard');
			}
		}

		$this->chk_permission();		
	}
	
	function index()
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Admin Permission ', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}

		$data = array();
		$res = $this->per->getData();
		
		$data['res'] = $res->result_array();
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
		$this->load->view('admin/layout',$data);
		
		
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
	Function will update or insert permissions for particular admin user
	will be in post method.
+-----------------------------------------+
*/
	function update_insertPermission()
	{
		$returnArr = array();
		$res = checkIsSuperAdmin();
		if($res)
		{
			$returnArr = $this->per->update_insertPermission();
		}
		else
		{
			$returnArr['type'] ='error';
			$returnArr['msg'] = "Sorry! you don't have permission.";
		}
		
		echo json_encode($returnArr);
	}
}