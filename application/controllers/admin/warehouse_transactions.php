<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class warehouse_transactions extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'warehouse_transactions_id';
	var $cPrimaryId = '';
	var $cTable = 'warehouse_transactions';
	var $controller = 'warehouse_transactions';
	var $per_add = 1;
	var $per_edit = 1; 
	var $per_delete = 1;
	var $per_view = 1;
	
	//parent constructor will load model inside it
	function warehouse_transactions()
	{
		parent::__construct();
		$this->load->model('admin/mdl_warehouse_transactions','cat');
		$this->cat->cTableName = $this->cTable;
		$this->cat->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->cat->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
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
	
	/**
	 * @since 
	 * @param number $start
	 */
	function index($start = 0)
	{
		/**
		 * @since 25-05-2015
		 * to show user enabled status by default
		 */
		if( !isset($_GET["status_filter"]) )
		{
			$_GET["status_filter"] = "0";
		}
		
		
		$logType = 'V';
		saveAdminLog($this->router->class, 'Warehouse Transactions', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		$num = $this->cat->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		//echo $this->db->last_query();
		
		$data['start'] = $start; //starting position of records
		$data['total_records'] = $num->num_rows(); // total num of records
		$data['per_page_drop'] = per_page_drop(); // per page dropdown
		$data['srt'] = $this->input->get('s'); // sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['product_filter'] = $this->input->get('product_filter'); // filter by category name
		$data['wt_type'] = $this->input->get('wt_type'); // filter by category name
		$data['fromDate'] = $this->input->get('fromDate');
		$data['toDate'] = $this->input->get('toDate');
		$data['status_filter'] = $this->input->get('status_filter'); // filter by status
		
		if($this->is_ajax)
		{
			$this->load->view('admin/'.$this->controller.'/ajax_html_data',$data); // this view loaded on ajax call
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
	function wtForm()
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
		if( empty($this->cPrimaryId) )
		{
			$this->form_validation->set_rules('product_id','Product','trim|required|numeric');
		}
		$this->form_validation->set_rules('wt_qty','Quantity','trim|required|numeric');
		$this->form_validation->set_rules('wt_rate','Rate','trim|required|numeric');

		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dtArr = $this->cat->getData();
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
				$this->cat->cPrimaryId = $this->cPrimaryId; // setting variable to model
				
  			 	$this->cat->saveData();
				redirect('admin/'.$this->controller);
			}
		}
		
	}
	
	/**
	 * 
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
				$res = $this->cat->importProductcode();
				setFlashMessage( $res["type"], $res["msg"]);
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
	
	/*
	 +-----------------------------------------+
	Update status for enabled/disabled
	@params : post array of ids,status
	+-----------------------------------------+
	*/
	function updateStatus()
	{
		if($this->per_edit == 0)
			$this->cat->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));
	}
}