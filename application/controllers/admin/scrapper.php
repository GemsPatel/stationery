<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class scrapper extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'movies_id';
	var $cPrimaryId = '';
	var $cTable = 'sc_movies';
	var $controller = 'scrapper';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function scrapper()
	{
		parent::__construct();
		$this->load->model('admin/mdl_scrapper','scr');
		$this->scr->cTableName = $this->cTable;
		$this->scr->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->scr->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
		
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
		$logType = 'V';
		saveAdminLog($this->router->class, 'Scrapper ', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->scr->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['name_filter'] = $this->input->get('name_filter');
		$data['year_filter'] = $this->input->get('year_filter');
		$data['site_filter'] = $this->input->get('site_filter');
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
		
		if($this->is_ajax)
		{
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data);
		}
		else
		{
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
			$this->load->view('admin/layout',$data);
		}
	}
	
/*
+-----------------------------------------+
	function for import data from csv file and 
	insert into database
+-----------------------------------------+
*/
	function importData()
	{
		if(isset($_FILES['import_csv']['name'])!=="")
		{
			$name = $_FILES['import_csv']['name'];
			$pos  = strpos($name,".");
			$type = strtoupper(substr($name,$pos+1));
			
			if($type=='CSV')
	  		{	
				$this->scr->importMovies();
				setFlashMessage('success','Imported successfully, scraper will do scraping of movies in batch mode. ');
				redirect('admin/'.$this->controller);
			}
			else
			{
		 		setFlashMessage('error','Please Select CSV file.');
		  		redirect('admin/'.$this->controller);	
			}
		}
		else
		{
			setFlashMessage('error','Please Select file.');
			redirect('admin/'.$this->controller);	
		}
	}
/*
+-----------------------------------------+
	This Function will product information 
	downloaded and create csv/xls file.
+-----------------------------------------+
*/	
	function exportData()
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$res = $this->db->get($this->cTable);
		$listArr = $res->result_array();
		
		$ext = $this->input->post($this->controller.'_export');
		$col= array(array_keys($listArr[0]));
		$col= $col[0];
		exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, $listArr, $ext);
		die;
	}
/*
+-----------------------------------------+
	Delete Product from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->scr->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
/*
+-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids,status
+-----------------------------------------+
*/	
	function updateStatus()
	{
		if($this->per_edit == 0)
			$this->scr->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));
	}
/*
* Function will display status que
*/
	public function statusPopupData()
	{
		if($this->is_ajax)
		{
			$dt['remaining_total_crawl'] = $this->db->where('cq_status',0)->count_all_results('cron_que');
			$this->load->view('admin/facebox/viewPopupScrapperStatus',$dt);
		}
		
	}


}