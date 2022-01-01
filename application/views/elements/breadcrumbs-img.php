		<!-- BREADCRUMBS -->
        <?php 
		if(($this->router->class=="home") && ($this->router->method=="index")):
			$classH =  "";
		else:
			$classH = "margbot30"; 
		endif;
		?>
		<section class="breadcrumb parallax <?php echo $classH ?>"></section>
		<!-- //BREADCRUMBS -->