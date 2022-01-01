<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class diamond_price extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoIdD = 'diamond_price_id'; 	// primary key for diamond price table
	var $cAutoIdM = 'metal_price_id'; 	  // primary key for metal price table
	var $cPrimaryIdD = array(); 				 // array of primary id for diamond price table
	var $cPrimaryIdM = array(); 				 // array of primary id for metal price table
	var $cTableD = 'diamond_price';		// table name for diamond price
	var $cTableM = 'metal_price';		  // table name for metal price
	var $controller = 'diamond_price';
	var $is_post = false;
	var $cnt_val = 0;                     // primary key index used at vallidation of diamondPriceName function
	var $cnt_val_key = 0;                 // primary key index used in key check callback function at vallidation of diamondPriceName function
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function diamond_price()
	{
		parent::__construct();
		$this->load->model('admin/mdl_diamond_price','dp');
		$this->dp->cTableNameD = $this->cTableD;
		$this->dp->cTableNameM = $this->cTableM;
		$this->dp->cAutoIdD = $this->cAutoIdD;
		$this->dp->cAutoIdM = $this->cAutoIdM;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_idD') != '' || $this->input->post('item_idD') != '')
		{
			$priArr = $this->security->xss_clean($_REQUEST['item_idD']);
			foreach($priArr as $k=>$ar)
				$this->cPrimaryIdD[]  = $this->dp->cPrimaryIdD[] = _de($ar);
		}

		if($this->input->get('item_idM') != '' || $this->input->post('item_idM') != '')
		{
			$priArr = $this->security->xss_clean($_REQUEST['item_idM']);
			foreach($priArr as $k=>$ar)
				$this->cPrimaryIdM[]  = $this->dp->cPrimaryIdM[] = _de($ar);
		}
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
		$per =  fetchPermission('diamond_price/diamondPriceForm');
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
	
	function diamondPriceName($str)
	{
		if(isset($this->cPrimaryIdD[$this->cnt_val])  && (int)$this->cPrimaryIdD[$this->cnt_val] > 0)
			$this->db->where($this->cAutoIdD." !=",$this->cPrimaryIdD[$this->cnt_val]);
			
		$this->cnt_val++;
			
		$c = $this->db->where('diamond_price_name',$str)->get($this->cTableD)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('diamondPriceName', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}
	
	function diamondPriceKey($str)
	{
		if(isset($this->cPrimaryIdD[$this->cnt_val_key])  && (int)$this->cPrimaryIdD[$this->cnt_val_key] > 0)  //do not check on edit mode
		{
			$this->cnt_val_key++;
			return true;
		}

		$this->cnt_val_key++;
		$c = $this->db->where('diamond_price_key',$str)->get($this->cTableD)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('diamondPriceKey', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else if($str == "")
		{
			$this->form_validation->set_message('diamondPriceKey', 'The Diamond Key is required.');
			return false;
		}
		else
		return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Diamond Price', $this->cTableD, $this->cAutoIdD, 0, $logType);
		
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$this->diamondPriceForm();
		/*$num = $this->dp->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		
		$data['pageName'] = 'admin/'.$this->controller.'/diamond_price_list';
		$this->load->view('admin/layout',$data);*/

	}
	
/*
+-----------------------------------------+
	Function will save data, all parameters 
	will be in post method.
+-----------------------------------------+
*/
	function diamondPriceForm()
	{
		$logType = 'V';
		saveAdminLog('diamond_price/diamondPriceForm', 'Diamond Price', $this->cTableD, $this->cAutoIdD, 0, $logType);
		
		if($this->per_edit == 0 && $this->per_add == 0)
		{}
		else
		{
			setFlashMessage('error',getErrorMessageFromCode('01008'));
			showPermissionDenied();
		}
		
		$data = array();
		$this->form_validation->set_rules('diamond_price_name[]','Diamond Name','trim|required');
		$this->form_validation->set_rules('diamond_price_key[]','Diamond Key','trim|callback_diamondPriceKey');
		$this->form_validation->set_rules('diamond_type_id[]','Diamond Type','trim|required');
		$this->form_validation->set_rules('dp_price[]','Diamond Price','trim|required|numeric');
		$this->form_validation->set_rules('diamond_color_id[]','Diamond Color','trim|required');
		$this->form_validation->set_rules('dp_weight_diff[]','Diamond Weight Difference','trim|required|numeric');
		$this->form_validation->set_rules('dp_icon[]','Diamond Icon','trim|required');
		$this->form_validation->set_rules('metal_type_price[]','Metal Type Price','trim|required');
		$this->form_validation->set_rules('metal_type_id[]','Metal Type','trim|required');
		$this->form_validation->set_rules('metal_purity_id[]','Metal Purity','trim|required');
		$this->form_validation->set_rules('metal_color_id[]','Metal Color','trim|required');
		$this->form_validation->set_rules('mp_price_difference[]','Metal Price Difference','trim|required');
		$this->form_validation->set_rules('mp_icon[]','Metal Icon','trim|required');
		
		if($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			$dt = array();
			$dt = $this->getData();
			
			$dt['pageName'] = 'admin/'.$this->controller.'/diamond_price_form';
			$this->load->view('admin/layout',$dt);
		}
		else
		{
			$this->is_post = true;
			if($this->form_validation->run() == FALSE )
			{
				$data['error'] = $this->form_validation->get_errors();
				
				if($data['error'])
					setFlashMessage('error',getErrorMessageFromCode('01005'));
				$data['pageName'] = 'admin/'.$this->controller.'/diamond_price_form';
				$this->load->view('admin/layout',$data);
			}
			else // saving data to database
			{
				//$this->dp->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->dp->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
/*
+-----------------------------------------+
	author Cloudwebs
	Delete diamond or metal Category
+-----------------------------------------+
*/		
	function deleteCategory()
	{	
		if($this->per_delete == 0)
		{
			$this->dp->deleteCategory();
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
			$this->dp->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));	
	}

/*
+-----------------------------------------+
	author Cloudwebs
	Update metal price in master table
	@params : post array of text input of metal price
+-----------------------------------------+
*/	
	function updateMetalPrice()
	{
		$this->dp->updateMetalPrice();
	}

/*
+-----------------------------------------+
	author Cloudwebs
	Update metal price in master table
	@params : post array of text input of metal price
+-----------------------------------------+
*/	
	function updateChargeProfit()
	{
		echo $this->dp->updateChargeProfit();
	}

/*
+-----------------------------------------+
	author Cloudwebs
	Update product pricing table the function might take long time as all combination of every products will be calculated
+-----------------------------------------+
*/	
	function updateProductPrices()
	{
		$this->dp->updateProductPrices();
	}

/*
 * @author Cloudwebs
 * @abstract fetch all nedded from model
 * @return $data array 
*/
	function getData()
	{
		$dt =  array();

		$dtArr = $this->dp->getData();
		$dt['diamond_price'] = $dtArr['D']->result_array();
		$dt['metal_price'] = $dtArr['M']->result_array();
        $sql = "SELECT metal_type_id, CONCAT(metal_type_name, CONCAT('|', metal_type_price)) AS metal_type FROM metal_type WHERE metal_type_status=0";
        $dt['metal_type_price'] = getDropDownAry($sql, "metal_type_id", "metal_type", null, null);
		return $dt;
	}
}