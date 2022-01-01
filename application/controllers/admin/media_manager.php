<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class media_manager extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = '';
	var $cTable = '';
	var $controller = 'media_manager';
	var $per_view = 1;
	
	
	//parent constructor will load model inside it
	function media_manager()
	{
		parent::__construct();
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
		$logType = 'V';
		saveAdminLog($this->router->class, 'Media Manager', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		// redirect(site_url('kcfinder/browse.php'));	
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
		$this->load->view('admin/layout',$data);
	}
	
 
	
	 
}