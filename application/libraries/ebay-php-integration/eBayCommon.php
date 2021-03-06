<?php
//ebay EBAY_DATA_FOLDER
//define( 'EBAY_DATA_FOLDER', BASE_DIR.'application/libraries/ebay-php-integration/' ); 
define( 'EBAY_DATA_FOLDER', BASE_DIR.'application/libraries/ebay-php-integration/' ); 

class eBayCommon
{
	public $dev_debug; 
	public $staging_debug; 
	public $mode;
	public $add_mode;
	public $responseDoc;
	public $block_style; 
	public $ebay_request_type;  
	
	/**
	 * @abstract mode of request identified by numeric value  
	 * @param $this->mode: 1 ==> add, 2 ==> update, 3 ==> delete, 4 ==> upload picture, 5 ==> relist fixed price item. 
	 */
	
	/**
	 * @abstract constructor
	 */
	function __construct( $req="FixedPriceItem" )
	{
		$this->dev_debug = TRUE;
		$this->staging_debug = TRUE;
		$this->mode = 0;
		$this->add_mode = 0;
		$this->block_style = ' style="background-color: #f1f1f1; margin: 50px;" ';

		/**
		 * added Reqeust type support on 11-09-2014 however default to "FixedPriceItem"
		 * type supported are
		 * FixedPriceItem: Buy it Now
		 * Item: Auction
		 * 
		 */
		$this->ebay_request_type = $req; 
		
		//import all configs
		require_once EBAY_DATA_FOLDER.'config/ebay_config.php';

		//import session helper class
		require_once EBAY_DATA_FOLDER.'helpers/eBaySession.php';
	}
	
	/**
	 * @abstract do request call 
	 */
	private function _do_request( $requestXmlBody ) 
	{
		
		//print request in dev degub mode
		if( $this->dev_debug )
		{
			echo '<div '.$this->block_style.'><p>This is XML request:</p>';
			echo '<pre>';
			echo htmlspecialchars( $requestXmlBody );
			echo '</pre></div>';
		}
		
		//
		$eBayConfig = geteBayConfigurations(); 
		
        //Create a new eBay session with all details pulled in from included keys.php 
        $session = new eBaySession( $eBayConfig['userToken'], $eBayConfig['devID'], $eBayConfig['appID'], $eBayConfig['certID'], $eBayConfig['serverUrl'], $eBayConfig['CompatabilityLevel'], 					$eBayConfig['siteID'], $this->getVerb()); 
		
		//send the request and get response 
		$responseXml = $session->sendHttpRequest( $requestXmlBody ); 
		
		//print response in dev degub mode
		if( $this->dev_debug )
		{
			echo '<div '.$this->block_style.'><p>This is XML response:</p>';
			echo '<pre>';
			echo htmlspecialchars( $responseXml );
			echo '</pre></div>';
		}
		
		
		//check response
		if( stristr( $responseXml, 'HTTP 404' ) || $responseXml == '' ) 
		{
			$this->_logger( 'Error sending request.' ); 
			return false; 
		}
		else 
		{
			return $responseXml; 
		}

	}

	/**
	 * @abstract load dom document
	 */
	private function _load_dom_document( $responseXml ) 
	{
		//Xml string is parsed and creates a DOM Document object
		$this->responseDoc = new DomDocument();
		$this->responseDoc->loadXML( $responseXml );
	}

	/**
	 * @abstract parses reponse and checks for errors if any
	 */
	private function _parse_response() 
	{
		//get any error nodes
		$errors = $this->_get_value_by_tag( 'Errors', false );
		
		//if there are error nodes: however should not stop execution since errors might be of warning level
		if( $errors->length > 0 )
		{
			$this->_handleErrors( $errors ); 
		}

		if( $this->_get_value_by_tag( 'Ack' ) == "Failure" )
		{
			if( $this->staging_debug ) 
			{
				//system/application log: errorLog is helperr function for logging so you are required to create your own in your application
				errorLog( "EBAY_XML_API", "Log: Mode: " . $this->mode . " Execution halted due to API call failure to eBay servers." ); 
			}
			
			//abort the execution
			/*if( $this->dev_debug )
			{
				exit;
			}*/
			
			return  false;
		}
		
		return true;
	}
	
	/**
	 * @abstract returns value of specified tag
	 */
	private function _get_value_by_tag( $tag_name, $is_node_value=true ) 
	{
		$element = $this->responseDoc->getElementsByTagName( $tag_name ); 
		if( !empty( $element ) )
		{
			if( $is_node_value )
			{
				return $element->item(0)->nodeValue; 
			}
			else 
			{
				return $element;	
			}
		}
		else 
		{
			return false; 	
		}
	}
	
	/**
	 * @abstract logging 
	 */
	private function _logger( $msg )
	{
		if( $this->staging_debug )
		{
			echo '<div '.$this->block_style.'> Log: Mode: ' . $this->mode . ' \n\n Message: ' . $msg . ' </div>';
		}
		else 
		{
			//system/application log: errorLog is helperr function for logging so you are required to create your own in your application
			errorLog( "EBAY_XML_API", "Log: Mode: " . $this->mode . " \n\n Message: " . $msg ); 
		}
	}
	
	/**
	 * @abstract error handling
	 */
	private function _handleErrors($errors)
	{
		$errorOutput = "Call Failure/Warning: \n<P><B>eBay returned the following error/warning(s):</B>";

		foreach ($errors as $error)
		{
			$errorOutput .= "# Error Code: " . $error->getElementsByTagName('ErrorCode')->item(0)->nodeValue . 
							" . Short Message: " . htmlentities( $error->getElementsByTagName('ShortMessage')->item(0)->nodeValue ) . 
							". Long Message: " .htmlentities( $error->getElementsByTagName('LongMessage')->item(0)->nodeValue ) . "\n";
		}
		

		$this->_logger( nl2br($errorOutput) ); 
	}
	
/**
 * @abstract add item to eBay listing
 */
	function addItem( Array $listingData )
	{
		$this->mode = 1; 
		
		$requestXmlBody = $this->getXMLStartTag(); 
		$requestXmlBody .= $this->getebayXMLConfigurations(); 
		$requestXmlBody .= $this->formatItemAddUpdateXML( $listingData ); 
		$requestXmlBody .= $this->getXMLStartTag( false );
		
		$responseXml = $this->_do_request( $requestXmlBody );
		
		if( !$responseXml )
		{
			return false; 	
		}
		else 
		{
			$this->_load_dom_document( $responseXml ); 
			
			if( $this->_parse_response() )	
			{
				return $this->_get_value_by_tag( 'ItemID' ); 
			}
			else
			{
				return false;	
			}
		}
		
	}

/**
 * @abstract update item to eBay listing
 */
	function updateItem( Array $listingData )
	{
		$this->mode = 2; 
		
		$requestXmlBody = $this->getXMLStartTag(); 
		$requestXmlBody .= $this->getebayXMLConfigurations(); 
		$requestXmlBody .= $this->formatItemAddUpdateXML( $listingData ); 
		$requestXmlBody .= $this->getXMLStartTag( false );
		
		$responseXml = $this->_do_request( $requestXmlBody );
		
		if( !$responseXml )
		{
			return false; 	
		}
		else 
		{
			$this->_load_dom_document( $responseXml ); 
			
			if( $this->_parse_response() )	
			{
				return $this->_get_value_by_tag( 'ItemID' ); 
			}
			else
			{
				return false;	
			}
			
			//return $this->_parse_response();	
		}
	}

/**
 * @abstract relist item to eBay listing
 */
	function relistItem( Array $listingData )
	{
		$this->mode = 5; 
		
		$requestXmlBody = $this->getXMLStartTag(); 
		$requestXmlBody .= $this->getebayXMLConfigurations(); 
		$requestXmlBody .= $this->formatItemAddUpdateXML( $listingData ); 
		$requestXmlBody .= $this->getXMLStartTag( false );
		
		$responseXml = $this->_do_request( $requestXmlBody );
		
		if( !$responseXml )
		{
			return false; 	
		}
		else 
		{
			$this->_load_dom_document( $responseXml ); 
			
			if( $this->_parse_response() )	
			{
				return $this->_get_value_by_tag( 'ItemID' ); 
			}
			else
			{
				return false;	
			}
			
			//return $this->_parse_response();	
		}
	}

/**
 * @abstract delete item to eBay listing
 */
	function deleteItem( Array $listingData )
	{
		$this->mode = 3; 
		
		$requestXmlBody = $this->getXMLStartTag(); 
		$requestXmlBody .= $this->getebayXMLConfigurations(); 
		$requestXmlBody .= $this->deleteItemXML( $listingData['ebay_item_id'] ); 
		$requestXmlBody .= $this->getXMLStartTag( false );
		
		$responseXml = $this->_do_request( $requestXmlBody );
		
		if( !$responseXml )
		{
			return false; 	
		}
		else 
		{
			$this->_load_dom_document( $responseXml ); 
			
			return $this->_parse_response();	
		}
	}

/**
 * @abstract delete item to eBay listing
 */
	function uploadImage( $image_url, $picture_name='Perrian' )
	{
		$this->mode = 4; 
		
		$requestXmlBody = $this->getXMLStartTag( $this->mode ); 
		$requestXmlBody .= $this->getebayXMLConfigurations(); 
		$requestXmlBody .= $this->uploadImageXML( $image_url, $picture_name ) ; 
		$requestXmlBody .= $this->getXMLStartTag( false ); 

		$responseXml = $this->_do_request( $requestXmlBody );
		
		if( !$responseXml )
		{
			return false; 	
		}
		else 
		{
			$this->_load_dom_document( $responseXml ); 
			
			$this->_parse_response();	
			
			//res
			$res = array();
			$this->responseDoc = $this->_get_value_by_tag( 'SiteHostedPictureDetails', false )->item(0); 
			
			$res['FullURL'] =  $this->_get_value_by_tag( 'FullURL' );
			$res['UseByDate'] = $this->_get_value_by_tag( 'UseByDate' );
			
			return $res;
		}
	}
	
/**
 * @abstract return eBay verb appplicable for differnnt mode of call request
 */
	function getVerb()
	{
		if( $this->mode == 1 ) 
		{
			return 'Add'.$this->ebay_request_type.'';
		}
		else if( $this->mode == 2 ) 
		{
			return 'Revise'.$this->ebay_request_type.'';
		}
		else if( $this->mode == 3 ) 
		{
			return 'End'.$this->ebay_request_type.'';
		}
		else if( $this->mode == 4 ) 
		{
			return 'UploadSiteHostedPictures';
		}
		else if( $this->mode == 5 ) 
		{
			return 'Relist'.$this->ebay_request_type.'';
		}
	}
	
/**
 * @abstract return xml start or end tag applicable for call being made
 */
	function getXMLStartTag( $is_start=true )
	{
		if( $this->mode == 1 ) 
		{
			if( $is_start )
			{
				return '<?xml version="1.0" encoding="utf-8" ?>
						<Add'.$this->ebay_request_type.'Request xmlns="urn:ebay:apis:eBLBaseComponents">';
			}
			else 
			{
				return '</Add'.$this->ebay_request_type.'Request>'; 
			}
		}
		else if( $this->mode == 2 ) 
		{
			if( $is_start )
			{
				return '<?xml version="1.0" encoding="utf-8" ?>
						<Revise'.$this->ebay_request_type.'Request xmlns="urn:ebay:apis:eBLBaseComponents">';
			}
			else
			{
				return '</Revise'.$this->ebay_request_type.'Request>'; 
			}
		}
		else if( $this->mode == 3 ) 
		{
			if( $is_start )
			{
				return '<?xml version="1.0" encoding="utf-8" ?>
						<End'.$this->ebay_request_type.'Request xmlns="urn:ebay:apis:eBLBaseComponents">';
			}
			else
			{
				return '</End'.$this->ebay_request_type.'Request>'; 
			}
		}
		else if( $this->mode == 4 ) 
		{
			if( $is_start )
			{
				return '<?xml version="1.0" encoding="utf-8" ?>
						<UploadSiteHostedPicturesRequest xmlns="urn:ebay:apis:eBLBaseComponents">';
			}
			else 
			{
				return '</UploadSiteHostedPicturesRequest>'; 
			}
		}
		else if( $this->mode == 5 ) 
		{
			if( $is_start )
			{
				return '<?xml version="1.0" encoding="utf-8" ?>
						<Relist'.$this->ebay_request_type.'Request xmlns="urn:ebay:apis:eBLBaseComponents">';
			}
			else 
			{
				return '</Relist'.$this->ebay_request_type.'Request>'; 
			}
		}

	}
	
/**
 * @abstract return eBay RequesterCredentials  
 */
	function getebayXMLConfigurations() 
	{
		$res = geteBayConfigurations(); 
		return "<RequesterCredentials><eBayAuthToken>".$res['userToken']."</eBayAuthToken></RequesterCredentials>
				<DetailLevel>ReturnAll</DetailLevel>
				<ErrorLanguage>en_US</ErrorLanguage>
				<Version>".$res['CompatabilityLevel']."</Version>
				<WarningLevel>High</WarningLevel>";
	}
	
/**
 * @abstract return eBay RequesterCredentials  
 */
	function formatItemAddUpdateXML( $listingData ) 
	{
		$listingConfig = getListingConfig();
		
		$duration = (!empty($listingData['duration'])) ? $listingData['duration'] : $listingConfig['duration'];
		
		$requestXmlBody = '<Item>';
		$requestXmlBody .= '<Site>'.$listingConfig['site'].'</Site>';
		
		if( $this->mode == 2 || $this->mode == 5)	//if update call
		{
			$requestXmlBody .= "<ItemID>".$listingData['ebay_item_id']."</ItemID>"; 
		}
		
		//primary category on ebay invenory
		$requestXmlBody .= '<PrimaryCategory>';
		$requestXmlBody .= '<CategoryID>'.$listingData['PrimaryCategoryID'].'</CategoryID>';
		$requestXmlBody .= '</PrimaryCategory>';
		
		if( $listingConfig["is_store_listing"] )
		{
			//store category
			$requestXmlBody .= '<Storefront>
							  <StoreCategoryID>'.$listingData['StoreCategoryID'].'</StoreCategoryID>';
			if( !empty( $listingData['StoreCategory2ID'] ) )
			{
				$requestXmlBody .= '<StoreCategory2ID>'.$listingData['StoreCategory2ID'].'</StoreCategory2ID>';
			}
			$requestXmlBody .= '</Storefront>';
		}

		if( $this->ebay_request_type == "FixedPriceItem" )
		{
			$requestXmlBody .= '<StartPrice currencyID="'.$listingConfig['currency_code'].'">'.$listingData['price'].'</StartPrice>';	//only set start price if item is applicable to auction
			$requestXmlBody .= '<BuyItNowPrice>0</BuyItNowPrice>';

			//remove below tags if you don't wish to allow best offer feature
			$requestXmlBody .= '<BestOfferDetails>
								<BestOfferEnabled>false</BestOfferEnabled>
							</BestOfferDetails>';
		}
		else if( $this->ebay_request_type == "Item" )
		{
			if($this->add_mode == 9)
			{
				$requestXmlBody .= '<BuyItNowPrice currencyID="'.$listingConfig['currency_code'].'">'.$listingData['price'].'</BuyItNowPrice>';
			}
			
			$requestXmlBody .= '<StartPrice currencyID="'.$listingConfig['currency_code'].'">'.$listingData['StartPrice'].'</StartPrice>';	//only set start price if item is applicable to auction
// 			$requestXmlBody .= '<DiscountPriceInfo currencyID="USD">'.$listingData['DiscountPriceInfo'].'</DiscountPriceInfo>';
//			$requestXmlBody .= '<BuyItNowPrice currencyID="USD">'.$listingData['BuyItNowPrice'].'</BuyItNowPrice>'; 
			
			if( $listingConfig["is_auction"] )
			{
				$requestXmlBody .= '<LiveAuction>'.TRUE.'</LiveAuction>'; 
			}
		}
		
		$requestXmlBody .= '<Country>IN</Country>';
		$requestXmlBody .= '<Currency>'.$listingConfig['currency_code'].'</Currency>';
		$requestXmlBody .= '<DispatchTimeMax>'.$listingConfig['DispatchTimeMax'].'</DispatchTimeMax>'; 
		$requestXmlBody .= '<ListingDuration>'.$duration.'</ListingDuration>'; 
		
		if( $this->ebay_request_type == "FixedPriceItem" )
		{
			$requestXmlBody .= '<ListingType>FixedPriceItem</ListingType>'; 
		}
		else if( $this->ebay_request_type == "Item" )
		{
			$requestXmlBody .= '<ListingType>Chinese</ListingType>';
		}
		
		
		$requestXmlBody .= '<PaymentMethods>'.$listingConfig['payment_method'].'</PaymentMethods>'; 
		$requestXmlBody .= '<PayPalEmailAddress>'.$listingConfig['paypal_id'].'</PayPalEmailAddress>'; 
		$requestXmlBody .= '<AutoPay>True</AutoPay>';
		
		$requestXmlBody .= '<PictureDetails>'; 
		/*					  <GalleryType>Gallery</GalleryType> 
							  <GalleryURL>http://www.".baseDomain()."/assets/product/PER-136/DIAMOND/DIAMOND-A/WHITE/</GalleryURL> */
		$requestXmlBody .= '<PhotoDisplay>PicturePack</PhotoDisplay>'; 
		foreach( $listingData['product_images'] as $k=>$ar )
		{
			$requestXmlBody .= '<PictureURL>'.$ar.'</PictureURL>'; 
		}
		$requestXmlBody .= '</PictureDetails>';
							 
		$requestXmlBody .= '<Location><![CDATA['.$listingConfig['location'].']]></Location>';
		
		$requestXmlBody .= '<Quantity>'.$listingConfig['quantity'].'</Quantity>'; 
		
		$refundOption = ($listingConfig['site'] != 'UK' ) ? '<RefundOption>'.$listingConfig['option'].'</RefundOption>' : '';
				
		$requestXmlBody .= '<ReturnPolicy>
							  <ReturnsAcceptedOption>'.$listingConfig['returns'].'</ReturnsAcceptedOption>
							  '.$refundOption.'
							  <ReturnsWithinOption>'.$listingConfig['within'].'</ReturnsWithinOption>
							  <Description>'.$listingConfig['description'].'</Description>
							  <ShippingCostPaidByOption>'.$listingConfig['paidby'].'</ShippingCostPaidByOption>
							</ReturnPolicy>';

		$requestXmlBody .= '<ShippingDetails>
								<ShippingServiceOptions>
								  <ShippingService>'.$listingConfig['ShippingService_LOCAL'].'</ShippingService>
								  <ShippingServiceCost currencyID="'.$listingConfig['currency_code'].'">0.0</ShippingServiceCost>
								  <ShippingServicePriority>1</ShippingServicePriority>
								  <ExpeditedService>false</ExpeditedService>
								  <ShippingTimeMin>5</ShippingTimeMin>
								  <ShippingTimeMax>10</ShippingTimeMax>
								  <FreeShipping>true</FreeShipping>
								</ShippingServiceOptions>
								
								<InternationalShippingServiceOption>
								  <ShippingService>'.$listingConfig['ShippingService'].'</ShippingService>
								  <ShippingServiceCost currencyID="'.$listingConfig['currency_code'].'">0.0</ShippingServiceCost>
								  <ShippingServicePriority>1</ShippingServicePriority>
								  <ShipToLocation>Americas</ShipToLocation>
								  <ShipToLocation>CA</ShipToLocation>
								  <ShipToLocation>GB</ShipToLocation>
								  <ShipToLocation>AU</ShipToLocation>
								  <ShipToLocation>Europe</ShipToLocation>
								  <ShipToLocation>Asia</ShipToLocation>
								  <ShipToLocation>CN</ShipToLocation>
								  <ShipToLocation>MX</ShipToLocation>
								  <ShipToLocation>DE</ShipToLocation>
								  <ShipToLocation>JP</ShipToLocation>
								  <ShipToLocation>BR</ShipToLocation>
								  <ShipToLocation>FR</ShipToLocation>
								  <ShipToLocation>RU</ShipToLocation>
								</InternationalShippingServiceOption>
								
								<ShippingType>'.$listingConfig['ShippingType'].'</ShippingType>
								
								<SellerExcludeShipToLocationsPreference>'.$listingConfig['SellerExcludeShipToLocationsPreference'].'</SellerExcludeShipToLocationsPreference>
							</ShippingDetails>
						<ShipToLocations>'.$listingConfig['ShipToLocations'].'</ShipToLocations>';

		$requestXmlBody .= '<RegionID>0</RegionID>';
		$requestXmlBody .= '<ShippingTermsInDescription>True</ShippingTermsInDescription>';
		
		
		$requestXmlBody .= '<Title><![CDATA['.$listingData['title'].']]></Title>'; 
		$requestXmlBody .= '<Description><![CDATA['.$listingData['description'].']]></Description>'; 
		$requestXmlBody .= '<SKU>'.$listingData['product_sku'].'</SKU>'; 
		
		
		$naturalLab = (MANUFACTURER_ID == 7) ? 'Natural' : 'Lab Created';
		
		$requestXmlBody .= '<ItemSpecifics>
								<NameValueList>
								  <Name>SKU</Name>
								  <Value>'.$listingData['product_sku'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>								
								<NameValueList>
								  <Name>Brand</Name>
								  <Value>'.$listingConfig["brand_name"].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>								
								<NameValueList>
								  <Name>Natural/Lab-Created</Name>
								  <Value>'.$naturalLab.'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								'; 
								
		if( $listingData['product_type'] == 'RING' )						
		{
			$ringSize = "All Size (Message us your ring size)";
			if( $this->ebay_request_type == "Item" && in_array($listingData['product_sku'], array('KRE-120')) )
				$ringSize = '11';
				
			$requestXmlBody .= '<NameValueList>
								  <Name>Sizable</Name>
								  <Value>Yes</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Ring Size</Name>
								  <Value>'.$ringSize.'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>';	
		}
		
		
		$xmlOtherDesc = "";
		if(MANUFACTURER_ID == 7)
		{
			$xmlOtherDesc .=   '<NameValueList>
								  <Name>Main Stone Shape</Name>
								  <Value>'.$listingData['diamond_shape_name_cs'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Diamond Color</Name>
								  <Value>'.$listingData['diamond_color_name_cs'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Clarity</Name>
								  <Value>'.$listingData['diamond_purity_name_cs'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Cut</Name>
								  <Value>Excellent</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Metal Purity</Name>
								  <Value>'.$listingData['metal_purity_name'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Certificate</Name>
								  <Value>Lab Certification</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Total Diamond Weight</Name>
								  <Value>'.$listingData['total_diamond_weight'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Total Gemstone Weight</Name>
								  <Value>'.$listingData['total_gemstone_weight'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Total Metal Weight</Name>
								  <Value>'.$listingData['product_metal_weight'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>';
		}
		else if(MANUFACTURER_ID == 2 || MANUFACTURER_ID == 3)
		{
			$xmlOtherDesc .=   '<NameValueList>
								  <Name>Metal Purity</Name>
								  <Value>925 Silver</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Total Carat Weight</Name>
								  <Value>'.$listingData['stone_total_weight'].' carats (Approx)</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								<NameValueList>
								  <Name>Total Weight</Name>
								  <Value>'.$listingData['product_price_weight'].'</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>'; 
		}
		
		
		$requestXmlBody .= '
								<NameValueList>
								  <Name>Metal</Name>
								  <Value>'.$listingData['metal_color_name'].' '.$listingData['metal_type_name'].' Plated</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								
								'.$xmlOtherDesc.'
								';
								
								/*<NameValueList>
								  <Name>Style</Name>
								  <Value></Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								
								<NameValueList>
								  <Name>Certification/Grading</Name>
								  <Value>IGI</Value>
								  <Source>ItemSpecific</Source>
								</NameValueList>
								*/
								
		if(MANUFACTURER_ID == 2 || MANUFACTURER_ID == 3)
		{				
			if( strpos( $listingData["dp_desc_cs"], "Black Diamonds (Moissanite)" ) === FALSE ) 
			{
				$requestXmlBody .= '<NameValueList>
									  <Name>Main Stone Treatment</Name>
									  <Value>Enhanced</Value>
									  <Source>ItemSpecific</Source>
									</NameValueList>';
			}
			else 
			{
				$requestXmlBody .= '<NameValueList>
									  <Name>Main Stone Treatment</Name>
									  <Value>Not Enhanced</Value>
									  <Source>ItemSpecific</Source>
									</NameValueList>';
			}
		}
		
		$requestXmlBody .= "</ItemSpecifics>
							<ConditionID>1000</ConditionID>";
		$requestXmlBody .= '</Item>';
		
		return $requestXmlBody; 
	}

/**
 * @abstract return eBay upload image XML part  
 */
	function deleteItemXML( $ebay_item_id ) 
	{
		return '<EndingReason>NotAvailable</EndingReason>
				<ItemID>'.$ebay_item_id.'</ItemID>';
	}
	

/**
 * @abstract return eBay upload image XML part  
 */
	function uploadImageXML( $image_url, $picture_name='Perrian' ) 
	{
		return '<ExternalPictureURL>'.$image_url.'</ExternalPictureURL>
			    <PictureName>'.$picture_name.'</PictureName>';
	}
	
}
?>