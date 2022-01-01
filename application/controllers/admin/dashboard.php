<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class dashboard extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'category_id';
	var $cTable = 'product_categories';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	var $controller = 'dashboard';
	
	//parent constructor will load model inside it
	function dashboard()
	{
		parent::__construct();
		//$this->load->model('admin/mdl_category','cat');
		//$this->cat->cTableName = $this->cTable;
		//$this->cat->cAutoId = $this->cAutoId;
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

	function index()
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'DashBoard', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		//common overview part
		$data['total_sales'] = $this->db->where('manufacturer_id',MANUFACTURER_ID)->select_sum('order_total_amt')->get('orders')->row_array();
		$whereDate = 'YEAR(order_created_date) BETWEEN '.date('Y').' AND '.date('Y').' ';
		$data['total_sales_year'] = $this->db->select_sum('order_total_amt')->where('manufacturer_id',MANUFACTURER_ID)->where($whereDate)->get('orders')->row_array();
		$data['total_orders'] = $this->db->where('manufacturer_id',MANUFACTURER_ID)->count_all_results('orders');
		$data['total_customers'] = $this->db->where('manufacturer_id',MANUFACTURER_ID)->count_all_results('customer');
		$data['customer_await_approval'] = $this->db->where('manufacturer_id',MANUFACTURER_ID)->where('customer_approved','0')->count_all_results('customer');
				
		//top 5 search terms part
		$this->db->select("*,(SELECT COUNT(search_terms_keywords) FROM search_terms WHERE search_terms_keywords=s.search_terms_keywords) as 'top5search'");
		$this->db->group_by('search_terms_keywords')->order_by('top5search','DESC')->limit(5);
		$data['top_search_terms'] = $this->db->get("search_terms s")->result_array();
		
		//Latest 10 orders
		$this->db->select('ord.order_id,ord.order_created_date,ord.order_total_amt,c.customer_id,c.customer_firstname,c.customer_lastname,c.customer_emailid,c.customer_phoneno,pm.payment_method_name');
		$this->db->join('customer c','c.customer_id=ord.customer_id','LEFT');
		$this->db->join('payment_method pm','pm.payment_method_id=ord.payment_method_id','LEFT');
		$this->db->where('ord.del_in', 0);
		$data['latest_ten_orders'] = $this->db->order_by('order_id','DESC')->limit(10)->get('orders ord')->result_array();
		
		//pr($data);
		$data['pageName'] = 'admin/dashboard/index';
		$this->load->view('admin/layout',$data);
	}

}