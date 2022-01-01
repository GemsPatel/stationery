<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class import_export extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'diamond_price_id';
	var $cTable = 'diamond_price';
	var $controller = 'import_export';
	var $per_view = 1;
	
	
	//parent constructor will load model inside it
	function import_export()
	{
		parent::__construct();
		$this->load->model('admin/mdl_import_export','imex');
		$this->imex->cTableName = $this->cTable;
		$this->imex->cAutoId = $this->cAutoId;
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
		$logType = 'V';
		saveAdminLog($this->router->class, 'Import and Export', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$data = $this->imex->getData();
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
		$this->load->view('admin/layout',$data);
	}
	
 function backupForm()
 {
	 $data['select_db'] = $this->input->post('select_db');
	 $data=$this->imex->getBackup();
	 $name='database-backup-'.date('Y-m-d').'.sql';
	 $save='assets/export/'.$name;
	 $fp = fopen($save,'w');
	 fwrite($fp,$data);
	 fclose($fp);
	 $this->load->helper('download');
	 $data1= file_get_contents($save); // Read the file's contents
	 force_download($name,$data1); 
	 header('Content-Type: application/force-download');  
	
 }
		
 function restoreForm()
 {
	
	  if($_FILES['export_file']['name']!=="")
	  {
		if($_FILES['export_file']['type']=='text/x-sql')
	  	{
		  
			ini_set('max_execution_time', 600);
			$this->imex->importDatabase();
			setFlashMessage('success','Database is Imported  successfully.');
			redirect('admin/'.$this->controller);
			//header("location:admin/".$this->controller);
		}
		{
		  setFlashMessage('error','Please Upload .SQL file.');
		  redirect('admin/'.$this->controller);	
		}
	  }
	  else
	  {
		  setFlashMessage('error','Please Upload file.');
		  redirect('admin/'.$this->controller);
	  }
		
 }
	
	 
}