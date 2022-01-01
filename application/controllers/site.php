<?php 
use DrewM\MailChimp\MailChimp;
use \Dropbox as dbx;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class site extends CI_Controller {

	//set ID of ccTLD for which process is executed
	var $manufacturer_id = 2;

	//parent constructor will load model inside it
	function site()
	{
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		parent::__construct();
		$this->dbname = "perrian_perry";
		$this->dbuser = "perrian_perry";
		$this->dbpass = "perry@db";

		//[temp]
		if( !$this->authenticateByIp() )
		{
			//setFlashMessage( 'error', 'Access Denied.' );
			//redirect();
		}
		
		//error_reporting(E_ALL);
		//ini_set("display_errors", 1);
		//$this->db->db_debug = TRUE;
		
		//cache driver
		//$this->load->driver( 'cache', array( 'adapter' => 'apc', 'backup' => 'file'));
	}
	
	function index()
	{
		redirect();
	}

	/**
	 * default test function
	 */
	function test()
	{
		$index = 1100;
		for($j=1; $j<=2; $j++)
		{
			for($i=1025; $i<=1074; $i++)
			{
				$index++;
				//echo 'curl "http://access.kaleholdings.com/cron/cpiMain?t_number=1041&t_process_number='.$i.'&is_browserCron=1&explicit_ip_key=sd676fJ7dfhjjgjhgjui23hgjhgfdk734sdfjhdfgG&is_use_proxy=0&SECONDS_INTERVAL_FROM=10&SECONDS_INTERVAL_TO=60&IS_DEBUG_curl=0"<br>';
				echo $index.' => "205.234.153.91:'.$i.':BLAZINGSEOLLC",<br>';
			}
		}
		
	}
	
	function php_info()
	{
		echo phpinfo(); 
	}
	
	function ext()
	{
		pr( get_loaded_extensions() ); 
	}

	function apc()
	{
		include_once 'APC/apc.php';	
	}

	function apccore()
	{
		$link = 'APC/apc.php';
		
		if(is_link($link))
		{
			echo(readlink($link)); die;
		}

		//symlinked to /usr/local/lib/php/apc.php
		include_once 'apc.php';	
	}
	
	/**
	 * @deprecated
	 */
	function saveupdProductccTld()
	{
			
	}
	
	function sku_transfer()
	{
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(360000);
		}
		
		$connection = ftp_connect('119.18.54.83');
		
		$login = ftp_login($connection, 'perrian', $this->config->item('encryption_key'));
		
		if (!$connection || !$login) { die('Connection attempt failed!'); }
		else { echo 'successfully connected.';}
		
		//If PHP is not properly recognizing the line endings when reading files either on or created by a Macintosh computer, enabling the auto_detect_line_endings run-time configuration option may help resolve the problem.
		ini_set("auto_detect_line_endings", true);
		
		$handle = @fopen("./assets/product/upload-list/sku-names-to-be-updated.txt", "r");
		if($handle)
		{
			while (($sku_name = trim(fgets($handle, 4096))) !== false)
			{
				$sku_path = "./public_html/assets/product/".$sku_name;
				//create folder on server if not exist
				if(!@ftp_chdir($connection, $sku_path))		
				{
					ftp_mkdir($connection, $sku_path);
				}
		
				$this->ftp_putAll($connection, "./assets/product/".$sku_name, $sku_path);
				
				echo "Folder with SKU:".$sku_name." uploaded successfully.";
			}

			if (!feof($handle))
			{
				echo "Error: unexpected fgets() fail\n";
			}
			fclose($handle);
		}
		
	}
		
/**
 * @author Cloudwebs
 * function will upload folder using ftp connection
 * $param $conn_id connection object
 *	
 */
	function ftp_putAll($conn_id, $src_dir, $dst_dir)
	{
		$d = dir($src_dir);
	    while($file = $d->read())
		{
			// do this for each file in the directory
	        if ($file != "." && $file != "..")
			{
				 // to prevent an infinite loop
        	    if(is_dir($src_dir."/".$file))
				{ 
					// do the following if it is a directory
                	if(!@ftp_chdir($conn_id, $dst_dir."/".$file))
					{
                    	ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
                	}
	                
					$this->ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
	            }
				else
				{
                	$upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
            	}
        	}
    	}
	    $d->close();
	}

/**
 * @author Cloudwebs
 * function will update used currency per hour
 *	
 */
	function currencyCron()
	{
		$updCurrArr = array();
		$record_process = 1;
		
		
		$limit = (int)exeQuery( " SELECT t_value FROM temp WHERE t_name='CURRENCY_INDEX' ", true, 't_value' );
		
		$res = executeQuery( " SELECT currency_code FROM currency WHERE currency_status=0 LIMIT ".$limit.",".$record_process." " );
		
		if( empty($res) )
		{
			//revert index to 0 if all currency updated once
			$limit = 0;
			$res = executeQuery( " SELECT currency_code FROM currency WHERE currency_status=0 LIMIT ".$limit.",".$record_process." " );
		}
		
		if( !empty($res) )
		{
			foreach( $res as $k=>$ar )		
			{
				if( $ar['currency_code'] == 'INR' || in_array( $ar['currency_code'], $updCurrArr ) )
				{
					continue;	
				}
				
				$updCurrArr[] = $ar['currency_code'];
				
				if( $this->updateCurrency(1, 'INR', $ar['currency_code']) )
				{
					echo 'Currency updated FROM INR To '.$ar['currency_code'].'.';
				}
				else
				{
					echo 'There might be error in updating currency FROM INR To '.$ar['currency_code'].'.';
				}
			}
		}

		$this->db->query( " UPDATE temp SET t_value=".($limit+$record_process)." WHERE t_name='CURRENCY_INDEX' " );
		
	}
	
/**
 * @author Cloudwebs
 * function will fetch and update currency from API
 *	
 */
	function updateCurrency($amount, $from_code, $to_code)
	{
		//fecth currency from API
		$final_price = convert_currency($amount, $from_code, $to_code);
		
		//update currency in database
		if(!empty($final_price))
		{
			$this->db->query('UPDATE currency SET currency_value='.$final_price.', currency_modified_date=NOW() WHERE currency_code=\''.$to_code.'\' ');
			return true;
		}
		else
		{
			return false;	
		}
		
	}

/**
 * @author Hitesh
 * function will send email template and update temp table per 15 minute
 *	
 */
 	function emailCron()
	{
		die; 
		$tempData = $this->db->where('t_name','EMAIL_CRON_INDEX')->get('tempo')->row_array();
		$emailList = $this->db->select('email_id,el_reference_source')->limit($tempData['t_no_of_records'], $tempData['t_value'])->where('el_status','S')->order_by('el_priority_level')->get('email_list')->result_array();
		
		$subject = 'Winter Sale || Flat 15% off';
		$tempContent = $this->load->view('templates/winter-sale-pendants', '', TRUE);
		if(!empty($emailList))
		{
			$cnt=0;
			foreach($emailList as $val)
			{
				$cnt++;
				$mail_body = $tempContent;
				$mail_body .= $this->load->view('templates/footer-template',array('email_id'=>$val['email_id']),TRUE);
				if($val['el_reference_source'] == 'RANKIT_PERSONAL')
					$fromEmail = 'rankit_103@yahoo.com';
				else
					$fromEmail = '';
				sendMail($val['email_id'], $subject, $mail_body, $fromEmail);
			}
			echo 'Operation successfull total '.$cnt.' mail sent.';
			$data['t_value'] = ($tempData['t_value'] + $tempData['t_no_of_records']);
			$this->db->set('tempo_modified_date', 'NOW()', FALSE);
			$this->db->where('temp_id',$tempData['temp_id'])->update('tempo',$data);
		}
	}
			
//Read csv file and email id saved			
	function readCSV()
	{
		if (function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0)
		{
			@set_time_limit(360000);
		}
		ini_set('memory_limit', '-1');
		
		$dataEmail = readCsvNew('5_lac_Ecommerce_Data_complete.csv');
		if($dataEmail)
		{
			$cnt=0;
			foreach($dataEmail as $val)
			{
				$emailList = getField('email_id','email_list','email_id',$val[1]);
				if($emailList == '')
				{
					$cnt++;
					$data = array(
						'email_id' => $val[1],
						'el_optout_level' => 1,
						'el_status' => 'S',
						'el_reference_source' => 'ECOMMERCE_DATA'
					);
					$this->db->insert('email_list',$data);
				}
				echo $emailList.'<br>';
			}
			echo 'Operation successfull saved total: '.$cnt;
		}
	}

//Read email from cpanel with imap in php
	function readEmailFromCpanel()
	{
		ini_set("max_execution_time",360000);

		/* connect to server */
		$hostname = '{'.baseDomain().':143/notls}INBOX';
		$username = 'info@cloudwebs.net';
		$password = 'perrianfamily99';
		
		/* try to connect */
		$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to domain:' . imap_last_error());
		
		/* grab emails */
		$emails = imap_search($inbox,'ALL');
		/* if emails are returned, cycle through each… */
		if($emails) {
		
		$output = "";
		
		/* put the newest emails on top */
		rsort($emails);
		
		echo "Number of email:".imap_num_msg($inbox);
		
		/* for every email… */
			foreach($emails as $email_number) {
			
				$overview = imap_fetch_overview($inbox,$email_number,0);
				$message = imap_fetchbody($inbox,$email_number,1);
				
				//$output .= $overview[0]->subject;
				//if($overview[0]->subject == "failure notice")
				/*if($overview[0]->subject == "Mail delivery failed: returning message to sender")
				{
					$strStart = strpos($message,"To:");
					$strlenStart = strlen('To:');
					$strEnd = strpos($message,"Subject:");
					
					$pos  = $strlenStart+ $strStart;
					$email_id = trim( substr($message, $pos, $strEnd - $pos));
					
					echo $email_id." <br>";
					$dbEmail = $this->db->where('email_id LIKE \'%'.$email_id.'%\' ')->get('email_list')->row_array();
					if($dbEmail)
					{
						//$this->db->where('email_list_id',$dbEmail['email_list_id'])->delete('email_list');						
					}
					//imap_delete($inbox,$email_number);
				}*/
				/*if(strpos( " ".$overview[0]->subject,"Warning: ") != FALSE)
				{
					$folder='INBOX.Processed';
					imap_delete($inbox,$email_number);
					imap_mail_move($inbox, $email_number, $folder);
        			imap_expunge($inbox);					
				}*/
				
				/*$output.= '<div>';
				$output.= '<span>'.$overview[0]->subject.'</span> ';
				$output.= '<span>'.$overview[0]->from.'</span>';
				$output.= '<span>'.$overview[0]->date.'</span>';
				$output.= '</div>';
				
				$output.= '<div>'.$message.'</div>';*/
			} //end of for loop
			//echo $output;
		} //end of if statement
		
		/* close the connection */
		imap_close($inbox);
	}
	
/**
 * @author Cloudwebs
 * Function will randomize sort order of product inventory => Currently called twice day using cron
 */	
	function randomSortOrder()
	{
		randomSortOrder();
		echo 'Sort order updated successfully.';
		//setFlashMessage('success', '');
		//redirect('admin/'.$this->controller);
	}
	
/**
 * Function will live server on database backup using cron
 */		
	function dbServerBackup()
	{
		if(!is_dir("../bkp_dir/".date('Y-m-d')))
			mkdir("../bkp_dir/".date('Y-m-d'));
		
		$filename = "~/bkp_dir/".date('Y-m-d')."/dbBackup.sql";
		system("mysqldump -u ".$this->dbuser." -p".$this->dbpass." ".$this->dbname." > ".$filename);
		echo "Successfully upload";
	}
	
/**
 * Function will live server on folder backup using cron
 */		
	function folderServerBackup()
	{
		error_reporting(0);
		ini_set("display_errors", 0);
		
		$pathU = "../bkp_dir/".date('Y-m-d');
		if(!is_dir($pathU))
			mkdir($pathU);
		
		if($this->input->get('assets') == "ASSETS")
		{
			/* CONFIGURE THE FOLLOWING THREE VARIABLES TO MATCH YOUR SETUP */
			$directory_path = array("assets" => "../public_html/assets");
			$dump_file_name = array("assets" => $pathU."/assets.tgz");
		}
		else if($this->input->get('general') == "GENERAL")
		{
			$directory_path = array("application" => "../public_html/application", "css" => "../public_html/css", "images" => "../public_html/images", "js" => "../public_html/js");
			$dump_file_name = array("application" => $pathU."/application.tgz", "css" => $pathU."/css.tgz", "images" => $pathU."/images.tgz", "js" => $pathU."/js.tgz");
		}
		else
		{
			redirect(site_url());
		}		
		/* CONFIGURE THE FOLLOWING FOUR VARIABLES TO MATCH YOUR FTP SETUP */
		$ftp_server = "119.18.54.83";   // Shouldn't have any trailing slashes and shouldn't be prefixed with ftp://
		$ftp_port = "22";            // FTP port - blank defaults to port 21
		$ftp_username = "perrian";         // FTP account username
		$ftp_password = "CHZ5HAfz7+NQ";         // FTP account password - blank for anonymous
		 
		// set up basic connection
		$ftp_conn = ftp_connect($ftp_server);
		 
		// Turn PASV mode on or off
		ftp_pasv($ftp_conn, false);
		 
		// login with username and password
		$login_result = ftp_login($ftp_conn, $ftp_username, $ftp_password);
		 
		// check connection
		if ((!$ftp_conn) || (!$login_result))
		{
		   echo "FTP connection has failed.";
		   echo "Attempted to connect to $ftp_server for user $ftp_username";
		   exit;
		}
		else
		{
		   echo "Connected to $ftp_server, for user $ftp_username";
		}
		
		foreach($directory_path as $key=> $val)
		{
			$command = "tar -zcvf {$dump_file_name[$key]} {$val}";
			$result = exec($command,$output);
			
			// upload the file
			$upload = ftp_put($ftp_conn, $val, $dump_file_name[$key], FTP_BINARY);
		}
		// close the FTP stream
		ftp_close($ftp_conn);
		
		//unlink($dump_file_name);   //delete the backup file from the server
	}
	
/**
 * @author Cloudwebs
 * Function will generate sitemap automatically each week on cron call
 */	
	function generateSitemap()
	{
		$CI = get_instance();
		$CI->load->helper('sitemap_functions');
		
		//document root
		$root_path = '';
		if( $_SERVER['HTTP_HOST'] == LOCALHOST_IP )
		{	$root_path = $_SERVER['DOCUMENT_ROOT']."/MyOwn/";	}
		else
		{	
			$root_path = $_SERVER['DOCUMENT_ROOT']."/";	
		}
		
		setTimeLimit();
		if( $this->input->get( 'mode' ) == "auto" || true )
		{

			$idTemp = 0;
			$SitemapFileNoI = 1;
			$tagArr = array();	//to prevent duplicate tag links in sitemap
			
			for($i=1; $i<8; $i++)
			{
				$SiteMapForI = $i;
				$is_records = true;
				$NoOfRecordsPerSitemapI = $this->input->get('NoOfRecordsPerSitemap');
				if( $i==4 || $i==6 ) { $NoOfRecordsPerSitemapI = 100; }
				
				while( $is_records )
				{
					$resArr = CallSitemapFunctions( $CI, $root_path, $NoOfRecordsPerSitemapI, $idTemp, $SiteMapForI, $SitemapFileNoI, $tagArr);				
					
					if( (int)$resArr['idTemp'] == 0 ) 
					{ 
						$is_records = false; 
					} 
					
					$idTemp = $resArr['idTemp'];
				}
			}
		}
		
		$FileA = array();
		$dir = opendir( $root_path );
		while( false != ( $file = readdir( $dir ) ) ) 
		{
			if( ( $file != "." ) and ( $file != ".." ) )
			{
				if(strpos(" ".$file,"sitemap") == 1 && $file != "sitemap.xml" && $file != "sitemap.html")
				{
					$FileA[] = $file; // put in array.
				}
			}
		}
	
		$FileA = $this->sort_specific_array($FileA, "sitemap", ".xml");
		CreateSiteMapIndex( $root_path, $FileA );
		
		echo 'All operations completed successfully.';
	}
	
	function sort_specific_array($arrA, $delimiterS, $suffixS)
	{
		$len = strlen($delimiterS);
		$arr1A = array();
		
		foreach ($arrA as $k=>$ar)
		{
			$pos = (int)strpos($ar,".");
			$arr1A[] = (int)substr($ar, $len, ($pos- $len));
		}
		
		sort($arr1A);
		
		$retA = array();
		foreach ($arr1A as $k=>$ar)
		{
			$retA[] = $delimiterS.$ar.$suffixS;
		}
		
		return $retA;
	}

	/************************************ RAPNET functions **************************************/
	
/**
 * @author Cloudwebs
 * Function will download diamonds from rapnet and insert/update diamond inventory at perrian.
 */	
	function downloadInsUpdDiamonds()
	{
		$starttime = time();
		setTimeLimit();

		//1 - Authenticate with TechNet. The authentication ticket will be stored in $auth_ticket. Note this MUST be HTTPS.
		$auth_url = "https://technet.rapaport.com/HTTP/Authenticate.aspx";
		$post_string = "username=73730&password=" . urlencode("RasiK.P207");
		
		//create HTTP POST request with curl:
		$request = curl_init($auth_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$auth_ticket = curl_exec($request); // execute curl post and store results in $auth_ticket
		curl_close ($request);
		
		//$feed_url = "http://technet.rapaport.com/HTTP/RapLink/download.aspx?SellerLogin=55482&SortBy=Owner&White=1&Fancy=1&Programmatically=yes&Version=1.0";
		$feed_url="http://technet.rapaport.com/HTTP/RapLink/download.aspx?AvailabilityIDs=1&CountryIDs=217,231,204&SortBy=Owner&White=1&Programmatically=yes&Version=1.0";
		$feed_url .= "&ticket=".$auth_ticket; //add authentication ticket:
		
		$filepath = 'assets/import/rapnetfeed_'.time().'.csv';
		$this->db->query(" UPDATE temp SET t_value='".$filepath."', tempo_modified_date=NOW() WHERE t_name='RAP_CSV_FILE' ");

		//prepare to save response as file.
		$fp = fopen( BASE_DIR.$filepath, 'wb');
		if ($fp == FALSE)
		{
			echo "File not opened.<br>";
			exit;
		}
		
		//create HTTP GET request with curl
		$request = curl_init( $feed_url ); // initiate curl object
		curl_setopt($request, CURLOPT_FILE, $fp); //Ask cURL to write the contents to a file
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_TIMEOUT, 0); //set timeout to infinite
		curl_exec($request); // execute curl post
		
		// additional options may be required depending upon your server configuration
		// you can find documentation on curl options at http://www.php.net/curl_setopt
		curl_close ($request); // close curl object
		fclose($fp); //close file;

		//update process index: process called from processRapnetCsv func so it was dependant on processRapnetCsv
		$this->db->query(" UPDATE temp SET t_value=0 WHERE t_name LIKE 'RAP_UPD_DIA%' ");

		echo 'Diamonds inventory downloaded.<br>';
		//header('Location: http://www.Stationery.com/site/processRapnetCsv?flp='.$filepath.'&starttime='.$starttime);
	}

	/**
	 * @author Cloudwebs
	 * Function will read Rapnet csv and process each row(diamond) one by one
	 */
	function processRapnetCsv( $filepath='' )
	{
	
		$time_elapsed = time();
		setTimeLimit();
	
		if($this->input->get('seller') == "GLOWSTAR")
		{
			$glowstarArr['glowstar'] = "GLOWSTAR";
			$this->session->set_userdata($glowstarArr);
		}
		//change: switched to batch processing Date: 8/4/2014
		$start = $end = 0;
		$nubRecords = 100;
	
		//read process index
		$start = (int)exeQuery( " SELECT tempo_value FROM tempo WHERE tempo_name='RAP_UPD_DIA_".MANUFACTURER_ID."' ", true, "tempo_value" );
	
		if( $start == -1 )
		{
			//download New file From Rapnet
			$res = exeQuery( " SELECT 1 AS 'Count' FROM tempo WHERE tempo_name LIKE 'RAP_UPD_DIA_%' AND tempo_value<>-1 ", true, "Count" );
				
			//only download new file if all ccTLD specific processes of diamond updates are complete
			if( !empty( $res ) && $res == 1 )
			{
				echo "waiting for other ccTLD processes to complete.";
			}
			else
			{
				//$this->downloadInsUpdDiamonds();
			}
				
			exit;
		}
	
	
		if( $filepath == '' )
		{
			$filepath = $this->input->get('flp');
		}
	
		if(  $filepath == '' )
		{
			$filepath = exeQuery( " SELECT tempo_value FROM tempo WHERE tempo_name='RAP_CSV_FILE' ", true, "tempo_value");
				
			if( empty($filepath) )
			{
				echo 'No filepath provided. Function execution aborted.';
				return;
			}
			else
			{
				//echo $filepath."<br>";
				//die;
			}
		}
	
		$csvRowArr = readCsvNew( $filepath );
	
		$keyArr = array_keys( $csvRowArr );
		$size = sizeof( $keyArr );
	
		if( sizeof( $csvRowArr[ $keyArr[ 0 ] ] ) != 34 )
		{
			echo 'File has invalid number of columns. Function execution aborted.';
			return;
		}
	
		$diamond_color_idArr = fetchKeyIdArr( "SELECT diamond_color_id, diamond_color_key FROM diamond_color ", 'diamond_color_id', 'diamond_color_key' );
		$diamond_purity_idArr = fetchKeyIdArr( "SELECT diamond_purity_id, diamond_purity_key FROM diamond_purity ", 'diamond_purity_id', 'diamond_purity_key' );
		$diamond_shape_idArr = fetchKeyIdArr( "SELECT diamond_shape_id, diamond_shape_key FROM diamond_shape ", 'diamond_shape_id', 'diamond_shape_key' );
		$seller_idArr = fetchKeyIdArr( "SELECT seller_id, s_key FROM rp_seller ", 'seller_id', 's_key' );
		$fancy_color_idArr = fetchKeyIdArr( "SELECT fancy_color_id, fc_key FROM rp_fancy_color ", 'fancy_color_id', 'fc_key' );
		$fancy_color_intensity_idArr = fetchKeyIdArr( "SELECT fancy_color_intensity_id, fci_key FROM rp_fancy_color_intensity ", 'fancy_color_intensity_id', 'fci_key' );
		$fancy_color_overtone_idArr = fetchKeyIdArr( "SELECT fancy_color_overtone_id, fco_key FROM rp_fancy_color_overtone ", 'fancy_color_overtone_id', 'fco_key' );
		$cut_idArr = fetchKeyIdArr( "SELECT cut_id, c_key FROM rp_cut ", 'cut_id', 'c_key' );
		$polish_idArr = fetchKeyIdArr( "SELECT polish_id, p_key FROM rp_polish ", 'polish_id', 'p_key' );
		$symmetry_idArr = fetchKeyIdArr( "SELECT symmetry_id, s_key FROM rp_symmetry ", 'symmetry_id', 's_key' );
		$fluorescence_color_idArr = fetchKeyIdArr( "SELECT fluorescence_color_id, fc_key FROM rp_fluorescence_color ", 'fluorescence_color_id', 'fc_key' );
		$certificate_lab_idArr = fetchKeyIdArr( "SELECT certificate_lab_id, cl_key FROM rp_certificate_lab ", 'certificate_lab_id', 'cl_key' );
		$culet_size_idArr = fetchKeyIdArr( "SELECT culet_size_id, cs_key FROM rp_culet_size ", 'culet_size_id', 'cs_key' );
	
		if( $start == 0 )
		{
			//update all rapnet diamond to status 1 so that only downloaded diamond is flagged as available
			if( MANUFACTURER_ID == 7 )
			{
				$this->db->query( " UPDATE diamond_price SET dp_status_temp=1 WHERE dp_rapnet_lot_no<>0 " );
			}
			else
			{
				$this->db->query( " UPDATE diamond_price_cctld SET dp_status_temp=1 WHERE manufacturer_id=".MANUFACTURER_ID." " );
			}
		}
	
		//define USD curreny constant
		$currency_id_USD = getField( "currency_id", "currency", "currency_code", "USD" );
	
		$end = ( ( $start + $nubRecords ) < $size ? ( $start + $nubRecords ) : $size );
		if( $start < $size )
		{
			for ( $i=$start; $i<$end; $i++ )
			{
				//call insupdDiamonds function to insert diamond if not exist otherwise update diamond
				$this->insupdDiamonds( $csvRowArr[ $keyArr[ $i ]  ], $i+1, $diamond_color_idArr, $diamond_purity_idArr, $diamond_shape_idArr, $seller_idArr, $fancy_color_idArr,
						$fancy_color_intensity_idArr, $fancy_color_overtone_idArr, $cut_idArr, $polish_idArr, $symmetry_idArr, $fluorescence_color_idArr,
						$certificate_lab_idArr, $culet_size_idArr, $currency_id_USD );
			}
	
			//update process index
			$this->db->query(" UPDATE tempo SET tempo_value=".$end." WHERE tempo_name='RAP_UPD_DIA_".MANUFACTURER_ID."' ");
				
			$time_elapsed = (time() - $time_elapsed);
				
			//log
			errorLog( 'RAP_DIAMOND_UPDATE', ' Rapaport Diamonds update process: batch completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed, false );
				
		}
		else
		{
			//reset process index
			$this->db->query(" UPDATE tempo SET tempo_value=-1 WHERE tempo_name='RAP_UPD_DIA_".MANUFACTURER_ID."' ");
	
			//update all rapnet diamond to status as that of temp status
			if( MANUFACTURER_ID == 7 )
			{
				$this->db->query(" UPDATE diamond_price SET dp_status=dp_status_temp WHERE dp_rapnet_lot_no<>0 ");
			}
			else
			{
				$this->db->query( " UPDATE diamond_price_cctld SET dp_status_temp=dp_status_temp WHERE manufacturer_id=".MANUFACTURER_ID." " );
			}
	
			$time_elapsed = (time() - $time_elapsed);
				
			//log
			errorLog( 'RAP_DIAMOND_UPDATE', ' Rapaport Diamonds process fully completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed, false );
		}
	
		echo 'Operation completed in '.$time_elapsed.' seconds.';
	}
	
	/**
	 * @author Cloudwebs
	 * Function will insert/update diamond in diamond price table as per Unique: RapNet Lot #No passed as array of diamond parameter from rapnet csv file.
	 */
	function insupdDiamonds( $diamondArr, $lineno, &$diamond_color_idArr, &$diamond_purity_idArr, &$diamond_shape_idArr, &$seller_idArr, &$fancy_color_idArr,
			&$fancy_color_intensity_idArr, &$fancy_color_overtone_idArr, &$cut_idArr, &$polish_idArr, &$symmetry_idArr, &$fluorescence_color_idArr,
			&$certificate_lab_idArr, &$culet_size_idArr, $currency_id_USD )
	{
		$diamond_priceData = array();	$is_update = false; $diamond_price_id=0;
	
		//check if exist then update else insert
		if( !empty($diamondArr[32]) && is_numeric($diamondArr[32]) )
		{
			$diamond_price_id = getField('diamond_price_id','diamond_price','dp_rapnet_lot_no',$diamondArr[32]);
			if( empty($diamond_price_id) )	$is_update = false; else $is_update =  true;
		}
		else
		{
			echo 'Diamond has invalid rapnet lot no: '.$diamondArr[32].'. So diamond at line '.$lineno.' was not processed.<br>';
			return;
		}
	
		//enable the diamond
		$diamond_priceData['dp_status_temp'] = 0;
	
		//seller id
		if( $diamondArr[1]!='' ||  $diamondArr[0]!='' )
		{
			$diamond_priceData['seller_id'] = 0;
			if( $diamondArr[1]!='' )
			{
				$diamond_priceData['seller_id'] = fetchKeyId( $diamondArr[1], 'seller_id', 's_name', 's_key', 'rp_seller', $seller_idArr );
			}
			else if( $diamondArr[0]!='' )
			{
				$diamond_priceData['seller_id'] = getField('seller_id','rp_seller','s_name', $diamondArr[0]);
	
				//if seller does not exist then insert in seller master
				if( empty($diamond_priceData['seller_id']) )
				{
					$this->db->insert('rp_seller', array('s_name'=> $diamondArr[0], 's_key'=> $diamondArr[1]));
					$diamond_priceData['seller_id'] = $this->db->insert_id();
				}
			}
		}
	
		//shape id
		if( $diamondArr[2]!='' )
		{
			$diamond_priceData['diamond_shape_id'] = fetchKeyId( $diamondArr[2], 'diamond_shape_id', 'diamond_shape_name', 'diamond_shape_key', 'diamond_shape', $diamond_shape_idArr );
		}
	
		//weight
		$diamond_priceData['dp_weight'] = $diamondArr[3];
	
		//color id
		if( $diamondArr[4]!='' )
		{
			$diamond_priceData['diamond_color_id'] = fetchKeyId( $diamondArr[4], 'diamond_color_id', 'diamond_color_name', 'diamond_color_key', 'diamond_color', $diamond_color_idArr );
		}
	
		//fancy color id
		if( $diamondArr[5]!='' )
		{
			$diamond_priceData['fancy_color_id'] = fetchKeyId( $diamondArr[5], 'fancy_color_id', 'fc_name', 'fc_key', 'rp_fancy_color', $fancy_color_idArr );
		}
	
		//fancy Intensity id
		if( $diamondArr[6]!='' )
		{
			$diamond_priceData['fancy_color_intensity_id'] = fetchKeyId( $diamondArr[6], 'fancy_color_intensity_id', 'fci_name', 'fci_key', 'rp_fancy_color_intensity',
					$fancy_color_intensity_idArr );
		}
	
		//fancy Overtone id
		if( $diamondArr[7]!='' )
		{
			$diamond_priceData['fancy_color_overtone_id'] = fetchKeyId( $diamondArr[7], 'fancy_color_overtone_id', 'fco_name', 'fco_key', 'rp_fancy_color_overtone',
					$fancy_color_overtone_idArr );
		}
	
		//clarity id
		if( $diamondArr[8]!='' )
		{
			$diamond_priceData['diamond_purity_id'] = fetchKeyId( $diamondArr[8], 'diamond_purity_id', 'diamond_purity_name', 'diamond_purity_key', 'diamond_purity', $diamond_purity_idArr );
		}
	
		//Cut Grade id
		if( $diamondArr[9]!='' )
		{
			$diamond_priceData['cut_id'] = fetchKeyId( $diamondArr[9], 'cut_id', 'c_name', 'c_key', 'rp_cut', $cut_idArr );
		}
	
		//Polish id
		if( $diamondArr[10]!='' )
		{
			$diamond_priceData['polish_id'] = fetchKeyId( $diamondArr[10], 'polish_id', 'p_name', 'p_key', 'rp_polish', $polish_idArr );
		}
	
		//Symmetry id
		if( $diamondArr[11]!='' )
		{
			$diamond_priceData['symmetry_id'] = fetchKeyId( $diamondArr[11], 'symmetry_id', 's_name', 's_key', 'rp_symmetry', $symmetry_idArr );
		}
	
		//Fluorescence id
		if( $diamondArr[12]!='' )
		{
			$diamond_priceData['fluorescence_color_id'] = fetchKeyId( $diamondArr[12], 'fluorescence_color_id', 'fc_name', 'fc_key', 'rp_fluorescence_color', $fluorescence_color_idArr );
		}
	
		//measurements id
		$diamond_priceData['dp_measurements'] = $diamondArr[13];
	
		//Certificate Lab id
		$diamond_priceData['certificate_lab_id'] = 0;
		if( $diamondArr[14]!='' )
		{
			if( stripos(" ".$diamondArr[14], "NONE") === FALSE )
			{
				$diamond_priceData['certificate_lab_id'] = fetchKeyId( $diamondArr[14], 'certificate_lab_id', 'cl_name', 'cl_key', 'rp_certificate_lab', $certificate_lab_idArr );
			}
		}
	
		//Certificate id
		$diamond_priceData['certificate_id'] = 0;
		if( $diamondArr[15]!='' && !empty($diamond_priceData['certificate_lab_id']) )
		{
			if( $diamond_priceData['certificate_lab_id'] != 0 && $diamondArr[15]!='' && $is_update )	//check only in update mode
			{
				$resCert = $this->db->query("SELECT certificate_id FROM rp_certificate
							WHERE certificate_lab_id=".$diamond_priceData['certificate_lab_id']." AND c_certificate_no='".$diamondArr[15]."' ")->row_array();
				$diamond_priceData['certificate_id'] = @$resCert['rp_certificate_id'];
			}
				
			//if Certificate Lab does not exist then insert in Certificate Lab master: for now inserted only if lab and cert no available
			if( empty($diamond_priceData['certificate_id']) && $diamond_priceData['certificate_lab_id'] != 0 && $diamondArr[15]!='' )
			{
				$this->db->insert('rp_certificate', array('c_certificate_no'=> $diamondArr[15], 'certificate_lab_id'=> $diamond_priceData['certificate_lab_id'], 'c_certificate_url'=>$diamondArr[31] ));
				$diamond_priceData['certificate_id'] = $this->db->insert_id();
			}
		}
	
		//stock_no id
		$diamond_priceData['dp_stock_no'] = $diamondArr[16];
	
		//treatment id
		$diamond_priceData['dp_treatment'] = $diamondArr[17];
	
		//dp_rapnet_price id
		$diamond_priceData['dp_rapnet_price'] = $diamondArr[18];
	
		//dp_rapnet_discount id
		$diamond_priceData['dp_rapnet_discount'] = $diamondArr[19];
	
		//dp_depth
		$diamond_priceData['dp_depth'] = $diamondArr[20];
	
		//dp_table
		$diamond_priceData['dp_table'] = $diamondArr[21];
	
		//dp_girdle
		$diamond_priceData['dp_girdle'] = $diamondArr[22];
	
		//culet_size_id id
		if( $diamondArr[23]!='' )
		{
			$diamond_priceData['culet_size_id'] = fetchKeyId( $diamondArr[23], 'culet_size_id', 'cs_name', 'cs_key', 'rp_culet_size', $culet_size_idArr );
		}
	
		//dp_member_comments id
		$diamond_priceData['dp_member_comments'] = $diamondArr[24];
	
		//dp_pincode_id id if only city is available then store only city statically else if country and state avialable then get pincode for that combo or store entry in pincode without area and get pincode_id.	Note*: when cityname is not available NONE is stored
		if( !empty($diamondArr[26]) && !empty($diamondArr[27]) )
		{
			$data_pin['country_id'] = exeQuery( " SELECT country_id FROM country WHERE country_key='".$diamondArr[27]."' ", true, "country_id" );
			if( empty($data_pin['country_id']) )
			{
				//insert country
				$this->db->insert('country', array('country_name'=> $diamondArr[27], 'country_key'=> strtoupper( $diamondArr[27] )));
				$data_pin['country_id'] = $this->db->insert_id();
			}
	
			$data_pin['state_id'] = exeQuery( " SELECT state_id FROM state WHERE country_id=".$data_pin['country_id']." AND state_key='".$diamondArr[26]."' ", true, "state_id" );
			if( empty($data_pin['state_id']) )
			{
				//insert state
				$this->db->insert('state', array('country_id'=>$data_pin['country_id'], 'state_name'=> $diamondArr[26], 'state_key'=> strtoupper( $diamondArr[26] )));
				$data_pin['state_id'] = $this->db->insert_id();
			}
	
			if( $diamondArr[25]!='' )
			{
				$resPin = $this->db->query("SELECT pincode_id FROM pincode
							WHERE state_id=".$data_pin['state_id']." AND ( cityname='".$diamondArr[25]."' ) ")->row_array();
	
				//pincode id
				$diamond_priceData['pincode_id'] = @$resPin['pincode_id'];
			}
			else
			{
				$diamondArr[25] = 'NONE';
				$resPin = $this->db->query("SELECT pincode_id FROM pincode
							WHERE state_id=".$data_pin['state_id']." AND ( cityname='".$diamondArr[25]."' ) ")->row_array();
	
				//pincode id
				$diamond_priceData['pincode_id'] = @$resPin['pincode_id'];
			}
				
			if( empty($diamond_priceData['pincode_id']) )
			{
				//insert pincode
				$this->db->insert('pincode', array('cityname'=> $diamondArr[25], 'state_id'=> $data_pin['state_id']));
				$diamond_priceData['pincode_id'] = $this->db->insert_id();
			}
		}
	
		//dp_city_name
		$diamond_priceData['dp_city_name'] = $diamondArr[25];
	
		//dp_is_matched_pair_separable id
		//upper case key field
		$diamondArr[28] = strtoupper( $diamondArr[28] );
		$diamond_priceData['dp_is_matched_pair_separable'] = ( $diamondArr[28]=='TRUE' || $diamondArr[28]=='Y' || $diamondArr[28]=='YES' || $diamondArr[28]=='1' )? 1 : 0;
	
		//Pair Stock id
		$diamond_priceData['dp_pair_stock'] = $diamondArr[29];
	
		//dp_rapnet_lot_no id
		$diamond_priceData['dp_rapnet_lot_no'] = $diamondArr[32];
	
		//dp_report_issue_date
		$diamond_priceData['dp_report_issue_date'] = formatDate( '', $diamondArr[33] );
	
		//labour charge(Commission in %) specified by company
		$diamond_priceData['diamond_price_labour_charge'] = SOL_COMM;
	
		if( $this->session->userdata('glowstar') == "GLOWSTAR" )
		{
			$diamond_priceData['dp_price'] = $diamond_priceData['dp_rapnet_price'];
		}
		else
		{
			$diamond_priceData['dp_price'] = round( $diamond_priceData['dp_rapnet_price'] * $diamond_priceData['dp_weight'], 3);
		}
			
		$diamond_priceData['dp_price'] = lp_rev( $diamond_priceData['dp_price'], $currency_id_USD, 0); //convert to INR
	
		//cz not applicable here
		$diamond_priceData['dp_price_cz'] = 0;
	
		//diamond price specified by company after adding labour charge(Commission in %) and find total price by multiplying into weight
		if( empty( $diamond_priceData['dp_rapnet_price'] ) || empty( $diamond_priceData['dp_weight'] ) )
		{
			$diamond_priceData['dp_status_temp'] = 1;	//disable diamond if price or weight is not specified
		}
		else
		{
	
			$diamond_priceData['dp_calculated_cost'] = round( $diamond_priceData['dp_price'] / ( (100 - $diamond_priceData['diamond_price_labour_charge']) / 100 ), 3 );
			$diamond_priceData['dp_calculated_cost'] = round( $diamond_priceData['dp_calculated_cost'] / ( (100 - VAT_CHARGE) / 100 ), 3);
	
			//payGateway commision
			$payGateWayCharge = $diamond_priceData['dp_calculated_cost'];
			$diamond_priceData['dp_calculated_cost'] = round( $diamond_priceData['dp_calculated_cost'] / ( (100 - PAY_GATE_CHARGE) /100), 3);
			$payGateWayCharge = $diamond_priceData['dp_calculated_cost'] - $payGateWayCharge;
	
			$tempD = round( $payGateWayCharge / ( (100 - PAY_YOU_SER_TAX) /100), 2);	//pay gateways service tax
			$diamond_priceData['dp_calculated_cost'] = round( $diamond_priceData['dp_calculated_cost'] + ( $tempD - $payGateWayCharge ) );
	
			//10% GST charges only appicable to .au ccTLD
			if( MANUFACTURER_ID == 8 )
			{
				$diamond_priceData['dp_calculated_cost'] += $diamond_priceData['dp_calculated_cost'] * AU_GST;
			}
	
			//discount in % specified by company
			$diamond_priceData['dp_discount'] = SOL_DISC;
			$diamond_priceData['dp_final_price'] = round($diamond_priceData['dp_calculated_cost'] - ($diamond_priceData['dp_calculated_cost']*($diamond_priceData['dp_discount'] / 100)));
		}
	
		if( $diamond_price_id == 1 || ( empty($diamond_price_id) && $is_update ) )
		{
			errorLog( 'RAP_API_DEBUG', 'InsupdDiamonds function in site controller bug detected with diamond_price_id: '.$diamond_price_id.' and is_update: '.(int)$is_update.'. at: '.date("Y-m-d H:i:s") );
			return;
		}
	
		//check if exist then update else insert
		if( $is_update )
		{
			//ccTLD
			$this->diamond_priceCcTld( $is_update, $diamond_price_id, $diamond_priceData );
				
			$this->db->set('dp_modified_date', 'NOW()', FALSE)->where('diamond_price_id',$diamond_price_id)->update('diamond_price', $diamond_priceData);	//update
			echo 'Diamond updated at line '.$lineno.'<br><br>';
			//echo $this->db->last_query();
		}
		else
		{
			$this->db->insert('diamond_price', $diamond_priceData);	//insert
			$diamond_price_id = $this->db->insert_id();
	
			//ccTLD
			$this->diamond_priceCcTld( $is_update, $diamond_price_id, $diamond_priceData );
	
			echo 'Diamond inserted at line '.$lineno.'<br><br>';
			//echo $this->db->last_query();
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max
	 */
	function diamond_priceCcTld( $is_update, $diamond_price_id, &$data )
	{
		$ccTldData = array();
	
		//ccTLD data
		$ccTldData['diamond_price_id'] = $diamond_price_id;
	
		if( $is_update )
		{
			if(  MANUFACTURER_ID != 7 )
			{
				//ccTLD data
				if( isset( $data['dp_calculated_cost'] ) )
				{
					$ccTldData['diamond_price_labour_charge'] = $data['diamond_price_labour_charge'];
					$ccTldData['dp_price'] = $data['dp_price'];
					$ccTldData['dp_price_cz'] = $data['dp_price_cz'];
					$ccTldData['dp_calculated_cost'] = $data['dp_calculated_cost'];
					$ccTldData['dp_discount'] = $data['dp_discount'];
					$ccTldData['dp_final_price'] = $data['dp_final_price'];
						
					unset( $data['diamond_price_labour_charge'] );
					unset( $data['dp_price'] );
					unset( $data['dp_price_cz'] );
					unset( $data['dp_calculated_cost'] );
					unset( $data['dp_discount'] );
					unset( $data['dp_final_price'] );
				}
	
				$ccTldData['dp_status_temp'] = $data['dp_status_temp'];
				unset( $data['dp_status_temp'] );
				$this->saveupdDiamondPriceCcTld( $ccTldData );
			}
		}
		else
		{
			$resManuf = getManufacturers();
				
			//ccTLD data
			if( isset( $data['dp_calculated_cost'] ) )
			{
				$ccTldData['diamond_price_labour_charge'] = $data['diamond_price_labour_charge'];
				$ccTldData['dp_price'] = $data['dp_price'];
				$ccTldData['dp_price_cz'] = $data['dp_price_cz'];
				$ccTldData['dp_calculated_cost'] = $data['dp_calculated_cost'];
				$ccTldData['dp_discount'] = $data['dp_discount'];
				$ccTldData['dp_final_price'] = $data['dp_final_price'];
	
				unset( $data['diamond_price_labour_charge'] );
				unset( $data['dp_price'] );
				unset( $data['dp_price_cz'] );
				unset( $data['dp_calculated_cost'] );
				unset( $data['dp_discount'] );
				unset( $data['dp_final_price'] );
			}
				
			foreach( $resManuf as $k=>$ar )
			{
				$statusTemp = 1;
				if( $ar['manufacturer_id'] == 7 )	//primary Stationery.com
				{
					if( MANUFACTURER_ID != 7 )
					{
						$this->db->where( 'diamond_price_id', $diamond_price_id)->update( "diamond_price", array( 'dp_status' => $statusTemp, 'dp_status_temp' => $statusTemp ) );
					}
				}
				else
				{
					if( $ar['manufacturer_id'] == MANUFACTURER_ID )
					{
						$statusTemp = $data['dp_status_temp'];
					}
						
					$ccTldData['manufacturer_id'] = $ar['manufacturer_id'];
					$ccTldData['dp_status'] = $statusTemp;
					$ccTldData['dp_status_temp'] = $statusTemp;
					$this->saveupdDiamondPriceCcTld( $ccTldData );
				}
			}
	
			unset( $data['dp_status_temp'] );
		}
	}
	
	/**
	 * function will return dia filter price and weight min and max
	 */
	function saveupdDiamondPriceCcTld( $data )
	{
		$update="";
		foreach($data as $key=>$val)						//creates updates string to be used in query if record already exist with unique index
		{
			$val = ( $val != '' ) ? $val : 0;
			$update .= $key."='".$val."', ";
		}
		$update .= "dp_cctld_modified_date=NOW()";
	
		$this->db->query( $this->db->insert_string( "diamond_price_cctld", $data).' ON DUPLICATE KEY UPDATE '.$update );
	}
	
	/************************************ RAPNET functions end *********************************/
	
/**
 * @author Cloudwebs
 * Function will authenticate remote call using IP address so that no other then local devlopment server or dedicated server with specific IP can call cron jobs
 */	
	function authenticateByIp()
	{
		$remote_ip = $_SERVER['REMOTE_ADDR'];
		if( strpos($remote_ip, '192.168.1.') !== FALSE || $remote_ip == '119.18.54.84' || $remote_ip == '119.18.54.83' || $remote_ip == '123.201.86.111' )
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
	
/**
 * Function will email saved to database
 */	
	function emailInsertDb()
	{
		$str = ' <deepak@sanjayicecream.com>, santosh nair <santosh.nair@smmart.co.in>, <dharmesh@vendeeonline.com>, chirag hirani <suchigems@gmail.com>, Hitesh Shah <hitesh.shah@suruchemical.com>, Ismail Hamdulay <ismailhamdulay@gmail.com>, Farooque Hamdulay <fdh555@gmail.com>, Alpesh Shah <alpesh@rajeshpaper.com>, Sagar Joshi <biolifeimpexpvtltd@gmail.com>, Lokendra Singh <lokendra@msn.com>, <quadir@alfaboilers.co.in>, VIVEK NORONHA <smetals@vsnl.com>, <info@ananthalwai.com>, Ayesha Villait <ayesha@bitsy.biz>, vinod kothari <vinodbkothari@gmail.com>, Madhuri Dadia <madhuri.dadia@smm-art.com>, Mitul Shah <mitul@rajeshpaper.com>, Asia Pacific Resources <krishika@yahoo.com>, rajen jb <rajenjb@gmail.com>, sspravin888@gmail.com <sspravin888@gmail.com>, <snehapack@gmail.com>, Chandrahas Moily <chandrahas.moily@smm-art.com>, rajdhanvantry@yahoo.co.in <rajdhanvantry@yahoo.co.in>, kailash Waman <kailash@jaineeket.com>, ajayshesh@rdpathlabs.com <ajayshesh@rdpathlabs.com>, jagdish mhatre <jagdishmhatre05@gmail.com>, Santosh Kamble <director@bizcraft.co.in>, Tajuddin Hamdulay <tajuddin@alfaboilers.co.in>, Mangal Patekar <santosh.0006@yahoo.com>, <prakash@rajeshpaper.com>, Rakesh Shah <rakesh@luckyforms.com>, atul.lactose@gmail.com <atul.lactose@gmail.com>, Murtaza MONARCH <creativemonarch@gmail.com>, Patel Mahesh <patelmahesh698@gmail.com>, anshi diamond <anshidiamond@rediffmail.com>, Shanti Patel <patshanti@gmail.com>, arundhatib@almalasersindia.com <arundhatib@almalasersindia.com>, Silverline Metal <silverlinemetal@gmail.com>, <dCloudwebs@neuronclothing.com>, Dilip shah <dilip@luckyforms.com>, Chhabiraj M Rane <chhabiraj.r@chhabi-india.com>, Santosh <santosh@universalengineering.co.in>, <yatin.samant@smm-art.com>, Anurag Konher <konher.anu2107@gmail.com>, Tarique Hamdulay <tarham70@gmail.com>, sujata chauhan <sujata.chauhan@smmart.co.in>, Sanjay Vaghani <svaghani007@gmail.com>, Raghav Kulkarni <raghavk@almalasersindia.com>, <abhishek.dubey@smmart.co.in>, Dilip Mhetre <mhetrepackaging@gmail.com>, Akbar Hamdulay <akbarhamdulay@gmail.com>, sunil sagvekar <sunilsagvekar1@gmail.com>, chetan@kanangraphics.com <chetan@kanangraphics.com>, rajendra neve <raje_neve@yahoo.co.in>, Deviyani Kiron <deviyania120@yahoo.com>, Hiten <hiten@neuronclothing.com>, Rajiv Thakkar <rajiv@rajivthakkar.com>, Mehul Padiya <sales_velock@vehydraulics.com>, <khalid@rediffmail.com>, DP Super Blanks <dp@superblanks.com>, Cloudwebs B Sanghvi <sales@halstondistributors.in>, <info@winnershashi.com>, hitesh mangukiya <krishifashions@gmail.com>, Tushar Pandya <ronaktp@gmail.com>, Hetal Shukla <hetal346@yahoo.com>, Dipin .V <dipin@syamadynamic.com>, Juzar Unwala <info@burhanjewellers.net>, Vishal Shah <bvchain@gmail.com>, Mahesh Mangukiya <mitha207@gmail.com>, Paresh Patel <pparesh@gmail.com>, Veena Gandhi <veenamg@hotmail.com>, Ajay Pathak <mail2ajayshorizon@gmail.com>, <kinjal.seerial@gmail.cim>, Bilal Hamdulay <bkhamdulay@gmail.com>, Kanji Patel <patelkanji75@yahoo.com>, Raja Shantanu <shantanumdj@gmail.com>, Satish A Valliat <satish@bitsy.biz>, Sangita Maheshwari <sangita.lactose@gmail.com>, chaitanya sheth <chaitanya@wudtools.com>, <kkamlesh@jyotipower.net>, <kulincorpn@hotmail.com>, Rajesh Patel <pewexd@gmail.com>, <shomil@vsnl.com>, Rasik P <mrasikp@yahoo.com>, hiten shah <hiten.7@gmail.com>, Anita Dighe <sanjaydighe2050@gmail.com>, Swapnil Kashikar <shreewire@hotmail.com>, Sonali Kashikar <sonalikashikar@yahoo.co.in>, hitesh chheda <hitesh651@rediffmail.com>, sujata chauhan <sujata.chauhan@smm-art.com>, Rakesh Sachdev <rakesh@acetech-india.com>, <nayan.kotian@smm-art.com>, Nishit Shah <nishitps@yahoo.com>, Sarah Hamdulay <sfhamdulay@gmail.com>, Ashok Jadhav <ashokj75@rediffmail.com>, Ramakant Tekade <rgt4@rediffmail.com>, <nkbapvl@gmail.com>, <nilesh@raysgems.com>, <irfan.dabeer@smm-art.com>, Sagar Gadodiya <shaktiepl@gmail.com>, Satya Narayan <snsahu.12011@gmail.com>, Santosh Nikam <santosh@snwealth.com>, Ashish Doshi <ashish@hasmukhdoshinsons.com>, Lakshmi Nair <lakshmi.nair@snkpm.com>, Ashwini Jadhav <ashwini.jadhav@snkpm.com>, marina barboz <marina.barboz@snkpm.com>, Sindhu Nair <sindhu.nair@smmart.co.in>, Kundan Gurav <kundan.gurav@smm-art.com>, Shrikant Somani <shrikant.somani@smm-art.com>, T V Gomathi <gomathi.tv@smmart.co.in>, Jagruti Keny <jagruti.keny@smm-art.com>, Priyal Jobalia <priyal.jobalia@smmart.co.in> ';
		
		preg_match_all('/\<(.*?)\>/',$str,$match);		
		$strArr = ($match[1]);
		$cnt = 0;
		//$strArr = explode('; ',$str);
		foreach($strArr as $k=>$val)
		{
			/*$data = array('email_id' => trim( $val ),
						'el_optout_level' => 1,
						'el_status' => 'S',
						'el_reference_source' => 'RASIK_GENERAL',
						'el_priority_level' => 20
					);
			$this->db->insert('email_list',$data);*/
			saveEmailList($val, 1, 'S', 'RASIK_GENERAL', 20); //save email_list table
			$cnt++;
		}
		echo 'successfully saved total='.$cnt;
	}
	
	
	function stateInsertDB()
	{
		$str = '6417229">Castel
				  6269131">England
				  6417223">Forest
				  3237203">Grouville
				  2641364">N Ireland
				  6417226">Saint Andrew
				  3237200">Saint Brelade
				  3237864">Saint Helier
				  3237497">Saint John
				  3237214">Saint Lawrence
				  3237716">Saint Martin
				  6417224">Saint Martin
				  3237212">Saint Mary
				  3237229">Saint Ouen
				  3237221">Saint Peter
				  6417228">Saint Peter Port
				  6417213">Saint Pierre du Bois
				  6417233">Saint Sampson
				  3237073">Saint Saviour
				  6417215">Saint Saviour
				  2638360">Scotland
				  3237072">St Clement
				  6417214">Torteval
				  3237530">Trinity
				  6417230">Vale
				  2634895">Wales';
			
			$strArr = explode('">',$str);
			$cnt = 0;
			foreach($strArr as $k=>$val)
			{
				$arrVal = (explode("\n",$val));
				//pr($arrVal);
				if(!is_numeric($arrVal[0]))
				{
					$data = array('country_id' => 237,
								'state_name' => $arrVal[0],
								'state_key' => strtoupper($arrVal[0]),
								'state_status' => 0
							);
					$this->db->insert('state',$data);
					$cnt++;
				}
			}
			echo 'successfully saved total='.$cnt;			
	}
	
	
	/************************************ eBay functions **************************************/
	
	/**
	 * @author Cloudwebs
	 * Batch Processing: Function will read Ebay Listing csv and process each row(product) one by one
	 */
	function processEbayListingCsv( $filepath='' ) 
	{
		//function specific logic
		$this->load->helper( 'ebay' );
		$data = $this->input->get();
	
	
		$time_elapsed = time();
		setTimeLimit();
	
		//change: switched to batch processing Date: 8/4/2014
		$start = $end = 0;
		$nubRecords = 1;
	
		//read process index
		$start = (int)exeQuery( " SELECT t_value FROM temp WHERE t_name='EBAY_LISTING_CSV_INDEX_".MANUFACTURER_ID."' ", true, "t_value" );
		if( $start == -1 )
		{
			//batch processes completed
			errorLog( 'EBAY_XML_API', 'EBAY_XML_API process: Mode=> '.$data['mode'].': batch processes already completed.' );
			exit;
		}
	
		if(  $filepath == '' )
		{
			$filepath = exeQuery( " SELECT t_value FROM temp WHERE t_name='EBAY_LISTING_CSV_".MANUFACTURER_ID."' ", true, "t_value");
				
			if( empty($filepath) )
			{
				echo 'No filepath provided. Function execution aborted.';
				return;
			}
		}
	
		$csvRowArr = readCsvNew( $filepath );
		$keyArr = array_keys( $csvRowArr );
		$size = sizeof( $keyArr );
	
		$end = ( ( $start + $nubRecords ) < $size ? ( $start + $nubRecords ) : $size );
		if( $start < $size )
		{
			for ( $i=$start; $i<$end; $i++ )
			{
				//display to console
				echo "Process start for Product Generated Code: ".trim( $csvRowArr[ $keyArr[ $i ] ][0] )."<br><br>";
	
				//list ebay item one by one
				$is_success = $this->ebayListing( $data['mode'], trim( $csvRowArr[ $keyArr[ $i ] ][0] ), true );
			}
	
			//update process index
			$this->db->query(" UPDATE temp SET t_value=".$end." WHERE t_name='EBAY_LISTING_CSV_INDEX_".MANUFACTURER_ID."' ");
				
			$time_elapsed = (time() - $time_elapsed);
				
			//log
			errorLog( 'EBAY_XML_API', ' EBAY_XML_API process: Mode=> '.$data['mode'].': Process '.( $is_success == true ? 'successful' : 'failed' ).'. batch completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed );
		}
		else
		{
			//reset process index
			$this->db->query(" UPDATE temp SET t_value=-1 WHERE t_name='EBAY_LISTING_CSV_INDEX_".MANUFACTURER_ID."' ");
			$time_elapsed = (time() - $time_elapsed);
				
			//log
			errorLog( 'EBAY_XML_API', 'EBAY_XML_API process: Mode=> '.$data['mode'].': process fully completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed );
		}
	
		echo 'Operation completed in '.$time_elapsed.' seconds.';
	}
	
	/**
	 * @author Cloudwebs
	 * Batch Processing: Function will read Ebay Listing from ebay_products table and process each product one by one
	 */
	function processEbayListing()
	{
		//function specific logic
		$this->load->helper( 'ebay' );
		$data = $this->input->get();
		$resEbay;
	
		$time_elapsed = time();
		setTimeLimit();
	
		$start = $end = 0;
		if( empty( $data['mode'] ) || !in_array( $data['mode'], array( 1,2,3,5,7 ) ) )
		{
			//batch processes completed
			errorLog( 'EBAY_XML_API', 'EBAY_XML_API process: Mode=> '.@$data['mode'].': Invalid/empty mode detected.' );
			exit;
		}
		
		//ebay auto listing configuration
		$tableConfig = ( MANUFACTURER_ID !=7 ) ? 'configuration_cctld' : 'configuration';
		$ebayAutoListing = getField('config_value',$tableConfig,'config_key','EBAY_AUTO_LISTING');
	
		//read process index
		$tempoRow = exeQuery( " SELECT t_value, t_no_of_records FROM temp WHERE t_name='EBAY_LISTING_DB_INDEX_".MANUFACTURER_ID."' " );
		$start = $tempoRow['t_value'];
		if( $start == -1 && $data['mode'] != 5 || $ebayAutoListing == 0 )	// != 5: run relisting endlessely
		{
			//batch processes completed
			echo 'EBAY_XML_API process: Mode=> '.$data['mode'].': DB batch processes already completed.'; 
			errorLog( 'EBAY_XML_API', 'EBAY_XML_API process: Mode=> '.$data['mode'].': DB batch processes already completed.' );
			exit;
		}
	
		if( $data['mode'] == 7 )
		{
			$resEbay = executeQuery( " SELECT product_price_id
									   FROM product_price 
									   WHERE product_price_status=0 AND product_price_id NOT IN ( SELECT DISTINCT product_price_id FROM ebay_product WHERE ep_status=0 ) 
									   GROUP BY product_id 
									   ORDER BY product_price_id LIMIT ".$tempoRow['t_value'].", ".$tempoRow['t_no_of_records']." " );  
		}
		else if( $data['mode'] == 1 )
		{
			$resEbay = executeQuery( " SELECT product_price_id
									   FROM ebay_product
									   WHERE ep_status=0
									   ORDER BY ebay_products_id LIMIT ".$tempoRow['t_value'].", ".$tempoRow['t_no_of_records']." " );
		}
		else if( $data['mode'] == 2 || $data['mode'] == 3 )
		{
			$resEbay = executeQuery( " SELECT product_price_id
									   FROM ebay_product
									   WHERE ep_status=0 AND ( ep_item_id<>'' AND ep_item_id IS NOT NULL )
									   ORDER BY ebay_products_id LIMIT ".$tempoRow['t_value'].", ".$tempoRow['t_no_of_records']." " );
		}
		else if( $data['mode'] == 5 )
		{
			$resEbay = executeQuery( " SELECT product_price_id, ebay_products_id, ep_mode 
									   FROM ebay_product
									   WHERE product_id in (select product_id from product_cctld where manufacturer_id=".MANUFACTURER_ID." AND product_status=0) AND ep_status=0 AND ep_i$city_arruto_listing=1 AND ( ep_item_id<>'' AND ep_item_id IS NOT NULL )
									   AND ep_created_date < timestampadd(day, -ep_listing_duration, NOW() )
									   AND ( ep_modified_date = '0000-00-00 00:00:00' OR ep_modified_date IS NULL OR ep_modified_date < timestampadd( day, -ep_listing_duration, NOW()) )
									   ORDER BY ebay_products_id LIMIT 2 " );
		}
	
		$end = $start + $tempoRow['t_no_of_records'];
		if( !empty( $resEbay ) )
		{
			foreach ( $resEbay as $k=>$ar)
			{
				//display to console
				echo "\n\nProcess started for Product Price ID: ".trim( $ar['product_price_id']."\nEbay Product Id: ".@$ar['ebay_products_id']."\nMode: ".$data["mode"]."\n" );
	
				//list ebay item one by one
				$_GET['ebay_products_id'] = $ar['ebay_products_id'];
				$_GET['add_mode'] = $ar['ep_mode'];
				$is_success = $this->ebayListing( $data['mode'], '', true, $ar['product_price_id'], $ar['ebay_products_id'], $ar['ep_mode'] );
				if($is_success == false)
				{
					$this->db->query( "UPDATE ebay_product SET ep_modified_date=NOW() WHERE ebay_products_id = ".$ar['ebay_products_id'] );
				}
			}
	
			//update process index
			if( $data['mode'] != 5 )
			{
				$this->db->query(" UPDATE temp SET t_value=".$end." WHERE t_name='EBAY_LISTING_DB_INDEX_".MANUFACTURER_ID."' ");
			}
				
			$time_elapsed = (time() - $time_elapsed);
			//log
			errorLog( 'EBAY_XML_API', ' EBAY_XML_API process: Mode=> '.$data['mode'].': DB batch Process '.( $is_success == true ? 'successful' : 'failed' ).'. batch completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed );
		}
		else
		{
			//reset process index
			if( $data['mode'] != 5 && $data['mode'] != 7 )
			{
				$this->db->query(" UPDATE temp SET t_value=-1 WHERE t_name='EBAY_LISTING_DB_INDEX_".MANUFACTURER_ID."' ");
			}
				
			$time_elapsed = (time() - $time_elapsed);
			//log
			errorLog( 'EBAY_XML_API', 'EBAY_XML_API process: Mode=> '.$data['mode'].': DB batch process fully completed at start: '.$start.' and end:'.$end.' index. <br> Time taken:'. $time_elapsed );
		}
	
		echo 'Operation completed in '.$time_elapsed.' seconds.'; 
	}
	
	/**
	 * @param $mode 1: add,2: update,3: delete, 5:relist, 7: add automatically from cron, 10: add with type acution(Item)
	 * @author Cloudwebs
	 * function will process ebay store listing
	 */
	function ebayListing( $mode='', $product_generated_code='', $is_helper_loaded=false, $product_price_id=0, $ebay_products_id='', $ep_mode='' )
	{
		if( !$is_helper_loaded )
		{
			$this->load->helper( 'ebay' );
		}
	
		if( empty( $mode ) )
		{
			$mode = $this->input->get('mode');
		}
	
		if( empty( $product_generated_code ) )
		{
			$product_generated_code = $this->input->get('product_generated_code');
		}
	
		if( empty( $product_price_id ) )
		{
			$product_price_id = $this->input->get('product_price_id');
		}
	
	
		if( !empty( $mode ) && ( !empty( $product_generated_code ) || !empty( $product_price_id ) ) )
		{
			if( $mode == 1 || $mode == 7 || $mode == 10 || $mode == 9 )
			{
				$type = "FixedPriceItem"; 
				if($mode == 10 || $mode == 9)
				{
					$type = "Item";	
				}
				
				if( addeBayItem( $product_generated_code, '', $product_price_id, $type, $mode ) )
				{
					echo 'Item added successfully.';
					return true;
				}
				else
				{
					echo 'There is some error while adding an item to eBay Listing server.';
					return false;
				}
			}
			else if( $mode == 2 )
			{
				$add_mode = $this->input->get('add_mode');
 				$type = "FixedPriceItem"; 
				if($add_mode == 10 || $add_mode == 9)
				{
					$type = "Item";	
				}
				
				if( updateeBayItem( $product_generated_code, '', $product_price_id, $type, $add_mode ) )
				{
					echo 'Item updated successfully.';
					return true;
				}
				else
				{
					echo 'There is some error while updating an item to eBay Listing server.';
					return false;
				}
			}
			else if( $mode == 3 )
			{
				$add_mode = $this->input->get('add_mode');
 				$type = "FixedPriceItem"; 
				if($add_mode == 10 || $add_mode == 9)
				{
					$type = "Item";	
				}
				
				if( deleteeBayItem( $product_generated_code, '', $product_price_id, $type, $add_mode ) )
				{
					echo 'Item deleted successfully.';
					return true;
				}
				else
				{
					echo 'There is some error while deleting an item from eBay Listing server.';
					return false;
				}
			}
			else if( $mode == 5 )
			{
				$add_mode = $this->input->get('add_mode');
 				$type = "FixedPriceItem"; 
				if($add_mode == 10 || $ep_mode == 10 || $add_mode == 9)
				{
					$type = "Item";	
				}
				
				if( relisteBayItem( $product_generated_code, '', $product_price_id, $type, $ebay_products_id, $add_mode ) )
				{
					echo 'Item relisted successfully.';
					return true;
				}
				else
				{
					//echo 'There is some error while relisting an item from eBay Listing server.';
					return false;
				}
			}
		}
	
	}
/*
* Function will get all product from ebay seller
*/	
	function fetchEbayProduct()
	{
		//function specific logic
		$this->load->helper( 'ebay' );
		$eBayCommon = new eBayCommon( "FixedPriceItem" );
		$eBayConfig = geteBayConfigurations(); 
		
		//pr($eBayConfig);die;
		
		setTimeLimit();
				
		$this->devID = $eBayConfig['devID'];
		$this->appID = $eBayConfig['appID'];
		$this->certID = $eBayConfig['certID'];
		$this->serverUrl = $eBayConfig['serverUrl'];
		//Use this URL for sandbox mode: https://api.sandbox.ebay.com/ws/api.dll
		//Use this URL for production mode: https://api.ebay.com/ws/api.dll
		$this->userToken = $eBayConfig['userToken'];
		$this->compatLevel = $eBayConfig['CompatabilityLevel'];
		
		$this->username = $eBayConfig['USER_NAME'];
		
		//SiteID must also be set in the Request's XML
		//SiteID = 0  (US) – UK = 3, Canada = 2, Australia = 15, ….
		//SiteID Indicates the eBay site to associate the call with
		
		$this->siteID = $eBayConfig['siteID'];

		$this->verb = 'GetSellerList'; //API call name
		$this->StartTimeFrom = '2014-10-30T21:59:59.005Z';
		$this->StartTimeTo = '2014-11-10T21:59:59.005Z';
		$this->EntriesPerPage = '200';
		
		$headers = array (
		//Regulates versioning of the XML interface for the API
		'X-EBAY-API-COMPATIBILITY-LEVEL: ' . $this->compatLevel,
		
		//set the keys
		'X-EBAY-API-DEV-NAME: ' . $this->devID,
		'X-EBAY-API-APP-NAME: ' . $this->appID,
		'X-EBAY-API-CERT-NAME: ' . $this->certID,
		
		//the name of the call we are requesting
		'X-EBAY-API-CALL-NAME: ' . $this->verb,
		
		'X-EBAY-API-SITEID: ' . $this->siteID,
		);
		
		//Build the request Xml string
		$requestXmlBody ='<?xml version="1.0" encoding="utf-8" ?>
		<GetSellerListRequest xmlns="urn:ebay:apis:eBLBaseComponents">
		<RequesterCredentials>
		<eBayAuthToken>'.$this->userToken.'</eBayAuthToken>
		</RequesterCredentials>
		<UserID>'.$this->username.'</UserID>
		<GranularityLevel>CustomCode</GranularityLevel>
		<StartTimeFrom>'.$this->StartTimeFrom.'</StartTimeFrom>
		<StartTimeTo>'.$this->StartTimeTo.'</StartTimeTo>
		<Pagination>
		<EntriesPerPage>'.$this->EntriesPerPage.'</EntriesPerPage>
		</Pagination>
		</GetSellerListRequest>';
		
		//build eBay headers using variables passed via constructor
		
		//initialise a CURL session
		$connection = curl_init();
		//set the server we are using (could be Sandbox or Production server)
		curl_setopt($connection, CURLOPT_URL, $this->serverUrl);
		
		//stop CURL from verifying the peer's certificate
		curl_setopt($connection, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($connection, CURLOPT_SSL_VERIFYHOST, 0);
		
		//set the headers using the array of headers
		curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
		
		//set method as POST
		curl_setopt($connection, CURLOPT_POST, 1);
		
		//set the XML body of the request
		curl_setopt($connection, CURLOPT_POSTFIELDS, $requestXmlBody);
		
		//set it to return the transfer as a string from curl_exec
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);
		
		//Send the Request
		$response = curl_exec($connection);
		
		//close the connection
		curl_close($connection);
		
		if(stristr($response, 'HTTP 404') || $response == "")
			die('<P>Error sending request');
		
		$resultXmlData = simplexml_load_string($response);
		
		pr($resultXmlData);die;
		
		$this->formatResponseData($resultXmlData);
	}
	
	function formatResponseData($response)
	{
		$resArr = $response->ItemArray->Item;
		if(is_object($resArr))
		{
			$resData = array();
			foreach($resArr as $res)
			{
				$resData['ItemID'] = $res->ItemID;
				$resData['ItemURL'] = $res->ListingDetails->ViewItemURL;
				$resData['CurrentPrice'] = $res->SellingStatus->CurrentPrice;
				$resData['Title'] = $res->Title;
				$resData['GalleryURL'] = $res->PictureDetails->GalleryURL;
				$resData['CategoryID'] = $res->PrimaryCategory->CategoryID;
				$resData['CategoryName'] = $res->PrimaryCategory->CategoryName;
				$resData['SKU'] = $res->SKU;
				
				//$this->createEbayPostData($resData);
				pr($res);die;
			}
		}		
	}
		
	function createEbayPostData($resData)
	{
		
		//field store in product category
		$resData['category_id'] = $this->saveCategoryData($resData);	
		
		//field store in product
		$this->saveProductData($resData);
		
	}
	
	/************************************ eBay functions end **********************************/
	
	
	
	/************************************ Product functions **************************************/
	
	/**
	 * @author Cloudwebs
	 * Function will update solitaire prices each day after updating
	 */
	function updateProductPrices()
	{
		setTimeLimit();
		$time_elapsed = time();
		$numRecords = 10;
		$res = null;
	
		//read process index
		$row = fetchRow( " SELECT * FROM temp WHERE t_name='UPD_PROD_PRICES_".MANUFACTURER_ID."' " );
		$start = $row['t_value'];
	
		//fetch product as per limit
		if( MANUFACTURER_ID == 7 )
		{
			$res = executeQuery( "SELECT p.product_id FROM product p LIMIT ".$start.",".$numRecords." " );
		}
		else
		{
			$res = executeQuery( "SELECT p.product_id FROM product p INNER JOIN product_cctld prc
								  ON ( prc.manufacturer_id=".MANUFACTURER_ID." AND prc.product_id=p.product_id )
								  LIMIT ".$start.",".$numRecords." " );
		}
	
		if( !empty( $res ) )
		{
			$cnt = 0;
			foreach( $res as $k=>$ar )
			{
				$cnt++;
				//In update mode change all price status to 1-disabled as some combinations may be deselected
				if( MANUFACTURER_ID == 7 )
				{
					$this->db->query("update product_price SET product_price_status_temp=1,product_price_modified_date=NOW() WHERE product_id=".$ar['product_id']."");
				}
				else
				{
					$this->db->query("update product_price_cctld SET product_price_status_temp=1,product_price_cctld_modified_date=NOW()
									  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
									 ( SELECT product_price_id FROM product_price WHERE product_id=".$ar['product_id']." )");
				}
	
				//update/insert product pricing
				update_insertProductPrice( $ar['product_id'] );
	
				if( MANUFACTURER_ID == 7 )
				{
					$this->db->query("update product_price SET product_price_status=product_price_status_temp WHERE product_id=".$ar['product_id']."");
				}
				else
				{
					$this->db->query("update product_price_cctld SET product_price_status=product_price_status_temp
									  WHERE manufacturer_id=".MANUFACTURER_ID." AND product_price_id IN
									 ( SELECT product_price_id FROM product_price WHERE product_id=".$ar['product_id']." ) ");
				}
			}
	
			echo $cnt." Products prices updated within Index Limit: ".$start." to ". ($start+$numRecords)."<br>" ;
		}
		else
		{
			//t_no_of_records is used to decide if removing cache group is neccessary only if it's value is 0
			if( (int)$row['t_no_of_records'] == 0 )
			{
				$_GET['c_key_group'] = 'filter';
				//$this->remCacheGroup();
	
				//update index that cache had been cleared
				$this->db->query( " UPDATE temp SET t_no_of_records=-1 WHERE t_name='UPD_PROD_PRICES_".MANUFACTURER_ID."' " );
	
				echo "Filter cache removed.<br>";
			}
	
			echo "All Product prices seems updated no products found within Index Limit: ".$start." to ". ($start+$numRecords)."<br>" ;
		}
	
		//update index in tempo
		$this->db->query( " UPDATE temp SET t_value=".($start+$numRecords)." WHERE t_name='UPD_PROD_PRICES_".MANUFACTURER_ID."' " );
	
		echo  "<br><br>MANUFACTURER_ID: ".MANUFACTURER_ID."=> Operation completed in ".(time() - $time_elapsed)." seconds.";
	}
	
	
	//Function will saved into product category database
	function saveCategoryData($data)
	{
		$_POST['ebay_category_id'] = $data['CategoryID'];
		$_POST['category_name'] = "".$data['CategoryName']."";
		$_POST['category_alias'] = "".strtolower(str_replace(" ","-",$data['CategoryName']))."";
		$_POST['category_meta_name'] = "".$data['CategoryName']."";
		$_POST['ebay_store_category_id'] = 0;
		$_POST['parent_id'] = 0;
		$_POST['category_status'] = 0;
		$_POST['category_sort_order'] = 0;
		$_POST['category_description'] = "";
		$_POST['custom_page_title'] = "".$data['CategoryName']."";
		$_POST['meta_keyword'] = "";
		$_POST['meta_description'] = "";
		$_POST['category_meta_name'] = "";
		$_POST['category_brand_code'] = "";
		$_POST['category_royalty'] = 0;
		$_POST['category_adv_cost'] = 0;
		$_POST['category_image'] = "";
		$_POST['category_banner'] = "";
		$_POST['m_category_image'] = "";
		
		$this->load->model('admin/mdl_product_categories','cat');
		
		$getCategoryId = exeQuery( " SELECT category_id FROM product_categories WHERE ebay_category_id = '".$_POST['ebay_category_id']."' ", true, 'category_id' ); 
		
		$this->cat->cPrimaryId  = ($getCategoryId) ? $getCategoryId : "";
		$this->cat->cAutoId  = "category_id";
		$this->cat->cTableName = "product_categories";
		
		$category_id = $this->cat->saveData();	
		
		return $category_id;
		//echo 'test='.$category_id;die;
		
	}
	
	//Function will saved into product database
	function saveProductData($data)
	{	
		$_POST['product_name'] = "".$data['Title']."";
		$_POST['product_alias'] = "".$data['ItemURL']."";
		$_POST['category_id'] = (!empty($data['category_id'])) ? $data['category_id'] : $data['CategoryID'];
		$SKU = (!empty($data['SKU'])) ? $data['SKU'] : $data['ItemID'];
		$_POST['product_sku'] = ""."SKU_".$SKU."";
		$_POST['product_price'] = $data['CurrentPrice'];
		$_POST['product_offer_id'] = 0;
		$_POST['product_manufacturer_id'] = MANUFACTURER_ID;
		$_POST['product_angle_in'] = "";
		$_POST['product_image'] = "";
		$_POST['product_video'] = "";
		$_POST['product_short_description'] = "";
		$_POST['product_description'] = "";
		$_POST['product_gender'] = "F";
		$_POST['product_accessories'] = detectAccessories($data['CategoryName'], false);
		$_POST['ring_size_region'] = "";
		$_POST['product_metal_priority_id'] = 27;
		$_POST['product_status'] = 1;
		$_POST['product_sort_order'] = 0;
		$_POST['custom_page_title'] = "".$data['Title']."";
		$_POST['meta_keyword'] = "";
		$_POST['meta_description'] = "";
		$_POST['product_discount'] = 0;
		$_POST['product_shipping_cost'] = 0;
		$_POST['product_cod_cost'] = 0;
		$_POST['product_tax_id'] = 0;
		$_POST['product_tags'] = "";
		$_POST['product_internal_note'] = "";
		$_POST['product_related_keywords'] = "";
		$_POST['product_related_products_id'] = "";
		$_POST['product_related_category_id'] = "";
		
		
		//field stored in product_value table
		$_POST['product_value_height'] = "";
		$_POST['product_value_width'] = "";
		$_POST['product_value_weight'] = "";
		$_POST['product_value_quantity'] = "";
		$_POST['product_value_notification_level'] = "";
		$_POST['product_value_maximum_purchase'] = "";
		$_POST['stock_status_id'] = 0;
		
		//field store in product_center_stone
		$_POST['pcs_diamond_shape_id'] = 0;
		$_POST['product_center_stone_size'] = 0;
		$_POST['product_center_stone_weight'] = 0;
		$_POST['product_center_stone_total'] = 0;
		$_POST['cs_p'][] = 0;
		
		//field are stored in product_side_stone1 table
		$_POST['pss1_diamond_shape_id'] = 0;
		$_POST['product_side_stone1_size'] = 0;
		$_POST['product_side_stone1_weight'] = 0;
		$_POST['product_side_stone1_total'] = 0;
		$_POST['ss1_p'][] = 0;

		//field are stored in product_side_stone2 table
		$_POST['pss2_diamond_shape_id'] = 0;
		$_POST['product_side_stone2_size'] = 0;
		$_POST['product_side_stone2_weight'] = 0;
		$_POST['product_side_stone2_total'] = 0;
		$_POST['ss2_p'][] = 0;
		
		$_POST['product_metal_weight_19'] = "";
		$_POST['product_metal_weight_20'] = "";
		$_POST['product_metal_weight_21'] = "";
		$_POST['product_metal_weight_22'] = "";
		$_POST['product_metal_weight_23'] = "";
		$_POST['product_metal_weight_24'] = "";
		$_POST['product_metal_weight_25'] = "";
		$_POST['product_metal_weight_26'] = "";
		$_POST['product_metal_weight_27'] = "";
		$_POST['product_metal_weight_28'] = "";
		$_POST['mt_p'][] = 0;
		
		
		$this->load->model('admin/mdl_product','prod');
		
		$getProductId = exeQuery( " SELECT product_id FROM product WHERE category_id = '".$_POST['category_id']."' ", true, 'product_id' ); 
		
		$_POST['is_selection_updated'] = ($getProductId) ? 1 : 0;
		$this->prod->cPrimaryId  = ($getProductId) ? $getProductId : "";
		$this->prod->cAutoId  = "product_id";
		$this->prod->cTableName = "product";
		
		//pr($_POST);die;
		$product_id = $this->prod->saveData();
		
		$imagefolder = 'assets/product/'.$_POST['product_sku'].'/';
		mkDirectory($imagefolder);
		$path = saveImageFromUrl($data['GalleryURL'], 'product/'.$_POST['product_sku']);
		
		$getProductPriceId = exeQuery( " SELECT product_price_id FROM product_price WHERE product_id = '".$product_id."' ", true, 'product_price_id' ); 
		
		
		$this->load->helper( 'ebay' );
		$ebaySiteArr = getEbayCountryCode();
		
		//field are stored in ebay_product 
		$_POST['product_id'] = $product_id;
		$_POST['product_price_id'] = $getProductPriceId;
		$_POST['ebay_site_id'] = $ebaySiteArr['country_id'];
		$_POST['ebay_item_id'] = $data['ItemID'];
		$_POST['ebay_title'] = "".$data['Title']."";
		$_POST['ebay_price'] = $data['CurrentPrice'];
		$_POST['ebay_duration'] = 3;
		$_POST['ebay_qty'] = 1;
		
		$this->load->model('admin/mdl_ebay_product','eprod');
		$this->eprod->saveEbayData();
	}
	
	
	//Function will saved into ebay product database
	function testSaveProductData($data)
	{
		//field store in product table
		/*$productData['product_name'] = $data['Title'];
		$productData['product_alias'] = $data['ItemURL'];
		$productData['category_id'] = (!empty($data['category_id'])) ? $data['category_id'] : "";
		$productData['product_sku'] = $data['ItemID'];
		$productData['product_price'] = $data['CurrentPrice'];
		$productData['product_offer_id'] = 0;
		$productData['product_manufacturer_id'] = "";
		$productData['product_angle_in'] = "";
		$productData['product_image'] = "";
		$productData['product_video'] = "";
		$productData['product_short_description'] = "";
		$productData['product_description'] = "";
		$productData['product_gender'] = "F";
		$productData['product_accessories'] = "";
		$productData['ring_size_region'] = "";
		$productData['product_metal_priority_id'] = 27;
		$productData['product_status'] = 0;
		$productData['product_sort_order'] = 0;
		$productData['custom_page_title'] = "";
		$productData['meta_keyword'] = "";
		$productData['meta_description'] = "";
		$productData['product_discount'] = 0;
		$productData['product_shipping_cost'] = 0;
		$productData['product_cod_cost'] = 0;
		$productData['product_tax_id'] = 0;
		$productData['product_tags'] = "";
		$productData['product_internal_note'] = "";
		$productData['product_related_keywords'] = "";
		$productData['product_related_products_id'] = "";
		$productData['product_related_category_id'] = "";
		
		
		//field stored in product_value table
		$dt_prodval['product_value_height'] = "";
		$dt_prodval['product_value_width'] = "";
		$dt_prodval['product_value_weight'] = "";
		$dt_prodval['product_value_quantity'] = "";
		$dt_prodval['product_value_notification_level'] = "";
		$dt_prodval['product_value_maximum_purchase'] = "";
		$dt_prodval['stock_status_id'] = 0;
		
		//field store in product_center_stone
		$dt_cs['pcs_diamond_shape_id'] = 0;
		$dt_cs['product_center_stone_size'] = 0;
		$dt_cs['product_center_stone_weight'] = 0;
		$dt_cs['product_center_stone_total'] = 0;
		
		//field are stored in product_side_stone1 table
		$dt_ss1['pss1_diamond_shape_id'] = 0;
		$dt_ss1['product_side_stone1_size'] = 0;
		$dt_ss1['product_side_stone1_weight'] = 0;
		$dt_ss1['product_side_stone1_total'] = 0;		

		//field are stored in product_side_stone2 table
		$dt_ss2['pss2_diamond_shape_id'] = 0;
		$dt_ss2['product_side_stone2_size'] = 0;
		$dt_ss2['product_side_stone2_weight'] = 0;
		$dt_ss2['product_side_stone2_total'] = 0;
		
		$this->load->model('admin/mdl_product','prod');
		$this->prod->saveData();
		
		$product_id = exeQuery( "SELECT product_id FROM product WHERE product_sku = '".$data['product_sku']."' AND category_id = '".$data['category_id']."' ", true, "product_id" );
		if($product_id == "")
		{
			$this->db->insert('product', $productData);
			$product_id = $this->db->insert_id();
			
			$dt_prodval['product_id'] = $product_id;
			$this->db->insert("product_value",$dt_prodval);
			
			$dt_cs['product_id'] = $product_id;
			$dt_cs['category_id'] = $data['category_id'];
			$this->db->insert("product_center_stone",$dt_cs);
			
			$dt_ss1['product_id'] = $product_id;
			$dt_ss1['category_id'] = $data['category_id'];
			$this->db->insert("product_side_stone1",$dt_ss1);
			
			$dt_ss2['product_id'] = $product_id;
			$dt_ss2['category_id'] = $data['category_id'];
			$this->db->insert("product_side_stone2",$dt_ss2);
			
			$dt_mt['product_id'] = $product_id;
			$dt_mt['category_id'] = $data['category_id'];
			$dt_mt['product_metal_weight'] = 0;
			$this->db->insert("product_metal",$dt_mt);
		}
		else // update into database
		{
			
			$this->db->where('product_id', $product_id);
			$this->db->update('product', $productData);
		}
		return $product_id;
		
		$eBayData['ep_item_id'] = $data['ItemID'];
		$eBayData['ep_product_price'] = $data['CurrentPrice'];
		$eBayData['ep_title'] = $data['Title'];
		
		$ebay_product_id = exeQuery( "SELECT ebay_products_id FROM ebay_product WHERE product_id = '".$data['product_id']."' AND product_price_id = '".$data['product_price_id']."' ", true, "ebay_products_id" );
		if($ebay_products_id == "")
		{
			$this->db->insert('ebay_product', $eBayData);
		}
		else
		{
			$this->db->where('ebay_products_id', $ebay_products_id);
			$this->db->update('ebay_product', $eBayData);
		}
		*/
	}
	
	function updProductPriceCctld()
	{
		die;
		$res = executeQuery( " SELECT * FROM product_price " );
		if(!empty($res))
		{
			$cnt=0;
			foreach($res as $val)
			{
				$product_price_cctld_id = exeQuery( "SELECT product_price_cctld_id FROM product_price_cctld WHERE product_price_id = '".$val['product_price_id']."' AND manufacturer_id = '".MANUFACTURER_ID."' ", true, "product_price_cctld_id" );
				$data = array(
						'product_generated_code' => $val['product_generated_code'],
						'product_price_id' => $val['product_price_id'],
						'manufacturer_id' => MANUFACTURER_ID,
						'product_price_calculated_price' => $val['product_price_calculated_price'],
						'product_price_calculated_price_cz' => $val['product_price_calculated_price_cz'],
						'product_price_calculated_price_mount' => $val['product_price_calculated_price_mount'],
						'product_discount' => $val['product_discount'],
						'product_discount_cz' => $val['product_discount_cz'],
						'product_discount_mount' => $val['product_discount_mount'],
						'product_discounted_price' => $val['product_discounted_price'],
						'product_discounted_price_cz' => $val['product_discounted_price_cz'],
						'product_discounted_price_mount' => $val['product_discounted_price_mount'],
						'product_price_status' => '0',
						'product_price_status_temp' => '0'
					);
				if(!empty($product_price_cctld_id))
				{
					$this->db->where('product_price_cctld_id', $product_price_cctld_id);
					$this->db->update('product_price_cctld',$data);
				}
				else
				{
					$this->db->insert('product_price_cctld',$data);
				}
				$cnt++;
			}
			echo 'Operation successfull total '.$cnt.' updated.';
		}
		else
		{
			echo 'Result not found.';
		}
	}
	
	function updProductCctld()
	{
		die;
		$res = executeQuery( " SELECT * FROM product " );
		if(!empty($res))
		{
			$cnt=0;
			foreach($res as $val)
			{
				$product_cctld_id = exeQuery( "SELECT product_cctld_id FROM product_cctld WHERE product_id = '".$val['product_id']."' AND manufacturer_id = '".MANUFACTURER_ID."' ", true, "product_cctld_id" );
				$data = array(
						'product_id' => $val['product_id'],
						'manufacturer_id' => MANUFACTURER_ID,
						'product_metal_priority_id' => $val['product_metal_priority_id'],
						'product_cs_priority_id' => $val['product_cs_priority_id'],
						'product_ss1_priority_id' => $val['product_ss1_priority_id'],
						'product_ss2_priority_id' => $val['product_ss2_priority_id'],
						'product_discount' => $val['product_discount'],
						'product_status' => 0
					);
				if(!empty($product_cctld_id))
				{
					$this->db->where('product_cctld_id', $product_cctld_id);
					$this->db->update('product_cctld',$data);
				}
				else
				{
					$this->db->insert('product_cctld',$data);
				}
				$cnt++;
			}
			echo 'Operation successfull total '.$cnt.' updated.';
		}
		else
		{
			echo 'Result not found.';
		}
	}
	
	/************************************ Product functions end **********************************/
	
	
	
	/**
	 * 
	 */
	function upload_test()
	{
		if( !isset($_FILES["uploaded_file"]) ) 
		{
			echo "Invalid input."; 	
		}
		else 
		{
			if ($_FILES["uploaded_file"]["error"] > 0)
			{
				echo "Error: " . $_FILES["uploaded_file"]["error"] . "<br>";
			}
			else
			{
				echo "Upload: " . $_FILES["uploaded_file"]["name"] . "<br>";
				echo "Type: " . $_FILES["uploaded_file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["uploaded_file"]["size"] / 1024) . " kB<br>";
				echo "Stored in: " . $_FILES["uploaded_file"]["tmp_name"];
			}
		}
	}
	
	/**
	 * changes the collation of entire DB
	 */
	function changeDBCollation()
	{
		return;
		$res = executeQuery("show tables");
		
		foreach( $res as $k=>$ar )
		{
			echo $ar["Tables_in_Cloudwebs_ecommerce"]."<br>";  
			$this->db->query("ALTER TABLE ".$ar["Tables_in_Cloudwebs_ecommerce"]." CONVERT TO CHARACTER SET utf8 ");
		}
		echo "The collation of your database has been successfully changed!";
	}
	
	/**
	 * for admin use only
	 */
	function addProductAttributes()
	{
		die; 
		$this->load->model('admin/mdl_product_attribute','cat');
		$this->cat->cTableName = "product_attribute";
		$this->cat->cAutoId = "product_attribute_id";
		
		for($i=1; $i<=100; $i++)
		{
			$_POST["item_id"] = ""; 
			$_POST["inventory_master_specifier_id"] = 18;
			$_POST["pa_value"] = $i." per 100gms";
			$_POST["pa_sort_order"] = $i * 5;
			$_POST["pa_status"] = 0;
			$this->cat->saveData(); 				
		}
		
	}
	
	
	/**
	 * generates sqlLite language data file query to be used in RESTApps
	 */
	function langQuery()
	{
// 		/**
// 		 * added on 15-04-2015
// 		 */
// 		$i$city_arrll = 1;
// 		if( $this->input->get("i$city_arrll") !== FALSE )
// 		{
// 			$i$city_arrll = (int)$this->input->get("i$city_arrll");
// 		}

		//
		$data["os"] = "android";
		if( $this->input->get("os") !== FALSE )
		{
			$data["os"] = $this->input->get("os");
		}
		
		
		//
		$data["act"] = $this->input->get("act"); 
		
		/**
		 * load helper and fetch all labels
		 */
		$this->load->helper( "custom_file" );
		$tempA = hefile_lang_fileAllLbels( "application/language/".$this->session->userdata("LANG")."/".$this->session->userdata("LANG").".php", $this->session->userdata("LANG") );
		
		//strip HTML tags
		foreach($tempA as $k=>$ar)
		{
			$tempA[$k] = str_replace( "&nbsp;", "", strip_tags( $ar ) );
		}
	
		$sqLiteQuery = "";
		$sqLiteQueryParams = "";
		
		if( $data["os"] == "android" )
		{
			if( $data["act"] === "install" )
			{
				// 			query("INSERT OR REPLACE INTO config (c_key, c_value) " +
				// 			"VALUES (?, ?);", new String[] { key, value });
			
				// 			$sqLiteQuery = "\" INSERT INTO 'config' \" + <br>";
			
				// 			$cnt = 0;
				// 			foreach($tempA as $k=>$ar)
				// 			{
				// 				if( $cnt === 0 )
				// 				{
				// 					$sqLiteQuery .= "\" SELECT '".$k."' AS 'c_key', '".$ar."' AS 'c_value' \" + <br>";
				// 				}
				// 				else
				// 				{
				// 					$sqLiteQuery .= "\" UNION SELECT '".$k."', '".$ar."' \" + <br>";
				// 				}
			
				// 				$cnt++;
				// 			}
			
				// 			$sqLiteQuery .= "\" ; \" ";
			
				$sqLiteQuery = "\" INSERT INTO 'config' \" + <br>";
				$sqLiteQueryParams = " new String[] { <br>";
			
				$cnt = 0;
				foreach($tempA as $k=>$ar)
				{
					if( $cnt === 0 )
					{
						$sqLiteQuery .= "\" SELECT ? AS 'c_key', ? AS 'c_value' \" + <br>";
					}
					else
					{
						$sqLiteQuery .= "\" UNION SELECT ?, ? \" + <br>";
					}
			
					$sqLiteQueryParams .= " \"".$k."\", \"".$ar."\", <br>";
			
					$cnt++;
				}
			
				$sqLiteQuery .= "\" ; \" ";
				$sqLiteQueryParams .= " } ";
			}
			else if( $data["act"] === "upgrade" )
			{
				// 			query("INSERT OR REPLACE INTO config (c_key, c_value) " +
				// 			"VALUES (?, ?);", new String[] { key, value });
			
				// 			foreach($tempA as $k=>$ar)
				// 			{
				// 				$sqLiteQuery .= "\" INSERT OR REPLACE INTO config (c_key, c_value) VALUES ('".$k."', '".$ar."'); \" + <br>";
				// 			}
			
				$sqLiteQuery = " String[] sqlStrA = new String[]{ ";
				$sqLiteQueryParams = " String[] paramsA = new String[] { <br>";
			
				foreach($tempA as $k=>$ar)
				{
					$sqLiteQuery .= "\" INSERT OR REPLACE INTO config (c_key, c_value) VALUES ( ?, ?); \", <br>";
					$sqLiteQueryParams .= " \"".$k."\", \"".$ar."\", <br>";
				}
			
				$sqLiteQuery .= " } ";
				$sqLiteQueryParams .= " } ";
			}
		}
		else if( $data["os"] == "ios" )
		{
			if( $data["act"] === "install" )
			{
				foreach($tempA as $k=>$ar)
				{
					echo " INSERT INTO config(c_key, c_value) VALUES('". str_replace("'", "''", $k) ."', '". str_replace("'", "''", $ar) ."'); <br>";
				}
				
			}
			else if( $data["act"] === "upgrade" )
			{
				// 			query("INSERT OR REPLACE INTO config (c_key, c_value) " +
				// 			"VALUES (?, ?);", new String[] { key, value });
			
				// 			foreach($tempA as $k=>$ar)
				// 			{
				// 				$sqLiteQuery .= "\" INSERT OR REPLACE INTO config (c_key, c_value) VALUES ('".$k."', '".$ar."'); \" + <br>";
				// 			}
			
				$sqLiteQuery = " String[] sqlStrA = new String[]{ ";
				$sqLiteQueryParams = " String[] paramsA = new String[] { <br>";
			
				foreach($tempA as $k=>$ar)
				{
					$sqLiteQuery .= "\" INSERT OR REPLACE INTO config (c_key, c_value) VALUES ( ?, ?); \", <br>";
					$sqLiteQueryParams .= " \"".$k."\", \"".$ar."\", <br>";
				}
			
				$sqLiteQuery .= " } ";
				$sqLiteQueryParams .= " } ";
			}
		}
	
		echo $sqLiteQuery."<br><br>";
		echo $sqLiteQueryParams;
	}
	
	function bugzillaLogin()
	{
		return true;
		$this->load->library('xmlrpc');
		$this->xmlrpc->server('http://bug.Cloudwebstechnology.com/xmlrpc.cgi', 80);
		$this->xmlrpc->method('User.login');
		
		$request = array('Bugzilla_login'=>'gautam.Cloudwebs@gmail.com', 'Bugzilla_password'=>'16other wise268', 
						'product'=>'ecommerce', 'component'=>'Cloudwebs', 'summary'=>'Test Bug sec', 'version'=>'2.1.0', 
						'op_sys'=>'Windows', 'bug_severity'=>'Bug-fix_Enhancement', 'rep_platform'=>'PC',
						'assigned_to'=>'hi0001234d@gmail.com', 'comment'=>'Test First rpc call', 'keywords'=>'HE_P1_',
						'priority'=>'High' );
		
		$this->xmlrpc->method('Bug.create');
		$this->xmlrpc->request(array(array($request, 'struct')),'struct');
		
		if(!$this->xmlrpc->send_request()) 
		{
			echo $this->xmlrpc->display_error();
			
		}
		else
		{
			echo $this->xmlrpc->send_request();
		}
		// this returns ticket ID
// 		print_r($this->xmlrpc->display_response());
		
	}
	function changeName()
	{
		return true;
		$old = "assets/product/JT2";
		$new = "assets/product/JT1";
		
		rename($new, $old);
	}
	
	function changeLabel()
	{
		//ar,zh,da,nl,en,fi,fr,de,he,id,it,ja,ko,lt,ne,pl,pt,ru,es,sv,tr,uk,
		$languages = array("en", "de", "fr", "fi", "es", "ar",
						   "sv", "da", "lt", "id", "nl", "he",
						   "pl", "nb", "it", "pt", "iw", 
						   "tr", "zh", "ja", "ko", "ru", "uk");//"in", 
		
		//he == iw, id == in,
		
		$get = $this->input->get();
		
		//
		if( empty( $get['os'] ) || $get['os'] == "android" )
		{
			$get['os'] = "android";
		}
		else 
		{
			$get['os'] = "ios";
		}
		
			foreach ( $languages as $k=>$file )
			{
				if( $file == "in" || $file == "iw")
				{
					if( $get['os'] == "android" )
					{
						if( $file == "in" )
						{
							$old = "d:/StartUpJob_Data/Android/values-id/strings.xml";
							$new = "d:/StartUpJob_Data/Android/values-in";
							// 						rename($new, $old);
							if( !file_exists($new) )
							{
								mkdir($new, 0777, true);
								copy($old, $new."/strings.xml");
								echo $k." Success folder copied by values-in<br>";
							}
						}
						//
						if( $file == "iw" )
						{
							$old = "d:/StartUpJob_Data/Android/values-he/strings.xml";
							$new = "d:/StartUpJob_Data/Android/values-iw";
							// 						rename($new, $old);
							if( !file_exists($new) )
							{
								mkdir($new, 0777, true);
								copy($old, $new."/strings.xml");
								echo $k." Success folder copied by values-iw<br>";
							}
						}
					}
					else
					{
						if( $file == "in" )
						{
							$old = "d:/StartUpJob_Data/IOS/values-id/localizable.strings";
							$new = "d:/StartUpJob_Data/IOS/values-in";
							// 						rename($new, $old);
							if( !file_exists($new) )
							{
								mkdir($new, 0777, true);
								copy($old, $new."/strings.xml");
								echo $k." Success folder copied by values-in<br>";
							}
						}
						//
						if( $file == "iw" )
						{
							$old = "d:/StartUpJob_Data/IOS/values-he/Localizable.strings";
							$new = "d:/StartUpJob_Data/IOS/values-iw";
							// 						rename($new, $old);
							if( !file_exists($new) )
							{
								mkdir($new, 0777, true);
								copy($old, $new."/strings.xml");
								echo $k." Success folder copied by values-iw<br>";
							}
						}
					}
				}
				else
				{
					//decode url data
					$url = "http://www.germanystartupjobs.com/app-api/languages/".$file.".php";
					//  Initiate curl
					$ch = curl_init();
					// Disable SSL verification
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					// Will return the response, if false it print the response
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					// Set the url
					curl_setopt($ch, CURLOPT_URL,$url);
					// Execute
					$result=curl_exec($ch);
					// Closing
					curl_close($ch);
				
					$tmparr = (array)json_decode(trim($result));
				
					$app_name = "app_name";
					$navigation_drawer_open = "navigation_drawer_open";
					$navigation_drawer_close = "navigation_drawer_close";
					$action_settings = "action_settings";
				
					if( !empty($tmparr) )
					{
						if( $get['os'] == "android" )
						{
							if( $file == "en" )
							{
								$file = "values";
							}
							else
							{
								$file = "values-".$file;
							}
				
							//
							$path = "d:/StartUpJob_Data/Android/".$file;
							if( !file_exists($path) )
							{
								mkdir($path, 0777, true);
							}
				
							$content = "<resources>\n\n";
							$content.= "<string name=\"".$app_name."\"> Startup Jobs </string>\n\n";
							$content.= "<string name=\"".$navigation_drawer_open."\"> Open navigation drawer </string>\n\n";
							$content.= "<string name=\"".$navigation_drawer_close."\"> Close navigation drawer </string>\n\n";
							$content.= "<string name=\"".$action_settings."\"> About </string>\n\n";
							
							foreach( $tmparr['localized_strings'] as $key=>$val )
							{
								$val = str_replace("'", "\'", $val);
								// 						echo "\"".$key."\" = \"".$val."\";<br><br>";
								$content.= "<string name=\"".$key."\"> ".$val." </string>\n\n";
							}
							$content.= "</resources>";
				
							$fp = fopen($path."/strings.xml","wb");
							fwrite($fp,$content);
				
							$k++;
							echo $k."  Success File: ".$file."<br>";
				
							fclose($fp);
				
						}
						else
						{
// 				 			if( $file == "en" )
// 							{
// 								$file = "values";
// 							}
// 							else
// 							{
								$file = $file.".lproj";
// 							}

							$path = "d:/StartUpJob_Data/IOS/".$file;
							if( !file_exists($path) )
							{
								mkdir($path, 0777, true);
							}
				
// 							foreach( $tmparr['localized_strings'] as $key=>$val )
// 							{
// 								$val = str_replace("'", "\'", $val);
// 								$content .= "\"".$key."\" = \" ".$val."\"<br><br>";
// 							}

// 							$content = "<resources>\n\n";
							$content= "\"".$app_name."\" = \"Startup Jobs\";\n\n";
							$content.= "\"".$navigation_drawer_open."\" = \"Open navigation drawer\";\n\n";
							$content.= "\"".$navigation_drawer_close."\" = \"Close navigation drawer\";\n\n";
							$content.= "\"".$action_settings."\" = \"About\";\n\n";
							
							foreach( $tmparr['localized_strings'] as $key=>$val )
							{
								$val = str_replace("'", "\'", $val);
								$content .= "\"".$key."\" = \"".$val."\";\n\n";
							}
// 							$content.= "</resources>";
				
							$fp = fopen($path."/Localizable.strings","wb");
							fwrite($fp,$content);

							$k++;
							echo $k."  Success File: ".$file."<br>";

							fclose($fp);
						}
					}
					else
					{
						echo "Empty Responce or Data not Found<br>";
					}
				}
			}	
		// Will dump a beauty json :3
// 		var_dump(json_decode($result, true));
	}
	
	
	/**
	 * 
	 */
	function stringTOlower()
	{
		$str = $this->input->get('winery');
		echo strtolower( $str )."<br>";
		echo str_replace( " ", "-", strtolower($str) );
	} 
	
	
	function testRobots()
	{
// 		$data = "123_String";
		
// 		$whatIWant = substr($data, strpos($data, "_") + 1);
		
// 		echo $whatIWant;
// 		die;
		
		$url = "http://www.winezon.it/";
		$useragent = false;
		$isAllowed = true;
		
		$url_path = explode('/',$url);
		
		if( sizeof($url_path) <= 3 )
		{
			$url = $url."/";
		}
		
		if( substr( $url, 0, 4 ) != "http" )
		{
			$url = "http://".$url;
		}
		
		$parsed = parse_url($url);
		pr($parsed);
		$agents = array(preg_quote('*'));
		if($useragent) $agents[] = preg_quote($useragent, '/');
		$agents = implode('|', $agents);
		
		// location of robots.txt file, only pay attention to it if the server says it exists
		if(function_exists('curl_init')) 
		{
			$handle = curl_init("http://{$parsed['host']}/robots.txt");
			curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
			$response = curl_exec($handle);
			$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
			
			if($httpCode == 200) 
			{
				$robotstxt = explode("\n", $response);
			} 
			else 
			{
				$robotstxt = false;
			}
			curl_close($handle);
		}
		else 
		{
			$robotstxt = @file("http://{$parsed['host']}/robots.txt");
		}
		
		// if there isn't a robots, then we're allowed in
		if(!empty($robotstxt)) 
		{
			$rules = array();
			$ruleApplies = false;
			
			foreach($robotstxt as $line) 
			{
				// skip blank lines
				if(!$line = trim($line)) 
					continue;
			
				// following rules only apply if User-agent matches $useragent or '*'
				if(preg_match('/^\s*User-agent: (.*)/i', $line, $match)) 
				{
					$ruleApplies = preg_match("/($agents)/i", $match[1]);
					continue;
				}
				if($ruleApplies) 
				{
					list($type, $rule) = explode(':', $line, 2);
					$type = trim(strtolower($type));
					// add rules that apply to array for testing
					$rules[] = array(
							'type' => $type,
							'match' => preg_quote(trim($rule), '/'),
					);
				}
			}
			
			$currentStrength = 0;
			foreach($rules as $rule) 
			{
				// check if page hits on a rule
				if(preg_match("/^{$rule['match']}/", $parsed['path'])) 
				{
					// prefer longer (more specific) rules and Allow trumps Disallow if rules same length
					$strength = strlen($rule['match']);
					if($currentStrength < $strength) 
					{
						$currentStrength = $strength;
						$isAllowed = ($rule['type'] == 'allow') ? true : false;
					} 
					elseif($currentStrength == $strength && $rule['type'] == 'allow') 
					{
						$currentStrength = $strength;
						$isAllowed = true;
					}
				}
			}
		}
		
		return $isAllowed;
	}	
	
	function testJsonDropBox()
	{
		require_once APPPATH.'libraries/simple_html_dom.php';
		
// 		$urls = array( 1 => "https://www.dropbox.com/s/wsxckfv17xm6o75/GVD-NEWBANDHANI-103-1.jpg?dl=0",
// 					  2 => "https://www.dropbox.com/s/4gg17vmclqhn1bi/GVD-NEWBANDHANI-103-2.jpg?dl=0",
// 					  3 => "https://www.dropbox.com/s/hgf8igllngt87mr/GVD-NEWBANDHANI-103-3.jpg?dl=0",
// 					  4 => "https://www.dropbox.com/s/1v499zwylxergsr/GVD-NEWBANDHANI-103-4.jpg?dl=0");
		
		$ar = "https://www.dropbox.com/s/wsxckfv17xm6o75/GVD-NEWBANDHANI-103-1.jpg?dl=0 | https://www.dropbox.com/s/4gg17vmclqhn1bi/GVD-NEWBANDHANI-103-2.jpg?dl=0 | https://www.dropbox.com/s/hgf8igllngt87mr/GVD-NEWBANDHANI-103-3.jpg?dl=0 | https://www.dropbox.com/s/1v499zwylxergsr/GVD-NEWBANDHANI-103-4.jpg?dl=0";
		
// 		$cookie_string = "Cookie: locale=en; gvc=Mzk2ODczNDk2ODY5Mjc0ODYyODcwMDgyNTE0OTA1NjA2MDg5MDY%3D; seen-sl-signup-modal=VHJ1ZQ%3D
// 								%3D; t=1sIdaWjWnRYfzKkS8BOpmGlg; __Host-js_csrf=1sIdaWjWnRYfzKkS8BOpmGlg; seen-sl-download-modal=VHJ1ZQ
// 								%3D%3D; _ga=GA1.2.157061386.1470372560; _dc_gtm_UA-279179-2=1";
			
		$multipleImages = array();
		$multipleImages = explode("|", $ar);
			
		if( !isEmptyArr( $multipleImages ) )
		{
			foreach ( $multipleImages as $k=>$expImage )
			{
				if( !empty( $expImage ) )
				{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, trim( $expImage ) );
					curl_setopt($ch, CURLOPT_TIMEOUT, 30);
					curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 20 );
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)");
					curl_setopt($ch, CURLOPT_REFERER, "http://google.com");
					curl_setopt( $ch, CURLOPT_CAINFO, APPPATH . 'libraries/facebook/fb_ca_chain_bundle.crt');
					// 		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_string);
					// 		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_string);
					curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_HEADER, 1);
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_VERBOSE, 1);
					
					$result = curl_exec($ch);
					
					if ($result === false)
					{
						$e = new Exception(curl_error($ch), curl_errno($ch));
						curl_close($ch);
						throw $e;
					}
					
					curl_close($ch);
					
					$htmlDom = str_get_html( $result );
					
					$img = $htmlDom->find('img[class=absolute-center]', 0);
					
					// 		echo $img;die;
					$drop_images = fetchSubStr( $img, 'src="', '"');
					
					// 		$htmlDom = json_decode( $drop_images );
					
					// 		$content = str_replace("size=32x32", "size=1024x1024", $htmlDom->files[0]->preview_url);
					
					// 		pr($content);die;
					// 		$content = file_get_contents(str_replace("size=32x32", "size=1024x1024", $htmlDom->files[0]->preview_url));
					
					if( !empty( $drop_images ) )
					{
						$content = file_get_contents($drop_images);
						
						$time = time();
						file_put_contents(BASE_DIR . '/assets/tmp/loop/'.$k."_".$time.'.jpg', $content);
							
						echo BASE_DIR . '/assets/tmp/loop/'.$k."_".$time.'.jpg<br>';
					}
					else 
					{
						echo "<br>Filename may be empty";
					}
				}
			}
		}
	}
	
	function testAMZN()
	{
		$this->load->helper('custom_file');
		$data = array();
		
		$data[0]['product_name0'] = "name0";
		$data[0]['product_name1'] = "name1";
		$data[0]['product_name2'] = "name2";
		$data[0]['product_name3'] = "name3";
		$data[0]['product_name4'] = "name4";
		$data[0]['product_name5'] = "name5";
		$data[0]['product_name6'] = "name6";
		$data[0]['product_name7'] = "name7";
		$data[0]['product_name8'] = "name8";
		$data[0]['product_name9'] = "name9";
		
		
		// create file name with proper folder structure
		$product_category_file = "assets/AMZN_Products/Gautam_test.txt";
		
		// write content save content variable
		$content = "";
		
		if( !hefile_isDirExists( $product_category_file ) )
		{
			mkdir($product_category_file, 0777, true);
		}
		
		// check empty file size or content
		if ( trim( hefile_fileRead( $product_category_file ) ) == false )
		{
			//get header product data row by amazon 
			foreach( $data[0] as $key=>$val )
			{
				$content.= $key."|";
			}
			$content.= "\n";
		}
		
		//get all content 
		foreach( $data[0] as $key=>$val )
		{
			$content.= $val."|";
		}
		$content.= "\n";
		
		//open and write all content with proper data
		$fp = fopen( BASE_DIR.$product_category_file, "a" );
		fwrite($fp,$content);
		
		//close file
		fclose($fp);
		echo "success";
	}
	
	/**
	 * test on ip configuration
	 */
	function testIPConfiguration()
	{
// 		$ip=208;
// 		for( $i=101; $i<=180; $i++ )
// 		{
// 			if( $i>=141 )
// 				$ip = 203;
			
// 			echo $i.' => "108.59.14.'.$ip.':13080:STORMPROXIES",<br>';
// // 			$j++;
// 		}

		for ($i=301; $i<=430; $i++)
		{
			$no = rand(1, 24);
			echo $no.' curl "http://access.kaleholdings.com/cron/cpiMain?t_number=1041&t_process_number='.$i.'"<br>';
		}
	}

	function productfolderStructure()
	{
	    $folder = array( BASE_DIR . '/assets/product/a/__L', BASE_DIR . '/assets/product/a/__M',BASE_DIR . '/assets/product/a/__T' );
	    
	    foreach ( $folder as $f )
	    {
	        if( !file_exists($f) )
	        {
	            mkdir($f, 0777, true);
	        }
	    }
	}
}