<div id="content">
  <?php $this->load->view('admin/elements/breadcrumb');?>
  <div class="box">
    <div class="heading"> 
      <h1><img alt="<?php echo $this->controller ?>" src="<?php echo getMenuIcon($this->controller)?>" height="22"> <?php echo pgTitle($this->controller)?></h1>
      <div class="buttons"><a class="button" onclick="$('#form').submit();">Save</a><a class="button" href="<?php echo site_url('admin/'.$this->controller).'?item_id='._en(@$this->cPrimaryId).'&m_id=';?>">Cancel</a></div>
    </div>
    <div class="content">
      <?php 
	  		$item_id = (@$this->cPrimaryId != '') ? _en(@$this->cPrimaryId) : '';
	  		$m_id = (@$this->cPrimaryIdM != '') ? _en(@$this->cPrimaryIdM) : '';
	  ?>
      <form id="form" enctype="multipart/form-data" method="post" action="<?php echo site_url('admin/'.$this->controller.'/menuItemForm')?>">
      <input type="hidden" name="item_id" value="<?php echo @$item_id; ?>"  />
      <input type="hidden" name="m_id" value="<?php echo  @$m_id?>"  />
      <input type="hidden" name="hidden_page_param" value="<?php echo (isset($hidden_page_param)) ? _en(@$hidden_page_param) : ((isset($_POST['hidden_page_param']))?_en($_POST['hidden_page_param']):''); ?>"  />
        <div id="tab-general" style="display: block;">
        <fieldset>
            <legend>Menu Items</legend>
			<table class="form">
              <tbody>
              <tr>
                <td><span class="required">*</span> Menu Item Type:</td> 
                <td><input type="text" readonly="readonly" style="background-color:#DDD;" name="front_menu_item_type" value="<?php echo (isset($hidden_page_param))?substr($hidden_page_param,0,strpos($hidden_page_param,"|")):(isset($_POST['hidden_page_param'])?substr($_POST['hidden_page_param'],0,strpos($_POST['hidden_page_param'],"|")):'');?>"  />&nbsp;&nbsp;<a class="button" rel="modal" href="<?php echo site_url('admin/'.$this->controller.'/popupSitePages?item_id='.@$item_id.'&m_id='.@$m_id)?>">Select</a>
				<span class="error_msg"><?php echo (@$error)?form_error('front_menu_item_type'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Menu Name:</td>
                <td><input type="text" name="front_menu_name" value="<?php echo (@$front_menu_name)?$front_menu_name:set_value('front_menu_name');?>">
				<span class="error_msg"><?php echo (@$error)?form_error('front_menu_name'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td><span class="required">*</span> Controller:</td>
                <td>
                <?php
					$front_hook_alias = (@$front_hook_alias)?$front_hook_alias:set_value('front_hook_alias');
					$sql = "SELECT front_hook_alias,front_hook_name FROM front_hook WHERE front_hook_status=0 AND front_hook_type='C'";
					$hookArr = getDropDownAry($sql,"front_hook_alias","front_hook_name",array(''=>'-Select Controller-'),false);
					echo form_dropdown('front_hook_alias',$hookArr,@$front_hook_alias,'');
				?>
				<span class="error_msg"><?php echo (@$error)?form_error('front_hook_alias'):''; ?></span>
                </td>
              </tr>
              <tr>
                <td>Parent Name:</td>
                <td>
				<?php 
					$setval =(@$fm_parent_id)? $fm_parent_id:@$_POST['fm_parent_id'];
					echo form_dropdown('fm_parent_id',getMultiLevelFrontMenuDropdown(@$this->cPrimaryId),@$setval,'style="width:160px;"');
				?>
                </td>
              </tr>
              <tr>
                <td>Override Url Apps:</td>
                <td><input type="text" value="<?php echo (@$fm_static_url_restapp)?$fm_static_url_restapp:@$_POST['fm_static_url_restapp'];?>" name="fm_static_url_restapp" size="25"> 
              	</td>
              </tr>
              <tr>
                  <td><span class="required">*</span> Icon:</td>
                  <td valign="top">
                     <div class="image" style="padding:5px;" align="center">
                         <?php
                         $url = (@$fm_icon) ? $fm_icon : ((@$_POST['fm_icon']) ? $_POST['fm_icon'] : asset_url('images/admin/no_image.jpg')); ?>
                         <img src="<?php echo asset_url($url);?>" width="35" height="35" id="catPrevImage_00" class="image" style="margin-bottom:0px;padding:3px;" /><br />
                         <input type="file" name="fm_icon_file" id="catImg_00" onchange="readURL(this,'00');" style="display: none;">
                         <input type="hidden" value="<?php echo (@$fm_icon) ? $fm_icon : @$_POST['fm_icon'];?>" name="fm_icon" id="hiddenCatImg" />
                         <div align="center">
                            <small><a onclick="$('#catImg_00').trigger('click');">Browse</a>&nbsp;|&nbsp;<a style="clear:both;" onclick="javascript:clear_image('catPrevImage_00')"; >Clear</a></small>
                         </div>
                    </div>
                    
                    <span class="error_msg"><?php echo (@$error)?form_error('fm_icon'):''; ?> </span>
            	</td>
              </tr>
              <tr>
                <td> Size:</td>
                <td><?php
						$setval =(@$image_size_id)? $image_size_id:@$_POST['image_size_id'];
					  	echo getImageSizeDropdown($setval); 
					?>
                </td>
              </tr>
              <tr>
                <td>Sort Order:</td>
                <td><input type="text" size="3" name="fm_sort_order" value="<?php echo (@$fm_sort_order)?$fm_sort_order:@$_POST['fm_sort_order'];?>"> 
              	</td>
              </tr>
              <tr>
                  <td>Is Display:</td>
                  <td>
                     <select name="is_display">
                       <option value="0" selected="selected">All</option>
                       <option value="1" <?php echo (@$is_display=='1' || @$_POST['is_display']=='1')?'selected="selected"':'';?>>Desktop</option>
                       <option value="2" <?php echo (@$is_display=='2' || @$_POST['is_display']=='2')?'selected="selected"':'';?>>Mobile</option>  
                     </select>
                  </td>
                </tr>
              <tr>
                <td>Status:</td>
                <td><select name="fm_status">
                    <option value="0" selected="selected">Enable</option>
                    <option value="1" <?php echo (@$fm_status=='1' || @$_POST['fm_status']=='1')?'selected="selected"':'';?>>Disable</option>
                    </select>
                </td>
              </tr>
            </tbody>
            </table>
            
        </fieldset>
                    
        <!--SEO-->
        <fieldset>
        <legend>SEO</legend>
            <?php $this->load->view('admin/elements/seo_form');?>
        </fieldset>

        </div>
        
               
        
      </form>
    </div>
  </div>
  
</div>



