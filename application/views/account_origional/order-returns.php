
<div class="contain">
  
  <?php $this->load->view('elements/notifications'); ?>
  
  <div class="center-other">
     <?php $this->load->view('account/breadcrub') ?>
    
    <div class="headding-catagory"><?php echo getLangMsg("m_a");?></div>
    <div class="TabbedPanelsContent">            
            <div class="addressPage-container">
        
                <div class="tradus-address-holder" style="float:left;width:74%;font-size:13px;">
                    <div class="tradus-form-container shipping-form" id="NewShippingAddress">
                        
                        <h3 class="tradus-add-address-header box_header_title"><?php echo getLangMsg("o_ret");?></h3>
                        <form method="post" action="">
                        <div class="tradus-address-holder" style="float:left;width:100%;font-size:13px;">
                            <div class="tradus-form-container shipping-form" id="NewShippingAddress">                        
                                <div id="toggleAddresses" class="toggleAddresses-previous">
                                     <div class="box-content">
                                        <div class="box-category" style="padding:0px;">
                                            <table>
                                              <thead>
                                                <tr>
                                                  <td class="" width="15%"><?php echo getLangMsg("o_id");?></td>
                                                  <td class="" width="35%"><?php echo getLangMsg("pname");?></td>
                                                  <td class="image" width="10%"><?php echo getLangMsg("status");?></td>
                                                  <td class="totalp" width="15%"><?php echo getLangMsg("price");?></td>
                                                  <td class="totalp" width="20%"><?php echo getLangMsg("dt")?></td>
                                                </tr>
                                              </thead>
                                            <?php
                                                $is_order = false;
                                                if(is_array(@$listArr) && sizeof($listArr)>0):
                                            ?>
                                              <?php			
                                                      $is_order = true;
                                                      foreach($listArr as $k=>$ar):
													  
													  $angle_in = ANGLE_IN;
													  if($ar['product_accessories']=='BAN' || $ar['product_accessories']=='BRA')
													  {
														  $angle_in = 0;	
													  }
                                              ?>
                                              <tbody class="submenuheader">
                                                <tr id="row_<?php echo $ar['product_price_id'];?>">
                                                  <td class=""><?php echo $ar['order_id'];?></td>
                                                  <td class="action" style="padding:2px;float:left;">
                                                      <a style="cursor:pointer;"><img title="Click here to view" alt="view" src="<?php echo asset_url(${'product_images_'.$ar['product_price_id']}[$angle_in])?>"></a>
                                                  </td>
                                                  <?php
                                                        $res = executeQuery("SELECT order_status_name FROM order_status WHERE order_status_id=".$ar['order_status_id']."");
                                                  ?>
                                                  <td class="image"><?php echo $res[0]['order_status_name'];?></td>
                                                  <td class="totalp chan_curr"><?php echo lp($ar['order_details_amt']);?></td>
                                                  <td class="totalp"><?php echo $ar['order_return_created_date'];?></td>
                                                </tr>   
                                              </tbody>  
                                            <?php
                                                      endforeach;
                                            ?>
                                            <?php		  
        
        
                                                else:
                                            ?>
                                                <tr>
                                                    <td colspan="5"><i class="fa fa-thumbs-o-down"></i>&nbsp;&nbsp;You haven't returned any order yet.</td>
                                                </tr>
                                            <?php
                                                endif;
                                            ?>
                                            </table>	
                                             <?php if($links){?>
                                             <div class="pagination" style="padding:7px;">
                                                <div class="links"><?php echo $links;?></div>
                                             </div>
                                             <?php }?>
                                            
                                        </div>                                
                                      </div>
                                      <div class="clear"></div>
                                      <div class="proceed-addressPage" style="margin-top:3px;">
                                          <input type="button" value="Back" class="button1" style="float:left;" onclick="window.location.href='<?php echo site_url('account')?>'">
                                      </div>                          
                                </div> 
                            </div> <!-- [/shipping-form] -->        
                        </div> <!-- [/address-holder] -->
                        </form>

                    </div> <!-- [/shipping-form] -->
                </div> <!-- [/address-holder] -->
                
				<?php $this->load->view('account/rightbar_box'); ?>
        
            </div>
          </div>
    
    <div class="clear"></div>
  </div>
    
</div>