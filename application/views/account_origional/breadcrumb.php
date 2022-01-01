
		<!-- PAGE HEADER -->
		<section class="page_header">
			
			<!-- CONTAINER -->
			<div class="container account">
				<h3 class="pull-left"><b>
				<ul>
                    <li><a href="<?php echo site_url()?>">Home <i class="fa fa-angle-right"></i></a></li>
                    <li><a href="<?php echo site_url($this->router->class)?>"><?php echo pgTitle($this->router->class); ?> <i class="fa fa-angle-right"></i></a></li>
                    <li><?php echo pgTitle(end($this->uri->segments)); ?></li>
                </ul>
                </b></h3>
				
				<div class="pull-right">
					<a href="javascript:history.back();" ><?php echo getLangMsg("bts");?><i class="fa fa-angle-right"></i></a>
				</div>
			</div><!-- //CONTAINER -->
		</section><!-- //PAGE HEADER -->
        