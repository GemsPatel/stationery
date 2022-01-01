<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class global_configuration extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'site_config_id';
	var $cTable = 'site_config';
	var $controller = 'global_configuration';
	var $cPrimaryId = '';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function global_configuration()
	{
		parent::__construct();
		$this->load->model('admin/mdl_global_configuration','aset');
		$this->aset->cTableName = $this->cTable;
		$this->aset->cAutoId = $this->cAutoId;
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
	
	function index($start = 0)
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$data = $this->aset->getData();
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
		$this->load->view('admin/layout',$data);
	}
	
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function globalConfigurationForm()
	{
		if($this->cPrimaryId != '')
		{
			if($this->per_edit != 0)
			{
				setFlashMessage('error',getErrorMessageFromCode('01008'));
				showPermissionDenied();
			}
		}
		
		// saving data to database
		$this->aset->saveData();
		redirect('admin/'.$this->controller);
	}
	

}