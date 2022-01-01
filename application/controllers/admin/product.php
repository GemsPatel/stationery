<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends CI_Controller {
	
	var $is_ajax = false;
	var $cAutoId = 'product_id';
	var $cPrimaryId = '';
	var $cTable = 'product';
	var $controller = 'product';
	var $per_add = 1;
	var $per_edit = 1;
	var $per_delete = 1;
	var $per_view = 1;
	var $is_post = false;
	
	/**
	 * inventory type key 
	 */
	var $IT_KEY = "";
	
	//parent constructor will load model inside it
	function Product()
	{
		parent::__construct();
		$this->load->model('admin/mdl_product','prod');
		$this->prod->cTableName = $this->cTable;
		$this->prod->cAutoId = $this->cAutoId;
		$this->is_ajax = $this->input->is_ajax_request();
		
		if($this->input->get('item_id') != '' || $this->input->post('item_id') != '')
			$this->cPrimaryId  = $this->prod->cPrimaryId = _de($this->security->xss_clean($_REQUEST['item_id']));
			
		$this->chk_permission();	
		
		$this->is_post = ($_SERVER['REQUEST_METHOD']=='POST')?true:false;
		
		//
		$this->IT_KEY = $this->prod->IT_KEY = $this->session->userdata("IT_KEY");
		
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
//		$this->db->db_debug = TRUE;

		/**
		 * load file helper
		 */
		$this->load->helper("Custom_file");
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

	function productSKU($str)
	{
		if( MANUFACTURER_ID != 7 )
		{
			$this->db->where('manufacturer_id', MANUFACTURER_ID)->join($this->cTable.'_cctld',$this->cTable.'_cctld.product_id='.$this->cTable.'.product_id');
		}
		if($this->cPrimaryId != '')
			$this->db->where( $this->cTable. "." . $this->cAutoId." !=",$this->cPrimaryId);
			
// 		$this->cnt_val++;
			
		$c = $this->db->where('product_sku',$str)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('productSKU', 'This '.$str.' already exist in database, please try different.');
			return false;
		}
		else
		return true;
	}

/*
+--------------------------------------------------+
	function will check category alias, return error
	if alias already exist.
+--------------------------------------------------+
*/	
	function checkAlias($str)
	{
		$al  = url_title($str);
		if($this->cPrimaryId)
			$this->db->where($this->cAutoId." !=",$this->cPrimaryId);
		
		$c = $this->db->where('product_alias',$al)->get($this->cTable)->num_rows();
		if($c > 0)
		{
			$this->form_validation->set_message('checkAlias','This alias of product is already exist.');
			return false;
		}
		else
			return true;
	}
	
	function index($start = 0)
	{
		$logType = 'V';
		saveAdminLog($this->router->class, 'Product', $this->cTable, $this->cAutoId, 0, $logType);
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
		
		$num = $this->prod->getData();
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
	function imageSize($str)
	{
		/**
		 * get uploaded file size in KB
		 */
		$filesize = round($_FILES['product_image_single']['size'] / 1024);
	
		$allowedSize = getField("config_value", "configuration", "config_key","PRODUCT_IMG_SIZE");
	
		$allowedrec = getField("config_value", "configuration", "config_key","PRODUCT_REC_IMAGE");
		
		if($filesize > $allowedSize)
		{
			$this->form_validation->set_message('imageSize', '(Maximum allowed size is : '.$allowedSize.' KB,'.$allowedrec.', your uploaded file size is '.$filesize.' KB.)');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	function productForm()
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
		$this->form_validation->set_rules('product_name','Product Name','trim|required');
		
		if(MANUFACTURER_ID == 7):
			$this->form_validation->set_rules('product_alias','Product Alias','trim|required|callback_checkAlias');		
		endif;
		
		$this->form_validation->set_rules('product_sku','Product SKU','trim|required|callback_productSKU');
		$this->form_validation->set_rules('category_id','Category ','required');
		$this->form_validation->set_rules('product_image_single','Product Image','trim|callback_imageSize');	//required| removed on 14-04-2015
		
		/**
		 * the manufaturer is no longer required here. <br>
		 * for that instead branc provision is there in category and seller_id is also there for market place
		 */
		//$this->form_validation->set_rules('product_manufacturer_id','Manufacturer ','trim|required');	
		
		//$this->form_validation->set_rules('product_short_description','Short Description','trim|required');
		
		/**
		 * validation turned off on 13-03-2015, to support simple product entry
		 */
		//$this->form_validation->set_rules('product_value_height','Product Height','trim|required');
		//$this->form_validation->set_rules('product_value_width','Product Width','trim|required');
		//$this->form_validation->set_rules('product_value_weight','Product Weight','trim|required');
		
		
/*		$this->form_validation->set_rules('custom_page_title','Custom Page Title','trim|required');
		$this->form_validation->set_rules('meta_description','Category meta Description','trim|required');
		$this->form_validation->set_rules('meta_keyword','Category Meta keyword','trim|required');
		$this->form_validation->set_rules('author','Category Author Name','trim|required');
		$this->form_validation->set_rules('content_rights','Category Contents Rights','trim|required');
*/		
		
		/**
		 * validation turned off on 13-03-2015, to support simple product entry
		 */
		//$this->form_validation->set_rules('product_price','Product Price','trim|required|numeric');
		//$this->form_validation->set_rules('stock_status_id','Availability Status','trim|required');

		/**
		 * Jewelry inventory
		 */
		if( $this->session->userdata("IT_KEY") === "JW" )
		{	$this->form_validation->set_rules('mt_p','Metal Category','required');		}
		
		/**
		 * @deprecated
		 * added validation of quantity unit on 17-03-2015 for warehouse managed inventory
		 */		
// 		if( hewr_isWarehouseManaged() )
// 		{
// 			$this->form_validation->set_rules('pv_quantity_unit','Quanity Unit','required');
// 		}
		
		if($this->input->get('edit') == 'true')
		{
			$dt =  array();
			if($this->cPrimaryId != '') // if primary id then we have to fetch those primary Id data
			{
				$dt = $this->getProductData();
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
				$this->prod->cPrimaryId = $this->cPrimaryId; // setting variable to model
				$this->prod->saveData();
				redirect('admin/'.$this->controller);
			}
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
			$this->prod->deleteData($ids);
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
			$this->prod->updateStatus();
		else
			echo json_encode(array('type'=>'error','msg'=>getErrorMessageFromCode('01008')));
	}

/*
 * @author Cloudwebs
 * @abstract fetch all nedded from model
 * @return $data array 
*/
	function getProductData()
	{
		$dt =  array();

		$prArr = $this->prod->getData();
		$dt = $prArr['resP']->row_array();
		$dt["resP"] = $prArr['resP']->row_array();
		$dt['center_stone_idArr'] = $prArr['resCS'];
		$dt['side_stone1_idArr'] = $prArr['resSS1'];
		$dt['side_stone2_idArr'] = $prArr['resSS2'];
		$dt['metal_price_idArr'] = $prArr['resM'];
		$dt['product_side_stonesData'] = $prArr['product_side_stonesData'];
		return $dt;
	}
	
/*
 * @author Cloudwebs
 * @abstract fetch calculated price for diamonds and metal
 * @return $price value
*/
	function getDiaMetPrice()
	{
		echo $this->prod->getDiaMetPrice();
	}
	
/*
+-----------------------------------------+
	This Function will product information 
	downloaded and create csv/xls file.
+-----------------------------------------+
*/	
// 	function exportData()
// 	{
// 		if($this->per_view != 0)
// 		{
// 			setFlashMessage('error',getErrorMessageFromCode('01010'));
// 			showPermissionDenied();
// 		}
		
// 		$res = $this->db->get($this->cTable);
// 		$listArr = $res->result_array();
		
// 		$ext = $this->input->post($this->controller.'_export');
// 		$col= array(array_keys($listArr[0]));
// 		$col= $col[0];
// 		exportExcel($this->cTable.'_'.date('Y-m-d').'.'.$ext, $col, $listArr, $ext);
// 		die;
// 	}

/**
 * @author Cloudwebs
 * @abstract Update sort order
 * @param  $sort_order 
 * @param  $id
 */	
	function updateSortOrder()
	{
		$id = $this->input->post('id');
		$sort_order = $this->input->post('sort_order');
		
		if($id!=''  && $sort_order!='')
		{
			$this->db->where($this->cAutoId,$id)->update($this->cTable,array('product_sort_order'=>$sort_order));
			echo json_encode(array('type'=>'success','msg'=>'Sort order updated successfully.'));		
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Specify sort order.'));		
		}
	}	
	
/**
 * @author Cloudwebs
 * @abstract function will add new tabs for adding more diamond category 
 */	
	function addStoneTab()
	{
		$data['product_stone_number'] = $this->input->post('product_stone_number');
		
		if((int) $data['product_stone_number'] >= 3)
		{
			$tab = '<a href="#tab-data'.($data['product_stone_number']+2).'" style="display: inline;">Side Stone '.$data['product_stone_number'].'</a>';
			$tab_content = $this->load->view('admin/'.$this->controller.'/add_stone_tab', $data, TRUE);
			echo json_encode(array('type'=>'success', 'tab'=> $tab, 'tab_content'=>$tab_content));		
		}
	}	

/**
 * @author Cloudwebs
 * @abstract Function will randomize sort order of product inventory
 */	
	function randomSortOrder()
	{
		randomSortOrder();
		setFlashMessage('success', 'Sort order updated successfully.');
		redirect('admin/'.$this->controller);
	}	
/*
* Function will ger product price id
*/
	function getProductPriceId()
	{
		$product_code = $this->input->post('product_code');
		if($product_code!='')
		{
			echo json_encode(array('type'=>'success', 'msg'=>'Create successfully page.', 'htmlContent'=>$product_price_id ));
		}
		else
		{
			echo json_encode(array('type'=>'error','msg'=>'Please enter product code.'));
		}
	}
/*
* Create html page for used on ebay
*/	
	function ebayHtmlPage( $product_price_id=0 )
	{
		$product_code = trim( $this->input->get('product_code') );
		
		$htmlContent = ebayHtmlPage( $product_code, $product_price_id, true );
		
		if( !empty( $htmlContent ) )
		{
			//load jquery
			echo '<script type="text/javascript" src="'.asset_url("js/admin/jquery/jquery-1.7.1.min.js").'"></script>';
			
			//textarea for html code
			echo '<textarea id="ebayHtml" name="ebayHtml" rows="10" cols="100">'.$htmlContent.'</textarea>';
			
			echo "<br><br><br>"; 
			
			//view preview
			echo $htmlContent;
			
			//select text within textarea
			echo '<script type="text/javascript">$(document).ready(function(){$(\'#ebayHtml\').select();});</script>';
		}
		else
		{
			echo " <h6>Invalid Input, specify product generated code properly.</h6> ";
		}
		
	}

	function checkFoldeStructure()
	{
		$product_id = $this->input->get( "id" ); 
		if( MANUFACTURER_ID == 7 )
			$product_price_id = exeQuery( " SELECT product_price_id FROM product_price WHERE product_id=".$product_id." AND product_price_status=0 LIMIT 1 ", true, "product_price_id" ) ;
		else
			$product_price_id = exeQuery( " SELECT product_price_id FROM product_price WHERE product_price_id IN (select product_price_id from product_price_cctld WHERE product_price_status=0 AND manufacturer_id=".MANUFACTURER_ID." ) AND product_id=".$product_id." LIMIT 1 ", true, "product_price_id" ) ;
		
		$this->ebayHtmlPage($product_price_id);
	}
	
	/*
	 * Function will list item listing with languages in reference.
	*/
	function itemLanguages()
	{
		if($this->input->get('edit') == 'true')
		{
			$data['listArr'] = $this->prod->getLanguagesForListing();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_sl';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
	
	/*
	 * Function will list item listing with languages in reference.
	*/
	function inventoryType()
	{
		if($this->input->get('insert') == 'true')
		{
			$data['listArr'] = $this->prod->getInventoryListing();
			$data['pageName'] = 'admin/'.$this->controller.'/'.$this->controller.'_list_it';
			$this->load->view('admin/layout',$data);
		}
		else
			redirect('admin/'.$this->controller);
	}
	
	//Function send product newsletter
	function productSendNewsletter()
	{
		if($this->input->post())
		{
			$data['email_address'] = $this->input->post('toEmail');
			$data['product_email_message'] = $this->input->post('toMsg');
			$data['email_list_id'] = getField("email_list_id", "email_list", "email_id", $data['email_address']);//added on 04-04-2016 used to unsubscribe email id
			
			$subject = $this->input->post('toSubject');
			
			$mail_body = $this->load->view('templates/product-newsletter', $data, TRUE);
			$mail_body .= $this->load->view('templates/footer-template',array( 'email_list_id'=>$data['email_list_id'],'email_id'=>$data['email_address'] ), TRUE);
			sendMail($data['email_address'], $subject, $mail_body);
			
			echo json_encode(array('type'=>'success','msg'=>'Email successfully send.'));	
		}
		else
			echo json_encode(array('type'=>'error','msg'=>'Please enter product email details'));
	}
	
	/********************************* Export-Import functions ************************************/
	
	/**
	 *	This Function will export product information
	 *  downloaded and create csv/xls file.
	 */
	function exportData()
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
	
		$this->prod->exportData();
	}
	
	/**
	 *	This Function will export product information
	 downloaded and create csv/xls file.
	 */
	function exportDataSample()
	{
		if($this->per_view != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01010'));
			showPermissionDenied();
		}
	
		$this->prod->exportDataSample();
	}
	
	/**
	 +-----------------------------------------+
	 function for import data from csv file and
	 insert into pincode table
	 format:: pincode,areaname,cityname,state_id
	 +-----------------------------------------+
	 */
	function importData()
	{
		if($this->per_edit != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01008'));
			showPermissionDenied();
		}
	
		if($this->per_add != 0)
		{
			setFlashMessage('error',getErrorMessageFromCode('01007'));
			showPermissionDenied();
		}
	
	
		if(isset($_FILES['import_csv']['name']))
		{
			$name = $_FILES['import_csv']['name'];
			$pos  = strpos($name,".");
			$type = strtoupper(substr($name,$pos+1));
	
			if($type=='CSV' || $type=='XML')
			{
				$this->prod->importData( $this );
				setFlashMessage('success','File is Imported  successfully.');
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
	
	/**
	 *
	 */
	function importDataProcess()
	{
		$path = $this->input->get("path");
		$start = $this->input->get("start");
		$this->prod->importDataProcess( $path, $start );
	}
	
	/********************************* Export-Import functions end *********************************/
}
