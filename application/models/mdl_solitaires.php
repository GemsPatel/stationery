<?php
class mdl_solitaires extends CI_Model
{

/*
+-----------------------------------------+
	Function will fetch products data from database
+-----------------------------------------+
*/
	function getProducts()
	{
		$this->load->model('mdl_jewellery','jew');
		$searchf = $this->input->get();
		$searchf['parent_category'] = '';	
		if( isset( $searchf['pty'] ) )
		{
			if( $searchf['pty'] == 'rin' ) { $searchf['parent_category'] = 'Ring'; }
			else if( $searchf['pty'] == 'ear' ) { $searchf['parent_category'] = 'Earring'; }
			else if( $searchf['pty'] == 'pen' ) { $searchf['parent_category'] = 'Pendant'; }
		}
		return $this->jew->getProducts( '', $searchf, '', false, true);
	}
	
/* 
 * @author Cloudwebs
 * @abstract function will search for diamond and return result as per filter criteria
 */
	function searchDiamond( $searchf )
	{
		//////////////////////////////////////////////////////////////////////////// Start Filter ////////////////////////////////////////////////////////////////////////
		//query variables 
		$select=$join=$where=$group_by=$order_by=$limit='';
		
		//to enable use of SQL_CALC_FOUND_ROWS
		ini_set("mysql.trace_mode", "0");
		
		//query parts
		$select = "SELECT SQL_CALC_FOUND_ROWS d.diamond_price_id ";
		$join = "FROM diamond_price d ";	
		$where = "WHERE d.dp_status=0 AND d.dp_final_price>0 ";
		
		
		$f = $this->input->get('f');
		$s = $this->input->get('s');
		if($f != '' && $s != '')
			$order_by .=  " ORDER BY ".$f." ".$s;
		else
			$order_by .=" ORDER BY dp_weight, dp_final_price ";	
		
		
		$searchf['prc_str'] = lp_rev($searchf['prc_str'], CURRENCY_ID, 0);	//round($searchf['prc_str'] * CURRENCY_VALUE); 
		$searchf['prc_end'] = lp_rev($searchf['prc_end'], CURRENCY_ID, 0);	//round($searchf['prc_end'] * CURRENCY_VALUE);
		
		$where .= " AND (dp_final_price BETWEEN ".$searchf['prc_str']." AND ".$searchf['prc_end'].") ";	//price filter
		$where .= " AND (dp_weight BETWEEN ".$searchf['ct_str']." AND ".$searchf['ct_end'].") ";	 //weight filter

		foreach((array)$searchf as $k=>$ar)
		{
			//skip price filter and weight filter
			if($k == "prc_str" || $k == "prc_end" || $k == "ct_str" || $k == "ct_end" )
			{
			}
			else 
			{
				$parmArr = explode('-', substr($searchf['param'], 0, -1));
				$parmGrpArr = array();
				foreach($parmArr as $key=>$val)
				{
					$tempA = explode('=', $val);
					$parmGrpArr[ $tempA[0] ][] = $tempA;					
				}
				
				foreach($parmGrpArr as $key=>$val)
				{
					if($key == "diamond_shape_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " diamond_shape_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "diamond_color_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " diamond_color_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "diamond_purity_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " diamond_purity_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "cut_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " cut_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "polish_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " polish_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "symmetry_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " symmetry_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "fluorescence_color_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " fluorescence_color_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
					else if($key == "certificate_lab_id")
					{
						$where_cond = ' (';
						foreach($val as $k1=>$v1)
						{
							$where_cond .= " certificate_lab_id=".$v1[1]." OR ";	
						}
						
						$where .= " AND ".substr( $where_cond, 0, -3). ")";
					}
				}
			}
		}
		
		if( $searchf['parent_category'] == 'Earring')
		{
			$where .= " AND d.dp_pair_stock<>'' ";
		}

		//limit listing
		$sessArr = array();
		$start_limit = 0 + PER_PAGE_FRONT;	
		$limit = " LIMIT 0,".PER_PAGE_FRONT." ";

		//save last query in session to be used in pagination call
		$sessArr['last_query_dia'] = $select.$join.$where.$group_by;
		$sessArr['start_limit_dia'] = $start_limit;
		$sessArr['last_query_orderby'] = $order_by;

		$last_query = $select.$join.$where.$group_by.$order_by.$limit;
		//caching database result
 		$qry_id = queryId( $last_query, 'd'); 
		$resCache;
		if ( ! $resCache = $this->cache->get( $qry_id ) )
		{
			$resCache['result_array'] = $this->db->query($last_query)->result_array();
			
//			if( $_SERVER['REMOTE_ADDR'] == '123.201.171.94' )
//			{
//				echo $this->db->last_query();
//			}

			//fetch total records 
			$resCnt = $this->db->query("SELECT FOUND_ROWS( ) as 'Count'")->row_array();

			$resCache['Count'] = $resCnt['Count'];

			saveCacheKey( $qry_id, 'dia_fil'); 				

			// Save into the cache for infinite time 
			$this->cache->save( $qry_id, $resCache, 0);
		}
		/*******caching end *******/

		$sessArr['total_records_dia'] = $resCache['Count'];
		$this->session->set_userdata($sessArr);
		$data['total_records'] = $resCache['Count'];
		$data['start'] = 0;
		$data['per_page'] = PER_PAGE_FRONT;
		
		//////////////////////////////////////////////////////////////////////////// End Filter ////////////////////////////////////////////////////////////////////////
		
		//fetch detail
		if( sizeof( $resCache['result_array'] ) > 0 )
		{
			$data['listArr'] = $this->diamondDetail( $resCache['result_array'] );
		}
		
		return $data;
	}

/* 
 * @author Cloudwebs
 * @abstract function will fetch diamond detail
 */
	function diamondDetail( $res )
	{
		//query variables now for fetching desired diamonds details
		$select=$join=$where=$group_by=$order_by=$limit='';
		
		//query parts
		$resArr = diaDetailSelJoinPart();
		$where = "WHERE d.dp_status=0 ";
		
		$group_by="";
		$order_by="";
		
		$last_query_orderby = $this->session->userdata('last_query_orderby');
		
		if($last_query_orderby != '')
			$order_by .=  $last_query_orderby;
		
		//generate IN part for query
		$where .= " AND d.diamond_price_id IN(";
		foreach($res as $k=>$ar)
		{
			$where .= $ar['diamond_price_id'].",";
		}
		$where = substr($where, 0, -1). ") ";
		
		$q = $this->db->query($resArr['select'].$resArr['join'].$where.$group_by.$order_by.$limit);
		//echo $this->db->last_query();
		return $q->result_array();
		
	}

/* 
 * @author Cloudwebs
 * @abstract function will used in diamond scroll pagination
 */
	function diamondScroll()
	{
		$data = array();
		$last_query_dia = $this->session->userdata('last_query_dia');
		$start_limit_dia = $this->session->userdata('start_limit_dia');
		$last_query_orderby = $this->session->userdata('last_query_orderby');

		if( $this->session->userdata('lType') == 'PC' )
		{
			//override with link pagination: for only desk site
			$start_limit_dia = $this->input->get('start');		
		}
			

		//limit listing
		$sessArr = array();
		$start_limit = $start_limit_dia + PER_PAGE_FRONT;	$limit = " LIMIT ".$start_limit_dia.",".PER_PAGE_FRONT." ";

		//save start limit in session
		$sessArr['start_limit_dia'] = $start_limit;
		$this->session->set_userdata($sessArr);

		$last_query = $last_query_dia.$last_query_orderby.$limit;
		//caching database result
 		$qry_id = queryId( $last_query, 'd'); 
		$resCache;
		if ( ! $resCache = $this->cache->get( $qry_id ) )
		{
			$resCache['result_array'] = $this->db->query( $last_query )->result_array();
			//echo $this->db->last_query();

			saveCacheKey( $qry_id, 'dia_fil'); 				

			// Save into the cache for infinite time 
			$this->cache->save( $qry_id, $resCache, 0);
		}
		/*******caching end *******/

		$data['start'] = $start_limit_dia;
		$data['per_page'] = PER_PAGE_FRONT;
		$data['total_records'] = $this->session->userdata('total_records_dia');

		if( sizeof( $resCache['result_array'] ) > 0 )
		{
			$data['listArr'] = $this->diamondDetail( $resCache['result_array'] );
		} 

		return $data;
	}
	
}