<?php
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);
?>
<?php echo theme_view('partials/_header'); ?> 
<div class="content-wrapper">
	<section class="content-header">
	<?php 
		if($view_back_button){
			echo "<a href='".$back_button_url."' class='btn btn-warning'>".$back_button_label."</a>";
		}
	?>
   	<h1>        
        <small><?php if (isset($toolbar_title)) : ?>
				  <?php echo $toolbar_title ?>
			  <?php endif; ?></small>
      </h1>
     
  	<ol class="breadcrumb">
	  <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	  <li class="active"><a href="#"> <?php if (isset($toolbar_title)) : ?>
				  <?php echo $toolbar_title ?>
			  <?php endif; ?></a></li>
	</ol>
	
	</section> 
	 
	<section class="content">
		  <?php echo Template::message(); ?>
		<?php echo isset($content) ? $content : Template::content(); ?>
	</section>
</div>	
<?php echo theme_view('partials/_footer'); ?>
