<style type="text/css">
/* -- MODAL STYLES ----------- */
ul.menu_types {
	padding: 0 0 0 15px;
	width: 750px;
	margin: 0;
}

ul.menu_types li,
dl.menu_type dd ul li {
	width: 240px;
	list-style: none;
	display: block;
	float: left;
	margin-right: 10px;
}


dl.menu_type {
	width: 240px;
	margin: 0;
	padding: 0;
}

dl.menu_type dt {
	font-weight: bold;
	font-size: 1.091em;
	float: left;
	margin: 13px 0 5px 0;
	border-bottom: 1px solid #666;
	width: 200px;
}

dl.menu_type dd {
	clear: left;
	margin: 0;
}

dl.menu_type dd ul li {
	margin: 0;
	padding:2px;
}

dl.menu_type dd ul {
	margin-left: -40px;
}

</style>

     <ul class="menu_types">
     		<?php
            	if(is_array($res) && sizeof($res)>0):
					foreach($res as $k=>$ar):
			?>
            <li>
            <dl class="menu_type">
                <dt><?php echo @$ar['name']; ?></dt>
                <dd>
                    <ul>
                    	<?php foreach($ar['data'] as $key=>$val):?>
                    	<form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/popupPageForm')?>">
                        <input type="hidden" name="hidden_page_param" value="<?php echo @$ar['name']; ?>|<?php echo @$ar['table']; ?>|<?php echo @$ar['field']; ?>|<?php echo $key;?>" />
                        <input type="hidden" name="item_id" value="<?php echo isset($item_id)?@$item_id:''; ?>" />
                        <input type="hidden" name="m_id" value="<?php echo isset($m_id)?@$m_id:''; ?>" />
                        <li><a onclick="return submitForm(this);" title="" href="javascript:void(0);" class="choose_type"><?php echo $val;?></a></li>
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>
			<?php
					endforeach;
				endif;
            ?>
	</ul>
<script language="javascript">
function submitForm(obj)
{
	$(obj).parents('form').submit();
}
</script>

