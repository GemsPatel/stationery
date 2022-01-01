      <input type="hidden" id="hidden_srt" value="<?php echo @$srt; ?>" />
      <input type="hidden" id="hidden_field" value="<?php echo @$field; ?>" />
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <input type="hidden" name="item_id" value="<?php echo (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : ''; ?>"  />
      <input type="hidden" name="m_id"  value=""  />
      <table class="list">
          <thead>
			<tr id="heading_tr" style="cursor:pointer;">
              <td class="left" width="40%">Site Name</td>
			  <td class="left" width="40%">Language</td>
              <td class="left" width="20%">Action</td>
            </tr>
            
          </thead>
          <tbody class="ajaxdata">
          <?php 
		  	$extra = "";			
	    	if(count($listArr)):
				foreach($listArr as $k=>$ar):
		  ?>
		            <tr id="m_<?php echo $ar['manufacturer_id']?>">
		                <td class="left"><?php echo $ar['item_name']?></td>
		                <td class="left"><?php echo $ar['manufacturer_name']?></td>
		                <td class="left">[ <a href="<?php echo site_url('admin/'.$this->controller.'/categoryForm?edit=true&item_id='._en($ar['item_id']).'&'.changeLangUriParams($ar['manufacturer_id'], $ar['manufacturer_key']))?>">Edit in <?php echo $ar['manufacturer_name'] ?></a> ] </td>
		            </tr>
          
		  <?php 
		  		endforeach;
		  	else:
				echo "<tr><td class='center' colspan='6'>No results!</td></tr>";
	   	   	endif; 
		  ?>
          </tbody>
        </table>
	</form>
