<div id="content">
  
  <div class="breadcrumb">
		<a href="<?php echo site_url();?>">Home</a>
  </div>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> Dashboard</h1>
    </div>
    <div class="content">
      <div class="overview">
        <div class="dashboard-heading">Overview</div>
        <div class="dashboard-content">
          <table>
            <tr>
              <td>Total Sales:</td>
              <td><?php echo lp(@$total_sales['order_total_amt']) ?></td>
            </tr>
            <tr>
              <td>Total Sales This Year:</td>
              <td><?php echo lp(@$total_sales_year['order_total_amt']); ?></td>
            </tr>
            <tr>
              <td>Total Orders:</td>
              <td><?php echo @$total_orders; ?></td>
            </tr>
            <tr>
              <td>No. of Customers:</td>
              <td><?php echo @$total_customers; ?></td>
            </tr>
            <tr>
              <td>Customers Awaiting Approval:</td>
              <td><?php echo @$customer_await_approval; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div class="statistic">
        <div class="dashboard-heading">Top 5 Search Terms</div>
        <div class="dashboard-content">
          <table class="list">
            <thead>
              <tr>
                <td class="left">Search Term</td>
                <td class="right">No Of Search</td>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($top_search_terms)): foreach($top_search_terms as $searchVal){?>
              <tr>
                <td class="left"><?php echo $searchVal['search_terms_keywords']; ?></td>
                <td class="right"><?php echo $searchVal['top5search']; ?></td>
              </tr>
            <?php } else: ?>
              <tr>
                <td class="center" colspan="2">No results!</td>
              </tr>
            <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      
      <div class="latest">
        <div class="dashboard-heading">Latest 10 Orders</div>
        <div class="dashboard-content">
          <table class="list">
            <thead>
              <tr>
                <td class="right">Order ID</td>
                <td class="left">Customer</td>
                <td class="left">Email</td>
                <td class="left">Phone No.</td>
                <td class="left">Payment Method</td>
                <td class="left">Date Added</td>
                <td class="right">Total</td>
              </tr>
            </thead>
            <tbody>
            <?php if(!empty($latest_ten_orders)): foreach($latest_ten_orders as $orderVal){?>
              <tr>
                <td class="right"><a href="<?php echo site_url('admin/sales_order/salesOrderForm?edit=true&item_id='._en(@$orderVal['order_id']).'&custid='._en(@$orderVal['customer_id']).' '); ?>"><?php echo $orderVal['order_id']; ?></a></td>
                <td class="left"><a href="<?php echo site_url('admin/customer/customerForm?edit=true&item_id='._en(@$orderVal['customer_id']).' '); ?>"><?php echo @$orderVal['customer_firstname'].' '.@$orderVal['customer_lastname']; ?></a></td>
                <td class="left"><?php echo $orderVal['customer_emailid']; ?></td>
                <td class="left"><?php echo $orderVal['customer_phoneno']; ?></td>
                <td class="left"><?php echo $orderVal['payment_method_name']; ?></td>
                <td class="left"><?php echo formatDate('d m, Y <b>h:i a</b>',$orderVal['order_created_date']); ?></td>
                <td class="right"><?php echo lp($orderVal['order_total_amt']); ?></td>
              </tr>
            <?php } else: ?>
              <tr>
                <td class="center" colspan="7">No results!</td>
              </tr>
            <?php endif;?>
            </tbody>
          </table>
        </div>
      </div>
      
    </div>
  </div>
  
</div>