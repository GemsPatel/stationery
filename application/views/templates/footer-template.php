	         				<tr>
                				<td colspan="2" height="10">
                	
                				</td>
              				</tr>
              				<tr>
                				<td colspan="2" style="border-top:1px dashed #e5e5e5; border-bottom: 3px solid #002EF6;">
                					<table width="640" border="0" cellspacing="0" cellpadding="0" align="center">
                    					<tbody>
                      						<tr>
                      							<td align="left">
                      								<a href="<?php echo site_url() ?>" target="_blank">
                      									<img alt="<?php echo baseDomain() ?>" src="<?php echo asset_url('images/foot_logo.png')?>" width="150">
                      								</a>
                      							</td>
	                    						<td align="right" style="font-family:Lucida Sans Unicode, Lucida Grande, sans-serif; padding:6px;">
                        							<p style="color:#F89C2B;margin:0px;padding:0px;font-size:13px;line-height:20px">
	                        							<?php echo getLangMsg("add");?><br>
	                        							<strong>Phone:</strong> 
	                        							<?php echo getField('config_value','configuration','config_key','TOLL_FREE_NO') ?> 
                        							</p>
                            						<span style="font-size:xx-small; color:#666;"> 
                            							Click here to <a href="<?php echo site_url('home/unsubscribe?email_list_id='._en(@$email_list_id).'&email_id='._en(@$email_id)) ?>" target="_blank">Unsubscribe</a> &nbsp;
                            						</span>
                        						</td>
                      						</tr>
                    					</tbody>
                  					</table>
                				</td>
              				</tr>
            			</tbody>
          			</table>
          		</td>
			</tr>
    	</tbody>
	</table>
</div>
