
<script language='JavaScript' type='text/javascript' src='<?php echo base_url(); ?>themes/default/js/default.js'></script>
<script language='JavaScript' type='text/javascript' src='<?php echo base_url(); ?>assets/js/LazyLoad.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/css/jquerybxslider/jquerybxslider.css'/>
<script language='JavaScript' type='text/javascript' src='<?php echo base_url(); ?>assets/js/jquerybxslider/jquerybxslider.min.js'></script>

<script>
	 function changelang(lang)
	 {
	 	 var json_url = "<?php echo base_url() ?>home/chlang/"+lang;
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: "",
			success: function(data){ 
       			location.reload();
		}});
	 	return false;
	 }
</script>
    <?php echo Assets::js(); ?>
    
</body>
</html>