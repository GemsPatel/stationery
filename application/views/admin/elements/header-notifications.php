<?php
	$cnt=0;
	$size = sizeof($notif_data);
	$heading = '';
	if($type=='orders_notif'):
?>
        <?php
        	if(isset($notif_data) && $size>0):
				$cnt=0;
				$heading = ($size>3)?'Last 3 orders':'Last '.$size.' orders';
		?>
        <h3><?php echo $heading;?></h3>
        		<table>
        <?php
				foreach($notif_data as $k=>$ar):
					$cnt++;
					if($cnt>3) break;
		?>
                    <tr>
                        <td>
                            <p style="float:left;"><a target="blank" href="<?php echo site_url('admin/sales_order/printInvoice?item_id='._en($ar['order_id']))?>">New Order placed by <?php echo $ar['customer_firstname']; ?></a></p>
                        </td>
                    </tr>
			<?php
				endforeach;                
            ?>
            	</table>
                <p><a href="<?php echo site_url('admin/sales_order')?>">Show all orders</a></p>
		<?php
        	else:
		?>
        <h3>No new orders</h3>
                <p class="no_notifs">No new orders has been placed on your shop</p>
<?php
			endif;
	elseif($type=='customers_notif'):
?>
        <?php
        	if(isset($notif_data) && $size>0):
				$cnt=0;
				$heading = ($size>3)?'Last 3 customers':'Last '.$size.' customers';
		?>
        <h3><?php echo $heading;?></h3>
        		<table>
        <?php
				foreach($notif_data as $k=>$ar):
					$cnt++;
					if($cnt>3) break;
		?>
                    <tr>
                        <td>
                            <p style="float:left;"><a href="<?php echo site_url('admin/customer/customerForm?edit=true&item_id='._en($ar['customer_id']))?>">New customer <?php echo $ar['name']; ?> has been registered as <?php echo $ar['customer_group_name']; ?></a></p>
                        </td>
                    </tr>
			<?php
				endforeach;                
            ?>
            	</table>
		        <p><a href="<?php echo site_url('admin/customer')?>">Show all customers</a></p>
		<?php
        	else:
		?>
        <h3>No new customers</h3>
		        <p class="no_notifs">No new customers registered on your shop</p>
<?php
			endif;
	elseif($type=='customer_messages_notif'):
?>
        <?php
        	if(isset($notif_data) && $size>0):
				$cnt=0;
				$heading = ($size>3)?'Last 3 messages':'Last '.$size.' messages';
		?>
        <h3><?php echo $heading;?></h3>
        		<table>
        <?php
				foreach($notif_data as $k=>$ar):
					$cnt++;
					if($cnt>3) break;
		?>
                    <tr>
                        <td>
                            <p style="float:left;"><a href="<?php echo site_url('admin/customer_private_message/viewPrivateMsgDetails?pm_email='._en($ar['pm_email']))?>">New Message from <?php echo $ar['customer_firstname']; ?> with subject <?php echo $ar['pm_question']; ?></a></p>
                        </td>
                    </tr>
			<?php
				endforeach;                
            ?>
            	</table>
		        <p><a href="<?php echo site_url('admin/customer_private_message')?>">Show all messages</a></p>
		<?php
        	else:
		?>
        <h3>No new messages</h3>
		        <p class="no_notifs">No new messages posted on your shop</p>
<?php
			endif;
	endif;
?>