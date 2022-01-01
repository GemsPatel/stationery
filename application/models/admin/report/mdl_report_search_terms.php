<?php
class mdl_report_search_terms extends CI_Model
{
	var $cTableName = '';
	var $cAutoId = '';
	var $cCategory = '';
	var $cPrimaryId ='';
	
	function getData()
	{
		if($this->cPrimaryId == "")
		{
			$f = $this->input->get('f');
			$s = $this->input->get('s');
			$search_keyword_filter = $this->input->get('search_keyword_filter');
			$fromDate = $this->input->get('fromDate');
			$toDate =$this->input->get('toDate');
			
			if(isset($search_keyword_filter) && $search_keyword_filter != "")
				$this->db->where('search_terms_keywords LIKE \''.$search_keyword_filter.'%\' ');
					
			if(!empty($fromDate) && !empty($toDate))
				$this->db->where('DATE_FORMAT(search_terms_created_date,"%d/%m/%Y") BETWEEN "'.$fromDate.'" and "'.$toDate.'"','',FALSE);
			
			
			if($f !='' && $s != '')
				$this->db->order_by($f,$s);				
			else
				$this->db->order_by($this->cAutoId,'DESC');
		}
		else if($this->cPrimaryId != '')
			$this->db->where($this->cAutoId,$this->cPrimaryId);
		//echo "SELECT `search_terms_id`, `search_terms_keywords`, `search_terms_created_date`, (SELECT COUNT(search_terms_keywords) FROM search_terms WHERE search_terms_keywords=s.search_terms_keywords ) as 'Count' FROM (`search_terms` s) GROUP BY `search_terms_keywords` ORDER BY Count DESC";
					
		$this->db->select("search_terms_id,search_terms_keywords,search_terms_created_date,(SELECT COUNT(search_terms_keywords) FROM search_terms WHERE search_terms_keywords=s.search_terms_keywords ) as 'Count'");
		$this->db->group_by('search_terms_keywords');
		$res = $this->db->get($this->cTableName." s");
		//echo $this->db->last_query();
		return $res;
		
	}
		/*
+----------------------------------------------------------+
	Function will get all users details.
+----------------------------------------------------------+
*/		
	function getCustomerDetails()
	{
		$userId = _de($this->input->get('item_id'));
		$this->db->select('search_terms.customer_id,search_terms.ip_address,CONCAT(customer.customer_firstname," ",customer.customer_lastname) as cust_name', FALSE);
		$this->db->join('customer','customer.customer_id=search_terms.customer_id OR customer.customer_id=0','LEFT');
		$userArr = $this->db->where('search_terms_keywords',$userId)->get('search_terms')->result_array();
		//echo $this->db->last_query();
		//pr($userArr); die;
		if($userArr)
			$resArr = $userArr;
		else
			$resArr['error'] = getErrorMessageFromCode('01006');
		
		return $resArr;
	}
	
	


}