<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class pincode extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'pincode_id';
	var $cPrimaryId = '';
	var $cTable = 'pincode';
	var $controller = 'pincode';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	//parent constructor will load model inside it
	function pincode()
	{
		parent::__construct();
		$this->load->model('admin/mdl_pincode','mt');
		$this->mt->cTableName = $this->cTable;
		$this->mt->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->mt->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
		saveAdminLog($this->router->class, 'Pincode', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->mt->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['text_state'] = $this->input->get('text_state'); // state field name
		$data['text_city'] = $this->input->get('text_city'); // city field name
		$data['text_area'] = $this->input->get('text_area'); // area field name
		$data['text_pincode'] = $this->input->get('text_pincode'); // pincode field name
		
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
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function pincodeForm()
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
		$this->form_validation->set_rules('state_id','State Name','trim|required');
		$this->form_validation->set_rules('areaname','Area Name','trim|required');
		$this->form_validation->set_rules('cityname','City Name','trim|required');
		$this->form_validation->set_rules('pincode','Pincode','trim|required|numeric|min_length[6]|max_length[6]');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->mt->getData();
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
				$this->mt->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->mt->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete data, single and multiple
	 from single function call.
	@params : Item id. OR post array of ids
+-----------------------------------------+
*/		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->mt->deleteData($ids);
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
			$this->mt->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
			
	}
/*
+-----------------------------------------+
	function for get state from country wise
+-----------------------------------------+
*/
	function getState()
	{
		return $this->mt->getState();
	}

/*
+-----------------------------------------+
	function for import data from csv file and 
	insert into pincode table 
	format:: pincode,areaname,cityname,state_id
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
				$this->mt->importPincode();
				setFlashMessage('success','Pincodes is Imported  successfully.');
				redirect('admin/'.$this->controller);
			}
			else
			{
		 		setFlashMessage('error','Please Upload .CSV file.');
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