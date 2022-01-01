<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class customer extends CI_controller {
	
	var $is_ajax = false;
	var $cAutoId = 'customer_id';
	var $cAutoIdA = 'customer_address_id'; // auto increment of address table
	var $cPrimaryId = '';
	var $cPrimaryIdA = array();  		 // primary of address table
	var $cTable = 'customer';
	var $cTableA = 'customer_address';  	  // address  table
	var $controller = 'customer';
	var $is_post = false;
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function customer()
	{
		parent::__construct();
		$this->load->model('admin/mdl_customer','cust');
		$this->cust->cTableName = $this->cTable;
		$this->cust->cAutoId = $this->cAutoId; 
		$this->cust->cTableNameA = $this->cTableA; // address table access 
		$this->cust->cAutoIdA = $this->cAutoIdA; // address auto increment access
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->cust->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
		
		// address table access 		
		if($this->input->get('item_idA') != '' || $this->input->post('item_idA') != '')
		{
			$priArr = $this->security->xss_clean($_REQUEST['item_idA']);
			foreach($priArr as $k=>$ar)
				$this->cPrimaryIdA[] = $this->cust->cPrimaryIdA[] = _de($ar);
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
	For exaconle : if we don't want index
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
	
	function customerEmail($str)
	{
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
			
		$c = $this->db->where('customer_emailid',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('customerEmail', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Customer', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cust->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['name_filter'] = $this->input->get('name_filter'); 
		$data['emailid_filter'] = $this->input->get('emailid_filter'); 
		$data['phoneno_filter'] = $this->input->get('phoneno_filter'); 
		$data['group_filter'] = $this->input->get('group_filter'); 
		$data['reference_filter'] = $this->input->get('reference_filter'); 
		$data['ip_filter'] = $this->input->get('ip_filter'); 
		$data['approved_filter'] = ($this->input->get('approved_filter')!='') ? $this->input->get('approved_filter') : '-1'; 
		$data['fromDate'] = $this->input->get('fromDate'); // field name of ip address
		$data['toDate'] = $this->input->get('toDate'); // field name of ip address
		$data['status_filter'] = ($this->input->get('status_filter') != '') ? $this->input->get('status_filter') : '-1'; // filter by status
		
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
	function customerForm()
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
		$this->form_validation->set_rules('customer_firstname','customer First Name','trim|required');
		$this->form_validation->set_rules('customer_lastname','Customer Last Name','trim|required');
		$this->form_validation->set_rules('customer_emailid','Customer Email Id','trim|required|valid_email|callback_customerEmail');
		$this->form_validation->set_rules('customer_phoneno','Customer Phone No','trim|required|numeric');
		if($this->cPrimaryId == "" || $this->input->post('customer_password')!=''){
			$this->form_validation->set_rules('customer_password','Customer Password','trim|required|min_length[6]');
			$this->form_validation->set_rules('customer_confirm_password','Customer Confirm Password','trim|required|min_length[6]|matches[customer_password]');
		}
		
		//address table fields
		$this->form_validation->set_rules('customer_address_firstname[]','Customer First Name','trim|required');
		$this->form_validation->set_rules('customer_address_lastname[]','Customer Last Name','trim|required');
		$this->form_validation->set_rules('customer_address_address[]','Address','trim|required');
		$this->form_validation->set_rules('customer_address_landmark_area[]','Landmark Area','trim|required');
		$this->form_validation->set_rules('pincode[]','Postal Code','trim|required|min_length[6]|max_length[6]|numeric');
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '' && $this->cPrimaryIdA != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->cust->getData();
				$dt = $dtArr['res']->row_array();
				$dt['cust_add'] = $dtArr['cus_add']->result_array();
			}
			$dt['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			$this->is_post = true;
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				if(!empty($_POST) && !isset($_POST['customer_address_firstname']))
				{
					setFlashMessage('error',"Warning! Select atleast one address.");
				}
				else if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				
				$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				
				$this->cust->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->cust->saveData();
				
				if(isset($_GET['mode']) && $_GET['mode']=='order')
					redirect('admin/sales_order?cust=list');
				else
					redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	Delete data, single and multiple
	 from single function call.
	@params : Item id. OR post array of	ids
+-----------------------------------------+
*/
/*
 * @author   Cloudwebs
 * @abstract function will load city as per state selected
 */
	function loadCityAjax()
	{
		$state_id = $this->input->post('state_id');
		if(!empty($state_id))
		{
			echo loadCity($state_id);
		}
		else
		{
			echo '<option value="">- Select State First -</option>';	
		}
	}
/*
 * @author   Cloudwebs
 * @abstract function will load area as per city selected
 */
	function loadAreaAjax()
	{
		$city_name = $this->input->post('city_name');
		$state_id = $this->input->post('sta_id');
		if($city_name!='' && $state_id)
		{
			echo loadArea($city_name,$state_id);
		}
		else
		{
			echo '<option value="">- Select City First -</option>';	
		}
	}

/*
 * @author   Cloudwebs
 * @abstract function will load pincode as per area selected
 */
	function loadPincodeAjax()
	{
		$area_name = $this->input->post('area_name');
		$city_name = $this->input->post('city_name');
		$state_id = $this->input->post('sta_id');
		if($area_name!='')
		{
			echo json_encode(loadPincode($area_name,$city_name,$state_id));
		}
		else
		{
			return json_encode(array('pincode_id'=>'','pincode'=>''));	
		}
	}
		
	function deleteData()
	{	
		if($this->per_delete == 0)
		{
			$ids = $this->input->post('selected');
			$this->cust->deleteData($ids);
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
			$this->cust->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
	}
/*
+-----------------------------------------+
	This Function will product information 
	downloaded and create csv/xls file.
+-----------------------------------------+
*/	
	function exportData()
	{
		$res = $this->db->get($this->cTable);
		$listArr = $res->result_array();
		
		$ext = $this->input->post($this->controller.'_export');
		$col= array(array_keys($listArr[0]));
		$col= $col[0];
		exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, $listArr, $ext);
		die;
	}
	/*
* @abstract fetch state as per country id passed
* 
*/
	function getState()
	{
		$countryid = $this->input->post('country_id');
		$name = $this->input->post('name');
		echo loadStateDropdown($name,$countryid);
	}
	

/**
 * @author Cloudwebs Kahar
 * @abstract add address in customer page
 */
	function addAddress()
	{
		$dt['address_row'] = $this->input->post('address_row');
		$add_form =  $this->load->view('admin/'.$this->controller.'/add_address',$dt, TRUE);
		
		echo $add_form;
	}
	

}