<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class coupon extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'coupon_id';
	var $cPrimaryId = '';
	var $cTable = 'coupon';
	var $controller = 'coupon';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function coupon()
	{
		parent::__construct();
		$this->load->model('admin/mdl_coupon','cou');
		$this->cou->cTableName = $this->cTable;
		$this->cou->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->cou->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
/*
+-----------------------------------------+
	Function will check unique coupon code.
+-----------------------------------------+
*/	
	function couponCode($str)
	{
		if($this->cPrimaryId)
		$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
		
		if( MANUFACTURER_ID != 7 )
				$this->db->where('manufacturer_id', MANUFACTURER_ID );
				
		$c = $this->db->where('coupon_code',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('couponCode', 'This coupon code already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
/*
+-----------------------------------------+
	Function will check validation discount field
	@params: $str-> discount input string value
+-----------------------------------------+
*/		
	function discount_amt_check($str)
	{
		if((int)$str <= 100)
			return true;
		else
		{
			$this->form_validation->set_message('discount_amt_check', 'The Discount field must contain a number less than 100');
			return false;
		}
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Coupon', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cou->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['text_name'] = $this->input->get('text_name');
		$data['text_code'] = $this->input->get('text_code');
		$data['cat_filter'] = $this->input->get('cat_filter'); // sort field name
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
	function couponForm()
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
		$this->form_validation->set_rules('coupon_name','Coupon Name','trim|required');
		$this->form_validation->set_rules('coupon_code','Coupon Code','trim|required|callback_couponCode');
		$this->form_validation->set_rules('coupon_maximum_use','Coupon Maximum  Use','trim|required|numeric');
		$this->form_validation->set_rules('coupon_above_amount','Above Ammount','trim|required|numeric');
		$this->form_validation->set_rules('coupon_desc','Coupon Description','trim|required');
		$this->form_validation->set_rules('coupon_expiry_date','Coupon Expiry Date','trim|required');
		if($this->input->post('coupon_type') == 'Percent')
			$ex = '|callback_discount_amt_check';
		$this->form_validation->set_rules('coupon_discount_amt','Discount amount','required|numeric'.@$ex);
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->cou->getData();
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
				$this->cou->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->cou->saveData();
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
			$this->cou->deleteData($ids);
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
			$this->cou->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
	}
		

}