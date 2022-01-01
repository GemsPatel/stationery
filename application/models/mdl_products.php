<?php
class mdl_products extends CI_Model
{
	var $cTableName = '';
	function __construct()
    {
         parent::__construct();
		 $this->cTableName = ( MANUFACTURER_ID != 7 ) ? 'product_categories_cctld' : 'product_categories' ; 
    }
/*
+-----------------------------------------+
	Function will fetch category details from database
	@cat_alias = category alias
+-----------------------------------------+
*/
	function getCategory($cat_alias='')
	{
		//$cTableName = ( MANUFACTURER_ID != 7 ) ? 'product_categories_cctld' : 'product_categories' ; 		
		if(empty($cat_alias))
			return '';
		else
		{
			$res = $this->db->where('category_alias',$cat_alias)
				->get($this->cTableName)
				->row_array();
			
			//echo $this->db->last_query();
			return $res;
		}
	}
/*
+-----------------------------------------+
	Function will fetch category id wise 
	category details from database
	@cat_id = category id
+-----------------------------------------+
*/	
	function getSubCategory($cat_id='')
	{
		if(!$cat_id)
		{
			return '';
		}
		else	
		{
			$front_hook_alias = $this->uri->segment(1);
			if( $front_hook_alias != 'products'  ) { $front_hook_alias = 'products'; }
			
			$menu_type_id = getField('front_menu_type_id','front_menu','front_menu_id',$this->menu_id);
			
			if( MANUFACTURER_ID == 7 )
			{
				$last_query = " SELECT front_menu_id,category_id,category_name,category_alias,category_image,category_description 
						FROM ".$this->cTableName." JOIN front_menu 
						ON front_menu.front_menu_primary_id=".$this->cTableName.".category_id 
						WHERE fm_status=0 
						AND parent_id=".$cat_id." 
						AND category_status=0 
						AND front_hook_alias='".$front_hook_alias."' 
						AND front_menu_type_id=".$menu_type_id."
						ORDER BY category_sort_order ";			
			}
			else 
			{
				$last_query = " SELECT front_menu.front_menu_id, prcc.category_id, prcc.category_name, prcc.category_alias, prcc.category_image, prcc.category_description 
						FROM ".$this->cTableName." prcc 
						JOIN front_menu ON ( prcc.manufacturer_id=".MANUFACTURER_ID." AND front_menu.front_menu_primary_id=prcc.category_id )
						JOIN front_menu_cctld ON ( front_menu_cctld.front_menu_id=front_menu.front_menu_id )
						WHERE fm_parent_id in
						(select front_menu.front_menu_id from front_menu 
						JOIN front_menu_cctld ON front_menu_cctld.front_menu_id=front_menu.front_menu_id AND front_menu_cctld.fm_status=0 AND front_menu_primary_id=".$cat_id."
						)
						AND front_menu_cctld.fm_status=0
						AND prcc.category_status=0 
						AND front_hook_alias='".$front_hook_alias."' 
						AND front_menu_type_id=".$menu_type_id." 
						ORDER BY category_sort_order ";	
			}
			
			//caching database result
	 		$qry_id = queryId( $last_query, 'c' ); 
			$res;
			if ( ! $res = $this->cache->get( $qry_id ) )
			{
				$res = $this->db->query( $last_query )->result_array();

//				if( $_SERVER['REMOTE_ADDR'] == '123.201.171.25' )
//				{
//					echo $this->db->last_query(); die;
//				}
				saveCacheKey( $qry_id, 'category'); 				

				// Save into the cache for infinite time 
				$this->cache->save( $qry_id, $res, 0);
			}
			/*******caching end *******/
			
			return $res;
		}
	}
	
/*
+-----------------------------------------+
	Function will fetch products as per searching
	product details from database
	@cat_id = category id
	@call_no if parametr is vlaue is one and there is keyword to search then explode keyword by space and search again
+-----------------------------------------+
*/	
	function getProducts( $cat_id='', $searchf='', $is_wild_search='', $is_ready_to_ship=false, $is_solitaire=false, $is_valentine_gifts=false, $call_no=0)
	{
		$cz_mount_prefix = '';
		if( $is_solitaire )	{ $cz_mount_prefix = '_mount'; } 
		else if( isset($searchf['cz']) ) { $cz_mount_prefix = '_cz'; }

		$search_code = "";
		$price_tag = "";
		$gender_filter_tag = "";
		$prod_cat_tag = "";
		$metal_tag = "";
		$metal_type_tag = "";
		$metal_color_tag = "";
		$metal_purity_tag = "";
		$stone_cat_tag = "";
		$product_offer_tag = "";
		$diamond_shape_tag = "";
		$diamond_type_tag = "";
		$diamond_color_tag = "";
		$diamond_purity_tag = "";
		$product_categories_tag = "";
		$diamond_price_tag = "";
		$cz_tag="";
		/**
		 * added on 18-03-2015 to support dynamic inventory
		 */
		$product_attribute_tag = ""; 

		$price_url_tag = "";
		$gender_filter_url_tag = "";
		$prod_cat_url_tag = "";
		$metal_url_tag = "";
		$metal_type_url_tag = "";
		$metal_color_url_tag = "";
		$metal_purity_url_tag = "";
		$stone_cat_url_tag = "";
		$product_offer_url_tag = "";
		$diamond_shape_url_tag = "";
		$diamond_type_url_tag = "";
		$diamond_color_url_tag = "";
		$diamond_purity_url_tag = "";
		$product_categories_url_tag = "";
		$diamond_price_url_tag = "";
		$cz_url_tag="";
		/**
		 * added on 18-03-2015 to support dynamic inventory
		 */
		$product_attribute_url_tag = "";
		
	
		//keyword search tags
		$keyword_search_tag = "";
		
		//if sorting option is selected then apply sorting
		$sort_by = $this->input->get('sort_by');
		$sort_by_tag ="";
		
		//query variables 
		$select=$join=$where=$group_by=$order_by=$limit='';
		
		//to enable use of SQL_CALC_FOUND_ROWS
		//ini_set("mysql.trace_mode", "0"); moved to php.ini as default
		
		/**
		 * specifies which table are already joined so that we have idea of joined table while building dynamic sql
		 */
		$table_joined = array(0=>'product_price');
						  
		if( MANUFACTURER_ID == 7 )			
		{
			$select = "SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, p.product_name, p.product_alias, p.product_angle_in, 
							  p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, pp.product_generated_code_info,  
							  pp.product_price_calculated_price".$cz_mount_prefix." as product_price_calculated_price, 
							  pp.product_discount".$cz_mount_prefix." as product_discount, 
							  pp.product_discounted_price".$cz_mount_prefix." as product_discounted_price, p.product_sku ";

			$where = "WHERE pp.product_price_status=0 AND p.product_status=0 ";	
			$join = "FROM product p INNER JOIN product_price pp ON pp.product_id=p.product_id ";	
		}
		else 
		{
			$table_joined[] = "product_cctld"; $table_joined[] = "product_price_cctld";

			/*$select = "SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, pc.product_name, p.product_alias, p.product_angle_in, 
							  p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, pp.product_generated_code_info,  
							  ppc.product_price_calculated_price".$cz_mount_prefix." as product_price_calculated_price, 
							  ppc.product_discount".$cz_mount_prefix." as product_discount, 
							  ppc.product_discounted_price".$cz_mount_prefix." as product_discounted_price, p.product_sku ";
							  
			$where = "WHERE ppc.product_price_status=0 AND pc.product_status=0 ";	
			$join = "FROM product p INNER JOIN product_cctld pc ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND pc.product_id=p.product_id )
					 INNER JOIN product_price pp ON pp.product_id=p.product_id 
					 INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id ) ";			  
			*/
					 
			$select = "SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, pc.product_name, p.product_alias, p.product_angle_in, 
							  p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, pp.product_generated_code_info,  
							  pp.product_price_calculated_price".$cz_mount_prefix." as product_price_calculated_price, 
							  pp.product_discount".$cz_mount_prefix." as product_discount, 
							  pp.product_discounted_price".$cz_mount_prefix." as product_discounted_price, p.product_sku ";

			$where = "WHERE pp.product_price_status=0 AND p.product_status=0 ";	
			
			$join = "FROM product p INNER JOIN product_cctld pc ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND pc.product_id=p.product_id )
					 INNER JOIN product_price pp ON pp.product_id=p.product_id 
					  "; 
		}
		
		$group_by=" GROUP BY product_id ";
		$order_by=" ORDER BY product_sort_order ";

		//search filter rendering
		if(!empty($searchf))
		{
			//search for only specified diamond and metal if not explicitly specified by user
			$is_exist_metal_color_purity = array_key_exists('metal_color_purity', $searchf);
			$is_exist_diamond_price = FALSE ;
			foreach( $searchf as $k=>$ar) { if( strpos(" ".$k, "diamond_price") !== FALSE ) { $is_exist_diamond_price = true; break; } }

			if($is_exist_metal_color_purity===FALSE && $is_exist_diamond_price===FALSE)
			{
				//allowed clean search for "Product Batch Code search" on 08-27-2014 as per Request by Rankit sir
				if( empty( $searchf["search_terms_keywords"] ) )
				{
					if( MANUFACTURER_ID == 7 )
					{
						$join = "FROM product_price pp INNER JOIN product p
								 ON (p.product_id=pp.product_id AND 
									 p.product_metal_priority_id=pp.metal_price_id AND 
									 p.product_cs_priority_id=pp.cs_diamond_price_id AND
									 p.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
									 p.product_ss2_priority_id=pp.ss2_diamond_price_id) ";
					}
					else
					{
						//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id )
						$join = "FROM product_price pp INNER JOIN product p
								 ON (p.product_id=pp.product_id)
								 
								 INNER JOIN product_cctld pc 
								 ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND 
								 	  pc.product_id=p.product_id AND 
								 	  pc.product_metal_priority_id=pp.metal_price_id AND 
								 	  pc.product_cs_priority_id=pp.cs_diamond_price_id AND 
								 	  pc.product_ss1_priority_id=pp.ss1_diamond_price_id AND
									  pc.product_ss2_priority_id=pp.ss2_diamond_price_id ) ";
					}
				}
				else
				{
					if( MANUFACTURER_ID == 7 )
					{
						$join = "FROM product_price pp INNER JOIN product p
								 ON (p.product_id=pp.product_id) ";
					}
					else
					{
						//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id )
						$join = "FROM product_price pp INNER JOIN product p
								 ON (p.product_id=pp.product_id)
								 
								 INNER JOIN product_cctld pc 
								 ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND pc.product_id=p.product_id ) ";
					}
				}
			}
			else if($is_exist_metal_color_purity===FALSE)
			{
				if( MANUFACTURER_ID == 7 )			
				{
					$join = "FROM product_price pp INNER JOIN product p 
							 ON (p.product_id=pp.product_id AND p.product_metal_priority_id=pp.metal_price_id ) ";
				}
				else
				{
					//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id )
					$join = "FROM product_price pp INNER JOIN product p 
							ON (p.product_id=pp.product_id ) 
							
							INNER JOIN product_cctld pc 
							ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND 
								 pc.product_id=p.product_id AND 
								 pc.product_metal_priority_id=pp.metal_price_id ) ";	
				}
			}
			else if($is_exist_diamond_price===FALSE)
			{
				if( MANUFACTURER_ID == 7 )			
				{
					$join = "FROM product_price pp INNER JOIN product p 
							 ON (p.product_id=pp.product_id AND 
								 p.product_cs_priority_id=pp.cs_diamond_price_id AND 
								 p.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
								 p.product_ss2_priority_id=pp.ss2_diamond_price_id) ";
				}
				else
				{
					//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id )
					$join = "FROM product_price pp INNER JOIN product p 
							 ON (p.product_id=pp.product_id ) 
							 
							 INNER JOIN product_cctld pc 
							 ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND 
							 	  pc.product_id=p.product_id AND 
							 	  p.product_cs_priority_id=pp.cs_diamond_price_id AND 
							 	  p.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
							 	  p.product_ss2_priority_id=pp.ss2_diamond_price_id ) ";	
				}
			}

			/**
			 * if solitaire search then make sure that only products with diamonds are get filtered: not proper implementation change required
			 */
			//if( $is_solitaire )	{ $where .= " AND pp.cs_diamond_price_id<>0 "; } 
			if( $is_solitaire )	
			{
				$where .= " AND p.product_accessories='SOL' ";
				if( $searchf['parent_category'] == 'Ring' ) { $where .= " AND pcm.category_id=".SOL_RING_M_PCID." "; } 
				else if( $searchf['parent_category'] == 'Earring' ) { $where .= " AND pcm.category_id=".SOL_EARR_M_PCID." "; } 
				else if( $searchf['parent_category'] == 'Pendant' ) { $where .= " AND pcm.category_id=".SOL_PEND_M_PCID." "; } 
				
				//join table and store in joined table array
				if(!in_array("product_category_map",$table_joined))
				{
					$table_joined[] = "product_category_map";	
					$join .= "INNER JOIN product_category_map pcm ON pcm.product_id=p.product_id ";
				}
			}

			/**
			 * browse though search inputs and act accordingly 
			 */
			foreach((array)$searchf as $k=>$ar)
			{
				if($k == "search_terms_keywords")			  //wild card search filter
				{
					if( empty($ar) )
						continue;
				
					$wh_cond = '';
					if( stripos( $ar, "code:" ) === FALSE )
					{
						if( $call_no == 0 )
						{
							$wh_cond = ' ( prc.category_name REGEXP \'[[:<:]]'.$ar.'\' OR prc.category_description REGEXP \'[[:<:]]'.$ar.'\' OR prc.meta_keyword REGEXP \'[[:<:]]'.$ar.'\'
							OR prc.meta_description REGEXP \'[[:<:]]'.$ar.'\' OR p.product_name REGEXP \'[[:<:]]'.$ar.'\' OR p.product_short_description REGEXP \'[[:<:]]'.$ar.'\'
							OR p.meta_keyword REGEXP \'[[:<:]]'.$ar.'\' OR p.meta_description REGEXP \'[[:<:]]'.$ar.'\' OR pp.product_generated_code_displayable LIKE \''.str_replace( " ", "-", $ar).'\'
							OR p.product_sku LIKE \''.str_replace( " ", "-", $ar).'\' ) ';
						}
						else if( $call_no == 1 )
						{
							$keywordArr = explode(" ", str_replace( "  ", " ", $ar ));
				
							$wh_cond = " (";
							foreach($keywordArr as $key1=>$val1)
							{
								$val1 = removeCommonWord( $val1 );
								if( !empty($val1) )
								{
									$wh_cond .= ' ( prc.category_name REGEXP \'[[:<:]]'.$val1.'\' OR prc.category_description REGEXP \'[[:<:]]'.$val1.'\' OR
									prc.meta_keyword REGEXP \'[[:<:]]'.$val1.'\' OR prc.meta_description REGEXP \'[[:<:]]'.$val1.'\' OR p.product_name REGEXP \'[[:<:]]'.$val1.'\'
									OR p.product_short_description REGEXP \'[[:<:]]'.$val1.'\' OR p.meta_keyword REGEXP \'[[:<:]]'.$val1.'\'
									OR p.meta_description REGEXP \'[[:<:]]'.$val1.'\' ) OR ';
								}
							}
							$wh_cond = substr( $wh_cond, 0, -3)." ) ";
						}
					}
					else
					{
						$tempArr = explode( ":", $ar );
						
						if( MANUFACTURER_ID == 7 )
						{
							$wh_cond = ' ( pp.product_generated_code_displayable=\''.str_replace( " ", "-", trim( $tempArr[1] ) ).'\' ) ';
						}
						else 
						{
							//$wh_cond = ' ( ppc.product_generated_code_displayable=\''.str_replace( " ", "-", trim( $tempArr[1] ) ).'\' ) ';
							$wh_cond = ' ( pp.product_generated_code_displayable=\''.str_replace( " ", "-", trim( $tempArr[1] ) ).'\' ) ';
						}
					}
				
					$where .= " AND ".$wh_cond;
						
					//join table and store in joined table array
					if(!in_array("product_category_map",$table_joined))
					{
						$table_joined[] = "product_category_map";
						$join .= "LEFT JOIN product_category_map pcm ON pcm.product_id=p.product_id ";
					}
					if(!in_array("product_categories",$table_joined))
					{
						$table_joined[] = "product_categories";
						$join .= "LEFT JOIN product_categories prc ON prc.category_id=pcm.category_id ";
					}
				
					//generate seo frendly search tag
					$keyword_search_tag = str_replace(" ","-",$ar)."+";
				}
				else if($k == "product_categories" && $ar!='')	  //product categories table
				{
					//					//Note(Filter_TEMP): on TEMP at perry UML: below code remove child category from search when parent already searched this is not full time implementation for filter it is a kind of hack to overcome catgeory page and filter issue in common ==> see products controller "Note(Filter_TEMP):" for more information
					//					$sub_category_id = $this->session->userdata('sub_category_id');
					//					if(sizeof($ar) > 1 && $sub_category_id !== FALSE)
						//					{
						//						$this->session->unset_userdata('sub_category_id');
						//						if(($key = array_search($sub_category_id, $ar)) !== false)
							//						{
							//						    unset($ar[$key]);
							//						}
							//					}
						
					$category_id = "";
					$wh_cond=" (";
						
					$catSearchArrSize = sizeof( $ar );
					$parentArr = array();
					$childArr = array();
				
					foreach((array)$ar  as $key=>$val)
					{
						//remove parent from search if child is already searched
						if( $catSearchArrSize > 1 )
						{
							$parent_id = exeQuery( "SELECT parent_id FROM product_categories WHERE category_id=".$val."", true, "parent_id" );
							if( $parent_id == 0 )
							{
								$parentArr[ $val ] = $val;
								continue;
							}
							else if( in_array( $parent_id, $parentArr ) )
							{
								//preserve parent in search url
								$category_id .= $parent_id."|";
								unset( $parentArr[ $parent_id ] );
							}
								
							$childArr[ $parent_id ] = $parent_id;
						}
				
						$wh_cond .= " pcm.category_id=".$val." OR ";
				
						//if more level is added in category tree then below functionality need to be recursive
						//change: from 26/4/2014 onwards category filter is changed from OR to AND
						//						$cat_child = $this->db->query("SELECT category_id FROM product_categories WHERE parent_id=".$val."")->result_array();
						//						if( is_array( $cat_child ) && sizeof( $cat_child ) > 0 )
							//						{
							//							foreach($cat_child as $kc=>$vc)
								//							{
								//								$wh_cond .= " pcm.category_id=".$vc['category_id']." OR ";
								//							}
								//						}
				
						$category_id .= $val."|";
					}
				
					//child arr check is only invoked to prevent malicious user from pasting same search category two times
					foreach( $childArr as $key=>$val)
					{
						if( in_array( $val, $parentArr ) )
						{
							//preserve parent in search url
							$category_id .= $val."|";
							unset( $parentArr[ $val ] );
						}
					}
						
					//include parent in search for whose childs are not searched
					foreach( $parentArr as $key=>$val)
					{
						$wh_cond .= " pcm.category_id=".$val." OR ";
						$category_id .= $val."|";
					}
				
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
				
					//join table and store in joined table array
					if(!in_array("product_category_map",$table_joined))
					{
						$table_joined[] = "product_category_map";
						$join .= "INNER JOIN product_category_map pcm ON pcm.product_id=p.product_id ";
					}
				
					//generate seo frendly search tag
					$res = getPipeStringData("product_categories","category_id","category_meta_name, category_alias ",substr($category_id,0,-1));
					$product_categories_tag .= "+";
					$product_categories_url_tag .= "+";
					foreach($res as $k1=>$ar1)
					{
						$product_categories_tag .= $ar1['category_meta_name']."+";
						$product_categories_url_tag .= $ar1['category_alias']."+";
					}
				}
				else if($k == "price_filter" && $ar!='')	//price filter
				{
					//generate seo frendly search tag
					$resArr = generatePriceTag((array)$ar);
					$price_tag = $resArr['price_tag'];
					$price_url_tag = $resArr['url_tag'];
					$min = $resArr['min'];
					$max = $resArr['max'];
						
					//if max zero then only greater then condition in where
					//					if($min == 0 && $max == 0)
						//						continue;
						//					else if($max != 0)
							//					{
							//						if( MANUFACTURER_ID == 7 )
								//						{
								//							$where .= " AND (pp.product_discounted_price BETWEEN ".$min." AND ".$max.") ";
								//						}
								//						else
									//						{
									//							$where .= " AND (ppc.product_discounted_price BETWEEN ".$min." AND ".$max.") ";
									//						}
									//					}
									//					else
										//					{
										//						if( MANUFACTURER_ID == 7 )
											//						{
											//							$where .= " AND pp.product_discounted_price>=".$min." ";
											//						}
											//						else
												//						{
												//							$where .= " AND ppc.product_discounted_price>=".$min." ";
												//						}
												//					}
				
					//Change: price filter algorithm changed from 23/4/2014
					$wh_cond = "( ";
					$tmp = '';
						
					if(  MANUFACTURER_ID == 7 )
					{
						$tmp = 'pp.product_discounted_price';
					}
					else
					{
						//$tmp = 'ppc.product_discounted_price';
						$tmp = 'pp.product_discounted_price';
					}
				
					foreach( $ar as $key=>$val )
					{
						$valArr = explode("-",$val);
				
						if( $valArr[0] == 0 )
							$wh_cond .= " ( ".$tmp."<=".$valArr[1]." ) OR ";
						else if( $valArr[1] == 0 )
							$wh_cond .= " ( ".$tmp.">=".$valArr[0]." ) OR ";
						else
							$wh_cond .= " ( ".$tmp." BETWEEN ".$valArr[0]." AND ".$valArr[1]." ) OR ";
					}
				
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
				
				}
				else if(strpos($k,"product_attribute") !== FALSE)	//product attribute filter
				{
					/**
					 * On 23-04-2015 to minimize memory usage foot print, instead of using unique variable name for each temp id $searched_id used. 
					 * So at optimization time it is required to do for other nested if code blocks also. 
					 */
					$searched_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " pp.pcs_diamond_shape_id=".$val." OR pp.pss1_diamond_shape_id=".$val." OR 
									  pp.pss2_diamond_shape_id=".$val." OR ppim.diamond_shape_id=".$val." OR ";

						$searched_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
				
					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}

					//generate seo frendly search tag
					$res = getPipeStringData("product_attribute","product_attribute_id","pa_value",substr($searched_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$product_attribute_tag .= $ar1['pa_value']."+";
						$product_attribute_url_tag .=  str_replace( " ", "-", $ar1['pa_value'])."+";
					}
				}
				else if($k == "metal_color_purity")
				{
					$metalMap = metalMap();
					$color_purity=" (";
					foreach($ar  as $key=>$val)
					{
						$metal_url_tag .= arrayKey( $metalMap, $val)."+";
						$valArr = explode("-",$val);
						$color_purity .= " ( pp.metal_color_id=".$valArr[0]." AND pp.metal_purity_id=".$valArr[1]." ) OR ";
					}
					$color_purity = substr($color_purity,0,-3).") ";
					$where .= " AND ".$color_purity;
					
					//generate seo freindly search tag
					$resArr = generateMetalTag($ar);
					$metal_tag = $resArr['metal_tag'];
				}
				else if($k == "metal_type")
				{
					$metal_type_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " pp.metal_type_id=".$val." OR ";
						$metal_type_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//generate seo frendly search tag
					$res = getPipeStringData("metal_type","metal_type_id","metal_type_name",substr($metal_type_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$metal_type_tag .= $ar1['metal_type_name']."+";							
					}
				}
				else if($k == "metal_color")
				{
					$this->searchMetalColor( $ar, $metal_color_tag, $where, $metal_color_url_tag);
/*					$metal_color_id = "";
					$wh_cond=" (";
					foreach((array)$ar  as $key=>$val)
					{
						$wh_cond .= " pp.metal_color_id=".$val." OR ";
						$metal_color_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//generate seo frendly search tag
					$res = getPipeStringData("metal_color","metal_color_id","metal_color_name",substr($metal_color_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$metal_color_tag .= $ar1['metal_color_name']."+";							
					}
*/				}
				else if($k == "metal_purity")
				{
					$this->searchMetalPurity( $ar, $metal_purity_tag, $where);
/*					$metal_purity_id = "";
					$wh_cond=" (";
					foreach((array)$ar  as $key=>$val)
					{
						$wh_cond .= " pp.metal_purity_id=".$val." OR ";
						$metal_purity_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//generate seo frendly search tag
					$res = getPipeStringData("metal_purity","metal_purity_id","metal_purity_name",substr($metal_purity_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$metal_purity_tag .= $ar1['metal_purity_name']."+";							
					}
*/				}
				else if($k == "gender_filter")
				{
					//Change: Date=> 19/11/2013 For now if both gender selected then display products from all gender also from unisex collection so code are commented 
					$gender=" (";
					
					if( sizeof($ar) >= 2 )
					{
						//$gender .= " p.product_gender='O' ";

						//generate seo frendly search tag
						$gender_filter_tag .= 'For-Women-And-Men-';	
						$gender_filter_url_tag .= "for-women-and-men+";						 
					}
					else if( sizeof($ar) == 1 )
					{
						$gender .= " p.product_gender='O' OR p.product_gender='".$ar[0]."' ";

						//generate seo frendly search tag
						$gender_filter_tag .= (( $ar[0] == 'F' ) ? 'For-Women' : 'For-Men' )."+";							
						$gender_filter_url_tag .= (( $ar[0] == 'F' ) ? 'for-women' : 'for-men' )."+";						 

						//conditional where due to change 
						$gender .= ") ";
						$where .= " AND ".$gender;
					}

				}
				else if($k == "product_offer")
				{
					$product_offer_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " (pom.product_offer_id=".$val." OR ";
						$product_offer_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;

					//join table and store in joined table array
					if(!in_array("product_offer_map",$table_joined))
					{
						$table_joined[] = "product_offer_map";	
						$join .= "INNER JOIN product_offer_map pom ON pom.product_id=p.product_id ";
					}

					//generate seo frendly search tag
					$res = getPipeStringData("product_offer","product_offer_id","product_offer_name",substr($product_offer_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$product_offer_tag .= $ar1['product_offer_name']."+";							
					}
				}
				else if($k == "diamond_shape")
				{
					$diamond_shape_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " (pp.pcs_diamond_shape_id=".$val." OR pp.pss1_diamond_shape_id=".$val." OR pp.pss2_diamond_shape_id=".$val." OR ppim.diamond_shape_id=".$val.") OR ";
						$diamond_shape_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";	
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}
					
					//generate seo frendly search tag
					$res = getPipeStringData("diamond_shape","diamond_shape_id","diamond_shape_name",substr($diamond_shape_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$diamond_shape_tag .= $ar1['diamond_shape_name']."+";							
					}
				}
				else if($k == "diamond_type")
				{
					$diamond_type_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " pp.diamond_type_id_cs=".$val." OR pp.diamond_type_id_ss1=".$val." OR pp.diamond_type_id_ss2=".$val." OR ppim.diamond_type_id=".$val." OR ";
						$diamond_type_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";	
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}
					
					//generate seo frendly search tag
					$res = getPipeStringData("diamond_type","diamond_type_id","diamond_type_name",substr($diamond_type_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$diamond_type_tag .= $ar1['diamond_type_name']."+";	
						$diamond_type_url_tag .= str_replace( " ", "-", $ar1['diamond_type_name'])."+";							
					}
				}
				else if($k == "cz")
				{
					//generate seo frendly search tag
					$cz_tag .= "Cubic Zirconia-";							
				}
				else if($k == "diamond_color")
				{
					$diamond_color_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " pp.diamond_color_id_cs=".$val." OR pp.diamond_color_id_ss1=".$val." OR pp.diamond_color_id_ss2=".$val." OR ppim.diamond_color_id=".$val." OR ";
						$diamond_color_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";	
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}
					
					//generate seo frendly search tag
					$res = getPipeStringData("diamond_color","diamond_color_id","diamond_color_name",substr($diamond_color_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$diamond_color_tag .= $ar1['diamond_color_name']."+";							
					}
				}
				else if($k == "diamond_purity")
				{
					$diamond_purity_id = "";
					$wh_cond=" (";
					foreach((array)$ar  as $key=>$val)
					{
						$wh_cond .= " pp.diamond_purity_id_cs=".$val." OR pp.diamond_purity_id_ss1=".$val." OR pp.diamond_purity_id_ss2=".$val." OR ppim.diamond_purity_id=".$val." OR ";
						$diamond_purity_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;
					
					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";	
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}
					
					//generate seo frendly search tag
					$res = getPipeStringData("diamond_purity","diamond_purity_id","diamond_purity_name",substr($diamond_purity_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$diamond_purity_tag .= $ar1['diamond_purity_name']."+";							
					}
				}
				else if(strpos($k,"diamond_price") !== FALSE)	//diamond category tables
				{
					$diamond_price_id = "";
					$wh_cond=" (";
					foreach($ar  as $key=>$val)
					{
						$wh_cond .= " pp.cs_diamond_price_id=".$val." OR pp.ss1_diamond_price_id=".$val." OR pp.ss2_diamond_price_id=".$val." OR ppim.diamond_price_id=".$val." OR ";

						//map precious gemstones to semi precious gemstones
						if( $k == "diamond_price-2"  )
						{
							if( $val == 7 ) { $wh_cond .= " pp.cs_diamond_price_id=62 OR pp.ss1_diamond_price_id=62 OR pp.ss2_diamond_price_id=62 OR ppim.diamond_price_id=62 OR "; }
							else if( $val == 56 ) { $wh_cond .= " pp.cs_diamond_price_id=65 OR pp.ss1_diamond_price_id=65 OR pp.ss2_diamond_price_id=65 OR ppim.diamond_price_id=65 OR "; }
							else if( $val == 57 ) { $wh_cond .= " pp.cs_diamond_price_id=64 OR pp.ss1_diamond_price_id=64 OR pp.ss2_diamond_price_id=64 OR ppim.diamond_price_id=64 OR "; }
							else if( $val == 58 ) { $wh_cond .= " pp.cs_diamond_price_id=66 OR pp.ss1_diamond_price_id=66 OR pp.ss2_diamond_price_id=66 OR ppim.diamond_price_id=66 OR "; }
							else if( $val == 59 ) { $wh_cond .= " pp.cs_diamond_price_id=63 OR pp.ss1_diamond_price_id=63 OR pp.ss2_diamond_price_id=63 OR ppim.diamond_price_id=63 OR "; }
							else if( $val == 60 ) { $wh_cond .= " pp.cs_diamond_price_id=67 OR pp.ss1_diamond_price_id=67 OR pp.ss2_diamond_price_id=67 OR ppim.diamond_price_id=67 OR "; }
						}
						$diamond_price_id .= $val."|";
					}
					$wh_cond = substr($wh_cond,0,-3).") ";
					$where .= " AND ".$wh_cond;

					//join table and store in joined table array
					if( !in_array("pp_pss_index_map",$table_joined) )
					{
						$table_joined[] = "pp_pss_index_map";	
						$join .= "LEFT JOIN pp_pss_index_map ppim ON ppim.product_price_id=pp.product_price_id ";
					}

					//generate seo frendly search tag
					$res = getPipeStringData("diamond_price","diamond_price_id","diamond_price_name",substr($diamond_price_id,0,-1));
					foreach($res as $k1=>$ar1)
					{
						$diamond_price_tag .= $ar1['diamond_price_name']."+";
						$diamond_price_url_tag .=  str_replace( " ", "-", $ar1['diamond_price_name'])."+";
					}
				}

			}
		}
		else if($is_ready_to_ship)	//ready to ship search
		{
			//fetch product_offer id of RTS because if ready to ship then display only RTS:Reday to Ship products
			$res = executeQuery("SELECT product_offer_id FROM product_offer WHERE product_offer_key='RTS'");
			$product_offer_idSta = @$res[0]['product_offer_id'];

			
			if( MANUFACTURER_ID == 7 )			
			{
				//specifies which table are already joined so that we have idea of joined table while building dynamic sql
				$table_joined = array(0=>'product_price');

				$select ="SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, p.product_name, p.product_alias, p.product_angle_in, 
								 p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, 
								 pp.product_generated_code_info,  pp.product_price_calculated_price, 
								 pp.product_discount, pp.product_discounted_price, p.product_sku ";

				$join = "FROM product_offer_map po INNER JOIN product p 
						 ON p.product_id=po.product_id 
						 INNER JOIN product_price pp 
						 ON (p.product_id=pp.product_id AND 
							p.product_metal_priority_id=pp.metal_price_id AND 
							p.product_cs_priority_id=pp.cs_diamond_price_id AND 
							p.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
							p.product_ss2_priority_id=pp.ss2_diamond_price_id) "; 
				$where = "WHERE po.product_offer_id=".$product_offer_idSta." AND pp.product_price_status=0 AND p.product_status=0 "; 
			}
			else
			{
				//specifies which table are already joined so that we have idea of joined table while building dynamic sql
				$table_joined = array(0=>'product_price',1=>'product_price_cctld',2=>'product_cctld');

				//ppc.product_price_calculated_price, ppc.product_discount, ppc.product_discounted_price,
				$select ="SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, pc.product_name, p.product_alias, p.product_angle_in, 
								 p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, 
								 pp.product_generated_code_info,  pp.product_price_calculated_price, 
								 pp.product_discount, pp.product_discounted_price, p.product_sku ";

				//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id )
				$join = "FROM product_offer_map po INNER JOIN product p 
						 ON p.product_id=po.product_id 
						 INNER JOIN product_price pp 
						 ON (p.product_id=pp.product_id ) 
						 
						 INNER JOIN product_cctld pc 
						 ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND 
						 	  pc.product_id=p.product_id AND 
						 	  pc.product_metal_priority_id=pp.metal_price_id AND 
						 	  pc.product_cs_priority_id=pp.cs_diamond_price_id AND 
						 	  pc.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
						 	  pc.product_ss2_priority_id=pp.ss2_diamond_price_id) ";						
				
				//ppc.product_price_status=0 AND
				$where = "WHERE po.product_offer_id=".$product_offer_idSta." AND pp.product_price_status=0 AND pc.product_status=0 ";
			}
		}
		else// if($sort_by == '')	//default if no search inputs provided and prices is from low to high
		{

			if( MANUFACTURER_ID == 7 )			
			{
				//specifies which table are already joined so that we have idea of joined table while building dynamic sql
				$table_joined = array(0=>'product_price');
				$select = "SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, p.product_name, p.product_alias, p.product_angle_in, 
								 p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, 
								 pp.product_generated_code_info,  pp.product_price_calculated_price, 
								 pp.product_discount, pp.product_discounted_price, p.product_sku ";

				$join = "FROM product_price pp 
						 INNER JOIN product p 
						 ON (p.product_id=pp.product_id AND 
							 p.product_metal_priority_id=pp.metal_price_id AND 
							 p.product_cs_priority_id=pp.cs_diamond_price_id AND 
							 p.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
							 p.product_ss2_priority_id=pp.ss2_diamond_price_id) ";
				$where = "WHERE pp.product_price_status=0 AND p.product_status=0 ";	
			}
			else
			{
				//specifies which table are already joined so that we have idea of joined table while building dynamic sql
				$table_joined = array(0=>'product_price',1=>'product_price_cctld',2=>'product_cctld');
				
				//ppc.product_price_calculated_price, ppc.product_discount, ppc.product_discounted_price,
				$select ="SELECT SQL_CALC_FOUND_ROWS p.product_id, p.inventory_type_id, p.category_id, pc.product_name, p.product_alias, p.product_angle_in, 
								 p.product_accessories, pp.product_price_id, pp.product_generated_code, pp.product_generated_code_displayable, 
								 pp.product_generated_code_info,  pp.product_price_calculated_price, 
								 pp.product_discount, pp.product_discounted_price, p.product_sku ";
				
				//INNER JOIN product_price_cctld ppc ON ( ppc.manufacturer_id = ".MANUFACTURER_ID." AND ppc.product_price_id=pp.product_price_id ) 
				$join = "FROM product_price pp INNER JOIN product p 
						 ON (p.product_id=pp.product_id ) 
						 
						 INNER JOIN product_cctld pc 
						 ON ( pc.manufacturer_id = ".MANUFACTURER_ID." AND 
						 	  pc.product_id=p.product_id AND 
						 	  pc.product_metal_priority_id=pp.metal_price_id AND 
						 	  pc.product_cs_priority_id=pp.cs_diamond_price_id AND 
						 	  pc.product_ss1_priority_id=pp.ss1_diamond_price_id AND 
						 	  pc.product_ss2_priority_id=pp.ss2_diamond_price_id) ";
							  
				//ppc.product_price_status=0 AND
				$where = "WHERE pp.product_price_status=0 AND pc.product_status=0 ";	
			}

		}
		
		//default category wise listing
		if(!empty($cat_id))
		{
			$where .= " AND pcm.category_id=".$cat_id." ";
			
			//join table and store in joined table array
			if(!in_array("product_category_map",$table_joined))
			{
				$table_joined[] = "product_category_map";	
				$join .= "INNER JOIN product_category_map pcm ON pcm.product_id=p.product_id ";
			}
		}

		
		//addded on 16-12-2016
		if(	empty($sort_by) )
		{
			$sort_by = "latest_products_asc";	
		}
		
		
		//sorting rendering	
		if($sort_by != '')
		{
			if($sort_by == "most_viewed_asc")
			{
				$sort_by_tag = "Most-Viewed-";
				$order_by=" ORDER BY product_view_buy DESC";
			}
			else if($sort_by == "latest_products_asc")
			{
				$sort_by_tag = "Latest-Arrivals-";
				$order_by=" ORDER BY product_id DESC";
			}
			else if($sort_by == "price_asc")
			{
				$sort_by_tag = "Price-Low-To-High-";
				$order_by=" ORDER BY product_discounted_price ASC";
			}
			else if($sort_by == "price_desc")
			{
				$sort_by_tag = "Price-High-To-Low-";
				$order_by=" ORDER BY product_discounted_price DESC";
			}
		}

		/**
		 * Cloudwebs: added on 03-04-2015
		 * check if get limit "st" is passed
		 */
		$start = (int)$this->input->get("start");
		
		//limit listing
		$sessArr = array();
		$start_limit = 0 + PER_PAGE_FRONT;	$limit = " LIMIT ".$start.",".PER_PAGE_FRONT." ";

		$qrTmp = $select.$join.$where.$group_by.$order_by.$limit;
		//echo $qrTmp; die; 
//		if( isIntranetIp() ) 
//		{
//			echo $qrTmp; 
//			die;
//		}

		$resCache;
		if( IS_CACHE )
		{
			//caching database result
			$qry_id = queryId( $qrTmp );
			$res;
			if ( ! $res = $this->cache->get( $qry_id ) )
			{
				$res = $this->db->query($qrTmp);
				$resCache['result_array'] = (array)$res->result_array();
					
				//fetch total records
				$resCnt = $this->db->query("SELECT FOUND_ROWS( ) as 'Count'")->row_array();
				$resCache['Count'] = $resCnt['Count'];
			
				saveCacheKey( $qry_id, 'filter');
			
				// Save into the cache for infinite time
				$this->cache->save( $qry_id, $resCache, 0);
			}
			else
			{
				$resCache = $res;
			}
			/*******caching end *******/
		}
		else 
		{
			$resCache['result_array'] = (array) $this->db->query($qrTmp)->result_array(); 
				
			//fetch total records
			$resCnt = $this->db->query("SELECT FOUND_ROWS( ) as 'Count'")->row_array();
			$resCache['Count'] = $resCnt['Count'];
		}
		//echo $qrTmp; pr($resCache); die; 
		
		//save last query in session to be used in pagination call
		if( !$is_ready_to_ship && !$is_solitaire && !$is_valentine_gifts )
		{
			$sessArr['last_query'] = $select.$join.$where.$group_by.$order_by;
			$sessArr['start_limit'] = $start_limit;
		}
		else if( $is_ready_to_ship )
		{
			$sessArr['last_query_ready_to_ship'] = $select.$join.$where.$group_by.$order_by;
			$sessArr['start_limit_ready_to_ship'] = $start_limit;
		}
		else if( $is_solitaire )
		{
			$sessArr['last_query_solitaire'] = $select.$join.$where.$group_by.$order_by;
			$sessArr['start_limit_solitaire'] = $start_limit;
		}
		else if( $is_valentine_gifts )
		{
			$sessArr['last_query_valentine_gifts'] = $select.$join.$where.$group_by.$order_by;
			$sessArr['start_limit_valentine_gifts'] = $start_limit;
		}
		
		$this->session->set_userdata($sessArr);

				
		/**
		 * seo freindly search code
		 */
		$search_tagArr = array('metal_tag'=>$metal_tag,'metal_purity_tag'=>$metal_purity_tag,'metal_color_tag'=>$metal_color_tag,'metal_type_tag'=>$metal_type_tag,'diamond_purity_tag'=>$diamond_purity_tag,'diamond_color_tag'=>$diamond_color_tag,'diamond_shape_tag'=>$diamond_shape_tag,'diamond_type_tag'=>$diamond_type_tag,'cz_tag'=>$cz_tag,'diamond_price_tag'=>$diamond_price_tag,'gender_filter_tag'=>$gender_filter_tag,'product_offer_tag'=>$product_offer_tag,'product_categories_tag'=>$product_categories_tag,'price_tag'=>$price_tag,'sort_by_tag'=>$sort_by_tag,'keyword_search_tag'=>$keyword_search_tag,'product_attribute_tag'=>$product_attribute_tag);

		/**
		 * seo freindly url code
		 */
		$search_url_tagArr = array('metal_url_tag'=>$metal_url_tag,'metal_purity_url_tag'=>$metal_purity_url_tag,'metal_color_url_tag'=>$metal_color_url_tag,'metal_type_url_tag'=>$metal_type_url_tag,'diamond_purity_url_tag'=>$diamond_purity_url_tag,'diamond_color_url_tag'=>$diamond_color_url_tag,'diamond_shape_url_tag'=>$diamond_shape_url_tag,'diamond_type_url_tag'=>$diamond_type_url_tag,'cz_url_tag'=>$cz_url_tag,'diamond_price_url_tag'=>$diamond_price_url_tag,'gender_filter_url_tag'=>$gender_filter_url_tag,'product_offer_url_tag'=>$product_offer_url_tag,'product_categories_url_tag'=>$product_categories_url_tag,'price_url_tag'=>$price_url_tag,'product_attribute_url_tag'=>$product_attribute_url_tag);

		$search_code = generateSearchCode( $search_tagArr );
		
		return array('data'=>$resCache,'search_code'=>rtrim(@$search_code,'-'),'search_tagArr'=>@$search_tagArr, 'search_url_tagArr'=>$search_url_tagArr, 'start'=>$start);
	}

/**
 * @author Cloudwebs
 * @abstract scroll pagination on front side liting page
 */
	function scrollPagination($page)
	{
		$last_query = '';
		if($page == 'filter')
		{	$last_query = $this->session->userdata('last_query');	}
		else if($page == 'ready_to_ship')
		{	$last_query = $this->session->userdata('last_query_ready_to_ship');	}
		else if($page == 'solitaire')
		{	$last_query = $this->session->userdata('last_query_solitaire');	}
		else if($page == 'valentine_gifts')
		{	$last_query = $this->session->userdata('last_query_valentine_gifts');	}
		
		$start_limit = 0;
		$is_sort_by_used = false;
		
		if($page == 'filter')
		{	$start_limit = $this->session->userdata('start_limit');	}
		else if($page == 'ready_to_ship')
		{	$start_limit = $this->session->userdata('start_limit_ready_to_ship');	}
		else if($page == 'solitaire')
		{	$start_limit = $this->session->userdata('start_limit_solitaire');	}
		else if($page == 'valentine_gifts')
		{	$start_limit = $this->session->userdata('start_limit_valentine_gifts');	}
		
		//return if session not set
		if( $last_query === FALSE  || $last_query=='')
		{
			return '';	
		}
		else
		{
			$last_query .= " LIMIT ".$start_limit.", ".PER_PAGE_FRONT." ";

			//increase limit session variable by PER_PAGE_FRONT
			$start_limit += PER_PAGE_FRONT;
			
			/**
			 * caching database result
			 */
			$res;
			$resCache;
			if( IS_CACHE )
			{
				$qry_id = queryId( $last_query );
				if ( ! $res = $this->cache->get( $qry_id ) )
				{
					$res = $this->db->query( $last_query );
				
					$resCache['result_array'] = (array)$res->result_array();
						
					saveCacheKey( $qry_id, 'filter');
				
					// Save into the cache for infinite time
					$this->cache->save( $qry_id, $resCache, 0);
				}
				else
				{
					$resCache = $res;
				}
			}
			else 
			{
				$res = $this->db->query( $last_query );
				$resCache['result_array'] = (array)$res->result_array();
			}
			/*******caching end *******/


			if($page == 'filter')
			{	$this->session->set_userdata( array('start_limit'=>$start_limit) );	}
			else if($page == 'ready_to_ship')
			{	$this->session->set_userdata( array('start_limit_ready_to_ship'=>$start_limit) );	}
			else if($page == 'solitaire')
			{	$this->session->set_userdata( array('start_limit_solitaire'=>$start_limit) );	}
			else if($page == 'valentine_gifts')
			{	$this->session->set_userdata( array('start_limit_valentine_gifts'=>$start_limit) );	}
	
			return array( 'data'=>$resCache, 'start_limit'=>$start_limit );
		}
	}

/*
+-----------------------------------------+
	Function will fetch product details from database
	@prodId = product id
+-----------------------------------------+
*/
	function getProductDetails($prodId='')
	{
		if(!$prodId)
			show_404();
		else
		{
			$res = $this->db->where('product_id', $prodId)
			    ->where('product_status','0')
				->get('product')
				->row_array();
			return $res;
		}
	}
	
/*
+-----------------------------------------+
	author Cloudwebs
	Function will fetch dynamic filter data to display filter
+-----------------------------------------+
*/	
	function getFilterData()
	{
		return cmn_vw_getFilterData(); 
	}
	
	function saveSearchTerm()
	{
		$data = array();
		$data['ip_address']=$_SERVER['REMOTE_ADDR'];
		$data['search_terms_keywords']=$this->input->get('search_terms_keywords');
		if($this->session->userdata('customer_id'))
		{
			$data['customer_id']= $this->session->userdata('customer_id');
		}
		else
		{
			$data['customer_id']='0';
		}
		$this->db->insert('search_terms',$data);
	}
	
	function fetchRingSize($rtype)
	{
		$sql = "select ring_size_id,ring_size_name from ring_size where region_type='".$rtype."' AND ring_size_status='0' order by ring_size_sort_order";
		$sizeArr = getDropDownAry($sql,"ring_size_id", "ring_size_name", '', false);
		return form_dropdown('ring_size_id',$sizeArr,'','');
	}
	
	//Save email to friend
	function saveEmailToFriend()
	{
		$data = $this->input->post();
		saveEmailList($data['es_to_emails'], 0, 'N', 'EMAIL_TO_FRIEND', 15); //save email_list table
		
		$product_price_id = $data['pid'];
		
		$data['es_message'] = '<p>Your friend '.$data['es_yourname'].' has suggested a product you might interested in with this message.</p><p style="font-style:italic;">'.@$data['es_message'].'</p>';
		$data['es_ip_address']= $_SERVER['REMOTE_ADDR'];
		
		unset($data['email_url']);
		unset($data['pid']);
		unset($data['es_yourname']);
		$this->db->insert('email_send_history',$data);
		
		$data['productArr'] = showProductsDetails($product_price_id);
		
		$mail_body = $this->load->view('templates/email-to-friend',$data,TRUE);
		$mail_body .= $this->load->view('templates/footer-template',array( 'email_list_id'=>"0",'email_id'=>$data['es_to_emails'] ),TRUE);
		sendMail($data['es_to_emails'], $data['es_subject'], $mail_body, $data['es_from_emails']);
	}

/**
 * @author Cloudwebs
 * @abstract Function for metal color filter 
 */	
	function searchMetalColor( $ar, &$metal_color_tag, &$where, &$metal_color_url_tag='' )
	{
		$metal_color_id = "";
		$wh_cond=" (";
		foreach((array)$ar  as $key=>$val)
		{
			$wh_cond .= " pp.metal_color_id=".$val." OR ";
			$metal_color_id .= $val."|";
		}
		$wh_cond = substr($wh_cond,0,-3).") ";
		$where .= " AND ".$wh_cond;
		
		//generate seo frendly search tag
		$res = getPipeStringData("metal_color","metal_color_id","metal_color_name",substr($metal_color_id,0,-1));
		foreach($res as $k1=>$ar1)
		{
			$metal_color_tag .= $ar1['metal_color_name']."+";		
			$metal_color_url_tag .= str_replace( " ", "-", $ar1['metal_color_name'])."+";		
		}
	}

/**
 * @author Cloudwebs
 * @abstract Function for metal purity filter 
 */	
	function searchMetalPurity( $ar, &$metal_purity_tag, &$where )
	{
		$metal_purity_id = "";
		$wh_cond=" (";
		foreach((array)$ar  as $key=>$val)
		{
			$wh_cond .= " pp.metal_purity_id=".$val." OR ";
			$metal_purity_id .= $val."|";
		}
		$wh_cond = substr($wh_cond,0,-3).") ";
		$where .= " AND ".$wh_cond;
		
		//generate seo frendly search tag
		$res = getPipeStringData("metal_purity","metal_purity_id","metal_purity_name",substr($metal_purity_id,0,-1));
		foreach($res as $k1=>$ar1)
		{
			$metal_purity_tag .= $ar1['metal_purity_name']."+";							
		}
	}
/**
 * @abstract Function for get parent category data
 */	
	function getParentCategoryData($category_id="")
	{
		if(empty($category_id))
			return '';
		else
		{
			$res = $this->db->query("SELECT category_name, category_description FROM ".$this->cTableName." WHERE category_id IN (SELECT parent_id FROM ".$this->cTableName." WHERE category_id=".$category_id.") ");
			return $res->row_array();
		}
	}

}
