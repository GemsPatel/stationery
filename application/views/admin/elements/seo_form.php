<?php $class = (!empty($class) && $class=="hide") ? 'hide': 'show';?>
<table class="form <?php echo $class ?>">
<tbody>
  <tr>
    <td> Custom Page Title:</td>
    <td><input type="text" size="38" name="custom_page_title" value="<?php echo (@$custom_page_title)?$custom_page_title: @$_POST['custom_page_title'];?>">
    	<span class="error_msg"><?php echo (@$error)?form_error('custom_page_title'):''; ?> </span>
    </td>
  </tr>
  <tr>
    <td> Meta Tag Description:</td>
    <td><textarea rows="4" cols="35" name="meta_description"><?php echo (@$meta_description)?$meta_description: @$_POST['meta_description'];?></textarea>
    	<span class="error_msg"><?php echo (@$error)?form_error('meta_description'):''; ?> </span>
    </td>
  </tr>
  <tr>
    <td>  Meta Tag Keywords:</td>
    <td><textarea rows="4" cols="35" name="meta_keyword"><?php echo (@$meta_keyword)?$meta_keyword: @$_POST['meta_keyword'];?></textarea>
    	<span class="error_msg"><?php echo (@$error)?form_error('meta_keyword'):''; ?> </span>
        <small class="small_text">Enter comma(,) between two meta keywords. </small>
    </td>
  </tr>
  <tr>
    <td> Robots:</td>
    <td>
   		<?php 
		  $setRobots = (@$robots) ? $robots : @$_POST['robots'];
		  $sql = "SELECT robots_id, robots_name FROM seo_robots WHERE robots_status=0";
		  $robotsArr = getDropDownAry($sql,"robots_id", "robots_name", array(), false);
		  echo form_dropdown('robots',@$robotsArr,@$setRobots,'class=""');
		?>
    </td>
  </tr>
  <tr>
    <td>  Author:</td>
   <td><input type="text"  size="38" name="author" value="<?php echo (@$author)?$author: @$_POST['author'];?>">
   		<span class="error_msg"><?php echo (@$error)?form_error('author'):''; ?> </span>
   </td>
  </tr>
  <tr>
    <td>  Content Rights:</td>
    <td><textarea rows="4" cols="35" name="content_rights"><?php echo (@$content_rights)?$content_rights: @$_POST['content_rights'];?></textarea>
    	<span class="error_msg"><?php echo (@$error)?form_error('content_rights'):''; ?> </span>
    </td>
  </tr>   
</tbody>
</table>