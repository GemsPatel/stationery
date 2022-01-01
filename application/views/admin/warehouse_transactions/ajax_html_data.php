<span id="loading_img_adm" style="display:none;">
<img style="padding:0;" src="<?php echo asset_url('images/preloader.gif'); ?>" alt="loader">
</span>
      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'wt_qty');
				$d = get_sort_order($this->input->get('s'),$this->input->get('f'),'wt_rate');
				$g = get_sort_order($this->input->get('s'),$this->input->get('f'),'wt_created_date');
				$e = get_sort_order($this->input->get('s'),$this->input->get('f'),'wt_status');
			  ?>
          	  <td width="1" style="text-align: center;"><!-- <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);"> --></td>
              <th class="left">Product Name</th>
              <th class="left" f="wt_qty" s="<?php echo @$c;?>">Qty</th>
              <th class="left" f="wt_rate" s="<?php echo @$d;?>">Rate</th>
              <th class="left">Total</th>
              <th class="left">Transaction Type</th>
              <th class="center" f="wt_status" s="<?php echo @$e;?>">Status</th>
              <th class="right" >Date</th>
              <td class="right">Action</td>
            </tr>
            
            <tr class="filter"> 
              <td width="1" style="text-align: center;"></td>
              <td class="left">
              	    <?php
              	    	$manArr = hewr_warehouseProductsDropDown();
						echo form_dropdown('product_filter',$manArr, @$product_filter, ' id="product_filter" style="width:150px;" ');
					?>
              </td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="right"></td>
              <td class="left">
                    <?php
						echo form_dropdown('wt_type',hewr_transactionTypes(), @$wt_type, ' id="wt_type" style="width:100px;" ');
					?>
              </td>
              <td class="right">
              	<select name="status_filter" id="status_filter">
                                    <option value="" selected="selected">All</option>
                                    <option value="0" <?php echo (@$status_filter=='0')?'selected="selected"':'';?>>Enabled</option>
                                    <option value="1" <?php echo (@$status_filter=='1')?'selected="selected"':'';?>>Disabled</option>
                </select></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker" name="fromDate" id="from" value="<?php echo (@$fromDate);?>" size="20"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>" size="20">
              </td>
              <td align="right"><a class="button" id="searchFilter">Filter</a></td>
            </tr>
            
          </thead>
          <tbody class="ajaxdata">
          <?php 
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
		            <tr id="<?php echo $ar[$this->cAutoId]?>">
		                <td style="text-align: center;"><!-- <input type="checkbox" value="<?php echo $ar[$this->cAutoId]?>" name="selected[]" class="chkbox"> --></td>
		                <td class="left"><?php echo $ar['product_name']?></td>
		                <td class="left"><?php echo $ar['wt_qty'];//." ".$ar["pv_quantity_unit"]?></td>
		                <td class="left"><?php echo lp( $ar['wt_rate'] )?></td>
		                <td class="left"><?php echo lp( round( $ar['wt_qty'] * $ar["wt_rate"], 2 ) )?></td>
		                <td class="left"><?php echo hewr_transactionTypeName($ar["wt_type"])?></td>
		                <td class="right">
		                <?php if($ar['wt_status']=='0')
		                        echo '<a id="ajaxStatusEnabled" rel="1" data-="'.$ar[$this->cAutoId].'" title="Enabled"><img src="'.asset_url('images/admin/enabled.gif').'" alt="enabled"/></a>';
		                    else
		                        echo '<a id="ajaxStatusEnabled" rel="0" data-="'.$ar[$this->cAutoId].'" title="Disabled"><img src="'.asset_url('images/admin/disabled.gif').'" alt="disabled"/></a>';
		                ?>
		                </td>
		                <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['wt_created_date']);?></td>
                        <?php
		                	if( $ar["wt_type"] == 1 ):
		                ?>
		                		<td class="right"> <?php if($this->per_edit == 0):?>[ <a href="<?php echo site_url('admin/'.$this->controller.'/wtForm?edit=true&item_id='._en($ar[$this->cAutoId]))?>">Edit</a> ] <?php endif;?> </td>
		                <?php
		                	else:
		                ?>
		                	<td class="right"></td>
		                <?php	
		                	endif;
		                ?>		
		            </tr>
		  <?php 
		  		endforeach;
		   else:
			 	echo "<tr><td class='center' colspan='8'>No results!</td></tr>";
	   	   endif; 
		  ?>
          </tbody>
        </table>
      
      <div class="pagination">
      	<?php $this->load->view('admin/elements/table_footer');?>
      </div>
	</form>