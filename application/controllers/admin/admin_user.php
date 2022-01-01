<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class admin_user extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'admin_user_id';
	var $cPrimaryId = '';
	var $cTable = 'admin_user';
	var $controller = 'admin_user';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function admin_user()
	{
		parent::__construct();
		$this->load->model('admin/mdl_admin_user','adu');
		$this->adu->cTableName = $this->cTable;
		$this->adu->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->adu->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
	
	function userEmailId($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('admin_user_emailid',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('userEmailId', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Admin User ', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->adu->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['text_firstname'] = $this->input->get('text_firstname');
		$data['text_lastname'] = $this->input->get('text_lastname');
		$data['text_emailid'] = $this->input->get('text_emailid');
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1';
		
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
	function adminUserForm()
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
		$this->form_validation->set_rules('admin_user_firstname','User Frist Name','trim|required');
		$this->form_validation->set_rules('admin_user_lastname','User Last Maximum ','trim|required');
		$this->form_validation->set_rules('admin_user_emailid','User Email Id','trim|required|callback_userEmailId|valid_email');
		if($this->cPrimaryId == "" || $this->input->post('admin_user_password')!=''){
			$this->form_validation->set_rules('admin_user_password','User Password','trim|required|min_length[6]');
			$this->form_validation->set_rules('admin_user_password_confirm','User Confirm Password','trim|required|min_length[6]|matches[admin_user_password]');
		}
		$this->form_validation->set_rules('admin_user_group_id',' User Group','trim|required');
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->adu->getData();
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
				$this->adu->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->adu->saveData();
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
	{	if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->adu->deleteData($ids);
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
			$this->adu->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));		
	}
	

}