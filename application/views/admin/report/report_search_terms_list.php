<script type="text/javascript">
jQuery(document).ready(function($) {
	$('a[rel*=modal]').facebox()
})
</script>
	  <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <table class="list">
          <thead>
            <tr id="heading_tr" style="cursor:pointer;">
			  <?php
            	$a = get_sort_order($this->input->get('s'),$this->input->get('f'),'search_terms_keywords');
				$b = get_sort_order($this->input->get('s'),$this->input->get('f'),'Count');
				$c = get_sort_order($this->input->get('s'),$this->input->get('f'),'search_terms_created_date');
				
				?>
              <th width="55%" class="left" f="search_terms_keywords" s="<?php echo @$a;?>">Keywords</th>
              <th width="10%" class="right" f="Count" s="<?php echo @$b;?>">No of Searches</th>
              <th width="15%" class="right" f="search_terms_created_date" s="<?php echo @$c;?>" >Date</th>
           </tr>
           <tr class="filter">
             <td class="left" valign="top"><input type="text" size="50" name="search_keyword_filter" value="<?php echo (@$search_keyword_filter);?>"></td>
              <td class="right"></td>
              <td class="right">
                <span>From: </span><input type="text" class="datepicker"  name="fromDate" id="from" value="<?php echo (@$fromDate);?>"><br />
                <span style="padding-left:15px;">To: </span><input type="text" name="toDate" id="to" class="datepicker" value="<?php echo (@$toDate);?>">
              </td>
          </tr>
          </thead>
          <tbody>
          <?php
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
            <tr id="<?php echo $ar[$this->cAutoId]?>">
               <td class="left"><?php echo $ar['search_terms_keywords'];?></td>
               <td class="right"><a rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/viewItemCustomer?item_id='._en($ar['search_terms_keywords'])); ?>"><?php  echo $ar['Count'];?></a></td>
               <td class="right"><?php echo formatDate('d m, Y <b>h:i a</b>',$ar['search_terms_created_date']);?></td>
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


