<?php 
$dateFilter = array('report_admin_log','report_customer_order','report_coupon','report_tax','report_order_return','report_order','report_gift','report_product_purchased','report_product_review','report_customer_review','report_payment','report_shipping','report_manufacturer','report_customer_account','customer_private_message','sales_order','sales_order_return','report_discount','customer','report_ring_sizer_request','report_call_me_back','report_customer_wish','report_customer_cart','report_search_terms','email_list','report_reffer_bonus','warehouse_transactions');

if(in_array($this->router->class, $dateFilter)):
?>
<script language="javascript">
$(function() {
	if($( "#from" ).size()){
		// DATEPICKER FOR FILTER
		$( "#from" ).datepicker({
		  changeMonth: true,
   	  	  dateFormat : 'dd/mm/yy',
		  maxDate: "d",
		  numberOfMonths: 2,
		  onClose: function( selectedDate ) {
			  if(selectedDate != '')
				$( "#to" ).datepicker( "option", "minDate", selectedDate );
		  }
		});
	}
	if($( "#to" ).size()){
		// DATEPICKER FOR FILTER
		$( "#to" ).datepicker({
		  defaultDate: "+1w",
		  dateFormat : 'dd/mm/yy',
		  changeMonth: true,
		  numberOfMonths: 2,
		  maxDate: "d",
		  onClose: function( selectedDate ) {
			  if(selectedDate != '')
				$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		  }
		});
	}

	<?php if($this->input->get('fromDate')){ ?>
			  $( "#to" ).datepicker( "option", "minDate", '<?php echo $this->input->get('fromDate'); ?>' );
	<?php };?>

	<?php if($this->input->get('toDate')){ ?>
			  $( "#from" ).datepicker( "option", "maxDate", '<?php echo $this->input->get('toDate'); ?>');
	<?php };?>

});
</script>
<?php endif;?>

<!-- pagination link -->
<?php if($links){ ?>
<div class="links"><?php echo $links;?></div>
<div class="results"><?php echo form_dropdown('perPage',$per_page_drop,set_value('perPage',@$this->session->userdata('perPage')),'class="perPageDropdown" onchange="perPageManage(this)"'); ?></div>
<?php }?>