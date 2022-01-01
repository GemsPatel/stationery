<script type="text/javascript">
		
	$(document).ready(function(e) {
         $('#button').click(function(e) {
              $('#image').show();
           });
			$('#image').hide();
        });
</script>
	
    <form id="import_form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/backupForm')?>">
        <fieldset>
            <legend>Backup</legend>
			<table class="form">
              <tbody>
              	<tr>
               	 <td class="left" width="20%">
                 <?php
				  $host = $_SERVER['HTTP_HOST'];
				 
				  ?>
                  <select name="select_db">
                         <option value="<?php echo ($host == '192.168.1.14') ? 'vjv' : 'perrian_perry'; ?>" selected="selected">Main Database</option>
                         <option value="perrian_geo" <?php echo (@$select_db=='perrian_geo' || @$_POST['select_db']=='perrian_geo')?'selected="selected"':'';?>>Geolocation Database</option>
                  </select>
                  </td>
                  <td class="left" width="10%"><a class="button" onclick="$('#import_form').submit();">Download Backup</a></td>
                  
                  <td class="left" width="10%"></td>
                  <td width="60%"></td>
                </tr>
           	  </tbody>
            </table>            
        </fieldset>
    </form>
    
    <form id="export_form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/restoreForm')?>" onclick="javascript:load_image();">
        <fieldset>
            <legend>Restore</legend>
            <table class="form">
              <tbody>
              	<tr> 
                <td class="left" width="10%">Select  Database</td>
                <td class="left" width="20%">
                  <select name="select_db">
                         <option value="<?php echo ($host == '192.168.1.14') ? 'vjv_geo' : 'perrian_geo'; ?>" selected="selected">Main Database</option>
                         <option value="perrian_geo" <?php echo (@$select_db=='perrian_geo' || @$_POST['select_db']=='perrian_geo')?'selected="selected"':'';?>>Geolocation Database</option>
                  </select>
                  </td>
                 </tr>
                
                 <tr>
                  <td class="left" width="10%">Select File to  Export</td>
                  <td class="left" width="20%"><input type="file" name="export_file" /></td>
                  </tr> 
                  <tr>
                  <td class="left" width="40%">
                  		<a class="button"  id="button" onclick="$('#export_form').submit();">Restore Database</a>
                         <span  style="display:none; float:right; padding-top:4px" id="image"><img  src="<?php echo  load_image('images/admin/ajax-loader.gif'); ?>" />  </span> 
                         
                  </td>
                  <td width="80%"></td>
                </tr>
               </tbody>
            </table> 
                           
        </fieldset>
    </form>
  
    
