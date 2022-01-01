<?php

//*************************************************** eBay configuration ********************************//


/**
 * @abstract eBay API version
 */
function geteBayConfigurations() 
{
	$res = array(); 
	// these keys can be obtained by registering at http://developer.ebay.com
	$production         = true;   // toggle to true if going against production
	
	//eBay API version
	$res['CompatabilityLevel'] = 551;
	
	//SiteID must also be set in the Request's XML
	//SiteID = 0  (US) - UK = 3, Canada = 2, Australia = 15, ....
	//SiteID Indicates the eBay site to associate the call with
	
	$ebaySiteArr = getEbayCountryCode();
	
	$res['siteID'] = $ebaySiteArr['country_id'];
	$username = "";
	if( MANUFACTURER_ID == 2 )
	{
		$username = "meerajewels";
		
		//production
		$p_devID = '507a508d-ee14-4408-9dca-7434dcb9c026';
		$p_appID = 'MeeraJew-2f20-4c5a-8d76-390272aea1aa';
		$p_certID = '258e6fd8-4dfa-424f-8945-c13540b94589';
		$p_userToken = 'AgAAAA**AQAAAA**aAAAAA**QZoOVA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AGl4aoCZKHowydj6x9nY+seQ**z3gCAA**AAMAAA**buCupmrtkbOQH3R2BNEr3NJy0LvnMz/p1roG5MnKeneJdR7E1v/A0z7+6PZAjsDtfvUIHt6EJJxrTMiV/NLqsuOfuy7TmmC1ZEaifmfasm793YqOb3DPBryWuF2V8AITDMnunxOstV5ygdjqbZi5fCP3c3YY+xmQxU8+lfwjpe3RM3wN4IvvpZGIpWvQvib538sfqZnnyn9oxbx1OVcTMiutydcSIypMKtvXCs1bgUp6Intwr6SjaV3Wd9QcNFpp4iNMxCl9xrYFXAaZsH73s+mZ5vJtDSqjP3hQFiZQXI+xBWwZQNhnDsFTSUdi4uBN+Nv8qCYdTOdrGjBMSEdkD4x7HIs7UemG2Amjw4rv8lZ8ND/Izv83snawzflfOv6YojbQd1ZHP+CQTmfjGxVFVLJuZav3+kP6sqb0kV4t/QSxgsowHprIURAYsmlXLdzL6NzwEnO0uTFaputTkhGify8Idw20Noen0PoX4OdbKR+yViEXoe5AeiF1fw1fO5Vv0Gdzfe48isA6QvaM+CNc3bmlvE8h2NBNplUPNI/sTgOHPHzBiJJaEA2isHt88oejfDiKAqcKI36x8CBo9RKe2keACx/HbqRWaOrBb4h3vaoCjQ+qpvZ/QgkJjY4s9ZpXJN6fGg8Fl+Otw8HovcOFIfTDpfAmUx64zcklIKX5zeyknctWDNsOItOM2LfQ6r0Nsc9QfTDc/hEQX8IOfCAcdyTLFx/AX6faODdGyzkK9aL551aRhNJI/YTM/sokzLhz'; 
		
		//sandbox
		$s_devID = '507a508d-ee14-4408-9dca-7434dcb9c026';
		$s_appID = 'MeeraJew-2ca8-4313-8dd3-d16ce2f5e9d8';
		$s_certID = '1c362557-f044-4661-bcad-446f8e33c2c9';
		$s_userToken = 'AgAAAA**AQAAAA**aAAAAA**txcMVA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhDJeCqAmdj6x9nY+seQ**fwcDAA**AAMAAA**IeQ///Upbj9Jt7PwCqDe1ASoxNGkEg1+Hyav7cWuuE7tlFYtZOCSRXZ5sDpPFxm2PW6PrblLlg8KFxrxMFrcGoM4qpx0OrZV27z2rMPE4zjEvqJc+fe/b5kj9sku3/pR1cYPIv5oxDysSmQaUOwbK23dZki/38IFbte6TTEFlcyWp58E6+rQXOpn3kNIXQmawJBcngb2bMIhXh8dvzVSvgg7w2ZppL+ch8UfMaORlfbAKWDp1ts9X89+aGzV/3FXwfTIC2HZFJsd4iC5Zxi/AuUM5+5AXZcJaajqWW8uGxlrSoczT72fp8hpp5DSwdyfphfCx1ynSxX+7/rsyHf7fqXbOmtG5TxkkxGJJjrf39KFnEAxN8sUJhtpLNWp8ZpiQH5qeeQKJf8R5j3NpwnuMACR82jU1Vr+5PLumOF3XJ2zPF0ZZF75E4lGqIOFikE7YFFUCkOX97FE/GfsDkbdo2H46UTLBXlio/7D2r+sK+fHfvqtZsMCpXsXeJRbYa5ZQoUoVkJ3q3JVzGigh3vL1e+7/qiQUHHkeJjoDL8dplzmZNCJBA/xynwTWCfJl5r+gPKwekPvv9VtHPvWWYje7xVmGoD+8wEsSCMDFNwLr+lgceqtyuR3AQSQprwiGcTar8701kX6YGYgel0sIwlb45ZZyQmhXpTLi6iIqLujikpqH/TrSveANB1cDU016scN0N+PQcKN3YrSAEGXdXpY3UuKKeckRXeSGxzr2moOuMPZFTZJPlCsIZbHmtaz5Qow';
	}
	else if(MANUFACTURER_ID == 3)
	{
		$username = "florencejewels";
		
		//production
		$p_devID = '6e4e677c-a8a2-45a4-bca4-a47e49619025';
		$p_appID = 'florence-5c4c-4aad-b3a2-83ad9fd520fa';
		$p_certID = '3dd942cf-0837-41d6-8d01-8a5f12574d06';
		$p_userToken = 'AgAAAA**AQAAAA**aAAAAA**z4F0VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6AHkIKoAZOHqAidj6x9nY+seQ**zZYCAA**AAMAAA**g/YgZf9vI07XDbcE8SIRz3xF1YAc6ugVr7W70px6ffVXl9dAeO7SdAN1DYVNYm3udlZVQjHCRlE91uDh+/wC/3n9pBONIXF/S+thnX2ZNSZIOb/bz5qsV/kM79rxP9/A95DVCmYhMS0bDO+9MqC2oNoAanM5rJkqZS+l3ZzuZ7gn5xZQsavWgQ/Nkd0joWQH/15sw+oRY2PBNhir7QTJ5G0FWiFUqsoLgjtJ1Kh/9XQYX81P/uzSsG650MvkmAmpqzYhp2GTQwDgA6rPy2py4f0Wnn84/W2jncme+dSB5Pygy++8MhwVnSHuVfqIetqeRbVMTcrh7qGoEA2k/GUlKAFugFKXYFtZtvm4yQTlg7X+sA7ep20SAEBdikc0lQCbp9KIGig5SBhoUgKPh+oNhVD5/iRo66QfyjS6mhJ/VTQ6Fj4cnMY0999lbiWlS04oeSxWR6vYX78IZli6OkqewC5827pFddQ1W2L+H5ZOhc+41UgPGrOjA6pMOmIwiYN7eW+Ojh5O9+tkHEPlaDMllOAykScRcIfHLJ3SBE3cppEVDj0QI2H7nu1x9/C6hibug5e4tHBO/YxgSaJhFOpyyBeztPOg3odwpPXGQya5/go+JX+lb7CxpdE8ALZ6O35trk8DL8zKHr3lTCFbubmxV313GFsASNYMNasblOngp03wfJSAK+8aEQff9UXfBtWE31VA8zQkBng1CUs+tc1w7FUN2/zRRYCaZMvAIkyrYcchBwUY8VXFoIXvd3WNxbvK'; 
		
		//sandbox
		$s_devID = '6e4e677c-a8a2-45a4-bca4-a47e49619025';
		$s_appID = 'florence-aa34-434a-a980-1693763b5ffe';
		$s_certID = '42ad639e-839e-4e55-92a2-288fff98d232';
		$s_userToken = 'AgAAAA**AQAAAA**aAAAAA**N4F0VA**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6wFk4GhDZCDpw2dj6x9nY+seQ**vyIDAA**AAMAAA**2+VYL2FQH/vcVXpokgei2N5K6r63UcvJfj6p37HoMbLn5l0sP5ZgouTDc4BrGWmWcX4tJJInWrgqdxYquHpQZX+XOT3EvCoyA3lS77RaTbgFe7SiJ4HJQ3TliguBADYtcgGouRg7Y/AslWjw6qhXEejJrq55caWBEaZI4t7vg9o+SGKeW1poHttscmMC5UUumTb56qWIoBhKShZRdBVXrmq+EkZVm8RBzL1yRIBPpcF0a/Ei2gEQbsestdB8ck8eW7aNwVfxZK4lJO3lxiPWBVw0cWOF+KCzJOPmavJnUqXyrpjfTArDUCgC6HUSVQN33vi4oCvoVzWDumQohz8cP9ueLUZxzTK7hweMmcMTTFJ+kerMkOuwTaSNC+zdwOE+35PqbNrl8+aPQrfqF3pv/MX29GetuysjDmmWa+8PB6fLHddR3Cca31KDCzjSUb7lX2z9XRiJJVXAw8pIMNko3bBMi1U9Bgr554TLKH7fTA2SCbYICqripjzjdXNR7Khz27DRqSA9OLuhVXQkA6Hk7ENOCGnqOVuYn6ksqX13T3nqNUGJAAonK3tNsLJAy/2dE1APFNu2Zd/8Yk/Vc5i5siv2Go0k+awO0WEtNiBtO4BOOsBZKJrf9Tffqgu/T4553Zzj1QQBO/PDBh/tYatJ24nmVpM9OxZjp3W+VczNPHHo8igk6KozFuMd7scgYVHlnkFe9DPVwWHKSAPXi+wlseJLiwMP8PdW6xuPfxng9FLMlGFM4CnfWr4bbVDPuxWz';
	}
	
	
	$res['USER_NAME'] = $username;
	
	if ($production)
	{
		// production environment
		$res['devID'] = $p_devID;   // these prod keys are different from sandbox keys
		$res['appID'] = $p_appID;
		$res['certID'] = $p_certID;
		//the token representing the eBay user to assign the call with
		$res['userToken'] = $p_userToken;
		
		//set the Server to use (Sandbox or Production)
		$res['serverUrl'] = 'https://api.ebay.com/ws/api.dll';      // server URL different for prod and sandbox
		
	}
	else
	{
		// sandbox (test) environment
		$res['devID'] = $s_devID;         // insert your devID for sandbox
		$res['appID'] = $s_appID;   // different from prod keys
		$res['certID'] = $s_certID;  // need three 'keys' and one token
		
		// the token representing the eBay user to assign the call with
		// this token is a long string - don't insert new lines - different from prod token
		$res['userToken'] = $s_userToken;
		
		//set the Server to use (Sandbox or Production)
		$res['serverUrl'] = 'https://api.sandbox.ebay.com/ws/api.dll';
		            
	}
		
	return $res; 
}

//*************************************************** eBay configuration end ********************************// 





//*************************************************** store/listing configuration ********************************// 

/**
 * @abstract returns store config
 */
function getStoreConfig() 
{
	// url
	$res['store_url'] = "http://stores.ebay.com/perrianjewels";
	return $res;
}

/**
 * @abstract returns store config
 */
function listingDurationDays() 
{
	return 3;
}

/**
 * @abstract returns listing config
 */
function getListingConfig() 
{
	//brand
	$brand_name="";
	if( MANUFACTURER_ID == 2 )
		$brand_name = "Meera Jewels";
	else if(MANUFACTURER_ID == 3)
		$brand_name = "Florence Jewels";
		
	$listing["brand_name"] = $brand_name;
	
	
	$listing["is_store_listing"] = FALSE;
	$listing["is_auction"] = FALSE;
	
	//listing SEO config 
	$listing['EBAY_TITLE_LENGTH'] = 80; 
	$listing['title_prefix'] = ''; 
	$listing['title_suffix'] = ''; 
	
	//other listing config 
	$listing['duration'] = 'Days_'.listingDurationDays(); 
	$listing['type'] = 'FixedPriceItem'; 
	$listing['quantity'] = 1; 
	
	//location
	$listing['location'] = 'SURAT, GUJARAT'; 
	
	//DispatchTimeMax
	$listing['DispatchTimeMax'] = 10;
	
	//payment config 
	$paypal_id="";
	if( MANUFACTURER_ID == 2 )
		$paypal_id = "meerajewels1988@gmail.com";
	else if(MANUFACTURER_ID == 3)
		$paypal_id = "florencejewels88@gmail.com";
		
	$listing['payment_method'] = 'PayPal'; 
	$listing['paypal_id'] = $paypal_id; 
	
	//refund policy
	$listing['option'] = 'MoneyBack'; 
	$listing['within'] = 'Days_14'; 
	$listing['returns'] = 'ReturnsAccepted'; 
	$listing['description'] = 'If you are not satisfied, return the item for refund.'; 
	$listing['paidby'] = 'Buyer'; 
	
	//site where to list product
	$ebaySiteArr = getEbayCountryCode();
	
	//shipping config
	//$listing['ShippingService_LOCAL'] = 'StandardShippingFromOutsideUS'; 
	//$listing['ShippingService'] = 'StandardInternational'; 
	$listing['ShippingService_LOCAL'] = $ebaySiteArr['ShippingService_LOCAL']; 
	$listing['ShippingService'] = $ebaySiteArr['ShippingService']; 
	$listing['ShippingType'] = 'Flat'; 
	$listing['SellerExcludeShipToLocationsPreference'] = 'true';
	$listing['ShipToLocations'] = 'Worldwide';
	
	
	$listing['site'] = $ebaySiteArr['abbreviation']; 
	$listing['currency_code'] = $ebaySiteArr['currency_code'];
	$listing['abbreviation'] = $ebaySiteArr['abbreviation'];
	

	return $listing;	
}

//*************************************************** store/listing configuration end ****************************//

?>