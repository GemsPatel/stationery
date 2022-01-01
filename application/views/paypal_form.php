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
	$paypal_id='admin@perrian.com.au'; // Business email ID
if($_SERVER['HTTP_HOST']=='192.168.1.14' || $is_test)
{
	$paypal_url='https://www.sandbox.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
}
else	//Live
{
	$paypal_url='https://www.paypal.com/cgi-bin/webscr'; // Test Paypal API URL
}

$action = '';
if(!empty($_POST))
{
  foreach($_POST as $key => $value)
  {     
  	if( !is_array( $value ) )
	    $posted[$key] = htmlentities($value, ENT_QUOTES);
	else
	    $posted[$key] = $value;
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


//success and cancel url for admin and front side are separated
if(isset($_POST['surl']))
	$surl = $_POST['surl'];
else
	$surl =  site_url('checkout/orderSuccess?oid='.$order_id);	

if(isset($_POST['furl']))
	$furl = $_POST['furl'];
else
	$furl =  site_url('checkout/orderFailed?oid='.$order_id);	

if( isset( $_POST['curl'] ) )
	$curl = $_POST['curl'];
else
	$curl =  site_url( 'checkout' );	
////

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
 <body onLoad="submitPayuForm();"> 
    <!-- payGateway loader -->
    <div class="loaddiv" id="loadDiv">
	    <div>
        	<span class="payLoader">Please Wait while you are redirecting to payment gateway.</span>
            <br><br>
            <img id="loadImage" src="<?php echo load_image('images/admin/ajax-loader-bar.gif')?>"/>
        </div>
    </div>

     <form action="<?php echo $paypal_url;?>" method="post" name="payuForm">
    	<input type="hidden" name="cpp_header_image" value="<?php echo asset_url('images/logo.png'); ?>">

        <input type='hidden' name='business' value='<?php echo $paypal_id; ?>'>
        <input type='hidden' name='cmd' value='_xclick'>
	    <input type="hidden" name="no_note" value="1" />
	    <input type="hidden" name="bn" value="Perrian_BuyNow_HSS_AU" />

        <?php
			if( $is_test ):
		?>
		        <input type='hidden' name='notify_url' value='<?php echo site_url('checkout/paypalNotify')?>'>
        <?php
			endif;
		?>
        
        <input type='hidden' name='item_name' value='<?php echo $posted['productinfo']?>'>
        <input type='hidden' name='item_number' value='<?php echo $posted['txnid']?>'>	<!-- transaction ID of perrian.com -->
        <input type='hidden' name='currency_code' value='<?php echo CURRENCY_CODE?>'>
        <input type='hidden' name='amount' value='<?php echo $posted['amount']?>'>
        <input type='hidden' name='quantity' value='1'>
        <input type="hidden" name="first_name" value="<?php echo $posted['firstname']?>"/>
        <input type="hidden" name="payer_email" value="<?php echo $posted['email']?>"/>

		<!-- automatically fillup necessary information for user in PayPal gateway form--> 
        <input type='hidden' name='address1' value='<?php echo $posted['shippAddr']['customer_address_address']?>'>
        <input type='hidden' name='city' value='<?php echo $posted['shippAddr']['cityname']?>'>
        <input type='hidden' name='country' value='<?php echo $posted['shippAddr']['country_code']?>'>
        <input type='hidden' name='email' value='<?php echo $posted['email']?>'>
        <input type="hidden" name="last_name" value="<?php echo $posted['shippAddr']['customer_address_lastname']?>"/>
        <input type="hidden" name="zip" value="<?php echo $posted['shippAddr']['pincode']?>"/>
    	
        <input type='hidden' name='no_shipping' value='1'>
        <input type='hidden' name='handling' value='0'>
        <input type='hidden' name='cancel_return' value='<?php echo $curl?>'>
        <input type='hidden' name='return' value='<?php echo $surl?>'>
     
       <input type="submit" value="Submit" style="display:none"/>
    </form>
</body>