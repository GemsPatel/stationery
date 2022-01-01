<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');
class ebay extends CI_Controller
{
	var $is_ajax = false;
	var $cAutoId = 'product_id';
	var $cPrimaryId = '';
	var $cTable = 'product';
	var $controller = 'ebay';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	var $is_post = false;
	
	//parent constructor will load model inside it
	function ebay()
	{
		parent::__construct();
		$this->load->model('admin/mdl_ebay_product','eprod');
		$this->eprod->cTableName = $this->cTable;
		$this->eprod->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->eprod->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
			
		$this->chk_permission();	
		
		$this->is_post = ($_SERVER['REQUEST_METHOD']=='POST')?true:false;
		
		$this->session->unset_userdata(array('ebay_country_id'=>''));
//		error_reporting(E_ALL);
//		ini_set("display_errors", 1);
//		$this->db->db_debug = TRUE;
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
		saveAdminLog($this->router->class, 'Ebay Product', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$num = $this->eprod->getData();
		$data = pagiationData('admin/'.$this->controller,$num->num_rows(),$start,3);
		
		$data['start'] = $start;
		$data['total_records'] = $num->num_rows();
		$data['per_page_drop'] = per_page_drop();
		$data['srt'] = $this->input->get('s'); 	// sort order
		$data['field'] = $this->input->get('f'); // sort field name
		$data['product_name_filter'] = $this->input->get('product_name_filter'); 
		$data['product_code_filter'] = $this->input->get('product_code_filter');
		$data['product_sku_filter'] = $this->input->get('product_sku_filter');
		$data['cat_filter'] = $this->input->get('cat_filter'); // filter by category name
		$data['status_filter'] = ($this->input->get('status_filter') != '')?$this->input->get('status_filter'):'-1'; // filter by status
		$data['ebay_country_id'] = ($this->input->get('ebay_country_id') != '')?$this->input->get('ebay_country_id'):'-1'; // filter by ebay site id
			
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
	
/**
 * @abstract add update delete product: accept product genererated code to identify product
 * @param $mode: 1 add, 2 update, 3 delete
 */
	function addUpdDelByGenCode( $product_generated_code='PR-550-16-C1', $mode=1 )
	{
	}
	
	//Function will update ebay title
	function saveEbayData()
	{
		$this->eprod->saveEbayData();
		
	}
	
	function deleteImagesFromEbay()
	{
		$product_price_id =$this->input->get("product_price_id");
		$ebay_products_id =$this->input->get("ebay_products_id");
		if(!empty($product_price_id) && !empty($ebay_products_id))
		{
			$this->db->where('product_price_id',$product_price_id)->where('ebay_products_id',$ebay_products_id)->delete('ebay_images');
			echo "Images has been delete successfully.";
			//setFlashMessage('success','Images has been delete successfully.');
		}
		else 
		{
			echo "Invalid input"; 
		}
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
			$this->eprod->deleteData($ids);
		}
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01009')));
	}
/*
 * Function will saved product to ebay products listing 
 */
	function ajaxAddEbayListing()
	{
		if($this->is_ajax)
		{
			//pr($_POST);
			$this->eprod->ajaxAddEbayListing();		
		}
	}
/*
* Duplicate ebay product
*/	
	function addEbayProduct()
	{
		$product_price_id = $this->input->get("product_price_id");
		$product_id = $this->input->get("product_id");
		$ebay_site_id = $this->input->get("ebay_site_id");
		
		//
		if( !empty($product_price_id) && !empty($product_price_id) )
		{
			$this->db->insert( "ebay_product", array( "product_id"=>$product_id, "product_price_id"=>$product_price_id, "ep_site_id"=>$ebay_site_id, "ep_status"=>1 ) );
			echo "Product replicated in Product table.";
		}
		else
		{
			echo "Invalid input.";
		}
	}


}