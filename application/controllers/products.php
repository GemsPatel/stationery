<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * @author Owner
 * 
 * This controller will serve all product related contents like CATEGORY_PAGES, product LISTING_OR_SEARCH result pages, PRODUCT_DETAIL pages. 
 * 
 * URL format above three kind of pages are as per below.   
 *
 * CATEGORY_PAGES: DOMAIN/category_main/category_sub/category_sub/..
 * LISTING_OR_SEARCH: DOMAIN/category_main/category_sub/category_sub/../search_term if applicable.html
 * PRODUCT_DETAIL: DOMAIN/category_main/category_sub/category_sub/../product_alias-product_price_id
 */
class products extends CI_Controller 
{

	var $cat_alias = '';
	var $menu_id = '';
	var $product_price_id=0;
	var $controller = 'products';
	var $start = 0; //pagination variable
	var $cz = '';
	var $is_ajax = false;
	
	/**
	 * parent constructor will load model inside it
	 */
	function products()
	{
		parent::__construct();
		$this->load->model('mdl_products','jew');
		$segArr = $this->uri->segment_array();
		$this->is_ajax = $this->input->is_ajax_request();
		
		$this->menu_id = end($segArr);
		
		$this->cat_alias = $segArr;
		
// 		if( isIntranetIp() )
// 		{
// 			error_reporting(E_ALL);
// 			ini_set("display_errors", 1);
// 			$this->db->db_debug = TRUE;
// 		}
		
		//moved below code to CI_Controller class constructor
		//cache driver
		//$this->load->driver( 'cache', array( 'adapter' => 'apc', 'backup' => 'file'));
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
	
	function index()
	{
		$this->urlDecodeAndRedirect();
	}

	/**
	 * 
	 */
	function urlDecodeAndRedirect()
	{
		/**
		 * LISTING_OR_SEARCH page
		 */
		if( strpos( $this->menu_id, ".html" ) !== FALSE ) 
		{
			$this->productCategoryPageSupportForNonCatPageRequirement(); 
			return $this->search(); 
		}
		else 
		{
			$tempA = explode( "-", $this->menu_id );
			if( sizeof($tempA) > 1 )
			{
				/**
				 * BUG 290: need to add support for detection of product's canonical URL
				 */
				if( is_numeric( end($tempA) ) )
				{
					$this->menu_id = end($tempA); 
					/**
					 * PRODUCT_DETAIL page
					 */
					return $this->showProductsDetails(); 
				}
			}
				
				
			/**
			 * BUG 290 resolution
			 * check if canonical URL
			 */
			if( checkIfRowExist( "SELECT 1 FROM product WHERE product_alias='".$this->menu_id."' " ) )
			{
				/**
				 * PRODUCT_DETAIL page
				 */
				return $this->showProductsDetails();
			}
				

			/**
			 * check if category page then take to category page. 
			 * CATEGORY_PAGES page
			 */
			if( getSysConfig( "IS_CAT_PAGES" ) )
			{
				return $this->productCategoryPage();
			}
			
			/**
			 * default 404 if url does not match to any. 
			 */
			redirect( "my404" ); 
		}
	}

	/**
	 * Function will load all configuration that is due, and can't be done in since productCategoryPage
	 * is not called due installation not require it. 
	 */
	function productCategoryPageSupportForNonCatPageRequirement()
	{

		/**
		 * do inventory and so it's filter related initialization, 
		 * but only if multiple inventories are supported.
		 */
		if( INVENTORY_TYPE_ID == 0 )
		{
			/**
			 * check if request is for category page then do necessary configuration for same
			 */
			if( isset( $_SERVER["REQUEST_URI"] ) )
			{
				/**
				 * if it's a categpry alias then set inventory type of this category in inventory session
				 */
				$data = $this->jew->getCategory( getSeoUrl( $_SERVER["REQUEST_URI"] ) );
				if( !isEmptyArr( $data ) )
				{
					if( !isset( $data["inventory_type_id"] ) )
					{
						$data["inventory_type_id"] = getField( "inventory_type_id" , "product_categories", "category_id", $data["category_id"]);
					}
			
					/**
					 * set category's inventory in session to show it's filter
					 */
					he_front_end_hlp_loadCatalogNavigationInventory( $data["inventory_type_id"] );
			
					// 				if( isIntranetIp() )
						// 				{
						// 					echo "called got here<br>";
						// 					echo $this->session->userdata( "IT_KEY" );
						// 					die;
						// 				}
				}
			}
		}
		
	}
	
	/**
	 * Function will display main category
	 */
	function productCategoryPage()
	{
		/**
		 * redirect for client which does not support category pages
		 */
		if( CLIENT == "STATIONERY" )
		{
			setFlashMessage("error", "Page not found."); 
			redirect(); 
		}
		
		$data = $this->jew->getCategory( $this->cat_alias[0] );
		if(@$data['category_id'])
		{
			/**
			 * set category's inventory in session to show it's filter
			 */
			he_front_end_hlp_loadCatalogNavigationInventory( $data["inventory_type_id"] ); 
			
			$data['subCatArr'] = $this->jew->getSubCategory($data['category_id']);
			$data['pageName'] = 'Products';
			$data['where'] = ' WHERE front_menu_id='.$this->menu_id.'';
			$this->load->view('site-layout',$data);					
		}
	}
	
/**
 * function will display product details
 */
	function showProductsDetails()
	{
		cmn_vw_showProductsDetails($this->menu_id);	
	}
	
	function productsDetails()
	{
		$data['pageName'] = 'products-details';
		$this->load->view('site-layout',$data);
	}

	function search()
	{
		if( strpos( $this->menu_id, ".html" ) !== FALSE )
		{
			$this->productCategoryPageSupportForNonCatPageRequirement();
		}

		$this->productListing(true);
	}

/**
 * @author Cloudwebs
 * @abstract function will fetch related product and display listings 
 * $param $is_search bool if true then product fetched as per search inputs else as per sub category id
 * @param $call_no is used to recursively call one if no results found and there is keyword that can be breaked by space
 *	
 */
	function productListing( $is_search, $is_wild_search=false, $call_no=0 )
	{
		cmn_vw_productListing( $is_search, $is_wild_search, $call_no, $this->cz ); 
	}

/**
 * @author Cloudwebs
 * @abstract scroll pagination on front side liting page
 */
	function scrollPagination()
	{
		cmn_vw_scrollPagination($this); 
	}


	function ring_size()
	{
		$type = $this->input->post('rtype');
		echo $this->jew->fetchRingSize($type);	
	}

	function fetchProductDetailsAjax()
	{
		cmn_vw_fetchProductDetailsAjax(); 
	}
	
	function readyToShip()
	{
		$data = array();
		getSearchParam( $data );
		
		$num = $this->jew->getProducts('','','',true);
		$data['listArr'] = $num['data']['result_array'];
		
		$seoArr = getCmsPages('READY_TO_SHIP');
		
		$data['total_records'] = $num['data']['Count'];
		$data['custom_page_title'] = strReplaceIndToAus(@$seoArr['custom_page_title']);
		$data['meta_description'] = strReplaceIndToAus(@$seoArr['meta_description']);
		$data['meta_keyword'] = strReplaceIndToAus(@$seoArr['meta_keyword']);
		$data['pageName'] = 'ready-to-ship';
		$data['sort_by'] = $this->input->get('sort_by');
		
		//randomize the result
		if( @$data['sort_by'] == '')
		{
			shuffle( $data['listArr'] );
			//set session if sorting is used: sort_by_ready_to_ship
			$this->session->set_userdata( array('sort_by_ready_to_ship'=>'' ) );	
		}
		else
		{
			//set session if sorting is used: sort_by_ready_to_ship
			$this->session->set_userdata( array('sort_by_ready_to_ship'=>$data['sort_by'] ) );	
		}

		$this->load->view('site-layout',$data);
	}

	function parseSku( $digit )
	{
		if( strlen($digit) == 1 )
			return "00".$digit;
		else if( strlen($digit) == 2 )
			return "0".$digit;
		else if( strlen($digit) >= 3 )
			return $digit;
		else 
			return false;	
	}

    function test3()
	{
		die;
		$product_id = '';
		$sku = '';
		$res = executeQuery("SELECT p.product_id,p.product_sku FROM product p WHERE product_accessories <>'RIN' AND product_accessories <>'COU' AND product_accessories <>'SOL' GROUP BY p.product_id");						   
		if(!empty($res))
		{
			 foreach($res as $key =>$val)
			 {
				 echo $val['product_id']."<br>";
				 $this->db->query("UPDATE product SET ring_size_region='N' WHERE product_id=".$val['product_id']." ");
				//echo $sku." updated <br>";
			 }
		}
	}
		
	function test()
	{
		die;
		$csvRowArr = readCsvNew( 'assets/import/tow_tone.csv' );
		$masterKey = $csvRowArr[0];
		$size = sizeof($csvRowArr);
		
		$sku = ''; $cnt=0;
		for( $i=1; $i<$size; $i++ )
		{
			
			for($j=0; $j<5; $j++)
			{
				if( $j == 0 && !empty($csvRowArr[$i][$j]))
				{
					$sku = 'PR-'.$this->parseSku($csvRowArr[$i][$j]);	
				}
				else if( $j == 1 && !empty($csvRowArr[$i][$j]))
				{
					$sku = 'PER-'.$this->parseSku($csvRowArr[$i][$j]);	
				}
				else if( $j == 2 && !empty($csvRowArr[$i][$j]))
				{
					$sku = 'PPD-'.$this->parseSku($csvRowArr[$i][$j]);	
				}
				else if( $j == 3 && !empty($csvRowArr[$i][$j]))
				{
					$sku = 'PTN-'.$this->parseSku($csvRowArr[$i][$j]);	
				}
				else if( $j == 4 && !empty($csvRowArr[$i][$j]))
				{
					$sku = 'PBG-'.$this->parseSku($csvRowArr[$i][$j]);	
				}
				else
				{
					$sku = '';	
				}
				
				if(!empty($sku)) { $cnt++;}
				
				$product_id = exeQuery(" SELECT product_id FROM product WHERE product_sku='".$sku."' ", true , 'product_id');
				if( !empty($product_id) )
				{
					//$this->db->query("UPDATE product SET category_id= CONCAT(category_id, '|269') WHERE product_sku='".$sku."' ");
					//$this->db->insert("product_category_map", array( 'product_id' => $product_id, 'category_id'=>269 ));
					//echo $sku." updated <br>";
				}
				else
				{
					//echo "SKU:".$sku." not found<br>";
				}
			}
			
		}
		echo 'Total '.$cnt.' products';

		die;
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(36000);
		}

		$db['second']['hostname'] = 'localhost';
		$db['second']['username'] = 'root';
		$db['second']['password'] = '';
		$db['second']['database'] = 'testq';
		$db['second']['dbdriver'] = 'mysql';
		$db['second']['dbprefix'] = '';
		$db['second']['pconnect'] = TRUE;
		$db['second']['db_debug'] = TRUE;
		$db['second']['cache_on'] = FALSE;
		$db['second']['cachedir'] = '';
		$db['second']['char_set'] = 'utf8';
		$db['second']['dbcollat'] = 'utf8_general_ci';
		$db['second']['swap_pre'] = '';
		$db['second']['autoinit'] = TRUE;
		$db['second']['stricton'] = FALSE;

		$second = $this->load->database( $db['second'] , TRUE );
		
		$res = $second->query("SELECT MovieID, Count(*) as 'Count' FROM download1 GROUP BY MovieID ORDER BY Count DESC")->result_array();
		
		foreach($res as $k=>$ar)
		{
			$second->query("UPDATE download1 SET MovieCount=".$ar['Count']." WHERE MovieID=".$ar['MovieID']." ");
		}
		
	}
	
	function test1()
	{die;
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(36000);
		}
		
		$resPin = $this->db->query("SELECT state_id, state_name FROM state ")->result_array();

		if(!empty($resPin))
		{
			foreach($resPin as $key=>$val)
			{
				$this->db->query("UPDATE state SET state_key='".strtoupper( mysql_real_escape_string( $val['state_name'] ) )."' WHERE state_id=".$val['state_id']." ");
			}
		}
		
		echo 'All operation completed';
	}

	function test2()
	{ 
		/*$res = $this->db->query("SELECT DISTINCT category_id,  category_alias FROM product_categories pc 
						  INNER JOIN front_menu fm 
						  ON (fm.front_menu_primary_id=pc.category_id AND fm.front_menu_table_name='product_categories')
						  WHERE ( category_id IN (2, 7, 43, 50, 56, 73) OR parent_id IN ( 2, 7, 43, 50, 56, 73) ) AND fm_status=0 ORDER BY category_id ")->result_array(); */
		
		/*$res = $this->db->query("SELECT DISTINCT diamond_price_id,  diamond_price_name FROM diamond_price dp 
						  WHERE dp_rapnet_lot_no=0 ")->result_array();*/

		
		$res = $this->db->query("SELECT p.product_id FROM product_price pp INNER JOIN product p ON (p.product_id=pp.product_id AND p.product_metal_priority_id=pp.metal_price_id AND p.product_cs_priority_id=pp.cs_diamond_price_id AND p.product_ss1_priority_id=pp.ss1_diamond_price_id AND p.product_ss2_priority_id=pp.ss2_diamond_price_id) LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id WHERE product_price_status=0 AND p.product_status=0 AND ( pp.diamond_type_id_cs=1 OR pp.diamond_type_id_ss1=1 OR pp.diamond_type_id_ss2=1 OR ppim.diamond_type_id=1 ) GROUP BY product_id ORDER BY product_sort_order")->result_array();
		
		foreach($res as $k=>$ar)
		{
			//echo "'".$ar['category_alias']."'=>".$ar['category_id'].", ";	
			//echo "'".str_replace( " ", "-", $ar['diamond_price_name'])."'=>".$ar['diamond_price_id'].", ";	
			echo $ar['product_id'].", ";
		}
	}
	
	function generateRandomString($len=TEST, $str='') 
	{
    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
	}

	function insert_pin()
	{
		die;
		$catArr = array(0=>2, 
						1=>33, 
						2=> 35, 
						3=> 36, 
						4=> 37, 
						5=>6, 
						6=> 34, 
						7=> 78);
		
		$res = $this->db->query("SELECT p.product_id, p.category_id FROM product p INNER JOIN product_category_map pcm 
						  ON pcm.product_id = p.product_id 
						  WHERE pcm.category_id=2 
						  GROUP BY p.product_id")->result_array();
		
		if(!empty($res))
		{
			foreach($res as $k=>$ar)
			{
				//echo ' product ID: '.$ar['product_id'].' with '.$ar['category_id'].'<br><br>';				  
				$catgory_idArr = explode('|', $ar['category_id']);
				
				//pr($catgory_idArr).'<br><br>';				  
				foreach($catgory_idArr as $key=>$val)
				{
					if(!in_array($val, $catArr))	
					{
						unset($catgory_idArr[$key]);	
					}
				}

				$this->db->query("DELETE FROM product_category_map WHERE product_id=".$ar['product_id']." ");
								 
				foreach($catgory_idArr as $key=>$val)
				{
					$this->db->query("INSERT INTO product_category_map values(".$ar['product_id'].", ".$val.") ");
					//echo $val.' Inserted in map <br><br>';
				}
								  
				//echo implode('|', $catgory_idArr).' category updated <br><br>'; if($k >= 10) die;
				$this->db->query("UPDATE product SET category_id='". implode('|', $catgory_idArr) ."' WHERE product_id=".$ar['product_id']." ");
			}
			
			echo 'All operations completed';
		}
		else
		{
			echo 'No result found.';
		}
		
	}

	function expressDeliveryTest()
	{
		$data['pageName'] = 'express-delivery';
		$this->load->view('site-layout',$data);
	}

/**
 * @author Cloudwebs
 * @abstract function will display product detail on hover of image
 */
	function hoverDetail()
	{
		$product_price_id = $this->input->get('val');
		
		if((int)$product_price_id != 0)
		{
			$data = showProductsDetails( $product_price_id, false, true, true, '', 0);
			
			echo $this->load->view('elements/hover_details', $data);
		}
		else
		{
			echo 'Something wrong happen.';	
		}
	}

/**
 * @author Cloudwebs
 * @abstract function will display compared Products with details
 */
	function compareProducts()
	{
		$compare_listArr = $this->input->get('compare_list');
		$data = array();
		
		if(isset($compare_listArr) && is_array($compare_listArr) && sizeof($compare_listArr)>0)
		{
			foreach($compare_listArr as $ar)
			{
				$data['list_arr'][$ar] = showProductsDetails( $ar, false, false, true, '', 0);
			}
		}
		
		$data['pageName'] = 'product_compare';
		$this->load->view('site-layout',$data);
	}
	
/**
 * Email to friend
 */
	function emailToFriend()
	{
		if($this->is_ajax)
		{
			$data = array();
			if($_POST)
			{
				$this->form_validation->set_rules('es_to_emails','To Email','trim|required|valid_email');		
				$this->form_validation->set_rules('es_from_emails','From Email','trim|required|valid_email');
				$this->form_validation->set_rules('es_subject','Subject','trim|required');
				if($this->form_validation->run() == FALSE)
				{
					$data = $this->form_validation->get_errors();
					echo json_encode($data);
					die;
				}
				else 
				{
					$this->jew->saveEmailToFriend();
					$data['success'] = 1;
					echo json_encode($data);
					die;
				}
			}
		}
		else
			redirect(site_url());
		
		$product_price_id = $this->input->get('pid');
		$resProd = $this->db->query('SELECT product_name FROM product p INNER JOIN product_price pp
									ON pp.product_id=p.product_id WHERE pp.product_price_id='.(int)$product_price_id.' ')->row_array();
		
		$data['es_subject'] = $resProd['product_name'];
		$data['pid'] = $product_price_id;
		$this->load->view('email_to_friend_popup', $data);
	}
	
/**
 * @author Cloudwebs
 * @abstract function will display pop up when some tries to close page 
 */
	function pageClosePopup()
	{
		//record customer behaviour in customer_interaction table
		$data['customer_id'] = (int)$this->session->userdata( 'customer_id' );
		$data['ci_interaction_type'] = 'PAGE_CLOSE_POPUP';
		$data['ci_forward_link'] = $_SERVER['HTTP_REFERER'];
		$this->db->insert('customer_interaction', $data);
		$data['customer_interaction_id'] = $this->db->insert_id();
	
		$data['input'] = $this->input->post();

		//change Note: pageToken is added in functionality to tackel uniqueness issue of same session pages
		$pid = $data['input']['pid'];		//pageToken
		$ring_size_id = $data['input']['ring_size_id'];
	
		$codeArr = $this->session->userdata('codeArr_'.$pid);
	
		$resArr = getProductVariant( $codeArr );
	
		foreach( $resArr as $k=>$ar )
		{
			$data['prod_data'][ $ar['product_price_id'] ] = showProductsDetails( $ar['product_price_id'], true, false, false, '', $ring_size_id );					
		}

		$this->load->view('page_close_popup', $data);
	}
/*
* Function will display new year gifts
*/	
	function newYearGifts()
	{
		$this->cat_alias = array();
		$this->cat_alias[0] = 'products/new-year-gifts';
		$this->cat_alias[1] = $this->menu_id = 65;
		$data = $this->jew->getCategory($this->cat_alias[0]);
		$data['subCatArr'] = $this->jew->getSubCategory($data['category_id']);
		$data['pageName'] = 'products';
		$data['where'] = ' WHERE front_menu_id='.$this->menu_id.' ';
		$this->load->view('site-layout',$data);					
	}

/*
* Function will display valentine gifts page
*/	
	function valentineGifts()
	{
		$data = array();
		getSearchParam( $data );

		$data['searchf']['product_categories'][] = 263;
		
		$num = $this->jew->getProducts( '', $data['searchf'], '', false, false, true);
		$data['listArr'] = $num['data']->result_array();
		
		//fetch total records 
		$resCnt = $this->db->query("SELECT FOUND_ROWS( ) as 'Count'")->row_array();
		
		$resCat = $this->db->query("SELECT * FROM product_categories WHERE category_id=263 ")->row_array();
		$data['total_records'] = $resCnt['Count'];
		$data['custom_page_title'] = $resCat['custom_page_title'];
		$data['meta_keyword'] = $resCat['meta_keyword'];
		$data['meta_description'] = $resCat['meta_description'];
		$data['pageName'] = 'valentine-gifts';
		$data['sort_by'] = $this->input->get('sort_by');
		
		//randomize the result
		if( @$data['sort_by'] == '')
		{
			shuffle( $data['listArr'] );
			//set session if sorting is used: sort_by_ready_to_ship
			$this->session->set_userdata( array('sort_by_valentine_gifts'=>'' ) );	
		}
		else
		{
			//set session if sorting is used: sort_by_ready_to_ship
			$this->session->set_userdata( array('sort_by_valentine_gifts'=>$data['sort_by'] ) );	
		}

		$this->load->view('site-layout',$data);
	}
	
	/* Function will compare size products */
	function compareSizePopup()
	{
		if($this->is_ajax)
		{
			$this->load->view('compare_size_popup');
		}
		else
			redirect(site_url());
	}
	
}
