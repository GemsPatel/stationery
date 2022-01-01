<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class email_list extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'email_list_id';
	var $cPrimaryId = '';
	var $cTable = 'email_list';
	var $controller = 'email_list';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function email_list()
	{		
		parent::__construct();
		$this->load->model('admin/mdl_email_list','el');
		$this->el->cTableName = $this->cTable;
		$this->el->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->el->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
/*
+-----------------------------------------+
	Callback function from form validation will
	check duplication in database
	$str - > string we are going to check in database
+-----------------------------------------+
*/
	function checkEmail($str)
	{
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);	
					
		$c = $this->db->where('email_id',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('checkEmail', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
			return true;
	}
		
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Email List', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->el->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['email_filter'] = $this->input->get('email_filter');
		$data['optlevel_filter'] = ($this->input->get('optlevel_filter') != '') ? $this->input->get('optlevel_filter') : '-1';
		$data['status_filter'] = ($this->input->get('status_filter') != '') ? $this->input->get('status_filter') : '-1';
		$data['refsource_filter'] = $this->input->get('refsource_filter');
		$data['fromDate'] = $this->input->get('fromDate');
		$data['toDate'] = $this->input->get('toDate');
		
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
	function emailListForm()
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
		$this->form_validation->set_rules('email_id','Email Id','trim|required|valid_email|callback_checkEmail');
		$this->form_validation->set_rules('el_reference_source','Reference source','trim|required');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') //if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->el->getData();
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
				$this->el->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->el->saveData();
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
			$this->el->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
	
	

}