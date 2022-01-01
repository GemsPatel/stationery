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
            <li>
            <dl class="menu_type">
                <dt>Articles</dt>
                <dd>
                    <ul>
                    	<?php foreach($articleArr as $k=>$va):?>
                        <form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="article|article_id|<?php echo $va['article_id'];?>" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);"><?php echo $va['article_name'];?></a></li>
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>
            
            <li>
            <dl class="menu_type">
                <dt>Banners</dt>
                <dd>
                    <ul>
                    	<?php foreach($bannersArr as $k1=>$val):?>
                        <form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="banner|banner_id|<?php echo $val['banner_id'];?>" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);"><?php echo $val['banner_name']?></a></li>
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>
            
            <li>
            <dl class="menu_type">
                <dt>Menu</dt>
                <dd>
                    <ul>
                    	<?php foreach($menuArr as $key=>$vm):?>
                        <form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="front_menu_type|front_menu_type_id|<?php echo $vm['front_menu_type_id'];?>" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);"><?php echo $vm['front_menu_type_name'];?></a></li>                        
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>

            <li>
            <dl class="menu_type">
                <dt>Product Categories</dt>
                <dd>
                    <ul>
                    	<?php foreach($catArr as $key=>$vm):?>
                        <form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="product_categories|category_id|<?php echo $key;?>" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);"><?php echo $vm;?></a></li>                        
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>

            <li>
            <dl class="menu_type">
                <dt>Design Modules</dt>
                <dd>
                    <ul>
                    	<?php foreach($elementsArr as $key=>$vm):?>
                        <form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="front_hook|front_hook_alias|<?php echo $vm['front_hook_alias'];?>" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);"><?php echo $vm['front_hook_name'];?></a></li>                        
                        </form>
                    	<?php endforeach;?>
                    </ul>
                </dd>
            </dl>
        	</li>
											
            <li>
            <dl class="menu_type">
                <dt>Search Filter</dt>
                <dd>
                    <ul>
                    	<form method="post" action="<?php echo site_url('admin/'.$this->router->class.'/addModuleForm')?>">
                        <input type="hidden" name="hidden_module_param" value="filters|filters_id|-1" />
                        <li><a onclick="javascript:submitModule(this);" href="javascript:void(0);">Filter</a></li>                    	
                        </form>
                    </ul>
                </dd>
            </dl>
        	</li>
            
            
        </ul>

<script language="javascript">
function submitModule(obj)
{
	$(obj).parents('form').submit();
}
</script>