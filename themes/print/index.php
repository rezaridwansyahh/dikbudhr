<?php Template::block('header', 'parts/head'); ?>
<?php

	//echo Template::message();
	echo isset($content) ? $content : Template::content();
?>

<?php echo theme_view('parts/_footer'); ?>
