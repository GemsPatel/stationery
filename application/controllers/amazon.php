<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class amazon extends CI_Controller 
{

	var $is_ajax = false;
	//parent constructor will load model inside it
	function amazon()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->load->model('mdl_home','hom');
		$this->is_ajax = $this->input->is_ajax_request();
	}
	
	function index()
	{
		if( !getSysConfig("IS_ES") || $this->session->userdata('is_entersite_loaded') ) 
		{
			$this->main();
		}
		else
		{
			$this->load->view('enter_site');
		}
	}
	
	function main()
	{
		redirect();
	}
	
	function structure($x, $cnt) 
	{
	    $qry = userAgentSystemConfig();//mysql_query("SELECT `parent_id` FROM `categories` WHERE `categories_id`=$x");
	    $result = mysql_fetch_assoc($qry);
	    $cat = $result['parent_id'];
	    if($cat !=0) 
		{
			structure($cat, $cnt++);
		}
		echo $cat.' >';
	}
	
	function check_ping_status()
	{
		//Config information
		$email = "kakdiya.gautam288@gmail.com";
		$server = "54.239.25.192"; //the address to test, without the "http://" this is a amazon remote address and port
		$port = "443";
		
		
		//Create a text file to store the result of the ping for comparison
		$db = "pingdata.txt";
		
		if (file_exists($db)):
			$previous_status = file_get_contents($db, true);
		else:
			file_put_contents($db, "up");
			$previous_status = "up";
		endif;
		
		//Ping the server and check if it's up
		$current_status =  $this->ping($server, $port, 10);
		
		//If it's down, log it and/or email the owner
		if ($current_status == "down"):
		
			echo "Server is down! ";
			file_put_contents($db, "down");
			
			if ($previous_status == "down"):
				mail($email, "Server is down", "Your server is down.");
			echo "Email sent.";
			endif;
			
			else:
			
				echo "Server is up! ";
				file_put_contents($db, "up");
			
				if ($previous_status == "down"):
					mail($email, "Server is up", "Your server is back up.");
				echo "Email sent.";
			endif;
		
		endif;
		
	}
	
	/**
	 * check ping using ip config proper work or not
	 * @param unknown $host
	 * @param unknown $port
	 * @param unknown $timeout
	 * @return string
	 */
	function ping($host, $port, $timeout)
	{
		$tB = microtime(true);
		$fP = fSockOpen($host, $port, $errno, $errstr, $timeout);
		if (!$fP) { return "down"; }
		$tA = microtime(true);
		return round((($tA - $tB) * 1000), 0)." ms";
	}
	
	/**
	 * check function to qty available
	 */
	function checkCartProduct()
	{
		$post_data = 'ref=ox_sc_update_quantity_1%7C30%7C999';
		$ConfigA[CURLOPT_URL] = "https://www.amazon.com/gp/cart/ajax-update.html/";
		$ConfigA[CURLOPT_POSTFIELDS] = $post_data;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.amazon.com/gp/cart/ajax-update.html/");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($ch, CURLOPT_TIMEOUT   , 13);
		curl_setopt($ch, CURLOPT_USERAGENT , "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36");
		curl_setopt($ch, CURLOPT_HEADER , true);
		curl_setopt($ch, CURLINFO_HEADER_OUT , true);
		curl_setopt($ch, CURLOPT_POSTFIELDS , true);
		if($cookies)
		{
			curl_setopt($ch, CURLOPT_COOKIE, implode(';',$cookies));
		}
		
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$result = curl_exec($ch);
		pr($result);
	}
	
}