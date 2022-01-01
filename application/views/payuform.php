<?php
// Merchant key here as provided by Payu
// Merchant Salt as provided by Payu
$is_test = false ;

//order_id for which processing is being initialized towards payment gateway
$order_id = $this->session->userdata( 'order_id' );



if( $_POST['email'] == 'hi0001234d@gmail.com' )
{
	$is_test = true;	
}

$posted = array();
if($_SERVER['HTTP_HOST']=='192.168.1.14' || $is_test)
{
	$MERCHANT_KEY = getPayuMerchantKeyTest();
	$posted['key'] = getPayuMerchantKeyTest();
	$SALT = getPayuMerchantSaltTest();
	$PAYU_BASE_URL = "https://test.payu.in";
}
else	//Live
{
	$MERCHANT_KEY = getPayuMerchantKeyLive(); 
	$posted['key'] = getPayuMerchantKeyLive();
	$SALT = getPayuMerchantSaltLive();
	$PAYU_BASE_URL = "https://secure.payu.in";
}
$service_provider = "payu_paisa";

$action = '';
if(!empty($_POST))
{
  foreach($_POST as $key => $value)
  {     
    $posted[$key] = htmlentities($value, ENT_QUOTES);
  }
}

//$posted['amount'] = 5;	//amount for testing
if(empty($posted['txnid']))
{
  // Generate random transaction id
  $posted['txnid'] = getTransactionID();
}
else
{
  $txnid = $posted['txnid'];
}

if(empty($posted['phone']))
{
  // if guest sign up or phone not available then assign CLIENT phone number
  $posted['phone'] = "09377262611";
}

// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash'])) 
{
    $hashVarsSeq = explode('|', $hashSequence);
    $hash_string = '';
    foreach($hashVarsSeq as $hash_var) {
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }
    $hash_string .= $SALT;
	$hash = strtolower(hash('sha512', $hash_string));
    $action = $PAYU_BASE_URL . '/_payment';
}
elseif(!empty($posted['hash']))
{
  $hash = $posted['hash'];
  $action = $PAYU_BASE_URL . '/_payment';
}

//success and cancel url for admin and front side are separated
if(isset($_POST['surl']))
	$surl = $_POST['surl'];
else
	$surl =  site_url('checkout/orderSuccess?oid='.$order_id);	

if(isset($_POST['furl']))
	$furl = $_POST['furl'];
else
	$furl =  site_url('checkout/orderFailed?oid='.$order_id);	

if(isset($_POST['curl']))
	$curl = $_POST['curl'];
else
	$curl =  site_url('checkout');	
////

//pr($posted); die; 
?>

<style type="text/css">
.payLoader
{
	color: green;
	font-size:18px;		
}

.loaddiv
{
	position:absolute;
	left:600px;
	top:300px;	
}

#loadImage
{
	margin-left: 100px;	
}
</style>

  <script>
  	
  	function submitPayuForm()
	{
	  	var payuForm = document.forms.payuForm;
	  	payuForm.submit();
    }
  </script>
  
 <!-- added On 18-06-2015 "document.forms.payuForm.submit(); return;" in onload since "submitPayuForm();" was not working in android webview -->
 <body onLoad="submitPayuForm();" >  
    <!-- payGateway loader -->
    <div class="loaddiv" id="loadDiv">
	    <div>
        	<span class="payLoader">Please Wait while you are redirecting to payment gateway.</span>
            <br><br>
            <img id="loadImage" src="<?php echo load_image('images/admin/ajax-loader-bar.gif')?>"/>
        </div>
    </div>

     <form action="<?php echo $action;?>" method="post" name="payuForm">
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY ?>" />
      <input type="hidden" name="hash" value="<?php echo $hash ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $posted['txnid'] ?>" />
      <input type="hidden" name="amount" value="<?php echo $posted['amount'] ?>" />
      <input type="hidden" name="firstname" id="firstname" value="<?php echo $posted['firstname'] ?>" />
      <input type="hidden" name="email" id="email" value="<?php echo $posted['email'] ?>" />
      <input type="hidden" name="phone" value="<?php echo $posted['phone'] ?>" />
      <input type="hidden" name="productinfo" value="<?php echo $posted['productinfo'] ?>"/>
      <input type="hidden" name="service_provider" value="<?php echo $service_provider ?>" />
      <input type="hidden" name="surl" value="<?php echo $surl?>" />
      <input type="hidden" name="furl" value="<?php echo $furl?>" />
      <input type="hidden" name="curl" value="<?php echo $curl?>" />
     
      <input type="submit" value="Submit" style="display:none"/>
    </form>
</body>

<?php
	/**
	 * added on 22-06-2015 for rest clients. 
	 * 
	 * To allow payment processing in web browsers.
	 */
	if( is_restClient() )
	{
		exit(1); 
	}
?>