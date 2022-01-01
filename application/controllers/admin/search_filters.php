<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class search_filters extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'filters_id';
	var $cPrimaryId = '';
	var $cTable = 'filters';
	var $controller = 'search_filters';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function search_filters()
	{
		parent::__construct();
		$this->load->model('admin/mdl_search_filters','sea');
		$this->sea->cTableName = $this->cTable;
		$this->sea->cAutoId = $this->cAutoId;
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
		else if(empty($per) || ($this->per_add == 1 && $this->per_edit == 1 && $this->per_delete == 1 && $this->per_view == 1))
		{
			showPermissionDenied();
		}
	}	
	
	function index()
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
// 		$data['resCompAttrFilter'] = $this->sea->getData();
		$data['listArr'] = getInventoryListing();
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_it';
		$this->load->view('admin/layout',$data);
	}

/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function searchFilterForm()
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
		 $this->sea->saveData();
		 redirect('admin/'.$this->controller);
	}

	/*
	 * Function will list item listing with languages in reference.
	*/
	function inventoryType()
	{		
		if($this->input->get('inventory') == 'true')
		{
			$data['listArr'] = $this->sea->getLanguagesForListing();
// 			$data['resCompAttrFilter'] = $this->sea->getData();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_sl';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
	
	/*
	 * Function will list item listing with languages in reference.
	*/
	function itemLanguages()
	{
		if($this->input->get('edit') == 'true')
		{
// 			$data['listArr'] = $this->sea->getLanguagesForListing();
			$data['resCompAttrFilter'] = $this->sea->getData();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
}