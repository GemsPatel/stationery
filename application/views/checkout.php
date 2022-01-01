<!-- CHECKOUT PAGE -->
<section class="checkout_page mt20">
	
	<!-- CONTAINER -->
	<div class="container">

	<?php
		$active_step = 1;
		$chk["is_logged_in"] = false;
		$chk["is_shipp_info_set"] = false;
		if( !empty( $chk["customer_id"] ) )
		{
			$active_step = 2; 
			$chk["is_logged_in"] = true;
			if( !empty( $chk["customer_shipping_address_id"] ) && !empty( $chk["customer_billing_address_id"] ) && $chk["is_shipping_valid"] )
			{
				$active_step = 3;
				$chk["is_shipp_info_set"] = true;
			}
		}

	?>
	
		<!-- CHECKOUT BLOCK -->
		<div class="checkout_block">
			<ul class="checkout_nav">
				<li class="<?php echo ( $active_step > 2 ? 'done_step2' : ($active_step > 1 ? 'done_step' : 'active_step') );?> ">1. <?php echo getLangMsg("u_info");?></li>
				<li class="<?php echo ( $active_step > 2 ? 'done_step' : ( $active_step == 2 ? 'active_step' : '' ) );?>">2. <?php echo getLangMsg("shipadd");?></li>
				<li class="<?php echo ( $active_step == 3 ? 'active_step' : '' );?>  last">3. <?php echo getLangMsg("payment");?></li>
			</ul>
	

			<?php
			if( !$chk["is_logged_in"] || $this->input->get("act") == "uinfo" )
			{
				$this->load->view('checkout-1', $chk);
			}
			else if( !$chk["is_shipp_info_set"] || ( $chk["is_logged_in"] && $this->input->get("act") == "sinfo" ) )
			{
				$this->load->view('checkout-2', $chk);
			}
			else if( ( $chk["is_logged_in"] && $chk["is_shipp_info_set"] ) || 
					 ( $chk["is_logged_in"] && $chk["is_shipp_info_set"] && $this->input->get("act") == "pinfo" ) )
			{
				$chk['customer_address_id'] = $chk["customer_shipping_address_id"];
				$this->load->view('checkout-3', $chk);
			}
			?>
            
        </div><!-- //CHECKOUT -->

	</div><!-- //CONTAINER -->
</section>
<!-- //CHECKOUT PAGE -->
        
<script type="text/javascript">
var shipp_add_id= <?php echo (int)$chk["customer_shipping_address_id"]; ?>;
var bill_add_id= <?php echo (int)$chk["customer_billing_address_id"]; ?>;
</script> 
        
<script type="text/javascript" src="<?php echo asset_url('js/checkout_js.js')?>"></script>        
       