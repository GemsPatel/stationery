<div id="content">
  
  <?php $this->load->view('admin/elements/breadcrumb');?>
  
  <div class="box">
    <div class="heading">
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller);?></h1>
      <div class="buttons"><a class="button" href="<?php echo site_url('admin/'.$this->controller);?>">Cancel</a></div>
    </div>
    <div class="content">
      <form id="form" enctype="multipart/form-data" method="post" action="">
      <input type="hidden" name="item_id" value=""  />
        <div id="tab-general" style="display: block;">
        <?php 
			if(is_array($res) && sizeof($res)>0):
				foreach($res as $k=>$ar):
					if(@$ar['admin_user_id'] != 0)
					{
						$name = executeQuery("SELECT CONCAT(admin_user_firstname,CONCAT(' ',admin_user_lastname)) as 'Name' FROM admin_user
						WHERE admin_user_id=".$ar['admin_user_id']." ");
						if(is_array($name) && sizeof($name)>0)
							$name = $name[0]['Name'];
					}
					else
					{
						$name = @$ar['pm_email'];
					}
		?>
        <fieldset>
            <legend><?php echo ((@$ar['admin_user_id'] != 0)?'Response From Admin: ':'').$name;?></legend>
			<table class="form">
              <tbody>
              	<tr>
                  <td>Sent on:</td>
                  <td><?php echo @$ar['pm_created_date'];?></td>
                </tr>
              	<tr>
                  <td>Name:</td>
                  <td><?php echo @$ar['pm_name'];?></td>
                </tr>
                <?php if(@$ar['pm_question'] != ''):?>
              	<tr>
                  <td>Question:</td>
                  <td><?php echo @$ar['pm_question'];?></td>
                </tr>
                <?php endif;?>
              	<tr>
                  <td>Message:</td>
                  <td><?php echo @$ar['pm_message'];?></td>
                </tr>
              	<tr>
                  <td>IP Address:</td>
                  <td><?php echo @$ar['pm_ip_address'];?></td>
                </tr>
           	  </tbody>
            </table>
        </fieldset>
		<?php
        		endforeach;
			endif;
		?>
        </div>
                
      </form>
    </div>
  </div>
  
</div>
