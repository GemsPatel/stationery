<!-- top-middle --> 
<style>
.box-category{
	padding:0px !important;
	margin-top:20px;
}
.box-category p {
	color: #582802;
	margin-bottom: 15px;
	line-height: 25px;
}
</style>
<?php 
	
?>
<div class="contain">
  <?php $this->load->view('elements/notifications'); ?>
  <div class="center-other">
    <div class="top-inner-nav">
      <ul>
        <li style="background-image:none;padding-right:5px;padding-left:1px;"><a href="<?php echo site_url()?>">Home</a></li>
        <li><a href="<?php echo site_url('account') ?>">Account</a></li>
        <li><?php echo pgTitle(end($this->uri->segments)); ?></li>
      </ul>
    </div>
    <?php $contactAlias = getField('article_alias', 'article', 'article_key', 'CONTACT_US'); ?>
    <div class="box-category">
        <h3 class="tradus-billing-heading" style="font-size:24px;"><?php echo getLangMsg("yahbc");?></h3>
		<p style="color:#963;"><?php echo getLangMsg("checkemail");?></p>
        
      	<p><?php echo getLangMsg("thanks");?><a href="<?php echo site_url('p/common/about-us/'.$contactAlias)?>" style="color:#999999;"><?php echo getLangMsg("cu");?></a> </p>
    </div>
    <div class="clear"></div>
    <input type="submit" style="float:right; margin-right:5px;" class="button1" value="Continue Shopping" onclick="window.location.href='<?php echo site_url()?>'">
  </div>
  
  <div class="clear"></div>
  
</div>
