<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class customer_private_message extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'private_message_id';
	var $cTable = 'private_message';
	var $controller = 'customer_private_message';
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function customer_private_message()
	{
		parent::__construct();
		$this->load->model('admin/mdl_customer_private_message','cpm');
		$this->cpm->cTableName = $this->cTable;
		$this->cpm->cAutoId = $this->cAutoId;
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
		saveAdminLog($this->router->class, 'Customer Private Message', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cpm->getData(false);
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['username_filter'] = $this->input->get('username_filter'); // field name of username
		$data['email_filter'] = $this->input->get('email_filter'); // module name
		$data['phone_filter'] = $this->input->get('phone_filter'); // module name
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
		$data['ip_filter'] = $this->input->get('ip_filter'); // field name of ip address
		$data['fromDate'] = $this->input->get('fromDate'); // field name of ip address
		$data['toDate'] = $this->input->get('toDate'); // field name of ip address
		
		if($this->is_ajax)
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
		else
		{
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list';
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
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->cpm->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
	
/*
+--------------------------------------------------+
	Will Display whole request which we get from 
	private detail report.
+--------------------------------------------------+
*/	
	function viewPrivateMsgDetails()
	{
		$data['res'] = $this->cpm->getData(true)->result_array();
		
		$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_view';
		$this->load->view('admin/layout',$data);
		
		/*$data['detail'] = $this->cpm->getData()->row_array();
		
		$data['detail']['pm_status'] = ($data['detail']['pm_status']=='O') ? 'Open' : 'Closed';
		unset($data['detail']['private_message_id']);
		unset($data['detail']['pm_parent_id']);	
		unset($data['detail']['customer_id']);			

		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);*/
	}
/*
+--------------------------------------------------+
	Will Display whole request which we get from 
	user detail report.
+--------------------------------------------------+
*/	
	function viewCustomerDetails()
	{
		$data['detail'] = $this->cpm->getCustomerDetails();
		if(!isset($data['detail']['error']))
		{
			$data['detail']['customer_group_id'] = getField('customer_group_name','customer_group','customer_group_id',$data['detail']['customer_group_id']);
			
			unset($data['detail']['customer_id']);
			unset($data['detail']['customer_password']);
			unset($data['detail']['customer_salt']);
			unset($data['detail']['customer_newsletter']);
			unset($data['detail']['customer_status']);
			unset($data['detail']['customer_approved']);
		}
		
		$this->load->view('admin/facebox/viewPopupRequestDetail',$data);
		
	}

}